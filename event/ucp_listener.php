<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\event\data;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class ucp_listener implements EventSubscriberInterface
{
	/** @var auth */
	protected auth $auth;

	/** @var config */
	protected config $config;

	/** @var language */
	protected language $language;

	/** @var request */
	protected request $request;

	/** @var template */
	protected template $template;

	/** @var user */
	protected user $user;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param auth         $auth
	 * @param config     $config
	 * @param language $language
	 * @param request   $request
	 * @param template $template
	 * @param user              $user
	 */
	public function __construct(auth $auth, config $config, language $language, request $request, template $template, user $user)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'core.ucp_prefs_view_data'				=> 'ucp_prefs_get_data',
			'core.ucp_prefs_view_update_data'		=> 'ucp_prefs_set_data',
		];
	}

	/**
	 * Get user's Similar Topics option and display it in UCP Prefs View page
	 *
	 * @access public
	 * @param data $event The event object
	 */
	public function ucp_prefs_get_data(data $event): void
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge($event['data'], [
			'similar_topics'	=> $this->request->variable('similar_topics', (int) $this->user->data['user_similar_topics']),
		]);

		// Output the data vars to the template (except on form submit)
		if (!$event['submit'])
		{
			$this->language->add_lang('similar_topics', 'vse/similartopics');
			$this->template->assign_vars([
				'S_SIMILAR_TOPICS'			=> $this->config['similar_topics'] && $this->auth->acl_get('u_similar_topics'),
				'S_DISPLAY_SIMILAR_TOPICS'	=> $event['data']['similar_topics'],
			]);
		}
	}

	/**
	 * Add user's Similar Topics option state into the sql_array
	 *
	 * @access public
	 * @param data $event The event object
	 */
	public function ucp_prefs_set_data(data $event): void
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_similar_topics' => $event['data']['similar_topics'],
		]);
	}
}
