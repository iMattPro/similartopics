<?php
/**
*
* Precise Similar Topics [Română]
* Translated by Ionuţ Butnaru and corrected by Ivan Petre Paul, both from phpBB Romanian Community www.phpbb.ro
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
	'PST_TITLE_ACP'		=> 'Subiecte similare precise',
	'PST_EXPLAIN'		=> 'Subiecte similare precise afișează o listă de subiecte similare în partea de jos a paginii subiect curente.',
	'PST_LEGEND1'		=> 'Setări generale',
	'PST_ENABLE'		=> 'Activaţi subiectele similare',
	'PST_ENABLE_EXPLAIN'=> 'Afișează subiecte similare în firele de discuție.',
	'PST_LEGEND2'		=> 'Încarcă setări',
	'PST_LIMIT'			=> 'Numărul de subiecte similare care să fie afişate',
	'PST_LIMIT_EXPLAIN'	=> 'Aici puteţi preciza câte subiecte similare să fie afişate. Implicite sunt 5.',
	'PST_TIME'			=> 'Perioada de căutare',
	'PST_TIME_EXPLAIN'	=> 'Această opţiune vă permite să configuraţi perioada de căutare a subiectelor similare. De exemplu, dacă alegeţi “5 zile” sistemul ca afişa numai subiectele similare din ultimele 5 zile. Implicit este un an. Setați valoarea la 0 pentru a nu aplica nicio limită de timp.',
	'PST_YEARS'			=> 'Ani',
	'PST_MONTHS'		=> 'Luni',
	'PST_WEEKS'			=> 'Săptămâni',
	'PST_DAYS'			=> 'Zile',
	'PST_CACHE'			=> 'Dimeniune cache',
	'PST_CACHE_EXPLAIN'	=> 'Subiectele similare memorate vor expira după acest timp, în secunde. Alegeţi 0 dacă doriţi să dezactivaţi memorarea subiectelor similare.',
	'PST_DYNAMIC'		=> 'Afișează dinamic subiecte similare',
	'PST_DYNAMIC_EXPLAIN'=> 'Afișează subiecte similare pe măsură ce utilizatorii scriu titlul unui subiect nou.',
	'PST_SENSE'			=> 'Sensibilitatea căutării',
	'PST_SENSE_EXPLAIN'	=> 'Pentru bazele de date MySQL sau Postgres, puteți seta sensibilitatea căutării la o valoare între 1 și 10. Folosiți un număr mai mic dacă nu sunt afișate subiecte similare. Setare recomandată: %d',
	'PST_LEGEND3'		=> 'Setări forum',
	'PST_NOSHOW_LIST'	=> 'Nu afişa în',
	'PST_NOSHOW_TITLE'	=> 'Nu afişa subiecte similare în',
	'PST_IGNORE_SEARCH'	=> 'Nu căuta în',
	'PST_IGNORE_TITLE'	=> 'Nu căuta subiecte similare în',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Avansat',
	'PST_ADVANCED_TITLE'=> 'Apăsaţi pentru a configura setările avansate ale subiectelor similare pentru',
	'PST_ADVANCED_EXP'	=> 'Aici puteţi alege forumuri specifice din care să afişaţi subiecte similare. Numai subiectele similare găsite în forumurile pe care le-aţi ales aici vor fi afişate în <strong>%s</strong>.<br><br>Nu alegeţi orice forumuri dacă doriţi ca toate subiectele similare din toate forumurile ce pot fi căutate să fie afişate în acest forum.<br><br>Selectaţi/Deselectaţi mai multe forumuri ţinând apăsată tasta <code>CTRL</code> şi făcând click.',
	'PST_ADVANCED_FORUM'=> 'Setări avansate pe forum pentru',
	'PST_DESELECT_ALL'	=> 'Deselectaţi tot',
	'PST_LEGEND4'		=> 'Setări opţionale',
	'PST_WORDS'			=> 'Cuvinte speciale ignorate',
	'PST_WORDS_EXPLAIN'	=> 'Adăugaţi cuvinte speciale unice care ar trebui ignorate atunci când sunt căutate subiecte similare. (Reţineţi: Cuvintele care sunt cunoscute ca frecvente în limba dumneavoastră sunt implicit ignorate.) Separaţi cuvintele între ele cu un spaţiu. Majusculele sunt nesemnificative.',
	'PST_SAVED'			=> 'Setări actualizate',
	'PST_FORUM_INFO'	=> '“Nu afişa în”: Nu va afişa subiectele similare în forumurile alese.<br>“Nu căuta în” : Nu va căuta subiecte similare în forumurile alese.',
	'PST_NO_COMPAT'		=> 'MODificarea Subiecte similare nu va funcţiona pe forumul dumneavoastr. MODificarea Subiecte similare necesită o bază de date cu MySQL 4 sau MySQL 5 sau PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'Prea multe forumuri au fost marcate în lista de forumuri. Vă rugăm să încercați din nou, cu o selecție mai mic.',
	'PST_FEATURES' => 'Alegeți unde apar subiecte similare',
	'PST_FEATURES_EXPLAIN' => 'Activați sau dezactivați fiecare opțiune pentru întregul forum.',
	'PST_TIME_UNIT' => 'Unitatea de perioadă de căutare',
	'PST_CACHE_OFF' => 'Oprit',
	'PST_CACHE_5_MINUTES' => '5 minute',
	'PST_CACHE_15_MINUTES' => '15 minute',
	'PST_CACHE_30_MINUTES' => '30 de minute',
	'PST_CACHE_1_HOUR' => '1 oră',
	'PST_CACHE_2_HOURS' => '2 ore',
	'PST_CACHE_4_HOURS' => '4 ore',
	'PST_CACHE_8_HOURS' => '8 ore',
	'PST_CACHE_12_HOURS' => '12 ore',
	'PST_CACHE_24_HOURS' => '24 de ore',
	'PST_CACHE_CUSTOM' => 'Personalizat: %d secunde',
	'PST_RESULTS' => 'Modelați rezultatele',
	'PST_RESULTS_EXPLAIN' => 'Setați câte subiecte similare apar și cât de recente trebuie să fie acele subiecte.',
	'PST_FORUM_RULES' => 'Decide cum participă fiecare forum',
	'PST_FORUM_RULES_EXPLAIN' => 'Fiecare forum are două comutatoare simple. Folosiți „Alegeți sursele” numai atunci când un forum are nevoie de propriul grup de căutare.',
	'PST_FORUMS_MANAGED' => 'forumuri',
	'PST_CUSTOM_RULES' => 'reguli de sursă personalizate',
	'PST_FILTER_FORUMS' => 'Găsiți un forum...',
	'PST_NO_FORUM_MATCH' => 'Niciun forum nu corespunde acestei căutări.',
	'PST_SHOW_HERE' => 'Afișați subiecte similare aici',
	'PST_SHOW_HERE_EXPLAIN' => 'Vizitatorii acestui forum pot vedea subiecte similare.',
	'PST_SEARCHABLE' => 'Faceți acest forum disponibil ca sursă',
	'PST_SEARCHABLE_EXPLAIN' => 'Căutările standard pot găsi subiecte de pe acest forum.',
	'PST_SEARCH_SOURCES' => 'Unde caută acest forum',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Folosiți fiecare forum disponibil sau alegeți un set personalizat.',
	'PST_CHOOSE_SOURCES' => 'Alegeți sursele',
	'PST_SOURCE_ALL' => 'Toate forumurile disponibile',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d forum selectat',
		2 => '%d forumuri selectate',
		3 => '%d de forumuri selectate',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Alegeți de unde provin subiecte similare',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Această alegere se aplică numai forumului prezentat mai sus. Nu schimbă niciun alt forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Căutați în fiecare forum al cărui comutator „disponibil ca sursă” este activat. Noile forumuri se alătură automat.',
	'PST_SOURCE_CUSTOM' => 'Doar forumuri selectate',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Căutați o listă fixă de forumuri. Cel mai bun pentru secțiuni strâns legate.',
	'PST_FILTER_SOURCES' => 'Găsiți un forum sursă...',
	'PST_SELECT_AVAILABLE' => 'Selectați toate cele disponibile',
	'PST_CLEAR' => 'Ștergeți selecția',
	'PST_GLOBALLY_AVAILABLE' => 'Disponibil',
	'PST_GLOBALLY_UNAVAILABLE' => 'Indisponibil global',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Opțiunile personalizate înlocuiesc comutatoarele de disponibilitate globală. Un forum selectat poate fi căutat chiar și atunci când este marcat „Indisponibil global”.',
	'PST_SELECT_ONE_SOURCE' => 'Alegeți cel puțin un forum sau folosiți „Toate forumurile disponibile”.',
	'PST_APPLY_SOURCES' => 'Folosiți aceste surse',
	'PST_TUNING' => 'Ajustați potrivirea și performanța',
	'PST_TUNING_EXPLAIN' => 'Valorile implicite funcționează pentru majoritatea comunităților. Reglați aceste comenzi numai atunci când este necesar.',
	'PST_READY_TO_SAVE' => 'Ești gata să aplici modificările?',
	'PST_SAVE_EXPLAIN' => 'Toate setările de pe această pagină se salvează împreună.',
	'PST_SAVE_SETTINGS' => 'Salvați setările',
));
