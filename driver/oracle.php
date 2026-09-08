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
		$sql_time = ($length > 0) ? " AND t.topic_time > ((CAST(SYS_EXTRACT_UTC(SYSTIMESTAMP) AS DATE) - DATE '1970-01-01') * 86400 - " . (int) $length . ')' : '';

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
				AND t.topic_id <> ' . (int) $topic_id . $sql_time,
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

		$sql = "SELECT i.index_name
			FROM user_indexes i
			WHERE i.table_name = UPPER('" . $this->db->sql_escape($table) . "')
			AND i.index_type = 'DOMAIN'
			AND i.domidx_opstatus = 'VALID'
			AND EXISTS (
				SELECT 1
				FROM user_ind_columns c
				WHERE c.index_name = i.index_name
				AND c.table_name = i.table_name
				AND c.column_name = UPPER('" . $this->db->sql_escape($column) . "')
			)";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$indexes[] = strtolower($column);
		}

		$this->db->sql_freeresult($result);

		return $indexes;
	}

	/**
	 * Check for one exact valid Oracle Text index.
	 *
	 * @param string $index_name Index name as stored by Oracle
	 * @param string $column     Column name
	 * @param string $table      Table name
	 * @return bool
	 */
	public function has_fulltext_index($index_name, $column = 'topic_title', $table = TOPICS_TABLE)
	{
		if (!$this->is_supported())
		{
			return false;
		}

		$sql = "SELECT i.index_name
			FROM user_indexes i
			WHERE i.index_name = UPPER('" . $this->db->sql_escape($index_name) . "')
			AND i.table_name = UPPER('" . $this->db->sql_escape($table) . "')
			AND i.index_type = 'DOMAIN'
			AND i.domidx_opstatus = 'VALID'
			AND EXISTS (
				SELECT 1
				FROM user_ind_columns c
				WHERE c.index_name = i.index_name
				AND c.table_name = i.table_name
				AND c.column_name = UPPER('" . $this->db->sql_escape($column) . "')
			)";
		$result = $this->db->sql_query($sql);
		$exists = (bool) $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $exists;
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

			if ($this->config !== null)
			{
				$this->config->set(self::OWNED_INDEX_CONFIG, strtoupper($index_name));
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function drop_owned_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		if ($this->config === null || !$this->config->offsetExists(self::OWNED_INDEX_CONFIG))
		{
			return;
		}

		$index = strtoupper($table . '_' . $column . '_ctx_idx');
		if ($this->config[self::OWNED_INDEX_CONFIG] === $index)
		{
			$this->drop_fulltext_index($column, $table);
		}

		$this->config->delete(self::OWNED_INDEX_CONFIG);
	}

	/**
	 * Drop the canonical Similar Topics Oracle Text index when it exists.
	 *
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return void
	 */
	public function drop_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		$expected_index = strtoupper($table . '_' . $column . '_ctx_idx');
		if ($this->has_fulltext_index($expected_index, $column, $table))
		{
			$this->db->sql_query('DROP INDEX ' . $this->quote_identifier($expected_index));
		}
	}

	/**
	 * Quote an Oracle identifier.
	 *
	 * @param string $identifier Identifier
	 * @return string
	 */
	protected function quote_identifier($identifier)
	{
		return '"' . str_replace('"', '""', $identifier) . '"';
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
			if (utf8_strlen($word) >= 3)
			{
				$search_words[] = $word;
			}
		}

		return !empty($search_words) ? implode(' OR ', $search_words) : $topic_title;
	}
}
