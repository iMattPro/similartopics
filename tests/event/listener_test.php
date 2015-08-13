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

	/** @var \vse\similartopics\core\similar_topics|\PHPUnit_Framework_MockObject_MockObject */
	protected $similar_topics;

	/**
	* Setup test environment
	*/
	public function setUp()
	{
		parent::setUp();

		// Load/Mock classes required by the event listener class
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
	 * Data set for test_display_similar_topics
	 *
	 * @return array Array of test data
	 */
	public function display_similar_topics_data()
	{
		return array(
			array(1, array(1), true, true, true),
			array(2, array(2), false, true, false),
			array(3, array(3), true, false, false),
			array(4, array(4), false, false, false),
		);
	}

	/**
	 * Test display_similar_topics event is working as expected
	 *
	 * @dataProvider display_similar_topics_data
	 */
	public function test_display_similar_topics($forum_id, $topic_data, $is_available, $forum_available, $display)
	{
		$this->similar_topics->expects($this->any())
			->method('is_available')
			->will($this->returnValue($is_available));

		$this->similar_topics->expects($this->any())
			->method('forum_available')
			->with($this->equalTo($forum_id))
			->will($this->returnValue($forum_available));

		$display = ($display) ? $this->once() : $this->never();

		$this->similar_topics->expects($display)
			->method('display_similar_topics')
			->with($topic_data);

		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.viewtopic_modify_page_title', array($this->listener, 'display_similar_topics'));

		$event_data = array('forum_id', 'topic_data');
		$event = new \phpbb\event\data(compact($event_data));
		$dispatcher->dispatch('core.viewtopic_modify_page_title', $event);
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
