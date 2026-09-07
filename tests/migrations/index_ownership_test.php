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

class index_ownership_test extends \phpbb_test_case
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

	public static function unowned_migration_data()
	{
		return [
			'mysql drop' => ['\vse\similartopics\migrations\release_1_1_0_data', 'drop_topic_title_fulltext'],
			'postgres drop' => ['\vse\similartopics\migrations\release_1_5_x\postgres_index', 'drop_postgres_changes'],
			'mssql drop' => ['\vse\similartopics\migrations\release_1_7_x\mssql_index', 'drop_mssql_fulltext_index'],
			'oracle drop' => ['\vse\similartopics\migrations\release_1_7_x\oracle_index', 'drop_oracle_fulltext_index'],
			'sqlite3 drop' => ['\vse\similartopics\migrations\release_1_7_x\sqlite3_index', 'drop_sqlite3_index'],
		];
	}

	/**
	 * @dataProvider unowned_migration_data
	 */
	public function test_historical_migrations_do_not_drop_unowned_indexes($class, $method)
	{
		$this->db->expects($this->never())->method('sql_query');
		$config = new \phpbb\config\config([]);
		$migration = new $class($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->$method();
	}

	public function test_sqlite_uninstall_drops_exact_owned_quoted_index()
	{
		$config = new \phpbb\config\config([
			\vse\similartopics\driver\sqlite3::OWNED_INDEX_CONFIG => 'idx_' . TOPICS_TABLE . '_topic_title',
		]);
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturn(['name' => 'idx_' . TOPICS_TABLE . '_topic_title']);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\sqlite3_index($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->drop_sqlite3_index();

		$this->assertSame('DROP INDEX IF EXISTS "idx_' . TOPICS_TABLE . '_topic_title"', $queries[1]);
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\sqlite3::OWNED_INDEX_CONFIG));
	}

	public function test_driver_records_fresh_sqlite_index_ownership()
	{
		$config = new \phpbb\config\config([]);
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			false,
			['name' => 'idx_' . TOPICS_TABLE . '_topic_title']
		);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\sqlite3_index($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->create_sqlite3_index();

		$this->assertCount(1, array_filter($queries, function ($sql) {
			return strpos($sql, 'CREATE INDEX ') === 0;
		}));
		$this->assertSame('idx_' . TOPICS_TABLE . '_topic_title', $config[\vse\similartopics\driver\sqlite3::OWNED_INDEX_CONFIG]);
	}

	public function test_driver_does_not_claim_preexisting_sqlite_index()
	{
		$config = new \phpbb\config\config([]);
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->expects($this->once())->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(['name' => 'idx_' . TOPICS_TABLE . '_topic_title']);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\sqlite3_index($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->create_sqlite3_index();

		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\sqlite3::OWNED_INDEX_CONFIG));
	}

	public function test_driver_records_fresh_mysql_index_ownership()
	{
		$config = new \phpbb\config\config([]);
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_server_info')->willReturn('5.7.0');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['Engine' => 'InnoDB'],
			false,
			['Index_type' => 'FULLTEXT', 'Key_name' => 'topic_title'],
			false
		);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_5_x\mysql_index($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->add_topic_title_fulltext();

		$this->assertCount(1, array_filter($queries, function ($sql) {
			return strpos($sql, 'ALTER TABLE ') === 0 && strpos($sql, 'ADD FULLTEXT') !== false;
		}));
		$this->assertSame('topic_title', $config[\vse\similartopics\driver\mysqli::OWNED_INDEX_CONFIG]);
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\mysqli::ORIGINAL_ENGINE_CONFIG));
	}

	public function test_driver_drops_owned_mysql_index_and_restores_changed_engine()
	{
		$config = new \phpbb\config\config([
			\vse\similartopics\driver\mysqli::OWNED_INDEX_CONFIG => 'topic_title',
			\vse\similartopics\driver\mysqli::ORIGINAL_ENGINE_CONFIG => 'innodb',
		]);
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['Engine' => 'MyISAM'],
			['Index_type' => 'FULLTEXT', 'Key_name' => 'topic_title'],
			false
		);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_1_0_data($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->drop_topic_title_fulltext();

		$this->assertContains('ALTER TABLE `' . TOPICS_TABLE . '` DROP INDEX `topic_title`', $queries);
		$this->assertContains('ALTER TABLE `' . TOPICS_TABLE . '` ENGINE = INNODB', $queries);
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\mysqli::OWNED_INDEX_CONFIG));
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\mysqli::ORIGINAL_ENGINE_CONFIG));
	}

	public function test_effectively_installed_legacy_mysql_migration_sees_later_marker_on_revert()
	{
		$config = new \phpbb\config\config([]);
		$migration = new \vse\similartopics\migrations\release_1_3_0_fulltext($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$this->assertTrue($migration->effectively_installed());
		$config['similar_topics_fulltext'] = 'innodb';

		$container = $this->createMock('\Symfony\Component\DependencyInjection\ContainerInterface');
		$migrator = $this->getMockBuilder('\phpbb\db\migrator')
			->setConstructorArgs([
				$container,
				$config,
				$this->db,
				$this->db_tools,
				'phpbb_migrations',
				'',
				'php',
				'phpbb_',
				[],
				new \phpbb\db\migration\helper(),
			])
			->setMethods(['load_migration_state', 'set_migration_state', 'process_data_step', 'get_migration'])
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
		$state->setValue($migrator, [
			$migration_name => [
				'migration_depends_on' => $migration->depends_on(),
				'migration_schema_done' => true,
				'migration_data_done' => true,
				'migration_data_state' => '',
				'migration_start_time' => 0,
				'migration_end_time' => 0,
			],
		]);
		$try_revert = $reflection->getMethod('try_revert');
		$try_revert->setAccessible(true);
		$try_revert->invoke($migrator, $migration_name);

		$this->assertTrue($captured_steps[0][1][0]);
		$this->assertSame('revert_fulltext_changes', $captured_steps[0][1][1][1][0][1]);
		$this->assertTrue($captured_steps[1][1][0]);
	}

	public function test_mssql_uninstall_preserves_owned_index_with_additional_column()
	{
		$config = new \phpbb\config\config([
			\vse\similartopics\driver\mssql::OWNED_INDEX_CONFIG => 'topic_title',
		]);
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['name' => 'topic_title'],
			['name' => 'topic_body'],
			false
		);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\mssql_index($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->drop_mssql_fulltext_index();

		$this->assertCount(1, $queries);
		$this->assertStringContainsString('sys.fulltext_index_columns', $queries[0]);
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\mssql::OWNED_INDEX_CONFIG));
	}

	public function test_oracle_uninstall_drops_exact_owned_quoted_index()
	{
		$index_name = strtoupper(TOPICS_TABLE . '_topic_title_ctx_idx');
		$config = new \phpbb\config\config([
			\vse\similartopics\driver\oracle::OWNED_INDEX_CONFIG => $index_name,
		]);
		$this->db->method('get_sql_layer')->willReturn('oracle');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturn(['index_name' => $index_name]);
		$this->db->method('sql_freeresult');
		$migration = new \vse\similartopics\migrations\release_1_7_x\oracle_index($config, $this->db, $this->db_tools, '', 'php', 'phpbb_', []);

		$migration->drop_oracle_fulltext_index();

		$this->assertSame('DROP INDEX "' . $index_name . '"', $queries[1]);
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\oracle::OWNED_INDEX_CONFIG));
	}
}
