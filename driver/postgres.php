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
 * This class handles similar topics queries for PostgreSQL dbms
 */
class postgres implements driver_interface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var string */
	protected $ts_name;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\config\config              $config
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config)
	{
		$this->db = $db;
		$this->config = $config;

		$this->set_ts_name($config['pst_postgres_ts_name']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return 'postgres';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type()
	{
		return 'postgres';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity)
	{
		$ts_name = $this->db->sql_escape($this->ts_name);
		$ts_query_text = $this->db->sql_escape(preg_replace(['/\s+/', '/\'/'], ['|', ''], $topic_title));
		$ts_rank_cd = "ts_rank_cd('{1,1,1,1}', to_tsvector('$ts_name', t.topic_title), to_tsquery('$ts_name', '$ts_query_text'), 32)";

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*, $ts_rank_cd AS score",

			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),
			'WHERE'		=> "to_tsquery('$ts_name', '$ts_query_text') @@ to_tsvector('$ts_name', t.topic_title) AND $ts_rank_cd >= " . (float) $sensitivity . '
				AND t.topic_status <> ' . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_time > (extract(epoch from current_timestamp)::integer - ' . (int) $length . ')
				AND t.topic_id <> ' . (int) $topic_id,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		return ($this->db->get_sql_layer() === 'postgres');
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_fulltext($column = 'topic_title', $table = TOPICS_TABLE)
	{
		return in_array($table . '_' . $this->ts_name . '_' . $column, $this->get_fulltext_indexes($column, $table), true);
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

		$sql = "SELECT c2.relname
			FROM pg_catalog.pg_class c1, pg_catalog.pg_index i, pg_catalog.pg_class c2
			WHERE c1.relname = '" . $this->db->sql_escape($table) . "'
				AND position('to_tsvector' in pg_catalog.pg_get_indexdef(i.indexrelid, 0, true)) > 0
				AND pg_catalog.pg_table_is_visible(c1.oid)
				AND c1.oid = i.indrelid
				AND i.indexrelid = c2.oid";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			if (strpos($row['relname'], $column) !== false)
			{
				$indexes[] = $row['relname'];
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
		// Make sure ts_name is current
		$this->set_ts_name($this->config['pst_postgres_ts_name']);

		$new_index = $table . '_' . $this->ts_name . '_' . $column;

		$indexed = false;

		foreach ($this->get_fulltext_indexes($column, $table) as $index)
		{
			if ($index === $new_index)
			{
				$indexed = true;
			}
			else
			{
				$sql = 'DROP INDEX ' . $index;
				$this->db->sql_query($sql);
			}
		}

		if (!$indexed)
		{
			$sql = 'CREATE INDEX ' . $this->db->sql_escape($new_index) . '
				ON '  . $this->db->sql_escape($table) . "
				USING gin (to_tsvector ('" . $this->db->sql_escape($this->ts_name) . "', " . $this->db->sql_escape($column) . '))';
			$this->db->sql_query($sql);
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
	 * Set the PostgreSQL Text Search name (dictionary)
	 *
	 * @param string $ts_name Dictionary name
	 */
	protected function set_ts_name($ts_name)
	{
		$this->ts_name = $ts_name ?: 'simple';
	}
}
