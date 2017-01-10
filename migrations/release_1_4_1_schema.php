<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2017 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations;

class release_1_4_1_schema extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_3_1_data',
					 '\vse\similartopics\migrations\release_1_4_0_schema');
	}

	public function update_schema()
	{
		return array(
			'change_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topic_forums'		=> array('MTEXT', null),
				),
			),
		);
	}

	public function revert_schema()
	{
	}
}
