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

	public function setUp(): void
	{
		parent::setUp();

		global $config, $phpbb_dispatcher, $template, $phpbb_root_path, $phpEx;

		$cache = new \phpbb_mock_cache;
		$config = new \phpbb\config\config([]);
		$config_text = $this->createMock('\phpbb\config\db_text');
		$db = $this->new_dbal();
		$phpbb_dispatcher = new \phpbb_mock_event_dispatcher();
		$log = $this->createMock('\phpbb\log\log');
		$this->request = $this->createMock('\phpbb\request\request');
		$template = $this->createMock('\phpbb\template\template');
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$language = new \phpbb\language\language($lang_loader);
		$user = new \phpbb\user($language, '\phpbb\datetime');

		$pst_manager = $this->createMock('\vse\similartopics\driver\manager');

		$this->controller = new \vse\similartopics\acp\controller\similar_topics_admin(
			$cache,
			$config,
			$config_text,
			$db,
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

	public function test_handle()
	{
		$this->request->expects($this->once())
			->method('variable');

		$this->controller->set_u_action('u_action')->handle();
	}
}

/**
 * Mock add_form_key()
 */
function add_form_key()
{
}
