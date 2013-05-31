<?php
/**
*
* @package Precise Similar Topics II
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class phpbb_ext_vse_similartopics_migrations_1_2_0 extends phpbb_db_migration
{
	public function effectively_installed()
	{
		return version_compare($this->config['similar_topics_version'], '1.2.0', '>=');
	}

	static public function depends_on()
	{
		return array('phpbb_ext_vse_similartopics_migrations_1_1_8');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('similar_topics_version', '1.2.0')),
		);
	}
}
