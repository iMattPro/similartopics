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
		$enableable = in_array($db->get_sql_layer(), array('mysqli', 'mysql4', 'postgres'));

		if (!$enableable && phpbb_version_compare(PHPBB_VERSION, '3.3.0-b1', '>='))
		{
			$lang = $this->container->get('language');
			$lang->add_lang('acp_similar_topics', 'vse/similartopics');
			return array($lang->lang('PST_NO_COMPAT'));
		}

		return $enableable;
	}
}
