<?php
/**
*
* Precise Similar Topics [French]
* Translated by Geolim4.com & Galixte (http://www.galixte.com)
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
	'PST_TITLE_ACP'		=> 'Sujets similaires précis', //Do not rename, even as in French
	'PST_EXPLAIN'		=> 'Affiche une liste des sujets similaires au bas de la page du sujet visionné.',
	'PST_LEGEND1'		=> 'Paramètre général',
	'PST_ENABLE'		=> 'Afficher les sujets similaires',
	'PST_LEGEND2'		=> 'Paramètres principaux',
	'PST_LIMIT'			=> 'Nombre du ou des sujets similaires à afficher',
	'PST_LIMIT_EXPLAIN'	=> 'Ici vous pouvez sélectionner le ou les nombres de sujets similaires à afficher. La valeur par défaut est de 5 sujets.',
	'PST_TIME'			=> 'Période de recherche',
	'PST_TIME_EXPLAIN'	=> 'Cette option vous permet de choisir la période de recherche des sujets similaires à afficher. Par exemple, si vous choisissez une période de « 5 jours » le système affichera uniquement les sujets créés depuis 5 jours. La valeur par défaut est de 1 an.',
	'PST_YEARS'			=> 'Année(s)',
	'PST_MONTHS'		=> 'Mois',
	'PST_WEEKS'			=> 'Semaine(s)',
	'PST_DAYS'			=> 'Jour(s)',
	'PST_CACHE'			=> 'Taille du cache des sujets similaires',
	'PST_CACHE_EXPLAIN'	=> 'La mise en cache des sujets similaires expirera après cette période. Paramétrez sur 0 pour désactiver la mise en cache des sujets similaires.',
	'PST_LEGEND3'		=> 'Paramètres des forums',
	'PST_NOSHOW_LIST'	=> 'Ne pas afficher dans',
	'PST_NOSHOW_TITLE'	=> 'Ne pas afficher les sujets similaires dans le forum :',
	'PST_IGNORE_SEARCH'	=> 'Ne pas rechercher dans',
	'PST_IGNORE_TITLE'	=> 'Ne pas rechercher les sujets similaires dans le forum :',
	'PST_STANDARD'		=> 'Standard',
	'PST_ADVANCED'		=> 'Avancé',
	'PST_ADVANCED_TITLE'=> 'Cliquez ici pour configurer les paramètres avancés du forum :',
	'PST_ADVANCED_EXP'	=> 'Ici vous pouvez sélectionner des forums spécifiques dont les sujets similaires proviendront. Seuls les sujets similaires trouvés dans les forums sélectionnés ici seront affichés dans le forum : <strong>%s</strong>.<br /><br />Ne sélectionnez aucun forum si vous souhaitez que tous les sujets similaires de tous les forums soient consultables dans ce forum.<br /><br />Vous pouvez sélectionner ou désélectionner plusieurs forums en maintenant appuyée la touche <samp>CTRL</samp> (ou <samp>&#8984;CMD</samp> sur Mac) du clavier de votre ordinateur et en cliquant.',
	'PST_ADVANCED_FORUM'=> 'Paramètres avancés du forum',
	'PST_DESELECT_ALL'	=> 'Tout désélectionner',
	'PST_LEGEND4'		=> 'Paramètres optionnels',
	'PST_WORDS'			=> 'Mots spécifiques à ignorer',
	'PST_WORDS_EXPLAIN'	=> 'Ajoutez des mots spécifiques à votre forum qui doivent être ignorés lors de la recherche des sujets similaires. (Remarque : Les mots qui sont actuellement considérés comme communs dans votre langue sont déjà ignorés par défaut.) Séparez chaque mot par un espace. Insensible à la casse. 255 caractères maximum.',
	'PST_SAVED'			=> 'Les paramètres des sujets similaires ont été mis à jour',
	'PST_FORUM_INFO'	=> '« Ne pas afficher dans » : N’affiche pas de sujets similaires dans les forums sélectionnés.<br /> « Ne pas rechercher dans » : Ne recherche pas de sujets similaires dans les forums sélectionnés.<br /><br />',
	'PST_NO_MYSQL'		=> 'L’extension « Sujets similaires précis » n’est pas compatible avec votre forum. Cette extension fonctionne uniquement avec une base de données MySQL 4 ou 5.',
	'PST_WARN_FULLTEXT'	=> 'L’extension « Sujets similaires précis » n’est pas compatible avec votre forum. Cette extension utilise les index FULLTEXT de MySQL utilisés uniquement par les tables MyISAM ou InnoDB. La version de MySQL 5.6.4 ou une version plus récente est requise pour les index FULLTEXT fonctionnant avec des tables InnoDB.<br /><br />Si vous souhaitez utiliser l’extension « Sujets similaires précis », votre base de données peut être mise à jour en toute sécurité pour supporter les index FULLTEXT. Toutes les modifications apportées seront rétablies si jamais vous décidez de supprimer l’extension « Sujets similaires précis ».',
	'PST_ADD_FULLTEXT'	=> 'Oui, activer le support des index FULLTEXT',
	'PST_SAVE_FULLTEXT'	=> 'Votre base de données a été mise à jour. Vous pouvez maintenant profiter de l’extension « Sujets similaires précis ».',
	'PST_ERR_FULLTEXT'	=> 'Votre base de données ne peut être mise à jour.',
	'PST_ERR_CONFIG'	=> 'De trop nombreux forums ont été sélectionnés dans la liste des forums. Veuillez réessayer avec une sélection plus restreinte.',
));
