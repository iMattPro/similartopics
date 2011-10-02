<?php
/**
*
* acp_similiar_topics.php [Deutsch]
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
	'PST_TITLE_ACP'		=> 'Vergleichbare Themen',
	'PST_TITLE'			=> 'Präzise Vergleichbare Themen II',
	'PST_LEGEND1'		=> 'Allgemeine Einstellungen',
	'PST_ENABLE'		=> 'Aktiviere Vergleichbare Themne',
	'PST_LEGEND2'		=> 'Lade Einstellungen',
	'PST_LIMIT'			=> 'Anzahl der angezeigten Vergleichbaren Themen',
	'PST_LIMIT_EXPLAIN'	=> 'Hier kannst du einstellen, wieviel Vergleichbare Themen angezeigt werden sollen. Standard sind 5 Themen.',
	'PST_TIME'			=> 'Suchzeitraum',
	'PST_TIME_EXPLAIN'	=> 'Diese Einstellung erlaubt dir den Suchzeitraum für Vergleichbare Themen zu konfigurieren. Zum Beispiel: Wenn du “5 Tage” wählst, wird das System nur Vergleichbare Themen innerhalb des Zeitraums der letzten 5 Tage anzeigen. Standard ist 1 Jahr.',	
	'PST_YEARS'			=> 'Jahre',
	'PST_MONTHS'		=> 'Monate',
	'PST_WEEKS'			=> 'Wochen',
	'PST_DAYS'			=> 'Tage',
	'PST_CACHE'			=> 'Zeitraum der Zwischenspeicherung für Vergleichbare Themen',
	'PST_CACHE_EXPLAIN'	=> 'Zwischengespeichterte Vergleichbare Themen werden nach dieser Zeit verfallen. In Sekunden angeben. Auf 0 setzen, wenn du den Zwichenspeicher deaktivieren willst.',
	'PST_LEGEND3'		=> 'Foren',
	'PST_NOSHOW_LIST' 	=> 'Nicht anzeigen in',
	'PST_NOSHOW_TITLE'	=> 'Vergleichbare Themen nicht anzeigen in',
	'PST_IGNORE_SEARCH' => 'Nicht Suchen Nach in',
	'PST_IGNORE_TITLE'	=> 'Nicht suchen nach Vergleichbaren Themen in',
	'PST_ADVANCED'		=> 'Erweiterte Einstellungen',
	'PST_ADVANCED_TITLE'=> 'Klicken um erweiterte Einstellungen für Vergleichbare Themen vorzunehmen',
	'PST_ADVANCED_EXP'	=> 'Hier kannst du spezifische Foren auswählen aus denen Vergleichbare Themen angezeigt werden sollen. Es werden nur Vergleichbare Themen in Foren, die du die hier einstellst, angezeigt <strong>%s</strong>.<br /><br />Wählen keine Foren, wenn Vergleichbare Themen aus allen durchsuchbaren Foren in diesem Forum angezeigt werden sollen.',
	'PST_DESELECT_ALL'	=> 'Alle abwählen',
	'PST_LEGEND4'		=> 'Optionale Einstellungen',
	'PST_WORDS'			=> 'Spezielle Wörter zu ignorieren',
	'PST_WORDS_EXPLAIN'	=> 'Fügen Sie spezielle Wörter nur in Ihrem Forum, dass zu häufig und stören bei der Suche nach relevanten ähnlichen Themen kann. Separate jedes Wort mit einem Leerzeichen. Case ist nicht empfindlich. Maximal 255 Zeichen.',
	'PST_SAVED'			=> 'Einstellungen für Vergleichbare Themen aktualisiert',
	'PST_FORUM_INFO'	=> '“Nicht anzeigen in”: Wird die Anzeige von Vergleichbaren Themen in den ausgewählten Foren deaktivieren.<br />“Nicht Suchen Nach in” : Wird die ausgewählten Foren bei der Suche nach Vergleichbaren Themen ignorieren.',
	'PST_WARNING'		=> 'Vergleichbare Themen werden in diesem Forum nicht funktionieren. Vergleichbare Themen erfordert eine MySQL 4 oder MySQL 5 Datenbank.',
	'PST_LOG_MSG'		=> '<strong>Vergleichbare Themen Einstellungen geändert</strong>',

	//For UMIL Installer
	'PST_FULLTEXT_ADD'	=> 'Hinzufügen FULLTEXT Index: topic_title',
	'PST_FULLTEXT_DROP'	=> 'Entfernen FULLTEXT Index: topic_title',
));

// For permissions
$lang = array_merge($lang, array(
	'acl_u_similar_topics'    => array('lang' => 'Kann Vergleichbare Themen sehen', 'cat' => 'misc'),
));

?>