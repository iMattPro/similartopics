<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\controller;

use phpbb\exception\http_exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use phpbb\request\request;
use vse\similartopics\core\similar_topics;

class ajax_controller
{
	/** @var request */
	protected $request;

	/** @var similar_topics */
	protected $similar_topics;

	/**
	 * Constructor
	 *
	 * @param request $request
	 * @param similar_topics $similar_topics
	 */
	public function __construct(request $request, similar_topics $similar_topics)
	{
		$this->request = $request;
		$this->similar_topics = $similar_topics;
	}

	/**
	 * Handle AJAX request for similar topics search
	 *
	 * @return JsonResponse
	 * @throws http_exception
	 */
	public function search_similar_topics()
	{
		if (!$this->request->is_ajax())
		{
			throw new http_exception(403, 'NO_AUTH_OPERATION');
		}

		$query = $this->request->variable('q', '', true);
		$forum_id = $this->request->variable('f', 0);

		if (strlen($query) < 3 || !$this->similar_topics->is_dynamic_available())
		{
			return new JsonResponse(['topics' => []]);
		}

		$topics = $this->similar_topics->search_similar_topics_ajax($query, $forum_id);

		return new JsonResponse(['topics' => $topics]);
	}
}
