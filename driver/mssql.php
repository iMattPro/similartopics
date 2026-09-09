<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\driver;

/**
 * This class handles similar topics queries for MSSQL dbms
 */
class mssql implements driver_interface
{
	/** Config key containing the full-text catalog owned by this extension */
	const OWNED_CATALOG_CONFIG = 'pst_owned_mssql_catalog';

	/** Full-text catalog used by this extension */
	const FULLTEXT_CATALOG = 'phpbb_catalog';

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config|null */
	protected $config;

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
	public function get_name()
	{
		return 'mssql';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type()
	{
		return 'mssql';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity)
	{
		if ($this->is_fulltext('topic_title', TOPICS_TABLE))
		{
			$search_condition = "CONTAINS(t.topic_title, '" . $this->db->sql_escape(str_replace(' ', ' AND ', $topic_title)) . "')";
		}
		else
		{
			$like_conditions = array();
			foreach (explode(' ', $topic_title) as $word)
			{
				$word = trim($word);
				if (utf8_strlen($word) > 2)
				{
					$like_conditions[] = "t.topic_title LIKE '%" . $this->db->sql_escape($word) . "%'";
				}
			}
			$search_condition = !empty($like_conditions)
				? '(' . implode(' OR ', $like_conditions) . ')'
				: "t.topic_title LIKE '%" . $this->db->sql_escape($topic_title) . "%'";
		}
		$sql_time = ($length > 0) ? " AND t.topic_time > (DATEDIFF(second, '1970-01-01', GETDATE()) - " . (int) $length . ')' : '';

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*,
				CASE WHEN " . $search_condition . " THEN 1.0 ELSE 0.0 END AS score",
			'FROM'		=> array(
				TOPICS_TABLE => 't'
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM' => array(FORUMS_TABLE => 'f'),
					'ON' => 'f.forum_id = t.forum_id'
				)
			),
			'WHERE'		=> $search_condition . "
				AND t.topic_status <> " . ITEM_MOVED . "
				AND t.topic_visibility = " . ITEM_APPROVED . "
				AND t.topic_id <> " . (int) $topic_id . $sql_time,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		return (strpos($this->db->get_sql_layer(), 'mssql') === 0);
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

		try
		{
			$sql = "SELECT c.name
				FROM sys.fulltext_index_columns fic
				INNER JOIN sys.columns c ON fic.object_id = c.object_id AND fic.column_id = c.column_id
				INNER JOIN sys.objects o ON fic.object_id = o.object_id
				WHERE o.name = '" . $this->db->sql_escape($table) . "'";
			$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$indexes[] = $row['name'];
			}

			$this->db->sql_freeresult($result);
		}
		catch (\Exception $e)
		{
			// Full-text search not available
		}

		return $indexes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		if (!$this->is_supported())
		{
			return;
		}

		// SQL Server permits only one full-text index per table. Preserve an
		// existing index and use the LIKE fallback when it lacks this column.
		if (!empty($this->get_fulltext_indexes($column, $table)) || !$this->fulltext_available())
		{
			return;
		}

		// Record ownership only when this extension creates the catalog.
		if (!$this->fulltext_catalog_exists())
		{
			$this->db->sql_query('CREATE FULLTEXT CATALOG ' . self::FULLTEXT_CATALOG);
			if ($this->config !== null)
			{
				$this->config->set(self::OWNED_CATALOG_CONFIG, self::FULLTEXT_CATALOG);
			}
		}

		// Create fulltext index
		$sql = "CREATE FULLTEXT INDEX ON " . $this->db->sql_escape($table) . "
			(" . $this->db->sql_escape($column) . ")
			KEY INDEX PK_" . $this->db->sql_escape($table) . "
			ON " . self::FULLTEXT_CATALOG;
		$this->db->sql_query($sql);

		if ($this->config !== null)
		{
			// SQL Server identifies its full-text index by indexed table.
			$this->config->set(self::OWNED_INDEX_CONFIG, $table);
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

		if ($this->config->offsetExists(self::OWNED_INDEX_CONFIG))
		{
			if ($this->config[self::OWNED_INDEX_CONFIG] === $table)
			{
				$this->drop_fulltext_index($column, $table);
			}

			$this->config->delete(self::OWNED_INDEX_CONFIG);
		}

		$this->drop_owned_fulltext_catalog();
	}

	/**
	 * Drop the Similar Topics full-text index when it contains no other columns.
	 * SQL Server permits only one full-text index per table, so preserving a
	 * multi-column index avoids removing unrelated search functionality.
	 *
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return void
	 */
	public function drop_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		if ($this->get_fulltext_indexes($column, $table) === array($column))
		{
			$this->db->sql_query('DROP FULLTEXT INDEX ON ' . $table);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_engine()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_stopword_support()
	{
		return false;
	}

	/**
	 * Check whether the extension's full-text catalog exists.
	 *
	 * @return bool
	 */
	protected function fulltext_catalog_exists()
	{
		$sql = "SELECT fulltext_catalog_id
			FROM sys.fulltext_catalogs
			WHERE name = '" . self::FULLTEXT_CATALOG . "'";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return (bool) $row;
	}

	/**
	 * Drop the owned catalog only when no full-text indexes still use it.
	 */
	protected function drop_owned_fulltext_catalog()
	{
		if (!$this->config->offsetExists(self::OWNED_CATALOG_CONFIG)
			|| $this->config[self::OWNED_CATALOG_CONFIG] !== self::FULLTEXT_CATALOG)
		{
			return;
		}

		$sql = "SELECT COUNT(fi.object_id) AS index_count
			FROM sys.fulltext_catalogs fc
			LEFT JOIN sys.fulltext_indexes fi ON fi.fulltext_catalog_id = fc.fulltext_catalog_id
			WHERE fc.name = '" . self::FULLTEXT_CATALOG . "'
			GROUP BY fc.fulltext_catalog_id";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row && (int) $row['index_count'] === 0)
		{
			$this->db->sql_query('DROP FULLTEXT CATALOG ' . self::FULLTEXT_CATALOG);
		}

		$this->config->delete(self::OWNED_CATALOG_CONFIG);
	}

	protected function fulltext_available()
	{
		try
		{
			$sql = "SELECT SERVERPROPERTY('IsFullTextInstalled') AS IsFullTextInstalled";
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
			return (bool) $row['IsFullTextInstalled'];
		}
		catch (\Exception $e)
		{
			return false;
		}
	}
}
