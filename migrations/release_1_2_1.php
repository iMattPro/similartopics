<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\migrations;

class release_1_2_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return version_compare($this->config['similar_topics_version'], '1.2.1', '>=');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_2_0');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('similar_topics_version', '1.2.1')),
		);
	}
}
