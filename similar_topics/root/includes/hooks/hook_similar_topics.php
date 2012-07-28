<?php
/**
*
* @package Precise Similar Topics II
* @copyright (c) 2010 Matt Friedman
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

// Return if the MOD isn't installed
if (!isset($config['similar_topics']))
{
	return;
}

/**
 * A hook that is used to load similar topics in the viewtopic pages
 * @param	$hook		the phpBB hook object
 * @param	$handle		the phpBB handle object
 * @return	void
 */
function similar_topics_hook(&$hook, $handle)
{
	global $auth, $config, $user, $phpbb_root_path, $phpEx;

	// Return if not supposed to see similar topics
	if (empty($config['similar_topics']) || !$auth->acl_get('u_similar_topics') || ($user->page['page_name'] != 'viewtopic.' . $phpEx) || $handle != 'body')
	{
		return;
	}

	if (!function_exists('similar_topics'))
	{
		include($phpbb_root_path . 'includes/functions_similar_topics.' . $phpEx);
	}

	// get similar topics
	$similar_topics = new similar_topics();
}

// Register the hook
if (isset($config['similar_topics']) && $config['similar_topics'])
{
	$phpbb_hook->register(array('template','display'), 'similar_topics_hook');
}