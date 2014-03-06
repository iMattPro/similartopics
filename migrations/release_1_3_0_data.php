<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\migrations;

class release_1_3_0_data extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_3_0_schema');
	}

	public function update_data()
	{
		return array(
			// remove old ACP module if it exists
			array('if', array(
				array('module.exists', array('acp', 'PST_TITLE_ACP', 'PST_TITLE')),
				array('module.remove', array('acp', 'PST_TITLE_ACP', 'PST_TITLE')),
			)),

			// add new ACP module
			array('module.add', array(
				'acp',
				'PST_TITLE_ACP',
				array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),

			// Update exisiting configs
			array('config.update', array('similar_topics_version', '1.3.0')),
		);
	}
}
