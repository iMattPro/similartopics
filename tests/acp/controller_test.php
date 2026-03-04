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

use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\db\driver\driver_interface;
use phpbb\exception\http_exception;
use phpbb\language\language;
use phpbb\language\language_file_loader;
use phpbb\request\request;
use phpbb\request\request_interface;
use phpbb\user;
use phpbb_database_test_case;
use phpbb_mock_cache;
use phpbb_mock_event_dispatcher;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use PHPUnit\DbUnit\DataSet\XmlDataSet;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use vse\similartopics\driver\manager;
use phpbb\datetime;
use phpbb\template\template;
use phpbb\log\log;

class controller_test extends phpbb_database_test_case
{
	protected static function setup_extensions(): array
	{
		return array('vse/similartopics');
	}

	public function getDataSet(): DefaultDataSet|XmlDataSet
	{
		return $this->createXMLDataSet(__DIR__ . '/fixtures/pst_data.xml');
	}

	/** @var similar_topics_admin */
	protected similar_topics_admin $controller;

	/** @var MockObject|request */
	protected MockObject|request $request;

	/** @var config */
	protected config $config;

	/** @var db_text|MockObject */
	protected db_text|MockObject $config_text;

	/** @var driver_interface */
	protected driver_interface $db;

	public function setUp(): void
	{
		parent::setUp();

		global $config, $phpbb_dispatcher, $template, $phpbb_root_path, $phpEx;

		$cache = new phpbb_mock_cache;
		$config = $this->config = new config([]);
		$this->config_text = $this->createMock(db_text::class);
		$this->db = $this->new_dbal();
		$phpbb_dispatcher = new phpbb_mock_event_dispatcher();
		$log = $this->createMock(log::class);
		$this->request = $this->createMock(request::class);
		$template = $this->createMock(template::class);
		$lang_loader = new language_file_loader($phpbb_root_path, $phpEx);
		$language = new language($lang_loader);
		$user = new user($language, datetime::class);
		$user->data['user_id'] = 2;
		$user->ip = '';

		$pst_manager = $this->createMock(manager::class);

		$this->controller = new similar_topics_admin(
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

	protected function setupDbCapture(&$executed_queries): void
	{
		$mock_db = $this->createMock(driver_interface::class);
		$mock_db->method('sql_query')
			->willReturnCallback(function($sql) use (&$executed_queries) {
				$executed_queries[] = $sql;
				return true;
			});
		$mock_db->method('sql_escape')->willReturnArgument(0);

		// Use reflection to replace the db dependency in the existing controller
		$reflection = new ReflectionClass($this->controller);
		$db_property = $reflection->getProperty('db');
		$db_property->setValue($this->controller, $mock_db);
	}

	public function test_handle(): void
	{
		$this->request->expects($this->once())
			->method('variable');

		$this->controller->set_u_action('u_action')->handle();
	}

	public function test_set_u_action_returns_self(): void
	{
		$this->controller->set_u_action('test_action');
		$this->assertEquals('test_action', $this->controller->u_action);
	}

	public function test_handle_advanced_action(): void
	{
		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default) use (&$call_count) {
				$call_count++;
				if ($call_count === 1)
				{
					$this->assertEquals('action', $param);
					$this->assertEquals('', $default);
					return 'advanced';
				}
				if ($call_count === 2)
				{
					$this->assertEquals('f', $param);
					$this->assertEquals(0, $default);
					return 1;
				}
				return null;
			});

		$this->request->expects($this->once())
			->method('is_set_post')
			->with('submit')
			->willReturn(false);

		$this->controller->set_u_action('u_action')->handle();
	}

	public static function default_settings_data_provider(): array
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
	public function test_default_settings_submit_and_verify($input_data, $expected_config): void
	{
		$request_map = [['action', '', '']];
		foreach ($input_data as $key => $value)
		{
			$default = ($key === 'pst_words') ? '' : (($key === 'pst_time_type') ? '' : (($key === 'pst_sense') ? 5 : 0));
			$is_raw = ($key === 'pst_words');
			$request_map[] = [$key, $default, $is_raw, request_interface::REQUEST, $value];
		}
		$request_map[] = ['mark_noshow_forum', [0], true, request_interface::REQUEST, []];
		$request_map[] = ['mark_ignore_forum', [0], true, request_interface::REQUEST, []];

		$this->request->method('variable')->willReturnMap($request_map);
		$this->request->method('is_set_post')->with('submit')->willReturn(true);

		try
		{
			$this->controller->set_u_action('u_action')->handle();
		}
		catch (http_exception)
		{
			// Expected exception
		}

		foreach ($expected_config as $key => $expected_value)
		{
			$this->assertEquals($expected_value, $this->config[$key]);
		}
	}

	public function test_advanced_settings_submit(): void
	{
		$this->request->method('variable')
			->willReturnMap([
				['action', '', false, request_interface::REQUEST, 'advanced'],
				['f', 0, false, request_interface::REQUEST, 1],
				['similar_forums_id', [0], false, request_interface::REQUEST, [2, 3]]
			]);

		$this->request->method('is_set_post')->with('submit')->willReturn(true);

		$executed_queries = [];
		$this->setupDbCapture($executed_queries);

		try
		{
			$this->controller->set_u_action('u_action')->handle();
		}
		catch (http_exception)
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
function check_form_key(): true
{
	return true;
}

/**
 * Mock trigger_error()
 */
function trigger_error($message, $type = E_USER_ERROR)
{
	throw new http_exception(200, $message);
}

/**
 * Mock adm_back_link()
 */
function adm_back_link($u_action): string
{
	return '';
}

/**
 * Mock make_forum_select()
 */
function make_forum_select(): string
{
	return '';
}
