<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\event;

use phpbb\event\data;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\controller\helper;
use phpbb\template\template;
use vse\similartopics\core\similar_topics;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var similar_topics */
	protected similar_topics $similar_topics;

	/** @var helper */
	protected helper $controller_helper;

	/** @var template */
	protected template $template;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param similar_topics $similar_topics
	 * @param helper $helper
	 * @param template $template
	 */
	public function __construct(similar_topics $similar_topics, helper $helper, template $template)
	{
		$this->similar_topics = $similar_topics;
		$this->controller_helper = $helper;
		$this->template = $template;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'core.viewtopic_modify_page_title'		=> 'display_similar_topics',
			'core.permissions'						=> 'add_permissions',
			'core.posting_modify_template_vars'		=> 'dynamic_similar_topics',
		];
	}

	/**
	 * Display similar topics
	 *
	 * @access public
	 * @param data $event The event object
	 */
	public function display_similar_topics(data $event): void
	{
		// Return early if similar topics is disabled
		if (!$this->similar_topics->is_available())
		{
			return;
		}

		$this->similar_topics->display_similar_topics($event['topic_data']);
	}

	/**
	 * Add custom permissions language variables
	 *
	 * @access public
	 * @param data $event The event object
	 */
	public function add_permissions(data $event): void
	{
		$event->update_subarray('permissions', 'u_similar_topics', [
			'lang' => 'ACL_U_SIMILARTOPICS',
			'cat' => 'misc'
		]);
	}

	/**
	 * Display dynamic similar topics when creating posts
	 *
	 * @access public
	 * @param data $event The event object
	 */
	public function dynamic_similar_topics(data $event): void
	{
		if ($event['mode'] !== 'post'
			|| !empty($event['post_data']['topic_id'])
			|| !$this->similar_topics->is_dynamic_available())
		{
			return;
		}

		$tpl_ary = [
			'S_DYNAMIC_SIMILAR_TOPICS' => true,
			'U_PST_AJAX_SEARCH' => $this->controller_helper->route('vse_similartopics_ajax_search'),
		];

		if ($this->template->retrieve_var('FORUM_ID') === null)
		{
			$tpl_ary['FORUM_ID'] = $event['forum_id'] ?? 0;
		}

		$this->template->assign_vars($tpl_ary);
		$this->similar_topics->add_language();
	}
}
