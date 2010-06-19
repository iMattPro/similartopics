<?php
/**
*
* @package - Precise Similar Topics II
* @version $Id: similar_topics.php, 11 6/18/10 10:47 PM VSE $
* @copyright (c) Matt Friedman, Tobias Schäfer, Xabi
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

/**
* Get similar topics based on matching topic titles
* Note: currently requires MySQL due to use of MATCH and AGAINST and UNIX_TIMESTAMP
* 
* @param array 	$topic_data		The current topic data for use in searching
* @param int 	$forum_id		The current forum to check
*/
function similar_topics(&$topic_data, $forum_id)
{
	global $auth, $config, $user, $db, $template, $phpbb_root_path, $phpEx;

	// Bail out if not using required MySQL to prevent any problems
	if ($db->sql_layer != 'mysql4' && $db->sql_layer != 'mysqli')
	{
		return;
	}

	// Bail out if the current forum is set to DO NOT DISPLAY similar topics
	if (!empty($config['similar_topics_hide']))
	{
		if (in_array($forum_id, explode(',', $config['similar_topics_hide'])))
		{
			return;
		}
	}

	// If similar topics is enabled and the number of topics to show is <> 0, proceed...
	if ($config['similar_topics'] && $config['similar_topics_limit'])
	{
		$sql_array = array(
			'SELECT'	=> 'f.forum_id, f.forum_name, 
				t.topic_id, t.topic_title, t.topic_time, t.topic_views, t.topic_replies, t.topic_poster, t.topic_first_poster_name, t.topic_first_poster_colour, 
				MATCH (t.topic_title) AGAINST (\'' . $db->sql_escape($topic_data['topic_title']) . '\') as score',
		
			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),

			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id'
				)
			),

			'WHERE'		=> "MATCH (t.topic_title) AGAINST ('" . $db->sql_escape($topic_data['topic_title']) . "') >= 0.5
				AND t.topic_status <> " . ITEM_MOVED . '
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . $config['similar_topics_time'] . ')
				AND t.topic_id <> ' . (int) $topic_data['topic_id'],

			'GROUP_BY'	=> 't.topic_id',

			'ORDER_BY'	=> 'score DESC',
		);

		// Now lets see if the current forum is set to search a specific forum search group, and search only those forums
		if (!empty($topic_data['similar_topic_forums']))
		{
			$sql_array['WHERE'] .= ' AND f.forum_id IN (' . $topic_data['similar_topic_forums'] . ')';
		}
		// Otherwise, lets see what forums are not allowed to be searched, and ignore those
		else if (!empty($config['similar_topics_ignore']))
		{
			$sql_array['WHERE'] .= ' AND f.forum_id NOT IN (' . $config['similar_topics_ignore'] . ')';
		}

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $config['similar_topics_limit'], 0, $config['similar_topics_cache']);
		$similar_topics = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		if (sizeof($similar_topics))
		{
			foreach ($similar_topics as $similar)
			{
				if ($auth->acl_get('f_read', $similar['forum_id']))
				{
					$template->assign_block_vars('similar', array(
						'TOPIC_TITLE'		=> $similar['topic_title'],
						'TOPIC_VIEWS'		=> $similar['topic_views'],
						'TOPIC_REPLIES'		=> $similar['topic_replies'],
						'TOPIC_TIME'		=> $user->format_date($similar['topic_time']),
						'TOPIC_AUTHOR_FULL'	=> get_username_string('full', $similar['topic_poster'], $similar['topic_first_poster_name'], $similar['topic_first_poster_colour']),
						'U_TOPIC'			=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=" . $similar['forum_id'] . '&amp;t=' . $similar['topic_id']),
						'U_FORUM'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx", "f=" . $similar['forum_id']),
						'FORUM'				=> $similar['forum_name'])
					);
				}
			}
		}
	}
}
?>