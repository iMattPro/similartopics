<?php
/**
*
* @package Precise Similar Topics II
* @copyright (c) 2013 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\acp;

/**
* @package acp
*/
class similar_topics_module
{
	public $u_action;

	protected $db;
	protected $user;
	protected $template;
	protected $request;
	protected $config;
	protected $phpbb_root_path;
	protected $php_ext;

	public function main($id, $mode)
	{
		global $db, $user, $template, $request, $config, $phpbb_root_path, $phpEx;

		$this->db = $db;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
		$this->config = $config;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $phpEx;

		$this->user->add_lang('acp/common');
		$this->tpl_name = 'acp_similar_topics';
		$this->page_title = $this->user->lang['PST_TITLE'];

		$form_key = 'acp_similar_topics';
		add_form_key($form_key);

		$action = $this->request->variable('action', '');

		switch ($action)
		{
			case 'advanced':
				$forum_id = $this->request->variable('f', 0);

				if ($this->request->is_set_post('submit'))
				{
					if (!check_form_key($form_key))
					{
						trigger_error('FORM_INVALID');
					}

					$similar_forums	= $this->request->variable('similar_forums_id', array(0));
					$similar_forums_string = implode(',', $similar_forums);

					$sql = 'UPDATE ' . FORUMS_TABLE . "
						SET similar_topic_forums = '" . $this->db->sql_escape($similar_forums_string) . "'
						WHERE forum_id = $forum_id";
					$this->db->sql_query($sql);

					add_log('admin', 'PST_LOG_MSG');

					trigger_error($this->user->lang['PST_SAVED'] . adm_back_link($this->u_action));
				}

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
					'PST_VERSION'				=> isset($this->config['similar_topics_version']) ? 'v' . $this->config['similar_topics_version'] : false,
					'SIMILAR_FORUMS_OPTIONS'	=> make_forum_select($selected, false, false, true),
					'PST_FORUM_NAME'			=> $forum_name,
					'PST_ADVANCED_EXP'			=> sprintf($this->user->lang['PST_ADVANCED_EXP'], $forum_name),
					'U_ACTION'					=> $this->u_action . '&amp;action=advanced&amp;f=' . $forum_id,
					'U_BACK'					=> $this->u_action,
				));
			break;

			default:
				if ($this->request->is_set_post('submit'))
				{
					if (!check_form_key($form_key))
					{
						trigger_error('FORM_INVALID');
					}

					$pst_enable = $this->request->variable('pst_enable', 0);
					$this->config->set('similar_topics', $pst_enable);

					$pst_limit = $this->request->variable('pst_limit', 0);
					$this->config->set('similar_topics_limit', abs($pst_limit));

					$pst_time_type = $this->request->variable('pst_time_type', '');
					$this->config->set('similar_topics_type', $pst_time_type);

					$pst_cache = $this->request->variable('pst_cache', 0);
					$this->config->set('similar_topics_cache', abs($pst_cache));

					$pst_ignore_forum = $this->request->variable('mark_ignore_forum', array(0), true);
					$this->config->set('similar_topics_ignore', (sizeof($pst_ignore_forum)) ? implode(',', $pst_ignore_forum) : '');

					$pst_noshow_forum = $this->request->variable('mark_noshow_forum', array(0), true);
					$this->config->set('similar_topics_hide', (sizeof($pst_noshow_forum)) ? implode(',', $pst_noshow_forum) : '');

					$pst_time = $this->request->variable('pst_time', 0);
					$this->config->set('similar_topics_time', $this->set_pst_time($pst_time, $pst_time_type));

					$pst_words = $this->request->variable('pst_words', '');
					$this->config->set('similar_topics_words', $pst_words);

					add_log('admin', 'PST_LOG_MSG');

					trigger_error($this->user->lang['PST_SAVED'] . adm_back_link($this->u_action));
				}

				// Build the time options select menu
				$time_options = array('d' => $this->user->lang['PST_DAYS'], 'w' => $this->user->lang['PST_WEEKS'], 'm' => $this->user->lang['PST_MONTHS'], 'y' => $this->user->lang['PST_YEARS']);
				$s_time_options = '';
				foreach ($time_options as $key => $value)
				{
					$selected = ($key == $this->config['similar_topics_type']) ? ' selected="selected"' : '';
					$s_time_options .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}

				$this->template->assign_vars(array(
					'S_PST_ENABLE'		=> isset($this->config['similar_topics']) ? $this->config['similar_topics'] : false,
					'PST_LIMIT'			=> isset($this->config['similar_topics_limit']) ? $this->config['similar_topics_limit'] : '',
					'PST_TIME'			=> $this->get_pst_time($this->config['similar_topics_time'], $this->config['similar_topics_type']),
					'PST_CACHE'			=> isset($this->config['similar_topics_cache']) ? $this->config['similar_topics_cache'] : '',
					'PST_WORDS'			=> isset($this->config['similar_topics_words']) ? $this->config['similar_topics_words'] : '',
					'S_TIME_OPTIONS'	=> $s_time_options,
					'PST_VERSION'		=> isset($this->config['similar_topics_version']) ? 'v' . $this->config['similar_topics_version'] : '',
					'S_PST_NO_SUPPORT'	=> !$this->fulltext_support(),
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
						'S_IS_ADVANCED'			=> $row['similar_topic_forums'] ? true : false,
						'U_ADVANCED'			=> $this->u_action . '&amp;action=advanced&amp;f=' . $row['forum_id'],
						'U_FORUM'				=> "{$this->phpbb_root_path}viewforum.$this->php_ext?f=" . $row['forum_id'],
					));
				}
			break;
		}
	}

	/**
	* Get forums list
	*
	* @return array	forum data rows
	* @access private
	*/
	private function get_forum_list()
	{
		$forum_list = array();

		$sql = 'SELECT forum_id, forum_name, similar_topic_forums
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' .	FORUM_POST . '
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
	* @access private
	*/
	private function set_pst_time($length, $type = 'y')
	{
		$length = abs($length);
		switch ($type)
		{
			case 'y':
				return ($length * 365 * 24 * 60 * 60);
			break;

			case 'm':
				return (round($length * 30.4) * 24 * 60 * 60);
			break;

			case 'w':
				return ($length * 7 * 24 * 60 * 60);
			break;

			case 'd':
				return ($length * 24 * 60 * 60);
			break;
		}
	}

	/**
	* Get the correct time $length value for the form
	*
	* @param int $time as a timestamp
	* @param string $type years, months, weeks, days
	* @return int time converted to the given $type
	* @access private
	*/
	private function get_pst_time($time, $type)
	{
		switch ($type)
		{
			case 'y':
				$length = $time / (365 * 24 * 60 * 60);
			break;

			case 'm':
				$length = round($time / (30.4 * 24 * 60 * 60));
			break;

			case 'w':
				$length = $time / (7 * 24 * 60 * 60);
			break;

			case 'd':
				$length = $time / (24 * 60 * 60);
			break;

			default:
				$length = '';
			break;
		}
		return (int) $length;
	}

	/**
	* Check for FULLTEXT index support
	*
	* @return bool true means FULLTEXT is supported
	* @access private
	*/
	private function fulltext_support()
	{
		if (($this->db->sql_layer != 'mysql4') && ($this->db->sql_layer != 'mysqli'))
		{
			return false;
		}

		$result = $this->db->sql_query('SHOW TABLE STATUS LIKE \'' . TOPICS_TABLE . '\'');
		$info = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$engine = '';
		if (isset($info['Engine']))
		{
			$engine = strtolower($info['Engine']);
		}
		else if (isset($info['Type']))
		{
			$engine = strtolower($info['Type']);
		}

		// FULLTEXT is supported on InnoDB since MySQL 5.6.4 according to
		// http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
		if ($engine === 'myisam' || ($engine === 'innodb' && phpbb_version_compare($this->db->sql_server_info(true), '5.6.4', '>=')))
		{
			return $this->is_fulltext('topic_title');
		}

		return false;
	}

	/**
	* Check if a field is a FULLTEXT index
	*
	* @param string $field name of a field
	* @return bool true means the field is a FULLTEXT index
	* @access private
	*/
	private function is_fulltext($field)
	{
		$sql = "SHOW INDEX 
			FROM " . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// deal with older MySQL versions which didn't use Index_type
			$index_type = (isset($row['Index_type'])) ? $row['Index_type'] : $row['Comment'];

			if ($index_type == 'FULLTEXT' && $row['Key_name'] == $field)
			{
				return true;
			}
		}

		return false;
	}
}
