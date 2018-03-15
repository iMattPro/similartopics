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
	public function is_enableable()
	{
		$db = $this->container->get('dbal.conn');
		return in_array($db->get_sql_layer(), array('mysqli', 'mysql4', 'postgres'));
	}
}
