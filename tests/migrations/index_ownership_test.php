<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2026 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\migrations;

use vse\similartopics\driver\driver_interface;
use vse\similartopics\migrations\release_1_8_x\index_ownership;

class index_ownership_test extends \phpbb_test_case
{
	/** @var \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	/** @var \phpbb\db\tools\tools_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db_tools;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var array */
	protected $queries;

	protected function setUp(): void
	{
		parent::setUp();
		$this->db = $this->createMock('\phpbb\db\driver\driver_interface');
		$this->db_tools = $this->createMock('\phpbb\db\tools\tools_interface');
		$this->config = new \phpbb\config\config(array('pst_postgres_ts_name' => 'english'));
		$this->queries = array();
	}

	public function test_depends_on_all_released_index_migrations()
	{
		$this->assertSame(array(
			'\vse\similartopics\migrations\release_1_5_x\mysql_index',
			'\vse\similartopics\migrations\release_1_5_x\postgres_index',
			'\vse\similartopics\migrations\release_1_7_x\mssql_index',
			'\vse\similartopics\migrations\release_1_7_x\oracle_index',
			'\vse\similartopics\migrations\release_1_7_x\sqlite3_index',
		), index_ownership::depends_on());
	}

	public function ownership_data()
	{
		return array(
			'mysql' => array('mysqli', 'topic_title', 'ALTER TABLE `' . TOPICS_TABLE . '` DROP INDEX `topic_title`'),
			'postgres' => array('postgres', TOPICS_TABLE . '_english_topic_title', 'DROP INDEX "' . TOPICS_TABLE . '_english_topic_title"'),
			'mssql' => array('mssql', TOPICS_TABLE, 'DROP FULLTEXT INDEX ON ' . TOPICS_TABLE),
			'oracle' => array('oracle', strtoupper(TOPICS_TABLE . '_topic_title_ctx_idx'), 'DROP INDEX "' . strtoupper(TOPICS_TABLE . '_topic_title_ctx_idx') . '"'),
			'sqlite3' => array('sqlite3', 'idx_' . TOPICS_TABLE . '_topic_title', 'DROP INDEX IF EXISTS "idx_' . TOPICS_TABLE . '_topic_title"'),
		);
	}

	/**
	 * @dataProvider ownership_data
	 */
	public function test_records_existing_required_index($sql_layer, $index)
	{
		$this->configure_index_lookup($sql_layer, $index);

		$this->get_migration()->record_index_ownership();

		$this->assertSame($index, $this->config[driver_interface::OWNED_INDEX_CONFIG]);
	}

	/**
	 * @dataProvider ownership_data
	 */
	public function test_uninstall_drops_exact_owned_index($sql_layer, $index, $drop_sql)
	{
		$this->config[driver_interface::OWNED_INDEX_CONFIG] = $index;
		$this->configure_index_lookup($sql_layer, $index);

		$this->get_migration()->drop_owned_index();

		$this->assertContains($drop_sql, $this->queries);
		$this->assertFalse($this->config->offsetExists(driver_interface::OWNED_INDEX_CONFIG));
	}

	public function test_unsupported_database_is_already_effective()
	{
		$this->db->method('get_sql_layer')->willReturn('firebird');

		$this->assertTrue($this->get_migration()->effectively_installed());
	}

	public function supported_database_data()
	{
		return array(
			array('mysqli'),
			array('postgres'),
			array('mssqlnative'),
			array('oracle'),
			array('sqlite3'),
		);
	}

	/**
	 * @dataProvider supported_database_data
	 */
	public function test_supported_database_always_reconciles_ownership($sql_layer)
	{
		$this->config[driver_interface::OWNED_INDEX_CONFIG] = 'stale_index';
		$this->db->method('get_sql_layer')->willReturn($sql_layer);

		$this->assertFalse($this->get_migration()->effectively_installed());
	}

	/**
	 * Configure catalog response for one required index.
	 *
	 * @param string $sql_layer Database SQL layer
	 * @param string $index Exact expected index identifier
	 * @return void
	 */
	protected function configure_index_lookup($sql_layer, $index)
	{
		$this->db->method('get_sql_layer')->willReturn($sql_layer);
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_server_info')->willReturn('5.7.0');
		$this->db->method('sql_query')->willReturnCallback(function ($sql) {
			$this->queries[] = $sql;
			return true;
		});

		switch ($sql_layer)
		{
			case 'mysqli':
				$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
					array('Engine' => 'InnoDB'),
					array('Index_type' => 'FULLTEXT', 'Key_name' => $index),
					false
				);
			break;

			case 'postgres':
				$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
					array('relname' => $index),
					false
				);
				$this->db->method('sql_fetchrowset')->willReturn(array(
					array('ts_name' => 'simple'),
					array('ts_name' => 'english'),
				));
			break;

			case 'mssql':
				$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
					array('name' => 'topic_title'),
					false
				);
			break;

			case 'oracle':
				$this->db->method('sql_fetchrow')->willReturn(array('index_name' => $index));
			break;

			case 'sqlite3':
				$this->db->method('sql_fetchrow')->willReturn(array('name' => $index));
			break;
		}

		$this->db->method('sql_freeresult');
	}

	/**
	 * @return index_ownership
	 */
	protected function get_migration()
	{
		return new index_ownership(
			$this->config,
			$this->db,
			$this->db_tools,
			'',
			'php',
			'phpbb_'
		);
	}
}
