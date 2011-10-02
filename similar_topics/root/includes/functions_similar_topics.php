<?php
/**
*
* @package Precise Similar Topics II
* @version $Id: functions_similar_topics.php, 20 9/30/11 8:03 PM VSE $
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
		// If board is non-English or custom ignore words are defined, apply pst_ignore_words function to topic_title
		$topic_title = (($user->lang_name == 'en' || $user->lang_name == 'en_us') && empty($config['similar_topics_words'])) ? $topic_data['topic_title'] : pst_ignore_words($topic_data['topic_title']);
		$topic_title = str_replace(array('&quot;', '&amp;'), '', $topic_title); //strip quotes, ampersands

		// If the topic_title winds up being empty, no need to continue
		if (empty($topic_title))
		{
			return;
		}

		$sql_array = array(
			'SELECT'	=> 'f.forum_id, f.forum_name, 
				t.topic_id, t.topic_title, t.topic_time, t.topic_views, t.topic_replies, t.topic_poster, t.topic_first_poster_name, t.topic_first_poster_colour, 
				MATCH (t.topic_title) AGAINST (\'' . $db->sql_escape($topic_title) . '\') as score',
		
			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),

			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id'
				)
			),

			'WHERE'		=> "MATCH (t.topic_title) AGAINST ('" . $db->sql_escape($topic_title) . "') >= 0.5
				AND t.topic_status <> " . ITEM_MOVED . '
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . $config['similar_topics_time'] . ')
				AND t.topic_id <> ' . (int) $topic_data['topic_id'],

//			'GROUP_BY'	=> 't.topic_id',

//			'ORDER_BY'	=> 'score DESC',
		);

		// Now lets see if the current forum is set to search a specific forum search group, and search only those forums
		if (!empty($topic_data['similar_topic_forums']))
		{
			$sql_array['WHERE'] .= ' AND ' . $db->sql_in_set('f.forum_id', explode(',', $topic_data['similar_topic_forums']));
		}
		// Otherwise, lets see what forums are not allowed to be searched, and ignore those
		else if (!empty($config['similar_topics_ignore']))
		{
			$sql_array['WHERE'] .= ' AND ' . $db->sql_in_set('f.forum_id', explode(',', $config['similar_topics_ignore']), true);
		}

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $config['similar_topics_limit'], 0, $config['similar_topics_cache']);

		while ($similar = $db->sql_fetchrow($result))
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
		$db->sql_freeresult($result);
	}
}

/**
* MySQL full-text has built-in English stop words. Use phpBB's ignore words for non-English languages
* Also remove any admin-defined special ignore words
* This will remove uppercases, and ignore words of 2 characters or less
* 
* @param  string $text			The topic title
* @return string $text			The topic titled with any ignore words removed
*/
function pst_ignore_words($text)
{
	global $config, $user;
	
	// strip out any non alpha-numeric characters using PCRE regex syntax
	$text = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $text)); 

	// Put all unique words in the title into an array, and remove uppercases and short words
	$word_list = array();

	if (!empty($text))
	{
		$word_list = array_unique(explode(' ', utf8_strtolower($text)));
		foreach ($word_list as $key => $word)
		{
			// Lets eliminate all words of 2 characters or less
			if (utf8_strlen(trim($word)) < 3)
			{
				unset($word_list[$key]);
			}
		}
	}

	// Use phpBB's stop-words if non-English user language is detected
	if ($user->lang_name != 'en' && $user->lang_name != 'en_us' && !empty($word_list))
	{
		global $phpbb_root_path, $phpEx;

		// Retrieves a language dependent list of words that should be ignored (method copied from search.php)
		$words = array();
		if (file_exists("{$user->lang_path}{$user->lang_name}/search_ignore_words.$phpEx"))
		{
			// include the file containing ignore words
			include("{$user->lang_path}{$user->lang_name}/search_ignore_words.$phpEx");
		}
		$word_list = array_diff($word_list, $words);
	}

	// Filter out custom un-wanted words
	if(!empty($config['similar_topics_words']) && !empty($word_list))
	{
		$words = explode(' ', utf8_strtolower($config['similar_topics_words']));
		$word_list = array_diff($word_list, $words);
	}
	
	// Rebuild our cleaned up topic title
	$text = !empty($word_list) ? implode(' ', $word_list) : '';
	return $text;
}

?>