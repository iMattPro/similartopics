<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\core;

class similar_topics
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\cache\service */
	protected $cache;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\event\dispatcher_interface */
	protected $dispatcher;

	/** @var \phpbb\pagination */
	protected $pagination;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\content_visibility */
	protected $content_visibility;

	/** @var string phpBB root path  */
	protected $root_path;

	/** @var string PHP file extension */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\cache\service              $cache
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\event\dispatcher_interface $dispatcher
	 * @param \phpbb\pagination                 $pagination
	 * @param \phpbb\request\request            $request
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\user                       $user
	 * @param \phpbb\content_visibility         $content_visibility
	 * @param string                            $root_path
	 * @param string                            $php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $dispatcher, \phpbb\pagination $pagination, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, \phpbb\content_visibility $content_visibility, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->dispatcher = $dispatcher;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->content_visibility = $content_visibility;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	/**
	 * Is similar topics available?
	 *
	 * @access public
	 * @return bool True if available, false otherwise
	 */
	public function is_available()
	{
		return $this->is_enabled() && $this->is_viewable() && $this->is_mysql();
	}

	/**
	 * Is similar topics configured?
	 *
	 * @access public
	 * @return bool True if configured, false otherwise
	 */
	public function is_enabled()
	{
		return !empty($this->config['similar_topics']) && !empty($this->config['similar_topics_limit']);
	}

	/**
	 * Is similar topics viewable bu the user?
	 *
	 * @access public
	 * @return bool True if viewable, false otherwise
	 */
	public function is_viewable()
	{
		return !empty($this->user->data['user_similar_topics']) && $this->auth->acl_get('u_similar_topics');
	}

	/**
	 * Get similar topics by matching topic titles
	 *
	 * NOTE: Currently requires MySQL due to the use of FULLTEXT indexes
	 * and MATCH and AGAINST and UNIX_TIMESTAMP. MySQL FULLTEXT has built-in
	 * English ignore words. We use phpBB's ignore words for non-English
	 * languages. We also remove any admin-defined special ignore words.
	 *
	 * @access public
	 * @param array $topic_data Array with topic data
	 */
	public function display_similar_topics($topic_data)
	{
		// If the forum should not display similar topics, no need to continue
		if ($topic_data['similar_topics_hide'])
		{
			return;
		}

		$topic_title = $this->clean_topic_title($topic_data['topic_title']);

		// If the cleaned up topic_title is empty, no need to continue
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
				AND t.topic_id <> ' . (int) $topic_data['topic_id'],
			//'GROUP_BY'	=> 't.topic_id',
			//'ORDER_BY'	=> 'score DESC', // this is done automatically by MySQL when not using IN BOOLEAN MODE
		);

		// Add topic tracking data to the query (only if query caching is off)
		if ($this->user->data['is_registered'] && $this->config['load_db_lastread'] && !$this->config['similar_topics_cache'])
		{
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(TOPICS_TRACK_TABLE => 'tt'), 'ON' => 'tt.topic_id = t.topic_id AND tt.user_id = ' . $this->user->data['user_id']);
			$sql_array['LEFT_JOIN'][] = array('FROM' => array(FORUMS_TRACK_TABLE => 'ft'), 'ON' => 'ft.forum_id = f.forum_id AND ft.user_id = ' . $this->user->data['user_id']);
			$sql_array['SELECT'] .= ', tt.mark_time, ft.mark_time as f_mark_time';
		}
		else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
		{
			// Cookie based tracking copied from search.php
			$tracking_topics = $this->request->variable($this->config['cookie_name'] . '_track', '', true, \phpbb\request\request_interface::COOKIE);
			$tracking_topics = $tracking_topics ? tracking_unserialize($tracking_topics) : array();
		}

		// We need to exclude passworded forums so we do not leak the topic title
		$passworded_forums = $this->user->get_passworded_forums();

		// See if the admin set this forum to only search a specific group of other forums, and include them
		if (!empty($topic_data['similar_topic_forums']))
		{
			// Remove any passworded forums from this group of forums we will be searching
			$included_forums = array_diff(json_decode($topic_data['similar_topic_forums'], true), $passworded_forums);
			// if there's nothing left to display (user has no access to the forums we want to search)
			if (empty($included_forums))
			{
				return;
			}

			$sql_array['WHERE'] .= ' AND ' . $this->db->sql_in_set('f.forum_id', $included_forums);
		}
		// Otherwise exclude any ignored forums
		else
		{
			// Remove any passworded forums
			if (count($passworded_forums))
			{
				$sql_array['WHERE'] .= ' AND ' . $this->db->sql_in_set('f.forum_id', $passworded_forums, true);
			}

			$sql_array['WHERE'] .= ' AND f.similar_topics_ignore = 0';
		}

		/**
		 * Event to modify the sql_array for similar topics
		 *
		 * @event vse.similartopics.get_topic_data
		 * @var array sql_array SQL array to get similar topics data
		 * @since 1.3.0
		 */
		$vars = array('sql_array');
		extract($this->dispatcher->trigger_event('vse.similartopics.get_topic_data', compact($vars)));

		$rowset = array();

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, $this->config['similar_topics_limit'], 0, $this->config['similar_topics_cache']);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$rowset[(int) $row['topic_id']] = $row;
		}
		$this->db->sql_freeresult($result);

		// Grab icons
		$icons = $this->cache->obtain_icons();

		/**
		 * Modify the rowset data for similar topics
		 *
		 * @event vse.similartopics.modify_rowset
		 * @var	array rowset Array with the search results data
		 * @since 1.4.2
		 */
		$vars = array('rowset');
		extract($this->dispatcher->trigger_event('vse.similartopics.modify_rowset', compact($vars)));

		foreach ($rowset as $row)
		{
			$similar_forum_id = (int) $row['forum_id'];
			$similar_topic_id = (int) $row['topic_id'];

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
						$this->user->data['user_lastmark'] = isset($tracking_topics['l']) ? ((int) base_convert($tracking_topics['l'], 36, 10) + (int) $this->config['board_startdate']) : 0;
					}
				}

				// Replies
				$replies = $this->content_visibility->get_count('topic_posts', $row, $similar_forum_id) - 1;

				// Get folder img, topic status/type related information
				$folder_img = $folder_alt = $topic_type = '';
				$unread_topic = isset($topic_tracking_info[$similar_topic_id]) && $row['topic_last_post_time'] > $topic_tracking_info[$similar_topic_id];
				topic_status($row, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);

				$topic_unapproved = $row['topic_visibility'] == ITEM_UNAPPROVED && $this->auth->acl_get('m_approve', $similar_forum_id);
				$posts_unapproved = $row['topic_visibility'] == ITEM_APPROVED && $row['topic_posts_unapproved'] && $this->auth->acl_get('m_approve', $similar_forum_id);
				//$topic_deleted = $row['topic_visibility'] == ITEM_DELETED;
				$u_mcp_queue = ($topic_unapproved || $posts_unapproved) ? append_sid("{$this->root_path}mcp.{$this->php_ext}", 'i=queue&amp;mode=' . ($topic_unapproved ? 'approve_details' : 'unapproved_posts') . "&amp;t=$similar_topic_id", true, $this->user->session_id) : '';
				//$u_mcp_queue = (!$u_mcp_queue && $topic_deleted) ? append_sid("{$this->root_path}mcp.{$this->php_ext}", "i=queue&amp;mode=deleted_topics&amp;t=$similar_topic_id", true, $this->user->session_id) : $u_mcp_queue;

				$base_url = append_sid("{$this->root_path}viewtopic.{$this->php_ext}", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id);

				$topic_row = array(
					'TOPIC_AUTHOR_FULL'		=> get_username_string('full', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']),
					'FIRST_POST_TIME'		=> $this->user->format_date($row['topic_time']),
					'LAST_POST_TIME'		=> $this->user->format_date($row['topic_last_post_time']),
					'LAST_POST_AUTHOR_FULL'	=> get_username_string('full', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),

					'TOPIC_REPLIES'			=> $replies,
					'TOPIC_VIEWS'			=> $row['topic_views'],
					'TOPIC_TITLE'			=> censor_text($row['topic_title']),
					'FORUM_TITLE'			=> $row['forum_name'],

					'TOPIC_IMG_STYLE'		=> $folder_img,
					'TOPIC_FOLDER_IMG'		=> $this->user->img($folder_img, $folder_alt),
					'TOPIC_FOLDER_IMG_ALT'	=> $this->user->lang($folder_alt),

					'TOPIC_ICON_IMG'		=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['img'] : '',
					'TOPIC_ICON_IMG_WIDTH'	=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['width'] : '',
					'TOPIC_ICON_IMG_HEIGHT'	=> (!empty($icons[$row['icon_id']])) ? $icons[$row['icon_id']]['height'] : '',
					'ATTACH_ICON_IMG'		=> ($this->auth->acl_get('u_download') && $this->auth->acl_get('f_download', $similar_forum_id) && $row['topic_attachment']) ? $this->user->img('icon_topic_attach', $this->user->lang('TOTAL_ATTACHMENTS')) : '',
					'UNAPPROVED_IMG'		=> ($topic_unapproved || $posts_unapproved) ? $this->user->img('icon_topic_unapproved', $topic_unapproved ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',

					'S_UNREAD_TOPIC'		=> $unread_topic,
					'S_TOPIC_REPORTED'		=> !empty($row['topic_reported']) && $this->auth->acl_get('m_report', $similar_forum_id),
					'S_TOPIC_UNAPPROVED'	=> $topic_unapproved,
					'S_POSTS_UNAPPROVED'	=> $posts_unapproved,
					//'S_TOPIC_DELETED'		=> $topic_deleted,
					'S_HAS_POLL'			=> (bool) $row['poll_start'],

					'U_NEWEST_POST'			=> append_sid("{$this->root_path}viewtopic.{$this->php_ext}", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id . '&amp;view=unread') . '#unread',
					'U_LAST_POST'			=> append_sid("{$this->root_path}viewtopic.{$this->php_ext}", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id . '&amp;p=' . $row['topic_last_post_id']) . '#p' . $row['topic_last_post_id'],
					'U_VIEW_TOPIC'			=> append_sid("{$this->root_path}viewtopic.{$this->php_ext}", 'f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id),
					'U_VIEW_FORUM'			=> append_sid("{$this->root_path}viewforum.{$this->php_ext}", 'f=' . $similar_forum_id),
					'U_MCP_REPORT'			=> append_sid("{$this->root_path}mcp.{$this->php_ext}", 'i=reports&amp;mode=reports&amp;f=' . $similar_forum_id . '&amp;t=' . $similar_topic_id, true, $this->user->session_id),
					'U_MCP_QUEUE'			=> $u_mcp_queue,
				);

				/**
				 * Event to modify the similar topics template block
				 *
				 * @event vse.similartopics.modify_topicrow
				 * @var array row       Array with similar topic data
				 * @var array topic_row Template block array
				 * @since 1.3.0
				 */
				$vars = array('row', 'topic_row');
				extract($this->dispatcher->trigger_event('vse.similartopics.modify_topicrow', compact($vars)));

				$this->template->assign_block_vars('similar', $topic_row);

				$this->pagination->generate_template_pagination($base_url, 'similar.pagination', 'start', $replies + 1, $this->config['posts_per_page'], 1, true, true);
			}
		}

		$this->user->add_lang_ext('vse/similartopics', 'similar_topics');

		$this->template->assign_vars(array(
			'L_SIMILAR_TOPICS'	=> $this->user->lang('SIMILAR_TOPICS'),
			'NEWEST_POST_IMG'	=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'LAST_POST_IMG'		=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
			'REPORTED_IMG'		=> $this->user->img('icon_topic_reported', 'TOPIC_REPORTED'),
			//'DELETED_IMG'		=> $this->user->img('icon_topic_deleted', 'TOPIC_DELETED'),
			'POLL_IMG'			=> $this->user->img('icon_topic_poll', 'TOPIC_POLL'),
			'S_PST_BRANCH'		=> phpbb_version_compare(max($this->config['phpbb_version'], PHPBB_VERSION), '3.2.0-dev', '<') ? '31x' : '32x',
		));
	}

	/**
	 * Clean topic title (and if needed, ignore-words)
	 *
	 * @access public
	 * @param string $text The topic title
	 * @return string The topic title
	 */
	public function clean_topic_title($text)
	{
		// Strip quotes, ampersands
		$text = str_replace(array('&quot;', '&amp;'), '', $text);

		if (!$this->english_lang() || $this->has_ignore_words())
		{
			$text = $this->strip_stop_words($text);
		}

		return $text;
	}

	/**
	 * Remove any non-english and/or custom defined ignore-words
	 *
	 * @access protected
	 * @param string $text The topic title
	 * @return string The topic title
	 */
	protected function strip_stop_words($text)
	{
		$words = array();

		// If non-English, look for a list of stop-words to be ignored
		// in either the core or the extension (deprecated from core)
		if (!$this->english_lang())
		{
			if (file_exists($search_ignore_words = "{$this->user->lang_path}{$this->user->lang_name}/search_ignore_words.{$this->php_ext}") ||
				file_exists($search_ignore_words = "{$this->root_path}ext/vse/similartopics/language/{$this->user->lang_name}/search_ignore_words.{$this->php_ext}"))
			{
				include($search_ignore_words);
			}
		}

		if ($this->has_ignore_words())
		{
			// Merge any custom defined ignore words from the ACP to the stop-words array
			$words = array_merge($this->make_word_array($this->config['similar_topics_words']), $words);
		}

		// Remove stop-words from the topic title text
		$words = array_diff($this->make_word_array($text), $words);

		// Convert our words array back to a string
		return implode(' ', $words);
	}

	/**
	 * Helper function to split string into an array of words
	 *
	 * @access protected
	 * @param string $text String of plain text words
	 * @return array Array of plaintext words
	 */
	protected function make_word_array($text)
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

	/**
	 * Check if English is the current user's language
	 *
	 * @access protected
	 * @return bool True if lang is 'en' or 'en_us', false otherwise
	 */
	protected function english_lang()
	{
		return ($this->user->lang_name === 'en' || $this->user->lang_name === 'en_us');
	}

	/**
	 * Check if custom ignore words have been defined for similar topics
	 *
	 * @access protected
	 * @return bool True or false
	 */
	protected function has_ignore_words()
	{
		return !empty($this->config['similar_topics_words']);
	}

	/**
	 * Check if the database layer is MySQL4 or later
	 *
	 * @access protected
	 * @return bool True is MySQL4 or later, false otherwise
	 */
	protected function is_mysql()
	{
		return ($this->db->get_sql_layer() === 'mysql4' || $this->db->get_sql_layer() === 'mysqli');
	}
}
