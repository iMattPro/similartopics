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
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $engine;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db)
	{
		$this->db = $db;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return 'mysqli';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type()
	{
		return 'mysql';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity)
	{
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
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . (int) $length . ')
				AND t.topic_id <> ' . (int) $topic_id,
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		return $this->is_mysql() && $this->supported_engine();
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_fulltext($column = 'topic_title', $table = TOPICS_TABLE)
	{
		return in_array($column, $this->get_fulltext_indexes($column, $table), true);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fulltext_indexes($column = 'topic_title', $table = TOPICS_TABLE)
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
			$index_type = isset($row['Index_type']) ? $row['Index_type'] : $row['Comment'];

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
	public function create_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		if (!$this->is_fulltext($column, $table))
		{
			// First see if we need to update the table engine to support fulltext indexes
			if (!$this->is_supported())
			{
				$sql = 'ALTER TABLE ' . $this->db->sql_escape($table) . ' ENGINE = MYISAM';
				$this->db->sql_query($sql);
				$this->set_engine();
			}

			$sql = 'ALTER TABLE ' . $this->db->sql_escape($table) . '
				ADD FULLTEXT (' . $this->db->sql_escape($column) . ')';
			$this->db->sql_query($sql);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_engine()
	{
		return $this->engine !== null ? $this->engine : $this->set_engine();
	}

	/**
	 * Set the database storage engine name
	 *
	 * @access protected
	 * @return string The storage engine name
	 */
	protected function set_engine()
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
	protected function get_table_info($table = TOPICS_TABLE)
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
	protected function is_mysql()
	{
		return ($this->db->get_sql_layer() === 'mysql4' || $this->db->get_sql_layer() === 'mysqli');
	}

	/**
	 * Check if the database engine is supported.
	 * FULLTEXT is supported on MyISAM, and also on InnoDB as of MySQL 5.6.4 according
	 * to http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
	 *
	 * @return bool True if supported, false otherwise
	 */
	protected function supported_engine()
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
