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
	'PST_ENABLE_EXPLAIN'=> 'Zobrazí podobná témata v diskusních vláknech témat.',
	'PST_LEGEND2'		=> 'Nastavení zatížení',
	'PST_LIMIT'			=> 'Počet podobných témat',
	'PST_LIMIT_EXPLAIN'	=> 'Zde můžete zvolit počet podobných témat, která se zobrazí. Výchozí nastavení je 5 témat.',
	'PST_TIME'			=> 'Stáří tématu',
	'PST_TIME_EXPLAIN'	=> 'Tato volba umožňuje omezit hledání na témata do určitého stáří. Pokud například nastavíte “5 dní”, rozšíření bude vyhledávat podobná témata pouze za posledních 5 dní. Výchozí nastavení je 1 rok. Nastavte hodnotu na 0, pokud nechcete časové omezení.',
	'PST_YEARS'			=> 'Roky',
	'PST_MONTHS'		=> 'Měsíce',
	'PST_WEEKS'			=> 'Týdny',
	'PST_DAYS'			=> 'Dny',
	'PST_CACHE'			=> 'Životnost cache',
	'PST_CACHE_EXPLAIN'	=> 'Cache podobných témat se vyprázdní po daném čase (v sekundách). Pokud nastavíte na 0, cache bude vypnuta.',
	'PST_DYNAMIC'		=> 'Zobrazit dynamická podobná témata',
	'PST_DYNAMIC_EXPLAIN'=> 'Při vytváření nového tématu zobrazí podobná témata během psaní do pole názvu tématu.',
	'PST_SENSE'			=> 'Citlivost vyhledávání',
	'PST_SENSE_EXPLAIN'	=> 'U databází MySQL nebo Postgres můžete nastavit citlivost vyhledávání na hodnotu od 1 do 10. Pokud se nezobrazují žádná podobná témata, použijte nižší číslo. Doporučené nastavení: %d',
	'PST_LEGEND3'		=> 'Nastavení fór',
	'PST_NOSHOW_LIST'	=> 'Nezobrazovat',
	'PST_NOSHOW_TITLE'	=> 'Nezobrazovat podobná témata v',
	'PST_IGNORE_SEARCH'	=> 'Nevyhledávat',
	'PST_IGNORE_TITLE'	=> 'Nevyhledávat podobná témata v',
	'PST_STANDARD'		=> 'Standartní',
	'PST_ADVANCED'		=> 'Rozšířené',
	'PST_ADVANCED_TITLE'=> 'Kliknutím provedete rozšířená nastavení pro fórum',
	'PST_ADVANCED_EXP'	=> 'Zde můžete vybrat pouze určité sekce, ve kterých chcete, aby se podobná témata vyhledávala. Poté budou v sekci <strong>%s</strong> zobrazena pouze podobná témata z těchto vybraných fór.<br><br>Pokud nevyberete žádná fóra, rozšíření bude podobná témata vyhledávat po celém fóru.<br><br>Výběr více fór můžete provést se stisknoutou klávesou <code>CTRL</code> (případně <code>CMD</code> pro Mac).',
	'PST_ADVANCED_FORUM'=> 'Rozšířené nastavení fóra',
	'PST_DESELECT_ALL'	=> 'Zrušit výběr všech',
	'PST_LEGEND4'		=> 'Volitelná nastavení',
	'PST_WORDS'			=> 'Ignorovaná slova',
	'PST_WORDS_EXPLAIN'	=> 'Zde můžete uvést seznam slov, u kterých si přejete, aby se při vyhledávání podobných témat ignorovala. (Pozn.: Slova, která jsou ve vašem jazyce považována za běžná, uvádět nemusíte (jsou ignorována automaticky). Jednotlivá slova oddělte mezerou. Nezáleží na velikosti písmen.',
	'PST_SAVED'			=> 'Nastavení podobných témat bylo úspěšně změněno',
	'PST_FORUM_INFO'	=> '“Nezobrazovat”: Nebude ve vybraných fórech zobrazovat podobná témata.<br>“Nevyhledávat” : Ve vybraných fórech se nebudou vyhledávat podobná témata.',
	'PST_NO_COMPAT'		=> 'Podobná témata nejsou kompatibilní s vašim fórem. Jsou vyžadovány technologie MySQL 4 nebo MySQL 5 nebo PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'Z hledání/zobrazení bylo vyřazeno příliš mnoho sekcí. Snižte prosím počet vybraných sekcí.',
	'PST_FEATURES' => 'Vyberte, kde se podobná témata zobrazí',
	'PST_FEATURES_EXPLAIN' => 'Každou možnost můžete zapnout nebo vypnout pro celé fórum.',
	'PST_TIME_UNIT' => 'Jednotka období vyhledávání',
	'PST_CACHE_OFF' => 'Vypnuto',
	'PST_CACHE_5_MINUTES' => '5 minut',
	'PST_CACHE_15_MINUTES' => '15 minut',
	'PST_CACHE_30_MINUTES' => '30 minut',
	'PST_CACHE_1_HOUR' => '1 hodina',
	'PST_CACHE_2_HOURS' => '2 hodiny',
	'PST_CACHE_4_HOURS' => '4 hodiny',
	'PST_CACHE_8_HOURS' => '8 hodin',
	'PST_CACHE_12_HOURS' => '12 hodin',
	'PST_CACHE_24_HOURS' => '24 hodin',
	'PST_CACHE_CUSTOM' => 'Vlastní: %d sekund',
	'PST_RESULTS' => 'Tvarujte výsledky',
	'PST_RESULTS_EXPLAIN' => 'Nastavte, kolik podobných témat se zobrazí a jak nedávná tato témata musí být.',
	'PST_FORUM_RULES' => 'Rozhodněte, jak se každé fórum zapojí',
	'PST_FORUM_RULES_EXPLAIN' => 'Každé fórum má dva jednoduché přepínače. Možnost „Vybrat zdroje“ použijte pouze v případě, že fórum potřebuje vlastní vyhledávací fond.',
	'PST_FORUMS_MANAGED' => 'fóra',
	'PST_CUSTOM_RULES' => 'vlastní pravidla zdroje',
	'PST_FILTER_FORUMS' => 'Najít fórum…',
	'PST_NO_FORUM_MATCH' => 'Tomuto vyhledávání neodpovídá žádná fóra.',
	'PST_SHOW_HERE' => 'Zobrazit podobná témata zde',
	'PST_SHOW_HERE_EXPLAIN' => 'Návštěvníci tohoto fóra mohou vidět podobná témata.',
	'PST_SEARCHABLE' => 'Zpřístupněte toto fórum jako zdroj',
	'PST_SEARCHABLE_EXPLAIN' => 'Standardní vyhledávání může najít témata z tohoto fóra.',
	'PST_SEARCH_SOURCES' => 'Kde toto fórum vyhledává',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Využijte každé dostupné fórum nebo si vyberte vlastní sadu.',
	'PST_CHOOSE_SOURCES' => 'Vyberte zdroje',
	'PST_SOURCE_ALL' => 'Všechna dostupná fóra',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d vybrané fórum',
		2 => '%d vybraná fóra',
		3 => '%d vybraných fór',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Vyberte, odkud podobná témata pocházejí',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Tato volba platí pouze pro výše uvedené fórum. Na žádném jiném fóru to nic nemění.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Prohledejte každé fórum, kde je zapnutý přepínač „dostupné jako zdroj“. Nová fóra se připojují automaticky.',
	'PST_SOURCE_CUSTOM' => 'Pouze vybraná fóra',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Prohledejte pevný seznam fór. Nejlepší pro úzce související sekce.',
	'PST_FILTER_SOURCES' => 'Najít zdrojové fórum…',
	'PST_SELECT_AVAILABLE' => 'Vyberte všechny dostupné',
	'PST_CLEAR' => 'Jasný výběr',
	'PST_GLOBALLY_AVAILABLE' => 'K dispozici',
	'PST_GLOBALLY_UNAVAILABLE' => 'Globálně nedostupné',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Vlastní volby přepíší přepínače globální dostupnosti. Vybrané fórum lze prohledávat, i když je označeno jako „Globálně nedostupné“.',
	'PST_SELECT_ONE_SOURCE' => 'Vyberte alespoň jedno fórum nebo použijte „Všechna dostupná fóra“.',
	'PST_APPLY_SOURCES' => 'Použijte tyto zdroje',
	'PST_TUNING' => 'Dolaďte sladění a výkon',
	'PST_TUNING_EXPLAIN' => 'Výchozí nastavení funguje pro většinu komunit. Tyto ovládací prvky upravujte pouze v případě potřeby.',
	'PST_READY_TO_SAVE' => 'Jste připraveni použít své změny?',
	'PST_SAVE_EXPLAIN' => 'Všechna nastavení na této stránce se uloží společně.',
	'PST_SAVE_SETTINGS' => 'Uložte nastavení',
));
