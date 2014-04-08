<?php
/**
*
* @package Precise Similar Topics
* @copyright (c) 2014 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* Check for FULLTEXT index support
*
* @return 	mixed 	True if FULLTEXT is supported, otherwise return name of unsupported engine
*/
function fulltext_support()
{
	global $db;

	if ($db->sql_layer != 'mysql4' && $db->sql_layer != 'mysqli')
	{
		return false;
	}

	$result = $db->sql_query('SHOW TABLE STATUS LIKE \'' . TOPICS_TABLE . '\'');
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$engine = '';
	if (isset($info['Engine']))
	{
		$engine = strtolower($info['Engine']);
	}
	else if (isset($info['Type']))
	{
		$engine = strtolower($info['Type']);
	}

	// FULLTEXT is supported on InnoDB since MySQL 5.6.4 according to
	// http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
	if ($engine === 'myisam' || ($engine === 'innodb' && phpbb_version_compare($db->sql_server_info(true), '5.6.4', '>=')))
	{
		return true;
	}

	return $engine;
}

/**
* Check if a field is already a FULLTEXT index
*
* @param	string	$field	name of a field
* @return	bool	true means the field is a FULLTEXT index
*/
function is_fulltext($field)
{
	global $db;

	$sql = "SHOW INDEX
		FROM " . TOPICS_TABLE;
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		// deal with older MySQL versions which didn't use Index_type
		$index_type = (isset($row['Index_type'])) ? $row['Index_type'] : $row['Comment'];

		if ($index_type == 'FULLTEXT' && $row['Key_name'] == $field)
		{
			return true;
		}
	}

	return false;
}
