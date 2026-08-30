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
	'PST_ENABLE_EXPLAIN'=> 'Mostra argomenti simili nelle discussioni.',
	'PST_LEGEND2'		=> 'Impostazioni caricamento',
	'PST_LIMIT'			=> 'Numero argomenti simili da mostrare',
	'PST_LIMIT_EXPLAIN'	=> 'È possibile definire quanti topic simili mostrare; il valore predefinito è 5.',
	'PST_TIME'			=> 'Arco temporale',
	'PST_TIME_EXPLAIN'	=> 'Specifica il periodo di tempo in cui agisce la ricerca di argomenti simili. per esempio, impostando “5 giorni” l’elenco mostrerà argomenti creati negli ultimi 5 giorni. Il valore predefinito è 1 anno. Imposta il valore su 0 per non applicare limiti di tempo.',
	'PST_YEARS'			=> 'Anni',
	'PST_MONTHS'		=> 'Mesi',
	'PST_WEEKS'			=> 'Settimane',
	'PST_DAYS'			=> 'Giorni',
	'PST_CACHE'			=> 'Lunghezza cache',
	'PST_CACHE_EXPLAIN'	=> 'Gli argomenti salvati nella cache saranno rimossi dopo il periodo di tempo (in secondi) specificato. Impostare a 0 per disabilitare la cache di argomenti simili.',
	'PST_DYNAMIC'		=> 'Mostra dinamicamente gli argomenti simili',
	'PST_DYNAMIC_EXPLAIN'=> 'Mostra argomenti simili mentre l’utente digita il titolo durante la creazione di un nuovo argomento.',
	'PST_SENSE'			=> 'Sensibilità della ricerca',
	'PST_SENSE_EXPLAIN'	=> 'Per database MySQL o Postgres puoi impostare la sensibilità della ricerca su un valore da 1 a 10. Usa un numero più basso se non vengono mostrati argomenti simili. Impostazione consigliata: %d',
	'PST_LEGEND3'		=> 'Impostazioni forum',
	'PST_NOSHOW_LIST'	=> 'Non mostrare in',
	'PST_NOSHOW_TITLE'	=> 'Non mostrare argomenti simili in',
	'PST_IGNORE_SEARCH'	=> 'Non cercare in',
	'PST_IGNORE_TITLE'	=> 'Non cercare argomenti simili in',
	'PST_STANDARD'		=> 'Base',
	'PST_ADVANCED'		=> 'Avanzate',
	'PST_ADVANCED_TITLE'=> 'Cliccare per configurare le impostazioni avanzate per',
	'PST_ADVANCED_EXP'	=> 'È possibile selezionare i forum in cui cercare argomenti simili. Solo gli argomenti trovati nei forum selezionati saranno mostrati in <strong>%s</strong>.<br><br>Per la ricerca in tutti i forum disponibili, non selezionare alcun forum.<br><br>Per la selezione di più forum, tenere premuto <samp>CTRL</samp> (o <samp>&#8984;CMD</samp> su Mac) e cliccare.',
	'PST_ADVANCED_FORUM'=> 'Impostazioni avanzate forum',
	'PST_DESELECT_ALL'	=> 'Deseleziona tutti',
	'PST_LEGEND4'		=> 'Impostazioni opzionali',
	'PST_WORDS'			=> 'Termini speciali da ignorare',
	'PST_WORDS_EXPLAIN'	=> 'È possibile escludere alcuni termini dalla ricerca di argomenti simili. I termini devono essere separati da uno spazio; non è fatta distinzione tra maiuscole e minuscole.<br><br>Nota: <em>i termini considerati comuni della propria lingua sono ignorati per impostazione predefinita.</em>',
	'PST_SAVED'			=> 'Impostazioni Argomenti simili aggiornate',
	'PST_FORUM_INFO'	=> '“Non mostrare in”: nei forum selezionati, non saranno mostrati gli argomenti simili.<br>“Non cercare in”: nei forum selezionati, non saranno cercati gli argomenti simili.',
	'PST_NO_COMPAT'		=> '“Argomenti simili” non è compatibile con la propria board: quest’estensione richiede un database MySQL versione 4 o 5 o PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'Troppi forum selezionati in elenco, ridurre la selezione.',
	'PST_FEATURES' => 'Scegli dove visualizzare argomenti simili',
	'PST_FEATURES_EXPLAIN' => 'Attiva o disattiva ogni opzione per l’intero forum.',
	'PST_TIME_UNIT' => 'Unità del periodo di ricerca',
	'PST_CACHE_OFF' => 'Spento',
	'PST_CACHE_5_MINUTES' => '5 minuti',
	'PST_CACHE_15_MINUTES' => '15 minuti',
	'PST_CACHE_30_MINUTES' => '30 minuti',
	'PST_CACHE_1_HOUR' => '1 ora',
	'PST_CACHE_2_HOURS' => '2 ore',
	'PST_CACHE_4_HOURS' => '4 ore',
	'PST_CACHE_8_HOURS' => '8 ore',
	'PST_CACHE_12_HOURS' => '12 ore',
	'PST_CACHE_24_HOURS' => '24 ore',
	'PST_CACHE_CUSTOM' => 'Personalizzato: %d secondi',
	'PST_RESULTS' => 'Dai forma ai risultati',
	'PST_RESULTS_EXPLAIN' => 'Imposta il numero di argomenti simili visualizzati e quanto recenti devono essere tali argomenti.',
	'PST_FORUM_RULES' => 'Decidi come partecipare ciascun forum',
	'PST_FORUM_RULES_EXPLAIN' => 'Ogni forum ha due semplici interruttori. Utilizza "Scegli fonti" solo quando un forum necessita di un proprio pool di ricerca.',
	'PST_FORUMS_MANAGED' => 'forum',
	'PST_CUSTOM_RULES' => 'regole di origine personalizzate',
	'PST_FILTER_FORUMS' => 'Trova un forum...',
	'PST_NO_FORUM_MATCH' => 'Nessun forum corrisponde a quella ricerca.',
	'PST_SHOW_HERE' => 'Mostra argomenti simili qui',
	'PST_SHOW_HERE_EXPLAIN' => 'I visitatori di questo forum possono vedere argomenti simili.',
	'PST_SEARCHABLE' => 'Rendi questo forum disponibile come fonte',
	'PST_SEARCHABLE_EXPLAIN' => 'Le ricerche standard possono trovare argomenti da questo forum.',
	'PST_SEARCH_SOURCES' => 'Dove cerca questo forum',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Utilizza tutti i forum disponibili o scegli un set personalizzato.',
	'PST_CHOOSE_SOURCES' => 'Scegli le fonti',
	'PST_SOURCE_ALL' => 'Tutti i forum disponibili',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d forum selezionato',
		2 => '%d forum selezionati',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Scegli da dove provengono argomenti simili',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Questa scelta vale solo per il forum sopra indicato. Non cambia nessun altro forum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Cerca in tutti i forum il cui interruttore "disponibile come fonte" è attivo. I nuovi forum si uniscono automaticamente.',
	'PST_SOURCE_CUSTOM' => 'Solo forum selezionati',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Cerca in un elenco fisso di forum. Ideale per sezioni strettamente correlate.',
	'PST_FILTER_SOURCES' => 'Trova un forum di origine...',
	'PST_SELECT_AVAILABLE' => 'Seleziona tutto disponibile',
	'PST_CLEAR' => 'Cancella selezione',
	'PST_GLOBALLY_AVAILABLE' => 'Disponibile',
	'PST_GLOBALLY_UNAVAILABLE' => 'Non disponibile globalmente',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Le scelte personalizzate sostituiscono le opzioni di disponibilità globale. È comunque possibile effettuare ricerche in un forum selezionato anche se contrassegnato come "Non disponibile globalmente".',
	'PST_SELECT_ONE_SOURCE' => 'Scegli almeno un forum o utilizza "Tutti i forum disponibili".',
	'PST_APPLY_SOURCES' => 'Usa queste fonti',
	'PST_TUNING' => 'Ottimizzazione dell\'abbinamento e delle prestazioni',
	'PST_TUNING_EXPLAIN' => 'Le impostazioni predefinite funzionano per la maggior parte delle comunità. Regola questi controlli solo quando necessario.',
	'PST_READY_TO_SAVE' => 'Pronto per applicare le modifiche?',
	'PST_SAVE_EXPLAIN' => 'Tutte le impostazioni in questa pagina vengono salvate insieme.',
	'PST_SAVE_SETTINGS' => 'Salva impostazioni',
));
