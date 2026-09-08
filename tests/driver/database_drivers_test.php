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

	public function driver_data_provider()
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

	public function search_period_data_provider()
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

	public function test_mssql_fulltext_lookup_reads_indexed_columns()
	{
		$this->db->method('get_sql_layer')->willReturn('mssqlnative');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->expects($this->once())
			->method('sql_query')
			->with($this->callback(function ($sql) {
				return strpos($sql, 'sys.fulltext_index_columns') !== false
					&& strpos($sql, 'sys.columns') !== false
					&& strpos($sql, 'sys.indexes') === false;
			}))
			->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(['name' => 'topic_title'], false);
		$this->db->method('sql_freeresult');

		$this->assertTrue((new \vse\similartopics\driver\mssql($this->db))->is_fulltext('topic_title'));
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

	public function test_oracle_search_period_uses_unix_timestamp()
	{
		$this->db->method('sql_escape')->willReturnArgument(0);

		$driver = new \vse\similartopics\driver\oracle($this->db);
		$query = $driver->get_query(1, 'test topic', 86400, 0.5);

		$this->assertStringContainsString('SYS_EXTRACT_UTC(SYSTIMESTAMP)', $query['WHERE']);
		$this->assertStringContainsString("DATE '1970-01-01'", $query['WHERE']);
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
		$queries = array();
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				false,
				['IsFullTextInstalled' => 1]
			);
		$this->db->method('sql_freeresult');
		$config = new \phpbb\config\config(array());

		$driver = new \vse\similartopics\driver\mssql($this->db, $config);
		$driver->create_fulltext_index();

		$this->assertTrue((bool) array_filter($queries, function ($sql) {
			return strpos($sql, 'CREATE FULLTEXT INDEX') !== false;
		}));
		$this->assertSame(TOPICS_TABLE, $config[\vse\similartopics\driver\driver_interface::OWNED_INDEX_CONFIG]);
	}

	public function test_mssql_preserves_existing_fulltext_index_on_other_column()
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = array();
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			array('name' => 'topic_body'),
			false
		);
		$this->db->method('sql_freeresult');
		$config = new \phpbb\config\config(array());

		(new \vse\similartopics\driver\mssql($this->db, $config))->create_fulltext_index();

		$this->assertFalse((bool) array_filter($queries, function ($sql) {
			return strpos($sql, 'CREATE FULLTEXT') !== false;
		}));
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\driver_interface::OWNED_INDEX_CONFIG));
	}

	public function test_mysqli_uses_differently_named_single_column_fulltext_index()
	{
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_server_info')->willReturn('5.7.0');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$queries = array();
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$queries) {
			$queries[] = $sql;
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			array('Engine' => 'InnoDB'),
			array('Index_type' => 'FULLTEXT', 'Key_name' => 'custom_title_ft', 'Seq_in_index' => 1, 'Column_name' => 'topic_title'),
			false
		);
		$this->db->method('sql_freeresult');
		$config = new \phpbb\config\config(array());

		$driver = new \vse\similartopics\driver\mysqli($this->db, $config);
		$driver->create_fulltext_index();

		$this->assertFalse((bool) array_filter($queries, function ($sql) {
			return strpos($sql, 'ADD FULLTEXT') !== false;
		}));
		$this->assertFalse($config->offsetExists(\vse\similartopics\driver\driver_interface::OWNED_INDEX_CONFIG));
	}

	public function test_mysqli_rejects_composite_fulltext_index_for_single_column_match()
	{
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_server_info')->willReturn('5.7.0');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			array('Engine' => 'InnoDB'),
			array('Index_type' => 'FULLTEXT', 'Key_name' => 'combined_ft', 'Seq_in_index' => 1, 'Column_name' => 'topic_title'),
			array('Index_type' => 'FULLTEXT', 'Key_name' => 'combined_ft', 'Seq_in_index' => 2, 'Column_name' => 'topic_body'),
			false
		);
		$this->db->method('sql_freeresult');

		$this->assertSame(array(), (new \vse\similartopics\driver\mysqli($this->db))->get_fulltext_indexes());
	}

	public function test_oracle_get_fulltext_indexes()
	{
		$this->db->method('get_sql_layer')->willReturn('oracle');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->expects($this->once())
			->method('sql_query')
			->with($this->callback(function ($sql) {
				return strpos($sql, 'EXISTS') !== false
					&& strpos($sql, 'user_ind_columns') !== false
					&& strpos($sql, "i.ityp_owner = 'CTXSYS'") !== false
					&& strpos($sql, "i.ityp_name = 'CONTEXT'") !== false;
			}))
			->willReturn(true);
		$this->db->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['index_name' => 'topics_topic_title_ctx_idx'],
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
				false
			);
		$this->db->method('sql_freeresult');

		$driver = new \vse\similartopics\driver\postgres($this->db, $this->config);

		$indexes = $driver->get_fulltext_indexes();
		$this->assertIsArray($indexes);

		// Test that is_fulltext returns false when no matching index exists
		$this->assertFalse($driver->is_fulltext());
	}

	public function test_postgres_query_treats_tsquery_punctuation_as_plain_text()
	{
		$this->db->method('sql_escape')->willReturnCallback(function ($value) {
			return str_replace("'", "''", $value);
		});
		$query = (new \vse\similartopics\driver\postgres($this->db, $this->config))
			->get_query(1, "alpha | beta & gamma:* (!delta) O'Reilly", 0, 0.5);

		$expected = "(plainto_tsquery('english', 'alpha') || plainto_tsquery('english', 'beta') || plainto_tsquery('english', 'gamma') || plainto_tsquery('english', 'delta') || plainto_tsquery('english', 'O''Reilly'))";
		$this->assertStringContainsString($expected . " @@ to_tsvector('english', t.topic_title)", $query['WHERE']);
		$this->assertStringNotContainsString('alpha|||beta', $query['WHERE']);
		$this->assertStringNotContainsString('gamma:*', $query['WHERE']);
	}

	public function test_postgres_query_handles_punctuation_only_title()
	{
		$this->db->method('sql_escape')->willReturnArgument(0);
		$query = (new \vse\similartopics\driver\postgres($this->db, $this->config))
			->get_query(1, '| & ! :* ()', 0, 0.5);

		$this->assertStringContainsString("(plainto_tsquery('english', '')) @@", $query['WHERE']);
	}

	public function test_deprecated_fulltext_support_wrapper()
	{
		$this->db->method('get_sql_layer')->willReturn('unsupported');
		$driver = new \vse\similartopics\core\fulltext_support($this->db);

		$this->assertFalse($driver->is_index('subject'));
	}

	public function test_mssql_fulltext_failures_are_treated_as_unavailable()
	{
		$this->db->method('get_sql_layer')->willReturn('mssql');
		$this->db->method('sql_query')->willThrowException(new \RuntimeException('Full-text unavailable'));
		$driver = new \vse\similartopics\driver\mssql($this->db);

		$this->assertSame([], $driver->get_fulltext_indexes());
		$driver->create_fulltext_index();
		$this->addToAssertionCount(1);
	}

	public function test_mysqli_unsupported_index_lookup_and_engine_conversion()
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

	public function test_mysqli_myisam_is_supported()
	{
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(['Type' => 'MyISAM']);
		$this->db->method('sql_freeresult');

		$this->assertTrue((new \vse\similartopics\driver\mysqli($this->db))->is_supported());
	}

	public function postgres_existing_indexes_data()
	{
		return [
			'current pre-existing index preserved' => [['phpbb_topics_english_topic_title'], 0, false],
			'old pre-existing index preserved' => [['phpbb_topics_simple_topic_title'], 1, true],
			'unrelated expression index preserved' => [['admin_topic_title_search'], 1, true],
		];
	}

	/**
	 * @dataProvider postgres_existing_indexes_data
	 */
	public function test_postgres_preserves_unowned_indexes($indexes, $expected_writes, $owns_new_index)
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
		$rows = array_map(function ($index) {
			return ['relname' => $index];
		}, $indexes);
		$rows[] = false;
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(...$rows);
		$this->db->method('sql_fetchrowset')->willReturn([
			['ts_name' => 'simple'],
			['ts_name' => 'english'],
		]);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->create_fulltext_index();

		$this->assertCount($expected_writes, $writes);
		$this->assertSame(
			$owns_new_index,
			$this->config->offsetExists(\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG)
		);
	}

	public function test_postgres_drops_only_owned_index()
	{
		$this->config[\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG] = 'phpbb_topics_simple_topic_title';
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->expects($this->exactly(2))->method('sql_query')
			->withConsecutive(
				[$this->stringContains('SELECT c2.relname')],
				['DROP INDEX "phpbb_topics_simple_topic_title"']
			)
			->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['relname' => 'phpbb_topics_simple_topic_title'],
			['relname' => 'phpbb_topics_english_topic_title'],
			['relname' => 'admin_topic_title_search'],
			false
		);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->drop_fulltext_indexes();

		$this->assertFalse($this->config->offsetExists(\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG));
	}

	public function test_postgres_does_not_drop_index_without_ownership()
	{
		$this->db->expects($this->never())->method('sql_query');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->drop_fulltext_indexes();
	}

	public function test_postgres_replaces_owned_index_when_configuration_changes()
	{
		$this->config[\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG] = 'phpbb_topics_simple_topic_title';
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$writes = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$writes) {
			if (strpos($sql, 'SELECT ') !== 0)
			{
				$writes[] = $sql;
			}
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['relname' => 'phpbb_topics_simple_topic_title'],
			false,
			['relname' => 'phpbb_topics_simple_topic_title'],
			false
		);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->create_fulltext_index();

		$this->assertCount(2, $writes);
		$this->assertSame('DROP INDEX "phpbb_topics_simple_topic_title"', $writes[0]);
		$this->assertStringContainsString('CREATE INDEX "phpbb_topics_english_topic_title"', $writes[1]);
		$this->assertSame(
			'phpbb_topics_english_topic_title',
			$this->config[\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG]
		);
	}

	public function test_postgres_drops_old_owned_index_when_target_exists()
	{
		$this->config[\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG] = 'phpbb_topics_simple_topic_title';
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$writes = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$writes) {
			if (strpos($sql, 'SELECT ') !== 0)
			{
				$writes[] = $sql;
			}
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['relname' => 'phpbb_topics_english_topic_title'],
			false,
			['relname' => 'phpbb_topics_simple_topic_title'],
			['relname' => 'phpbb_topics_english_topic_title'],
			false
		);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->create_fulltext_index();

		$this->assertSame(['DROP INDEX "phpbb_topics_simple_topic_title"'], $writes);
		$this->assertFalse($this->config->offsetExists(
			\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG
		));
	}

	public function test_postgres_clears_ownership_when_owned_index_is_missing()
	{
		$this->config[\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG] = 'missing_index';
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->expects($this->once())
			->method('sql_query')
			->with($this->stringContains('SELECT c2.relname'))
			->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['relname' => 'admin_topic_title_search'],
			false
		);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->drop_owned_fulltext_index();

		$this->assertFalse($this->config->offsetExists(
			\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG
		));
	}

	public function ownership_guard_driver_data()
	{
		return [
			['mysqli'],
			['mssql'],
			['oracle'],
			['sqlite3'],
		];
	}

	/**
	 * @dataProvider ownership_guard_driver_data
	 */
	public function test_drop_owned_index_without_config_does_nothing($driver_class)
	{
		$this->db->expects($this->never())->method('sql_query');
		$class = '\\vse\\similartopics\\driver\\' . $driver_class;

		$this->assertNull((new $class($this->db))->drop_owned_fulltext_index());
	}

	/**
	 * @dataProvider ownership_guard_driver_data
	 */
	public function test_drop_owned_index_without_ownership_config_does_nothing($driver_class)
	{
		$this->db->expects($this->never())->method('sql_query');
		$class = '\\vse\\similartopics\\driver\\' . $driver_class;

		$this->assertNull((new $class($this->db, $this->config))->drop_owned_fulltext_index());
		$this->assertFalse($this->config->offsetExists(
			\vse\similartopics\driver\driver_interface::OWNED_INDEX_CONFIG
		));
	}

	public function test_postgres_preserves_truncated_legacy_index()
	{
		$ts_name = str_repeat('x', 60);
		$legacy_index = substr('phpbb_topics_' . $ts_name . '_topic_title', 0, 63);
		$this->config['pst_postgres_ts_name'] = $ts_name;
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$writes = [];
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$writes) {
			if (strpos($sql, 'SELECT ') !== 0)
			{
				$writes[] = $sql;
			}
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			['relname' => $legacy_index],
			false
		);
		$this->db->method('sql_fetchrowset')->willReturn([
			['ts_name' => $ts_name],
		]);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->create_fulltext_index();

		$this->assertCount(1, $writes);
		$this->assertSame(0, strpos($writes[0], 'CREATE INDEX "'));
		$this->assertStringNotContainsString('DROP INDEX', $writes[0]);
		$this->assertStringNotContainsString('CREATE INDEX "' . $legacy_index . '"', $writes[0]);
		$this->assertTrue($this->config->offsetExists(
			\vse\similartopics\driver\postgres::OWNED_INDEX_CONFIG
		));
	}

	public function test_postgres_quotes_and_sanitizes_index_identifiers()
	{
		$writes = [];
		$this->config['pst_postgres_ts_name'] = 'english;DROP_INDEX';
		$this->db->method('get_sql_layer')->willReturn('postgres');
		$this->db->method('sql_escape')->willReturnArgument(0);
		$this->db->method('sql_query')->willReturnCallback(function ($sql) use (&$writes) {
			if (strpos($sql, 'SELECT ') !== 0)
			{
				$writes[] = $sql;
			}
			return true;
		});
		$this->db->method('sql_fetchrow')->willReturnOnConsecutiveCalls(
			false
		);
		$this->db->method('sql_fetchrowset')->willReturn([
			['ts_name' => 'english;DROP_INDEX'],
		]);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\postgres($this->db, $this->config))->create_fulltext_index();

		$this->assertCount(1, $writes);
		$this->assertStringContainsString('CREATE INDEX "phpbb_topics_english_DROP_INDEX_topic_title"', $writes[0]);
		$this->assertStringContainsString('ON "phpbb_topics"', $writes[0]);
		$this->assertStringContainsString(', "topic_title"))', $writes[0]);
	}

	public function test_sqlite_existing_index_is_not_recreated()
	{
		$this->db->method('get_sql_layer')->willReturn('sqlite3');
		$this->db->expects($this->once())->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchrow')->willReturn(['name' => 'idx_phpbb_topics_topic_title']);
		$this->db->method('sql_freeresult');

		(new \vse\similartopics\driver\sqlite3($this->db))->create_fulltext_index();
	}

	protected function create_driver($driver_class)
	{
		switch ($driver_class)
		{
			case 'mysqli':
				return new \vse\similartopics\driver\mysqli($this->db);
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
