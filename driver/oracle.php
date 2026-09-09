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
	protected \phpbb\db\driver\driver_interface $db;

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
	public function get_name(): string
	{
		return 'oracle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string
	{
		return 'oracle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query(int $topic_id, string $topic_title, int $length, float $sensitivity): array
	{
		// Clean and prepare the search terms for Oracle Text
		$search_terms = $this->prepare_search_terms($topic_title);
		// Oracle Text relevance scores range from 0 through 100.
		$score_threshold = (float) $sensitivity * 100;
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
				AND SCORE(1) >= " . $score_threshold . '
				AND t.topic_status <> ' . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_id <> ' . (int) $topic_id . $sql_time,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_ajax_query(int $topic_id, string $topic_title, int $length, float $sensitivity): array
	{
		return $this->get_query($topic_id, $topic_title, $length, $sensitivity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported(): bool
	{
		return $this->is_oracle();
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

		$sql = "SELECT i.index_name
			FROM user_indexes i
			WHERE i.table_name = UPPER('" . $this->db->sql_escape($table) . "')
			AND i.index_type = 'DOMAIN'
			AND i.ityp_owner = 'CTXSYS'
			AND i.ityp_name = 'CONTEXT'
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
			AND i.ityp_owner = 'CTXSYS'
			AND i.ityp_name = 'CONTEXT'
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
	public function create_fulltext_index(string $column = 'topic_title', string $table = TOPICS_TABLE): void
	{
		if (!$this->is_fulltext($column, $table))
		{
			$index_name = $this->get_index_name($table, $column);

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
	public function drop_owned_fulltext_index(string $column = 'topic_title', string $table = TOPICS_TABLE): void
	{
		if ($this->config === null || !$this->config->offsetExists(self::OWNED_INDEX_CONFIG))
		{
			return;
		}

		$index = $this->config[self::OWNED_INDEX_CONFIG];
		if ($this->has_fulltext_index($index, $column, $table))
		{
			$this->db->sql_query('DROP INDEX ' . $this->quote_identifier($index));
		}

		$this->config->delete(self::OWNED_INDEX_CONFIG);
	}

	/**
	 * Build a deterministic Oracle index name no longer than 30 bytes.
	 *
	 * Keep legacy names when they already fit. Longer names use a fixed ASCII
	 * prefix and hash so custom, including multibyte, table prefixes stay safe.
	 *
	 * @param string $table  Table name
	 * @param string $column Column name
	 * @return string
	 */
	protected function get_index_name($table, $column)
	{
		$name = $table . '_' . $column . '_ctx_idx';

		return strlen($name) <= 30
			? $name
			: 'pst_' . substr(hash('sha256', $name), 0, 26);
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
	public function get_engine(): string
	{
		return 'oracle';
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_stopword_support(): bool
	{
		return true;
	}

	/**
	 * Check if the database is using Oracle
	 *
	 * @return bool True if is oracle, false otherwise
	 */
	protected function is_oracle(): bool
	{
		return str_starts_with($this->db->get_sql_layer(), 'oracle');
	}

	/**
	 * Prepare search terms for Oracle Text query
	 *
	 * @param string $topic_title The topic title to search for
	 * @return string Formatted search terms for Oracle Text
	 */
	protected function prepare_search_terms(string $topic_title): string
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
