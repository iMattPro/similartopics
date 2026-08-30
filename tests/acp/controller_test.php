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

	/** @var \phpbb\template\template|\PHPUnit\Framework\MockObject\MockObject */
	protected $template;

	public function setUp(): void
	{
		parent::setUp();

		global $config, $phpbb_dispatcher, $template, $phpbb_root_path, $phpEx;

		$cache = new phpbb_mock_cache;
		$config = $this->config = new config([]);
		$this->config_text = $this->createMock(db_text::class);
		$this->db = $this->new_dbal();
		$extension_manager = $this->createMock('\phpbb\extension\manager');
		$metadata_manager = $this->createMock('\phpbb\extension\metadata_manager');
		$metadata_manager->method('get_metadata')->with('version')->willReturn('metadata-version');
		$extension_manager->method('create_extension_metadata_manager')->with('vse/similartopics')->willReturn($metadata_manager);
		$phpbb_dispatcher = new phpbb_mock_event_dispatcher();
		$log = $this->createMock(log::class);
		$this->request = $this->createMock(request::class);
		$template = $this->template = $this->createMock(template::class);
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

	protected function setControllerProperty($name, $value): void
	{
		$property = (new \ReflectionClass($this->controller))->getProperty($name);
		$property->setValue($this->controller, $value);
	}

	public function test_handle(): void
	{
		$this->controller->set_u_action('u_action')->handle();
		$this->addToAssertionCount(1);
	}

	public function test_set_u_action_returns_self(): void
	{
		$this->controller->set_u_action('test_action');
		$this->assertEquals('test_action', $this->controller->u_action);
	}

	public function test_get_extension_version_from_metadata(): void
	{
		$method = (new \ReflectionClass($this->controller))->getMethod('get_extension_version');
		$method->setAccessible(true);

		$this->assertSame('metadata-version', $method->invoke($this->controller));
	}

	public function test_get_extension_version_returns_empty_string_on_metadata_exception(): void
	{
		$extension_manager = $this->createMock('\phpbb\extension\manager');
		$extension_manager->method('create_extension_metadata_manager')
			->with('vse/similartopics')
			->willThrowException(new \phpbb\extension\exception('INVALID_EXTENSION_NAME'));
		$this->setControllerProperty('ext_manager', $extension_manager);

		$method = (new \ReflectionClass($this->controller))->getMethod('get_extension_version');
		$method->setAccessible(true);

		$this->assertSame('', $method->invoke($this->controller));
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
		$request_map = [];
		foreach ($input_data as $key => $value)
		{
			$default = ($key === 'pst_words') ? '' : (($key === 'pst_time_type') ? '' : (($key === 'pst_sense') ? 5 : 0));
			$is_raw = ($key === 'pst_words');
			$request_map[] = [$key, $default, $is_raw, request_interface::REQUEST, $value];
		}
		$request_map[] = ['forum_rules', '', false, \phpbb\request\request_interface::POST, '{&quot;2&quot;:{&quot;show&quot;:0,&quot;searchable&quot;:0,&quot;mode&quot;:&quot;all&quot;,&quot;sources&quot;:[]}}'];
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

	public function test_update_forum_sources_saves_sanitized_custom_selection(): void
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

	public function test_parse_forum_rules_returns_complete_normalized_rules()
	{
		$method = (new \ReflectionClass($this->controller))->getMethod('parse_forum_rules');
		$method->setAccessible(true);
		$payload = '{"1":{"show":1,"searchable":0,"mode":"custom","sources":[2,3]},"2":{"show":0,"searchable":1,"mode":"all","sources":[]},"3":{"show":1,"searchable":1,"mode":"all","sources":[]}}';

		$this->assertSame([
			'shown' => [1, 3],
			'searchable' => [2, 3],
			'modes' => [1 => 'custom', 2 => 'all', 3 => 'all'],
			'sources' => [1 => '2,3', 2 => '', 3 => ''],
		], $method->invoke($this->controller, $payload, [1, 2, 3]));
	}

	public function test_parse_forum_rules_accepts_empty_forum_list()
	{
		$method = (new \ReflectionClass($this->controller))->getMethod('parse_forum_rules');
		$method->setAccessible(true);

		$this->assertSame([
			'shown' => [],
			'searchable' => [],
			'modes' => [],
			'sources' => [],
		], $method->invoke($this->controller, '{}', []));
	}

	public function invalid_forum_rules_data_provider()
	{
		$valid = ['show' => 1, 'searchable' => 1, 'mode' => 'all', 'sources' => []];

		return [
			'malformed JSON' => ['{', [1]],
			'missing forum' => [json_encode([1 => $valid]), [1, 2]],
			'unknown forum' => [json_encode([2 => $valid]), [1]],
			'noncanonical forum ID' => ['{"01":{"show":1,"searchable":1,"mode":"all","sources":[]}}', [1]],
			'missing property' => [json_encode([1 => ['show' => 1, 'searchable' => 1, 'mode' => 'all']]), [1]],
			'extra property' => [json_encode([1 => $valid + ['extra' => 1]]), [1]],
			'invalid show flag' => [json_encode([1 => array_merge($valid, ['show' => true])]), [1]],
			'invalid searchable flag' => [json_encode([1 => array_merge($valid, ['searchable' => '1'])]), [1]],
			'invalid mode' => [json_encode([1 => array_merge($valid, ['mode' => 'invalid'])]), [1]],
			'non-array sources' => [json_encode([1 => array_merge($valid, ['sources' => '1'])]), [1]],
			'non-integer source' => [json_encode([1 => array_merge($valid, ['mode' => 'custom', 'sources' => ['1']])]), [1]],
			'unknown source' => [json_encode([1 => array_merge($valid, ['mode' => 'custom', 'sources' => [2]])]), [1]],
			'duplicate source' => [json_encode([1 => array_merge($valid, ['mode' => 'custom', 'sources' => [1, 1]])]), [1]],
			'custom mode without sources' => [json_encode([1 => array_merge($valid, ['mode' => 'custom'])]), [1]],
			'all mode with sources' => [json_encode([1 => array_merge($valid, ['sources' => [1]])]), [1]],
		];
	}

	/**
	 * @dataProvider invalid_forum_rules_data_provider
	 */
	public function test_parse_forum_rules_rejects_invalid_payload($payload, $forum_ids)
	{
		$method = (new \ReflectionClass($this->controller))->getMethod('parse_forum_rules');
		$method->setAccessible(true);

		$this->expectException('\phpbb\exception\http_exception');
		$method->invoke($this->controller, $payload, $forum_ids);
	}

	public function test_display_builds_initial_forum_rules_payload()
	{
		$assigned_vars = [];
		$this->template->method('assign_vars')->willReturnCallback(function($vars) use (&$assigned_vars) {
			$assigned_vars = array_merge($assigned_vars, $vars);
		});
		$this->request->method('is_set_post')->willReturn(false);

		$this->controller->handle();

		$this->assertArrayHasKey('PST_FORUM_RULES', $assigned_vars);
		$this->assertJsonStringEqualsJsonString(
			'{"2":{"show":0,"searchable":0,"mode":"all","sources":[]}}',
			$assigned_vars['PST_FORUM_RULES']
		);
	}

	public function test_incomplete_forum_rules_are_rejected_before_settings_saved()
	{
		$this->request->method('is_set_post')->with('submit')->willReturn(true);
		$this->request->method('variable')->willReturnMap([
			['forum_rules', '', false, \phpbb\request\request_interface::POST, '{}'],
		]);

		try
		{
			$this->controller->handle();
			$this->fail('Incomplete forum rules should be rejected.');
		}
		catch (http_exception)
		{
			$this->assertFalse(isset($this->config['similar_topics']));
		}
	}

	public function test_default_settings_displays_postgres_and_forum_options(): void
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

	public function test_postgres_setting_rebuilds_index(): void
	{
		$driver = $this->createMock('\vse\similartopics\driver\driver_interface');
		$driver->method('get_type')->willReturn('postgres');
		$driver->expects($this->once())->method('create_fulltext_index')->with('topic_title');
		$this->setControllerProperty('similartopics', $driver);
		$this->config['pst_postgres_ts_name'] = 'simple';
		$this->request->method('variable')->willReturnMap([
			['pst_postgres_ts_name', 'simple', false, \phpbb\request\request_interface::REQUEST, 'english'],
			['forum_rules', '', false, \phpbb\request\request_interface::POST, '{&quot;2&quot;:{&quot;show&quot;:0,&quot;searchable&quot;:0,&quot;mode&quot;:&quot;all&quot;,&quot;sources&quot;:[]}}'],
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

	public function test_invalid_form_ends_request(): void
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
function check_form_key(): bool
{
	global $similar_topics_form_valid;
	return $similar_topics_form_valid !== false;
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
