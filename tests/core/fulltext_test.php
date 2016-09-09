<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\core;

require_once __DIR__ . '/../../../../../includes/functions.php';

class fulltext_test extends \phpbb_database_test_case
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \vse\similartopics\core\fulltext_support */
	protected $fulltext;

	static protected function setup_extensions()
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
		$this->fulltext = new \vse\similartopics\core\fulltext_support($this->db);
	}

	/**
	 * Very basic test we're running here
	 *
	 * Mostly just to check that our test case is running
	 */
	public function test_check()
	{
		$sql = 'SELECT *
			FROM phpbb_config';
		$result = $this->db->sql_query($sql);
		$this->assertEquals(array(
			array(
				'config_name'	=> 'foo',
				'config_value'	=> 'bar',
				'is_dynamic'	=> '0',
			),
		), $this->db->sql_fetchrowset($result));
		$this->db->sql_freeresult($result);
	}

	/**
	 * Test that the db is MySQL
	 */
	public function test_is_mysql()
	{
		$this->assertTrue($this->fulltext->is_mysql());
	}

	/**
	 * Test that the db supports fulltext index
	 */
	public function test_fulltext_support()
	{
		// Check for fulltext index support
		$this->assertTrue($this->fulltext->is_supported(), 'Fulltext support failed.');

		// Check that the engine name is stored correctly
		$this->assertEquals($this->fulltext->get_engine(), 'myisam');
	}

	/**
	 * Test that the field is fulltext
	 */
	public function test_fulltext_index()
	{
		$field = 'topic_title';

		// Check that the topic_title is NOT a fulltext index
		$this->assertFalse($this->fulltext->is_index($field));

		// Make topic_title a fulltext index
		$sql = "ALTER TABLE phpbb_topics ADD FULLTEXT ($field)";
		$this->db->sql_query($sql);

		// Check that the topic_title is a fulltext index
		$this->assertTrue($this->fulltext->is_index($field));
	}
}
