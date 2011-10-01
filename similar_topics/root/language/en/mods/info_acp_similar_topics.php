<?php
/**
*
* @package language
* @version $Id: info_acp_similar_topics.php 15 9/30/11 8:08 PM VSE $
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
	'PST_LIMIT'			=> 'Number of Similar Topics to display',
	'PST_LIMIT_EXPLAIN'	=> 'Here you can define how many similar topics to display. The default is 5 topics.',
	'PST_TIME'			=> 'Search Period',
	'PST_TIME_EXPLAIN'	=> 'This option allows you to configure the Similar Topics search period. For example, if set to “5 days” the system will only show similar topics from within the last 5 days. The default is 1 year.',	
	'PST_YEARS'			=> 'Years',
	'PST_MONTHS'		=> 'Months',
	'PST_WEEKS'			=> 'Weeks',
	'PST_DAYS'			=> 'Days',
	'PST_CACHE'			=> 'Similar Topics cache length',
	'PST_CACHE_EXPLAIN'	=> 'Cached similar topics will expire after this time, in seconds. Set to 0 if you want to disable the similar topics cache.',
	'PST_LEGEND3'		=> 'Forum Settings',
	'PST_NOSHOW_LIST' 	=> 'Do Not Display In',
	'PST_NOSHOW_TITLE'	=> 'Do not display similar topics in',
	'PST_IGNORE_SEARCH' => 'Do Not Search In',
	'PST_IGNORE_TITLE'	=> 'Do not search for similar topics in',
	'PST_ADVANCED'		=> 'Advanced',
	'PST_ADVANCED_TITLE'=> 'Click to set up advanced similar topic settings for',
	'PST_ADVANCED_EXP'	=> 'Here you can select specific forums to pull similar topics from. Only similar topics found in the forums you select here will be displayed in <strong>%s</strong>.<br /><br />Do not select any forums if you want similar topics from all searchable forums to be displayed in this forum.',
	'PST_DESELECT_ALL'	=> 'Deselect all',
	'PST_LEGEND4'		=> 'Optional Settings',
	'PST_WORDS'			=> 'Special words to ignore',
	'PST_WORDS_EXPLAIN'	=> 'Common words in your language will already be ignored, so this is for special words unique to your forum that may be too common and interfering with finding relevant similar topics. Separate each word with a space. Max. 255 characters.',
	'PST_SAVED'			=> 'Similar Topics settings updated',
	'PST_FORUM_INFO'	=> '“Do Not Display In”: Will not show similar topics in the selected forums.<br />“Do Not Search In” : Will not search the selected forums for similar topics.',
	'PST_WARNING'		=> 'Similar Topics will not work with your forum. Similar Topics requires a MySQL 4 or MySQL 5 database.',
	'PST_LOG_MSG'		=> '<strong>Altered similar topics settings</strong>',

	//For UMIL Installer
	'PST_FULLTEXT_ADD'	=> 'Adding FULLTEXT index: topic_title',
	'PST_FULLTEXT_DROP'	=> 'Dropped FULLTEXT index: topic_title',
));

// For permissions
$lang = array_merge($lang, array(
	'acl_u_similar_topics'    => array('lang' => 'Can view similar topics', 'cat' => 'misc'),
));

?>