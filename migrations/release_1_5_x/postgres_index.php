<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_5_x;

class postgres_index extends \phpbb\db\migration\migration
{
	/**
	 * Do not run this migration if the DB is not PostgreSQL
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		return $this->db->get_sql_layer() !== 'postgres' || $this->config->offsetExists('pst_postgres_ts_name');
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_4_3_data');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('pst_postgres_ts_name', $this->get_ts_name())),
			array('if', array(
				$this->db->get_sql_layer() === 'postgres',
				array('custom', array(array($this, 'create_postgres_index'))),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'postgres',
				array('custom', array(array($this, 'drop_postgres_changes'))),
			)),
		);
	}

	/**
	 * Create PostgreSQL FULLTEXT index for the topic_title
	 */
	public function create_postgres_index()
	{
		$this->get_driver()->create_fulltext_index();
	}

	/**
	 * Drop PostgreSQL indexes managed by Similar Topics
	 */
	public function drop_postgres_changes()
	{
		$this->get_driver()->drop_fulltext_indexes();
	}

	/**
	 * Get an instance of the similartopics POSTGRES driver
	 *
	 * @return \vse\similartopics\driver\postgres
	 */
	protected function get_driver()
	{
		return new \vse\similartopics\driver\postgres($this->db, $this->config);
	}

	protected function get_ts_name()
	{
		return $this->config->offsetExists('fulltext_postgres_ts_name') ? $this->config['fulltext_postgres_ts_name'] : 'simple';
	}
}
