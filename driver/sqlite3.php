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
	/** Maximum number of recent topics examined by one SQLite search */
	const SEARCH_CANDIDATE_LIMIT = 10000;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config|null */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\config\config|null $config Ownership config for legacy cleanup
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
		$score_conditions = array();

		foreach ($words as $word)
		{
			$like_condition = "t.topic_title LIKE '%" . $this->db->sql_escape(trim($word)) . "%'";
			$like_conditions[] = $like_condition;
			$score_conditions[] = 'CASE WHEN ' . $like_condition . ' THEN 1 ELSE 0 END';
		}

		$where_condition = '(' . implode(' OR ', $like_conditions) . ')';
		$score = '(' . implode(' + ', $score_conditions) . ')';
		$sql_time = ($length > 0) ? " AND t.topic_time > (strftime('%s', 'now') - " . (int) $length . ')' : '';

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*, $score AS score",
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
				AND t.topic_id <> " . (int) $topic_id . $sql_time,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * Generate a bounded SQLite query for live AJAX suggestions.
	 *
	 * @param int    $topic_id    The ID of the main topic
	 * @param string $topic_title The title of the main topic
	 * @param int    $length      The length of time of the search period
	 * @param float  $sensitivity The search score weighting
	 * @return array An SQL query array
	 */
	public function get_ajax_query($topic_id, $topic_title, $length, $sensitivity)
	{
		$sql_array = $this->get_query($topic_id, $topic_title, $length, $sensitivity);
		$candidate_floor = '(SELECT COALESCE(MAX(recent.topic_id), 0) - ' . self::SEARCH_CANDIDATE_LIMIT . ' FROM ' . TOPICS_TABLE . ' recent)';
		$sql_array['WHERE'] .= "
			AND t.topic_id > " . $candidate_floor;

		return $sql_array;
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
		// SQLite LIKE search needs no auxiliary index. This flag reports that
		// required search schema is ready, which keeps SQLite enabled in the ACP.
		return $this->is_supported();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fulltext_indexes($column = 'topic_title', $table = TOPICS_TABLE)
	{
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		// Leading-wildcard LIKE cannot use a regular B-tree title index.
	}

	/**
	 * {@inheritdoc}
	 */
	public function drop_owned_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		// Preserve any legacy B-tree. Only discard obsolete ownership metadata.
		if ($this->config !== null && $this->config->offsetExists(self::OWNED_INDEX_CONFIG))
		{
			$this->config->delete(self::OWNED_INDEX_CONFIG);
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
}
