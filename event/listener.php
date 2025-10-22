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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\controller\helper;
use phpbb\template\template;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \vse\similartopics\core\similar_topics */
	protected $similar_topics;

	/** @var helper */
	protected $helper;

	/** @var template */
	protected $template;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param \vse\similartopics\core\similar_topics $similar_topics
	 * @param helper $helper
	 * @param template $template
	 */
	public function __construct(\vse\similartopics\core\similar_topics $similar_topics, helper $helper, template $template)
	{
		$this->similar_topics = $similar_topics;
		$this->helper = $helper;
		$this->template = $template;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getSubscribedEvents()
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
	 * @param \phpbb\event\data $event The event object
	 */
	public function display_similar_topics($event)
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
	 * @param \phpbb\event\data $event The event object
	 */
	public function add_permissions($event)
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
	 * @param \phpbb\event\data $event The event object
	 */
	public function dynamic_similar_topics($event)
	{
		if ($event['mode'] !== 'post'
			|| !empty($event['post_data']['topic_id'])
			|| !$this->similar_topics->is_available()
			|| !$this->similar_topics->is_dynamic_enabled())
		{
			return;
		}

		$tpl_ary = [
			'S_DYNAMIC_SIMILAR_TOPICS' => true,
			'U_PST_AJAX_SEARCH' => $this->helper->route('vse_similartopics_ajax_search'),
		];

		if ($this->template->retrieve_var('FORUM_ID') === null)
		{
			$tpl_ary['FORUM_ID'] = isset($event['forum_id']) ? $event['forum_id'] : 0;
		}

		$this->template->assign_vars($tpl_ary);
		$this->similar_topics->add_language();
	}
}
