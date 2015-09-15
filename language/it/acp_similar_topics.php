<?php
/**
*
* Precise Similar Topics [Italian]
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
	'PST_TITLE_ACP'		=> 'Argomenti simili',
	'PST_EXPLAIN'		=> '“Argomenti simili” mostra un elenco di argomenti simili in fondo alla pagina dell’argomento corrente.',
	'PST_LEGEND1'		=> 'Impostazioni generali',
	'PST_ENABLE'		=> 'Mostra argomenti simili',
	'PST_LEGEND2'		=> 'Impostazioni caricamento',
	'PST_LIMIT'			=> 'Numero argomenti simili da mostrare',
	'PST_LIMIT_EXPLAIN'	=> 'È possibile definire quanti topic simili mostrare; il valore predefinito è 5.',
	'PST_TIME'			=> 'Arco temporale',
	'PST_TIME_EXPLAIN'	=> 'Specifica il periodo di tempo in cui agisce la ricerca di argomenti simili. per esempio, impostando “5 giorni” l’elenco mostrerà argomenti creati negli ultimi 5 giorni. Il valore predefinito è 1 anno.',
	'PST_YEARS'			=> 'Anni',
	'PST_MONTHS'		=> 'Mesi',
	'PST_WEEKS'			=> 'Settimane',
	'PST_DAYS'			=> 'Giorni',
	'PST_CACHE'			=> 'Lunghezza cache',
	'PST_CACHE_EXPLAIN'	=> 'Gli argomenti salvati nella cache saranno rimossi dopo il periodo di tempo (in secondi) specificato. Impostare a 0 per disabilitare la cache di argomenti simili.',
	'PST_LEGEND3'		=> 'Impostazioni forum',
	'PST_NOSHOW_LIST'	=> 'Non mostrare in',
	'PST_NOSHOW_TITLE'	=> 'Non mostrare argomenti simili in',
	'PST_IGNORE_SEARCH'	=> 'Non cercare in',
	'PST_IGNORE_TITLE'	=> 'Non cercare argomenti simili in',
	'PST_STANDARD'		=> 'Base',
	'PST_ADVANCED'		=> 'Avanzate',
	'PST_ADVANCED_TITLE'=> 'Cliccare per configurare le impostazioni avanzate per',
	'PST_ADVANCED_EXP'	=> 'È possibile selezionare i forum in cui cercare argomenti simili. Solo gli argomenti trovati nei forum selezionati saranno mostrati in <strong>%s</strong>.<br /><br />Per la ricerca in tutti i forum disponibili, non selezionare alcun forum.<br /><br />Per la selezione di più forum, tenere premuto <samp>CTRL</samp> (o <samp>&#8984;CMD</samp> su Mac) e cliccare.',
	'PST_ADVANCED_FORUM'=> 'Impostazioni avanzate forum',
	'PST_DESELECT_ALL'	=> 'Deseleziona tutti',
	'PST_LEGEND4'		=> 'Impostazioni opzionali',
	'PST_WORDS'			=> 'Termini speciali da ignorare',
	'PST_WORDS_EXPLAIN'	=> 'È possibile escludere alcuni termini dalla ricerca di argomenti simili. I termini devono essere separati da uno spazio; non è fatta distinzione tra maiuscole e minuscole; massimo 255 caratteri.<br /><br />Nota: <em>i termini considerati comuni della propria lingua sono ignorati per impostazione predefinita.</em>',
	'PST_SAVED'			=> 'Impostazioni Argomenti simili aggiornate',
	'PST_FORUM_INFO'	=> '“Non mostrare in”: nei forum selezionati, non saranno mostrati gli argomenti simili.<br />“Non cercare in”: nei forum selezionati, non saranno cercati gli argomenti simili.',
	'PST_NO_MYSQL'		=> '“Argomenti simili” non è compatibile con la propria board: quest’estensione richiede un database MySQL versione 4 o 5.',
	'PST_WARN_FULLTEXT'	=> '“Argomenti simili” non è compatibile con la propria board.<br />Quest’estensione fa uso di indici fulltext MySQL disponibili su tavole MyISAM o InnoDB. È richiesto MySQL versione 5.6.4 o successiva per l’uso di indici fulltext su tavole InnoDB.<br /><br />Per usare Argomenti simili, è possibile aggiornare il proprio database per il supporto agli indici fulltext; i cambiamenti sono reversibili qualora l’estensione venga rimossa.',
	'PST_ADD_FULLTEXT'	=> 'Sì, abilita supporto a indici fulltext',
	'PST_SAVE_FULLTEXT'	=> 'Il database è stato aggiornato. È ora possibile usare Argomenti simili.',
	'PST_ERR_FULLTEXT'	=> 'Il database non è stato aggiornato.',
	'PST_ERR_CONFIG'	=> 'Troppi forum selezionati in elenco, ridurre la selezione.',
));
