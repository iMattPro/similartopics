<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_5_x;

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
		return array('\vse\similartopics\migrations\release_1_5_x\similar_topic_words');
	}

	public function update_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'sqlite3',
				array('custom', array(array($this, 'create_sqlite3_index'))),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'sqlite3',
				array('custom', array(array($this, 'drop_sqlite3_index'))),
			)),
		);
	}

	/**
	 * Create FULLTEXT index for the topic_title in topics table
	 */
	public function create_sqlite3_index()
	{
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Drop FULLTEXT index we created from the topics table
	 */
	public function drop_sqlite3_index()
	{
		if ($this->db->get_sql_layer() === 'sqlite3')
		{
			$sql = "DROP INDEX IF EXISTS idx_" . TOPICS_TABLE . "_topic_title";
			$this->db->sql_query($sql);
		}
	}

	/**
	 * @return sqlite3
	 */
	protected function get_driver()
	{
		if ($this->driver === null)
		{
			$this->driver = new sqlite3($this->db);
		}
		return $this->driver;
	}
}
