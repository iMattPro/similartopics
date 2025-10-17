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

class new_drivers_test extends \phpbb_test_case
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	protected function setUp(): void
	{
		parent::setUp();

		// Mock database driver
		$this->db = $this->getMockBuilder('\phpbb\db\driver\driver_interface')
			->getMock();
	}

	public function test_mssql_driver_instantiation()
	{
		$driver = new \vse\similartopics\driver\mssql($this->db);

		self::assertEquals('mssql', $driver->get_name());
		self::assertEquals('mssql', $driver->get_type());
	}

	public function test_mssqlnative_driver_instantiation()
	{
		$driver = new \vse\similartopics\driver\mssqlnative($this->db);

		self::assertEquals('mssqlnative', $driver->get_name());
		self::assertEquals('mssql', $driver->get_type());
	}

	public function test_sqlite3_driver_instantiation()
	{
		$driver = new \vse\similartopics\driver\sqlite3($this->db);

		self::assertEquals('sqlite3', $driver->get_name());
		self::assertEquals('sqlite', $driver->get_type());
	}

	public function test_mssql_driver_query_structure()
	{
		$this->db->method('sql_escape')->willReturnArgument(0);

		$driver = new \vse\similartopics\driver\mssql($this->db);
		$query = $driver->get_query(1, 'test topic', 86400, 0.5);

		self::assertArrayHasKey('SELECT', $query);
		self::assertArrayHasKey('FROM', $query);
		self::assertArrayHasKey('LEFT_JOIN', $query);
		self::assertArrayHasKey('WHERE', $query);
		self::assertArrayHasKey('ORDER_BY', $query);

		if ($driver->is_fulltext('topic_title', TOPICS_TABLE))
		{
			self::assertStringContainsString('CONTAINS', $query['WHERE']);
			self::assertStringContainsString('CONTAINS', $query['SELECT']);
		}
		else
		{
			self::assertStringContainsString('LIKE', $query['WHERE']);
		}
		self::assertStringContainsString('CASE WHEN', $query['SELECT']);
	}

	public function test_sqlite3_driver_query_structure()
	{
		$this->db->method('sql_escape')->willReturnArgument(0);

		$driver = new \vse\similartopics\driver\sqlite3($this->db);
		$query = $driver->get_query(1, 'test topic', 86400, 0.5);

		self::assertArrayHasKey('SELECT', $query);
		self::assertArrayHasKey('FROM', $query);
		self::assertArrayHasKey('LEFT_JOIN', $query);
		self::assertArrayHasKey('WHERE', $query);
		self::assertArrayHasKey('ORDER_BY', $query);

		self::assertStringContainsString('LIKE', $query['WHERE']);
	}
}
