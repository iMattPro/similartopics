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
		$this->service = $this->getMockBuilder('\phpbb\cache\service')
			->disableOriginalConstructor()
			->getMock();
		$this->config_text = $this->getMockBuilder('\phpbb\config\db_text')
			->disableOriginalConstructor()
			->getMock();
		$this->db = $this->getMockBuilder('\phpbb\db\driver\driver_interface')
			->getMock();
		$this->dispatcher = $this->getMockBuilder('\phpbb\event\dispatcher_interface')
			->getMock();
		$this->pagination = $this->getMockBuilder('\phpbb\pagination')
			->disableOriginalConstructor()
			->getMock();
		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()
			->getMock();
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->getMock();
		$this->content_visibility = $this->getMockBuilder('\phpbb\content_visibility')
			->disableOriginalConstructor()
			->getMock();
		$this->manager = $this->getMockBuilder('\vse\similartopics\driver\manager')
			->disableOriginalConstructor()
			->getMock();
		$this->driver = $this->getMockBuilder('\vse\similartopics\driver\driver_interface')
			->getMock();
		$this->ext_manager = $this->getMockBuilder('\phpbb\extension\manager')
			->disableOriginalConstructor()
			->getMock();

		// Classes used in the tests
		$this->auth = $this->getMockBuilder('\phpbb\auth\auth')->getMock();
		$this->config = new \phpbb\config\config(array());
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
		return array(
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'mysqli',
				true,
			),
			array(
				array(
					'similar_topics' => false,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => false,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => false),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, false),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => false,
					'similar_topics_limit' => false,
				),
				array('user_similar_topics' => false),
				array('u_similar_topics', 0, false),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => '',
					'similar_topics_limit' => '',
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => ''),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'mysql4',
				true,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'innodb',
				false,
			),
			array(
				array(
					'similar_topics' => null,
					'similar_topics_limit' => null,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => null),
				array('u_similar_topics', 0, true),
				'mysqli',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'postgres',
				true,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'sqlite',
				false,
			),
			array(
				array(
					'similar_topics' => true,
					'similar_topics_limit' => true,
				),
				array('user_similar_topics' => true),
				array('u_similar_topics', 0, true),
				'sqlite3',
				false,
			),
		);
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
			->willReturnMap(array($auth_data));
		$this->db->expects(self::atMost(2))
			->method('get_sql_layer')
			->willReturn($sql_layer);
		$this->manager->expects(self::once())
			->method('get_driver')
			->with($sql_layer)
			->willReturn((in_array($sql_layer, array('mysqli', 'mysql4', 'postgres')) ? $this->driver : null));

		$similar_topics = $this->get_similar_topics();

		self::assertEquals($expected, $similar_topics->is_available());
	}

	public function clean_topic_title_test_data()
	{
		return array(
			array('The quick, brown fox jumps over a lazy dog.', 'brown lazy', 'the quick fox jumps over dog'),
			array('The quick, brown fox jumps over a lazy dog.', 'the quick brown fox jumps over a lazy dog', ''),
			array('The quick, brown fox jumps over a lazy dog.', '', 'the quick brown fox jumps over lazy dog'),
			array('El zorro marrón rápido salta por encima de un perro perezoso.', 'marrón', 'zorro rápido salta por encima perro perezoso'),
			array('The "quick", brown fox & jumps &amp; over a &quot;lazy&quot; dog.', 'brown lazy', 'the quick fox jumps over dog'),
		);
	}

	/**
	 * @dataProvider clean_topic_title_test_data
	 */
	public function test_clean_topic_title($test_string, $ignore_words, $expected)
	{
		$this->service->method('get_driver')
			->willReturnCallback(array($this, 'set_cache'));

		$this->config_text->expects(self::once())
			->method('get')
			->with('similar_topics_words')
			->willReturn($ignore_words);

		$this->ext_manager->expects(self::once())
			->method('get_finder')
			->willReturnCallback(array($this, 'get_finder'));

		$similar_topics = $this->get_similar_topics();

		self::assertSame($expected, $similar_topics->clean_topic_title($test_string));
	}

	public function set_cache()
	{
		$cache = $this->getMockBuilder('\phpbb\cache\driver\driver_interface')
			->getMock();
		$cache->method('get')
			->willReturn(false);

		return $cache;
	}

	public function get_finder()
	{
		$finder = $this->getMockBuilder('\phpbb\finder')
			->disableOriginalConstructor()
			->getMock();
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
			->willReturn(array());

		return $finder;
	}
}
