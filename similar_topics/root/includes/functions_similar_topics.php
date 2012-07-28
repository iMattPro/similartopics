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
* Find similar topics based on matching topic titles. Currently requires MySQL 
* due to the use of FULLTEXT indexes and MATCH and AGAINST and UNIX_TIMESTAMP.
* MySQL FULLTEXT has built-in English ignore words. We'll use phpBB's ignore words
* for non-English languages. Also removes any admin-defined special ignore words.
*
* @package Precise Similar Topics II
*/
class similar_topics
{
	/**
	* Is the MOD enabled?
	*/
	var $is_active		= false;

	/**
	* The maximum number of similar topics to display
	*/
	var $topic_limit	= 5;

	/**
	* The maximum age of similar topics to display
	*/
	var $topic_age		= 365;

	/**
	* Cache SQL queries for similar topics
	*/
	var $cache_time		= 0;

	/**
	* String of custom words defined in the ACP to be ignored in similar topic searches
	*/
	var $ignore_words	= '';

	/**
	* String of forum IDs that are not to be searched for similar topics
	*/
	var $ignore_forums	= '';

	/**
	* Is the current forum allowed to display similar topics?
	*/
	var $allowed_forum	= true;

	/**
	* Is the board using a MySQL database?
	*/
	var $mysql_db		= true;

	/**
	* Similar Topics MOD constructor
	* @access public
	*/
	function similar_topics()
	{
		global $config, $db, $forum_id;

		$this->is_active		= (bool) $config['similar_topics'];
		$this->topic_limit		= (int) $config['similar_topics_limit'];
		$this->topic_age		= (int) $config['similar_topics_time'];
		$this->cache_time		= (int) $config['similar_topics_cache'];
		$this->ignore_words		= (string) $config['similar_topics_words'];
		$this->ignore_forums	= (string) $config['similar_topics_ignore'];
		$this->allowed_forum	= (!in_array($forum_id, explode(',', $config['similar_topics_hide']))) ? true : false;
		$this->mysql_db			= (($db->sql_layer == 'mysql4') || ($db->sql_layer == 'mysqli')) ? true : false;

		// All reasons to bail out of the MOD
		if (!$this->is_active || !$this->mysql_db || !$this->topic_limit || !$this->allowed_forum)
		{
			return;
		}

		$this->_get_similar_topics();
	}

	/**
	* Get similar topics by matching topic titles
	* @access private
	*/
	function _get_similar_topics()
	{
		global $auth, $cache, $config, $user, $db, $topic_data, $template, $phpbb_root_path, $phpEx;

		$topic_title = $this->_strip_topic_title($topic_data['topic_title']);

		// If the topic_title winds up being empty, no need to continue
		if (empty($topic_title))
		{
			return;
		}

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
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . $this->topic_age . ')
				AND t.topic_id <> ' . (int) $topic_data['topic_id'],

//			'GROUP_BY'	=> 't.topic_id',
//			'ORDER_BY'	=> 'score DESC', // this is done automatically by MySQL when not using IN BOOLEAN MODE
		);

		// Add topic tracking data to query (only when query caching is 0)
		if ($user->data['is_registered'] && $config['load_db_lastread'] && !$this->cache_time)
		{
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(TOPICS_TRACK_TABLE => 'tt'), 'ON' => 'tt.topic_id = t.topic_id AND tt.user_id = ' . $user->data['user_id']);
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(FORUMS_TRACK_TABLE => 'ft'), 'ON' => 'ft.forum_id = f.forum_id AND ft.user_id = ' . $user->data['user_id']);
			$sql_array['SELECT'] .= ', tt.mark_time, ft.mark_time as f_mark_time';
		}

		// Now lets see if the current forum is set to search a specific forum search group, and search only those forums
		if (!empty($topic_data['similar_topic_forums']))
		{
			$sql_array['WHERE'] .= ' AND ' . $db->sql_in_set('f.forum_id', explode(',', $topic_data['similar_topic_forums']));
		}
		// Otherwise, lets see what forums are not allowed to be searched, and ignore those
		else if (!empty($this->ignore_forums))
		{
			$sql_array['WHERE'] .= ' AND ' . $db->sql_in_set('f.forum_id', explode(',', $this->ignore_forums), true);
		}

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$result = $db->sql_query_limit($sql, $this->topic_limit, 0, $this->cache_time);

		// Grab icons
		$icons = $cache->obtain_icons();

		$rowset = array();

		while ($similar = $db->sql_fetchrow($result))
		{
			$similar_forum_id = (int) $similar['forum_id'];
			$similar_topic_id = (int) $similar['topic_id'];
			$rowset[$similar_topic_id] = $similar;

			if ($auth->acl_get('f_read', $similar_forum_id))
			{
				// Get topic tracking info
				if ($user->data['is_registered'] && $config['load_db_lastread'] && !$this->cache_time)
				{
					$topic_tracking_info = get_topic_tracking($similar_forum_id, $similar_topic_id, $rowset, array($similar_forum_id => $similar['f_mark_time']));
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

	/**
	* Remove problem characters (and if needed, any ignore-words) from the topic title
	* @access private
	*/
	function _strip_topic_title($text)
	{
		global $user;

		// strip quotes, ampersands
		$text = str_replace(array('&quot;', '&amp;'), '', $text);

		$english_lang = ($user->lang_name == 'en' || $user->lang_name == 'en_us') ? true : false;
		$ignore_words = !empty($this->ignore_words) ? true : false;

		if (!$english_lang || $ignore_words)
		{
			$text = $this->_strip_stop_words($text, $english_lang, $ignore_words);
		}

		return $text;
	}

	/**
	* Remove any non-english and/or custom defined ignore-words
	* @access private
	*/
	function _strip_stop_words($text, $english_lang, $ignore_words)
	{
		global $user, $phpEx;

		$words = array();

		if (!$english_lang && file_exists("{$user->lang_path}{$user->lang_name}/search_ignore_words.$phpEx"))
		{
			// Retrieve a language dependent list of words to be ignored (method copied from search.php)
			include("{$user->lang_path}{$user->lang_name}/search_ignore_words.$phpEx");
		}

		if ($ignore_words)
		{
			// Merge any custom defined ignore words from the ACP to the stop-words array
			$words = array_merge($this->_make_word_array($this->ignore_words), $words);
		}

		// Remove stop-words from the topic title text
		$words = array_diff($this->_make_word_array($text), $words);

		// Convert our words array back to a string
		$text = !empty($words) ? implode(' ', $words) : '';

		return $text;
	}

	/**
	* Split string into an array of words
	* @access private
	*/
	function _make_word_array($text)
	{
		// strip out any non-alpha-numeric characters using PCRE regex syntax
		$text = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $text));

		$words = explode(' ', utf8_strtolower($text));
		foreach ($words as $key => $word)
		{
			// strip words of 2 characters or less
			if (utf8_strlen(trim($word)) < 3)
			{
				unset($words[$key]);
			}
		}

		return $words;
	}
}
