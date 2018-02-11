<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations;

class release_postgres_support extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('similar_topics_postgres_ts_name');
	}

	static public function depends_on()
	{
		return array('\vse\similartopics\migrations\release_1_4_3_data');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('similar_topics_postgres_ts_name',
				($this->config->offsetExists('fulltext_postgres_ts_name')? $this->config['fulltext_postgres_ts_name'] : 'simple')
			))
		);
	}

	public function revert_data()
	{
		return array(
			// Revert the storage engine back to the original setting if it was stored
			array('if', array(
				($this->db->get_sql_layer() === 'postgres'),
				array('custom', array(array($this, 'revert_postgres_changes'))),
			)),

		);
	}

	/**
	 * Drop the PostgreSQL FULLTEXT index on phpbb_topics.topic_title
	 *
	 */
	public function revert_postgres_changes()
	{
		$fulltext = new \vse\similartopics\core\fulltext_support($this->db);
		$indexes = $fulltext->get_pg_indexes();

		if ($fulltext->is_postgres() && !empty($indexes))
		{
			foreach($indexes as $index)
			{
				$sql = 'DROP INDEX ' . $index;
				$this->db->sql_query($sql);
			}

		}

	}

}
