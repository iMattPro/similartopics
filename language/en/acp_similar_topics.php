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
	'PST_EXPLAIN'		=> 'Help visitors discover similar topics while reading a topic or writing a new one. Choose where similar topics appear and which forums they come from.',
	'PST_FEATURES'		=> 'Choose where similar topics appear',
	'PST_FEATURES_EXPLAIN' => 'Turn each experience on or off for the whole board.',
	'PST_LEGEND1'		=> 'General settings',
	'PST_ENABLE'		=> 'Display similar topics',
	'PST_ENABLE_EXPLAIN'=> 'Show similar topics below topic pages.',
	'PST_LEGEND2'		=> 'Load settings',
	'PST_LIMIT'			=> 'Number of similar topics to display',
	'PST_LIMIT_EXPLAIN'	=> 'Maximum number of similar topics shown at once. Recommended: 5.',
	'PST_TIME'			=> 'Search period',
	'PST_TIME_EXPLAIN'	=> 'Only include topics newer than this. Enter 0 for no age limit.',
	'PST_TIME_UNIT'		=> 'Search period unit',
	'PST_YEARS'			=> 'Years',
	'PST_MONTHS'		=> 'Months',
	'PST_WEEKS'			=> 'Weeks',
	'PST_DAYS'			=> 'Days',
	'PST_CACHE'			=> 'Similar topics cache length',
	'PST_CACHE_EXPLAIN'	=> 'Reuse similar topic results to reduce database work. Longer durations improve performance but may delay updated results.',
	'PST_CACHE_OFF'		=> 'Off',
	'PST_CACHE_5_MINUTES' => '5 minutes',
	'PST_CACHE_15_MINUTES' => '15 minutes',
	'PST_CACHE_30_MINUTES' => '30 minutes',
	'PST_CACHE_1_HOUR'	=> '1 hour',
	'PST_CACHE_2_HOURS'	=> '2 hours',
	'PST_CACHE_4_HOURS'	=> '4 hours',
	'PST_CACHE_8_HOURS'	=> '8 hours',
	'PST_CACHE_12_HOURS' => '12 hours',
	'PST_CACHE_24_HOURS' => '24 hours',
	'PST_CACHE_CUSTOM'	=> 'Custom: %d seconds',
	'PST_DYNAMIC'		=> 'Display dynamic similar topics',
	'PST_DYNAMIC_EXPLAIN'=> 'Show similar topics while someone types a new topic title.',
	'PST_SENSE'			=> 'Search sensitivity',
	'PST_SENSE_EXPLAIN'	=> 'For MySQL or Postgres databases, you can set the search sensitivity to a value between 1 and 10. Use a lower number if you are not seeing any similar topics. Recommended setting: %d',
	'PST_LEGEND3'		=> 'Forum settings',
	'PST_NOSHOW_LIST'	=> 'Do Not Display In',
	'PST_NOSHOW_TITLE'	=> 'Do not display similar topics in',
	'PST_IGNORE_SEARCH'	=> 'Do Not Search In',
	'PST_IGNORE_TITLE'	=> 'Do not search for similar topics in',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Advanced',
	'PST_ADVANCED_TITLE'=> 'Click to set up advanced similar topic settings for',
	'PST_ADVANCED_EXP'	=> 'Here you can select specific forums to pull similar topics from. Only similar topics found in the forums you select here will be displayed in <strong>%s</strong>.<br><br>Do not select any forums if you want similar topics from all searchable forums to be displayed in this forum.<br><br>Select multiple forums by holding <samp>CTRL</samp> (or <samp>&#8984;CMD</samp> on Mac) and clicking.',
	'PST_ADVANCED_FORUM'=> 'Advanced forum settings',
	'PST_DESELECT_ALL'	=> 'Deselect all',
	'PST_LEGEND4'		=> 'Optional settings',
	'PST_WORDS'			=> 'Special words to ignore',
	'PST_WORDS_EXPLAIN'	=> 'Add special words unique to your forum that should be ignored when finding similar topics. (Note: Words that are currently regarded as common in your language are already ignored by default.) Separate each word with a space. Case insensitive.',
	'PST_SAVED'			=> 'Precise Similar Topics settings updated',
	'PST_FORUM_INFO'	=> '“Do Not Display In”: Will not show similar topics in the selected forums.<br>“Do Not Search In” : Will not search for similar topics in the selected forums.',
	'PST_NO_COMPAT'		=> 'Precise Similar Topics is not compatible with your forum.',
	'PST_ERR_CONFIG'	=> 'Too many forums were marked in the list of forums. Please try again with a smaller selection.',
	'PST_RESULTS'		=> 'Shape the results',
	'PST_RESULTS_EXPLAIN' => 'Set how many similar topics appear and how recent those topics must be.',
	'PST_FORUM_RULES'	=> 'Decide how each forum participates',
	'PST_FORUM_RULES_EXPLAIN' => 'Each forum has two simple switches. Use “Choose sources” only when a forum needs its own search pool.',
	'PST_FORUMS_MANAGED' => 'forums',
	'PST_CUSTOM_RULES'	=> 'custom source rules',
	'PST_FILTER_FORUMS'	=> 'Find a forum…',
	'PST_NO_FORUM_MATCH' => 'No forums match that search.',
	'PST_SHOW_HERE'		=> 'Show similar topics here',
	'PST_SHOW_HERE_EXPLAIN' => 'Visitors in this forum can see similar topics.',
	'PST_SEARCHABLE'		=> 'Make this forum available as a source',
	'PST_SEARCHABLE_EXPLAIN' => 'Standard searches may find topics from this forum.',
	'PST_SEARCH_SOURCES' => 'Where this forum searches',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Use every available forum or choose a custom set.',
	'PST_CHOOSE_SOURCES' => 'Choose sources',
	'PST_SOURCE_ALL'		=> 'All available forums',
	'PST_SOURCE_CUSTOM_COUNT' => '%d selected forums',
	'PST_SOURCE_CUSTOM_ONE' => '1 selected forum',
	'PST_SOURCE_CUSTOM_MANY' => '%d selected forums',
	'PST_CHOOSE_FORUM_SOURCES' => 'Choose where similar topics come from',
	'PST_SOURCE_MODAL_EXPLAIN' => 'This choice applies only to the forum shown above. It does not change any other forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Search every forum whose “available as a source” switch is on. New forums join automatically.',
	'PST_SOURCE_CUSTOM'	=> 'Only selected forums',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Search a fixed list of forums. Best for tightly related sections.',
	'PST_FILTER_SOURCES' => 'Find a source forum…',
	'PST_SELECT_AVAILABLE' => 'Select all available',
	'PST_CLEAR'			=> 'Clear selection',
	'PST_GLOBALLY_AVAILABLE' => 'Available',
	'PST_GLOBALLY_UNAVAILABLE' => 'Not globally available',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Custom choices override the global availability switches. A selected forum can still be searched even when marked “Not globally available.”',
	'PST_SELECT_ONE_SOURCE' => 'Choose at least one forum, or use “All available forums.”',
	'PST_APPLY_SOURCES'	=> 'Use these sources',
	'PST_TUNING'		=> 'Fine-tune matching and performance',
	'PST_TUNING_EXPLAIN' => 'Defaults work for most communities. Adjust these controls only when needed.',
	'PST_READY_TO_SAVE' => 'Ready to apply your changes?',
	'PST_SAVE_EXPLAIN'	=> 'All settings on this page save together.',
	'PST_SAVE_SETTINGS' => 'Save settings',
));
