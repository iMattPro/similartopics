<?php
/**
*
* info_acp_similiar_topics [Swedish]
*
* @package language
* @copyright (c) 2013 Matt Friedman (Translated by Aros via phpbb.com)
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
	'PST_TITLE_ACP'		=> '“Liknande Trådar”',
	'PST_TITLE'			=> 'Precise Similar Topics',
	'PST_EXPLAIN'		=> 'Precise Similar Topics visar en lista över liknande ämnen längst ner i det aktuella ämnet sida.',
	'PST_LEGEND1'		=> 'Generella inställningar',
	'PST_ENABLE'		=> 'Aktivera Similar Topics',
	'PST_LEGEND2'		=> 'Ladda inställningar',
	'PST_LIMIT'			=> 'Antal liknande trådar som ska visas',
	'PST_LIMIT_EXPLAIN'	=> 'Här anger du antalet liknande trådar som skall visas. Förinställt värde är 5 st.',
	'PST_TIME'			=> 'Sökperiod',
	'PST_TIME_EXPLAIN'	=> 'Det här alternativet möjliggör inställning av sökperioden. Exempel, om du anger “5 dagar” kommer systemet endast att visa liknande trådar från de 5 senaste dagarna. Det förinställda värdet är 1 år.',
	'PST_YEARS'			=> 'År',
	'PST_MONTHS'		=> 'Månader',
	'PST_WEEKS'			=> 'Veckor',
	'PST_DAYS'			=> 'Dagar',
	'PST_CACHE'			=> 'Lagring av liknande trådar',
	'PST_CACHE_EXPLAIN'	=> 'Lagrade trådar upphör efter denna tid, inställt i sekunder. Ange 0 om du vill stänga av lagring av liknande trådar.',
	'PST_LEGEND3'		=> 'Foruminställningar',
	'PST_NOSHOW_LIST'	=> 'Visa inte i',
	'PST_NOSHOW_TITLE'	=> 'Visa inte liknande trådar i',
	'PST_IGNORE_SEARCH'	=> 'Sök inte i',
	'PST_IGNORE_TITLE'	=> 'Sök inte efter liknande trådar i',
	'PST_ADVANCED'		=> 'Avancerade',
	'PST_ADVANCED_TITLE'=> 'Klicka för att ställa in avancerade inställningar',
	'PST_ADVANCED_EXP'	=> 'Här kan du välja specifika kategorier att hämta liknande trådar från. Endast liknande trådar från de kategorier du väljer här kommer att visas i <strong>%s</strong>.<br /><br />Välj inga specifika kategorier om du vill visa liknande trådar från alla kategorier i detta forum.<br /><br />Använd t.ex. kombinationen <code>CTRL</code> + musklick för att markera och välja (avvälja) fler än en kategori.',
	'PST_ADVANCED_FORUM'=> 'Avancerade foruminställningar',
	'PST_DESELECT_ALL'	=> 'Avmarkera alla',
	'PST_LEGEND4'		=> 'Valfria inställningar',
	'PST_WORDS'			=> 'Särskilda ord som ska ignoreras',
	'PST_WORDS_EXPLAIN'	=> 'Lägg till särskilda ord på forumet som skall ignoreras vid sökningen av liknande trådar. (Obs: Ord som betraktas som vanliga ord är redan undantagna). Separera varje ord med ett mellanslag. Ingen hänsyn tas till versaler eller gemenser. Max. 255 tecken.',
	'PST_SAVED'			=> 'Inställningarna har uppdaterats',
	'PST_FORUM_INFO'	=> '“Visa inte i” :  Liknande trådar visas inte i valda kategorier.<br />“Sök inte i” :  Sökning efter liknande trådar sker inte i valda kategorier.',
	'PST_WARNING'		=> 'Similar Topics fungerar inte på ditt forum. Similar Topics kräver MySQL 4 eller MySQL 5. Din databas stöder inte Fulltext index. Detta innebär vanligtvis din trådar bordet inte använder MyISAM lagring motorn som krävs för detta MOD ska fungera. <a href="https://www.phpbb.com/customise/db/mod/precise_similar_topics_ii/faq/f_1116" onclick="window.open(this.href);return false;">Mer information</a>.',
	'PST_LOG_MSG'		=> '<strong>Ändrade inställningar</strong>',
));
