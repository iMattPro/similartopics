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
	'PST_LEGEND2'		=> 'Osnovne postavke',
	'PST_LIMIT'			=> 'Broj sličnih tema za prikaz',
	'PST_LIMIT_EXPLAIN'	=> 'Određivanje prikaza broja sličnih tema [zadano=5].',
	'PST_TIME'			=> 'Razdoblje pretraživanja',
	'PST_TIME_EXPLAIN'	=> 'Određivanje razdoblje pretraživanja broja sličnih tema [zadano=godina].<br />Npr. ukoliko je vrijednost postavljena na 5, sistem će prikaz(iv)ati slične teme unazad 5 dana.',
	'PST_YEARS'			=> 'Godina/e',
	'PST_MONTHS'		=> 'Mjesec/a/i',
	'PST_WEEKS'			=> 'Tjedan/na/a',
	'PST_DAYS'			=> 'Dan/a',
	'PST_CACHE'			=> 'Trajanje sličnih tema pohranjenih u priručnu memoriju',
	'PST_CACHE_EXPLAIN'	=> 'Slične teme, pohranjene u priručnu memoriju, bit će izbrisane, iz priručne memorije, po isteku [u sekundama] postavljenog vremena [0=onemogućeno].',
	'PST_SENSE'			=> 'Search sensitivity',
	'PST_SENSE_EXPLAIN'	=> 'Set the search sensitivity to a value between 1 and 10. Use a lower number if you are not seeing any similar topics. Recommended settings: For “phpbb_topics” database tables using InnoDB use 1; for MyISAM use 5.',
	'PST_LEGEND3'		=> 'Postavke foruma',
	'PST_NOSHOW_LIST'	=> 'Ne prikazuj u',
	'PST_NOSHOW_TITLE'	=> 'Ne prikazuj slične teme u',
	'PST_IGNORE_SEARCH'	=> 'Ne pretražuj u',
	'PST_IGNORE_TITLE'	=> 'Ne pretražuj slične teme u',
	'PST_STANDARD'		=> 'Standardno',
	'PST_ADVANCED'		=> 'Napredno',
	'PST_ADVANCED_TITLE'=> 'Klikni za podešavanje naprednih postavki sličnih tema u/za',
	'PST_ADVANCED_EXP'	=> 'Ukoliko želiš da slične teme, iz određenih pretražljivih foruma, budu prikazane na <strong>%s</strong>, označi [odaberi] željene forume.<br /><br />Ukoliko želiš da slične teme, iz svih pretražljivih foruma, budu prikazane na forumu, nemoj označiti [odabrati] niti jedan forum.<br /><br />Forume možeš (od)označavati korištenjem [pritisnute tipke] <code>CTRL</code> i kliktanjem [mišem].',
	'PST_ADVANCED_FORUM'=> 'Napredne postavke foruma',
	'PST_DESELECT_ALL'	=> 'Odoznači sve',
	'PST_LEGEND4'		=> 'Opcionalne postavke',
	'PST_WORDS'			=> 'Ignorirane riječi',
	'PST_WORDS_EXPLAIN'	=> 'Popis riječi koje će biti ignorirane prilikom pronalaženja sličnih tema [zadano su ignorirane riječi koje su navedene ako učestale].<br />Riječi odvoji razmacima. Neosjetljivo na velika/mala slova.',
	'PST_SAVED'			=> 'Postavke sličnih tema su ažurirane.',
	'PST_FORUM_INFO'	=> '“Ne prikazuj u”: neće prikaz(iv)ati slične teme u odabranim forumima.<br />“Ne pretražuj u”: neće pretraž(iv)ati slične teme u odabranim forumima.',
	'PST_NO_COMPAT'		=> 'Slične teme nisu kompatibilne s forumom.<br />Pokreću se samo na/u MySQL 4 ili MySQL 5 ili PostgreSQL bazi podataka.',
	'PST_ERR_CONFIG'	=> 'Previše forumi su označeni na popisu foruma. Pokušajte ponovno s manjim izborom.',
));
