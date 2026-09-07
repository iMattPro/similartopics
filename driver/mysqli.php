<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\driver;

/**
 * This class handles similar topics queries for MySQLi dbms
 */
class mysqli implements driver_interface
{
	const OWNED_INDEX_CONFIG = 'pst_mysql_owned_index';
	const ORIGINAL_ENGINE_CONFIG = 'pst_mysql_original_engine';

	/** @var \phpbb\db\driver\driver_interface */
	protected \phpbb\db\driver\driver_interface $db;

	/** @var \phpbb\config\config|null */
	protected $config;

	/** @var string */
	protected string $engine;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\config\config|null $config
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config = null)
	{
		$this->db = $db;
		$this->config = $config;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string
	{
		return 'mysqli';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string
	{
		return 'mysql';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query(int $topic_id, string $topic_title, int $length, float $sensitivity): array
	{
		$sql_time = ($length > 0) ? ' AND t.topic_time > (UNIX_TIMESTAMP() - ' . (int) $length . ')' : '';

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*,
				MATCH (t.topic_title) AGAINST ('" . $this->db->sql_escape($topic_title) . "') AS score",

			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),
			'WHERE'		=> "MATCH (t.topic_title) AGAINST ('" . $this->db->sql_escape($topic_title) . "') >= " . (float) $sensitivity . '
				AND t.topic_status <> ' . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_id <> ' . (int) $topic_id . $sql_time,
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported(): bool
	{
		return $this->is_mysql() && $this->supported_engine();
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_fulltext(string $column = 'topic_title', string $table = TOPICS_TABLE): bool
	{
		return in_array($column, $this->get_fulltext_indexes($column, $table), true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fulltext_indexes(string $column = 'topic_title', string $table = TOPICS_TABLE): array
	{
		$indexes = array();

		if (!$this->is_supported())
		{
			return $indexes;
		}

		$sql = 'SHOW INDEX
			FROM ' . $this->db->sql_escape($table);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Older MySQL versions didn't use Index_type, so fallback to Comment
			$index_type = $row['Index_type'] ?? $row['Comment'];

			if ($index_type === 'FULLTEXT' && $row['Key_name'] === $column)
			{
				$indexes[] = $row['Key_name'];
			}
		}

		$this->db->sql_freeresult($result);

		return $indexes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_fulltext_index(string $column = 'topic_title', string $table = TOPICS_TABLE): void
	{
		if (!$this->is_fulltext($column, $table))
		{
			// First see if we need to update the table engine to support fulltext indexes
			if (!$this->is_supported())
			{
				if ($this->config !== null)
				{
					$this->config->set(self::ORIGINAL_ENGINE_CONFIG, (string) $this->get_engine());
				}
				$sql = 'ALTER TABLE ' . $this->db->sql_escape($table) . ' ENGINE = MYISAM';
				$this->db->sql_query($sql);
				$this->set_engine();
			}

			$sql = 'ALTER TABLE ' . $this->db->sql_escape($table) . '
				ADD FULLTEXT (' . $this->db->sql_escape($column) . ')';
			$this->db->sql_query($sql);

			if ($this->config !== null && $this->is_fulltext($column, $table))
			{
				$this->config->set(self::OWNED_INDEX_CONFIG, $column);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function drop_owned_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		if ($this->config === null)
		{
			return;
		}
		if (!$this->config->offsetExists(self::OWNED_INDEX_CONFIG))
		{
			// A failed/unverified creation may leave only this extension-owned
			// bookkeeping value. Remove it without touching table or index.
			$this->config->delete(self::ORIGINAL_ENGINE_CONFIG);
			return;
		}

		$owns_index = $this->config[self::OWNED_INDEX_CONFIG] === $column;
		if ($owns_index && $this->is_fulltext($column, $table))
		{
			$sql = 'ALTER TABLE ' . $this->quote_identifier($table) .
				' DROP INDEX ' . $this->quote_identifier($column);
			$this->db->sql_query($sql);
		}

		$original_engine = $this->config->offsetExists(self::ORIGINAL_ENGINE_CONFIG)
			? $this->config[self::ORIGINAL_ENGINE_CONFIG]
			: '';
		if ($owns_index
			&& preg_match('/^[a-zA-Z0-9_]+$/D', $original_engine)
			&& strtolower($this->get_engine()) === 'myisam')
		{
			$sql = 'ALTER TABLE ' . $this->quote_identifier($table) . ' ENGINE = ' . strtoupper($original_engine);
			$this->db->sql_query($sql);
		}

		$this->config->delete(self::OWNED_INDEX_CONFIG);
		$this->config->delete(self::ORIGINAL_ENGINE_CONFIG);
	}

	/**
	 * Quote a MySQL identifier.
	 *
	 * @param string $identifier Identifier
	 * @return string
	 */
	protected function quote_identifier($identifier)
	{
		return '`' . str_replace('`', '``', $identifier) . '`';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_engine(): string
	{
		return $this->engine ?? $this->set_engine();
	}

	/**
	 * Set the database storage engine name
	 *
	 * @access protected
	 * @return string The storage engine name
	 */
	protected function set_engine(): string
	{
		$this->engine = '';

		if ($this->is_mysql())
		{
			$info = $this->get_table_info();

			// Modern MySQL uses 'Engine', but older may still use 'Type'
			foreach (array('Engine', 'Type') as $name)
			{
				if (isset($info[$name]))
				{
					$this->engine = strtolower($info[$name]);
					break;
				}
			}
		}

		return $this->engine;
	}

	/**
	 * Get topics table information
	 *
	 * @access protected
	 * @param string $table Name of the table
	 * @return mixed Array with the table info, false if the table does not exist
	 */
	protected function get_table_info(string $table = TOPICS_TABLE): mixed
	{
		$result = $this->db->sql_query("SHOW TABLE STATUS LIKE '" . $this->db->sql_escape($table) . "'");
		$info = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $info;
	}

	/**
	 * Check if the database is using MySQL
	 *
	 * @access public
	 * @return bool True if is mysql, false otherwise
	 */
	protected function is_mysql(): bool
	{
		return str_starts_with($this->db->get_sql_layer(), 'mysql');
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_stopword_support(): bool
	{
		return true;
	}

	/**
	 * Check if the database engine is supported.
	 * FULLTEXT is supported on MyISAM, and also on InnoDB as of MySQL 5.6.4 according
	 * to http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
	 *
	 * @return bool True if supported, false otherwise
	 */
	protected function supported_engine(): bool
	{
		if ($this->get_engine() === 'myisam')
		{
			return true;
		}

		if ($this->get_engine() === 'innodb')
		{
			return phpbb_version_compare($this->db->sql_server_info(true), '5.6.4', '>=');
		}

		return false;
	}
}
