<?php
/**
*
* @package Precise Similar Topics II
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/

if (!defined('IN_PHPBB'))
{
    exit;
}

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class phpbb_ext_vse_similartopics_event_listener implements EventSubscriberInterface
{

	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_page_title'		=> 'load_similar_topics',
			'core.permissions'						=> 'add_permissions',

			'core.ucp_prefs_view_data'				=> 'ucp_prefs_get_data', // need to request
			'core.ucp_prefs_view_update_data'		=> 'ucp_prefs_set_data', // need to request
		);
	}

	public function load_similar_topics($event)
	{
		global $config, $auth, $user, $phpbb_container;

		// Return early if not supposed to see similar topics
		if (empty($config['similar_topics']) || empty($user->data['user_similar_topics']) || !$auth->acl_get('u_similar_topics'))
		{
			return;
		}

		$similar = $phpbb_container->get('similartopics.manager');
		$similar->get_similar_topics($event);
	}

	public function add_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_similar_topics'] = array('lang' => 'ACL_U_SIMILARTOPICS', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	/**
	* Get user's Similar Topics option and display it in UCP Prefs View page
	*
	* @param Event $event Event object
	* @return null
	*/
	public function ucp_prefs_get_data($event)
	{
		global $auth, $config, $user, $template;

		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], array(
			'similar_topics'	=> request_var('similar_topics', (int) $user->data['user_similar_topics']),
		));

		// Output the data vars to the template (except on form submit)
		if (!$event['submit'])
		{
			$data = $event['data'];
			$user->add_lang_ext('vse/similartopics', 'similar_topics');
			$template->assign_vars(array(
				'S_SIMILAR_TOPICS'			=> $config['similar_topics'] && $auth->acl_get('u_similar_topics'),
				'S_DISPLAY_SIMILAR_TOPICS'	=> $data['similar_topics'],
			));
		}
	}

	/**
	* Add user's Similar Topics option state into the sql_array
	*
	* @param Event $event Event object
	* @return null
	*/
	public function ucp_prefs_set_data($event)
	{
		$data = $event['data'];
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'user_similar_topics' => $data['similar_topics'],
		));
	}

}
