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
 * This class handles similar topics queries for SQLite3 dbms
 */
class sqlite3 implements driver_interface
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
		return 'sqlite3';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type()
	{
		return 'sqlite';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity)
	{
		$words = explode(' ', $topic_title);
		$like_conditions = array();

		foreach ($words as $word)
		{
			$like_conditions[] = "t.topic_title LIKE '%" . $this->db->sql_escape(trim($word)) . "%'";
		}

		$where_condition = '(' . implode(' OR ', $like_conditions) . ')';

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*,
				CASE WHEN " . $where_condition . " THEN 1.0 ELSE 0.0 END AS score",
			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),
			'WHERE'		=> $where_condition . "
				AND t.topic_status <> " . ITEM_MOVED . "
				AND t.topic_visibility = " . ITEM_APPROVED . "
				AND t.topic_time > (strftime('%s', 'now') - " . (int) $length . ")
				AND t.topic_id <> " . (int) $topic_id,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		return ($this->db->get_sql_layer() === 'sqlite3');
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_fulltext($column = 'topic_title', $table = TOPICS_TABLE)
	{
		// For SQLite, we check if a regular index exists since we use LIKE operations
		return $this->index_exists($table, $column);
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

		// Check for FTS virtual tables
		$sql = "SELECT name FROM sqlite_master
			WHERE type='table' AND name LIKE '" . $this->db->sql_escape($table) . "_fts%'";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$indexes[] = $row['name'];
		}

		$this->db->sql_freeresult($result);

		return $indexes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		// SQLite FTS setup is complex and optional for LIKE-based search
		// We'll create a simple index to improve LIKE performance
		if ($this->index_exists($table, $column))
		{
			return;
		}

		$escaped_table = $this->db->sql_escape($table);
		$escaped_column = $this->db->sql_escape($column);
		$sql = 'CREATE INDEX idx_' . $escaped_table . '_' . $escaped_column . '
			ON ' . $escaped_table . ' (' . $escaped_column . ')';
		$this->db->sql_query($sql);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_engine()
	{
		return '';
	}

	/**
	 * Check if index exists
	 *
	 * @param string $table Name of the table
	 * @param string $column Name of the column
	 * @return bool True if index exists, false otherwise
	 */
	protected function index_exists($table, $column)
	{
		if (!$this->is_supported())
		{
			return false;
		}

		$index_name = 'idx_' . $table . '_' . $column;
		$sql = "SELECT name FROM sqlite_master
			WHERE type='index' AND name = '" . $this->db->sql_escape($index_name) . "'";
		$result = $this->db->sql_query($sql);
		$exists = (bool) $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $exists;
	}
}
