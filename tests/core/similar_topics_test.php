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

		global $phpbb_root_path, $phpEx;

		// Classes we just need to mock for the constructor
		$this->service = $this->createMock('\phpbb\cache\service');
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
		$this->auth = $this->createMock('\phpbb\auth\auth');
		$this->config = new \phpbb\config\config([]);
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
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

	public static function is_available_test_data()
	{
		return [
			'enabled on mysqli' => [
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
				[
					'similar_topics' => '1',
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mssqlnative',
				true,
			],
			'enabled on invalid db' => [
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
				[
					'similar_topics' => false,
					'similar_topics_limit' => '5',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			'admin show 0 results' => [
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
				[
					'similar_topics' => null,
					'similar_topics_limit' => null,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
		];
	}

	/**
	 * @dataProvider is_available_test_data
	 */
	public function test_is_available($config_data, $user_data, $auth_data, $sql_layer, $expected)
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
			->willReturn((in_array($sql_layer, ['mysqli', 'mysql4', 'postgres', 'sqlite3', 'mssql', 'mssqlnative']) ? $this->driver : null));

		$similar_topics = $this->get_similar_topics();

		self::assertEquals($expected, $similar_topics->is_available());
	}
}
