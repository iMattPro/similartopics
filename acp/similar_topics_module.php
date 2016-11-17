<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\acp;

/**
 * @package acp
 */
class similar_topics_module
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \vse\similartopics\core\fulltext_support */
	protected $fulltext;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var array */
	protected $times;

	/** @var string */
	public $page_title;

	/** @var string */
	public $tpl_name;

	/** @var string */
	public $u_action;

	/**
	 * ACP module constructor
	 *
	 * @access public
	 */
	public function __construct()
	{
		global $phpbb_container;

		$this->config    = $phpbb_container->get('config');
		$this->db        = $phpbb_container->get('dbal.conn');
		$this->fulltext  = $phpbb_container->get('vse.similartopics.fulltext_support');
		$this->log       = $phpbb_container->get('log');
		$this->request   = $phpbb_container->get('request');
		$this->template  = $phpbb_container->get('template');
		$this->user      = $phpbb_container->get('user');
		$this->root_path = $phpbb_container->getParameter('core.root_path');
		$this->php_ext   = $phpbb_container->getParameter('core.php_ext');
		$this->times     = array(
			'd' => 86400, // one day
			'w' => 604800, // one week
			'm' => 2626560, // one month
			'y' => 31536000, // one year
		);
	}

	/**
	 * Main ACP module
	 *
	 * @access public
	 */
	public function main()
	{
		$this->user->add_lang_ext('vse/similartopics', 'acp_similar_topics');

		$this->tpl_name = 'acp_similar_topics';
		$this->page_title = $this->user->lang('PST_TITLE_ACP');

		$form_key = 'acp_similar_topics';
		add_form_key($form_key);

		$action = $this->request->variable('action', '');

		switch ($action)
		{
			case 'advanced':
				$forum_id = $this->request->variable('f', 0);

				if ($this->request->is_set_post('submit'))
				{
					$this->check_form_key($form_key);

					$similar_topic_forums = $this->request->variable('similar_forums_id', array(0));
					$similar_topic_forums = !empty($similar_topic_forums) ? json_encode($similar_topic_forums) : '';

					$sql = 'UPDATE ' . FORUMS_TABLE . "
						SET similar_topic_forums = '" . $this->db->sql_escape($similar_topic_forums) . "'
						WHERE forum_id = $forum_id";
					$this->db->sql_query($sql);

					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_MSG');

					$this->end('PST_SAVED');
				}

				$forum_name = '';
				$selected = array();
				if ($forum_id > 0)
				{
					$sql = 'SELECT forum_name, similar_topic_forums
						FROM ' . FORUMS_TABLE . "
						WHERE forum_id = $forum_id";
					$result = $this->db->sql_query($sql);
					while ($fid = $this->db->sql_fetchrow($result))
					{
						$selected = json_decode($fid['similar_topic_forums'], true);
						$forum_name = $fid['forum_name'];
					}
					$this->db->sql_freeresult($result);
				}

				$this->template->assign_vars(array(
					'S_ADVANCED_SETTINGS'		=> true,
					'SIMILAR_FORUMS_OPTIONS'	=> make_forum_select($selected, false, false, true),
					'PST_FORUM_NAME'			=> $forum_name,
					'PST_ADVANCED_EXP'			=> $this->user->lang('PST_ADVANCED_EXP', $forum_name),
					'U_ACTION'					=> $this->u_action . '&amp;action=advanced&amp;f=' . $forum_id,
					'U_BACK'					=> $this->u_action,
				));
			break;

			default:
				if ($this->request->is_set_post('submit'))
				{
					$this->check_form_key($form_key);

					// Set basic config settings
					$this->config->set('similar_topics', $this->request->variable('pst_enable', 0));
					$this->config->set('similar_topics_limit', abs($this->request->variable('pst_limit', 0))); // use abs for positive values only
					$this->config->set('similar_topics_cache', abs($this->request->variable('pst_cache', 0))); // use abs for positive values only
					$this->config->set('similar_topics_words', $this->request->variable('pst_words', '', true));

					// Set date/time config settings
					$pst_time = abs($this->request->variable('pst_time', 0)); // use abs for positive values only
					$pst_time_type = $this->request->variable('pst_time_type', '');
					$this->config->set('similar_topics_type', $pst_time_type);
					$this->config->set('similar_topics_time', $this->set_pst_time($pst_time, $pst_time_type));

					// Set checkbox array form data
					$this->update_forum('similar_topics_hide', $this->request->variable('mark_noshow_forum', array(0), true));
					$this->update_forum('similar_topics_ignore', $this->request->variable('mark_ignore_forum', array(0), true));

					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_MSG');

					$this->end('PST_SAVED');
				}

				// Allow option to update the database to enable FULLTEXT support
				if ($this->request->is_set_post('fulltext'))
				{
					if (confirm_box(true))
					{
						// If FULLTEXT is not supported, lets make it so
						if (!$this->fulltext_support_enabled())
						{
							// Alter the database to support FULLTEXT
							$this->enable_fulltext_support();

							// Store the original database storage engine in a config var for recovery on uninstall
							$this->config->set('similar_topics_fulltext', (string) $this->fulltext->get_engine());

							$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_FULLTEXT', time(), array(TOPICS_TABLE));

							$this->end('PST_SAVE_FULLTEXT');
						}
						$this->end('PST_ERR_FULLTEXT', E_USER_WARNING);
					}
					confirm_box(false, $this->user->lang('CONFIRM_OPERATION'), build_hidden_fields(array(
						'fulltext' => 1,
					)));
				}

				// Build the time options select menu
				$time_options = array(
					'd' => $this->user->lang('PST_DAYS'),
					'w' => $this->user->lang('PST_WEEKS'),
					'm' => $this->user->lang('PST_MONTHS'),
					'y' => $this->user->lang('PST_YEARS')
				);
				foreach ($time_options as $value => $label)
				{
					$this->template->assign_block_vars('similar_time_options', array(
						'VALUE'			=> $value,
						'LABEL'			=> $label,
						'S_SELECTED'	=> $value == $this->config['similar_topics_type'],
					));
				}

				$this->template->assign_vars(array(
					'S_PST_ENABLE'		=> $this->isset_or_default($this->config['similar_topics'], false),
					'PST_LIMIT'			=> $this->isset_or_default($this->config['similar_topics_limit'], ''),
					'PST_CACHE'			=> $this->isset_or_default($this->config['similar_topics_cache'], ''),
					'PST_WORDS'			=> $this->isset_or_default($this->config['similar_topics_words'], ''),
					'PST_TIME'			=> $this->get_pst_time($this->config['similar_topics_time'], $this->config['similar_topics_type']),
					'S_PST_NO_SUPPORT'	=> !$this->fulltext_support_enabled(),
					'S_PST_NO_MYSQL'	=> !$this->fulltext->is_mysql(),
					'U_ACTION'			=> $this->u_action,
				));

				$forum_list = $this->get_forum_list();
				foreach ($forum_list as $row)
				{
					$this->template->assign_block_vars('forums', array(
						'FORUM_NAME'			=> $row['forum_name'],
						'FORUM_ID'				=> $row['forum_id'],
						'CHECKED_IGNORE_FORUM'	=> $row['similar_topics_ignore'] ? 'checked="checked"' : '',
						'CHECKED_NOSHOW_FORUM'	=> $row['similar_topics_hide'] ? 'checked="checked"' : '',
						'S_IS_ADVANCED'			=> (bool) $row['similar_topic_forums'],
						'U_ADVANCED'			=> "{$this->u_action}&amp;action=advanced&amp;f=" . $row['forum_id'],
						'U_FORUM'				=> append_sid("{$this->root_path}viewforum.{$this->php_ext}", 'f=' . $row['forum_id']),
					));
				}
			break;
		}
	}

	/**
	 * Check form key, trigger error if invalid
	 *
	 * @access protected
	 * @param string $form_key The form key value
	 */
	protected function check_form_key($form_key)
	{
		if (!check_form_key($form_key))
		{
			$this->end('FORM_INVALID', E_USER_WARNING);
		}
	}

	/**
	 * Get forums list
	 *
	 * @access protected
	 * @return array forum data rows
	 */
	protected function get_forum_list()
	{
		$sql = 'SELECT forum_id, forum_name, similar_topic_forums, similar_topics_hide, similar_topics_ignore
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' . FORUM_POST . '
			ORDER BY left_id ASC';
		$result = $this->db->sql_query($sql);
		$forum_list = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $forum_list;
	}

	/**
	 * Update the similar topics columns in the forums table
	 *
	 * @param string $column    The name of the column to update
	 * @param array  $forum_ids An array of forum_ids
	 */
	protected function update_forum($column, $forum_ids)
	{
		$this->db->sql_transaction('begin');

		// Set marked forums (in set) to 1
		$sql = 'UPDATE ' . FORUMS_TABLE . "
			SET $column = 1
			WHERE " . $this->db->sql_in_set('forum_id', $forum_ids, false, true);
		$this->db->sql_query($sql);

		// Set unmarked forums (not in set) to 0
		$sql = 'UPDATE ' . FORUMS_TABLE . "
			SET $column = 0
			WHERE " . $this->db->sql_in_set('forum_id', $forum_ids, true, true);
		$this->db->sql_query($sql);

		$this->db->sql_transaction('commit');
	}

	/**
	 * Calculate the time in seconds based on requested time period length
	 *
	 * @access protected
	 * @param int    $length user entered value
	 * @param string $type   years, months, weeks, days (y|m|w|d)
	 * @return int time in seconds
	 */
	protected function set_pst_time($length, $type = 'y')
	{
		$type = isset($this->times[$type]) ? $type : 'y';

		return (int) ($length * $this->times[$type]);
	}

	/**
	 * Get the correct time period length value for the form
	 *
	 * @access protected
	 * @param int    $time as a timestamp
	 * @param string $type years, months, weeks, days (y|m|w|d)
	 * @return int time converted to the given $type
	 */
	protected function get_pst_time($time, $type = '')
	{
		return isset($this->times[$type]) ? (int) round($time / $this->times[$type]) : 0;
	}

	/**
	 * Check for FULLTEXT index support
	 *
	 * @access protected
	 * @return bool True if FULLTEXT is fully supported, false otherwise
	 */
	protected function fulltext_support_enabled()
	{
		if ($this->fulltext->is_supported())
		{
			return $this->fulltext->is_index('topic_title');
		}

		return false;
	}

	/**
	 * Enable FULLTEXT support for the topic_title
	 *
	 * @access protected
	 */
	protected function enable_fulltext_support()
	{
		if (!$this->fulltext->is_mysql())
		{
			$this->end('PST_NO_MYSQL', E_USER_WARNING);
		}

		// Alter the storage engine
		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ENGINE = MYISAM';
		$this->db->sql_query($sql);

		// Prevent adding extra indeces.
		if ($this->fulltext->is_index('topic_title'))
		{
			return;
		}

		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (topic_title)';
		$this->db->sql_query($sql);
	}

	/**
	 * Return a variable if it is set, otherwise default
	 *
	 * @access protected
	 * @param mixed $var     The variable to test
	 * @param mixed $default The default value to use
	 * @return mixed The value of the variable if set, otherwise default value
	 */
	protected function isset_or_default($var, $default)
	{
		return null !== $var ? $var : $default;
	}

	/**
	 * End script execution with a trigger_error message
	 *
	 * @access protected
	 * @param string $message Language key string
	 * @param int    $code    E_USER_NOTICE|E_USER_WARNING
	 * @return void
	 */
	protected function end($message, $code = E_USER_NOTICE)
	{
		trigger_error($this->user->lang($message) . adm_back_link($this->u_action), $code);
	}
}
