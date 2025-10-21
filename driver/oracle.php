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
 * This class handles similar topics queries for Oracle dbms
 */
class oracle implements driver_interface
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
		return 'oracle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type()
	{
		return 'oracle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity)
	{
		// Clean and prepare the search terms for Oracle Text
		$search_terms = $this->prepare_search_terms($topic_title);

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*,
				SCORE(1) AS score",

			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),
			'WHERE'		=> "CONTAINS(t.topic_title, '" . $this->db->sql_escape($search_terms) . "', 1) > 0
				AND SCORE(1) >= " . (float) $sensitivity . '
				AND t.topic_status <> ' . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_time > (EXTRACT(EPOCH FROM SYSTIMESTAMP) - ' . (int) $length . ')
				AND t.topic_id <> ' . (int) $topic_id,
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		return $this->is_oracle();
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

		$sql = "SELECT index_name
			FROM user_indexes
			WHERE table_name = UPPER('" . $this->db->sql_escape($table) . "')
			AND index_type = 'DOMAIN'
			AND domidx_opstatus = 'VALID'";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Check if this is a text index on our column
			$check_sql = "SELECT column_name
				FROM user_ind_columns
				WHERE index_name = '" . $this->db->sql_escape($row['index_name']) . "'
				AND column_name = UPPER('" . $this->db->sql_escape($column) . "')";
			$check_result = $this->db->sql_query($check_sql);

			if ($this->db->sql_fetchrow($check_result))
			{
				$indexes[] = strtolower($column);
			}
			$this->db->sql_freeresult($check_result);
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
			$index_name = $table . '_' . $column . '_ctx_idx';

			// Create Oracle Text index
			$sql = "CREATE INDEX " . $this->db->sql_escape($index_name) . "
				ON " . $this->db->sql_escape($table) . " (" . $this->db->sql_escape($column) . ")
				INDEXTYPE IS CTXSYS.CONTEXT
				PARAMETERS ('STOPLIST CTXSYS.DEFAULT_STOPLIST SYNC (ON COMMIT)')";
			$this->db->sql_query($sql);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_engine()
	{
		return 'oracle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_stopword_support()
	{
		return true;
	}

	/**
	 * Check if the database is using Oracle
	 *
	 * @return bool True if is oracle, false otherwise
	 */
	protected function is_oracle()
	{
		return strpos($this->db->get_sql_layer(), 'oracle') === 0;
	}

	/**
	 * Prepare search terms for Oracle Text query
	 *
	 * @param string $topic_title The topic title to search for
	 * @return string Formatted search terms for Oracle Text
	 */
	protected function prepare_search_terms($topic_title)
	{
		// Remove special characters and split into words
		$words = preg_split('/[^\p{L}\p{N}]+/u', $topic_title, -1, PREG_SPLIT_NO_EMPTY);

		// Filter out short words and create OR query
		$search_words = array();
		foreach ($words as $word)
		{
			if (strlen($word) >= 3)
			{
				$search_words[] = $word;
			}
		}

		return !empty($search_words) ? implode(' OR ', $search_words) : $topic_title;
	}
}
