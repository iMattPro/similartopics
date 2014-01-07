<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\migrations;

class release_1_1_0_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['similar_topics_version']) && version_compare($this->config['similar_topics_version'], '1.1.0', '>=');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topic_forums'	=> array('VCHAR_UNI', ''),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topic_forums',
				),
			),
		);
	}
}
