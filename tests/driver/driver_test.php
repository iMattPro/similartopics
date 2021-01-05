<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\driver;

class driver_test extends \phpbb_database_test_case
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \vse\similartopics\driver\manager */
	protected $manager;

	protected static function setup_extensions()
	{
		return array('vse/similartopics');
	}

	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/fixtures/config.xml');
	}

	protected function setUp(): void
	{
		parent::setUp();

		$this->db = $this->new_dbal();
		$this->config = new \phpbb\config\config(array());
		$drivers = array(
			'postgres',
			'mysql4',
			'mysqli',
		);
		$services = array();
		foreach ($drivers as $driver)
		{
			$class = "\\vse\\similartopics\\driver\\$driver";
			if ($driver === 'postgres')
			{
				$services['vse.similartopics.driver.' . $driver] = new $class($this->db, $this->config);
			}
			else
			{
				$services['vse.similartopics.driver.' . $driver] = new $class($this->db);
			}
		}

		$this->manager = new \vse\similartopics\driver\manager($services);
	}

	public function get_driver()
	{
		return $this->manager->get_driver($this->db->get_sql_layer());
	}

	public function test_get_driver()
	{
		self::assertInstanceOf('\\vse\\similartopics\\driver\\' . $this->db->get_sql_layer(), $this->get_driver());
	}

	public function test_get_driver_fails()
	{
		self::assertNull($this->manager->get_driver('foo'));
	}

	public function test_get_name()
	{
		self::assertEquals($this->db->get_sql_layer(), $this->get_driver()->get_name());
	}

	public function test_get_type()
	{
		self::assertSame(0, strpos($this->db->get_sql_layer(), $this->get_driver()->get_type()));
	}

	public function test_get_query()
	{
		$sql = $this->get_driver()->get_query(1, 'foo bar', 0, 0);

		if ($this->db->get_sql_layer() === 'postgres')
		{
			$select = "f.forum_id, f.forum_name, t.*, ts_rank_cd('{1,1,1,1}', to_tsvector('simple', t.topic_title), to_tsquery('simple', 'foo|bar'), 32) AS score";
			$where = "to_tsquery('simple', 'foo|bar') @@ to_tsvector('simple', t.topic_title) AND ts_rank_cd('{1,1,1,1}', to_tsvector('simple', t.topic_title), to_tsquery('simple', 'foo|bar'), 32) >= 0 AND t.topic_status <> 2 AND t.topic_visibility = 1 AND t.topic_time > (extract(epoch from current_timestamp)::integer - 0) AND t.topic_id <> 1";
		}
		else
		{
			$select = "f.forum_id, f.forum_name, t.*, MATCH (t.topic_title) AGAINST ('foo bar') AS score";
			$where = "MATCH (t.topic_title) AGAINST ('foo bar') >= 0 AND t.topic_status <> 2 AND t.topic_visibility = 1 AND t.topic_time > (UNIX_TIMESTAMP() - 0) AND t.topic_id <> 1";
		}

		self::assertEquals($select, preg_replace('#\s\s+#', ' ', $sql['SELECT']));
		self::assertArrayHasKey('FROM', $sql);
		self::assertArrayHasKey('LEFT_JOIN', $sql);
		self::assertEquals($where, preg_replace('#\s\s+#', ' ', $sql['WHERE']));
	}

	public function test_is_supported()
	{
		$driver = $this->get_driver();

		$unsupported = $driver->get_engine() === 'innodb' && phpbb_version_compare($this->db->sql_server_info(true), '5.6.4', '<');

		self::assertSame(!$unsupported, $this->get_driver()->is_supported());
	}

	public function test_index()
	{
		$driver = $this->get_driver();

		$column = 'topic_title';

		// Check that the topic_title is NOT a fulltext index
		self::assertFalse($driver->is_fulltext($column));

		// Make topic_title a fulltext index
		$driver->create_fulltext_index($column);

		// Now check that the topic_title is a fulltext index
		self::assertTrue($driver->is_fulltext($column));
	}
}
