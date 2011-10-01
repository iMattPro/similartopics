<?php
/**
*
* @package acp
* @version $Id: similar_topics_version.php 3 10/1/11 11:32 AM VSE $
* @copyright (c) 2007 StarTrekGuide
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
			'version'	=> '1.1.6',
			'file'		=> array('www.orca-music.com', 'software', 'mods.xml'),
		);
	}
}

?>