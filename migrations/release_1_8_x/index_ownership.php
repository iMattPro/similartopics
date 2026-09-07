<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2026 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations\release_1_8_x;

use vse\similartopics\driver\driver_interface;
use vse\similartopics\driver\mssql;
use vse\similartopics\driver\mysqli;
use vse\similartopics\driver\oracle;
use vse\similartopics\driver\postgres;
use vse\similartopics\driver\sqlite3;

/**
 * Record the Similar Topics index so uninstall removes only the index we claim.
 *
 * Existing indexes are adopted during upgrades. This does not prove historical
 * provenance because legacy releases did not record which index they created.
 */
class index_ownership extends \phpbb\db\migration\migration
{
	/**
	 * Do not run when DB has no index migration.
	 *
	 * @return bool
	 */
	public function effectively_installed()
	{
		return $this->get_database_type() === '';
	}

	public static function depends_on()
	{
		return array(
			'\vse\similartopics\migrations\release_1_5_x\mysql_index',
			'\vse\similartopics\migrations\release_1_5_x\postgres_index',
			'\vse\similartopics\migrations\release_1_7_x\mssql_index',
			'\vse\similartopics\migrations\release_1_7_x\oracle_index',
			'\vse\similartopics\migrations\release_1_7_x\sqlite3_index',
		);
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'record_index_ownership'))),
		);
	}

	public function revert_data()
	{
		return array(
			array('custom', array(array($this, 'drop_owned_index'))),
		);
	}

	/**
	 * Adopt required index so upgraded and fresh installs have same ownership.
	 */
	public function record_index_ownership()
	{
		$driver = $this->get_driver();
		if ($driver !== null)
		{
			$driver->claim_fulltext_index('topic_title', TOPICS_TABLE);
		}
	}

	/**
	 * Remove exact owned index before released migration cleanup runs.
	 */
	public function drop_owned_index()
	{
		$driver = $this->get_driver();
		if ($driver !== null)
		{
			$driver->drop_owned_fulltext_index('topic_title', TOPICS_TABLE);
		}
	}

	/**
	 * @return driver_interface|null
	 */
	protected function get_driver()
	{
		switch ($this->get_database_type())
		{
			case 'mysql':
				return new mysqli($this->db, $this->config);

			case 'postgres':
				return new postgres($this->db, $this->config);

			case 'mssql':
				return new mssql($this->db, $this->config);

			case 'oracle':
				return new oracle($this->db, $this->config);

			case 'sqlite3':
				return new sqlite3($this->db, $this->config);
		}

		return null;
	}

	/**
	 * @return string mysql|postgres|mssql|oracle|sqlite3, or empty string
	 */
	protected function get_database_type()
	{
		$sql_layer = $this->db->get_sql_layer();

		if (strpos($sql_layer, 'mysql') === 0)
		{
			return 'mysql';
		}
		if ($sql_layer === 'postgres')
		{
			return 'postgres';
		}
		if (strpos($sql_layer, 'mssql') === 0)
		{
			return 'mssql';
		}
		if (strpos($sql_layer, 'oracle') === 0)
		{
			return 'oracle';
		}
		if ($sql_layer === 'sqlite3')
		{
			return 'sqlite3';
		}

		return '';
	}
}
