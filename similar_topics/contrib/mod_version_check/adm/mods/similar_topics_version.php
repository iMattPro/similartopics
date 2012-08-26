<?php
/**
*
* @package Precise Similar Topics II
* @copyright (c) 2010 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @package mod_version_check
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

class similar_topics_version
{
	function version()
	{
		return array(
			'author'	=> 'VSE',
			'title'		=> 'Precise Similar Topics II',
			'tag'		=> 'similar_topics',
			'version'	=> '1.2.0',
			'file'		=> array('orcamx.vlexofree.com', 'software', 'mods.xml'),
		);
	}
}

?>