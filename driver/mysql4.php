<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\driver;

class mysql4 extends mysqli
{
	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return 'mysql4';
	}
}
