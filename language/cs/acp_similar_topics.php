<?php
/**
*
* Precise Similar Topics [Czech]
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
	'PST_TITLE_ACP'		=> 'Podobná témata',
	'PST_EXPLAIN'		=> 'Toto rozšíření zobrazí na spodu každého tématu odkazy na několik podobných (souvisejících) témat.',
	'PST_LEGEND1'		=> 'Hlavní nastavení',
	'PST_ENABLE'		=> 'Zobrazit podobná témata',
	'PST_LEGEND2'		=> 'Nastavení zatížení',
	'PST_LIMIT'			=> 'Počet podobných témat',
	'PST_LIMIT_EXPLAIN'	=> 'Zde můžete zvolit počet podobných témat, která se zobrazí. Výchozí nastavení je 5 témat.',
	'PST_TIME'			=> 'Stáří tématu',
	'PST_TIME_EXPLAIN'	=> 'Tato volba umožňuje omezit hledání na témata do určitého stáří. Pokud například nastavíte “5 dní”, rozšíření bude vyhledávat podobná témata pouze za posledních 5 dní. Výchozí nastavení je 1 rok.',
	'PST_YEARS'			=> 'Roky',
	'PST_MONTHS'		=> 'Měsíce',
	'PST_WEEKS'			=> 'Týdny',
	'PST_DAYS'			=> 'Dny',
	'PST_CACHE'			=> 'Životnost cache',
	'PST_CACHE_EXPLAIN'	=> 'Cache podobných témat se vyprázdní po daném čase (v sekundách). Pokud nastavíte na 0, cache bude vypnuta.',
	'PST_SENSE'			=> 'Search sensitivity',
	'PST_SENSE_EXPLAIN'	=> 'Set the search sensitivity to a value between 1 and 10. Use a lower number if you are not seeing any similar topics. Recommended settings: For “phpbb_topics” database tables using InnoDB use 1; for MyISAM use 5.',
	'PST_LEGEND3'		=> 'Nastavení fór',
	'PST_NOSHOW_LIST'	=> 'Nezobrazovat',
	'PST_NOSHOW_TITLE'	=> 'Nezobrazovat podobná témata v',
	'PST_IGNORE_SEARCH'	=> 'Nevyhledávat',
	'PST_IGNORE_TITLE'	=> 'Nevyhledávat podobná témata v',
	'PST_STANDARD'		=> 'Standartní',
	'PST_ADVANCED'		=> 'Rozšířené',
	'PST_ADVANCED_TITLE'=> 'Kliknutím provedete rozšířená nastavení pro fórum',
	'PST_ADVANCED_EXP'	=> 'Zde můžete vybrat pouze určité sekce, ve kterých chcete, aby se podobná témata vyhledávala. Poté budou v sekci <strong>%s</strong> zobrazena pouze podobná témata z těchto vybraných fór.<br /><br />Pokud nevyberete žádná fóra, rozšíření bude podobná témata vyhledávat po celém fóru.<br /><br />Výběr více fór můžete provést se stisknoutou klávesou <code>CTRL</code> (případně <code>CMD</code> pro Mac).',
	'PST_ADVANCED_FORUM'=> 'Rozšířené nastavení fóra',
	'PST_DESELECT_ALL'	=> 'Zrušit výběr všech',
	'PST_LEGEND4'		=> 'Volitelná nastavení',
	'PST_WORDS'			=> 'Ignorovaná slova',
	'PST_WORDS_EXPLAIN'	=> 'Zde můžete uvést seznam slov, u kterých si přejete, aby se při vyhledávání podobných témat ignorovala. (Pozn.: Slova, která jsou ve vašem jazyce považována za běžná, uvádět nemusíte (jsou ignorována automaticky). Jednotlivá slova oddělte mezerou. Nezáleží na velikosti písmen.',
	'PST_SAVED'			=> 'Nastavení podobných témat bylo úspěšně změněno',
	'PST_FORUM_INFO'	=> '“Nezobrazovat”: Nebude ve vybraných fórech zobrazovat podobná témata.<br />“Nevyhledávat” : Ve vybraných fórech se nebudou vyhledávat podobná témata.',
	'PST_NO_COMPAT'		=> 'Podobná témata nejsou kompatibilní s vašim fórem. Jsou vyžadovány technologie MySQL 4 nebo MySQL 5 nebo PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'Z hledání/zobrazení bylo vyřazeno příliš mnoho sekcí. Snižte prosím počet vybraných sekcí.',
));
