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

class ucp_listener_test extends \phpbb_test_case
{
	/** @var \vse\similartopics\event\listener */
	protected $listener;

	/**
	* Setup test environment
	*
	* @access public
	*/
	public function setUp()
	{
		parent::setUp();

		// Load/Mock classes required by the event listener class
		$this->auth = $this->getMock('\phpbb\auth\auth');
		$this->config = new \phpbb\config\config(array('similar_topics' => 1));
		$this->request = $this->getMock('\phpbb\request\request');
		$this->template = new \vse\similartopics\tests\mock\template();
		$this->user = $this->getMock('\phpbb\user');
		$this->similar_topics = new \vse\similartopics\tests\mock\similar_topics();
	}

	/**
	* Create our event listener
	*
	* @access protected
	*/
	protected function set_listener()
	{
		$this->listener = new \vse\similartopics\event\listener(
			$this->auth,
			$this->config,
			$this->request,
			$this->template,
			$this->user,
			$this->similar_topics
		);
	}

	/**
	* Test the event listener is constructed correctly
	*
	* @access public
	*/
	public function test_construct()
	{
		$this->set_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	* Test the event listener is subscribing events
	*
	* @access public
	*/
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.viewtopic_modify_page_title',
			'core.permissions',
			'core.ucp_prefs_view_data',
			'core.ucp_prefs_view_update_data',
		), array_keys(\vse\similartopics\event\listener::getSubscribedEvents()));
	}

	/**
	* Data set for test_add_permissions
	*
	* @return array Array of test data
	* @access public
	*/
	public function add_permissions_data()
	{
		return array(
			array(
				array(),
				array(
					array(
						'lang' => 'ACL_U_SIMILARTOPICS',
						'cat' => 'misc',
					),
				),
			),
			array(
				array(
					array(
						'lang' => 'ACL_U_FOOBAR',
						'cat' => 'misc',
					),
				),
				array(
					array(
						'lang' => 'ACL_U_FOOBAR',
						'cat' => 'misc',
					),
					array(
						'lang' => 'ACL_U_SIMILARTOPICS',
						'cat' => 'misc',
					),
				),
			),
		);
	}

	/**
	* Test the add_permissions event
	*
	* @dataProvider add_permissions_data
	* @access public
	*/
	public function test_add_permissions($permissions, $expected_contains)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.permissions', array($this->listener, 'add_permissions'));

		$event_data = array('permissions');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.permissions', $event);

		$permissions = $event->get_data_filtered($event_data);
		$permissions = $permissions['permissions'];

		foreach ($expected_contains as $expected)
		{
			$this->assertContains($expected, $permissions);
		}
	}

	/**
	* Data set for test_ucp_prefs_set_data
	*
	* @return array Array of test data
	* @access public
	*/
	public function ucp_prefs_set_data_data()
	{
		return array(
			array(
				array(),
				array(),
				array('user_similar_topics' => 0),
			),
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
	* @access public
	*/
	public function test_ucp_prefs_set_data($data, $sql_ary, $expected)
	{
		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_prefs_view_update_data', array($this->listener, 'ucp_prefs_set_data'));

		$event_data = array('data', 'sql_ary');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.ucp_prefs_view_update_data', $event);

		$event_data_after = $event->get_data_filtered($event_data);
		$sql_ary = $event_data_after['sql_ary'];

		$this->assertEquals($expected, $sql_ary);
	}

	/**
	* Data set for test_ucp_prefs_set_data
	*
	* @return array Array of test data
	* @access public
	*/
	public function ucp_prefs_get_data_data()
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
	* @access public
	*/
	public function test_ucp_prefs_get_data($similar_topics, $submit, $u_similar_topics, $data, $expected)
	{
		$this->auth->expects($this->any())
			->method('acl_get')
			->with($this->stringContains('u_similar_topics'), $this->anything())
			->will($this->returnValue($u_similar_topics));

		$this->user->data['user_similar_topics'] = 0;
		$this->request->expects($this->any())
			->method('variable')
			->will($this->returnValue($similar_topics));

		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.ucp_prefs_view_data', array($this->listener, 'ucp_prefs_get_data'));

		$event_data = array('submit', 'data');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.ucp_prefs_view_data', $event);

		$data = $event->get_data_filtered($event_data);
		$data = $data['data'];

		$this->assertEquals($expected, $data);

		if (!$submit)
		{
			$this->assertEquals(array(
				'S_SIMILAR_TOPICS'			=> $u_similar_topics,
				'S_DISPLAY_SIMILAR_TOPICS'	=> $similar_topics,
			), $this->template->get_template_vars());
		}
	}
}
