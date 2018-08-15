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
	'PST_TITLE_ACP'		=> 'נושאים דומים במדויק',
	'PST_EXPLAIN'		=> 'נושאים דומים במדויק מציג רשימת נושאים דומים (קשורים) בחלק התחתון של דף הנושא הנוכחי.',
	'PST_LEGEND1'		=> 'הגדרות כלליות',
	'PST_ENABLE'		=> 'הצג נושאים דומים',
	'PST_LEGEND2'		=> 'טען הגדרות',
	'PST_LIMIT'			=> 'מספר נושאים דומים להצגה',
	'PST_LIMIT_EXPLAIN'	=> 'כאן באפשרותך להגדיר כמה נושאים דומים להציג. ברירת המחדל היא 5 נושאים.',
	'PST_TIME'			=> 'תקופת חיפוש',
	'PST_TIME_EXPLAIN'	=> 'אפשרות זו מאפשרת לך להגדיר את תקופת החיפוש של נושאים דומים. לדוגמה, אם מוגדר "5 ימים" המערכת תציג רק נושאים דומים מתוך 5 הימים האחרונים. ברירת המחדל היא שנה אחת.',
	'PST_YEARS'			=> 'שנים',
	'PST_MONTHS'		=> 'חודשים',
	'PST_WEEKS'			=> 'שבועות',
	'PST_DAYS'			=> 'ימים',
	'PST_CACHE'			=> 'אורך מטמון נושאים דומים',
	'PST_CACHE_EXPLAIN'	=> 'מטמון נושאים דומים יפוג לאחר זמן, ערך בשניות. קבע 0 אם אתה רוצה לבטל את מטמון נושאים דומים.',
	'PST_SENSE'			=> 'רגישות חיפוש',
	'PST_SENSE_EXPLAIN'	=> 'הגדר את רגישות החיפוש לערך בין 1 ל -10. השתמש במספר נמוך יותר אם אינך רואה נושאים דומים. הגדרות מומלצות: עבור טבלאות מסד נתונים “phpbb_topics” הפועלים ב- InnoDB השתמש ב- 1; עבור  MyISAM השתמש ב- 5.',
	'PST_LEGEND3'		=> 'הגדרות פורום',
	'PST_NOSHOW_LIST'	=> 'לא להציג ב',
	'PST_NOSHOW_TITLE'	=> 'לא להציג נושאים דומים ב',
	'PST_IGNORE_SEARCH'	=> 'לא לחפש בתוך',
	'PST_IGNORE_TITLE'	=> 'לא לחפש נושאים דומים בתוך',
	'PST_STANDARD'		=> 'תקן',
	'PST_ADVANCED'		=> 'מתקדם',
	'PST_ADVANCED_TITLE'=> 'לחץ כדי להגדיר הגדרות מתקדמות לנושא דומה עבור',
	'PST_ADVANCED_EXP'	=> 'כאן ניתן לבחור פורומים ספציפיים כדי למשוך נושאים דומים מ. רק נושאים דומים שנמצאו בפורומים שתבחר כאן יוצגו ב- <strong>%s</strong>. אל תבחר בכל הפורומים, אם אתה רוצה שנושאים דומים מחיפוש בכל הפורומים יוצג בפורום זה.<br /><br />בחר במספר פורומים על ידי החזקת מקש <samp>CTRL</samp> (או <samp>&#8984;CMD</samp> ב- MAC) ולחיצה.',
	'PST_ADVANCED_FORUM'=> 'הגדרות פורום מתקדמות',
	'PST_DESELECT_ALL'	=> 'הסר סימון מהכל',
	'PST_LEGEND4'		=> 'הגדרות אופציונליות',
	'PST_WORDS'			=> 'להתעלם ממילים מיוחדות',
	'PST_WORDS_EXPLAIN'	=> 'הוסף מילים מיוחדות וייחודיות בפורום שלך שיש להתעלם מהן בעת מציאת נושאים דומים. (הערה: כברירת מחדל, הוא כבר מתעלם ממילים שנחשבות נפוצות בשפה שלך.) הפרד כל מילה עם רווח. לא תלוי רישיות. מקסימון 255 תווים.',
	'PST_SAVED'			=> 'הגדרות נושאים דומים עודכנו',
	'PST_FORUM_INFO'	=> '"לא להציג ב": לא יוצגו נושאים דומים בפורומים שנבחרו.<br />"לא לחפש בתוך": לא יחפש נושאים דומים בפורומים שנבחרו.',
	'PST_NO_COMPAT'		=> 'נושאים דומים אינו תואם את הפורום שלך. נושאים דומים יפעל רק על מסד נתונים MySQL או PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'יותר מדי פורומים סומנו ברשימת הפורומים. נסה שוב עם בחירה קטנה יותר.',
));
