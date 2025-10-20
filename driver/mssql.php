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
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

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
				if (strlen($word) > 2)
				{
					$like_conditions[] = "t.topic_title LIKE '%" . $this->db->sql_escape($word) . "%'";
				}
			}
			$search_condition = !empty($like_conditions)
				? '(' . implode(' OR ', $like_conditions) . ')'
				: "t.topic_title LIKE '%" . $this->db->sql_escape($topic_title) . "%'";
		}

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
				AND t.topic_time > (DATEDIFF(second, '1970-01-01', GETDATE()) - " . (int) $length . ")
				AND t.topic_id <> " . (int) $topic_id,
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
			$sql = "SELECT i.name
				FROM sys.fulltext_indexes fi
				INNER JOIN sys.indexes i ON fi.object_id = i.object_id AND fi.unique_index_id = i.index_id
				INNER JOIN sys.objects o ON fi.object_id = o.object_id
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
		if (!$this->is_supported() || !$this->fulltext_available() || $this->is_fulltext($column, $table))
		{
			return;
		}

		// Create fulltext catalog if it doesn't exist
		$sql = "IF NOT EXISTS (SELECT * FROM sys.fulltext_catalogs WHERE name = 'phpbb_catalog')
			CREATE FULLTEXT CATALOG phpbb_catalog";
		$this->db->sql_query($sql);

		// Create fulltext index
		$sql = "CREATE FULLTEXT INDEX ON " . $this->db->sql_escape($table) . "
			(" . $this->db->sql_escape($column) . ")
			KEY INDEX PK_" . $this->db->sql_escape($table) . "
			ON phpbb_catalog";
		$this->db->sql_query($sql);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_engine()
	{
		return '';
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
