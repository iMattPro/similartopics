<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations;

class release_1_4_3_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('similar_topics_sense');
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_4_1_schema');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('similar_topics_sense', $this->get_default_sensitivity())),
		);
	}

	/**
	 * Get default sensitivity setting
	 * Seems like 5 will be best for MyISAM and 1 will be best for InnoDB
	 *
	 * @return int
	 */
	public function get_default_sensitivity()
	{
		$fulltext_support = new \vse\similartopics\core\fulltext_support($this->db);

		return $fulltext_support->get_engine() === 'innodb' ? 1 : 5;
	}
}
