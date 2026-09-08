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
use phpbb\request\request;
use Symfony\Component\HttpFoundation\JsonResponse;
use vse\similartopics\core\similar_topics;

class ajax_controller
{
	const MIN_QUERY_LENGTH = 3;
	const MAX_QUERY_LENGTH = 120;
	const MAX_QUERY_TERMS = 12;

	/** @var request */
	protected request $request;

	/** @var similar_topics */
	protected similar_topics $similar_topics;

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
	public function search_similar_topics(): JsonResponse
	{
		if (!$this->request->is_ajax())
		{
			throw new http_exception(403, 'NO_AUTH_OPERATION');
		}

		$query = $this->request->variable('q', '', true);
		$forum_id = $this->request->variable('f', 0);

		if (!$this->is_query_valid($query) || !$this->similar_topics->is_dynamic_available())
		{
			return new JsonResponse(['topics' => []]);
		}

		$topics = $this->similar_topics->search_similar_topics_ajax($query, $forum_id);

		return new JsonResponse(['topics' => $topics]);
	}

	/**
	 * Validate query bounds before any database search.
	 *
	 * @param string $query Search query
	 * @return bool
	 */
	protected function is_query_valid($query)
	{
		$length = utf8_strlen($query);
		if ($length < self::MIN_QUERY_LENGTH || $length > self::MAX_QUERY_LENGTH)
		{
			return false;
		}

		$matches = array();
		$term_count = preg_match_all('#[\p{L}\p{N}]+#u', $query, $matches);

		return $term_count > 0 && $term_count <= self::MAX_QUERY_TERMS;
	}
}
