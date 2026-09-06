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

	public static function depends_on()
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
	 * Legacy create callback retained for migration compatibility
	 */
	public function add_topic_title_fulltext()
	{
		$fulltext = $this->get_fulltext();

		// Existing installations have this migration recorded complete, so phpBB
		// does not rerun this changed create callback during upgrade. Fresh installs
		// use the ownership-aware driver; pre-existing indexes are never claimed.
		if ($fulltext->is_supported() && !$fulltext->is_index('topic_title'))
		{
			$fulltext->create_fulltext_index('topic_title', TOPICS_TABLE);
		}
	}

	/**
	 * Legacy drop callback retained for migration compatibility
	 */
	public function drop_topic_title_fulltext()
	{
		// phpBB uses this historical class again during purge. A later migration
		// cannot replace its revert callback, so this existing callback must delegate
		// to the driver. No marker means no DDL; all legacy/unowned indexes remain.
		$this->get_fulltext()->drop_owned_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Get an instance of the fulltext class
	 *
	 * @return \vse\similartopics\core\fulltext_support
	 */
	public function get_fulltext()
	{
		return new \vse\similartopics\core\fulltext_support($this->db, $this->config);
	}
}
