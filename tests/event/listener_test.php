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
	protected function setUp(): void
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
		self::assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	 * Test the event listener is subscribing events
	 */
	public function test_getSubscribedEvents()
	{
		self::assertEquals(array(
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
			array(array('forum_id' => 1), true, true),
			array(array('forum_id' => 2), false, false),
		);
	}

	/**
	 * Test display_similar_topics event is working as expected
	 *
	 * @dataProvider display_similar_topics_data
	 */
	public function test_display_similar_topics($topic_data, $is_available, $display)
	{
		$this->similar_topics->expects(self::once())
			->method('is_available')
			->willReturn($is_available);

		$this->similar_topics->expects($is_available ? self::once() : self::never())
			->method('display_similar_topics')
			->with($topic_data);

		$this->set_listener();

		$dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
		$dispatcher->addListener('core.viewtopic_modify_page_title', array($this->listener, 'display_similar_topics'));

		$forum_id = $topic_data['forum_id'];
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

		$data = $event->get_data_filtered($event_data);

		foreach ($expected_contains as $expected)
		{
			self::assertContains($expected, $data['permissions']);
		}
	}
}
