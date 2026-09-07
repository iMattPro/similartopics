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

class index_cleanup_test extends \phpbb_test_case
{
	/** @var \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	/** @var \phpbb\db\tools\tools_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db_tools;

	protected function setUp(): void
	{
		parent::setUp();
		$this->db = $this->createMock('\phpbb\db\driver\driver_interface');
		$this->db_tools = $this->createMock('\phpbb\db\tools\tools_interface');
	}

	public function test_sqlite_uninstall_drops_canonical_quoted_index()
	{
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = array();
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturn(array('name' => 'idx_' . TOPICS_TABLE . '_topic_title'));
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\sqlite3_index(
			new \phpbb\config\config(array()), $this->db, $this->db_tools, '', 'php', 'phpbb_'
		);

		$migration->drop_sqlite3_index();

		$this->assertSame('DROP INDEX IF EXISTS "idx_' . TOPICS_TABLE . '_topic_title"', $queries[1]);
	}

	public function test_mssql_uninstall_preserves_index_with_additional_column()
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = array();
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			array('name' => 'topic_title'),
			array('name' => 'topic_body'),
			false
		);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\mssql_index(
			new \phpbb\config\config(array()), $this->db, $this->db_tools, '', 'php', 'phpbb_'
		);

		$migration->drop_mssql_fulltext_index();

		$this->assertCount(1, $queries);
		$this->assertStringContainsString('sys.fulltext_index_columns', $queries[0]);
	}

	public function test_oracle_uninstall_drops_canonical_quoted_index()
	{
		$index_name = strtoupper(TOPICS_TABLE . '_topic_title_ctx_idx');
		$this->db->method('get_sql_layer')->willReturn('oracle');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = array();
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturn(array('index_name' => $index_name));
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\oracle_index(
			new \phpbb\config\config(array()), $this->db, $this->db_tools, '', 'php', 'phpbb_'
		);

		$migration->drop_oracle_fulltext_index();

		$this->assertSame('DROP INDEX "' . $index_name . '"', $queries[1]);
	}

	public function test_effectively_installed_mysql_migration_sees_later_engine_marker_on_revert()
	{
		$config = new \phpbb\config\config(array());
		$migration = new \vse\similartopics\migrations\release_1_3_0_fulltext(
			$config, $this->db, $this->db_tools, '', 'php', 'phpbb_'
		);

		$this->assertTrue($migration->effectively_installed());
		$config['similar_topics_fulltext'] = 'innodb';

		$container = $this->createMock('\Symfony\Component\DependencyInjection\ContainerInterface');
		$migrator = $this->getMockBuilder('\phpbb\db\migrator')
			->setConstructorArgs(array(
				$container,
				$config,
				$this->db,
				$this->db_tools,
				'phpbb_migrations',
				'',
				'php',
				'phpbb_',
				array(),
				new \phpbb\db\migration\helper(),
			))
			->setMethods(array('load_migration_state', 'set_migration_state', 'process_data_step', 'get_migration'))
			->getMock();
		$migrator->method('get_migration')->willReturn($migration);
		$captured_steps = null;
		$migrator->method('process_data_step')->willReturnCallback(function ($steps) use (&$captured_steps) {
			$captured_steps = $steps;
			return true;
		});

		$migration_name = get_class($migration);
		$reflection = new \ReflectionClass($migrator);
		$state = $reflection->getProperty('migration_state');
		$state->setAccessible(true);
		$state->setValue($migrator, array(
			$migration_name => array(
				'migration_depends_on' => $migration->depends_on(),
				'migration_schema_done' => true,
				'migration_data_done' => true,
				'migration_data_state' => '',
				'migration_start_time' => 0,
				'migration_end_time' => 0,
			),
		));
		$try_revert = $reflection->getMethod('try_revert');
		$try_revert->setAccessible(true);
		$try_revert->invoke($migrator, $migration_name);

		$this->assertTrue($captured_steps[0][1][0]);
		$this->assertSame('revert_fulltext_changes', $captured_steps[0][1][1][1][0][1]);
		$this->assertTrue($captured_steps[1][1][0]);
	}
}
