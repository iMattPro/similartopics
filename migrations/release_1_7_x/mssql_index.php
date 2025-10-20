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
	 * Create a FULLTEXT index for the topic_title in the topic table
	 */
	public function create_mssql_fulltext_index()
	{
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Drop the FULLTEXT index we created from the topic table
	 */
	public function drop_mssql_fulltext_index()
	{
		$sql = 'DROP FULLTEXT INDEX ON ' . TOPICS_TABLE;
		$this->db->sql_query($sql);
	}

	/**
	 * @return mssql
	 */
	protected function get_driver()
	{
		if ($this->driver === null)
		{
			$this->driver = new mssql($this->db);
		}
		return $this->driver;
	}
}
