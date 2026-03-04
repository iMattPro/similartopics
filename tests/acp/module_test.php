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

use phpbb_test_case;
use vse\similartopics\acp\similar_topics_info;
use vse\similartopics\acp\similar_topics_module;
use Symfony\Component\DependencyInjection\ContainerInterface;
use vse\similartopics\acp\controller\similar_topics_admin;

class module_test extends phpbb_test_case
{
	/**
	 * Test the acp module instance
	 */
	public function test_module(): void
	{
		global $phpbb_container;

		// Test basic module instantiation
		$module = new similar_topics_module();
		$this->assertInstanceOf(similar_topics_module::class, $module);

		// Test calling module->main()
		$mock_acp_controller = $this->getMockBuilder(similar_topics_admin::class)
			->disableOriginalConstructor()
			->onlyMethods(array('handle', 'set_u_action'))
			->getMock();

		$mock_acp_controller->expects($this->once())
			->method('set_u_action')
			->with('u_action')
			->willReturn($mock_acp_controller);

		$phpbb_container = $this->createMock(ContainerInterface::class);
		$phpbb_container->expects(self::once())
			->method('get')
			->with('vse.similartopics.acp.controller')
			->willReturn($mock_acp_controller);

		$module->u_action = 'u_action';
		$module->main();
	}

	public function test_info(): void
	{
		$info_class = new similar_topics_info();
		$info_array = $info_class->module();
		$this->assertArrayHasKey('filename', $info_array);
		$this->assertEquals('\vse\similartopics\acp\similar_topics_module', $info_array['filename']);
	}
}
