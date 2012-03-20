<?php
/**
*
* @package acp
* @copyright (c) 2007 StarTrekGuide
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
			'version'	=> '1.1.7',
			'file'		=> array('orcamx.vlexofree.com', 'software', 'mods.xml'),
		);
	}
}

?>