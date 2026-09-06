<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_7_x;

use vse\similartopics\driver\mssql;

class mssql_index extends \phpbb\db\migration\migration
{
	/** @var mssql */
	protected $driver;

	/**
	 * Do not run this migration if the DB is not MSSQL or MSSQL NATIVE
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		if (strpos($this->db->get_sql_layer(), 'mssql') === false)
		{
			return true;
		}
		return $this->get_driver()->is_fulltext('topic_title', TOPICS_TABLE);
	}

	public static function depends_on()
	{
		return ['\vse\similartopics\migrations\release_1_5_x\similar_topic_words'];
	}

	public function update_data()
	{
		return [
			['if', [
				strpos($this->db->get_sql_layer(), 'mssql') === 0,
				['custom', [[$this, 'create_mssql_fulltext_index']]],
			]],
		];
	}

	public function revert_data()
	{
		return [
			['if', [
				strpos($this->db->get_sql_layer(), 'mssql') === 0,
				['custom', [[$this, 'drop_mssql_fulltext_index']]],
			]],
		];
	}

	/**
	 * Legacy create callback retained for migration compatibility
	 */
	public function create_mssql_fulltext_index()
	{
		// Completed migrations are not rerun on upgrade. Fresh installs reach this
		// ownership-aware driver and record a marker only after verified creation.
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Legacy drop callback retained for migration compatibility
	 */
	public function drop_mssql_fulltext_index()
	{
		// phpBB uses this historical callback during purge; a new migration cannot
		// replace it. Driver performs no DDL without ownership and also preserves a
		// table-wide full-text index if another indexed column was added later.
		$this->get_driver()->drop_owned_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * @return mssql
	 */
	protected function get_driver()
	{
		if ($this->driver === null)
		{
			$this->driver = new mssql($this->db, $this->config);
		}
		return $this->driver;
	}
}
