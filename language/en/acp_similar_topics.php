<?php
/**
*
* Precise Similar Topics [English]
*
* @copyright (c) 2013 Matt Friedman
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'PST_TITLE_ACP'		=> 'Precise Similar Topics',
	'PST_EXPLAIN'		=> 'Precise Similar Topics displays a list of similar (related) topics at the bottom of the current topic’s page.',
	'PST_LEGEND1'		=> 'General settings',
	'PST_ENABLE'		=> 'Display Similar Topics',
	'PST_LEGEND2'		=> 'Load settings',
	'PST_LIMIT'			=> 'Number of Similar Topics to display',
	'PST_LIMIT_EXPLAIN'	=> 'Here you can define how many similar topics to display. The default is 5 topics.',
	'PST_TIME'			=> 'Search period',
	'PST_TIME_EXPLAIN'	=> 'This option allows you to confine Similar Topics results to newer topics (and prevent reviving old threads). For example, if set to “30 days” the system will only show similar topics from within the last 30 days. The default is 1 year. Set it to 99 years if you want to effectively disable this feature.',
	'PST_YEARS'			=> 'Years',
	'PST_MONTHS'		=> 'Months',
	'PST_WEEKS'			=> 'Weeks',
	'PST_DAYS'			=> 'Days',
	'PST_CACHE'			=> 'Similar Topics cache length',
	'PST_CACHE_EXPLAIN'	=> 'Cached similar topics will expire after this time, in seconds. Set to 0 if you want to disable the similar topics cache.',
	'PST_SENSE'			=> 'Search sensitivity',
	'PST_SENSE_EXPLAIN'	=> 'Set the search sensitivity to a value between 1 and 10. Use a lower number if you are not seeing any similar topics. Recommended settings: For “phpbb_topics” database tables using InnoDB use 1; for MyISAM use 5.',
	'PST_LEGEND3'		=> 'Forum settings',
	'PST_NOSHOW_LIST'	=> 'Do Not Display In',
	'PST_NOSHOW_TITLE'	=> 'Do not display similar topics in',
	'PST_IGNORE_SEARCH'	=> 'Do Not Search In',
	'PST_IGNORE_TITLE'	=> 'Do not search for similar topics in',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Advanced',
	'PST_ADVANCED_TITLE'=> 'Click to set up advanced similar topic settings for',
	'PST_ADVANCED_EXP'	=> 'Here you can select specific forums to pull similar topics from. Only similar topics found in the forums you select here will be displayed in <strong>%s</strong>.<br /><br />Do not select any forums if you want similar topics from all searchable forums to be displayed in this forum.<br /><br />Select multiple forums by holding <samp>CTRL</samp> (or <samp>&#8984;CMD</samp> on Mac) and clicking.',
	'PST_ADVANCED_FORUM'=> 'Advanced forum settings',
	'PST_DESELECT_ALL'	=> 'Deselect all',
	'PST_LEGEND4'		=> 'Optional settings',
	'PST_WORDS'			=> 'Special words to ignore',
	'PST_WORDS_EXPLAIN'	=> 'Add special words unique to your forum that should be ignored when finding similar topics. (Note: Words that are currently regarded as common in your language are already ignored by default.) Separate each word with a space. Case insensitive.',
	'PST_SAVED'			=> 'Similar Topics settings updated',
	'PST_FORUM_INFO'	=> '“Do Not Display In”: Will not show similar topics in the selected forums.<br />“Do Not Search In” : Will not search for similar topics in the selected forums.',
	'PST_NO_COMPAT'		=> 'Similar Topics is not compatible with your forum. Similar Topics will only run on a MySQL or PostgreSQL database.',
	'PST_ERR_CONFIG'	=> 'Too many forums were marked in the list of forums. Please try again with a smaller selection.',
));
