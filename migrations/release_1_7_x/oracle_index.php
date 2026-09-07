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

use vse\similartopics\driver\oracle;

class oracle_index extends \phpbb\db\migration\migration
{
	/** @var oracle */
	protected $driver;

	/**
	 * Do not run this migration if the DB is not Oracle
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		if (strpos($this->db->get_sql_layer(), 'oracle') !== 0)
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
				strpos($this->db->get_sql_layer(), 'oracle') === 0,
				['custom', [[$this, 'create_oracle_fulltext_index']]],
			]],
		];
	}

	public function revert_data()
	{
		return [
			['if', [
				strpos($this->db->get_sql_layer(), 'oracle') === 0,
				['custom', [[$this, 'drop_oracle_fulltext_index']]],
			]],
		];
	}

	/**
	 * Legacy create callback retained for migration compatibility
	 */
	public function create_oracle_fulltext_index()
	{
		// Completed migrations are not rerun on upgrade. Fresh installs reach this
		// ownership-aware driver and record a marker only after verified creation.
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Legacy drop callback retained for migration compatibility
	 */
	public function drop_oracle_fulltext_index()
	{
		// phpBB uses this historical callback during purge; a new migration cannot
		// replace it. Driver verifies ownership plus exact valid domain-index name.
		$this->get_driver()->drop_owned_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * @return oracle
	 */
	protected function get_driver()
	{
		if ($this->driver === null)
		{
			$this->driver = new oracle($this->db, $this->config);
		}
		return $this->driver;
	}
}
