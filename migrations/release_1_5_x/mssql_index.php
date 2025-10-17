<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_5_x;

use vse\similartopics\driver\mssql;

class mssql_index extends \phpbb\db\migration\migration
{
	/** @var mssql */
	protected $driver;

	/**
	 * Do not run this migration if the DB is not MSSQL or MSSQL NATIVE
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		$sql_layer = $this->db->get_sql_layer();
		if ($sql_layer !== 'mssql' && $sql_layer !== 'mssqlnative')
		{
			return true;
		}
		return $this->get_driver()->is_fulltext('topic_title', TOPICS_TABLE);
	}

	public static function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_5_x\similar_topic_words');
	}

	public function update_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'mssql' || $this->db->get_sql_layer() === 'mssqlnative',
				array('custom', array(array($this, 'create_mssql_fulltext_index'))),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('if', array(
				$this->db->get_sql_layer() === 'mssql' || $this->db->get_sql_layer() === 'mssqlnative',
				array('custom', array(array($this, 'drop_mssql_fulltext_index'))),
			)),
		);
	}

	/**
	 * Create FULLTEXT index for the topic_title in topics table
	 */
	public function create_mssql_fulltext_index()
	{
		$this->get_driver()->create_fulltext_index('topic_title', TOPICS_TABLE);
	}

	/**
	 * Drop FULLTEXT index we created from the topics table
	 */
	public function drop_mssql_fulltext_index()
	{
		if ($this->db->get_sql_layer() === 'mssql' || $this->db->get_sql_layer() === 'mssqlnative')
		{
			$sql = "DROP FULLTEXT INDEX ON " . TOPICS_TABLE;
			$this->db->sql_query($sql);
		}
	}

	/**
	 * @return mssql
	 */
	protected function get_driver()
	{
		if ($this->driver === null)
		{
			$this->driver = new mssql($this->db);
		}
		return $this->driver;
	}
}
