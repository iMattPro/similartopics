<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \vse\similartopics\core\similar_topics */
	protected $similar_topics;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param \vse\similartopics\core\similar_topics $similar_topics
	 */
	public function __construct(\vse\similartopics\core\similar_topics $similar_topics)
	{
		$this->similar_topics = $similar_topics;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_page_title'		=> 'display_similar_topics',
			'core.permissions'						=> 'add_permissions',
		);
	}

	/**
	 * Display similar topics
	 *
	 * @access public
	 * @param \phpbb\event\data $event The event object
	 */
	public function display_similar_topics($event)
	{
		// Return early if no reason to display similar topics
		if (!$this->similar_topics->is_available() || !$this->similar_topics->forum_available($event['forum_id']))
		{
			return;
		}

		$this->similar_topics->display_similar_topics($event['topic_data']);
	}

	/**
	 * Add custom permissions language variables
	 *
	 * @access public
	 * @param \phpbb\event\data $event The event object
	 */
	public function add_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_similar_topics'] = array('lang' => 'ACL_U_SIMILARTOPICS', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}
}
