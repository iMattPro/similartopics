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
	'PST_EXPLAIN'		=> 'Precise Similar Topics displays a list of similar (related) topics at the bottom of the current topic’s page.',
	'PST_LEGEND1'		=> 'Generalna podešavanja',
	'PST_ENABLE'		=> 'Uključi Similar Topics',
	'PST_LEGEND2'		=> 'Učitaj podešavanja',
	'PST_LIMIT'			=> 'Broj sličnih tema koje će biti prikazane',
	'PST_LIMIT_EXPLAIN'	=> 'Ovde možete odrediti koliko sličnih tema će biti prikazano. Podrazumevana vrednost je 5.',
	'PST_TIME'			=> 'Period pretrage',
	'PST_TIME_EXPLAIN'	=> 'Ova opcija vam omogućava da podesite koliki period pretrage će da koristi Similar Topics. Na primer ako podesite na 5 dana sistem će da prikazuje samo slične teme iz perioda od tih 5 dana. Podrazumevana vrednost je jedna godina.',
	'PST_YEARS'			=> 'Godina',
	'PST_MONTHS'		=> 'Meseci',
	'PST_WEEKS'			=> 'Nedelja',
	'PST_DAYS'			=> 'Dana',
	'PST_CACHE'			=> 'Dužina keša za Similar Topics',
	'PST_CACHE_EXPLAIN'	=> 'Keširane slične teme će da nestanu nakon toliko vremena u sekunadma. Podesite vrednost na 0 ukoliko želite da isključite ovu opciju.',
	'PST_SENSE'			=> 'Search sensitivity',
	'PST_SENSE_EXPLAIN'	=> 'Set the search sensitivity to a value between 1 and 10. Use a lower number if you are not seeing any similar topics. Recommended settings: For “phpbb_topics” database tables using InnoDB use 1; for MyISAM use 5.',
	'PST_LEGEND3'		=> 'Podešavanja foruma',
	'PST_NOSHOW_LIST'	=> 'Ne prikazuj u',
	'PST_NOSHOW_TITLE'	=> 'Ne prikazuj slične teme u',
	'PST_IGNORE_SEARCH'	=> 'Ne pretražuj',
	'PST_IGNORE_TITLE'	=> 'Nemoj da pretražuješ slične teme u',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Napredna podešavanja',
	'PST_ADVANCED_TITLE'=> 'Kliknite ukoliko želite da odredite napredna podešavanja za',
	'PST_ADVANCED_EXP'	=> 'Ovde možete odrediti specifične forume iz kojih želite da se pretražuju slične teme. Jedino slične teme koje budu pronađene u forumu će biti prikazane u <strong>%s</strong>.<br /><br />Nemojte da selektujete nijedan forum ukoliko želite da se prikazuju slične teme iz svih foruma u kojima je uključena opcija Similar Topics.<br /><br />Možete izabrati više foruma tako što držite <code>CTRL</code> i kliknete.',
	'PST_ADVANCED_FORUM'=> 'Advanced forum settings',
	'PST_DESELECT_ALL'	=> 'Deselektuj sve',
	'PST_LEGEND4'		=> 'Opciona podešavanja',
	'PST_WORDS'			=> 'Specialne reči za ignorisanje',
	'PST_WORDS_EXPLAIN'	=> 'Dodajte specijalne reči koje su unikatne za vaš forum koje bi trebalo da budu ignorisane kada se pretražuju slične teme. (Napomena: reči koje se inače smatraju za ustaljene će ionako biti ignorisane prilikom pretrage.) Reči razdvojte razmakom. Case insensitive.',
	'PST_SAVED'			=> 'Podešavanja za Similar Topics su ažurirana',
	'PST_FORUM_INFO'	=> '"Ne prikazuj u": Neće prikazati slične teme u izabranim forumima.<br />"Ne pretražuj u": Neće pretraživati slične teme u izabranim forumima.',
	'PST_NO_COMPAT'		=> 'Similar Topics ne funkcioniše sa vašim forumom. Similar Topics zahteva MySQL 4 ili MySQL 5 ili PostgreSQL bazu podataka.',
	'PST_ERR_CONFIG'	=> 'Previše forumi su označeni na listi forumima . Pokušajte ponovo sa manjim izborom.',
));
