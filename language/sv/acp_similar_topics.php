<?php
/**
*
* Precise Similar Topics [Swedish]
* Translated by Aros via phpbb.com
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
	'PST_STANDARD'		=> 'Standard',
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
	'PST_NO_MYSQL'		=> 'Similar Topics fungerar inte på ditt forum. Similar Topics kräver MySQL 4 eller MySQL 5.',
	'PST_WARN_FULLTEXT'	=> 'Similar Topics fungerar inte på ditt forum. Similar Topics använder FULLTEXT index som kräver en MySQL 4 eller MySQL 5 databas och “phpbb_topics” tabell måste vara inställd på MyISAM lagring motor (eller InnoDB är också tillåtet vid användning med MySQL 5.6.4 eller nyare).<br /><br />Om du vill använda Similar Topics, vi kan säkert uppdatera din databas för att stödja FULLTEXT index. Alla ändringar kommer att återställas om du någon gång bestämmer dig för att ta bort Similar Topics.',
	'PST_ADD_FULLTEXT'	=> 'Ja, aktivera stöd för fulltext index',
	'PST_SAVE_FULLTEXT'	=> 'Din databas har uppdaterats. Nu kan du njuta av att använda Similar Topics.',
	'PST_ERR_FULLTEXT'	=> 'Din databas kunde inte uppdateras.',
	'PST_ERR_CONFIG'	=> 'Alltför många forum märktes i listan över forum. Försök igen med ett mindre urval.',
));
