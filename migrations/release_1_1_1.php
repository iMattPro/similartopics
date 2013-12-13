<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\migrations;

class release_1_1_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return version_compare($this->config['similar_topics_version'], '1.1.1', '>=');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_1_0');
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

			// Update configs
			array('config.add', array('similar_topics_cache', '0')),
			array('config.update', array('similar_topics_time', '31536000')),

			array('config.update', array('similar_topics_version', '1.1.1')),
		);
	}
}
