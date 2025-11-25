<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\migrations;

class release_1_3_0_data extends \phpbb\db\migration\container_aware_migration
{
	public function effectively_installed()
	{
		return isset($this->config['similar_topics_version']) && version_compare($this->config['similar_topics_version'], '1.3.0', '>=');
	}

	public static function depends_on()
	{
		return array(
			'\vse\similartopics\migrations\release_1_1_0_data',
			'\vse\similartopics\migrations\release_1_3_0_schema'
		);
	}

	public function update_data()
	{
		// use module tool explicitly since module.exists does not work in 'if'
		$module_tool = $this->container->get('migrator.tool.module');

		return array(
			// remove any old ACP modules, wrapped in an if blanket to prevent
			// these modules from being added back during extension un-installation.
			array('if', array(
				$module_tool->exists('acp', 'PST_TITLE_ACP', 'PST_TITLE', true),
				array('module.remove', array('acp', 'PST_TITLE_ACP', 'PST_TITLE')),
			)),
			array('if', array(
				$module_tool->exists('acp', 'PST_TITLE_ACP', 'PST_SETTINGS', true),
				array('module.remove', array('acp', 'PST_TITLE_ACP', 'PST_SETTINGS')),
			)),
			// add new ACP module
			array('module.add', array(
				'acp', 'PST_TITLE_ACP', array(
					'module_basename'	=> '\vse\similartopics\acp\similar_topics_module',
					'modes'				=> array('settings'),
				),
			)),
			// Update existing configs
			array('config.update', array('similar_topics_version', '1.3.0')),
		);
	}

	/**
	 * Explicit revert handler for phpBB 4.0+ compatibility
	 *
	 * This fixes uninstall failures in phpBB 4.0.0+ caused by a behavior change in
	 * module removal (commit 07b63fc6a8, ticket PHPBB-17507):
	 *
	 * - phpBB 3.x: Silently succeeded when removing non-existent modules
	 * - phpBB 4.0: Throws MODULE_NOT_EXIST exception when removing non-existent modules
	 *
	 * The problem: This migration uses 'if' conditions to conditionally remove modules
	 * during installation. During automatic reversal (uninstall), the migration helper skips
	 * all 'if' statements, causing it to attempt removal of modules that may not exist,
	 * triggering the exception in phpBB 4.0+.
	 *
	 * The solution: Provide explicit revert_data() that removes the parent category
	 * TOPIC_PREVIEW instead of individual child modules. This works because:
	 * - The parent category always exists (added by release_1_0_0.php)
	 * - Child modules are already removed by prior migration reversals
	 * - Removing an empty parent category never throws exceptions
	 *
	 * @return array
	 */
	public function revert_data()
	{
		return array(
			array('module.remove', array('acp', 'PST_TITLE_ACP')),
		);
	}
}
