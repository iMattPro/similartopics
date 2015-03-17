<?php
/**
*
* Precise Similar Topics [Polish]
* Translated by liptonace http://zonewarez.pl/
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
	'PST_SETTINGS'		=> 'Ustawienia Podobne tematy',
	'PST_LOG_FULLTEXT'	=> '<strong>Zmieniane w bazie kompatybilności Similar Topics</strong><br />» Zmienił “%s” do silnika przechowywania tabeli MyISAM i dodaje indeks do “topic_title” FULLTEXT',
	'PST_LOG_MSG'		=> '<strong>Zmieniono ustawienia podobnych tematów</strong>',
));
