<?php
/**
*
* Precise Similar Topics [Polish]
* Translated by liptonace zonewarez.pl
* Translated PL by Tomasz Hetman / ToTemat i Głos Obywwateli Forum
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
	'PST_EXPLAIN'		=> 'Precise Similar Topics wyświetla listę podobnych (powiązanych) tematów. Podobne tematy mogą być wyświetlane na dole aktualnego wątku dyskusji i/lub w momencie, gdy użytkownik wpisuje tytuł nowego posta.',
	'PST_LEGEND1'		=> 'Ustawienia ogólne',
	'PST_ENABLE'		=> 'Wyświetlaj podobne tematy',
	'PST_ENABLE_EXPLAIN'=> 'Pokaż podobne tematy w wątkach dyskusyjnych.',
	'PST_LEGEND2'		=> 'Ustawienia ładowania',
	'PST_LIMIT'			=> 'Liczba wyświetlanych podobnych tematów',
	'PST_LIMIT_EXPLAIN'	=> 'Tutaj możesz zdefiniować, ile podobnych tematów ma być wyświetlanych. Domyślnie jest to 5 tematów.',
	'PST_TIME'			=> 'Okres wyszukiwania',
	'PST_TIME_EXPLAIN'	=> 'Ta opcja pozwala ograniczyć wyniki podobnych tematów do nowszych wątków (i zapobiega odświeżaniu starych dyskusji). Na przykład, jeśli ustawisz „30 dni”, system pokaże tylko podobne tematy z ostatnich 30 dni. Domyślnie jest to 1 rok. Ustaw 99 lat, jeśli chcesz skutecznie wyłączyć tę funkcję.',
	'PST_YEARS'			=> 'Lata',
	'PST_MONTHS'		=> 'Miesiące',
	'PST_WEEKS'			=> 'Tygodnie',
	'PST_DAYS'			=> 'Dni',
	'PST_CACHE'			=> 'Czas przechowywania cache podobnych tematów',
	'PST_CACHE_EXPLAIN'	=> 'Zapisane w pamięci podręcznej podobne tematy wygasną po tym czasie (w sekundach). Ustaw 0, jeśli chcesz wyłączyć cache dla podobnych tematów.',
	'PST_DYNAMIC'		=> 'Wyświetlaj dynamiczne podobne tematy',
	'PST_DYNAMIC_EXPLAIN'=> 'Pokaż podobne tematy podczas wpisywania przez użytkowników tytułu tematu podczas tworzenia nowych wątków.',
	'PST_SENSE'			=> 'Czułość wyszukiwania',
	'PST_SENSE_EXPLAIN'	=> 'W przypadku baz danych MySQL lub Postgres możesz ustawić czułość wyszukiwania na wartość od 1 do 10. Użyj niższej liczby, jeśli nie widzisz żadnych podobnych tematów. Zalecane ustawienie: %d',
	'PST_LEGEND3'		=> 'Ustawienia forów',
	'PST_NOSHOW_LIST'	=> 'Nie wyświetlaj w',
	'PST_NOSHOW_TITLE'	=> 'Nie wyświetlaj podobnych tematów w',
	'PST_IGNORE_SEARCH'	=> 'Nie szukaj w',
	'PST_IGNORE_TITLE'	=> 'Nie szukaj podobnych tematów w',
	'PST_STANDARD'		=> 'Standardowe',
	'PST_ADVANCED'		=> 'Zaawansowane',
	'PST_ADVANCED_TITLE'=> 'Kliknij, aby skonfigurować zaawansowane ustawienia podobnych tematów dla',
	'PST_ADVANCED_EXP'	=> 'Tutaj możesz wybrać konkretne fora, z których mają być pobierane podobne tematy. Tylko tematy znalezione na wybranych forach będą wyświetlane w <strong>%s</strong>.<br><br>Nie wybieraj żadnych forów, jeśli chcesz, aby w tym dziale wyświetlały się podobne tematy ze wszystkich przeszukiwalnych forów.<br><br>Wybierz wiele forów, przytrzymując klawisz <samp>CTRL</samp> (lub <samp>&#8984;CMD</samp> na komputerach Mac) i klikając.',
	'PST_ADVANCED_FORUM'=> 'Zaawansowane ustawienia forum',
	'PST_DESELECT_ALL'	=> 'Odznacz wszystko',
	'PST_LEGEND4'		=> 'Ustawienia opcjonalne',
	'PST_WORDS'			=> 'Specjalne słowa do zignorowania',
	'PST_WORDS_EXPLAIN'	=> 'Dodaj specjalne słowa unikalne dla Twojego forum, które powinny być ignorowane podczas szukania podobnych tematów. (Uwaga: Słowa powszechnie uznawane za popularne w Twoim języku są już domyślnie ignorowane). Rozdzielaj słowa spacją. Wielkość liter nie ma znaczenia.',
	'PST_SAVED'			=> 'Ustawienia Precise Similar Topics zostały zaktualizowane',
	'PST_FORUM_INFO'	=> '„Nie wyświetlaj w”: Nie pokaże podobnych tematów na wybranych forach.<br>„Nie szukaj w”: Nie będzie przeszukiwać wybranych forów w celu znalezienia podobnych tematów.',
	'PST_NO_COMPAT'		=> 'Precise Similar Topics nie jest kompatybilne z Twoim forum.',
	'PST_ERR_CONFIG'	=> 'Zbyt wiele forów zostało zaznaczonych na liście. Spróbuj ponownie z mniejszą liczbą zaznaczeń.',
));
