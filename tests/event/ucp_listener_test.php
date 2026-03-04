<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\event\dispatcher;
use phpbb\language\language;
use phpbb\language\language_file_loader;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use vse\similartopics\event\ucp_listener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ucp_listener_test extends phpbb_test_case
{
	/** @var ucp_listener */
	protected ucp_listener $listener;

	/** @var MockObject|auth */
	protected MockObject|auth $auth;

	/** @var config */
	protected config $config;

	/** @var language */
	protected language $language;

	/** @var MockObject|request */
	protected MockObject|request $request;

	/** @var MockObject|template */
	protected MockObject|template $template;

	/** @var MockObject|user */
	protected MockObject|user $user;

	/**
	 * Setup test environment
	 */
	protected function setUp(): void
	{
		parent::setUp();

		global $phpbb_root_path, $phpEx;

		// Load/Mock classes required by the event listener class
		$this->auth = $this->createMock(auth::class);
		$this->config = new config(array('similar_topics' => 1));
		$this->request = $this->createMock(request::class);
		$this->template = $this->createMock(template::class);
		$this->language = new language(new language_file_loader($phpbb_root_path, $phpEx));
		$this->user = $this->createMock(user::class);
	}

	/**
	 * Create our event listener
	 */
	protected function set_listener(): void
	{
		$this->listener = new ucp_listener(
			$this->auth,
			$this->config,
			$this->language,
			$this->request,
			$this->template,
			$this->user
		);
	}

	/**
	 * Test the event listener is constructed correctly
	 */
	public function test_construct(): void
	{
		$this->set_listener();
		self::assertInstanceOf(EventSubscriberInterface::class, $this->listener);
	}

	/**
	 * Test the event listener is subscribing events
	 */
	public function test_getSubscribedEvents(): void
	{
		self::assertEquals(array(
			'core.ucp_prefs_view_data',
			'core.ucp_prefs_view_update_data',
		), array_keys(ucp_listener::getSubscribedEvents()));
	}

	/**
	 * Data set for test_ucp_prefs_set_data
	 *
	 * @return array Array of test data
	 */
	public static function ucp_prefs_set_data_data(): array
	{
		return array(
			array(
				array('similar_topics' => 1),
				array(),
				array('user_similar_topics' => 1),
			),
			array(
				array(
					'user_options'		=> 0,
					'similar_topics'	=> 1,
				),
				array(
					'user_options'				=> 0,
					'user_topic_sortby_type'	=> 0,
					'user_post_sortby_type'		=> 0,
					'user_topic_sortby_dir'		=> 0,
					'user_post_sortby_dir'		=> 0,
				),
				array(
					'user_options'				=> 0,
					'user_topic_sortby_type'	=> 0,
					'user_post_sortby_type'		=> 0,
					'user_topic_sortby_dir'		=> 0,
					'user_post_sortby_dir'		=> 0,
					'user_similar_topics'		=> 1,
				),
			),
		);
	}

	/**
	 * Test the ucp_prefs_set_data event
	 *
	 * @dataProvider ucp_prefs_set_data_data
	 */
	public function test_ucp_prefs_set_data($data, $sql_ary, $expected): void
	{
		$this->set_listener();

		$dispatcher = new dispatcher();
		$dispatcher->addListener('core.ucp_prefs_view_update_data', array($this->listener, 'ucp_prefs_set_data'));

		$event_data = array('data', 'sql_ary');
		$event_data_after = $dispatcher->trigger_event('core.ucp_prefs_view_update_data', compact($event_data));
		extract($event_data_after);

		self::assertEquals($expected, $sql_ary);
	}

	/**
	 * Data set for test_ucp_prefs_set_data
	 *
	 * @return array Array of test data
	 */
	public static function ucp_prefs_get_data_data(): array
	{
		return array(
			array(
				1,
				false,
				false,
				array(),
				array('similar_topics' => 1),
			),
			array(
				1,
				false,
				true,
				array(),
				array('similar_topics' => 1),
			),
			array(
				1,
				true,
				true,
				array(),
				array('similar_topics' => 1),
			),
			array(
				1,
				false,
				true,
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
				),
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
					'similar_topics'=> 1,
				),
			),
			array(
				1,
				true,
				true,
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
				),
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
					'similar_topics'=> 1,
				),
			),
			array(
				0,
				false,
				true,
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
				),
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
					'similar_topics'=> 0,
				),
			),
			array(
				0,
				true,
				true,
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
				),
				array(
					'images'		=> 0,
					'flash'			=> 0,
					'smilies'		=> 0,
					'sigs'			=> 0,
					'avatars'		=> 0,
					'wordcensor'	=> 0,
					'similar_topics'=> 0,
				),
			),
		);
	}

	/**
	 * Test the ucp_prefs_get_data event
	 *
	 * @dataProvider ucp_prefs_get_data_data
	 */
	public function test_ucp_prefs_get_data($similar_topics, $submit, $u_similar_topics, $data, $expected): void
	{
		$this->auth->method('acl_get')
			->with(self::stringContains('u_similar_topics'), self::anything())
			->willReturn($u_similar_topics);

		$this->user->data['user_similar_topics'] = 0;
		$this->request->expects(self::once())
			->method('variable')
			->willReturn($similar_topics);

		$this->set_listener();

		if (!$submit)
		{
			$this->template->expects(self::once())
				->method('assign_vars')
				->with(array(
					'S_SIMILAR_TOPICS'			=> $u_similar_topics,
					'S_DISPLAY_SIMILAR_TOPICS'	=> $similar_topics,
				));
		}

		$dispatcher = new dispatcher();
		$dispatcher->addListener('core.ucp_prefs_view_data', array($this->listener, 'ucp_prefs_get_data'));

		$event_data = array('submit', 'data');
		$event_data_after = $dispatcher->trigger_event('core.ucp_prefs_view_data', compact($event_data));
		extract($event_data_after);

		self::assertEquals($expected, $data);
	}
}
