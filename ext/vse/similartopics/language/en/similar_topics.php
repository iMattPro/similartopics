<?php
/**
 *
 * info_acp_similiar_topics [English]
 * 
 * @package language
 * @copyright (c) 2010 Matt Friedman
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'SIMILAR_TOPICS'		=> 'Similar Topics',
));
