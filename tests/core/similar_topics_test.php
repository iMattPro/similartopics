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

	/** @var \phpbb\extension\manager|\PHPUnit\Framework\MockObject\MockObject */
	protected $ext_manager;

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
		$this->manager = $this->createMock('\vse\similartopics\driver\manager');
		$this->driver = $this->createMock('\vse\similartopics\driver\driver_interface');
		$this->ext_manager = $this->createMock('\phpbb\extension\manager');

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
			$this->ext_manager,
			$this->language,
			$this->pagination,
			$this->request,
			$this->template,
			$this->user,
			$this->content_visibility,
			$this->manager,
			$this->phpbb_root_path,
			$this->phpEx
		);
	}

	public function is_available_test_data()
	{
		return [
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				true,
			],
			[
				[
					'similar_topics' => false,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => false,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => false],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, false],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => false,
					'similar_topics_limit' => false,
				],
				['user_similar_topics' => false],
				['u_similar_topics', 0, false],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => '',
					'similar_topics_limit' => '',
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => ''],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysql4',
				true,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'innodb',
				false,
			],
			[
				[
					'similar_topics' => null,
					'similar_topics_limit' => null,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => null],
				['u_similar_topics', 0, true],
				'mysqli',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'postgres',
				true,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'sqlite',
				false,
			],
			[
				[
					'similar_topics' => true,
					'similar_topics_limit' => true,
				],
				['user_similar_topics' => true],
				['u_similar_topics', 0, true],
				'sqlite3',
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
			->willReturn((in_array($sql_layer, ['mysqli', 'mysql4', 'postgres']) ? $this->driver : null));

		$similar_topics = $this->get_similar_topics();

		self::assertEquals($expected, $similar_topics->is_available());
	}

	public function clean_topic_title_test_data()
	{
		return [
			['The quick, brown fox jumps over a lazy dog.', 'brown lazy', 'the quick fox jumps over dog'],
			['The quick, brown fox jumps over a lazy dog.', 'the quick brown fox jumps over a lazy dog', ''],
			['The quick, brown fox jumps over a lazy dog.', '', 'the quick brown fox jumps over lazy dog'],
			['El zorro marrón rápido salta por encima de un perro perezoso.', 'marrón', 'zorro rápido salta por encima perro perezoso'],
			['The "quick", brown fox & jumps &amp; over a &quot;lazy&quot; dog.', 'brown lazy', 'the quick fox jumps over dog'],
		];
	}

	/**
	 * @dataProvider clean_topic_title_test_data
	 */
	public function test_clean_topic_title($test_string, $ignore_words, $expected)
	{
		$this->service->method('get_driver')
			->willReturnCallback([$this, 'set_cache']);

		$this->config_text->expects(self::once())
			->method('get')
			->with('similar_topics_words')
			->willReturn($ignore_words);

		$this->ext_manager->expects(self::once())
			->method('get_finder')
			->willReturnCallback([$this, 'get_finder']);

		$similar_topics = $this->get_similar_topics();

		self::assertSame($expected, $similar_topics->clean_topic_title($test_string));
	}

	public function set_cache()
	{
		$cache = $this->createMock('\phpbb\cache\driver\driver_interface');
		$cache->method('get')
			->willReturn(false);

		return $cache;
	}

	public function get_finder()
	{
		$finder = $this->createMock('\phpbb\finder');
		$finder->expects(self::once())
			->method('set_extensions')
			->willReturnSelf();
		$finder->expects(self::once())
			->method('prefix')
			->willReturnSelf();
		$finder->expects(self::once())
			->method('suffix')
			->willReturnSelf();
		$finder->expects(self::once())
			->method('extension_directory')
			->willReturnSelf();
		$finder->expects(self::once())
			->method('core_path')
			->willReturnSelf();
		$finder->expects(self::once())
			->method('get_files')
			->willReturn([]);

		return $finder;
	}
}
