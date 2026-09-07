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

use phpbb\config\config;

/**
 * This class handles similar topics queries for PostgreSQL dbms
 */
class postgres implements driver_interface
{
	/** Config key containing exact name of index created by this extension */
	const OWNED_INDEX_CONFIG = 'pst_postgres_owned_index';

	/** @var \phpbb\db\driver\driver_interface */
	protected \phpbb\db\driver\driver_interface $db;

	/** @var config */
	protected config $config;

	/** @var string */
	protected string $ts_name;

	/**
	 * Constructor
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param config $config
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db, config $config)
	{
		$this->db = $db;
		$this->config = $config;

		$this->set_ts_name($config->offsetExists('pst_postgres_ts_name') ? $config['pst_postgres_ts_name'] : 'simple');
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string
	{
		return 'postgres';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string
	{
		return 'postgres';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query(int $topic_id, string $topic_title, int $length, float $sensitivity): array
	{
		$ts_name = $this->db->sql_escape($this->ts_name);
		$ts_query_text = $this->db->sql_escape(preg_replace(['/\s+/', '/\'/'], ['|', ''], $topic_title));
		$ts_rank_cd = "ts_rank_cd('{1,1,1,1}', to_tsvector('$ts_name', t.topic_title), to_tsquery('$ts_name', '$ts_query_text'), 32)";
		$sql_time = ($length > 0) ? ' AND t.topic_time > (extract(epoch from current_timestamp)::integer - ' . (int) $length . ')' : '';

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
				AND t.topic_id <> ' . (int) $topic_id . $sql_time,
			'ORDER_BY'	=> 'score DESC, t.topic_time DESC',
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported(): bool
	{
		return ($this->db->get_sql_layer() === 'postgres');
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_fulltext(string $column = 'topic_title', string $table = TOPICS_TABLE): bool
	{
		return in_array($this->get_index_name($table, $column), $this->get_fulltext_indexes($column, $table), true);
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
			if (str_contains($row['relname'], $column))
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
	public function create_fulltext_index(string $column = 'topic_title', string $table = TOPICS_TABLE): void
	{
		// Make sure ts_name is current
		$this->set_ts_name($this->config->offsetExists('pst_postgres_ts_name') ? $this->config['pst_postgres_ts_name'] : 'simple');

		$new_index = $this->get_index_name($table, $column);
		$indexes = $this->get_fulltext_indexes($column, $table);
		$owned_index = $this->config->offsetExists(self::OWNED_INDEX_CONFIG) ? $this->config[self::OWNED_INDEX_CONFIG] : '';

		// A dictionary change may obsolete an index we previously created. Never
		// remove other matching expression indexes: existence does not prove ownership.
		if ($owned_index && $owned_index !== $new_index)
		{
			if (in_array($owned_index, $indexes, true))
			{
				$sql = 'DROP INDEX ' . $this->quote_identifier($owned_index);
				$this->db->sql_query($sql);
				$indexes = array_values(array_diff($indexes, array($owned_index)));
			}
			$this->config->delete(self::OWNED_INDEX_CONFIG);
		}

		if (!in_array($new_index, $indexes, true))
		{
			$sql = 'CREATE INDEX ' . $this->quote_identifier($new_index) . '
				ON '  . $this->quote_identifier($table) . "
				USING gin (to_tsvector ('" . $this->db->sql_escape($this->ts_name) . "', " . $this->quote_identifier($column) . '))';
			$this->db->sql_query($sql);

			// Record ownership only after catalog lookup confirms creation. If DDL
			// succeeds but verification fails, later cleanup safely preserves it.
			if (in_array($new_index, $this->get_fulltext_indexes($column, $table), true))
			{
				$this->config->set(self::OWNED_INDEX_CONFIG, $new_index);
			}
		}
	}

	/**
	 * Drop only the PostgreSQL index whose ownership was recorded at creation.
	 *
	 * @param string $column Column name
	 * @param string $table  Table name
	 */
	public function drop_owned_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE)
	{
		if (!$this->config->offsetExists(self::OWNED_INDEX_CONFIG))
		{
			return;
		}

		$owned_index = $this->config[self::OWNED_INDEX_CONFIG];
		if (in_array($owned_index, $this->get_fulltext_indexes($column, $table), true))
		{
			$sql = 'DROP INDEX ' . $this->quote_identifier($owned_index);
			$this->db->sql_query($sql);
		}

		$this->config->delete(self::OWNED_INDEX_CONFIG);
	}

	/**
	 * Build a safe, deterministic PostgreSQL index name.
	 *
	 * PostgreSQL limits identifiers to 63 bytes by default. Keep generated names
	 * within that limit so catalog lookups match names PostgreSQL stores.
	 *
	 * @param string $table  Table name
	 * @param string $column Column name
	 * @return string
	 */
	protected function get_index_name($table, $column)
	{
		$name = preg_replace('/[^a-zA-Z0-9_]/', '_', $table . '_' . $this->ts_name . '_' . $column);

		if (strlen($name) > 63)
		{
			$name = substr($name, 0, 46) . '_' . substr(hash('sha256', $name), 0, 16);
		}

		return $name;
	}

	/**
	 * Quote a PostgreSQL identifier.
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
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_stopword_support(): bool
	{
		return !in_array($this->ts_name, ['simple', ''], true);
	}

	/**
	 * Get a list of postgresql text search names
	 *
	 * @return array array of text search names
	 */
	public function get_cfg_name_list(): array
	{
		$sql = 'SELECT cfgname AS ts_name FROM pg_ts_config';
		$result = $this->db->sql_query($sql);
		$ts_options = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $ts_options;
	}

	/**
	 * Set the PostgreSQL Text Search name (dictionary)
	 *
	 * @param string $ts_name Dictionary name
	 */
	protected function set_ts_name(string $ts_name): void
	{
		$this->ts_name = $ts_name ?: 'simple';
	}
}
