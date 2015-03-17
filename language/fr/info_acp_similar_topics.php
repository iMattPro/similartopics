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
	'PST_SETTINGS'		=> 'Paramètres des sujets similaires',
	'PST_LOG_FULLTEXT'	=> '<strong>Base de données modifiée pour la compatibilité avec l’extension « Sujets similaires précis »</strong><br />» Table « %s » corrigée dans le moteur de stockage MyISAM et ajout d’un index FULLTEXT pour le champ « topic_title »',
	'PST_LOG_MSG'		=> '<strong>Modifications des paramètres des sujets similaires</strong>',
));
