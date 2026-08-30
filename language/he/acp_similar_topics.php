<?php
/**
*
* Precise Similar Topics [Hebrew]
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
	'PST_ENABLE_EXPLAIN'=> 'הצגת נושאים דומים בדיוני הנושאים.',
	'PST_LEGEND2'		=> 'טען הגדרות',
	'PST_LIMIT'			=> 'מספר נושאים דומים להצגה',
	'PST_LIMIT_EXPLAIN'	=> 'כאן באפשרותך להגדיר כמה נושאים דומים להציג. ברירת המחדל היא 5 נושאים.',
	'PST_TIME'			=> 'תקופת חיפוש',
	'PST_TIME_EXPLAIN'	=> 'אפשרות זו מאפשרת לך להגדיר את תקופת החיפוש של נושאים דומים. לדוגמה, אם מוגדר "5 ימים" המערכת תציג רק נושאים דומים מתוך 5 הימים האחרונים. ברירת המחדל היא שנה אחת. הגדר ערך 0 כדי לבטל את מגבלת הזמן.',
	'PST_YEARS'			=> 'שנים',
	'PST_MONTHS'		=> 'חודשים',
	'PST_WEEKS'			=> 'שבועות',
	'PST_DAYS'			=> 'ימים',
	'PST_CACHE'			=> 'אורך מטמון נושאים דומים',
	'PST_CACHE_EXPLAIN'	=> 'מטמון נושאים דומים יפוג לאחר זמן, ערך בשניות. קבע 0 אם אתה רוצה לבטל את מטמון נושאים דומים.',
	'PST_DYNAMIC'		=> 'הצגת נושאים דומים באופן דינמי',
	'PST_DYNAMIC_EXPLAIN'=> 'הצגת נושאים דומים בזמן שהמשתמש מקליד בשדה כותרת הנושא בעת יצירת נושא חדש.',
	'PST_SENSE'			=> 'רגישות חיפוש',
	'PST_SENSE_EXPLAIN'	=> 'הגדר את רגישות החיפוש לערך בין 1 ל -10. השתמש במספר נמוך יותר אם אינך רואה נושאים דומים. הגדרות מומלצות: <samp>%d</samp>',
	'PST_LEGEND3'		=> 'הגדרות פורום',
	'PST_NOSHOW_LIST'	=> 'לא להציג ב',
	'PST_NOSHOW_TITLE'	=> 'לא להציג נושאים דומים ב',
	'PST_IGNORE_SEARCH'	=> 'לא לחפש בתוך',
	'PST_IGNORE_TITLE'	=> 'לא לחפש נושאים דומים בתוך',
	'PST_STANDARD'		=> 'תקן',
	'PST_ADVANCED'		=> 'מתקדם',
	'PST_ADVANCED_TITLE'=> 'לחץ כדי להגדיר הגדרות מתקדמות לנושא דומה עבור',
	'PST_ADVANCED_EXP'	=> 'כאן ניתן לבחור פורומים ספציפיים כדי למשוך נושאים דומים מ. רק נושאים דומים שנמצאו בפורומים שתבחר כאן יוצגו ב- <strong>%s</strong>. אל תבחר בכל הפורומים, אם אתה רוצה שנושאים דומים מחיפוש בכל הפורומים יוצג בפורום זה.<br><br>בחר במספר פורומים על ידי החזקת מקש <samp>CTRL</samp> (או <samp>&#8984;CMD</samp> ב- MAC) ולחיצה.',
	'PST_ADVANCED_FORUM'=> 'הגדרות פורום מתקדמות',
	'PST_DESELECT_ALL'	=> 'הסר סימון מהכל',
	'PST_LEGEND4'		=> 'הגדרות אופציונליות',
	'PST_WORDS'			=> 'להתעלם ממילים מיוחדות',
	'PST_WORDS_EXPLAIN'	=> 'הוסף מילים מיוחדות וייחודיות בפורום שלך שיש להתעלם מהן בעת מציאת נושאים דומים. (הערה: כברירת מחדל, הוא כבר מתעלם ממילים שנחשבות נפוצות בשפה שלך.) הפרד כל מילה עם רווח. לא תלוי רישיות.',
	'PST_SAVED'			=> 'הגדרות נושאים דומים עודכנו',
	'PST_FORUM_INFO'	=> '"לא להציג ב": לא יוצגו נושאים דומים בפורומים שנבחרו.<br>"לא לחפש בתוך": לא יחפש נושאים דומים בפורומים שנבחרו.',
	'PST_NO_COMPAT'		=> 'נושאים דומים אינו תואם את הפורום שלך. נושאים דומים יפעל רק על מסד נתונים MySQL או PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'יותר מדי פורומים סומנו ברשימת הפורומים. נסה שוב עם בחירה קטנה יותר.',
	'PST_FEATURES' => 'בחר היכן יופיעו נושאים דומים',
	'PST_FEATURES_EXPLAIN' => 'הפעל או כבה כל אפשרות עבור כל מערכת הפורומים.',
	'PST_TIME_UNIT' => 'יחידת תקופת חיפוש',
	'PST_CACHE_OFF' => 'כבוי',
	'PST_CACHE_5_MINUTES' => '5 דקות',
	'PST_CACHE_15_MINUTES' => '15 דקות',
	'PST_CACHE_30_MINUTES' => '30 דקות',
	'PST_CACHE_1_HOUR' => 'שעה אחת',
	'PST_CACHE_2_HOURS' => '2 שעות',
	'PST_CACHE_4_HOURS' => '4 שעות',
	'PST_CACHE_8_HOURS' => '8 שעות',
	'PST_CACHE_12_HOURS' => '12 שעות',
	'PST_CACHE_24_HOURS' => '24 שעות',
	'PST_CACHE_CUSTOM' => 'מותאם אישית: %d שניות',
	'PST_RESULTS' => 'עצב את התוצאות',
	'PST_RESULTS_EXPLAIN' => 'הגדר כמה נושאים דומים יופיעו ועד כמה נושאים אלה חייבים להיות עדכניים.',
	'PST_FORUM_RULES' => 'להחליט כיצד כל פורום משתתף',
	'PST_FORUM_RULES_EXPLAIN' => 'לכל פורום שני מתגים פשוטים. השתמש ב"בחר מקורות" רק כאשר פורום זקוק למאגר חיפוש משלו.',
	'PST_FORUMS_MANAGED' => 'פורומים',
	'PST_CUSTOM_RULES' => 'כללי מקור מותאמים אישית',
	'PST_FILTER_FORUMS' => 'מצא פורום...',
	'PST_NO_FORUM_MATCH' => 'אין פורומים שתואמים את החיפוש הזה.',
	'PST_SHOW_HERE' => 'הצג נושאים דומים כאן',
	'PST_SHOW_HERE_EXPLAIN' => 'מבקרים בפורום זה יכולים לראות נושאים דומים.',
	'PST_SEARCHABLE' => 'הפוך את הפורום הזה לזמין כמקור',
	'PST_SEARCHABLE_EXPLAIN' => 'חיפושים רגילים עשויים למצוא נושאים מהפורום הזה.',
	'PST_SEARCH_SOURCES' => 'איפה הפורום הזה מחפש',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'השתמש בכל פורום זמין או בחר סט מותאם אישית.',
	'PST_CHOOSE_SOURCES' => 'בחר מקורות',
	'PST_SOURCE_ALL' => 'כל הפורומים הזמינים',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d פורום נבחר',
		2 => '%d פורומים נבחרים',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'בחר מאיפה מגיעים נושאים דומים',
	'PST_SOURCE_MODAL_EXPLAIN' => 'בחירה זו חלה רק על הפורום המוצג לעיל. זה לא משנה אף פורום אחר.',
	'PST_SOURCE_ALL_EXPLAIN' => 'חפש בכל פורום שהמתג שלו "זמין כמקור" פועל. פורומים חדשים מצטרפים אוטומטית.',
	'PST_SOURCE_CUSTOM' => 'רק פורומים נבחרים',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'חפש ברשימה קבועה של פורומים. הטוב ביותר עבור קטעים הקשורים הדוק.',
	'PST_FILTER_SOURCES' => 'מצא פורום מקור...',
	'PST_SELECT_AVAILABLE' => 'בחר את כל הזמינים',
	'PST_CLEAR' => 'נקה בחירה',
	'PST_GLOBALLY_AVAILABLE' => 'זָמִין',
	'PST_GLOBALLY_UNAVAILABLE' => 'לא זמין באופן גלובלי',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'בחירות מותאמות אישית עוקפות את מתגי הזמינות הגלובלית. עדיין ניתן לחפש בפורום נבחר גם כאשר הוא מסומן "לא זמין באופן גלובלי".',
	'PST_SELECT_ONE_SOURCE' => 'בחר פורום אחד לפחות, או השתמש ב"כל הפורומים הזמינים".',
	'PST_APPLY_SOURCES' => 'השתמש במקורות אלה',
	'PST_TUNING' => 'כוונן את ההתאמה והביצועים',
	'PST_TUNING_EXPLAIN' => 'ברירת המחדל פועלת עבור רוב הקהילות. התאם את הפקדים הללו רק בעת הצורך.',
	'PST_READY_TO_SAVE' => 'מוכן להחיל את השינויים שלך?',
	'PST_SAVE_EXPLAIN' => 'כל ההגדרות בדף זה נשמרות יחד.',
	'PST_SAVE_SETTINGS' => 'שמור הגדרות',
));
