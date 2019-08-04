<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2019 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_5_x;

class similar_topic_words extends \phpbb\db\migration\container_aware_migration
{
	public function effectively_installed()
	{
		$config_text = $this->container->get('config_text');

		return $config_text->get('similar_topics_words') !== null;
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_1_6_data');
	}

	/**
	 * Create the new similar_topics_words field in the CONFIG_TEXT
	 * table and copy the contents of the old similar_topics_words
	 * CONFIG value to it (if there is any) and then delete the old
	 * CONFIG field.
	 *
	 * @return array
	 */
	public function update_data()
	{
		return array(
			array('config_text.add', array('similar_topics_words', $this->config['similar_topics_words'] ?: '')),
			array('config.remove', array('similar_topics_words')),
		);
	}
}
