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
	 * Create a FULLTEXT index for the topic_title in the topic table
	 */
	public function create_oracle_fulltext_index()
	{
		// Safety fix: fresh creation records ownership; completed legacy updates stay unmarked.
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Drop the FULLTEXT index we created from the topic table
	 */
	public function drop_oracle_fulltext_index()
	{
		// Safety fix: current revert code runs on purge; unmarked legacy indexes survive.
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
