<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\acp;

class module_test extends \phpbb_test_case
{
	/**
	 * Test the acp module instance
	 */
	public function test_module()
	{
		global $phpbb_container, $phpbb_root_path, $phpEx;

		// Test basic module instantiation
		$module = new \vse\similartopics\acp\similar_topics_module();
		$this->assertInstanceOf('\vse\similartopics\acp\similar_topics_module', $module);

		// Test calling module->main()
		$lang_loader = new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx);
		$lang = new \phpbb\language\language($lang_loader);
		$user = new \phpbb\user($lang, '\phpbb\datetime');

		$mock_acp_controller = $this->getMockBuilder('\vse\similartopics\acp\controller\similar_topics_admin')
			->disableOriginalConstructor()
			->setMethods(array('handle', 'set_u_action'))
			->getMock();

		$mock_acp_controller->expects($this->once())
			->method('set_u_action')
			->willReturn($mock_acp_controller);

		$phpbb_container = $this->createMock('Symfony\Component\DependencyInjection\ContainerInterface');
		$phpbb_container
			->expects($this->at(0))
			->method('get')
			->with('language')
			->willReturn($user);

		$phpbb_container
			->expects($this->at(1))
			->method('get')
			->with('vse.similartopics.acp.controller')
			->willReturn($mock_acp_controller);

		$module->main();
	}

	public function test_info()
	{
		$info_class = new \vse\similartopics\acp\similar_topics_info();
		$info_array = $info_class->module();
		$this->assertArrayHasKey('filename', $info_array);
		$this->assertEquals('\vse\similartopics\acp\similar_topics_module', $info_array['filename']);
	}
}
