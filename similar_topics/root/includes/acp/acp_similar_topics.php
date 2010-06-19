<?php
/**
*
* @package acp
* @version $Id: acp_similar_topics.php 13 6/18/10 9:07 PM VSE $
* @copyright (c) 2010 Matt Friedman
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
* @package acp
*/
class acp_similar_topics
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('acp/common');
		$this->tpl_name = 'acp_similar_topics';
		$this->page_title = $user->lang['PST_TITLE'];

		$form_name = 'acp_similar_topics';
		add_form_key($form_name);

		$action = request_var('action', '');

		switch ($action)
		{
			case 'advanced':

				$submit = (isset($_POST['submit'])) ? true : false;
				$forum_id = request_var('f', 0);

				if ($submit)
				{
					if (!check_form_key($form_name))
					{
						trigger_error('FORM_INVALID');
					}

					$similar_forums	= request_var('similar_forums_id', array(0));
					$similar_forums_string = implode(',', $similar_forums);

					$sql = 'UPDATE ' . FORUMS_TABLE . "
						SET similar_topic_forums = '" . $db->sql_escape($similar_forums_string) . "'
						WHERE forum_id = $forum_id";
					$db->sql_query($sql);

					add_log('admin', 'PST_LOG_MSG');

					trigger_error($user->lang['PST_SAVED'] . adm_back_link($this->u_action));
				}

				$selected = array();
				if ($forum_id > 0)
				{
					$sql = 'SELECT forum_name, similar_topic_forums
						FROM ' . FORUMS_TABLE . '
						WHERE forum_id = ' . $db->sql_escape($forum_id);
					$result = $db->sql_query($sql);
					while ($fid = $db->sql_fetchrow($result))
					{
						$selected = explode(',', trim($fid['similar_topic_forums']));
						$forum_name = $fid['forum_name'];
					}
					$db->sql_freeresult($result);
				}

				$template->assign_vars(array(
					'S_ADVANCED_SETTINGS'		=> true,
					'S_PST_VERSION'				=> isset($config['similar_topics_version']) ? 'v' . $config['similar_topics_version'] : false,
					'SIMILAR_FORUMS_OPTIONS'	=> make_forum_select($selected, false, false, true),
					'PST_FORUM_NAME'			=> $forum_name,
					'PST_ADVANCED_EXP'			=> sprintf($user->lang['PST_ADVANCED_EXP'], $forum_name),
					'U_ACTION'					=> $this->u_action . '&amp;action=advanced&amp;f=' . $forum_id,
					'U_BACK'					=> $this->u_action,
				));

			break;

			default:

				$submit = (isset($_POST['submit'])) ? true : false;

				if ($submit)
				{
					if (!check_form_key($form_name))
					{
						trigger_error('FORM_INVALID');
					}

					$pst_enable = request_var('pst_enable', 0);
					set_config('similar_topics', $pst_enable);

					$pst_limit = request_var('pst_limit', 0);
					set_config('similar_topics_limit', abs($pst_limit));

					$pst_time_type = request_var('pst_time_type', '');
					set_config('similar_topics_type', $pst_time_type);

					$pst_ignore_forum = request_var('mark_ignore_forum', array(0), true);
					set_config('similar_topics_ignore', (sizeof($pst_ignore_forum)) ? implode(',', $pst_ignore_forum) : '');

					$pst_noshow_forum = request_var('mark_noshow_forum', array(0), true);
					set_config('similar_topics_hide', (sizeof($pst_noshow_forum)) ? implode(',', $pst_noshow_forum) : '');

					$pst_time = request_var('pst_time', 0);
					set_config('similar_topics_time', $this->set_pst_time($pst_time, $pst_time_type));

					add_log('admin', 'PST_LOG_MSG');

					trigger_error($user->lang['PST_SAVED'] . adm_back_link($this->u_action));
				}

				// Build the time options select menu
				$time_options = array('d' => $user->lang['PST_DAYS'], 'w' => $user->lang['PST_WEEKS'], 'm' => $user->lang['PST_MONTHS'], 'y' => $user->lang['PST_YEARS']);
				$s_time_options = '';
				foreach ($time_options as $key => $value)
				{
					$selected = ($key == $config['similar_topics_type']) ? ' selected="selected"' : '';
					$s_time_options .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
				}

				$template->assign_vars(array(
					'S_PST_ENABLE'		=> isset($config['similar_topics']) ? $config['similar_topics'] : false,
					'PST_LIMIT'			=> isset($config['similar_topics_limit']) ? $config['similar_topics_limit'] : '',
					'PST_TIME'			=> $this->get_pst_time($config['similar_topics_time'], $config['similar_topics_type']),
					'S_TIME_OPTIONS'	=> $s_time_options,
					'S_PST_VERSION'		=> isset($config['similar_topics_version']) ? 'v' . $config['similar_topics_version'] : false,
					'U_ACTION'			=> $this->u_action,
				));

				$ignore_forums = explode(',', trim($config['similar_topics_ignore']));
				$noshow_forums = explode(',', trim($config['similar_topics_hide']));

				$forum_list = $this->get_forum_list();
				foreach ($forum_list as $forum_id => $row)
				{
					$template->assign_block_vars('forums', array(
						'FORUM_NAME'			=> $row['forum_name'],
						'FORUM_ID'				=> $row['forum_id'],
						'CHECKED_IGNORE_FORUM'	=> (in_array($row['forum_id'], $ignore_forums)) ? 'checked="checked"' : '',
						'CHECKED_NOSHOW_FORUM'	=> (in_array($row['forum_id'], $noshow_forums)) ? 'checked="checked"' : '',
						'S_IS_ADVANCED'			=> $row['similar_topic_forums'] ? true : false,
						'U_ADVANCED'			=> append_sid("{$phpbb_admin_path}index.$phpEx", 'i=similar_topics&amp;action=advanced&amp;f=' . $row['forum_id']),
						'U_FORUM'				=> "{$phpbb_root_path}viewforum.$phpEx?f=" . $row['forum_id'],
					));
				}

			break;
		}

		// Warn if database layer is not MySQL 4+
		if ($db->sql_layer != 'mysql4' && $db->sql_layer != 'mysqli')
		{
			$template->assign_var('S_PST_WARNING', true);
		}

	}

	/**
	* Get forums list
	*/
	function get_forum_list()
	{
		global $db;

		$forum_list = array();

		$sql = 'SELECT forum_id, forum_name, similar_topic_forums
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' .	FORUM_POST . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql);
		$forum_list = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $forum_list;
	}

	/**
	* Calculate the $pst_time based on user input
	*
	* @param int $length user entered value
	* @param string $type years, months, weeks, days
	*/
	function set_pst_time($length, $type = 'y')
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
	*/
	function get_pst_time($time, $type)
	{
		switch ($type)
		{
			case 'y':
				$length = ($time / 365 / 24 / 60 / 60);
			break;

			case 'm':
				$length = (round($time / 30.4 / 24 / 60 / 60));
			break;

			case 'w':
				$length = ($time / 7 / 24 / 60 / 60);
			break;

			case 'd':
				$length = ($time / 24 / 60 / 60);
			break;

			default:
				$length = '';
			break;
		}
		return (int) $length;
	}

}
?>