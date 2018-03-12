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

	public function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/fixtures/config.xml');
	}

	public function setUp()
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
		$this->assertInstanceOf('\\vse\\similartopics\\driver\\' . $this->db->get_sql_layer(), $this->get_driver());
	}

	public function test_get_driver_fails()
	{
		$this->assertNull($this->manager->get_driver('foo'));
	}

	public function test_get_name()
	{
		$this->assertEquals($this->db->get_sql_layer(), $this->get_driver()->get_name());
	}

	public function test_get_type()
	{
		$this->assertSame(0, strpos($this->db->get_sql_layer(), $this->get_driver()->get_type()));
	}

	public function test_get_query()
	{
		$sql = $this->get_driver()->get_query(1, 'foo bar', 0, 0);

		if ($this->db->get_sql_layer() === 'postgres')
		{
			$select = "f.forum_id, f.forum_name, t.*, ts_rank_cd(to_tsvector('simple', t.topic_title), to_tsquery('foo|bar'), 32) AS score";
			$where = "ts_rank_cd(to_tsvector('simple', t.topic_title), to_tsquery('foo|bar'), 32) >= 0 AND t.topic_status <> 2 AND t.topic_visibility = 1 AND t.topic_time > (extract(epoch from current_timestamp)::integer - 0) AND t.topic_id <> 1";
		}
		else
		{
			$select = "f.forum_id, f.forum_name, t.*, MATCH (t.topic_title) AGAINST ('foo bar') AS score";
			$where = "MATCH (t.topic_title) AGAINST ('foo bar') >= 0 AND t.topic_status <> 2 AND t.topic_visibility = 1 AND t.topic_time > (UNIX_TIMESTAMP() - 0) AND t.topic_id <> 1";
		}

		$this->assertEquals($select, preg_replace('#\s\s+#', ' ', $sql['SELECT']));
		$this->assertArrayHasKey('FROM', $sql);
		$this->assertArrayHasKey('LEFT_JOIN', $sql);
		$this->assertEquals($where, preg_replace('#\s\s+#', ' ', $sql['WHERE']));
	}

	public function test_is_supported()
	{
		$this->assertTrue($this->get_driver()->is_supported(), 'Fulltext support failed.');
	}

	public function test_index()
	{
		$driver = $this->get_driver();

		$column = 'topic_title';

		// Check that the topic_title is NOT a fulltext index
		$this->assertFalse($driver->is_index($column));

		// Make topic_title a fulltext index
		$driver->create_fulltext_index($column);

		// No check that the topic_title is a fulltext index
		$this->assertTrue($driver->is_index($column));
	}
}
