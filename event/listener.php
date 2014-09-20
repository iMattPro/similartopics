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
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \vse\similartopics\core\similar_topics */
	protected $similar_topics;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\user $user
	* @param \vse\similartopics\core\similar_topics $similar_topics
	* @return \vse\similartopics\event\listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\user $user, \vse\similartopics\core\similar_topics $similar_topics)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->user = $user;
		$this->similar_topics = $similar_topics;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_page_title'		=> 'load_similar_topics',
			'core.permissions'						=> 'add_permissions',
		);
	}

	/**
	* Load Similar Topics manager
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function load_similar_topics($event)
	{
		// Return early if not supposed to see similar topics
		if (empty($this->config['similar_topics']) || empty($this->user->data['user_similar_topics']) || !$this->auth->acl_get('u_similar_topics'))
		{
			return;
		}

		$this->similar_topics->get_similar_topics($event['topic_data'], $event['forum_id']);
	}

	/**
	* Add custom permissions language variables
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function add_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_similar_topics'] = array('lang' => 'ACL_U_SIMILARTOPICS', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}
}
