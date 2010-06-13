<?php
/**
*
* @package - Precise Similar Topics II
* @version $Id: info_acp_similar_topics.php 5 6/11/10 12:07 AM VSE $
* @copyright (c) 2010 Matt Friedman
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
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
	//For ACP Page
	'PST_TITLE_ACP'		=> 'Similar Topics',
	'PST_TITLE'			=> 'Precise Similar Topics II',
	'PST_LEGEND1'		=> 'General Settings',
	'PST_ENABLE'		=> 'Enable Similar Topics',
	'PST_LEGEND2'		=> 'Load Settings',
	'PST_LIST'			=> 'Number of Similar Topics to display',
	'PST_LIST_EXPLAIN'	=> 'Here you can define how many similar topics to display. The default is 5 topics.',	
	'PST_YEAR'			=> 'Number of years worth of topics to search',
	'PST_YEAR_EXPLAIN'	=> 'Here you can define how far back into your board’s history to search for similar topics. The default is 1 year.',	
	'PST_YEARS'			=> 'year(s)',
	'PST_LEGEND3'		=> 'Forum Settings',
	'PST_NOSHOW_LIST' 	=> 'Do Not Display In',
	'PST_NOSHOW_TITLE'	=> 'Do not display similar topics in',
	'PST_IGNORE_SEARCH' => 'Do Not Search In',
	'PST_IGNORE_TITLE'	=> 'Do not search for similar topics in',
	'PST_SAVED'			=> 'Similar Topics settings updated',

	//For UMIL Installer
	'PST_FULLTEXT_ADD'	=> 'Adding FULLTEXT index: topic_title',
	'PST_FULLTEXT_DROP'	=> 'Dropped FULLTEXT index: topic_title',
));

?>