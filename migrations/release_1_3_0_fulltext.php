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

class release_1_3_0_fulltext extends \phpbb\db\migration\migration
{
	/**
	 * phpBB records an effectively installed migration with both schema and data
	 * complete. Its revert_data() therefore still runs during purge. This matters
	 * for legacy installs where release_1_5_x\mysql_index added the engine marker
	 * after this migration had been recorded as effectively installed.
	 */
	public function effectively_installed()
	{
		return !isset($this->config['similar_topics_fulltext']);
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_3_0_data');
	}

	public function revert_data()
	{
		// Conditions are evaluated when purge runs, not when migration was installed.
		// A legacy marker added later by release_1_5_x\mysql_index is therefore seen.
		return array(
			// Revert the storage engine back to the original setting if it was stored
			array('if', array(
				!empty($this->config['similar_topics_fulltext']),
				array('custom', array(array($this, 'revert_fulltext_changes'))),
			)),

			// Remove the config holding the original storage engine setting if it exists
			array('if', array(
				isset($this->config['similar_topics_fulltext']),
				array('config.remove', array('similar_topics_fulltext')),
			)),
		);
	}

	/**
	 * Drop the FULLTEXT index on phpbb_topics.topic_title
	 * Convert phpbb_topics back to the original storage engine
	 */
	public function revert_fulltext_changes()
	{
		$fulltext = new \vse\similartopics\core\fulltext_support($this->db);

		// Drop the FULLTEXT index
		if ($fulltext->is_index('topic_title'))
		{
			$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' DROP INDEX topic_title';
			$this->db->sql_query($sql);
		}

		// Revert the storage engine back to its original setting
		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ENGINE = ' . $this->db->sql_escape(strtoupper($this->config['similar_topics_fulltext']));
		$this->db->sql_query($sql);
	}
}
