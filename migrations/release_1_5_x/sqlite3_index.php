<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_5_x;

class sqlite3_index extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return (new \vse\similartopics\driver\sqlite3($this->db))->is_fulltext('topic_title', TOPICS_TABLE);
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

	public function create_sqlite3_index()
	{
		$driver = new \vse\similartopics\driver\sqlite3($this->db);
		$driver->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	public function drop_sqlite3_index()
	{
		if ($this->db->get_sql_layer() === 'sqlite3')
		{
			// Drop the index we created
			$sql = "DROP INDEX IF EXISTS idx_" . TOPICS_TABLE . "_topic_title";
			$this->db->sql_query($sql);
		}
	}
}
