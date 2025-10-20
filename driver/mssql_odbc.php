<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\driver;

/**
 * This class handles similar topics queries for MSSQL ODBC dbms
 */
class mssql_odbc extends mssql
{
	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return 'mssql_odbc';
	}
}
