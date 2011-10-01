<?php
/**
*
* @author VSE (Matt Friedman) maf675@gmail.com
* @package umil
* @version $Id install_similar_topics.php 14 11/7/10 3:49 PM VSE $
* @copyright (c) 2010 VSE
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'PST_TITLE';

/*
* The name of the config variable which will hold the currently installed version
* UMIL will handle checking, setting, and updating the version itself.
*/
$version_config_name = 'similar_topics_version';

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
*/
$language_file = 'mods/info_acp_similar_topics';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	// Version 1.1.0
	'1.1.0'	=> array(
		// Set default configuration variables
		'config_add' => array(
			array('similar_topics', '0'),
			array('similar_topics_limit', '5'),
			array('similar_topics_hide', ''),
			array('similar_topics_ignore', ''),
			array('similar_topics_type', 'y'),
			array('similar_topics_time', '365'),
		),

		// Add the new similar_topic_forums column to the forums table
		'table_column_add' => array(
			array(FORUMS_TABLE, 'similar_topic_forums', array('VCHAR_UNI', '')),
		),

		// Alright, now lets add some modules to the ACP
		'module_add' => array(
			// First, lets add a new category named ACP_CAT_TEST_MOD to ACP_CAT_DOT_MODS
			array('acp', 'ACP_CAT_DOT_MODS', 'PST_TITLE_ACP'),

			// Now we will add the settings and features modes from the acp_board module to the ACP_CAT_TEST_MOD category using the "automatic" method.
			array('acp', 'PST_TITLE_ACP', array(
					'module_basename'		=> 'similar_topics',
				),
			),
		),
		
		// Custom function to update SQL topic_title table to FULLTEXT
		'custom'	=> 'make_fulltext',
	),

	// Version 1.1.1
	'1.1.1'	=> array(
		// Add permission settings
		'permission_add' => array(
			array('u_similar_topics', true),
		),

		// Set up some permission defaults
		'permission_set' => array(
			array('ROLE_USER_FULL', 'u_similar_topics'),
			array('ROLE_USER_STANDARD', 'u_similar_topics'),
    		array('REGISTERED', 'u_similar_topics', 'group'),
    		array('REGISTERED_COPPA', 'u_similar_topics', 'group'),
		),

		// Lets add a config to store the cache length
		'config_add' => array(
			array('similar_topics_cache', '0'),
		),

		// Lets change our similar_topics_time to 1 year timestamp
		'config_update'	=> array(
			array('similar_topics_time', '31536000'),
		),

		// purge the cache
		'cache_purge' => array(),
	),

	// Version 1.1.2
	'1.1.2' => array(
		// Nothing changed in this version.
	),

	// Version 1.1.3
	'1.1.3' => array(
		// Nothing changed in this version.
	),

	// Version 1.1.4
	'1.1.4' => array(
		// Nothing changed in this version.
	),

	// Version 1.1.5
	'1.1.5' => array(
		// Nothing changed in this version.
	),

	// Version 1.1.6
	'1.1.6' => array(
		// Nothing changed in this version.
	),

);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

/*
* Here is our custom function that will be called for version 1.1.0.
*
* @param string $action The action (install|update|uninstall) will be sent through this.
* @param string $version The version this is being run for will be sent through this.
*/
function make_fulltext($action, $version)
{
	global $db, $table_prefix, $umil;

	if ($action == 'install')
	{
		// Run this when installing
		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (topic_title)';
		$db->sql_query($sql);

		return 'PST_FULLTEXT_ADD';
	}

	if ($action == 'uninstall')
	{
		// Run this when uninstalling
		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' DROP INDEX topic_title';
		$db->sql_query($sql);

		return 'PST_FULLTEXT_DROP';
	}
}

?>