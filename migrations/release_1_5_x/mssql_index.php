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

class mssql_index extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return (new \vse\similartopics\driver\mssql($this->db))->is_fulltext('topic_title', TOPICS_TABLE);
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_5_x\similar_topic_words');
	}

	public function update_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'mssql' || $this->db->get_sql_layer() === 'mssqlnative',
				array('custom', array(array($this, 'create_mssql_fulltext_index'))),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'mssql' || $this->db->get_sql_layer() === 'mssqlnative',
				array('custom', array(array($this, 'drop_mssql_fulltext_index'))),
			)),
		);
	}

	public function create_mssql_fulltext_index()
	{
		$driver = new \vse\similartopics\driver\mssql($this->db);
		$driver->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	public function drop_mssql_fulltext_index()
	{
		if ($this->db->get_sql_layer() === 'mssql' || $this->db->get_sql_layer() === 'mssqlnative')
		{
			// Drop fulltext index
			$sql = "DROP FULLTEXT INDEX ON " . TOPICS_TABLE;
			$this->db->sql_query($sql);
		}
	}
}
