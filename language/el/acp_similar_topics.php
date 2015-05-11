<?php
/**
*
* Precise Similar Topics [Greek - el]
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
	'PST_TITLE_ACP'		=> 'Παραπλήσια Θέματα',
	'PST_EXPLAIN'		=> 'Εμφανίζει μια λίστα παραπλήσιων (συναφών) θεμάτων στο τέλος της τρέχουσας σελίδας θέματος.',
	'PST_LEGEND1'		=> 'Γενικές ρυθμίσεις',
	'PST_ENABLE'		=> 'Εμφάνιση παραπλήσιων θεμάτων',
	'PST_LEGEND2'		=> 'Ρυθμίσεις εμφάνισης',
	'PST_LIMIT'			=> 'Αριθμός παραπλήσιων θεμάτων που θα εμφανίζονται',
	'PST_LIMIT_EXPLAIN'	=> 'Εδώ μπορείτε να ορίσετε πόσα παραπλήσια θέματα θα εμφανίζονται. Η προεπιλογή είναι 5 θέματα.',
	'PST_TIME'			=> 'Χρονική περίοδος αναζήτησης',
	'PST_TIME_EXPLAIN'	=> 'Αυτή η επιλογή σας επιτρέπει να ρυθμίσετε την χρονική περίοδο αναζήτησης των παραπλήσιων θεμάτων. Για παράδειγμα, αν επιλέξετε "5 ημέρες" το σύστημα θα εμφανίσει μόνο παραπλήσια θέματα των τελευταίων 5 ημερών. Η προεπιλογή είναι 1 χρόνος.',
	'PST_YEARS'			=> 'Χρόνια',
	'PST_MONTHS'		=> 'Μήνες',
	'PST_WEEKS'			=> 'Εβδομάδες',
	'PST_DAYS'			=> 'Ημέρες',
	'PST_CACHE'			=> 'Μέγεθος μνήμης παραπλήσιων θεμάτων',
	'PST_CACHE_EXPLAIN'	=> 'Η αποθηκευμένη μνήμη παραπλήσιων θεμάτων θα λήξει μετά από αυτό το διάστημα, σε δευτερόλεπτα. Θέστε σε "0" αν θέλετε να απενεργοποιήσετε την αποθηκευμένη μνήμη παραπλήσιων θεμάτων.',
	'PST_LEGEND3'		=> 'Ρυθμίσεις Δημ. Συζήτησης',
	'PST_NOSHOW_LIST'	=> 'Να μην εμφανίζονται σε',
	'PST_NOSHOW_TITLE'	=> 'Να μην εμφανίζονται παραπλήσια θέματα σε',
	'PST_IGNORE_SEARCH'	=> 'Να μην αναζητούνται σε',
	'PST_IGNORE_TITLE'	=> 'Να μην αναζητούνται παραπλήσια θέματα σε',
	'PST_STANDARD'		=> 'Τυπικές',
	'PST_ADVANCED'		=> 'Σύνθετες',
	'PST_ADVANCED_TITLE'=> 'Πατήστε για περισσότερες ρυθμίσεις παραπλήσιων θεμάτων στο θέμα',
	'PST_ADVANCED_EXP'	=> 'Εδώ μπορείτε να επιλέξετε συγκεκριμένες Δημ. Συζητήσεις από τις οποίες θα τραβήξει παραπλήσια θέματα. Μόνο παραπλήσια θέματα που βρέθηκαν στις Δημ. Συζητήσεις που επιλέγετε εδώ θα εμφανιστούν στην ενότητα <strong>%s</strong>.<br /><br />Μην επιλέξετε καμία Δημ. Συζήτηση, αν θέλετε παραπλήσια θέματα από όλες τις Δημ. Συζητήσεις να εμφανίζονται σε αυτή τη Δημ. Συζήτηση.<br /><br />Για να επιλέξτε/αποεπιλέξτε πολλές Δ. Συζητήσεις κρατήστε πατημένο το <code>CTRL</code> και κάντε κλικ.',
	'PST_ADVANCED_FORUM'=> 'Σύνθετες ρυθμίσεις',
	'PST_DESELECT_ALL'	=> 'Αποεπιλογή όλων',
	'PST_LEGEND4'		=> 'Προαιρετκές ρυθμίσεις',
	'PST_WORDS'			=> 'Ειδικές λέξεις για αγνόηση',
	'PST_WORDS_EXPLAIN'	=> 'Προσθέστε ειδικές λέξεις μοναδικές στη Δ. Συζήτησή σας, που θέλετε να αγνοηθούν κατά την εύρεση παραπλήσιων θεμάτων. (Σημείωση: Οι λέξεις που  σήμερα θεωρούνται κοινές στη γλώσσα σας αγνοούνται από προεπιλογή.) Χωρίστε κάθε λέξη με ένα κενό. Με πεζά ή κεφαλαία. Μέγιστο 255 χαρακτήρες.',
	'PST_SAVED'			=> 'Οι ρυθμίσεις των Παραπλήσιων Θεμάτων ενημερώθηκαν',
	'PST_FORUM_INFO'	=> '"Να μην εμφανίζονται σε": Δεν θα εμφανίζονται παραπλήσια θέματα στις επιλεγμένες Δημ. Συζητήσεις.<br />"Να μην αναζητούνται σε": Δεν θα αναζητούνται παραπλήσια θέματα στις επιλεγμένες Δημ. Συζητήσεις.',
	'PST_NO_MYSQL'		=> 'Το "Παραπλήσια Θέματα" δεν είναι συμβατό με τη Δ. Συζήτησή σας. Μπορεί να λειτουργήσει μόνο σε βάσεις δεδομένων με MySQL 4 ή MySQL 5.',
	'PST_WARN_FULLTEXT'	=> 'Το "Παραπλήσια Θέματα" δεν είναι συμβατό με τη Δ. Συζήτησή σας. Χρησιμοποιεί ευρετήρια FULLTEXT τα οποία απαιτούν βάση δεδομένων MySQL 4 ή MySQL 5 και ο πίνακας "phpbb_topics" πρέπει να ρυθμιστεί σε μηχανή αποθήκευσης MyISAM (ή το InnoDB που είναι επιτρεπτό όταν χρησιμοποιείται με MySQL 5.6.4 ή νεώτερη).<br /><br />Αν θέλετε να χρησιμοποιήσετε το "Παραπλήσια Θέματα", η βάση δεδομένων σας μπορεί να ενημερωθεί με ασφάλεια ώστε να υποστηρίζει ευρετήρια FULLTEXT. Οποιεσδήποτε αλλαγές θα επανέλθουν αν ποτέ αποφασίσετε να καταργήσετε τα "Παραπλήσια Θέματα".',
	'PST_ADD_FULLTEXT'	=> 'Ενεργοποιήθηκε η υποστήριξη ευρετηρίων FULLTEXT',
	'PST_SAVE_FULLTEXT'	=> 'Η βάση δεδομένων έχει ενημερωθεί. Τώρα μπορείτε να χρησιμοποιήσετε το "Παραπλήσια Θέματα.',
	'PST_ERR_FULLTEXT'	=> 'Your database could not be updated.',
	'PST_ERR_CONFIG'	=> 'Too many forums were marked in the list of forums. Please try again with a smaller selection.',
));
