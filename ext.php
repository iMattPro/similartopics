<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics;

class ext extends \phpbb\extension\base
{
	/**
	 * Extension requires phpBB 4.0.0 or newer.
	 *
	 * @return bool
	 */
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '4.0.0-dev', '>=');
	}
}
