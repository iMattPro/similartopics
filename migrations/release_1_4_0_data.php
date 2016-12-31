<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2016 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations;

class release_1_4_0_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return !$this->config->offsetExists('similar_topics_hide') && !$this->config->offsetExists('similar_topics_ignore');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_4_0_schema');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'migrate_config_data'))),
			array('custom', array(array($this, 'convert_similar_topic_forums'))),

			array('config.remove', array('similar_topics_hide')),
			array('config.remove', array('similar_topics_ignore')),
		);
	}

	/**
	 * Convert data stored in configs to new columns in the forum table
	 */
	public function migrate_config_data()
	{
		$old_configs = array(
			'similar_topics_hide',
			'similar_topics_ignore',
		);

		foreach ($old_configs as $column)
		{
			$forum_ids = explode(',', $this->config[$column]);

			if (!empty($forum_ids))
			{
				$sql = 'UPDATE ' . FORUMS_TABLE . "
					SET $column = 1
					WHERE " . $this->db->sql_in_set('forum_id', $forum_ids);
				$this->db->sql_query($sql);
			}
		}
	}

	/**
	 * Convert imploded string data into json encoded data
	 */
	public function convert_similar_topic_forums()
	{
		$sql = 'SELECT forum_id, similar_topic_forums
			FROM ' . FORUMS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			if (!empty($row['similar_topic_forums']))
			{
				$forum_ids = explode(',', $row['similar_topic_forums']);
				$forum_ids = json_encode($forum_ids, JSON_NUMERIC_CHECK);

				$sql = 'UPDATE ' . FORUMS_TABLE . "
					SET similar_topic_forums = '" . $this->db->sql_escape($forum_ids) . "'
					WHERE forum_id = " . (int) $row['forum_id'];
				$this->db->sql_query($sql);
			}
		}
		$this->db->sql_freeresult($result);
	}
}
