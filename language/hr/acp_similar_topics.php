<?php
/**
*
* Precise Similar Topics [Croatian]
* Croatian translation by Ančica Sečan (http://ancica.sunceko.net)
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
	'PST_TITLE_ACP'		=> 'Slične teme',
	'PST_EXPLAIN'		=> 'Ovdje možeš o[ne]mogućiti prikaz liste sličnih [povezanih] tema na dnu stranice trenutne teme.',
	'PST_LEGEND1'		=> 'Opće postavke',
	'PST_ENABLE'		=> 'Prikaži Slične teme',
	'PST_ENABLE_EXPLAIN'=> 'Prikazuje slične teme u raspravama tema.',
	'PST_LEGEND2'		=> 'Osnovne postavke',
	'PST_LIMIT'			=> 'Broj sličnih tema za prikaz',
	'PST_LIMIT_EXPLAIN'	=> 'Određivanje prikaza broja sličnih tema [zadano=5].',
	'PST_TIME'			=> 'Razdoblje pretraživanja',
	'PST_TIME_EXPLAIN'	=> 'Određivanje razdoblje pretraživanja broja sličnih tema [zadano=godina].<br>Npr. ukoliko je vrijednost postavljena na 5, sistem će prikaz(iv)ati slične teme unazad 5 dana. Postavite na 0 ako ne želite vremensko ograničenje.',
	'PST_YEARS'			=> 'Godina/e',
	'PST_MONTHS'		=> 'Mjesec/a/i',
	'PST_WEEKS'			=> 'Tjedan/na/a',
	'PST_DAYS'			=> 'Dan/a',
	'PST_CACHE'			=> 'Trajanje sličnih tema pohranjenih u priručnu memoriju',
	'PST_CACHE_EXPLAIN'	=> 'Slične teme, pohranjene u priručnu memoriju, bit će izbrisane, iz priručne memorije, po isteku [u sekundama] postavljenog vremena [0=onemogućeno].',
	'PST_DYNAMIC'		=> 'Prikaži dinamičke slične teme',
	'PST_DYNAMIC_EXPLAIN'=> 'Prikazuje slične teme dok korisnici/e upisuju naslov pri stvaranju nove teme.',
	'PST_SENSE'			=> 'Osjetljivost pretraživanja',
	'PST_SENSE_EXPLAIN'	=> 'Za MySQL ili Postgres baze podataka osjetljivost pretraživanja možeš postaviti na vrijednost od 1 do 10. Ako se ne prikazuju slične teme, upotrijebi manji broj. Preporučena postavka: %d',
	'PST_LEGEND3'		=> 'Postavke foruma',
	'PST_NOSHOW_LIST'	=> 'Ne prikazuj u',
	'PST_NOSHOW_TITLE'	=> 'Ne prikazuj slične teme u',
	'PST_IGNORE_SEARCH'	=> 'Ne pretražuj u',
	'PST_IGNORE_TITLE'	=> 'Ne pretražuj slične teme u',
	'PST_STANDARD'		=> 'Standardno',
	'PST_ADVANCED'		=> 'Napredno',
	'PST_ADVANCED_TITLE'=> 'Klikni za podešavanje naprednih postavki sličnih tema u/za',
	'PST_ADVANCED_EXP'	=> 'Ukoliko želiš da slične teme, iz određenih pretražljivih foruma, budu prikazane na <strong>%s</strong>, označi [odaberi] željene forume.<br><br>Ukoliko želiš da slične teme, iz svih pretražljivih foruma, budu prikazane na forumu, nemoj označiti [odabrati] niti jedan forum.<br><br>Forume možeš (od)označavati korištenjem [pritisnute tipke] <code>CTRL</code> i kliktanjem [mišem].',
	'PST_ADVANCED_FORUM'=> 'Napredne postavke foruma',
	'PST_DESELECT_ALL'	=> 'Odoznači sve',
	'PST_LEGEND4'		=> 'Opcionalne postavke',
	'PST_WORDS'			=> 'Ignorirane riječi',
	'PST_WORDS_EXPLAIN'	=> 'Popis riječi koje će biti ignorirane prilikom pronalaženja sličnih tema [zadano su ignorirane riječi koje su navedene ako učestale].<br>Riječi odvoji razmacima. Neosjetljivo na velika/mala slova.',
	'PST_SAVED'			=> 'Postavke sličnih tema su ažurirane.',
	'PST_FORUM_INFO'	=> '“Ne prikazuj u”: neće prikaz(iv)ati slične teme u odabranim forumima.<br>“Ne pretražuj u”: neće pretraž(iv)ati slične teme u odabranim forumima.',
	'PST_NO_COMPAT'		=> 'Slične teme nisu kompatibilne s forumom.<br>Pokreću se samo na/u MySQL 4 ili MySQL 5 ili PostgreSQL bazi podataka.',
	'PST_ERR_CONFIG'	=> 'Previše forumi su označeni na popisu foruma. Pokušajte ponovno s manjim izborom.',
	'PST_FEATURES' => 'Odaberite gdje se pojavljuju slične teme',
	'PST_FEATURES_EXPLAIN' => 'Uključite ili isključite svaku opciju za cijeli forum.',
	'PST_TIME_UNIT' => 'Jedinica razdoblja pretraživanja',
	'PST_CACHE_OFF' => 'Isključeno',
	'PST_CACHE_5_MINUTES' => '5 minuta',
	'PST_CACHE_15_MINUTES' => '15 minuta',
	'PST_CACHE_30_MINUTES' => '30 minuta',
	'PST_CACHE_1_HOUR' => '1 sat',
	'PST_CACHE_2_HOURS' => '2 sata',
	'PST_CACHE_4_HOURS' => '4 sata',
	'PST_CACHE_8_HOURS' => '8 sati',
	'PST_CACHE_12_HOURS' => '12 sati',
	'PST_CACHE_24_HOURS' => '24 sata',
	'PST_CACHE_CUSTOM' => 'Prilagođeno: %d sekundi',
	'PST_RESULTS' => 'Oblikujte rezultate',
	'PST_RESULTS_EXPLAIN' => 'Postavite koliko se sličnih tema pojavljuje i koliko novije te teme moraju biti.',
	'PST_FORUM_RULES' => 'Odlučite kako svaki forum sudjeluje',
	'PST_FORUM_RULES_EXPLAIN' => 'Svaki forum ima dva jednostavna prekidača. Upotrijebite "Odaberi izvore" samo kada forum treba vlastiti skup pretraživanja.',
	'PST_FORUMS_MANAGED' => 'forumi',
	'PST_CUSTOM_RULES' => 'prilagođena pravila izvora',
	'PST_FILTER_FORUMS' => 'Pronađite forum…',
	'PST_NO_FORUM_MATCH' => 'Nijedan forum ne odgovara toj pretrazi.',
	'PST_SHOW_HERE' => 'Ovdje prikaži slične teme',
	'PST_SHOW_HERE_EXPLAIN' => 'Posjetitelji ovog foruma mogu vidjeti slične teme.',
	'PST_SEARCHABLE' => 'Učinite ovaj forum dostupnim kao izvor',
	'PST_SEARCHABLE_EXPLAIN' => 'Standardna pretraživanja mogu pronaći teme s ovog foruma.',
	'PST_SEARCH_SOURCES' => 'Gdje ovaj forum traži',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Koristite svaki dostupni forum ili odaberite prilagođeni skup.',
	'PST_CHOOSE_SOURCES' => 'Odaberite izvore',
	'PST_SOURCE_ALL' => 'Svi dostupni forumi',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d odabrani forum',
		2 => '%d odabrana foruma',
		3 => '%d odabranih foruma',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Odaberite odakle dolaze slične teme',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Ovaj izbor se odnosi samo na gore prikazani forum. Ne mijenja nijedan drugi forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Pretražite svaki forum čiji je prekidač "dostupan kao izvor" uključen. Novi se forumi automatski pridružuju.',
	'PST_SOURCE_CUSTOM' => 'Samo odabrani forumi',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Pretražite fiksni popis foruma. Najbolje za usko povezane odjeljke.',
	'PST_FILTER_SOURCES' => 'Pronađite izvorni forum...',
	'PST_SELECT_AVAILABLE' => 'Odaberite sve dostupne',
	'PST_CLEAR' => 'Očisti odabir',
	'PST_GLOBALLY_AVAILABLE' => 'na raspolaganju',
	'PST_GLOBALLY_UNAVAILABLE' => 'Nije globalno dostupno',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Prilagođeni izbori poništavaju prekidače globalne dostupnosti. Odabrani forum i dalje se može pretraživati čak i kada je označen "Nije globalno dostupno".',
	'PST_SELECT_ONE_SOURCE' => 'Odaberite barem jedan forum ili upotrijebite "Svi dostupni forumi".',
	'PST_APPLY_SOURCES' => 'Koristite ove izvore',
	'PST_TUNING' => 'Fino podesite podudaranje i izvedbu',
	'PST_TUNING_EXPLAIN' => 'Zadane postavke rade za većinu zajednica. Prilagodite ove kontrole samo kada je to potrebno.',
	'PST_READY_TO_SAVE' => 'Jeste li spremni primijeniti svoje promjene?',
	'PST_SAVE_EXPLAIN' => 'Sve postavke na ovoj stranici spremaju se zajedno.',
	'PST_SAVE_SETTINGS' => 'Spremi postavke',
));
