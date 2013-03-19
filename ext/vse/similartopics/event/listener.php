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
		);
	}

	public function load_similar_topics($event)
	{
		global $config, $auth, $phpbb_container;

		// Return early if not supposed to see similar topics
		if (empty($config['similar_topics']) || !$auth->acl_get('u_similar_topics'))
		{
			return;
		}

		$similar = $phpbb_container->get('similartopics.manager');
		$similar->get_similar_topics($event);
	}
}
