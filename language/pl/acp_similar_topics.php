<?php
/**
*
* Precise Similar Topics [Polish]
* Translated by liptonace zonewarez.pl
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
	'PST_TITLE_ACP'		=> 'Precyzyjne podobne tematy',
	'PST_EXPLAIN'		=> 'Rozszerzenie Precise Similar Topics wyświetla listę podobnych tematów na dole strony bieżącego tematu.',
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
	'PST_CACHE'			=> 'Czas trwania pamięci podręcznej',
	'PST_CACHE_EXPLAIN'	=> 'Pamięć podręczna wygasa po upływie tego czasu, w sekundach. Ustaw na 0, jeśli chcesz wyłączyć pamięć podręczną dla podobnych tematów.',
	'PST_SENSE'			=> 'Czułość wyszukiwania',
	'PST_SENSE_EXPLAIN'	=> 'Ustaw czułość wyszukiwania na wartość pomiędzy 1 do 10. Użyj niższej liczby, jeśli nie widzisz żadnych podobnych tematów. Zalecane ustawienia: Dla tabel bazy danych “phpbb_topics” używających InnoDB użyj 1; dla MyISAM użyj 5.',
	'PST_LEGEND3'		=> 'Ustawienia forum',
	'PST_NOSHOW_LIST'	=> 'Nie wyświetlaj w',
	'PST_NOSHOW_TITLE'	=> 'Nie wyświetlaj podobnych tematów w',
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
	'PST_WORDS_EXPLAIN'	=> 'Dodaj specjalne unikalne słowa, które powinny być zignorowane podczas wyszukiwania podobnych tematów. (Notka: Słowa, które są obecnie uważane za powszechne w twoim języku, są już domyślnie ignorowane.) Oddziel każde słowo spacją. Bez względu na wielkość liter.',
	'PST_SAVED'			=> 'Zaktualizowano ustawienia podobnych tematów',
	'PST_FORUM_INFO'	=> '“Nie wyświetlaj w”: Nie będzie wyświetlać podobnych tematów w wybranych forach.<br />“Nie szukaj w”: Nie będzie wyszukiwać podobnych tematów w wybranych forach.',
	'PST_NO_COMPAT'		=> 'Rozszerzenie podobne tematy nie jest kompatybilne z twoim forum. Rozszerzenie podobne tematy będzie działać tylko w bazie danych MySQL lub PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'Zbyt wiele forów zostało oznaczonych na liście forów. Proszę, spróbuj ponownie z mniejszym wyborem.',
));
