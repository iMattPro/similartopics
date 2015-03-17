<?php
/**
*
* Precise Similar Topics [Polish]
* Translated by liptonace http://zonewarez.pl/
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
	'PST_EXPLAIN'		=> 'Precise Similar Topics wyświetla listę podobnych tematów na dole bieżącego tematu w stronie.',
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
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Zaawansowane',
	'PST_ADVANCED_TITLE'=> 'Kliknij, aby ustawić zaawansowane ustawienia dla',
	'PST_ADVANCED_EXP'	=> 'Tutaj możesz wybrać konkretne fora dla wyświetlania podobnych tematów. Tylko podobne tematy znalezione na wybranych forach będą wyświetlane w <strong>%s</strong>.<br /><br />Nie należy wybierać żadnych, jeśli chcesz aby podobne tematy były wyszukiwane we wszystkich forach.<br /><br />Zaznacz/odznacz wiele działów poprzez przytrzymanie klawisza <code>CTRL</code> i klikanie.',
	'PST_ADVANCED_FORUM'=> 'Zaawansowane ustawienia forum',
	'PST_DESELECT_ALL'	=> 'Odznacz wszystko',
	'PST_LEGEND4'		=> 'Ustawienia dodatkowe',
	'PST_WORDS'			=> 'Specjalne słowa do zignorowania',
	'PST_WORDS_EXPLAIN'	=> 'Dodaj specjalny słów, które powinny być ignorowane podczas szukania podobne tematy. (Uwaga: Często słowa w zapytaniu języku jest domyślnie ignorowany.) Oddzielania poszczególnych słowa spacją. Sprawa nie jest wrażliwa. Maksymalnie 255 znaków.',
	'PST_SAVED'			=> 'Zaktualizowano ustawienia podobnych tematów',
	'PST_FORUM_INFO'	=> '“Nie wyświetlaj w” : Wyłącza wyświetlanie podobnych tematów w wybranym forum.<br />“Nie szukaj w” : Będzie ignorować wybrane forum, szukając podobnych tematów.',
	'PST_NO_MYSQL'		=> 'Similar Topics nie będą działać z forum. Similar Topics wymaga MySQL 4 lub MySQL 5 bazy danych.',
	'PST_WARN_FULLTEXT'	=> 'Similar Topics nie będą działać z forum. Similar Topics używa indeksów FULLTEXT, które wymagają bazy danych MySQL 4 lub MySQL 5 i “phpbb_topics” musi być ustawiony stół do silnika składowania MyISAM (lub InnoDB jest również dozwolone w przypadku korzystania z MySQL 5.6.4 lub nowszej).<br /><br />Jeśli chcesz użyć Similar Topics, możemy bezpiecznie zaktualizować bazę danych do obsługi indeksów FULLTEXT. Wszelkie zmiany wprowadzone zostaną przywrócone, jeśli kiedykolwiek zdecydujesz się usunąć Similar Topics.',
	'PST_ADD_FULLTEXT'	=> 'Tak, włącz obsługę indeksów FULLTEXT',
	'PST_SAVE_FULLTEXT'	=> 'Baza danych została zaktualizowana. Teraz możesz cieszyć się stosując Similar Topics.',
	'PST_ERR_FULLTEXT'	=> 'Baza danych nie może być aktualizowana.',
	'PST_ERR_CONFIG'	=> 'Zbyt wiele forum zostały oznaczone w liście forach. Spróbuj ponownie z mniejszym wyborem.',
));
