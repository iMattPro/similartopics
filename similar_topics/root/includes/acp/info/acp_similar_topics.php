<?php
/**
*
* @package acp
* @version $Id: acp_similar_topics.php 10 2/21/12 1:50 AM VSE $
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
* @package module_install
*/
class acp_similar_topics_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_similar_topics',
			'title'		=> 'PST_TITLE',
			'version'	=> '1.1.7',
			'modes'		=> array(
				'index'	=> array(
					'title'			=> 'PST_TITLE',
					'auth'			=> 'acl_a_board',
					'cat'			=> array('PST_TITLE'),
				),
			),
		);
	}
}

?>