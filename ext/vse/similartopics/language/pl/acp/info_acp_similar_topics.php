<?php
/**
 *
 * info_acp_similiar_topics [Polish]
 * 
 * @package language
 * @copyright (c) 2013 Matt Friedman (Translated by liptonace http://zonewarez.pl/)
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

$lang = array_merge($lang, array(
	'PST_TITLE_ACP'		=> 'Podobne tematy',
	'PST_TITLE'			=> 'Precise Similar Topics II',
	'PST_LEGEND1'		=> 'Ustawienia ogólne',
	'PST_ENABLE'		=> 'Włącz podobne tematy',
	'PST_LEGEND2'		=> 'Załaduj ustawienia',
	'PST_LIMIT'			=> 'Liczba wyświetlanych tematów',
	'PST_LIMIT_EXPLAIN'	=> 'Tutaj możesz określić, ile podobnych tematów zostanie wyświetlonych. Domyślnie jest to 5 tematów.',
	'PST_TIME'			=> 'Szukaj w okresie',
	'PST_TIME_EXPLAIN'	=> 'Opcja ta pozwala skonfigurować wyszukiwanie podobnych tematów w danym okresie. Na przykład dla wartości "5 dni" system będzie pokazywać tylko podobne tematy z ostatnich 5 dni. Domyślnie jest to 1 rok.',	
	'PST_YEARS'			=> 'Rok',
	'PST_MONTHS'		=> 'Miesiąc',
	'PST_WEEKS'			=> 'Tydzień',
	'PST_DAYS'			=> 'Dzień',
	'PST_CACHE'			=> 'Czas trwania cache',
	'PST_CACHE_EXPLAIN'	=> 'Cache wygasa po upływie tego czasu, w sekundach. Ustaw na 0, jeśli chcesz wyłączyć cache dla podobnych tematów.',
	'PST_LEGEND3'		=> 'Ustawienia forum',
	'PST_NOSHOW_LIST'	=> 'Nie wyświetlaj w',
	'PST_NOSHOW_TITLE'	=> 'Nie wyśiwetlaj podobnych tematów w',
	'PST_IGNORE_SEARCH'	=> 'Nie szukaj w',
	'PST_IGNORE_TITLE'	=> 'Nie szukaj podobnych tematów w',
	'PST_ADVANCED'		=> 'Zaawansowane',
	'PST_ADVANCED_TITLE'=> 'Kliknij, aby ustawić zaawansowane ustawienia dla',
	'PST_ADVANCED_EXP'	=> 'Tutaj możesz wybrać konkretne fora dla wyświetlania podobnych tematów. Tylko podobne tematy znalezione na wybranych forach będą wyświetlane w <strong>%s</strong>.<br /><br />Nie należy wybierać żadnych, jeśli chcesz aby podobne tematy były wyszukiwane we wszystkich forach.',
	'PST_DESELECT_ALL'	=> 'Odznacz wszystko',
	'PST_LEGEND4'		=> 'Ustawienia dodatkowe',
	'PST_WORDS'			=> 'Specjalne słowa do zignorowania',
	'PST_WORDS_EXPLAIN'	=> 'Dodaj specjalny słów, które powinny być ignorowane podczas szukania podobne tematy. (Uwaga: Często słowa w zapytaniu języku jest domyślnie ignorowany.) Oddzielania poszczególnych słowa spacją. Sprawa nie jest wrażliwa. Maksymalnie 255 znaków.',
	'PST_SAVED'			=> 'Zaktualizowano ustawienia podobnych tematów',
	'PST_FORUM_INFO'	=> '“Nie wyświetlaj w” : Wyłącza wyświetlanie podobnych tematów w wybranym forum.<br />“Nie szukaj w” : Będzie ignorować wybrane forum, szukając podobnych tematów.',
	'PST_WARNING'		=> 'Similar Topics nie będą działać z forum. Similar Topics wymaga MySQL 4 lub MySQL 5 bazy danych.',
	'PST_LOG_MSG'		=> '<strong>Zmieniono ustawienia podobnych tematów</strong>',
));

// For permissions
$lang = array_merge($lang, array(
	'acl_u_similar_topics'	=> array('lang' => 'Może widzieć podobne tematy', 'cat' => 'misc'),
));
