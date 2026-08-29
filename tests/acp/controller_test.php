<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\acp\controller;

class controller_test extends \phpbb_database_test_case
{
	protected static function setup_extensions()
	{
		return array('vse/similartopics');
	}

	public function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . '/fixtures/pst_data.xml');
	}

	/** @var \vse\similartopics\acp\controller\similar_topics_admin */
	protected $controller;

	/** @var \PHPUnit_Framework_MockObject_MockObject|\phpbb\request\request */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text|\PHPUnit\Framework\MockObject\MockObject */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	public function setUp(): void
	{
		parent::setUp();

		global $config, $phpbb_dispatcher, $template, $phpbb_root_path, $phpEx;

		$cache = new \phpbb_mock_cache;
		$config = $this->config = new \phpbb\config\config([]);
		$this->config_text = $this->createMock('\phpbb\config\db_text');
		$this->db = $this->new_dbal();
		$extension_manager = $this->createMock('\phpbb\extension\manager');
		$metadata_manager = $this->createMock('\phpbb\extension\metadata_manager');
		$metadata_manager->method('get_metadata')->with('version')->willReturn('metadata-version');
		$extension_manager->method('create_extension_metadata_manager')->with('vse/similartopics')->willReturn($metadata_manager);
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		$log = $this->createMock('\phpbb\log\log');
		$this->request = $this->createMock('\phpbb\request\request');
		$template = $this->createMock('\phpbb\template\template');
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$language = new \phpbb\language\language($lang_loader);
		$user = new \phpbb\user($language, '\phpbb\datetime');
		$user->data['user_id'] = 2;
		$user->ip = '';

		$pst_manager = $this->createMock('\vse\similartopics\driver\manager');

		$this->controller = new \vse\similartopics\acp\controller\similar_topics_admin(
			$cache,
			$this->config,
			$this->config_text,
			$this->db,
			$extension_manager,
			$pst_manager,
			$language,
			$log,
			$this->request,
			$template,
			$user,
			$phpbb_root_path,
			$phpEx
		);
	}

	protected function setupDbCapture(&$executed_queries)
	{
		$mock_db = $this->createMock('\phpbb\db\driver\driver_interface');
		$mock_db->method('sql_query')
			->willReturnCallback(function($sql) use (&$executed_queries) {
				$executed_queries[] = $sql;
				return true;
			});
		$mock_db->method('sql_escape')->willReturnArgument(0);
		
		// Use reflection to replace the db dependency in the existing controller
		$reflection = new \ReflectionClass($this->controller);
		$db_property = $reflection->getProperty('db');
		$db_property->setAccessible(true);
		$db_property->setValue($this->controller, $mock_db);
	}

	protected function setControllerProperty($name, $value)
	{
		$property = (new \ReflectionClass($this->controller))->getProperty($name);
		$property->setAccessible(true);
		$property->setValue($this->controller, $value);
	}

	public function test_handle()
	{
		$this->controller->set_u_action('u_action')->handle();
		$this->addToAssertionCount(1);
	}

	public function test_set_u_action_returns_self()
	{
		$result = $this->controller->set_u_action('test_action');
		$this->assertInstanceOf('\vse\similartopics\acp\controller\similar_topics_admin', $result);
		$this->assertEquals('test_action', $this->controller->u_action);
	}

	public function test_get_extension_version_from_metadata()
	{
		$method = (new \ReflectionClass($this->controller))->getMethod('get_extension_version');
		$method->setAccessible(true);

		$this->assertSame('metadata-version', $method->invoke($this->controller));
	}

	public function default_settings_data_provider()
	{
		return [
			'valid settings' => [
				[
					'pst_enable' => 1,
					'pst_dynamic' => 1,
					'pst_limit' => 5,
					'pst_cache' => 3600,
					'pst_words' => 'test ignore words',
					'pst_sense' => 7,
					'pst_time' => 30,
					'pst_time_type' => 'd'
				],
				[
					'similar_topics' => 1,
					'similar_topics_dynamic' => 1,
					'similar_topics_limit' => 5,
					'similar_topics_cache' => 3600,
					'similar_topics_sense' => 7,
					'similar_topics_time' => 2592000
				]
			],
			'negative values' => [
				[
					'pst_enable' => 1,
					'pst_dynamic' => 1,
					'pst_limit' => -5,
					'pst_cache' => -100,
					'pst_words' => 'test',
					'pst_sense' => -3,
					'pst_time' => -10,
					'pst_time_type' => 'w'
				],
				[
					'similar_topics' => 1,
					'similar_topics_dynamic' => 1,
					'similar_topics_limit' => 5,
					'similar_topics_cache' => 100,
					'similar_topics_sense' => 3,
					'similar_topics_time' => 6048000
				]
			]
		];
	}

	/**
	 * @dataProvider default_settings_data_provider
	 */
	public function test_default_settings_submit_and_verify($input_data, $expected_config)
	{
		$request_map = [];
		foreach ($input_data as $key => $value)
		{
			$default = ($key === 'pst_words') ? '' : (($key === 'pst_time_type') ? '' : (($key === 'pst_sense') ? 5 : 0));
			$is_raw = ($key === 'pst_words');
			$request_map[] = [$key, $default, $is_raw, \phpbb\request\request_interface::REQUEST, $value];
		}
		$request_map[] = ['show_forum', [0], false, \phpbb\request\request_interface::REQUEST, []];
		$request_map[] = ['searchable_forum', [0], false, \phpbb\request\request_interface::REQUEST, []];
		$request_map[] = ['forum_source_mode', [0 => ''], false, \phpbb\request\request_interface::REQUEST, []];
		$request_map[] = ['similar_forums', [0 => ''], false, \phpbb\request\request_interface::REQUEST, []];

		$this->request->method('variable')->willReturnMap($request_map);
		$this->request->method('is_set_post')->with('submit')->willReturn(true);

		try
		{
			$this->controller->handle();
		}
		catch (\phpbb\exception\http_exception $e)
		{
			// Expected exception
		}

		foreach ($expected_config as $key => $expected_value)
		{
			$this->assertEquals($expected_value, $this->config[$key]);
		}
	}

	public function test_update_forum_sources_saves_sanitized_custom_selection()
	{
		$executed_queries = [];
		$this->setupDbCapture($executed_queries);

		$method = (new \ReflectionClass($this->controller))->getMethod('update_forum_sources');
		$method->setAccessible(true);
		$method->invoke($this->controller, [1, 2, 3], [1 => 'custom', 2 => 'all', 3 => 'custom'], [1 => '2,3,99,2', 2 => '1', 3 => '']);

		$this->assertCount(3, $executed_queries);
		$this->assertStringContainsString('UPDATE ' . FORUMS_TABLE, $executed_queries[0]);
		$this->assertStringContainsString("similar_topic_forums = '[2,3]'", $executed_queries[0]);
		$this->assertStringContainsString("similar_topic_forums = ''", $executed_queries[1]);
		$this->assertStringContainsString("similar_topic_forums = ''", $executed_queries[2]);
	}

	public function test_default_settings_displays_postgres_and_forum_options()
	{
		$db = $this->createMock('\phpbb\db\driver\driver_interface');
		$db->method('get_sql_layer')->willReturn('postgres');
		$db->method('sql_query')->willReturn(true);
		$db->method('sql_fetchrowset')->willReturnOnConsecutiveCalls(
			[['forum_id' => 2, 'forum_name' => 'Forum', 'similar_topic_forums' => '[3]', 'similar_topics_hide' => 1, 'similar_topics_ignore' => 1]],
			[['ts_name' => 'simple'], ['ts_name' => 'english']]
		);
		$db->method('sql_freeresult');
		$this->setControllerProperty('db', $db);
		$this->setControllerProperty('similartopics', new \vse\similartopics\driver\postgres($db, new \phpbb\config\config(['pst_postgres_ts_name' => 'english'])));
		$this->request->method('is_set_post')->willReturn(false);

		$this->controller->set_u_action('u_action')->handle();
		$this->addToAssertionCount(1);
	}

	public function test_postgres_setting_rebuilds_index()
	{
		$driver = $this->createMock('\vse\similartopics\driver\driver_interface');
		$driver->method('get_type')->willReturn('postgres');
		$driver->expects($this->once())->method('create_fulltext_index')->with('topic_title');
		$this->setControllerProperty('similartopics', $driver);
		$this->config['pst_postgres_ts_name'] = 'simple';
		$this->request->method('variable')->willReturnMap([
			['pst_postgres_ts_name', 'simple', false, \phpbb\request\request_interface::REQUEST, 'english'],
			['show_forum', [0], false, \phpbb\request\request_interface::REQUEST, []],
			['searchable_forum', [0], false, \phpbb\request\request_interface::REQUEST, []],
			['forum_source_mode', [0 => ''], false, \phpbb\request\request_interface::REQUEST, []],
			['similar_forums', [0 => ''], false, \phpbb\request\request_interface::REQUEST, []],
		]);
		$this->request->method('is_set_post')->willReturn(true);

		try
		{
			$this->controller->set_u_action('u_action')->handle();
		}
		catch (\phpbb\exception\http_exception $e)
		{
		}
		$this->assertSame('english', $this->config['pst_postgres_ts_name']);
	}

	public function test_invalid_form_ends_request()
	{
		global $similar_topics_form_valid;
		$similar_topics_form_valid = false;
		$this->request->method('variable')->willReturn('');
		$this->request->method('is_set_post')->willReturn(true);

		$this->expectException('\phpbb\exception\http_exception');
		try
		{
			$this->controller->set_u_action('u_action')->handle();
		}
		finally
		{
			$similar_topics_form_valid = true;
		}
	}
}

/**
 * Mock add_form_key()
 */
function add_form_key()
{
}

/**
 * Mock check_form_key()
 */
function check_form_key()
{
	global $similar_topics_form_valid;
	return $similar_topics_form_valid !== false;
}

/**
 * Mock trigger_error()
 */
function trigger_error($message, $type = E_USER_ERROR)
{
	throw new \phpbb\exception\http_exception(200, $message);
}

/**
 * Mock adm_back_link()
 */
function adm_back_link($u_action)
{
	return '';
}

/**
 * Mock make_forum_select()
 */
function make_forum_select()
{
	return '';
}
