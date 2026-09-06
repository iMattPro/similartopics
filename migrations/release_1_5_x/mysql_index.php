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
	 * Legacy create callback retained for migration compatibility
	 */
	public function add_topic_title_fulltext()
	{
		// Existing installations have this migration recorded complete, so phpBB
		// does not rerun this changed callback during upgrade. On fresh installs,
		// driver creates only a missing index and records ownership after verification.
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Get an instance of the similartopics mysqli driver
	 *
	 * @return \vse\similartopics\driver\mysqli
	 */
	protected function get_driver()
	{
		return new \vse\similartopics\driver\mysqli($this->db, $this->config);
	}
}
