<?php
/**
*
* Precise Similar Topics
*
* @copyright (c) 2013 Matt Friedman
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vse\similartopics\migrations;

class release_1_1_1_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['similar_topics_cache']);
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_1_0_data');
	}

	public function update_data()
	{
		return array(
			// Add new permissions
			array('permission.add', array('u_similar_topics')),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_similar_topics')),
			array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_similar_topics')),
			array('permission.permission_set', array('REGISTERED', 'u_similar_topics', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'u_similar_topics', 'group')),

			// Add new configs
			array('config.add', array('similar_topics_cache', '0')),

			// Update existing configs
			array('config.update', array('similar_topics_time', '31536000')),
			array('config.update', array('similar_topics_version', '1.1.1')),
		);
	}
}
