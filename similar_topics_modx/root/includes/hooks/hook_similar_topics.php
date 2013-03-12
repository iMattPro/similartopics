<?php
/**
*
* @package Precise Similar Topics II
* @version $Id$
* @copyright (c) 2010 Matt Friedman
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
 * A hook that is used to load similar topics in the viewtopic body
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

	if (!class_exists('phpbb_similar_topics'))
	{
		include($phpbb_root_path . 'includes/functions_similar_topics.' . $phpEx);
	}

	// Get similar topics
	$similar = new phpbb_similar_topics();
	$similar->get_similar_topics();
}

// Register the hook
if (isset($config['similar_topics']) && $config['similar_topics'])
{
	$phpbb_hook->register(array('template','display'), 'similar_topics_hook');
}
