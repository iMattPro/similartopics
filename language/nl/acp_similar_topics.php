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
	'PST_ENABLE_EXPLAIN'=> 'Toon vergelijkbare onderwerpen in discussies.',
	'PST_LEGEND2'		=> 'Laad instellingen',
	'PST_LIMIT'			=> 'Aantal vergelijkbare onderwerpen weer te geven',
	'PST_LIMIT_EXPLAIN'	=> 'Hier kunt u opgeven hoeveel soortgelijke onderwerpen weer te laten geven. Standaard is dit 5 onderwerpen.',
	'PST_TIME'			=> 'Zoek periode',
	'PST_TIME_EXPLAIN'	=> 'Met deze optie kunt u de zoek periode van vergelijkbare onderwerpen configureren. Bijvoorbeeld, indien ingesteld op "5 dagen" het systeem zal dan alleen soortgelijke onderwerpen van de laatste 5 dagen laten zien. De standaardwaarde is 1 jaar. Stel de waarde in op 0 voor geen tijdslimiet.',
	'PST_YEARS'			=> 'Jaren',
	'PST_MONTHS'		=> 'Maanden',
	'PST_WEEKS'			=> 'Weken',
	'PST_DAYS'			=> 'Dagen',
	'PST_CACHE'			=> 'Cache lengte vergelijkbare onderwerpen',
	'PST_CACHE_EXPLAIN'	=> 'Cache vergelijkbare onderwerpen verloopt na deze tijd in seconden. Stel in op 0 als u dit wilt uitschakelen.',
	'PST_DYNAMIC'		=> 'Dynamische vergelijkbare onderwerpen tonen',
	'PST_DYNAMIC_EXPLAIN'=> 'Toon vergelijkbare onderwerpen terwijl gebruikers bij het maken van een nieuw onderwerp de titel invoeren.',
	'PST_SENSE'			=> 'Zoekgevoeligheid',
	'PST_SENSE_EXPLAIN'	=> 'Voor MySQL- of Postgres-databases kun je de zoekgevoeligheid instellen op een waarde van 1 tot 10. Gebruik een lager getal als er geen vergelijkbare onderwerpen verschijnen. Aanbevolen instelling: %d',
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
	'PST_JAVASCRIPT_REQUIRED' => 'JavaScript is vereist om foruminstellingen en bronselecties op deze pagina te bewerken. Schakel JavaScript in voordat u deze instellingen wijzigt.',
	'PST_ERR_CONFIG'	=> 'Te veel fora werden gemarkeerd in de lijst van forums. Probeer het opnieuw met een kleinere selectie.',
	'PST_FEATURES' => 'Kies waar vergelijkbare onderwerpen verschijnen',
	'PST_FEATURES_EXPLAIN' => 'Schakel elke optie voor het hele forum in of uit.',
	'PST_TIME_UNIT' => 'Zoekperiode-eenheid',
	'PST_CACHE_OFF' => 'Uit',
	'PST_CACHE_5_MINUTES' => '5 minuten',
	'PST_CACHE_15_MINUTES' => '15 minuten',
	'PST_CACHE_30_MINUTES' => '30 minuten',
	'PST_CACHE_1_HOUR' => '1 uur',
	'PST_CACHE_2_HOURS' => '2 uur',
	'PST_CACHE_4_HOURS' => '4 uur',
	'PST_CACHE_8_HOURS' => '8 uur',
	'PST_CACHE_12_HOURS' => '12 uur',
	'PST_CACHE_24_HOURS' => '24 uur',
	'PST_CACHE_CUSTOM' => 'Aangepast: %d seconden',
	'PST_RESULTS' => 'Vorm de resultaten',
	'PST_RESULTS_EXPLAIN' => 'Stel in hoeveel vergelijkbare onderwerpen verschijnen en hoe recent deze onderwerpen moeten zijn.',
	'PST_FORUM_RULES' => 'Bepaal hoe elk forum deelneemt',
	'PST_FORUM_RULES_EXPLAIN' => 'Elk forum heeft twee eenvoudige schakelaars. Gebruik “Kies bronnen” alleen als een forum een eigen zoekpool nodig heeft.',
	'PST_FORUMS_MANAGED' => 'forums',
	'PST_CUSTOM_RULES' => 'aangepaste broninstellingen',
	'PST_FILTER_FORUMS' => 'Zoek een forum…',
	'PST_NO_FORUM_MATCH' => 'Er zijn geen forums die overeenkomen met die zoekopdracht.',
	'PST_SHOW_HERE' => 'Toon hier vergelijkbare onderwerpen',
	'PST_SHOW_HERE_EXPLAIN' => 'Bezoekers van dit forum kunnen vergelijkbare onderwerpen zien.',
	'PST_SEARCHABLE' => 'Stel dit forum beschikbaar als bron',
	'PST_SEARCHABLE_EXPLAIN' => 'Standaardzoekopdrachten kunnen onderwerpen van dit forum opleveren.',
	'PST_SEARCH_SOURCES' => 'Waar dit forum zoekt',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Gebruik elk beschikbaar forum of kies een aangepaste set.',
	'PST_CHOOSE_SOURCES' => 'Kies bronnen',
	'PST_SOURCE_ALL' => 'Alle beschikbare forums',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d geselecteerd forum',
		2 => '%d geselecteerde forums',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Kies waar soortgelijke onderwerpen vandaan komen',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Deze keuze geldt alleen voor het hierboven weergegeven forum. Het verandert niets aan een ander forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Doorzoek elk forum waarvan de schakelaar \'beschikbaar als bron\' is ingeschakeld. Nieuwe forums worden automatisch lid.',
	'PST_SOURCE_CUSTOM' => 'Alleen geselecteerde forums',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Zoek in een vaste lijst met forums. Het beste voor nauw verwante secties.',
	'PST_FILTER_SOURCES' => 'Zoek een bronforum…',
	'PST_SELECT_AVAILABLE' => 'Selecteer alles wat beschikbaar is',
	'PST_CLEAR' => 'Duidelijke selectie',
	'PST_GLOBALLY_AVAILABLE' => 'Beschikbaar',
	'PST_GLOBALLY_UNAVAILABLE' => 'Niet algemeen beschikbaar',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Aangepaste keuzes overschrijven de globale beschikbaarheidsschakelaars. Een geselecteerd forum kan nog steeds worden doorzocht, zelfs als het gemarkeerd is als \'Niet algemeen beschikbaar\'.',
	'PST_SELECT_ONE_SOURCE' => 'Kies ten minste één forum of gebruik \'Alle beschikbare forums\'.',
	'PST_APPLY_SOURCES' => 'Gebruik deze bronnen',
	'PST_TUNING' => 'Verfijn de afstemming en prestaties',
	'PST_TUNING_EXPLAIN' => 'Standaardwaarden werken voor de meeste community\'s. Pas deze bedieningselementen alleen aan als dat nodig is.',
	'PST_READY_TO_SAVE' => 'Klaar om uw wijzigingen toe te passen?',
	'PST_SAVE_EXPLAIN' => 'Alle instellingen op deze pagina worden samen opgeslagen.',
	'PST_SAVE_SETTINGS' => 'Instellingen opslaan',
));
