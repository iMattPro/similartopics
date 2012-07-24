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

/**
* Get similar topics based on matching topic titles
* Note: currently requires MySQL due to use of MATCH and AGAINST and UNIX_TIMESTAMP
* 
* @param array 	$topic_data		The current topic data for use in searching
* @param int 	$forum_id		The current forum to check
*/
function similar_topics($topic_data, $forum_id)
{
	global $auth, $cache, $config, $user, $db, $template, $phpbb_root_path, $phpEx;

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
		$topic_title = clean_title($topic_data['topic_title']);

		// If the topic_title winds up being empty, no need to continue
		if (empty($topic_title))
		{
			return;
		}

		// Grab icons
		$icons = $cache->obtain_icons();

		// Similar Topics query
		$sql_array = array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*, 
				MATCH (t.topic_title) AGAINST ('" . $db->sql_escape($topic_title) . "') AS score",

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
				AND t.topic_approved = 1
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . $config['similar_topics_time'] . ')
				AND t.topic_id <> ' . (int) $topic_data['topic_id'],

//			'GROUP_BY'	=> 't.topic_id',

//			'ORDER_BY'	=> 'score DESC', // this is done automatically by MySQL when not using IN BOOLEAN MODE
		);

		// Add topic tracking data to query (only when query caching is off)
		if ($user->data['is_registered'] && $config['load_db_lastread'] && !$config['similar_topics_cache'])
		{
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(TOPICS_TRACK_TABLE => 'tt'), 'ON' => 'tt.topic_id = t.topic_id AND tt.user_id = ' . $user->data['user_id']);
			$sql_array['SELECT'] .= ', tt.mark_time';
		}

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

		$rowset = array();

		while ($similar = $db->sql_fetchrow($result))
		{
			$similar_forum_id = (int) $similar['forum_id'];
			$similar_topic_id = (int) $similar['topic_id'];
			$rowset[$similar_topic_id] = $similar;

			if ($auth->acl_get('f_read', $similar_forum_id))
			{
				// Get topic tracking info
				if ($user->data['is_registered'] && $config['load_db_lastread'] && !$config['similar_topics_cache'])
				{
					$topic_tracking_info = get_topic_tracking($similar_forum_id, $similar_topic_id, $rowset, array($similar_forum_id => $similar['mark_time']));
				}
				else if ($config['load_anon_lastread'] || $user->data['is_registered'])
				{
					$topic_tracking_info = get_complete_topic_tracking($similar_forum_id, $similar_topic_id);
				}

				$folder_img = $folder_alt = $topic_type = '';
				$replies = ($auth->acl_get('m_approve', $similar_forum_id)) ? $similar['topic_replies_real'] : $similar['topic_replies'];
				$unread_topic = (isset($topic_tracking_info[$similar_topic_id]) && $similar['topic_last_post_time'] > $topic_tracking_info[$similar_topic_id]) ? true : false;
				topic_status($similar, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);

				$topic_unapproved = (!$similar['topic_approved'] && $auth->acl_get('m_approve', $similar_forum_id)) ? true : false;
				$posts_unapproved = ($similar['topic_approved'] && $similar['topic_replies'] < $similar['topic_replies_real'] && $auth->acl_get('m_approve', $similar_forum_id)) ? true : false;
				$u_mcp_queue = ($topic_unapproved || $posts_unapproved) ? append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=queue&amp;mode=' . (($topic_unapproved) ? 'approve_details' : 'unapproved_posts') . "&amp;t=$similar_topic_id", true, $user->session_id) : '';

				$template->assign_block_vars('similar', array(
					'ATTACH_ICON_IMG'		=> ($similar['topic_attachment'] && $auth->acl_get('u_download')) ? $user->img('icon_topic_attach', $user->lang['TOTAL_ATTACHMENTS']) : '',
					'FIRST_POST_TIME'		=> $user->format_date($similar['topic_time']),
					'FORUM_TITLE'			=> $similar['forum_name'],
					'LAST_POST_AUTHOR_FULL'	=> get_username_string('full', $similar['topic_last_poster_id'], $similar['topic_last_poster_name'], $similar['topic_last_poster_colour']),
					'LAST_POST_TIME'		=> $user->format_date($similar['topic_last_post_time']),
					'PAGINATION'			=> topic_generate_pagination($similar['topic_replies'], append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=" . $similar_forum_id . '&amp;t=' . $similar_topic_id)),
					'S_TOPIC_REPORTED'		=> (!empty($similar['topic_reported']) && $auth->acl_get('m_report', $similar_forum_id)) ? true : false,
					'S_TOPIC_UNAPPROVED'	=> $topic_unapproved,
					'S_POSTS_UNAPPROVED'	=> $posts_unapproved,
					'S_UNREAD_TOPIC'		=> $unread_topic,
					'TOPIC_AUTHOR_FULL'		=> get_username_string('full', $similar['topic_poster'], $similar['topic_first_poster_name'], $similar['topic_first_poster_colour']),
					'TOPIC_FOLDER_IMG'		=> $user->img($folder_img, $folder_alt),
					'TOPIC_FOLDER_IMG_SRC'	=> $user->img($folder_img, $folder_alt, false, '', 'src'),
					'TOPIC_ICON_IMG'		=> (!empty($icons[$similar['icon_id']])) ? $icons[$similar['icon_id']]['img'] : '',
					'TOPIC_ICON_IMG_WIDTH'	=> (!empty($icons[$similar['icon_id']])) ? $icons[$similar['icon_id']]['width'] : '',
					'TOPIC_ICON_IMG_HEIGHT'	=> (!empty($icons[$similar['icon_id']])) ? $icons[$similar['icon_id']]['height'] : '',
					'TOPIC_REPLIES'			=> $similar['topic_replies'],
					'TOPIC_TITLE'			=> $similar['topic_title'],
					'TOPIC_VIEWS'			=> $similar['topic_views'],
					'U_LAST_POST'			=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=" . $similar_forum_id . '&amp;t=' . $similar_topic_id . '&amp;p=' . $similar['topic_last_post_id']) . '#p' . $similar['topic_last_post_id'],
					'U_MCP_QUEUE'			=> $u_mcp_queue,
					'U_MCP_REPORT'			=> append_sid("{$phpbb_root_path}mcp.$phpEx", 'i=reports&amp;mode=reports&amp;f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id, true, $user->session_id),
					'U_NEWEST_POST'			=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=" . $similar_forum_id . '&amp;t=' . $similar_topic_id . '&amp;view=unread') . '#unread',
					'U_VIEW_FORUM'			=> append_sid("{$phpbb_root_path}viewforum.$phpEx", "f=" . $similar_forum_id),
					'U_VIEW_TOPIC'			=> append_sid("{$phpbb_root_path}viewtopic.$phpEx", "f=" . $similar_forum_id . '&amp;t=' . $similar_topic_id),
					'UNAPPROVED_IMG'		=> ($topic_unapproved || $posts_unapproved) ? $user->img('icon_topic_unapproved', ($topic_unapproved) ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',
				));
			}
		}

		$db->sql_freeresult($result);

		$user->add_lang('mods/info_acp_similar_topics');

		$template->assign_vars(array(
			'L_SIMILAR_TOPICS'	=> $user->lang['PST_TITLE_ACP'],
			'LAST_POST_IMG'		=> $user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
			'NEWEST_POST_IMG'	=> $user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'REPORTED_IMG'		=> $user->img('icon_topic_reported', 'TOPIC_REPORTED'),
		));
	}
}

/**
* Remove problem characters from the topic title
* MySQL fulltext has built-in English stop words. Use phpBB's ignore words for non-English languages
* Also remove any admin-defined special ignore words
* 
* @param  string $text			The topic title
* @return string $text			The topic title cleaned and with any ignore words removed
*/
function clean_title($text)
{
	global $config, $user;

	$text = str_replace(array('&quot;', '&amp;'), '', $text); //strip quotes, ampersands

	$english_lang = ($user->lang_name == 'en' || $user->lang_name == 'en_us') ? true : false;
	$ignore_words = !empty($config['similar_topics_words']) ? true : false;

	if (!$english_lang || $ignore_words)
	{
		// strip out any non-alpha-numeric characters using PCRE regex syntax
		$text = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $text));

		// Put words in the title into an array, and remove uppercases and short words
		$word_list = array();
		if (!empty($text))
		{
			$word_list = explode(' ', utf8_strtolower($text));
			foreach ($word_list as $key => $word)
			{
				// Lets eliminate all words of 2 characters or less
				if (utf8_strlen(trim($word)) < 3)
				{
					unset($word_list[$key]);
				}
			}
		}

		// If non-English user language is detected, we must remove stop-words using phpBB's ignore words list
		if (!$english_lang && !empty($word_list))
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

		// Remove custom ignore words
		if ($ignore_words && !empty($word_list))
		{
			$words = explode(' ', utf8_strtolower($config['similar_topics_words']));
			$word_list = array_diff($word_list, $words);
		}

		// Rebuild our cleaned up topic title
		$text = !empty($word_list) ? implode(' ', $word_list) : '';
	}

	return $text;
}

