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

/**
 * This class handles similar topics queries for MSSQL Native dbms
 */
class mssqlnative extends mssql
{
	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return 'mssqlnative';
	}
}
