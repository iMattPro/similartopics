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

/**
 * @package module_install
 */
class phpbb_ext_vse_similartopics_acp_similar_topics_info
{
	function module()
	{
		return array(
			'filename'	=> 'similar_topics_module',
			'title'		=> 'PST_TITLE',
			'version'	=> '1.3.0',
			'modes'		=> array(
				'index'	=> array('title' => 'PST_TITLE', 'auth'	=> 'acl_a_board', 'cat'	=> array('PST_TITLE')),
			),
		);
	}
}
