<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_5_x;

class mysql_index extends \phpbb\db\migration\migration
{
	/**
	 * Do not run this migration if the DB is not MySQL
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		return strpos($this->db->get_sql_layer(), 'mysql') !== 0;
	}

	public static function depends_on()
	{
		return array(
			'\vse\similartopics\migrations\release_1_1_0_data',
			'\vse\similartopics\migrations\release_1_3_0_fulltext',
			'\vse\similartopics\migrations\release_1_4_3_data',
		);
	}

	public function update_data()
	{
		return array(
			array('if', array(
				strpos($this->db->get_sql_layer(), 'mysql') === 0,
				array('custom', array(array($this, 'add_topic_title_fulltext'))),
			)),
		);
	}

	/**
	 * Add a MYSQLI FULLTEXT index to phpbb_topics.topic_title
	 *
	 * This is a retry if the same action in release_1_1_0_data failed.
	 * The first attempt could fail if the table was InnoDB and not
	 * supporting FULLTEXT, in which case we will convert the table to
	 * MyISAM so we can then create the fulltext index. The old storage
	 * engine will be stored so it can be reverted on uninstall.
	 * Data is reverted in release_1_3_0_fulltext.
	 */
	public function add_topic_title_fulltext()
	{
		$driver = $this->get_driver();

		if ($driver->get_type() === 'mysql' && !$driver->is_index('topic_title'))
		{
			// Store the original database storage engine in a config var for recovery on uninstall
			$this->config->set('similar_topics_fulltext', (string) $driver->get_engine());

			// Alter the storage engine to support FULLTEXT
			$driver->alter_engine('MYISAM');

			// Create the FULLTEXT index
			$driver->create_fulltext_index('topic_title');
		}
	}

	/**
	 * Get an instance of the similartopics mysqli driver
	 *
	 * @return \vse\similartopics\driver\mysqli
	 */
	protected function get_driver()
	{
		return new \vse\similartopics\driver\mysqli($this->db);
	}
}
