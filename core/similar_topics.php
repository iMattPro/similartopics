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

use phpbb\auth\auth;
use phpbb\cache\service as cache;
use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\content_visibility;
use phpbb\db\driver\driver_interface as db;
use phpbb\event\dispatcher_interface as dispatcher;
use phpbb\language\language;
use phpbb\pagination;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use vse\similartopics\driver\driver_interface as similartopics_driver;
use vse\similartopics\driver\manager as similartopics_manager;

class similar_topics
{
	/** @var auth */
	protected $auth;

	/** @var cache */
	protected $cache;

	/** @var config */
	protected $config;

	/** @var db_text */
	protected $config_text;

	/** @var db */
	protected $db;

	/** @var dispatcher */
	protected $dispatcher;

	/** @var language */
	protected $language;

	/** @var pagination */
	protected $pagination;

	/** @var request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var content_visibility */
	protected $content_visibility;

	/** @var stop_word_helper */
	protected $helper;

	/** @var similartopics_driver */
	protected $similartopics;

	/** @var string phpBB root path  */
	protected $root_path;

	/** @var string PHP file extension */
	protected $php_ext;

	/** @var string String of custom ignore words */
	protected $ignore_words;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param auth                  $auth
	 * @param cache                 $cache
	 * @param config                 $config
	 * @param db_text               $config_text
	 * @param db                    $db
	 * @param dispatcher            $dispatcher
	 * @param language              $language
	 * @param pagination            $pagination
	 * @param request               $request
	 * @param template              $template
	 * @param user                  $user
	 * @param content_visibility    $content_visibility
	 * @param stop_word_helper      $stop_word_helper
	 * @param similartopics_manager $similartopics_manager
	 * @param string                $root_path
	 * @param string                $php_ext
	 */
	public function __construct(auth $auth, cache $cache, config $config, db_text $config_text, db $db, dispatcher $dispatcher, language $language, pagination $pagination, request $request, template $template, user $user, content_visibility $content_visibility, stop_word_helper $stop_word_helper, similartopics_manager $similartopics_manager, $root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->db = $db;
		$this->dispatcher = $dispatcher;
		$this->helper = $stop_word_helper;
		$this->language = $language;
		$this->pagination = $pagination;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->content_visibility = $content_visibility;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

		$this->similartopics = $similartopics_manager->get_driver($db->get_sql_layer());
	}

	/**
	 * Is similar topics available?
	 *
	 * @access public
	 * @return bool True if available, false otherwise
	 */
	public function is_available()
	{
		return $this->is_enabled() && $this->is_viewable() && $this->similartopics !== null;
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
	 * Is similar topics viewable by the user?
	 *
	 * @access public
	 * @return bool True if viewable, false otherwise
	 */
	public function is_viewable()
	{
		return !empty($this->user->data['user_similar_topics']) && $this->auth->acl_get('u_similar_topics');
	}

	/**
	 * Is dynamic similar topics enabled?
	 *
	 * @access public
	 * @return bool True if enabled, false otherwise
	 */
	public function is_dynamic_enabled()
	{
		return !empty($this->config['similar_topics_dynamic']);
	}

	/**
	 * Get similar topics by matching topic titles
	 * Loosely based on viewforum.php lines 840-1040
	 *
	 * NOTE: FULLTEXT has built-in English ignore words. We use phpBB's
	 * ignore words for non-English languages. We also remove any
	 * admin-defined special ignore words.
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

		$this->helper->set_use_localized($this->get_localized_ignore_words());
		$this->helper->set_additional_ignore_words($this->get_additional_ignore_words());
		$topic_title = $this->helper->clean_text($topic_data['topic_title']);

		// If the cleaned up topic_title is empty, no need to continue
		if (empty($topic_title))
		{
			return;
		}

		// Get stored sensitivity value and divide by 10. In query, it should be a number between 0.0 to 1.0.
		$sensitivity = $this->config->offsetExists('similar_topics_sense') ? number_format($this->config['similar_topics_sense'] / 10, 1, '.', '') : '0.5';

		// Similar Topics SQL query is generated in similar topics driver
		$sql_array = $this->similartopics->get_query($topic_data['topic_id'], $topic_title, $this->config['similar_topics_time'], $sensitivity);

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

		// We need to exclude passworded forums, so we do not leak the topic title
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
		// Otherwise, exclude any ignored forums
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

				$view_topic_url_params = 't=' . $similar_topic_id;

				$topic_unapproved = $row['topic_visibility'] == ITEM_UNAPPROVED && $this->auth->acl_get('m_approve', $similar_forum_id);
				$posts_unapproved = $row['topic_visibility'] == ITEM_APPROVED && $row['topic_posts_unapproved'] && $this->auth->acl_get('m_approve', $similar_forum_id);
				$u_mcp_queue = ($topic_unapproved || $posts_unapproved) ? append_sid("{$this->root_path}mcp.$this->php_ext", 'i=queue&amp;mode=' . ($topic_unapproved ? 'approve_details' : 'unapproved_posts') . "&amp;t=$similar_topic_id", true, $this->user->session_id) : '';

				$base_url = append_sid("{$this->root_path}viewtopic.$this->php_ext", $view_topic_url_params);

				$topic_row = array(
					'TOPIC_AUTHOR_FULL'			=> get_username_string('full', $row['topic_poster'], $row['topic_first_poster_name'], $row['topic_first_poster_colour']),
					'FIRST_POST_TIME'			=> $this->user->format_date($row['topic_time']),
					'FIRST_POST_TIME_RFC3339'	=> gmdate(DATE_RFC3339, $row['topic_time']),
					'LAST_POST_TIME'			=> $this->user->format_date($row['topic_last_post_time']),
					'LAST_POST_TIME_RFC3339'	=> gmdate(DATE_RFC3339, $row['topic_last_post_time']),
					'LAST_POST_AUTHOR_FULL'		=> get_username_string('full', $row['topic_last_poster_id'], $row['topic_last_poster_name'], $row['topic_last_poster_colour']),

					'TOPIC_REPLIES'			=> $replies,
					'TOPIC_VIEWS'			=> $row['topic_views'],
					'TOPIC_TITLE'			=> censor_text($row['topic_title']),
					'FORUM_TITLE'			=> $row['forum_name'],

					'TOPIC_IMG_STYLE'		=> $folder_img,
					'TOPIC_FOLDER_IMG'		=> $this->user->img($folder_img, $folder_alt),
					'TOPIC_FOLDER_IMG_ALT'	=> $this->language->lang($folder_alt),

					'TOPIC_ICON_IMG'		=> !empty($icons[$row['icon_id']]) ? $icons[$row['icon_id']]['img'] : '',
					'TOPIC_ICON_IMG_WIDTH'	=> !empty($icons[$row['icon_id']]) ? $icons[$row['icon_id']]['width'] : '',
					'TOPIC_ICON_IMG_HEIGHT'	=> !empty($icons[$row['icon_id']]) ? $icons[$row['icon_id']]['height'] : '',
					'ATTACH_ICON_IMG'		=> ($this->auth->acl_get('u_download') && $this->auth->acl_get('f_download', $similar_forum_id) && $row['topic_attachment']) ? $this->user->img('icon_topic_attach', $this->language->lang('TOTAL_ATTACHMENTS')) : '',
					'UNAPPROVED_IMG'		=> ($topic_unapproved || $posts_unapproved) ? $this->user->img('icon_topic_unapproved', $topic_unapproved ? 'TOPIC_UNAPPROVED' : 'POSTS_UNAPPROVED') : '',

					'S_UNREAD_TOPIC'		=> $unread_topic,
					'S_TOPIC_REPORTED'		=> !empty($row['topic_reported']) && $this->auth->acl_get('m_report', $similar_forum_id),
					'S_TOPIC_UNAPPROVED'	=> $topic_unapproved,
					'S_POSTS_UNAPPROVED'	=> $posts_unapproved,
					'S_HAS_POLL'			=> (bool) $row['poll_start'],
					'S_POST_ANNOUNCE'		=> $row['topic_type'] == POST_ANNOUNCE,
					'S_POST_GLOBAL'			=> $row['topic_type'] == POST_GLOBAL,
					'S_POST_STICKY'			=> $row['topic_type'] == POST_STICKY,
					'S_TOPIC_LOCKED'		=> $row['topic_status'] == ITEM_LOCKED,
					'S_TOPIC_MOVED'			=> $row['topic_status'] == ITEM_MOVED,
					'S_TOPIC_HOT'			=> $this->config['hot_threshold'] && ($replies + 1) >= $this->config['hot_threshold'] && $row['topic_status'] != ITEM_LOCKED,

					'U_NEWEST_POST'			=> append_sid("{$this->root_path}viewtopic.$this->php_ext", $view_topic_url_params . '&amp;view=unread') . '#unread',
					'U_LAST_POST'			=> append_sid("{$this->root_path}viewtopic.$this->php_ext", $view_topic_url_params . '&amp;p=' . $row['topic_last_post_id']) . '#p' . $row['topic_last_post_id'],
					'U_VIEW_TOPIC'			=> $base_url,
					'U_VIEW_FORUM'			=> append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $similar_forum_id),
					'U_MCP_REPORT'			=> append_sid("{$this->root_path}mcp.$this->php_ext", 'i=reports&amp;mode=reports&amp;' . $view_topic_url_params, true, $this->user->session_id),
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

		$this->add_language();

		$this->template->assign_vars(array(
			'NEWEST_POST_IMG'	=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'LAST_POST_IMG'		=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
			'REPORTED_IMG'		=> $this->user->img('icon_topic_reported', 'TOPIC_REPORTED'),
			'POLL_IMG'			=> $this->user->img('icon_topic_poll', 'TOPIC_POLL'),
		));
	}

	/**
	 * Add lang files for similar topics
	 *
	 * @return void
	 */
	public function add_language()
	{
		$this->language->add_lang('similar_topics', 'vse/similartopics');
	}

	/**
	 * Check if we should load localized ignore words
	 *
	 * @access protected
	 * @return bool True if non-English language or using a dbms with no stop-words
	 */
	protected function get_localized_ignore_words()
	{
		return !in_array($this->user->lang_name, ['en', 'en_us'], true) || !$this->similartopics->has_stopword_support();
	}

	/**
	 * Search for similar topics via AJAX for dynamic suggestions
	 *
	 * @param string $query The search query
	 * @param int $forum_id The forum ID to search from
	 * @return array Array of similar topics
	 */
	public function search_similar_topics_ajax($query, $forum_id = 0)
	{
		$this->helper->set_use_localized($this->get_localized_ignore_words());
		$this->helper->set_additional_ignore_words($this->get_additional_ignore_words());
		$cleaned_query = $this->helper->clean_text($query);

		if (empty($cleaned_query))
		{
			return [];
		}

		$sensitivity = $this->config->offsetExists('similar_topics_sense') ? number_format($this->config['similar_topics_sense'] / 10, 1, '.', '') : '0.5';
		$sql_array = $this->similartopics->get_query(0, $cleaned_query, $this->config['similar_topics_time'], $sensitivity);

		$similar_topic_forums = null;
		if ($forum_id > 0)
		{
			$sql = 'SELECT similar_topic_forums
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . (int) $forum_id;
			$result = $this->db->sql_query($sql, 3600);
			$similar_topic_forums = $this->db->sql_fetchfield('similar_topic_forums');
			$this->db->sql_freeresult($result);
		}

		$passworded_forums = $this->user->get_passworded_forums();

		if (!empty($similar_topic_forums))
		{
			$included_forums = array_diff(json_decode($similar_topic_forums, true), $passworded_forums);
			if (empty($included_forums))
			{
				return [];
			}
			$sql_array['WHERE'] .= ' AND ' . $this->db->sql_in_set('f.forum_id', $included_forums);
		}
		else
		{
			if (count($passworded_forums))
			{
				$sql_array['WHERE'] .= ' AND ' . $this->db->sql_in_set('f.forum_id', $passworded_forums, true);
			}
			$sql_array['WHERE'] .= ' AND f.similar_topics_ignore = 0';
		}

		$topics = [];
		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, 5);

		while ($row = $this->db->sql_fetchrow($result))
		{
			if ($this->auth->acl_get('f_read', (int) $row['forum_id']))
			{
				$topics[] = [
					'id' => (int) $row['topic_id'],
					'title' => censor_text($row['topic_title']),
					'url' => append_sid("{$this->root_path}viewtopic.$this->php_ext", 't=' . $row['topic_id'])
				];
			}
		}
		$this->db->sql_freeresult($result);

		return $topics;
	}

	/**
	 * Get custom ignore words if any were defined for similar topics
	 *
	 * @access protected
	 * @return string|null String of ignore words or null if there are none defined
	 */
	protected function get_additional_ignore_words()
	{
		$key = 'similar_topics_words';

		$cache = $this->cache->get_driver();

		if ($this->ignore_words === null && (($this->ignore_words = $cache->get("_$key")) === false))
		{
			$this->ignore_words = $this->config_text->get($key);

			$cache->put("_$key", $this->ignore_words);
		}

		return $this->ignore_words;
	}
}
