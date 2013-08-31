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

class phpbb_ext_vse_similartopics_core_similar_topics
{
	/** @var phpbb_auth */
	protected $auth;

	/** @var phpbb_cache_service */
	protected $cache;

	/** @var phpbb_config */
	protected $config;

	/** @var phpbb_db_driver */
	protected $db;

	/** @var phpbb_template */
	protected $template;

	/** @var phpbb_user */
	protected $user;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;
	
	/**
	* Similar topics constructor method
	* 
	* @param phpbb_auth $auth
	* @param phpbb_cache_service $cache
	* @param phpbb_config $config
	* @param phpbb_db_driver $db
	* @param phpbb_template $template
	* @param phpbb_user $user
	* @param string $root_path
	* @param string $php_ext
	*/
	public function __construct(phpbb_auth $auth, phpbb_cache_service $cache, phpbb_config $config, phpbb_db_driver $db, phpbb_template $template, phpbb_user $user, phpbb_content_visibility $content_visibility, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->content_visibility = $content_visibility;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	* Get similar topics by matching topic titles
	*
	* NOTE: Currently requires MySQL due to the use of FULLTEXT indexes
	* and MATCH and AGAINST and UNIX_TIMESTAMP. MySQL FULLTEXT has built-in
	* English ignore words. Use phpBB's ignore words for non-English
	* languages. We also remove any admin-defined special ignore words.
	*
	* @param Event $event Event object
	* @return null
	*/
	public function get_similar_topics($event)
	{
		global $phpbb_dispatcher;

		// Potential reasons to stop execution
		if (!$this->config['similar_topics_limit'] || (($this->db->sql_layer != 'mysql4') && ($this->db->sql_layer != 'mysqli')) || (in_array($event['forum_id'], explode(',', $this->config['similar_topics_hide']))))
		{
			return;
		}

		$topic_title = $this->strip_topic_title($event['topic_data']['topic_title']);

		// If the stripped down topic_title is empty, no need to continue
		if (empty($topic_title))
		{
			return;
		}

		// Similar Topics query
		$sql_array = array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*, 
				MATCH (t.topic_title) AGAINST ('" . $this->db->sql_escape($topic_title) . "') AS score",

			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),

			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),

			'WHERE'		=> "MATCH (t.topic_title) AGAINST ('" . $this->db->sql_escape($topic_title) . "') >= 0.5
				AND t.topic_status <> " . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . $this->config['similar_topics_time'] . ')
				AND t.topic_id <> ' . (int) $event['topic_data']['topic_id'],

		//	'GROUP_BY'	=> 't.topic_id',

		//	'ORDER_BY'	=> 'score DESC', // this is done automatically by MySQL when not using IN BOOLEAN MODE
		);

		// Add topic tracking data to the query (only when query caching is off)
		if ($this->user->data['is_registered'] && $this->config['load_db_lastread'] && !$this->config['similar_topics_cache'])
		{
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(TOPICS_TRACK_TABLE => 'tt'), 'ON' => 'tt.topic_id = t.topic_id AND tt.user_id = ' . $this->user->data['user_id']);
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(FORUMS_TRACK_TABLE => 'ft'), 'ON' => 'ft.forum_id = f.forum_id AND ft.user_id = ' . $this->user->data['user_id']);
			$sql_array['SELECT'] .= ', tt.mark_time, ft.mark_time as f_mark_time';
		}
		else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
		{
			// Cookie based tracking copied from search.php
			$tracking_topics = (isset($_COOKIE[$this->config['cookie_name'] . '_track'])) ? ((STRIP) ? stripslashes($_COOKIE[$this->config['cookie_name'] . '_track']) : $_COOKIE[$this->config['cookie_name'] . '_track']) : '';
			$tracking_topics = ($tracking_topics) ? tracking_unserialize($tracking_topics) : array();
		}

		// Now lets see if the current forum is set to search only in specified forums
		if (!empty($event['topic_data']['similar_topic_forums']))
		{
			$sql_array['WHERE'] .= ' AND ' . $this->db->sql_in_set('f.forum_id', explode(',', $event['topic_data']['similar_topic_forums']));
		}
		// Otherwise, lets see what forums are not allowed to be searched, and ignore those
		else if (!empty($this->config['similar_topics_ignore']))
		{
			$sql_array['WHERE'] .= ' AND ' . $this->db->sql_in_set('f.forum_id', explode(',', $this->config['similar_topics_ignore']), true);
		}

		$vars = array('sql_array');
		extract($phpbb_dispatcher->trigger_event('similartopics.similar_topic_data', compact($vars)));

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $this->config['similar_topics_limit'], 0, $this->config['similar_topics_cache']);

		// Grab icons
		$icons = $this->cache->obtain_icons();

		$rowset = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$similar_forum_id = (int) $row['forum_id'];
			$similar_topic_id = (int) $row['topic_id'];
			$rowset[$similar_topic_id] = $row;

			if ($this->auth->acl_get('f_read', $similar_forum_id))
			{
				// Get topic tracking info
				if ($this->user->data['is_registered'] && $this->config['load_db_lastread'] && !$this->config['similar_topics_cache'])
				{
					$topic_tracking_info = get_topic_tracking($similar_forum_id, $similar_topic_id, $rowset, array($similar_forum_id => $row['f_mark_time']));
				}
				else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
				{
					$topic_tracking_info = get_complete_topic_tracking($similar_forum_id, $similar_topic_id);

					if (!$this->user->data['is_registered'])
					{
						$this->user->data['user_lastmark'] = (isset($tracking_topics['l'])) ? (int) (base_convert($tracking_topics['l'], 36, 10) + $this->config['board_startdate']) : 0;
					}
				}

				// Replies
				$replies = $this->content_visibility->get_count('topic_posts', $row, $similar_forum_id) - 1;

				// Get folder img, topic status/type related information
				$folder_img = $folder_alt = $topic_type = '';
				$unread_topic = (isset($topic_tracking_info[$similar_topic_id]) && $row['topic_last_post_time'] > $topic_tracking_info[$similar_topic_id]) ? true : false;
				topic_status($row, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);

				$topic_unapproved = ($row['topic_visibility'] == ITEM_UNAPPROVED && $this->auth->acl_get('m_approve', $similar_forum_id)) ? true : false;
				$posts_unapproved = ($row['topic_visibility'] == ITEM_APPROVED && $row['topic_posts_unapproved'] && $this->auth->acl_get('m_approve', $similar_forum_id)) ? true : false;
				//$topic_deleted = $row['topic_visibility'] == ITEM_DELETED;
				$u_mcp_queue = ($topic_unapproved || $posts_unapproved) ? append_sid("{$this->root_path}mcp.$this->php_ext", 'i=queue&amp;mode=' . (($topic_unapproved) ? 'approve_details' : 'unapproved_posts') . "&amp;t=$similar_topic_id", true, $this->user->session_id) : '';
				//$u_mcp_queue = (!$u_mcp_queue && $topic_deleted) ? append_sid("{$this->root_path}mcp.$this->php_ext", "i=queue&amp;mode=deleted_topics&amp;t=$similar_topic_id", true, $this->user->session_id) : '';

				$base_url = append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id);

				$topic_row = array(
					'TOPIC_AUTHOR_FULL'		=> get_username_string('full', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']),
					'FIRST_POST_TIME'		=> $this->user->format_date($row['topic_time']),
					'LAST_POST_TIME'		=> $this->user->format_date($row['topic_last_post_time']),
					'LAST_POST_AUTHOR_FULL'	=> get_username_string('full', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),

					'PAGE_NUMBER'			=> phpbb_on_page($this->template, $this->user, $base_url, $replies + 1, $this->config['posts_per_page'], 1), 
					'TOPIC_REPLIES'			=> $replies,
					'TOPIC_VIEWS'			=> $row['topic_views'],
					'TOPIC_TITLE'			=> $row['topic_title'],
					'FORUM_TITLE'			=> $row['forum_name'],

					'TOPIC_IMG_STYLE'		=> $folder_img,
					'TOPIC_FOLDER_IMG'		=> $this->user->img($folder_img, $folder_alt),
					'TOPIC_FOLDER_IMG_ALT'	=> $this->user->lang[$folder_alt],

					'TOPIC_ICON_IMG'		=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['img'] : '',
					'TOPIC_ICON_IMG_WIDTH'	=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['width'] : '',
					'TOPIC_ICON_IMG_HEIGHT'	=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['height'] : '',
					'ATTACH_ICON_IMG'		=> ($this->auth->acl_get('u_download') && $this->auth->acl_get('f_download', $similar_forum_id) && $row['topic_attachment']) ? $this->user->img('icon_topic_attach', $this->user->lang['TOTAL_ATTACHMENTS']) : '',
					'UNAPPROVED_IMG'		=> ($topic_unapproved || $posts_unapproved) ? $this->user->img('icon_topic_unapproved', ($topic_unapproved) ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',

					'S_UNREAD_TOPIC'		=> $unread_topic,
					'S_TOPIC_REPORTED'		=> (!empty($row['topic_reported']) && $this->auth->acl_get('m_report', $similar_forum_id)) ? true : false,
					'S_TOPIC_UNAPPROVED'	=> $topic_unapproved,
					'S_POSTS_UNAPPROVED'	=> $posts_unapproved,
					//'S_TOPIC_DELETED'		=> $topic_deleted,

					'U_NEWEST_POST'			=> append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id . '&amp;view=unread') . '#unread',
					'U_LAST_POST'			=> append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id . '&amp;p=' . $row['topic_last_post_id']) . '#p' . $row['topic_last_post_id'],
					'U_VIEW_TOPIC'			=> append_sid("{$this->root_path}viewtopic.$this->php_ext", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id),
					'U_VIEW_FORUM'			=> append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $similar_forum_id),
					'U_MCP_REPORT'			=> append_sid("{$this->root_path}mcp.$this->php_ext", 'i=reports&amp;mode=reports&amp;f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id, true, $this->user->session_id),
					'U_MCP_QUEUE'			=> $u_mcp_queue,
				);

				$vars = array('row', 'topic_row');
				extract($phpbb_dispatcher->trigger_event('similartopics.modify_topicrow', compact($vars)));

				$this->template->assign_block_vars('similar', $topic_row);

				phpbb_generate_template_pagination($this->template, $base_url, 'similar.pagination', 'start', $replies + 1, $this->config['posts_per_page'], 1, true, true);
			}
		}

		$this->db->sql_freeresult($result);

		$this->user->add_lang_ext('vse/similartopics', 'similar_topics');

		$this->template->assign_vars(array(
			'L_SIMILAR_TOPICS'	=> $this->user->lang['SIMILAR_TOPICS'],
			'NEWEST_POST_IMG'	=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'LAST_POST_IMG'		=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
			'REPORTED_IMG'		=> $this->user->img('icon_topic_reported', 'TOPIC_REPORTED'),
			//'DELETED_IMG'		=> $this->user->img('icon_topic_deleted', 'TOPIC_DELETED'),
		));
	}

	/**
	* Remove problem characters (and if needed, ignore-words) from topic title
	*
	* @param	string	$text 	The topic title
	* @return	string	The topic title
	* @access	private
	*/
	private function strip_topic_title($text)
	{
		// Strip quotes, ampersands
		$text = str_replace(array('&quot;', '&amp;'), '', $text);

		$english_lang = ($this->user->lang_name == 'en' || $this->user->lang_name == 'en_us') ? true : false;
		$ignore_words = !empty($this->config['similar_topics_words']) ? true : false;

		if (!$english_lang || $ignore_words)
		{
			$text = $this->strip_stop_words($text, $english_lang, $ignore_words);
		}

		return $text;
	}

	/**
	* Remove any non-english and/or custom defined ignore-words
	*
	* @param	string	$text 			The topic title
	* @param	bool	$english_lang 	False means use phpBB's ignore words 
	* @param	bool	$ignore_words 	True means strip custom ignore words
	* @return	string	The topic title
	* @access	private
	*/
	private function strip_stop_words($text, $english_lang, $ignore_words)
	{
		$words = array();

		if (!$english_lang && file_exists("{$this->user->lang_path}{$this->user->lang_name}/search_ignore_words.$this->php_ext"))
		{
			// Retrieve a language dependent list of words to be ignored (method copied from search.php)
			include("{$this->user->lang_path}{$this->user->lang_name}/search_ignore_words.$this->php_ext");
		}

		if ($ignore_words)
		{
			// Merge any custom defined ignore words from the ACP to the stop-words array
			$words = array_merge($this->make_word_array($this->config['similar_topics_words']), $words);
		}

		// Remove stop-words from the topic title text
		$words = array_diff($this->make_word_array($text), $words);

		// Convert our words array back to a string
		$text = !empty($words) ? implode(' ', $words) : '';

		return $text;
	}

	/**
	* Helper function to split string into an array of words
	*
	* @param	string	$text 	String of plain text words
	* @return	array	array of plaintext words
	* @access	private
	*/
	private function make_word_array($text)
	{
		// Strip out any non-alpha-numeric characters using PCRE regex syntax
		$text = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $text));

		$words = explode(' ', utf8_strtolower($text));
		foreach ($words as $key => $word)
		{
			// Strip words of 2 characters or less
			if (utf8_strlen(trim($word)) < 3)
			{
				unset($words[$key]);
			}
		}

		return $words;
	}
}
