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

use vse\similartopics\driver\sqlite3;

class sqlite3_index extends \phpbb\db\migration\migration
{
	/** @var sqlite3 */
	protected $driver;

	/**
	 * Do not run this migration if the DB is not SQLITE3
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		if ($this->db->get_sql_layer() !== 'sqlite3')
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
				$this->db->get_sql_layer() === 'sqlite3',
				['custom', [[$this, 'create_sqlite3_index']]],
			]],
		];
	}

	public function revert_data()
	{
		return [
			['if', [
				$this->db->get_sql_layer() === 'sqlite3',
				['custom', [[$this, 'drop_sqlite3_index']]],
			]],
		];
	}

	/**
	 * Legacy create callback retained for migration compatibility
	 */
	public function create_sqlite3_index()
	{
		// Completed migrations are not rerun on upgrade. Fresh installs reach this
		// ownership-aware driver and record a marker only after verified creation.
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Legacy drop callback retained for migration compatibility
	 */
	public function drop_sqlite3_index()
	{
		// phpBB uses this historical callback during purge; a new migration cannot
		// replace it. Driver performs no DDL without exact recorded ownership.
		$this->get_driver()->drop_owned_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * @return sqlite3
	 */
	protected function get_driver()
	{
		if ($this->driver === null)
		{
			$this->driver = new sqlite3($this->db, $this->config);
		}
		return $this->driver;
	}
}
