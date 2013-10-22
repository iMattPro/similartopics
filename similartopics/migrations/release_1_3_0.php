<?php
/**
*
* @package Precise Similar Topics II
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
			array('custom', array(array($this, 'update_module_basename'))),
			array('config.update', array('similar_topics_version', '1.3.0')),
		);
	}

	public function update_module_basename()
	{
		$old_module_basename = 'acp_similar_topics';
		$new_module_basename = '\vse\similartopics\acp\similar_topics_module';
		
		$sql = 'UPDATE ' . $this->table_prefix . "modules
			SET module_basename = '" . $this->db->sql_escape($new_module_basename) . "'
			WHERE module_basename = '$old_module_basename'";
		$this->db->sql_query($sql);
	}
}
