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
	'PST_TIME_EXPLAIN'	=> 'Ta opcja pozwala ograniczyć wyniki podobnych tematów do nowszych wątków (i zapobiega odświeżaniu starych dyskusji). Na przykład, jeśli ustawisz „30 dni”, system pokaże tylko podobne tematy z ostatnich 30 dni. Domyślnie jest to 1 rok. Ustaw 0, jeśli nie chcesz ograniczać czasu.',
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
	'PST_FEATURES' => 'Wybierz, gdzie pojawiają się podobne tematy',
	'PST_FEATURES_EXPLAIN' => 'Włącz lub wyłącz każdą opcję dla całego forum.',
	'PST_TIME_UNIT' => 'Wyszukaj jednostkę okresu',
	'PST_CACHE_OFF' => 'Wyłączony',
	'PST_CACHE_5_MINUTES' => '5 minut',
	'PST_CACHE_15_MINUTES' => '15 minut',
	'PST_CACHE_30_MINUTES' => '30 minut',
	'PST_CACHE_1_HOUR' => '1 godzina',
	'PST_CACHE_2_HOURS' => '2 godziny',
	'PST_CACHE_4_HOURS' => '4 godziny',
	'PST_CACHE_8_HOURS' => '8 godzin',
	'PST_CACHE_12_HOURS' => '12 godzin',
	'PST_CACHE_24_HOURS' => '24 godziny',
	'PST_CACHE_CUSTOM' => 'Niestandardowe: %d sekund',
	'PST_RESULTS' => 'Kształtuj wyniki',
	'PST_RESULTS_EXPLAIN' => 'Ustaw, ile podobnych tematów ma się pojawiać i jak aktualne muszą być te tematy.',
	'PST_FORUM_RULES' => 'Zdecyduj, w jaki sposób każde forum uczestniczy',
	'PST_FORUM_RULES_EXPLAIN' => 'Każde forum ma dwa proste przełączniki. Użyj opcji „Wybierz źródła” tylko wtedy, gdy forum potrzebuje własnej puli wyszukiwania.',
	'PST_FORUMS_MANAGED' => 'fora',
	'PST_CUSTOM_RULES' => 'niestandardowe reguły źródłowe',
	'PST_FILTER_FORUMS' => 'Znajdź forum…',
	'PST_NO_FORUM_MATCH' => 'Żadne fora nie pasują do tego wyszukiwania.',
	'PST_SHOW_HERE' => 'Pokaż podobne tematy tutaj',
	'PST_SHOW_HERE_EXPLAIN' => 'Odwiedzający to forum mogą zobaczyć podobne tematy.',
	'PST_SEARCHABLE' => 'Udostępnij to forum jako źródło',
	'PST_SEARCHABLE_EXPLAIN' => 'Wyszukiwanie standardowe może znaleźć tematy z tego forum.',
	'PST_SEARCH_SOURCES' => 'Gdzie to forum szuka',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Skorzystaj z każdego dostępnego forum lub wybierz zestaw niestandardowy.',
	'PST_CHOOSE_SOURCES' => 'Wybierz źródła',
	'PST_SOURCE_ALL' => 'Wszystkie dostępne fora',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d wybrane forum',
		2 => '%d wybrane fora',
		3 => '%d wybranych forów',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Wybierz, skąd pochodzą podobne tematy',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Wybór ten dotyczy tylko forum pokazanego powyżej. Nie zmienia to żadnego innego forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Przeszukaj każde forum, na którym włączona jest opcja „dostępne jako źródło”. Nowe fora dołączają się automatycznie.',
	'PST_SOURCE_CUSTOM' => 'Tylko wybrane fora',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Przeszukaj ustaloną listę forów. Najlepsze dla ściśle powiązanych sekcji.',
	'PST_FILTER_SOURCES' => 'Znajdź forum źródłowe…',
	'PST_SELECT_AVAILABLE' => 'Wybierz wszystkie dostępne',
	'PST_CLEAR' => 'Wyczyść wybór',
	'PST_GLOBALLY_AVAILABLE' => 'Dostępny',
	'PST_GLOBALLY_UNAVAILABLE' => 'Niedostępne globalnie',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Niestandardowe opcje zastępują globalne przełączniki dostępności. Wybrane forum można nadal przeszukiwać, nawet jeśli jest zaznaczone jako „Niedostępne globalnie”.',
	'PST_SELECT_ONE_SOURCE' => 'Wybierz co najmniej jedno forum lub użyj opcji „Wszystkie dostępne fora”.',
	'PST_APPLY_SOURCES' => 'Skorzystaj z tych źródeł',
	'PST_TUNING' => 'Dostosuj dopasowanie i wydajność',
	'PST_TUNING_EXPLAIN' => 'Wartości domyślne działają w większości społeczności. Dostosuj te elementy sterujące tylko wtedy, gdy jest to konieczne.',
	'PST_READY_TO_SAVE' => 'Gotowy do zastosowania zmian?',
	'PST_SAVE_EXPLAIN' => 'Wszystkie ustawienia na tej stronie są zapisywane razem.',
	'PST_SAVE_SETTINGS' => 'Zapisz ustawienia',
));
