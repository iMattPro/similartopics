<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\migrations;

class release_1_3_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return version_compare($this->config['similar_topics_version'], '1.3.0', '>=');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_2_1');
	}

	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_similar_topics'	=> array('BOOL', 1),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_similar_topics',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			// remove module if updating
			array('if', array(
				array('module.exists', array('acp', 'PST_TITLE_ACP', 'PST_TITLE')),
				array('module.remove', array('acp', 'PST_TITLE_ACP', 'PST_TITLE')),
			)),

			// re-add module if updating
			array('module.add', array(
				'acp', 
				'PST_TITLE_ACP',
				array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),

			array('config.update', array('similar_topics_version', '1.3.0')),
		);
	}
}
