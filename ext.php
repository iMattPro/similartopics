<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics;

class ext extends \phpbb\extension\base
{

	public function is_enableable()
	{
		global $db;
		$fulltext = new \vse\similartopics\core\fulltext_support($db);
		if (!$fulltext->is_mysql() & !$fulltext->is_postgres())
		{
			$this->container->get('user')->add_lang_ext('vse/similartopics', 'info_acp_similar_topics');
			trigger_error($this->container->get('user')->lang['PST_NOT_COMPATIBLE'], E_USER_WARNING);
			return false;
		}

		return true;
	}

	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
				if (empty($old_state))
				{
					global $db;
					$fulltext = new \vse\similartopics\core\fulltext_support($db);
					if ($fulltext->is_postgres())
					{
						$this->container->get('user')->add_lang_ext('vse/similartopics', 'info_acp_similar_topics');
						$this->container->get('template')->assign_var('L_EXTENSION_ENABLE_SUCCESS', $this->container->get('user')->lang['EXTENSION_ENABLE_SUCCESS'] .
								$this->container->get('user')->lang['PST_LOG_CREATE_INDEX'] );
					}
				}

				// Run parent enable step method
				return parent::enable_step($old_state);

				break;

			default:

				// Run parent enable step method
				return parent::enable_step($old_state);

				break;
		}
	}
}
