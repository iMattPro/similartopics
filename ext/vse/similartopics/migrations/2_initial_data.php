<?php
/**
 *
 * @package Precise Similar Topics II
 * @copyright (c) 2010 Matt Friedman
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
 * Initial data changes needed for Extension installation
 */
class phpbb_ext_vse_similartopics_migrations_2_initial_data extends phpbb_db_migration
{
	/**
	 * @inheritdoc
	 */
	static public function depends_on()
	{
		return array('phpbb_ext_vse_similartopics_migrations_1_initial_schema');
	}

	/**
	 * @inheritdoc
	 */
	public function update_data()
	{
		return array(
			// Remove previous versions if they exist
			array('if', array(
				($this->config['similar_topics_version']),
				array('config.remove', array('similar_topics_version')),
			)),

			// Add configs
			array('config.add', array('similar_topics_version', '1.3.0b1')),
			array('config.add', array('similar_topics', '0')),
			array('config.add', array('similar_topics_limit', '5')),
			array('config.add', array('similar_topics_hide', '')),
			array('config.add', array('similar_topics_ignore', '')),
			array('config.add', array('similar_topics_type', 'y')),
			array('config.add', array('similar_topics_time', '31536000')),
			array('config.add', array('similar_topics_cache', '0')),
			array('config.add', array('similar_topics_words', '')),

			// Add ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'PST_TITLE_ACP')),
			array('module.add', array('acp', 'PST_TITLE_ACP', array(
				'module_basename'	=> 'phpbb_ext_vse_similartopics_acp_similar_topics_module',
				),
			)),
//			array('module.add', array('acp', 'ACP_BOARD_CONFIGURATION', array(
//				'module_basename'	=> 'phpbb_ext_vse_similartopics_acp_similar_topics_module',
//				),
//			)),

			// Add permissions
			array('permission.add', array('u_similar_topics')),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_similar_topics')),
			array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_similar_topics')),
			array('permission.permission_set', array('REGISTERED', 'u_similar_topics', 'group')),
			array('permission.permission_set', array('REGISTERED_COPPA', 'u_similar_topics', 'group')),

			// Custom functions
			array('custom', array(array($this, 'add_topic_title_fulltext'))),
		);
	}

	/**
	 * @inheritdoc
	 */
	public function revert_data()
	{
		return array(
 			// Custom functions
			array('custom', array(array($this, 'drop_topic_title_fulltext'))),   
		);
	}

	/**
	 * Add a FULLTEXT index to phpbb_topics.topic_title
	 */
	public function add_topic_title_fulltext()
	{
		if (($this->db->sql_layer != 'mysql4') && ($this->db->sql_layer != 'mysqli'))
		{
			return;
		}

		// Prevent adding extra indeces.
		if ($this->is_fulltext('topic_title'))
		{
			return;
		}

		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (topic_title)';
		$this->db->sql_query($sql);
	}

	/**
	 * Drop the FULLTEXT index on phpbb_topics.topic_title
	 */
	public function drop_topic_title_fulltext()
	{
		if (($this->db->sql_layer != 'mysql4') && ($this->db->sql_layer != 'mysqli'))
		{
			return;
		}

		// Return if there is no FULLTEXT index to drop.
		if (!$this->is_fulltext('topic_title'))
		{
			return;
		}

		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' DROP INDEX topic_title';
		$this->db->sql_query($sql);
	}

	/**
	 * Check to see if $key is already a FULLTEXT index
	 */
	public function is_fulltext($key)
	{
		$sql = "SHOW INDEX 
			FROM " . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// deal with older MySQL versions which didn't use Index_type
			$index_type = (isset($row['Index_type'])) ? $row['Index_type'] : $row['Comment'];

			if ($index_type == 'FULLTEXT' && $row['Key_name'] == $key)
			{
				return true;
			}
		}

		return false;
	}

}
