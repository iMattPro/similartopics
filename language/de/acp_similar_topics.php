<?php
/**
*
* Precise Similar Topics [Deutsch]
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
	'PST_TITLE_ACP'		=> 'Präzise Vergleichbare Themen',
	'PST_EXPLAIN'		=> 'Präzise Vergleichbare Themen zeigt eine Liste von ähnlichen Themen am unteren Rand des aktuellen Themas der Seite.',
	'PST_LEGEND1'		=> 'Allgemeine Einstellungen',
	'PST_ENABLE'		=> 'Aktiviere Vergleichbare Themen',
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
	'PST_NOSHOW_LIST'	=> 'Nicht anzeigen in',
	'PST_NOSHOW_TITLE'	=> 'Vergleichbare Themen nicht anzeigen in',
	'PST_IGNORE_SEARCH'	=> 'Nicht Suchen Nach in',
	'PST_IGNORE_TITLE'	=> 'Nicht suchen nach Vergleichbaren Themen in',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Erweiterte Einstellungen',
	'PST_ADVANCED_TITLE'=> 'Klicken um erweiterte Einstellungen für Vergleichbare Themen vorzunehmen',
	'PST_ADVANCED_EXP'	=> 'Hier kannst du spezifische Foren auswählen aus denen Vergleichbare Themen angezeigt werden sollen. Es werden nur Vergleichbare Themen in Foren, die du die hier einstellst, angezeigt <strong>%s</strong>.<br /><br />Wählen keine Foren, wenn Vergleichbare Themen aus allen durchsuchbaren Foren in diesem Forum angezeigt werden sollen.<br /><br />Wähle mehrere Foren aus/ab, indem du beim Klicken die <code>Strg</code>-Taste drückst.',
	'PST_ADVANCED_FORUM'=> 'Erweiterte Foren Einstellungen',
	'PST_DESELECT_ALL'	=> 'Alle abwählen',
	'PST_LEGEND4'		=> 'Optionale Einstellungen',
	'PST_WORDS'			=> 'Spezielle Wörter zu ignorieren',
	'PST_WORDS_EXPLAIN'	=> 'Füge Wörter hinzu, die speziell in deinem Forum häufig vorkommen und deshalb ignoriert werden sollen. (Hinweis: Wörter, die derzeit in deiner Sprache als häufig angesehen werden, werden bereits standardmäßig ignoriert.) Trenne die Worte mit Leerzeichen, Groß-/Kleinschreibung wird ignoriert, maximal 255 Zeichen.',
	'PST_SAVED'			=> 'Einstellungen für Vergleichbare Themen aktualisiert',
	'PST_FORUM_INFO'	=> '“Nicht anzeigen in”: Wird die Anzeige von Vergleichbaren Themen in den ausgewählten Foren deaktivieren.<br />“Nicht Suchen Nach in” : Wird die ausgewählten Foren bei der Suche nach Vergleichbaren Themen ignorieren.',
	'PST_NO_MYSQL'		=> 'Vergleichbare Themen werden in diesem Forum nicht funktionieren. Vergleichbare Themen erfordert eine MySQL 4 oder MySQL 5 Datenbank.',
	'PST_WARN_FULLTEXT'	=> 'Vergleichbare Themen werden in diesem Forum nicht funktionieren. Vergleichbare Themen verwendet FULLTEXT-Indizes, die eine MySQL 4 oder MySQL 5 Datenbank und die phpbb_topics-Tabelle benötigen, müssen sich an die MyISAM-Speicher-Engine gesetzt werden (oder InnoDB ist auch erlaubt, wenn sie mit MySQL 5.6.4 oder neuer verwendet wird). <br /> <br /> Wenn Sie Vergleichbare Themen verwenden möchten, können wir sicher aktualisieren Sie Ihre Datenbank, um FULLTEXT-Indizes unterstützt. Alle vorgenommenen Änderungen werden rückgängig gemacht werden, wenn Sie jemals entscheiden Vergleichbare Themen zu entfernen.',
	'PST_ADD_FULLTEXT'	=> 'Ja, die Unterstützung für FULLTEXT-Indizes',
	'PST_SAVE_FULLTEXT'	=> 'Ihre Datenbank wurde aktualisiert. Jetzt können Sie mit Vergleichbare Themen.',
	'PST_ERR_FULLTEXT'	=> 'Die Datenbank konnte nicht aktualisiert werden.',
	'PST_ERR_CONFIG'	=> 'Zu viele Foren wurden in die Liste der Foren markiert. Bitte versuchen Sie es mit einer kleineren Auswahl.',
));
