<?php
/**
*
* Precise Similar Topics [Dutch]
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
	'PST_TITLE_ACP'		=> 'Precieze vergelijkbare onderwerpen',
	'PST_EXPLAIN'		=> 'Precise Similar Topics toont een lijst van soortgelijke onderwerpen aan de onderkant van de pagina van het huidige onderwerpen.',
	'PST_LEGEND1'		=> 'Algemene instellingen',
	'PST_ENABLE'		=> 'Vergelijkbare onderwerpen inschakelen',
	'PST_ENABLE_EXPLAIN'=> 'Show similar topics in topic discussion threads.',
	'PST_LEGEND2'		=> 'Laad instellingen',
	'PST_LIMIT'			=> 'Aantal vergelijkbare onderwerpen weer te geven',
	'PST_LIMIT_EXPLAIN'	=> 'Hier kunt u opgeven hoeveel soortgelijke onderwerpen weer te laten geven. Standaard is dit 5 onderwerpen.',
	'PST_TIME'			=> 'Zoek periode',
	'PST_TIME_EXPLAIN'	=> 'Met deze optie kunt u de zoek periode van vergelijkbare onderwerpen configureren. Bijvoorbeeld, indien ingesteld op "5 dagen" het systeem zal dan alleen soortgelijke onderwerpen van de laatste 5 dagen laten zien. De standaardwaarde is 1 jaar.',
	'PST_YEARS'			=> 'Jaren',
	'PST_MONTHS'		=> 'Maanden',
	'PST_WEEKS'			=> 'Weken',
	'PST_DAYS'			=> 'Dagen',
	'PST_CACHE'			=> 'Cache lengte vergelijkbare onderwerpen',
	'PST_CACHE_EXPLAIN'	=> 'Cache vergelijkbare onderwerpen verloopt na deze tijd in seconden. Stel in op 0 als u dit wilt uitschakelen.',
	'PST_DYNAMIC'		=> 'Display dynamic similar topics',
	'PST_DYNAMIC_EXPLAIN'=> 'Show similar topics as users type in the topic title field when creating new topics.',
	'PST_SENSE'			=> 'Search sensitivity',
	'PST_SENSE_EXPLAIN'	=> 'For MySQL or Postgres databases, you can set the search sensitivity to a value between 1 and 10. Use a lower number if you are not seeing any similar topics. Recommended setting: %d',
	'PST_LEGEND3'		=> 'Forum instellingen',
	'PST_NOSHOW_LIST'	=> 'Niet weergeven in',
	'PST_NOSHOW_TITLE'	=> 'Vergelijkbare Onderwerpen Niet weergeven in',
	'PST_IGNORE_SEARCH'	=> 'Niet zoeken In',
	'PST_IGNORE_TITLE'	=> 'Niet zoeken naar vergelijkbare onderwerpen in',
	'PST_STANDARD'		=> 'Standaard',
	'PST_ADVANCED'		=> 'Geavanceerd',
	'PST_ADVANCED_TITLE'=> 'Klik om Geavanceerde vergelijkbare onderwerpen in te stellen voor',
	'PST_ADVANCED_EXP'	=> 'Hier kunt u specifieke forums selecteren om de soortgelijke onderwerpen uit te halen. Alleen vergelijkbare onderwerpen gevonden in de forums die u hier selecteert worden weergegeven in <strong>%s</strong>.<br><br>Selecteer helemaal geen forum als u wilt dat vergelijkbare onderwerpen uit alle doorzoekbare forums worden weergegeven in dit forum.<br><br>Selecteer/Deselecteer meerdere forums door <code>CTRL</code> ingedrukt te houden en forums aan te klikken.',
	'PST_ADVANCED_FORUM'=> 'Geavanceerd forum instellingen',
	'PST_DESELECT_ALL'	=> 'Selectie opheffen',
	'PST_LEGEND4'		=> 'Optionele instellingen',
	'PST_WORDS'			=> 'Speciale woorden om te negeren',
	'PST_WORDS_EXPLAIN'	=> 'Voeg speciale woorden toe die uniek zijn aan uw forum en moeten genegeerd worden bij het vinden van vergelijkbare onderwerpen. (Opgelet: woorden die momenteel als veelvoorkomend worden beschouwd in uw taal, worden reeds standaard genegeerd.) Scheid elk woord met een spatie. Niet hoofdlettergevoelig.',
	'PST_SAVED'			=> 'Vergelijkbare onderwerpen instellingen bijgewerkt',
	'PST_FORUM_INFO'	=> '“Niet weergeven in”: Soortgelijke onderwerpen worden niet weergegeven in de geselecteerde forums.<br>“Niet zoeken in” : Zal niet zoeken in de geselecteerde forums voor vergelijkbare onderwerpen.',
	'PST_NO_COMPAT'		=> 'Vergelijkbare onderwerpen zal niet werken op uw forum. Vergelijkbare onderwerpen vereist een PostgreSQL, MySQL 4 of 5 MySQL database.',
	'PST_ERR_CONFIG'	=> 'Te veel fora werden gemarkeerd in de lijst van forums. Probeer het opnieuw met een kleinere selectie.',
));
