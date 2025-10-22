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

class database_drivers_test extends \phpbb_test_case
{
	/** @var \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	protected function setUp(): void
	{
		parent::setUp();
		$this->db = $this->createMock('\phpbb\db\driver\driver_interface');
		$this->config = new \phpbb\config\config(['pst_postgres_ts_name' => 'english']);
	}

	public static function driver_data_provider()
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
	public function test_driver_basic_properties($driver_class, $expected_name, $expected_type)
	{
		$driver = $this->create_driver($driver_class);

		$this->assertEquals($expected_name, $driver->get_name());
		$this->assertEquals($expected_type, $driver->get_type());
	}

	public function test_mssql_driver()
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

		$driver = new \vse\similartopics\driver\mssql($this->db);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('', $driver->get_engine());
		$this->assertFalse($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('CONTAINS', $query['WHERE']);
	}

	public function test_mssql_driver_without_fulltext()
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(false);
		$this->db->method('sql_freeresult');

		$driver = new \vse\similartopics\driver\mssql($this->db);

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertStringContainsString('LIKE', $query['WHERE']);
	}

	public function test_postgres_driver()
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

		$driver = new \vse\similartopics\driver\postgres($this->db, $this->config);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('', $driver->get_engine());
		$this->assertTrue($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('to_tsquery', $query['WHERE']);
		$this->assertStringContainsString('ts_rank_cd', $query['SELECT']);
	}

	public function test_oracle_driver()
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

		$driver = new \vse\similartopics\driver\oracle($this->db);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('oracle', $driver->get_engine());
		$this->assertTrue($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('CONTAINS', $query['WHERE']);
		$this->assertStringContainsString('SCORE(1)', $query['SELECT']);
	}

	public function test_sqlite3_driver()
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

		$driver = new \vse\similartopics\driver\sqlite3($this->db);

		$this->assertTrue($driver->is_supported());
		$this->assertEquals('', $driver->get_engine());
		$this->assertFalse($driver->has_stopword_support());

		$query = $driver->get_query(1, 'test topic', 86400, 0.5);
		$this->assertArrayHasKey('SELECT', $query);
		$this->assertStringContainsString('LIKE', $query['WHERE']);
	}

	public function test_fulltext_index_operations()
	{
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(false);
		$this->db->method('sql_freeresult');

		$drivers = [
			new \vse\similartopics\driver\mssql($this->db),
			new \vse\similartopics\driver\postgres($this->db, $this->config),
			new \vse\similartopics\driver\oracle($this->db),
			new \vse\similartopics\driver\sqlite3($this->db)
		];

		foreach ($drivers as $driver)
		{
			$indexes = $driver->get_fulltext_indexes();
			$this->assertIsArray($indexes);

			$driver->create_fulltext_index();
			$this->addToAssertionCount(1);
		}
	}

	public function test_postgres_cfg_name_list()
	{
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrowset')->willReturn([
			['ts_name' => 'simple'],
			['ts_name' => 'english']
		]);
		$this->db->method('sql_freeresult');

		$driver = new \vse\similartopics\driver\postgres($this->db, $this->config);
		$cfg_list = $driver->get_cfg_name_list();

		$this->assertIsArray($cfg_list);
		$this->assertCount(2, $cfg_list);
	}

	public function test_unsupported_drivers()
	{
		$this->db->method('get_sql_layer')->willReturn('unsupported');

		$drivers = [
			new \vse\similartopics\driver\mssql($this->db),
			new \vse\similartopics\driver\postgres($this->db, $this->config),
			new \vse\similartopics\driver\oracle($this->db),
			new \vse\similartopics\driver\sqlite3($this->db)
		];

		foreach ($drivers as $driver)
		{
			$this->assertFalse($driver->is_supported());
			$this->assertEquals([], $driver->get_fulltext_indexes());
		}
	}

	public function test_mssql_create_fulltext_index()
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['IsFullTextInstalled' => 1],
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new \vse\similartopics\driver\mssql($this->db);
		$driver->create_fulltext_index();
		$this->addToAssertionCount(1);
	}

	public function test_oracle_get_fulltext_indexes()
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

		$driver = new \vse\similartopics\driver\oracle($this->db);
		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);
		$this->assertContains('topic_title', $indexes);
	}

	public function test_sqlite3_fulltext_methods()
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

		$driver = new \vse\similartopics\driver\sqlite3($this->db);

		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);

		$this->assertTrue($driver->is_fulltext());
	}

	public function test_postgres_fulltext_methods()
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

		$driver = new \vse\similartopics\driver\postgres($this->db, $this->config);

		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);

		// Test that is_fulltext returns false when no matching index exists
		$this->assertFalse($driver->is_fulltext());
	}

	protected function create_driver($driver_class)
	{
		switch ($driver_class)
		{
			case 'mssql':
				return new \vse\similartopics\driver\mssql($this->db);
			case 'postgres':
				return new \vse\similartopics\driver\postgres($this->db, $this->config);
			case 'oracle':
				return new \vse\similartopics\driver\oracle($this->db);
			case 'sqlite3':
				return new \vse\similartopics\driver\sqlite3($this->db);
		}
		return null;
	}
}
