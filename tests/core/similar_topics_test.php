<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2015 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\core;

require_once __DIR__ . '/../../../../../includes/functions_display.php';

use phpbb\auth\auth;
use phpbb\cache\service;
use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\content_visibility;
use phpbb\event\dispatcher;
use phpbb\language\language;
use phpbb\language\language_file_loader;
use phpbb\pagination;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb_mock_cache;
use phpbb_mock_event_dispatcher;
use phpbb_mock_extension_manager;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use vse\similartopics\core\similar_topics;
use vse\similartopics\core\stop_word_helper;
use vse\similartopics\driver\driver_interface;
use vse\similartopics\driver\manager;
use phpbb\datetime;
use phpbb\event\dispatcher_interface;

class similar_topics_test extends phpbb_test_case
{
	/** @var MockObject|auth */
	protected MockObject|auth $auth;

	/** @var MockObject|service */
	protected MockObject|service $service;

	/** @var config */
	protected config $config;

	/** @var MockObject|db_text */
	protected MockObject|db_text $config_text;

	/** @var MockObject|\phpbb\db\driver\driver_interface */
	protected MockObject|\phpbb\db\driver\driver_interface $db;

	/** @var MockObject|dispatcher */
	protected MockObject|dispatcher $dispatcher;

	/** @var language */
	protected language $language;

	/** @var MockObject|pagination */
	protected MockObject|pagination $pagination;

	/** @var MockObject|request */
	protected MockObject|request $request;

	/** @var template|MockObject */
	protected MockObject|template $template;

	/** @var user */
	protected user $user;

	/** @var MockObject|content_visibility */
	protected MockObject|content_visibility $content_visibility;

	/** @var MockObject|stop_word_helper */
	protected MockObject|stop_word_helper $stop_word_helper;

	/** @var MockObject|manager */
	protected MockObject|manager $manager;

	/** @var MockObject|driver_interface */
	protected MockObject|driver_interface $driver;

	/** @var phpbb_mock_extension_manager  */
	protected phpbb_mock_extension_manager $extension_manager;

	/** @var string */
	protected string $phpbb_root_path;

	/** @var string */
	protected string $phpEx;

	protected function setUp(): void
	{
		parent::setUp();

		global $phpbb_dispatcher, $cache, $phpbb_root_path, $phpEx;

		// Classes we just need to mock for the constructor
		$phpbb_dispatcher = new phpbb_mock_event_dispatcher();
		$cache = new phpbb_mock_cache();
		$this->service = $this->createMock(service::class);
		$this->service->method('get_driver')->willReturn($cache);
		$this->config_text = $this->createMock(db_text::class);
		$this->db = $this->createMock(\phpbb\db\driver\driver_interface::class);
		$this->dispatcher = $this->createMock(dispatcher_interface::class);
		$this->pagination = $this->createMock(pagination::class);
		$this->request = $this->createMock(request::class);
		$this->template = $this->createMock(template::class);
		$this->content_visibility = $this->createMock(content_visibility::class);
		$this->stop_word_helper = $this->createMock(stop_word_helper::class);
		$this->manager = $this->createMock(manager::class);
		$this->driver = $this->createMock(driver_interface::class);

		// Classes used in the tests
		$this->extension_manager = new phpbb_mock_extension_manager(
			$phpbb_root_path,
			array(
				'vse/similartopics' => array(
					'ext_name' => 'vse/similartopics',
					'ext_active' => '1',
					'ext_path' => 'ext/vse/similartopics',
				),
			));
		$this->auth = $this->createMock(auth::class);
		$this->config = new config([]);
		$lang_loader = new language_file_loader($phpbb_root_path, $phpEx);
		$lang_loader->set_extension_manager($this->extension_manager);
		$this->language = new language($lang_loader);
		$this->user = new user($this->language, datetime::class);
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpEx = $phpEx;
	}

	public function get_similar_topics(): similar_topics
	{
		return new similar_topics(
			$this->auth,
			$this->service,
			$this->config,
			$this->config_text,
			$this->db,
			$this->dispatcher,
			$this->language,
			$this->pagination,
			$this->request,
			$this->template,
			$this->user,
			$this->content_visibility,
			$this->stop_word_helper,
			$this->manager,
			$this->phpbb_root_path,
			$this->phpEx
		);
	}

	public static function is_available_test_data(): array
	{
		return [
			'enabled on mysqli' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				true,
			],
			'enabled on mysql4' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysql4',
				true,
			],
			'enabled on postgres' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'postgres',
				true,
			],
			'enabled on sqlite' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'sqlite',
				false,
			],
			'enabled on sqlite3' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'sqlite3',
				true,
			],
			'enabled on mssql' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mssql',
				true,
			],
			'enabled on mssqlnative' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mssqlnative',
				true,
			],
			'enabled on mssql_odbc' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mssql_odbc',
				true,
			],
			'enabled on oracle' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'oracle',
				true,
			],
			'enabled on invalid db' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'innodb',
				false,
			],
			'enabled on no db' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'',
				false,
			],
			'admin do not show' => [
				'is_available',
				[
					'similar_topics' => '0',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'admin show 0 results' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '0',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'admin fully disabled' => [
				'is_available',
				[
					'similar_topics' => '0',
					'similar_topics_limit' => '0',
				],
				['user_similar_topics' => false],
				['u_similar_topics', 0, false],
				'mysqli',
				false,
			],
			'user disabled' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => false],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'user not authed' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, false],
				'mysqli',
				false,
			],
			'user disabled and not authed' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => null],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'user settings error' => [
				'is_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => ''],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'empty configs' => [
				'is_available',
				[
					'similar_topics' => '',
					'similar_topics_limit' => '',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'null configs' => [
				'is_available',
				[
					'similar_topics' => null,
					'similar_topics_limit' => null,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'dynamic enabled' => [
				'is_dynamic_available',
				[
					'similar_topics' => '0',
					'similar_topics_limit' => '5',
					'similar_topics_dynamic' => '1',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				true,
			],
			'dynamic admin do not show' => [
				'is_dynamic_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
					'similar_topics_dynamic' => '0',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'dynamic admin show 0 results' => [
				'is_dynamic_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '0',
					'similar_topics_dynamic' => '1',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'dynamic admin fully disabled' => [
				'is_dynamic_available',
				[
					'similar_topics' => '0',
					'similar_topics_limit' => '0',
					'similar_topics_dynamic' => '0',
				],
				['user_similar_topics' => false],
				['u_similar_topics', 0, false],
				'mysqli',
				false,
			],
			'dynamic user disabled' => [
				'is_dynamic_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
					'similar_topics_dynamic' => '1',
				],
				['user_similar_topics' => false],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'dynamic user not authed' => [
				'is_dynamic_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
					'similar_topics_dynamic' => '1',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, false],
				'mysqli',
				false,
			],
			'dynamic user disabled and not authed' => [
				'is_dynamic_available',
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
					'similar_topics_dynamic' => '1',
				],
				['user_similar_topics' => null],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
		];
	}

	/**
	 * @dataProvider is_available_test_data
	 */
	public function test_is_available($method, $config_data, $user_data, $auth_data, $sql_layer, $expected): void
	{
		$this->config = new config($config_data);
		$this->user->data['user_similar_topics'] = $user_data['user_similar_topics'];
		$this->auth->method('acl_get')
			->with(self::stringContains('_'), self::anything())
			->willReturnMap([$auth_data]);
		$this->db->expects(self::atMost(2))
			->method('get_sql_layer')
			->willReturn($sql_layer);
		$this->manager->expects(self::once())
			->method('get_driver')
			->with($sql_layer)
			->willReturn((in_array($sql_layer, ['mysqli', 'mysql4', 'postgres', 'sqlite3', 'mssql', 'mssqlnative', 'mssql_odbc', 'oracle']) ? $this->driver : null));
		$this->driver->method('has_stopword_support')
			->willReturn(in_array($sql_layer, ['mysqli', 'mysql4', 'oracle']));

		$similar_topics = $this->get_similar_topics();

		self::assertEquals($expected, $similar_topics->$method());
	}

	public function test_display_similar_topics_hidden_forum(): void
	{
		$topic_data = ['similar_topics_hide' => true];
		$this->db->method('get_sql_layer')->willReturn('');
		$similar_topics = $this->get_similar_topics();

		// Should return early without doing anything
		$similar_topics->display_similar_topics($topic_data);
		$this->addToAssertionCount(1);
	}

	public function test_display_similar_topics_empty_title(): void
	{
		$topic_data = ['similar_topics_hide' => false, 'topic_title' => ''];
		$this->db->method('get_sql_layer')->willReturn('');
		$this->stop_word_helper->method('clean_text')->willReturn('');
		$similar_topics = $this->get_similar_topics();

		$similar_topics->display_similar_topics($topic_data);
		$this->addToAssertionCount(1);
	}

	public function test_display_similar_topics_renders_readable_results(): void
	{
		global $auth, $cache, $config, $request, $user;

		$this->config = $config = new \phpbb\config\config([
			'similar_topics_sense' => 5,
			'similar_topics_time' => 86400,
			'similar_topics_limit' => 5,
			'similar_topics_cache' => 0,
			'load_db_lastread' => true,
			'load_anon_lastread' => false,
			'cookie_name' => 'phpbb',
			'posts_per_page' => 10,
			'board_startdate' => 0,
			'hot_threshold' => 0,
		]);
		$this->user = $user = $this->createPartialMock('\phpbb\user', ['get_passworded_forums', 'format_date', 'img', 'optionget']);
		$this->user->data = [
			'is_registered' => true,
			'user_id' => 2,
			'user_lastmark' => 0,
		];
		$this->user->session_id = 'session';
		$this->user->method('get_passworded_forums')->willReturn([9]);
		$this->user->method('format_date')->willReturn('date');
		$this->user->method('img')->willReturnArgument(0);
		$request = $this->request;

		$auth = $this->auth;
		$this->auth->method('acl_get')->willReturn(true);
		$cache = new \phpbb_mock_cache();
		$this->service->method('obtain_icons')->willReturn([
			1 => ['img' => 'icon.png', 'width' => 16, 'height' => 16],
		]);
		$this->content_visibility->method('get_count')->willReturn(2);
		$this->stop_word_helper->method('clean_text')->with('Current topic')->willReturn('current topic');
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->manager->method('get_driver')->willReturn($this->driver);
		$this->driver->method('has_stopword_support')->willReturn(true);
		$this->driver->expects(self::exactly(2))->method('get_query')->willReturn([
			'SELECT' => 't.*',
			'FROM' => [],
			'WHERE' => '1=1',
		]);
		$this->dispatcher->method('trigger_event')->willReturnCallback(function ($event_name, $data) {
			return $data;
		});
		$this->db->method('sql_in_set')->willReturn('f.forum_id NOT IN (9)');
		$this->db->method('sql_build_query')->willReturn('SELECT similar');
		$this->db->expects(self::exactly(2))->method('sql_query_limit')->with('SELECT similar', 5, 0, 0)->willReturn('result');
		$this->db->expects(self::exactly(6))->method('sql_fetchrow')->with('result')->willReturnOnConsecutiveCalls(
			$this->topic_row(['topic_visibility' => ITEM_UNAPPROVED, 'topic_id' => 3]),
			$this->topic_row(['topic_posts_unapproved' => 1, 'topic_id' => 4]),
			false,
			$this->topic_row(['topic_visibility' => ITEM_UNAPPROVED, 'topic_id' => 3]),
			$this->topic_row(['topic_posts_unapproved' => 1, 'topic_id' => 4]),
			false
		);
		$this->db->expects(self::exactly(2))->method('sql_freeresult')->with('result');
		$this->template->expects(self::exactly(4))->method('assign_block_vars')->with('similar', self::callback(function ($row) {
			return $row['TOPIC_TITLE'] === 'Similar topic' && $row['TOPIC_ICON_IMG'] === 'icon.png';
		}));
		$this->template->expects(self::exactly(2))->method('assign_vars')->with(self::isType('array'));
		$this->pagination->expects(self::exactly(4))->method('generate_template_pagination');

		$this->get_similar_topics()->display_similar_topics([
			'similar_topics_hide' => false,
			'similar_topic_forums' => '[2,9]',
			'topic_id' => 1,
			'topic_title' => 'Current topic',
		]);

		$this->user->data['is_registered'] = false;
		$this->config['load_db_lastread'] = false;
		$this->config['load_anon_lastread'] = true;
		$this->get_similar_topics()->display_similar_topics([
			'similar_topics_hide' => false,
			'similar_topic_forums' => '',
			'topic_id' => 1,
			'topic_title' => 'Current topic',
		]);
	}

	public function test_display_similar_topics_stops_when_included_forums_are_passworded(): void
	{
		global $config, $user;

		$this->config = $config = new \phpbb\config\config([
			'similar_topics_time' => 86400,
			'similar_topics_cache' => 0,
			'load_db_lastread' => false,
			'load_anon_lastread' => true,
			'cookie_name' => 'phpbb',
		]);
		$this->user = $user = $this->createPartialMock('\phpbb\user', ['get_passworded_forums']);
		$this->user->data = ['is_registered' => false, 'user_id' => ANONYMOUS];
		$this->user->method('get_passworded_forums')->willReturn([2]);
		$this->request->expects(self::once())->method('variable')->willReturn('');
		$this->stop_word_helper->method('clean_text')->willReturn('current topic');
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->manager->method('get_driver')->willReturn($this->driver);
		$this->driver->method('get_query')->willReturn(['SELECT' => 't.*', 'FROM' => [], 'WHERE' => '1=1']);

		$this->get_similar_topics()->display_similar_topics([
			'similar_topics_hide' => false,
			'similar_topic_forums' => '[2]',
			'topic_id' => 1,
			'topic_title' => 'Current topic',
		]);
		$this->addToAssertionCount(1);
	}

	public function test_search_similar_topics_ajax_empty_query(): void
	{
		$this->db->method('get_sql_layer')->willReturn('');
		$this->stop_word_helper->method('clean_text')->willReturn('');
		$similar_topics = $this->get_similar_topics();

		$result = $similar_topics->search_similar_topics_ajax('', 1);
		self::assertEquals([], $result);
	}

	public function test_search_similar_topics_ajax_with_results(): void
	{
		global $config, $user, $auth, $cache;
		$this->config = $config = new config(['similar_topics_time' => 86400]);
		$this->stop_word_helper->method('clean_text')->willReturn('test query');
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->manager->method('get_driver')->willReturn($this->driver);
		$this->driver->method('get_query')->willReturn(['SELECT' => 't.topic_id, t.topic_title', 'FROM' => [], 'WHERE' => '1=1']);
		$this->user = $user = $this->createPartialMock(user::class, ['get_passworded_forums', 'optionget']);
		$this->user->method('get_passworded_forums')->willReturn([]);
		$this->auth->method('acl_get')->willReturn(true);
		$auth = $this->auth;
		$cache = new phpbb_mock_cache();

		$this->db->expects(self::once())
			->method('sql_query')
			->willReturn(true);
		$this->db->expects(self::once())
			->method('sql_query_limit')
			->willReturn(true);
		$this->db->expects(self::exactly(2))
			->method('sql_fetchrow')
			->willReturnOnConsecutiveCalls(
				['topic_id' => 1, 'topic_title' => 'Test Topic', 'forum_id' => 1],
				false
			);
		$this->db->expects(self::exactly(2))
			->method('sql_freeresult');
		$this->db->method('sql_fetchfield')->willReturn('[1]');

		$similar_topics = $this->get_similar_topics();
		$result = $similar_topics->search_similar_topics_ajax('test query', 1);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertEquals(1, $result[0]['id']);
		self::assertEquals('Test Topic', $result[0]['title']);
	}

	public function test_search_similar_topics_ajax_excludes_passworded_forums(): void
	{
		global $config, $user;
		$this->config = $config = new \phpbb\config\config(['similar_topics_time' => 86400]);
		$this->stop_word_helper->method('clean_text')->willReturn('query');
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->manager->method('get_driver')->willReturn($this->driver);
		$this->driver->method('get_query')->willReturn(['SELECT' => 't.*', 'FROM' => [], 'WHERE' => '1=1']);
		$this->db->method('sql_in_set')->willReturn('f.forum_id NOT IN (9)');
		$this->db->method('sql_build_query')->willReturn('SELECT similar');
		$this->db->method('sql_query_limit')->willReturn('result');
		$this->db->method('sql_fetchrow')->willReturn(false);
		$this->user = $user = $this->createPartialMock('\phpbb\user', ['get_passworded_forums']);
		$this->user->method('get_passworded_forums')->willReturn([9]);

		$this->assertSame([], $this->get_similar_topics()->search_similar_topics_ajax('query'));
	}

	public function test_search_similar_topics_ajax_rejects_passworded_included_forums(): void
	{
		global $config, $user;
		$this->config = $config = new \phpbb\config\config(['similar_topics_time' => 86400]);
		$this->stop_word_helper->method('clean_text')->willReturn('query');
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->manager->method('get_driver')->willReturn($this->driver);
		$this->driver->method('get_query')->willReturn(['SELECT' => 't.*', 'FROM' => [], 'WHERE' => '1=1']);
		$this->db->method('sql_query')->willReturn(true);
		$this->db->method('sql_fetchfield')->willReturn('[2]');
		$this->user = $user = $this->createPartialMock('\phpbb\user', ['get_passworded_forums']);
		$this->user->method('get_passworded_forums')->willReturn([2]);

		$this->assertSame([], $this->get_similar_topics()->search_similar_topics_ajax('query', 1));
	}

	public function test_add_language(): void
	{
		$this->db->method('get_sql_layer')->willReturn('');
		$similar_topics = $this->get_similar_topics();
		$similar_topics->add_language();
		$this->assertTrue($this->language->is_set('SIMILAR_TOPICS'));
	}

	private function topic_row(array $overrides = []): array
	{
		return array_replace([
			'forum_id' => 2,
			'forum_name' => 'Forum',
			'topic_id' => 3,
			'topic_title' => 'Similar topic',
			'topic_status' => ITEM_UNLOCKED,
			'topic_type' => POST_NORMAL,
			'topic_visibility' => ITEM_APPROVED,
			'topic_posts_unapproved' => 0,
			'topic_attachment' => 1,
			'topic_reported' => 1,
			'topic_posted' => 0,
			'topic_views' => 10,
			'topic_poster' => 2,
			'topic_first_poster_name' => 'Author',
			'topic_first_poster_colour' => '',
			'topic_last_poster_id' => 3,
			'topic_last_poster_name' => 'Last',
			'topic_last_poster_colour' => '',
			'topic_time' => 100,
			'topic_last_post_time' => 200,
			'topic_last_post_id' => 4,
			'f_mark_time' => 50,
			'poll_start' => 0,
			'icon_id' => 1,
		], $overrides);
	}
}
