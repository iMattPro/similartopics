<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\migrations;

class release_1_1_0_data extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['similar_topics_version']) && version_compare($this->config['similar_topics_version'], '1.1.0', '>=');
	}

	static public function depends_on()
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

			// Add ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'PST_TITLE_ACP'
			)),
			array('module.add', array(
				'acp', 'PST_TITLE_ACP', array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),

			// Custom functions
			array('custom', array(array($this, 'add_topic_title_fulltext'))),

			// Keep track of version in the database
			array('config.add', array('similar_topics_version', '1.1.0')),
		);
	}

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
		if (!$this->fulltext_support())
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
		if (!$this->fulltext_support())
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
	* Check for FULLTEXT index support
	*/
	public function fulltext_support()
	{
		if (($this->db->sql_layer != 'mysql4') && ($this->db->sql_layer != 'mysqli'))
		{
			return false;
		}

		$result = $this->db->sql_query('SHOW TABLE STATUS LIKE \'' . TOPICS_TABLE . '\'');
		$info = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$engine = '';
		if (isset($info['Engine']))
		{
			$engine = strtolower($info['Engine']);
		}
		else if (isset($info['Type']))
		{
			$engine = strtolower($info['Type']);
		}

		// FULLTEXT is supported on InnoDB since MySQL 5.6.4 according to
		// http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
		if ($engine === 'myisam' || ($engine === 'innodb' && phpbb_version_compare($this->db->sql_server_info(true), '5.6.4', '>=')))
		{
			return true;
		}

		return false;
	}

	/**
	* Check if a field is already a FULLTEXT index
	*
	* @param	string	$field 	name of a field
	* @return	bool	true means the field is a FULLTEXT index
	*/
	public function is_fulltext($field)
	{
		$sql = "SHOW INDEX 
			FROM " . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// deal with older MySQL versions which didn't use Index_type
			$index_type = (isset($row['Index_type'])) ? $row['Index_type'] : $row['Comment'];

			if ($index_type == 'FULLTEXT' && $row['Key_name'] == $field)
			{
				return true;
			}
		}

		return false;
	}
}
