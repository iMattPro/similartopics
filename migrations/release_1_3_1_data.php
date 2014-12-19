<?php
/**
*
* Precise Similar Topics
*
* @copyright (c) 2014 Matt Friedman
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vse\similartopics\migrations;

class release_1_3_1_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !isset($this->config['similar_topics_version']);
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_3_0_data');
	}

	public function update_data()
	{
		return array(
			// Remove version config
			array('config.remove', array('similar_topics_version')),
		);
	}
}
