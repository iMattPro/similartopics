<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\core;

use vse\similartopics\driver\mysqli;

/**
 * Class is now a wrapper for use by old migrations,
 * to avoid having to edit the old migrations too much.
 */
class fulltext_support extends mysqli
{
	/**
	 * Check if a column is a FULLTEXT index in topics table
	 * This has been deprecated in 1.5.0, replaced by is_fulltext()
	 *
	 * @param string $column Name of the column
	 * @return bool True if column is a FULLTEXT index, false otherwise
	 */
	public function is_index(string $column = 'topic_title'): bool
	{
		return $this->is_fulltext($column, TOPICS_TABLE);
	}
}
