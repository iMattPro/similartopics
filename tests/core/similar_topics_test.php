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

class similar_topics_test extends \phpbb_test_case
{
	/** @var \phpbb\auth\auth|\PHPUnit\Framework\MockObject\MockObject */
	protected $auth;

	/** @var \phpbb\cache\service|\PHPUnit\Framework\MockObject\MockObject */
	protected $service;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text|\PHPUnit\Framework\MockObject\MockObject */
	protected $config_text;

	/** @var \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	/** @var \phpbb\event\dispatcher|\PHPUnit\Framework\MockObject\MockObject */
	protected $dispatcher;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\pagination|\PHPUnit\Framework\MockObject\MockObject */
	protected $pagination;

	/** @var \phpbb\request\request|\PHPUnit\Framework\MockObject\MockObject */
	protected $request;

	/** @var \phpbb\template\template|\PHPUnit\Framework\MockObject\MockObject */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\content_visibility|\PHPUnit\Framework\MockObject\MockObject */
	protected $content_visibility;

	/** @var \vse\similartopics\core\stop_word_helper|\PHPUnit\Framework\MockObject\MockObject */
	protected $stop_word_helper;

	/** @var \vse\similartopics\driver\manager|\PHPUnit\Framework\MockObject\MockObject */
	protected $manager;

	/** @var \vse\similartopics\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $driver;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $phpEx;

	protected function setUp(): void
	{
		parent::setUp();

		global $phpbb_dispatcher, $cache, $phpbb_root_path, $phpEx;

		// Classes we just need to mock for the constructor
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		$cache = new \phpbb_mock_cache();
		$this->service = $this->createMock('\phpbb\cache\service');
		$this->service->method('get_driver')->willReturn($cache);
		$this->config_text = $this->createMock('\phpbb\config\db_text');
		$this->db = $this->createMock('\phpbb\db\driver\driver_interface');
		$this->dispatcher = $this->createMock('\phpbb\event\dispatcher_interface');
		$this->pagination = $this->createMock('\phpbb\pagination');
		$this->request = $this->createMock('\phpbb\request\request');
		$this->template = $this->createMock('\phpbb\template\template');
		$this->content_visibility = $this->createMock('\phpbb\content_visibility');
		$this->stop_word_helper = $this->createMock('\vse\similartopics\core\stop_word_helper');
		$this->manager = $this->createMock('\vse\similartopics\driver\manager');
		$this->driver = $this->createMock('\vse\similartopics\driver\driver_interface');

		// Classes used in the tests
		$this->extension_manager = new \phpbb_mock_extension_manager(
			$phpbb_root_path,
			array(
				'vse/similartopics' => array(
					'ext_name' => 'vse/similartopics',
					'ext_active' => '1',
					'ext_path' => 'ext/vse/similartopics',
				),
			));
		$this->auth = $this->createMock('\phpbb\auth\auth');
		$this->config = new \phpbb\config\config([]);
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$lang_loader->set_extension_manager($this->extension_manager);
		$this->language = new \phpbb\language\language($lang_loader);
		$this->user = new \phpbb\user($this->language, '\phpbb\datetime');
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpEx = $phpEx;
	}

	public function get_similar_topics()
	{
		return new \vse\similartopics\core\similar_topics(
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

	public function is_available_test_data()
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
	public function test_is_available($method, $config_data, $user_data, $auth_data, $sql_layer, $expected)
	{
		$this->config = new \phpbb\config\config($config_data);
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

	public function test_display_similar_topics_hidden_forum()
	{
		$topic_data = ['similar_topics_hide' => true];
		$similar_topics = $this->get_similar_topics();

		// Should return early without doing anything
		$similar_topics->display_similar_topics($topic_data);
		$this->addToAssertionCount(1);
	}

	public function test_display_similar_topics_empty_title()
	{
		$topic_data = ['similar_topics_hide' => false, 'topic_title' => ''];
		$this->stop_word_helper->method('clean_text')->willReturn('');
		$similar_topics = $this->get_similar_topics();

		$similar_topics->display_similar_topics($topic_data);
		$this->addToAssertionCount(1);
	}

	public function test_search_similar_topics_ajax_empty_query()
	{
		$this->stop_word_helper->method('clean_text')->willReturn('');
		$similar_topics = $this->get_similar_topics();

		$result = $similar_topics->search_similar_topics_ajax('', 1);
		self::assertEquals([], $result);
	}

	public function test_search_similar_topics_ajax_with_results()
	{
		global $config, $user, $auth, $cache;
		$this->config = $config = new \phpbb\config\config(['similar_topics_time' => 86400]);
		$this->stop_word_helper->method('clean_text')->willReturn('test query');
		$this->db->method('get_sql_layer')->willReturn('mysqli');
		$this->manager->method('get_driver')->willReturn($this->driver);
		$this->driver->method('get_query')->willReturn(['SELECT' => 't.topic_id, t.topic_title', 'FROM' => [], 'WHERE' => '1=1']);
		$this->user = $user = $this->createPartialMock('\phpbb\user', ['get_passworded_forums', 'optionget']);
		$this->user->method('get_passworded_forums')->willReturn([]);
		$this->auth->method('acl_get')->willReturn(true);
		$auth = $this->auth;
		$cache = new \phpbb_mock_cache();

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
		$this->db->method('sql_fetchfield')->willReturn(null);

		$similar_topics = $this->get_similar_topics();
		$result = $similar_topics->search_similar_topics_ajax('test query', 1);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertEquals(1, $result[0]['id']);
		self::assertEquals('Test Topic', $result[0]['title']);
	}

	public function test_add_language()
	{
		$similar_topics = $this->get_similar_topics();
		$similar_topics->add_language();
		$this->assertTrue($this->language->is_set('SIMILAR_TOPICS'));
	}
}
