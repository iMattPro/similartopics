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

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\request\request */
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

	public function test_handle()
	{
		$this->request->expects($this->once())
			->method('variable');

		$this->controller->set_u_action('u_action')->handle();
	}

	public function test_set_u_action_returns_self()
	{
		$result = $this->controller->set_u_action('test_action');
		$this->assertInstanceOf('\vse\similartopics\acp\controller\similar_topics_admin', $result);
		$this->assertEquals('test_action', $this->controller->u_action);
	}

	public function test_handle_advanced_action()
	{
		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default) use (&$call_count) {
				$call_count++;
				if ($call_count === 1) {
					$this->assertEquals('action', $param);
					$this->assertEquals('', $default);
					return 'advanced';
				}
				if ($call_count === 2) {
					$this->assertEquals('f', $param);
					$this->assertEquals(0, $default);
					return 1;
				}
			});

		$this->request->expects($this->once())
			->method('is_set_post')
			->with('submit')
			->willReturn(false);

		$this->controller->set_u_action('u_action')->handle();
	}

	public static function default_settings_data_provider()
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
		$request_map = [['action', '', '']];
		foreach ($input_data as $key => $value)
		{
			$default = ($key === 'pst_words') ? '' : (($key === 'pst_time_type') ? '' : (($key === 'pst_sense') ? 5 : 0));
			$is_raw = ($key === 'pst_words');
			$request_map[] = [$key, $default, $is_raw, \phpbb\request\request_interface::REQUEST, $value];
		}
		$request_map[] = ['mark_noshow_forum', [0], true, \phpbb\request\request_interface::REQUEST, []];
		$request_map[] = ['mark_ignore_forum', [0], true, \phpbb\request\request_interface::REQUEST, []];

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

	public function test_advanced_settings_submit()
	{
		$this->request->method('variable')
			->willReturnMap([
				['action', '', false, \phpbb\request\request_interface::REQUEST, 'advanced'],
				['f', 0, false, \phpbb\request\request_interface::REQUEST, 1],
				['similar_forums_id', [0], false, \phpbb\request\request_interface::REQUEST, [2, 3]]
			]);

		$this->request->method('is_set_post')->with('submit')->willReturn(true);

		$executed_queries = [];
		$this->setupDbCapture($executed_queries);

		try
		{
			$this->controller->set_u_action('u_action')->handle();
		}
		catch (\phpbb\exception\http_exception $e)
		{
			// Expected exception
		}

		$this->assertCount(1, $executed_queries);
		$this->assertStringContainsString('UPDATE ' . FORUMS_TABLE, $executed_queries[0]);
		$this->assertStringContainsString("similar_topic_forums = '[2,3]'", $executed_queries[0]);
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
	return true;
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
