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
	 * Extension requires Mysql or Postgres DBMS and phpBB 3.2.1 or newer.
	 *
	 * @return array|bool If phpBB 3.3.x, return message as to why it could not be installed.
	 *                    Otherwise, just return boolean true/false.
	 */
	public function is_enableable()
	{
		$db = $this->container->get('dbal.conn');
		$valid_db = in_array($db->get_sql_layer(), array('mysqli', 'mysql4', 'postgres'));
		$valid_phpBB = phpbb_version_compare(PHPBB_VERSION, '3.2.1', '>=');
		$enableable = $valid_db && $valid_phpBB;

		// Since showing error messages only works for phpBB 3.3.x, the only message worth showing is
		// if the DBMS is incompatible. That is, we can't show somebody on phpBB 3.2.x. the message that
		// their board is too old, so there's no reason to handle invalid phpBB version messages here.
		if (!$enableable && phpbb_version_compare(PHPBB_VERSION, '3.3.0-b1', '>='))
		{
			$lang = $this->container->get('language');
			$lang->add_lang('acp_similar_topics', 'vse/similartopics');
			return array($lang->lang('PST_NO_COMPAT'));
		}

		return $enableable;
	}
}
