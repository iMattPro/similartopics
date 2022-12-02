<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\acp;

class similar_topics_module
{
	/** @var string */
	public $page_title;

	/** @var string */
	public $tpl_name;

	/** @var string */
	public $u_action;

	/**
	 * Main ACP module
	 *
	 * @access public
	 * @throws \Exception
	 */
	public function main()
	{
		global $phpbb_container;

		$this->tpl_name = 'acp_similar_topics';

		$this->page_title = $phpbb_container->get('language')
			->lang('PST_TITLE_ACP');

		$phpbb_container->get('vse.similartopics.acp.controller')
			->set_u_action($this->u_action)
			->handle();
	}
}
