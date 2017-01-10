<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2016 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations;

class release_1_4_0_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'forums', 'similar_topics_hidden');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_3_1_data');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topics_hide'		=> array('BOOL', 0),
					'similar_topics_ignore'		=> array('BOOL', 0),
				),
			),
			'change_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topic_forums'		=> array('MTEXT', ''),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topics_hide',
					'similar_topics_ignore',
				),
			),
		);
	}
}
