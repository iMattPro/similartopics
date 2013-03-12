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
 * Initial schema changes needed for Extension installation
 */
class phpbb_ext_vse_similartopics_migrations_1_initial_schema extends phpbb_db_migration
{
	/**
	 * @inheritdoc
	 */
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topic_forums'	=> array('VCHAR_UNI', ''),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'similar_topic_forums',
				),
			),
		);
	}
}