<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\controller;

use phpbb\request\request;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use vse\similartopics\controller\ajax_controller;
use vse\similartopics\core\similar_topics;
use phpbb\exception\http_exception;

class ajax_controller_test extends phpbb_test_case
{
	/** @var MockObject|request */
	protected MockObject|request $request;

	/** @var MockObject|similar_topics */
	protected MockObject|similar_topics $similar_topics;

	/** @var ajax_controller */
	protected ajax_controller $controller;

	protected function setUp(): void
	{
		parent::setUp();

		$this->request = $this->createMock(request::class);
		$this->similar_topics = $this->createMock(similar_topics::class);

		$this->controller = new ajax_controller(
			$this->request,
			$this->similar_topics
		);
	}

	public function test_search_similar_topics_not_ajax(): void
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(false);

		$this->expectException(http_exception::class);
		$this->controller->search_similar_topics();
	}

	public function test_search_similar_topics_short_query(): void
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(true);

		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default, $raw = false) use (&$call_count) {
				$call_count++;
				if ($call_count === 1)
				{
					return 'ab';
				}
				return 1;
			});

		$response = $this->controller->search_similar_topics();
		$data = json_decode($response->getContent(), true);

		$this->assertEquals(['topics' => []], $data);
	}

	public function test_search_similar_topics_not_available(): void
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(true);

		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default, $raw = false) use (&$call_count) {
				$call_count++;
				if ($call_count === 1)
				{
					return 'test query';
				}
				return 1;
			});

		$this->similar_topics->expects($this->once())
			->method('is_dynamic_available')
			->willReturn(false);

		$response = $this->controller->search_similar_topics();
		$data = json_decode($response->getContent(), true);

		$this->assertEquals(['topics' => []], $data);
	}

	public function test_search_similar_topics_success(): void
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(true);

		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default, $raw = false) use (&$call_count) {
				$call_count++;
				if ($call_count === 1)
				{
					return 'test query';
				}
				return 1;
			});

		$this->similar_topics->expects($this->once())
			->method('is_dynamic_available')
			->willReturn(true);

		$expected_topics = [
			['id' => 1, 'title' => 'Test Topic', 'url' => 'viewtopic.php?t=1']
		];

		$this->similar_topics->expects($this->once())
			->method('search_similar_topics_ajax')
			->with('test query', 1)
			->willReturn($expected_topics);

		$response = $this->controller->search_similar_topics();
		$data = json_decode($response->getContent(), true);

		$this->assertEquals(['topics' => $expected_topics], $data);
	}
}
