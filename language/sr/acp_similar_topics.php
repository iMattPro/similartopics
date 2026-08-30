<?php
/**
*
* Precise Similar Topics [Serbian]
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
	'PST_EXPLAIN'		=> 'Precise Similar Topics prikazuje spisak sličnih (povezanih) tema na dnu stranice trenutne teme.',
	'PST_LEGEND1'		=> 'Generalna podešavanja',
	'PST_ENABLE'		=> 'Uključi slične teme',
	'PST_ENABLE_EXPLAIN'=> 'Prikazuje slične teme u raspravama tema.',
	'PST_LEGEND2'		=> 'Učitaj podešavanja',
	'PST_LIMIT'			=> 'Broj sličnih tema koje će biti prikazane',
	'PST_LIMIT_EXPLAIN'	=> 'Ovde možete odrediti koliko sličnih tema će biti prikazano. Podrazumevana vrednost je 5.',
	'PST_TIME'			=> 'Period pretrage',
	'PST_TIME_EXPLAIN'	=> 'Ova opcija vam omogućava da podesite koliki period pretrage će da koristi Similar Topics. Na primer ako podesite na 5 dana sistem će da prikazuje samo slične teme iz perioda od tih 5 dana. Podrazumevana vrednost je jedna godina. Postavite vrednost na 0 ako ne želite vremensko ograničenje.',
	'PST_YEARS'			=> 'Godina',
	'PST_MONTHS'		=> 'Meseci',
	'PST_WEEKS'			=> 'Nedelja',
	'PST_DAYS'			=> 'Dana',
	'PST_CACHE'			=> 'Dužina keša za Similar Topics',
	'PST_CACHE_EXPLAIN'	=> 'Keširane slične teme će da nestanu nakon toliko vremena u sekunadma. Podesite vrednost na 0 ukoliko želite da isključite ovu opciju.',
	'PST_DYNAMIC'		=> 'Prikaži dinamičke slične teme',
	'PST_DYNAMIC_EXPLAIN'=> 'Prikazuje slične teme dok korisnici unose naslov pri pravljenju nove teme.',
	'PST_SENSE'			=> 'Osetljivost pretrage',
	'PST_SENSE_EXPLAIN'	=> 'Za MySQL ili Postgres baze možete podesiti osetljivost pretrage na vrednost od 1 do 10. Upotrebite manji broj ako se ne prikazuju slične teme. Preporučeno podešavanje: %d',
	'PST_LEGEND3'		=> 'Podešavanja foruma',
	'PST_NOSHOW_LIST'	=> 'Ne prikazuj u',
	'PST_NOSHOW_TITLE'	=> 'Ne prikazuj slične teme u',
	'PST_IGNORE_SEARCH'	=> 'Ne pretražuj',
	'PST_IGNORE_TITLE'	=> 'Nemoj da pretražuješ slične teme u',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Napredna podešavanja',
	'PST_ADVANCED_TITLE'=> 'Kliknite ukoliko želite da odredite napredna podešavanja za',
	'PST_ADVANCED_EXP'	=> 'Ovde možete odrediti specifične forume iz kojih želite da se pretražuju slične teme. Jedino slične teme koje budu pronađene u forumu će biti prikazane u <strong>%s</strong>.<br><br>Nemojte da selektujete nijedan forum ukoliko želite da se prikazuju slične teme iz svih foruma u kojima je uključena opcija Similar Topics.<br><br>Možete izabrati više foruma tako što držite <code>CTRL</code> i kliknete.',
	'PST_ADVANCED_FORUM'=> 'Napredna podešavanja foruma',
	'PST_DESELECT_ALL'	=> 'Deselektuj sve',
	'PST_LEGEND4'		=> 'Opciona podešavanja',
	'PST_WORDS'			=> 'Specialne reči za ignorisanje',
	'PST_WORDS_EXPLAIN'	=> 'Dodajte specijalne reči koje su unikatne za vaš forum koje bi trebalo da budu ignorisane kada se pretražuju slične teme. (Napomena: reči koje se inače smatraju za ustaljene će ionako biti ignorisane prilikom pretrage.) Reči razdvojte razmakom. Ne razlikuju se velika i mala slova.',
	'PST_SAVED'			=> 'Podešavanja za Similar Topics su ažurirana',
	'PST_FORUM_INFO'	=> '"Ne prikazuj u": Neće prikazati slične teme u izabranim forumima.<br>"Ne pretražuj u": Neće pretraživati slične teme u izabranim forumima.',
	'PST_NO_COMPAT'		=> 'Similar Topics ne funkcioniše sa vašim forumom. Similar Topics zahteva MySQL 4 ili MySQL 5 ili PostgreSQL bazu podataka.',
	'PST_ERR_CONFIG'	=> 'Previše forumi su označeni na listi forumima . Pokušajte ponovo sa manjim izborom.',
	'PST_FEATURES' => 'Izaberite gde se pojavljuju slične teme',
	'PST_FEATURES_EXPLAIN' => 'Uključite ili isključite svaku opciju za ceo forum.',
	'PST_TIME_UNIT' => 'Jedinica perioda pretrage',
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
	'PST_RESULTS_EXPLAIN' => 'Podesite koliko se sličnih tema pojavljuje i koliko te teme moraju biti novije.',
	'PST_FORUM_RULES' => 'Odlučite kako će svaki forum učestvovati',
	'PST_FORUM_RULES_EXPLAIN' => 'Svaki forum ima dva jednostavna prekidača. Koristite „Izaberite izvore“ samo kada je forumu potreban sopstveni bazen za pretragu.',
	'PST_FORUMS_MANAGED' => 'forumi',
	'PST_CUSTOM_RULES' => 'prilagođena izvorna pravila',
	'PST_FILTER_FORUMS' => 'Pronađite forum…',
	'PST_NO_FORUM_MATCH' => 'Nijedan forum ne odgovara toj pretrazi.',
	'PST_SHOW_HERE' => 'Prikažite slične teme ovde',
	'PST_SHOW_HERE_EXPLAIN' => 'Posetioci ovog foruma mogu da vide slične teme.',
	'PST_SEARCHABLE' => 'Učinite ovaj forum dostupnim kao izvor',
	'PST_SEARCHABLE_EXPLAIN' => 'Standardne pretrage mogu pronaći teme sa ovog foruma.',
	'PST_SEARCH_SOURCES' => 'Gde ovaj forum traži',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Koristite svaki dostupni forum ili izaberite prilagođeni skup.',
	'PST_CHOOSE_SOURCES' => 'Izaberite izvore',
	'PST_SOURCE_ALL' => 'Svi dostupni forumi',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d izabrani forum',
		2 => '%d izabrana foruma',
		3 => '%d izabranih foruma',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Odaberite odakle dolaze slične teme',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Ovaj izbor se odnosi samo na forum prikazan iznad. To ne menja nijedan drugi forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Pretražite svaki forum čiji je prekidač „dostupan kao izvor“ uključen. Novi forumi se pridružuju automatski.',
	'PST_SOURCE_CUSTOM' => 'Samo odabrani forumi',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Pretražite fiksnu listu foruma. Najbolje za usko povezane odeljke.',
	'PST_FILTER_SOURCES' => 'Pronađite forum izvora…',
	'PST_SELECT_AVAILABLE' => 'Izaberite sve dostupne',
	'PST_CLEAR' => 'Obriši izbor',
	'PST_GLOBALLY_AVAILABLE' => 'Dostupan',
	'PST_GLOBALLY_UNAVAILABLE' => 'Globalno nedostupno',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Prilagođeni izbori zamenjuju prekidače globalne dostupnosti. Izabrani forum se i dalje može pretraživati čak i kada je označen sa „Globalno nedostupno“.',
	'PST_SELECT_ONE_SOURCE' => 'Izaberite najmanje jedan forum ili koristite „Svi dostupni forumi“.',
	'PST_APPLY_SOURCES' => 'Koristite ove izvore',
	'PST_TUNING' => 'Fino podešavanje podudaranja i performansi',
	'PST_TUNING_EXPLAIN' => 'Podrazumevane vrednosti rade za većinu zajednica. Podesite ove kontrole samo kada je potrebno.',
	'PST_READY_TO_SAVE' => 'Spremni da primenite promene?',
	'PST_SAVE_EXPLAIN' => 'Sva podešavanja na ovoj stranici se čuvaju zajedno.',
	'PST_SAVE_SETTINGS' => 'Sačuvaj podešavanja',
));
