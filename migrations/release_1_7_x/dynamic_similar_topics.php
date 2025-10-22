<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_7_x;

class dynamic_similar_topics extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('similar_topics_dynamic');
	}

	public static function depends_on()
	{
		return ['\vse\similartopics\migrations\release_1_5_x\similar_topic_words'];
	}

	public function update_data()
	{
		return [
			['config.add', ['similar_topics_dynamic', 0]],
		];
	}
}
