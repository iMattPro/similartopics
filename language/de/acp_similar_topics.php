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
	'PST_ENABLE_EXPLAIN'=> 'Ähnliche Themen in den Diskussionsverläufen anzeigen.',
	'PST_LEGEND2'		=> 'Lade Einstellungen',
	'PST_LIMIT'			=> 'Anzahl der angezeigten Vergleichbaren Themen',
	'PST_LIMIT_EXPLAIN'	=> 'Hier kannst du einstellen, wieviel Vergleichbare Themen angezeigt werden sollen. Standard sind 5 Themen.',
	'PST_TIME'			=> 'Suchzeitraum',
	'PST_TIME_EXPLAIN'	=> 'Diese Einstellung erlaubt dir den Suchzeitraum für Vergleichbare Themen zu konfigurieren. Zum Beispiel: Wenn du “5 Tage” wählst, wird das System nur Vergleichbare Themen innerhalb des Zeitraums der letzten 5 Tage anzeigen. Standard ist 1 Jahr. Stelle den Wert auf 0, wenn du keine zeitliche Begrenzung möchtest.',
	'PST_YEARS'			=> 'Jahre',
	'PST_MONTHS'		=> 'Monate',
	'PST_WEEKS'			=> 'Wochen',
	'PST_DAYS'			=> 'Tage',
	'PST_CACHE'			=> 'Zeitraum der Zwischenspeicherung für Vergleichbare Themen',
	'PST_CACHE_EXPLAIN'	=> 'Zwischengespeichterte Vergleichbare Themen werden nach dieser Zeit verfallen. In Sekunden angeben. Auf 0 setzen, wenn du den Zwichenspeicher deaktivieren willst.',
	'PST_DYNAMIC'		=> 'Dynamische ähnliche Themen anzeigen',
	'PST_DYNAMIC_EXPLAIN'=> 'Beim Erstellen neuer Themen ähnliche Themen anzeigen, während Benutzer den Thementitel eingeben.',
	'PST_SENSE'			=> 'Suchempfindlichkeit',
	'PST_SENSE_EXPLAIN'	=> 'Für MySQL- oder Postgres-Datenbanken kann die Suchempfindlichkeit auf einen Wert zwischen 1 und 10 eingestellt werden. Verwende einen niedrigeren Wert, wenn keine ähnlichen Themen angezeigt werden. Empfohlene Einstellung: %d',
	'PST_LEGEND3'		=> 'Foren',
	'PST_NOSHOW_LIST'	=> 'Nicht anzeigen in',
	'PST_NOSHOW_TITLE'	=> 'Vergleichbare Themen nicht anzeigen in',
	'PST_IGNORE_SEARCH'	=> 'Nicht Suchen Nach in',
	'PST_IGNORE_TITLE'	=> 'Nicht suchen nach Vergleichbaren Themen in',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Erweiterte Einstellungen',
	'PST_ADVANCED_TITLE'=> 'Klicken um erweiterte Einstellungen für Vergleichbare Themen vorzunehmen',
	'PST_ADVANCED_EXP'	=> 'Hier kannst du spezifische Foren auswählen aus denen Vergleichbare Themen angezeigt werden sollen. Es werden nur Vergleichbare Themen in Foren, die du die hier einstellst, angezeigt <strong>%s</strong>.<br><br>Wählen keine Foren, wenn Vergleichbare Themen aus allen durchsuchbaren Foren in diesem Forum angezeigt werden sollen.<br><br>Wähle mehrere Foren aus/ab, indem du beim Klicken die <code>Strg</code>-Taste drückst.',
	'PST_ADVANCED_FORUM'=> 'Erweiterte Foren Einstellungen',
	'PST_DESELECT_ALL'	=> 'Alle abwählen',
	'PST_LEGEND4'		=> 'Optionale Einstellungen',
	'PST_WORDS'			=> 'Spezielle Wörter zu ignorieren',
	'PST_WORDS_EXPLAIN'	=> 'Füge Wörter hinzu, die speziell in deinem Forum häufig vorkommen und deshalb ignoriert werden sollen. (Hinweis: Wörter, die derzeit in deiner Sprache als häufig angesehen werden, werden bereits standardmäßig ignoriert.) Trenne die Worte mit Leerzeichen, Groß-/Kleinschreibung wird ignoriert.',
	'PST_SAVED'			=> 'Einstellungen für Vergleichbare Themen aktualisiert',
	'PST_FORUM_INFO'	=> '“Nicht anzeigen in”: Wird die Anzeige von Vergleichbaren Themen in den ausgewählten Foren deaktivieren.<br>“Nicht Suchen Nach in” : Wird die ausgewählten Foren bei der Suche nach Vergleichbaren Themen ignorieren.',
	'PST_NO_COMPAT'		=> 'Vergleichbare Themen werden in diesem Forum nicht funktionieren. Vergleichbare Themen erfordert eine MySQL 4 oder MySQL 5 oder PostgreSQL Datenbank.',
	'PST_JAVASCRIPT_REQUIRED' => 'JavaScript ist erforderlich, um Foreneinstellungen und Quellenauswahlen auf dieser Seite zu bearbeiten. Aktivieren Sie JavaScript, bevor Sie diese Einstellungen ändern.',
	'PST_ERR_CONFIG'	=> 'Zu viele Foren wurden in die Liste der Foren markiert. Bitte versuchen Sie es mit einer kleineren Auswahl.',
	'PST_FEATURES' => 'Wählen Sie aus, wo ähnliche Themen angezeigt werden',
	'PST_FEATURES_EXPLAIN' => 'Schalten Sie jede Option für das gesamte Board ein oder aus.',
	'PST_TIME_UNIT' => 'Suchzeitraumeinheit',
	'PST_CACHE_OFF' => 'Aus',
	'PST_CACHE_5_MINUTES' => '5 Minuten',
	'PST_CACHE_15_MINUTES' => '15 Minuten',
	'PST_CACHE_30_MINUTES' => '30 Minuten',
	'PST_CACHE_1_HOUR' => '1 Stunde',
	'PST_CACHE_2_HOURS' => '2 Stunden',
	'PST_CACHE_4_HOURS' => '4 Stunden',
	'PST_CACHE_8_HOURS' => '8 Stunden',
	'PST_CACHE_12_HOURS' => '12 Stunden',
	'PST_CACHE_24_HOURS' => '24 Stunden',
	'PST_CACHE_CUSTOM' => 'Benutzerdefiniert: %d Sekunden',
	'PST_RESULTS' => 'Gestalten Sie die Ergebnisse',
	'PST_RESULTS_EXPLAIN' => 'Legen Sie fest, wie viele ähnliche Themen angezeigt werden und wie aktuell diese Themen sein müssen.',
	'PST_FORUM_RULES' => 'Entscheiden Sie, wie sich jedes Forum beteiligt',
	'PST_FORUM_RULES_EXPLAIN' => 'Jedes Forum verfügt über zwei einfache Schalter. Verwenden Sie „Quellen auswählen“ nur, wenn ein Forum einen eigenen Suchpool benötigt.',
	'PST_FORUMS_MANAGED' => 'Foren',
	'PST_CUSTOM_RULES' => 'benutzerdefinierte Quelleinstellungen',
	'PST_FILTER_FORUMS' => 'Finden Sie ein Forum…',
	'PST_NO_FORUM_MATCH' => 'Zu dieser Suche passen keine Foren.',
	'PST_SHOW_HERE' => 'Ähnliche Themen hier anzeigen',
	'PST_SHOW_HERE_EXPLAIN' => 'Besucher in diesem Forum können ähnliche Themen sehen.',
	'PST_SEARCHABLE' => 'Stellen Sie dieses Forum als Quelle zur Verfügung',
	'PST_SEARCHABLE_EXPLAIN' => 'Standardsuchen können Themen aus diesem Forum finden.',
	'PST_SEARCH_SOURCES' => 'Wo dieses Forum sucht',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Nutzen Sie jedes verfügbare Forum oder wählen Sie ein benutzerdefiniertes Set.',
	'PST_CHOOSE_SOURCES' => 'Wählen Sie Quellen',
	'PST_SOURCE_ALL' => 'Alle verfügbaren Foren',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d ausgewähltes Forum',
		2 => '%d ausgewählte Foren',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Wählen Sie aus, woher ähnliche Themen kommen',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Diese Auswahl gilt nur für das oben gezeigte Forum. Es ändert kein anderes Forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Durchsuchen Sie jedes Forum, dessen Schalter „Als Quelle verfügbar“ aktiviert ist. Neue Foren treten automatisch bei.',
	'PST_SOURCE_CUSTOM' => 'Nur ausgewählte Foren',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Durchsuchen Sie eine feste Liste von Foren. Am besten für eng miteinander verbundene Abschnitte.',
	'PST_FILTER_SOURCES' => 'Finden Sie ein Quellenforum…',
	'PST_SELECT_AVAILABLE' => 'Wählen Sie alle verfügbaren aus',
	'PST_CLEAR' => 'Klare Auswahl',
	'PST_GLOBALLY_AVAILABLE' => 'Verfügbar',
	'PST_GLOBALLY_UNAVAILABLE' => 'Global nicht verfügbar',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Benutzerdefinierte Optionen haben Vorrang vor den globalen Verfügbarkeitsschaltern. Ein ausgewähltes Forum kann auch dann noch durchsucht werden, wenn es als „Global nicht verfügbar“ markiert ist.',
	'PST_SELECT_ONE_SOURCE' => 'Wählen Sie mindestens ein Forum oder verwenden Sie „Alle verfügbaren Foren“.',
	'PST_APPLY_SOURCES' => 'Nutzen Sie diese Quellen',
	'PST_TUNING' => 'Feinabstimmung und Leistung',
	'PST_TUNING_EXPLAIN' => 'Die Standardeinstellungen funktionieren für die meisten Communities. Passen Sie diese Steuerelemente nur bei Bedarf an.',
	'PST_READY_TO_SAVE' => 'Sind Sie bereit, Ihre Änderungen zu übernehmen?',
	'PST_SAVE_EXPLAIN' => 'Alle Einstellungen auf dieser Seite werden zusammen gespeichert.',
	'PST_SAVE_SETTINGS' => 'Einstellungen speichern',
));
