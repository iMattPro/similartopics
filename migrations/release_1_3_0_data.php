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

class release_1_3_0_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['similar_topics_version']) && version_compare($this->config['similar_topics_version'], '1.3.0', '>=');
	}

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
			array('if', array(
				array('module.exists', array('acp', 'PST_TITLE_ACP', 'PST_SETTINGS')),
				array('module.remove', array('acp', 'PST_TITLE_ACP', 'PST_SETTINGS')),
			)),
			// add new ACP module
			array('module.add', array(
				'acp', 'PST_TITLE_ACP', array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),
			// Update existing configs
			array('config.update', array('similar_topics_version', '1.3.0')),
		);
	}
}
