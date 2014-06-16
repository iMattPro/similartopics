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

class release_1_1_0_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['similar_topics_version']) && version_compare($this->config['similar_topics_version'], '1.1.0', '>=');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_1_0_schema');
	}

	public function update_data()
	{
		return array(
			// Add configs
			array('config.add', array('similar_topics', '0')),
			array('config.add', array('similar_topics_limit', '5')),
			array('config.add', array('similar_topics_hide', '')),
			array('config.add', array('similar_topics_ignore', '')),
			array('config.add', array('similar_topics_type', 'y')),
			array('config.add', array('similar_topics_time', '365')),

			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'PST_TITLE_ACP'
			)),
			array('module.add', array(
				'acp',
				'PST_TITLE_ACP',
				array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),

			// Custom functions
			array('custom', array(array($this, 'add_topic_title_fulltext'))),

			// Keep track of version in the database
			array('config.add', array('similar_topics_version', '1.1.0')),
		);
	}

	public function revert_data()
	{
		return array(
			// Custom functions
			array('custom', array(array($this, 'drop_topic_title_fulltext'))),
		);
	}

	/**
	* Add a FULLTEXT index to phpbb_topics.topic_title
	*/
	public function add_topic_title_fulltext()
	{
		if (!function_exists('fulltext_support'))
		{
			include($this->phpbb_root_path . 'ext/vse/similartopics/includes/functions.' . $this->php_ext);
		}

		// FULLTEXT is not supported
		if (fulltext_support() !== true)
		{
			return;
		}

		// Prevent adding extra indeces.
		if (is_fulltext('topic_title'))
		{
			return;
		}

		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (topic_title)';
		$this->db->sql_query($sql);
	}

	/**
	* Drop the FULLTEXT index on phpbb_topics.topic_title
	*/
	public function drop_topic_title_fulltext()
	{
		if (!function_exists('fulltext_support'))
		{
			include($this->phpbb_root_path . 'ext/vse/similartopics/includes/functions.' . $this->php_ext);
		}

		// FULLTEXT is not supported
		if (fulltext_support() !== true)
		{
			return;
		}

		// Return if there is no FULLTEXT index to drop.
		if (!is_fulltext('topic_title'))
		{
			return;
		}

		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' DROP INDEX topic_title';
		$this->db->sql_query($sql);
	}
}
