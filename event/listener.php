<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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

	/** @var \phpbb\request\request */
	protected $request;

	/** @var phpbb_template */
	protected $template;

	/** @var phpbb_user */
	protected $user;

	/** @var \vse\similartopics\core\similar_topics */
	protected $similar_topics;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\request\request $request
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \vse\similartopics\core\similar_topics $similar_topics
	* @return \vse\similartopics\event\listener
	* @access public
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \vse\similartopics\core\similar_topics $similar_topics)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
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

			'core.ucp_prefs_view_data'				=> 'ucp_prefs_get_data',
			'core.ucp_prefs_view_update_data'		=> 'ucp_prefs_set_data',
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

	/**
	* Get user's Similar Topics option and display it in UCP Prefs View page
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_prefs_get_data($event)
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], array(
			'similar_topics'	=> $this->request->variable('similar_topics', (int) $this->user->data['user_similar_topics']),
		));

		// Output the data vars to the template (except on form submit)
		if (!$event['submit'])
		{
			$data = $event['data'];
			$this->user->add_lang_ext('vse/similartopics', 'similar_topics');
			$this->template->assign_vars(array(
				'S_SIMILAR_TOPICS'			=> $this->config['similar_topics'] && $this->auth->acl_get('u_similar_topics'),
				'S_DISPLAY_SIMILAR_TOPICS'	=> $data['similar_topics'],
			));
		}
	}

	/**
	* Add user's Similar Topics option state into the sql_array
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_prefs_set_data($event)
	{
		$data = $event['data'];
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'user_similar_topics' => $data['similar_topics'],
		));
	}
}
