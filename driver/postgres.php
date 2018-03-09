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

class postgres implements driver_interface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $ts_name;

	/**
	 * mysql constructor.
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\config\config              $config
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config)
	{
		$this->db = $db;
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
		$ts_query_text	= $this->db->sql_escape(str_replace(' ', '|',  $topic_title));

		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*,
				ts_rank_cd(to_tsvector('{$this->ts_name}', t.topic_title), '$ts_query_text', 32) AS score",

			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),
			'WHERE'		=> "ts_rank_cd(to_tsvector('{$this->ts_name}', t.topic_title), '$ts_query_text', 32) >= " . (float) $sensitivity/10 . '
				AND t.topic_status <> ' . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_time > (extract(epoch from current_timestamp)::integer - ' . (int) $length . ')
				AND t.topic_id <> ' . (int) $topic_id,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * Check if the database is using PostgreSQL
	 *
	 * @access public
	 * @return bool True if is postgresql, false otherwise
	 */
	public function is_postgres()
	{
		return ($this->db->get_sql_layer() === 'postgres');
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		return $this->is_postgres();
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_index($field = 'topic_title')
	{
		$is_index = false;

		foreach ($this->get_pg_indexes($field) as $index)
		{
			if ($index === TOPICS_TABLE . '_' . $this->ts_name . '_' . $field)
			{
				$is_index = true;
				break;
			}
		}

		return $is_index;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_fulltext_index($field = 'topic_title')
	{
		$new_index = TOPICS_TABLE . '_' . $this->ts_name . '_' . $field;

		$indexed = false;

		foreach ($this->get_pg_indexes() as $index)
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
				ON '  . TOPICS_TABLE . " 
				USING gin (to_tsvector ('" . $this->db->sql_escape($this->ts_name) . "', " . $this->db->sql_escape($field) . '))';
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Set the PostgreSQL Text Search name (dictionary)
	 *
	 * @param string $ts_name Dictionary name
	 * @return \vse\similartopics\driver\postgres
	 */
	public function set_ts_name($ts_name)
	{
		$this->ts_name = $ts_name ?: 'simple';

		return $this;
	}

	/**
	 * get all PostgreSQL FULLTEXT indexes on field in topics table
	 *
	 * @access public
	 * @param string $field name of a field
	 * @return array contains index names
	 */
	public function get_pg_indexes($field = 'topic_title')
	{
		$indexes = array();

		if (!$this->is_postgres())
		{
			return $indexes;
		}

		$sql = "SELECT c2.relname, pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) AS indexdef
			FROM pg_catalog.pg_class c1, pg_catalog.pg_index i, pg_catalog.pg_class c2
			WHERE c1.relname = '" . TOPICS_TABLE . "'
				AND pg_catalog.pg_table_is_visible(c1.oid)
				AND c1.oid = i.indrelid
				AND i.indexrelid = c2.oid";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			if (strpos($row['relname'], $field) !== false)
			{
				$indexes[] = $row['relname'];
			}
		}
		$this->db->sql_freeresult($result);

		return $indexes;
	}

	/**
	 * Get list of PostgreSQL text search names
	 *
	 * @return array array of text search names
	 */
	public function get_cfgname_list()
	{
		$sql = 'SELECT cfgname AS ts_name FROM pg_ts_config';
		$result = $this->db->sql_query($sql);
		$ts_options = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $ts_options;
	}
}
