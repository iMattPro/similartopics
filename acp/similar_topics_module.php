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
	const ONE_DAY = 86400;
	const ONE_WEEK = 604800;
	const ONE_YEAR = 31536000;

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
		global $config, $db, $request, $template, $user, $phpbb_log, $phpbb_root_path, $phpEx;

		$this->config = $config;
		$this->db = $db;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->log = $phpbb_log;
		$this->root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;
		$this->fulltext = new \vse\similartopics\core\fulltext_support($this->db);

		$this->user->add_lang('acp/common');
		$this->user->add_lang_ext('vse/similartopics', 'acp_similar_topics');

		$this->tpl_name = 'acp_similar_topics';
		$this->page_title = $this->user->lang('PST_TITLE_ACP');
	}

	/**
	* Main ACP module
	*
	* @param int $id
	* @param string $mode
	* @access public
	*/
	public function main($id, $mode)
	{
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

					$similar_topic_forums = implode(',', $this->request->variable('similar_forums_id', array(0)));
					$this->validate_config_length($similar_topic_forums);

					$sql = 'UPDATE ' . FORUMS_TABLE . "
						SET similar_topic_forums = '" . $this->db->sql_escape($similar_topic_forums) . "'
						WHERE forum_id = $forum_id";
					$this->db->sql_query($sql);

					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_MSG');

					trigger_error($this->user->lang('PST_SAVED') . adm_back_link($this->u_action));
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
						$selected = explode(',', trim($fid['similar_topic_forums']));
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

					// Get checkbox array form data and check string length
					$mark_noshow_forum = implode(',', $this->request->variable('mark_noshow_forum', array(0), true));
					$mark_ignore_forum = implode(',', $this->request->variable('mark_ignore_forum', array(0), true));
					$this->validate_config_length($mark_noshow_forum, $mark_ignore_forum);

					// Set basic config settings
					$this->config->set('similar_topics', $this->request->variable('pst_enable', 0));
					$this->config->set('similar_topics_limit', abs($this->request->variable('pst_limit', 0))); // use abs for positive values only
					$this->config->set('similar_topics_cache', abs($this->request->variable('pst_cache', 0))); // use abs for positive values only
					$this->config->set('similar_topics_words', $this->request->variable('pst_words', '', true));
					$this->config->set('similar_topics_hide', $mark_noshow_forum);
					$this->config->set('similar_topics_ignore', $mark_ignore_forum);

					// Set date/time config settings
					$pst_time = abs($this->request->variable('pst_time', 0)); // use abs for positive values only
					$pst_time_type = $this->request->variable('pst_time_type', '');
					$this->config->set('similar_topics_type', $pst_time_type);
					$this->config->set('similar_topics_time', $this->set_pst_time($pst_time, $pst_time_type));

					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_MSG');

					trigger_error($this->user->lang('PST_SAVED') . adm_back_link($this->u_action));
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

							trigger_error($this->user->lang('PST_SAVE_FULLTEXT') . adm_back_link($this->u_action));
						}
						else
						{
							trigger_error($this->user->lang('PST_ERR_FULLTEXT') . adm_back_link($this->u_action), E_USER_WARNING);
						}
					}
					else
					{
						confirm_box(false, $this->user->lang('CONFIRM_OPERATION'), build_hidden_fields(array(
							'fulltext' => 1,
						)));
					}
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

				$ignore_forums = explode(',', trim($this->config['similar_topics_ignore']));
				$noshow_forums = explode(',', trim($this->config['similar_topics_hide']));

				$forum_list = $this->get_forum_list();
				foreach ($forum_list as $row)
				{
					$this->template->assign_block_vars('forums', array(
						'FORUM_NAME'			=> $row['forum_name'],
						'FORUM_ID'				=> $row['forum_id'],
						'CHECKED_IGNORE_FORUM'	=> (in_array($row['forum_id'], $ignore_forums)) ? 'checked="checked"' : '',
						'CHECKED_NOSHOW_FORUM'	=> (in_array($row['forum_id'], $noshow_forums)) ? 'checked="checked"' : '',
						'S_IS_ADVANCED'			=> (bool) $row['similar_topic_forums'],
						'U_ADVANCED'			=> "{$this->u_action}&amp;action=advanced&amp;f=" . $row['forum_id'],
						'U_FORUM'				=> append_sid("{$this->root_path}viewforum.{$this->php_ext}", 'f=' . $row['forum_id']),
					));
				}
			break;
		}
	}

	/**
	* Check if config field values exceed 255 chars
	*
	* @return null
	* @access protected
	*/
	protected function validate_config_length()
	{
		$arg_list = func_get_args();
		foreach ($arg_list as $arg)
		{
			if (strlen($arg) > 255)
			{
				trigger_error($this->user->lang('PST_ERR_CONFIG') . adm_back_link($this->u_action), E_USER_WARNING);
			}
		}
	}

	/**
	* Check form key, trigger error if invalid
	*
	* @param string $form_key The form key value
	* @return null
	* @access protected
	*/
	protected function check_form_key($form_key)
	{
		if (!check_form_key($form_key))
		{
			trigger_error($this->user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
		}
	}

	/**
	* Get forums list
	*
	* @return array	forum data rows
	* @access protected
	*/
	protected function get_forum_list()
	{
		$sql = 'SELECT forum_id, forum_name, similar_topic_forums
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' . FORUM_POST . '
			ORDER BY left_id ASC';
		$result = $this->db->sql_query($sql);
		$forum_list = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $forum_list;
	}

	/**
	* Calculate the $pst_time based on user input
	*
	* @param int $length user entered value
	* @param string $type years, months, weeks, days
	* @return int time in seconds
	* @access protected
	*/
	protected function set_pst_time($length, $type)
	{
		switch ($type)
		{
			case 'd':
				$time = $length * self::ONE_DAY;
			break;

			case 'w':
				$time = $length * self::ONE_WEEK;
			break;

			case 'm':
				$time = round($length * 30.4) * self::ONE_DAY;
			break;

			case 'y':
			default:
				$time = $length * self::ONE_YEAR;
			break;
		}
		return (int) $time;
	}

	/**
	* Get the correct time $length value for the form
	*
	* @param int $time as a timestamp
	* @param string $type years, months, weeks, days
	* @return int time converted to the given $type
	* @access protected
	*/
	protected function get_pst_time($time, $type)
	{
		switch ($type)
		{
			case 'y':
				$length = $time / self::ONE_YEAR;
			break;

			case 'm':
				$length = round($time / 30.4 / self::ONE_DAY);
			break;

			case 'w':
				$length = $time / self::ONE_WEEK;
			break;

			case 'd':
				$length = $time / self::ONE_DAY;
			break;

			default:
				$length = 0;
			break;
		}
		return (int) $length;
	}

	/**
	* Check for FULLTEXT index support
	*
	* @return bool True if FULLTEXT is fully supported, false otherwise
	* @access protected
	*/
	protected function fulltext_support_enabled()
	{
		if ($this->fulltext->is_supported())
		{
			return $this->fulltext->index('topic_title');
		}

		return false;
	}

	/**
	* Enable FULLTEXT support for the topic_title
	*
	* @return null
	* @access protected
	*/
	protected function enable_fulltext_support()
	{
		if (!$this->fulltext->is_mysql())
		{
			trigger_error($this->user->lang('PST_NO_MYSQL') . adm_back_link($this->u_action), E_USER_WARNING);
		}

		// Alter the storage engine
		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ENGINE = MYISAM';
		$this->db->sql_query($sql);

		// Prevent adding extra indeces.
		if ($this->fulltext->index('topic_title'))
		{
			return;
		}

		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (topic_title)';
		$this->db->sql_query($sql);
	}

	/**
	* Return a variable if it is set, otherwise default
	*
	* @param mixed $var The variable to test
	* @param mixed $default The default value to use
	* @return mixed The value of the variable if set, otherwise default value
	* @access protected
	*/
	protected function isset_or_default($var, $default)
	{
		return (isset($var)) ? $var : $default;
	}
}
