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

class similar_topic_words extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !$this->config->offsetExists('similar_topics_words');
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_1_6_data');
	}

	public function update_data()
	{
		return array(
			array('if', array(
				!empty($this->config['similar_topics_words']),
				array('config_text.add', array('similar_topics_words', $this->config['similar_topics_words'])),
			)),
			array('config.remove', array('similar_topics_words')),
		);
	}
}
