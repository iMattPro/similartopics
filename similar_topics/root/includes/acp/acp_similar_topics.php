<?php
/**
*
* @package - Precise Similar Topics II
* @version $Id: acp_similar_topics.php 4 6/10/10 5:05 PM VSE $
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
		add_form_key('acp_similar_topics');

		$submit = (isset($_POST['submit'])) ? true : false;

		if ($submit)
		{
			if (!check_form_key('acp_similar_topics'))
			{
				trigger_error('FORM_INVALID');
			}

			$pst_enable = request_var('pst_enable', 0);
			set_config('similar_topics', $pst_enable);

			$pst_list = request_var('pst_list', 5);
			set_config('similar_topics_list', $pst_list);

			$pst_year = request_var('pst_year', 1);
			set_config('similar_topics_year', $pst_year);

			$pst_ignore_forum = request_var('mark_ignore_forum', array(0), true);
			set_config('similar_topics_ignore', (sizeof($pst_ignore_forum)) ? implode(',', $pst_ignore_forum) : '');

			$pst_noshow_forum = request_var('mark_noshow_forum', array(0), true);
			set_config('similar_topics_hide', (sizeof($pst_noshow_forum)) ? implode(',', $pst_noshow_forum) : '');

			trigger_error($user->lang['PST_SAVED'] . adm_back_link($this->u_action));
		}

		$template->assign_vars(array(
			'S_PST_ENABLE'		=> isset($config['similar_topics']) ? $config['similar_topics'] : false,
			'PST_LIST'			=> isset($config['similar_topics_list']) ? $config['similar_topics_list'] : '',
			'PST_YEAR'			=> isset($config['similar_topics_year']) ? $config['similar_topics_year'] : '',
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
				'U_FORUM'				=> "{$phpbb_root_path}viewforum.$phpEx?f=" . $row['forum_id'],
			));
		}

	}

	/**
	* Get forums list
	*/
	function get_forum_list()
	{
		global $db;
		
		$forum_list = array();

		$sql = 'SELECT forum_id, forum_name
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' .	FORUM_POST . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql);
		$forum_list = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		return $forum_list;
	}
}
?>