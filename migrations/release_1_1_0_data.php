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
		return isset($this->config['similar_topics']);
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
			array('config.add', array('similar_topics_version', '1.1.0')),

			// Add ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'PST_TITLE_ACP')),
			array('module.add', array('acp', 'PST_TITLE_ACP',
				array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),

			array('custom', array(array($this, 'add_topic_title_fulltext'))),
		);
	}

	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'drop_topic_title_fulltext'))),
		);
	}

	/**
	 * Add a MYSQLI FULLTEXT index to phpbb_topics.topic_title
	 */
	public function add_topic_title_fulltext()
	{
		$driver = $this->get_driver();

		// FULLTEXT is supported and topic_title IS NOT an index
		if ($driver->is_supported() && !$driver->is_index('topic_title'))
		{
			$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (topic_title)';
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Drop the MYSQLI FULLTEXT index on phpbb_topics.topic_title
	 */
	public function drop_topic_title_fulltext()
	{
		$driver = $this->get_driver();

		// FULLTEXT is supported and topic_title IS an index
		if ($driver->is_supported() && $driver->is_index('topic_title'))
		{
			$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' DROP INDEX topic_title';
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Get an instance of the similartopics MYSQLI driver
	 *
	 * @return \vse\similartopics\driver\mysqli
	 */
	public function get_driver()
	{
		return new \vse\similartopics\driver\mysqli($this->db);
	}
}
