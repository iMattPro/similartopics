<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\driver;

use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use vse\similartopics\driver\mysqli;
use vse\similartopics\driver\mssql;
use vse\similartopics\driver\oracle;
use vse\similartopics\driver\postgres;
use vse\similartopics\driver\sqlite3;

class database_drivers_test extends phpbb_test_case
{
	/** @var MockObject|driver_interface */
	protected MockObject|driver_interface $db;

	/** @var config */
	protected config $config;

	protected function setUp(): void
	{
		parent::setUp();
		$this->db = $this->createMock(driver_interface::class);
		$this->config = new config(['pst_postgres_ts_name' => 'english']);
	}

	public static function driver_data_provider(): array
	{
		return [
			'mssql' => ['mssql', 'mssql', 'mssql'],
			'postgres' => ['postgres', 'postgres', 'postgres'],
			'oracle' => ['oracle', 'oracle', 'oracle'],
			'sqlite3' => ['sqlite3', 'sqlite3', 'sqlite']
		];
	}

	/**
	 * @dataProvider driver_data_provider
	 */
	public function test_driver_basic_properties($driver_class, $expected_name, $expected_type): void
	{
		$driver = $this->create_driver($driver_class);

		$this->assertEquals($expected_name, $driver->get_name());
		$this->assertEquals($expected_type, $driver->get_type());
	}

	public static function search_period_data_provider(): array
	{
		return [
			'mysqli unlimited' => ['mysqli', 0, false],
			'mysqli limited' => ['mysqli', 86400, true],
			'mssql unlimited' => ['mssql', 0, false],
			'mssql limited' => ['mssql', 86400, true],
			'postgres unlimited' => ['postgres', 0, false],
			'postgres limited' => ['postgres', 86400, true],
			'oracle unlimited' => ['oracle', 0, false],
			'oracle limited' => ['oracle', 86400, true],
			'sqlite3 unlimited' => ['sqlite3', 0, false],
			'sqlite3 limited' => ['sqlite3', 86400, true],
		];
	}

	/**
	 * @dataProvider search_period_data_provider
	 */
	public function test_search_period($driver_class, $length, $has_time_limit)
	{
		$this->db->method('sql_escape')->willReturnArgument(0);
		$query = $this->create_driver($driver_class)->get_query(1, 'test topic', $length, 0.5);

		if ($has_time_limit)
		{
			$this->assertStringContainsString('t.topic_time >', $query['WHERE']);
			$this->assertStringContainsString((string) $length, $query['WHERE']);
		}
		else
		{
			$this->assertStringNotContainsString('t.topic_time >', $query['WHERE']);
		}
	}

	public function test_mssql_driver(): void
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['name' => 'topic_title'],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new mssql($this->db);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('', $driver->get_engine());
		$this->assertFalse($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('CONTAINS', $query['WHERE']);
	}

	public function test_mssql_driver_without_fulltext(): void
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(false);
		$this->db->method('sql_freeresult');

		$driver = new mssql($this->db);

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertStringContainsString('LIKE', $query['WHERE']);
	}

	public function test_postgres_driver(): void
	{
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['relname' => 'topics_english_topic_title'],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new postgres($this->db, $this->config);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('', $driver->get_engine());
		$this->assertTrue($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('to_tsquery', $query['WHERE']);
		$this->assertStringContainsString('ts_rank_cd', $query['SELECT']);
	}

	public function test_oracle_driver(): void
	{
		$this->db->method('get_sql_layer')->willReturn('oracle');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['index_name' => 'topics_topic_title_ctx_idx'],
				['column_name' => 'TOPIC_TITLE'],
				false,
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new oracle($this->db);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('oracle', $driver->get_engine());
		$this->assertTrue($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('CONTAINS', $query['WHERE']);
		$this->assertStringContainsString('SCORE(1)', $query['SELECT']);
	}

	public function test_sqlite3_driver(): void
	{
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['name' => 'topics_fts'],
				false,
				['name' => 'idx_topics_topic_title'],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new sqlite3($this->db);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('', $driver->get_engine());
		$this->assertFalse($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('LIKE', $query['WHERE']);
	}

	public function test_fulltext_index_operations(): void
	{
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(false);
		$this->db->method('sql_freeresult');

		$drivers = [
			new mssql($this->db),
			new postgres($this->db, $this->config),
			new oracle($this->db),
			new sqlite3($this->db)
		];

		foreach ($drivers as $driver)
		{
			$indexes = $driver->get_fulltext_indexes();
			$this->assertIsArray($indexes);

			$driver->create_fulltext_index();
			$this->addToAssertionCount(1);
		}
	}

	public function test_postgres_cfg_name_list(): void
	{
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrowset')->willReturn([
			['ts_name' => 'simple'],
			['ts_name' => 'english']
		]);
		$this->db->method('sql_freeresult');

		$driver = new postgres($this->db, $this->config);
		$cfg_list = $driver->get_cfg_name_list();

		$this->assertIsArray($cfg_list);
		$this->assertCount(2, $cfg_list);
	}

	public function test_unsupported_drivers(): void
	{
		$this->db->method('get_sql_layer')->willReturn('unsupported');

		$drivers = [
			new mssql($this->db),
			new postgres($this->db, $this->config),
			new oracle($this->db),
			new sqlite3($this->db)
		];

		foreach ($drivers as $driver)
		{
			$this->assertFalse($driver->is_supported());
			$this->assertEquals([], $driver->get_fulltext_indexes());
		}
	}

	public function test_mssql_create_fulltext_index(): void
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['IsFullTextInstalled' => 1],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new mssql($this->db);
		$driver->create_fulltext_index();
		$this->addToAssertionCount(1);
	}

	public function test_oracle_get_fulltext_indexes(): void
	{
		$this->db->method('get_sql_layer')->willReturn('oracle');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['index_name' => 'topics_topic_title_ctx_idx'],
				['column_name' => 'TOPIC_TITLE'],
				false,
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new oracle($this->db);
		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);
		$this->assertContains('topic_title', $indexes);
	}

	public function test_sqlite3_fulltext_methods(): void
	{
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['name' => 'topics_fts'],
				false,
				['name' => 'idx_topics_topic_title'],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new sqlite3($this->db);

		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);

		$this->assertTrue($driver->is_fulltext());
	}

	public function test_postgres_fulltext_methods(): void
	{
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['relname' => 'topics_english_topic_title'],
				false,
				['relname' => 'topics_english_topic_title'],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new postgres($this->db, $this->config);

		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);

		// Test that is_fulltext returns false when no matching index exists
		$this->assertFalse($driver->is_fulltext());
	}

	public function test_deprecated_fulltext_support_wrapper(): void
	{
		$this->db->method('get_sql_layer')->willReturn('unsupported');
		$driver = new \vse\similartopics\core\fulltext_support($this->db);

		$this->assertFalse($driver->is_index('subject'));
	}

	public function test_mssql_fulltext_failures_are_treated_as_unavailable(): void
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_query')->willThrowException(new \RuntimeException('Full-text unavailable'));
		$driver = new \vse\similartopics\driver\mssql($this->db);

		$this->assertSame([], $driver->get_fulltext_indexes());
		$driver->create_fulltext_index();
		$this->addToAssertionCount(1);
	}

	public function test_mysqli_unsupported_index_lookup_and_engine_conversion(): void
	{
		$queries = [];
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_server_info')->willReturn('5.5.0');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['Engine' => 'InnoDB'],
			['Engine' => 'InnoDB']
		);
		$this->db->method('sql_freeresult');

		$driver = new \vse\similartopics\driver\mysqli($this->db);
		$this->assertSame([], $driver->get_fulltext_indexes());
		$driver->create_fulltext_index();

		$this->assertTrue((bool) array_filter($queries, function ($sql) {
			return strpos($sql, 'ENGINE = MYISAM') !== false;
		}));
		$this->assertTrue((bool) array_filter($queries, function ($sql) {
			return strpos($sql, 'ADD FULLTEXT') !== false;
		}));
	}

	public function test_mysqli_myisam_is_supported(): void
	{
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(['Type' => 'MyISAM']);
		$this->db->method('sql_freeresult');

		$this->assertTrue((new \vse\similartopics\driver\mysqli($this->db))->is_supported());
	}

	public static function postgres_existing_indexes_data(): array
	{
		return [
			'current index retained' => [['phpbb_topics_english_topic_title'], 0],
			'old index replaced' => [['phpbb_topics_simple_topic_title'], 2],
		];
	}

	/**
	 * @dataProvider postgres_existing_indexes_data
	 */
	public function test_postgres_reconciles_existing_indexes($indexes, $expected_writes): void
	{
		$writes = [];
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$writes) {
			if (strpos($sql, 'SELECT ') !== 0)
			{
				$writes[] = $sql;
			}
			return true;
		});
		$rows = array_map(function ($index) { return ['relname' => $index]; }, $indexes);
		$rows[] = false;
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(...$rows);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->create_fulltext_index();

		$this->assertCount($expected_writes, $writes);
	}

	public function test_sqlite_existing_index_is_not_recreated(): void
	{
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->expects($this->once())->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(['name' => 'idx_phpbb_topics_topic_title']);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\sqlite3($this->db))->create_fulltext_index();
	}

	protected function create_driver($driver_class): mysqli|mssql|oracle|postgres|sqlite3|null
	{
		return match ($driver_class) {
			'mysqli' => new mysqli($this->db),
			'mssql' => new mssql($this->db),
			'oracle' => new oracle($this->db),
			'postgres' => new postgres($this->db, $this->config),
			'sqlite3' => new sqlite3($this->db),
			default => null,
		};
	}
}
