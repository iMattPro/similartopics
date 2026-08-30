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
	'PST_ENABLE_EXPLAIN'=> 'Visa liknande ämnen i ämnesdiskussioner.',
	'PST_LEGEND2'		=> 'Ladda inställningar',
	'PST_LIMIT'			=> 'Antal liknande trådar som ska visas',
	'PST_LIMIT_EXPLAIN'	=> 'Här anger du antalet liknande trådar som skall visas. Förinställt värde är 5 st.',
	'PST_TIME'			=> 'Sökperiod',
	'PST_TIME_EXPLAIN'	=> 'Det här alternativet möjliggör inställning av sökperioden. Exempel, om du anger “5 dagar” kommer systemet endast att visa liknande trådar från de 5 senaste dagarna. Det förinställda värdet är 1 år. Ange 0 för ingen tidsgräns.',
	'PST_YEARS'			=> 'År',
	'PST_MONTHS'		=> 'Månader',
	'PST_WEEKS'			=> 'Veckor',
	'PST_DAYS'			=> 'Dagar',
	'PST_CACHE'			=> 'Lagring av liknande trådar',
	'PST_CACHE_EXPLAIN'	=> 'Lagrade trådar upphör efter denna tid, inställt i sekunder. Ange 0 om du vill stänga av lagring av liknande trådar.',
	'PST_DYNAMIC'		=> 'Visa liknande ämnen dynamiskt',
	'PST_DYNAMIC_EXPLAIN'=> 'Visa liknande ämnen medan användare skriver ämnesrubriken när de skapar nya ämnen.',
	'PST_SENSE'			=> 'Sökkänslighet',
	'PST_SENSE_EXPLAIN'	=> 'För MySQL- eller Postgres-databaser kan sökkänsligheten ställas in på ett värde mellan 1 och 10. Använd ett lägre tal om inga liknande ämnen visas. Rekommenderad inställning: %d',
	'PST_LEGEND3'		=> 'Foruminställningar',
	'PST_NOSHOW_LIST'	=> 'Visa inte i',
	'PST_NOSHOW_TITLE'	=> 'Visa inte liknande trådar i',
	'PST_IGNORE_SEARCH'	=> 'Sök inte i',
	'PST_IGNORE_TITLE'	=> 'Sök inte efter liknande trådar i',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Avancerade',
	'PST_ADVANCED_TITLE'=> 'Klicka för att ställa in avancerade inställningar',
	'PST_ADVANCED_EXP'	=> 'Här kan du välja specifika kategorier att hämta liknande trådar från. Endast liknande trådar från de kategorier du väljer här kommer att visas i <strong>%s</strong>.<br><br>Välj inga specifika kategorier om du vill visa liknande trådar från alla kategorier i detta forum.<br><br>Använd t.ex. kombinationen <code>CTRL</code> + musklick för att markera och välja (avvälja) fler än en kategori.',
	'PST_ADVANCED_FORUM'=> 'Avancerade foruminställningar',
	'PST_DESELECT_ALL'	=> 'Avmarkera alla',
	'PST_LEGEND4'		=> 'Valfria inställningar',
	'PST_WORDS'			=> 'Särskilda ord som ska ignoreras',
	'PST_WORDS_EXPLAIN'	=> 'Lägg till särskilda ord på forumet som skall ignoreras vid sökningen av liknande trådar. (Obs: Ord som betraktas som vanliga ord är redan undantagna). Separera varje ord med ett mellanslag. Ingen hänsyn tas till versaler eller gemenser.',
	'PST_SAVED'			=> 'Inställningarna har uppdaterats',
	'PST_FORUM_INFO'	=> '“Visa inte i” :  Liknande trådar visas inte i valda kategorier.<br>“Sök inte i” :  Sökning efter liknande trådar sker inte i valda kategorier.',
	'PST_NO_COMPAT'		=> 'Similar Topics fungerar inte på ditt forum. Similar Topics kräver MySQL 4 eller MySQL 5 eller PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'Alltför många forum märktes i listan över forum. Försök igen med ett mindre urval.',
	'PST_FEATURES' => 'Välj var liknande ämnen visas',
	'PST_FEATURES_EXPLAIN' => 'Aktivera eller inaktivera varje alternativ för hela forumet.',
	'PST_TIME_UNIT' => 'Sökperiodenhet',
	'PST_CACHE_OFF' => 'Av',
	'PST_CACHE_5_MINUTES' => '5 minuter',
	'PST_CACHE_15_MINUTES' => '15 minuter',
	'PST_CACHE_30_MINUTES' => '30 minuter',
	'PST_CACHE_1_HOUR' => '1 timme',
	'PST_CACHE_2_HOURS' => '2 timmar',
	'PST_CACHE_4_HOURS' => '4 timmar',
	'PST_CACHE_8_HOURS' => '8 timmar',
	'PST_CACHE_12_HOURS' => '12 timmar',
	'PST_CACHE_24_HOURS' => '24 timmar',
	'PST_CACHE_CUSTOM' => 'Anpassad: %d sekunder',
	'PST_RESULTS' => 'Forma resultaten',
	'PST_RESULTS_EXPLAIN' => 'Ställ in hur många liknande ämnen som ska visas och hur nya dessa ämnen måste vara.',
	'PST_FORUM_RULES' => 'Bestäm hur varje forum deltar',
	'PST_FORUM_RULES_EXPLAIN' => 'Varje forum har två enkla växlar. Använd "Välj källor" endast när ett forum behöver en egen sökpool.',
	'PST_FORUMS_MANAGED' => 'forum',
	'PST_CUSTOM_RULES' => 'anpassade källregler',
	'PST_FILTER_FORUMS' => 'Hitta ett forum...',
	'PST_NO_FORUM_MATCH' => 'Inga forum matchar den sökningen.',
	'PST_SHOW_HERE' => 'Visa liknande ämnen här',
	'PST_SHOW_HERE_EXPLAIN' => 'Besökare i detta forum kan se liknande ämnen.',
	'PST_SEARCHABLE' => 'Gör detta forum tillgängligt som en källa',
	'PST_SEARCHABLE_EXPLAIN' => 'Standardsökningar kan hitta ämnen från detta forum.',
	'PST_SEARCH_SOURCES' => 'Där detta forum söker',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Använd alla tillgängliga forum eller välj en anpassad uppsättning.',
	'PST_CHOOSE_SOURCES' => 'Välj källor',
	'PST_SOURCE_ALL' => 'Alla tillgängliga forum',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d valt forum',
		2 => '%d valda forum',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Välj var liknande ämnen kommer ifrån',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Detta val gäller endast forumet som visas ovan. Det förändrar inte något annat forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Sök i alla forum vars omkopplare för "tillgänglig som källa" är på. Nya forum går med automatiskt.',
	'PST_SOURCE_CUSTOM' => 'Endast utvalda forum',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Sök i en fast lista med forum. Bäst för tätt relaterade sektioner.',
	'PST_FILTER_SOURCES' => 'Hitta ett källforum...',
	'PST_SELECT_AVAILABLE' => 'Välj alla tillgängliga',
	'PST_CLEAR' => 'Rensa val',
	'PST_GLOBALLY_AVAILABLE' => 'Tillgänglig',
	'PST_GLOBALLY_UNAVAILABLE' => 'Inte globalt tillgänglig',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Anpassade val åsidosätter de globala tillgänglighetsomkopplarna. Ett valt forum kan fortfarande sökas även när det är markerat som "Inte globalt tillgänglig".',
	'PST_SELECT_ONE_SOURCE' => 'Välj minst ett forum eller använd "Alla tillgängliga forum".',
	'PST_APPLY_SOURCES' => 'Använd dessa källor',
	'PST_TUNING' => 'Finjustera matchning och prestanda',
	'PST_TUNING_EXPLAIN' => 'Standardinställningarna fungerar för de flesta gemenskaper. Justera dessa kontroller endast när det behövs.',
	'PST_READY_TO_SAVE' => 'Är du redo att tillämpa dina ändringar?',
	'PST_SAVE_EXPLAIN' => 'Alla inställningar på denna sida sparas tillsammans.',
	'PST_SAVE_SETTINGS' => 'Spara inställningar',
));
