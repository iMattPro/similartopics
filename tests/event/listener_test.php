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

class listener_test extends \phpbb_test_case
{
	/** @var \vse\similartopics\event\listener */
	protected $listener;

	/**
	* Setup test environment
	*/
	public function setUp()
	{
		parent::setUp();

		// Load/Mock classes required by the event listener class
		$this->auth = $this->getMock('\phpbb\auth\auth');
		$this->config = new \phpbb\config\config(array('similar_topics' => 1));
		$this->user = $this->getMock('\phpbb\user', array(), array('\phpbb\datetime'));
		$this->similar_topics = $this->getMockBuilder('\vse\similartopics\core\similar_topics')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	* Create our event listener
	*/
	protected function set_listener()
	{
		$this->listener = new \vse\similartopics\event\listener(
			$this->auth,
			$this->config,
			$this->user,
			$this->similar_topics
		);
	}

	/**
	* Test the event listener is constructed correctly
	*/
	public function test_construct()
	{
		$this->set_listener();
		$this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	* Test the event listener is subscribing events
	*/
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.viewtopic_modify_page_title',
			'core.permissions',
		), array_keys(\vse\similartopics\event\listener::getSubscribedEvents()));
	}

	/**
	* Data set for test_add_permissions
	*
	* @return array Array of test data
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
}
