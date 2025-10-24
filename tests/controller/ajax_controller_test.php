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

class ajax_controller_test extends \phpbb_test_case
{
	/** @var \phpbb\request\request|\PHPUnit\Framework\MockObject\MockObject */
	protected $request;

	/** @var \vse\similartopics\core\similar_topics|\PHPUnit\Framework\MockObject\MockObject */
	protected $similar_topics;

	/** @var \vse\similartopics\controller\ajax_controller */
	protected $controller;

	protected function setUp(): void
	{
		parent::setUp();

		$this->request = $this->createMock('\phpbb\request\request');
		$this->similar_topics = $this->createMock('\vse\similartopics\core\similar_topics');

		$this->controller = new \vse\similartopics\controller\ajax_controller(
			$this->request,
			$this->similar_topics
		);
	}

	public function test_search_similar_topics_not_ajax()
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(false);

		$this->expectException('\phpbb\exception\http_exception');
		$this->controller->search_similar_topics();
	}

	public function test_search_similar_topics_short_query()
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(true);

		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default, $raw = false) use (&$call_count) {
				$call_count++;
				if ($call_count === 1) {
					return 'ab';
				}
				return 1;
			});

		$response = $this->controller->search_similar_topics();
		$data = json_decode($response->getContent(), true);

		$this->assertEquals(['topics' => []], $data);
	}

	public function test_search_similar_topics_not_available()
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(true);

		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default, $raw = false) use (&$call_count) {
				$call_count++;
				if ($call_count === 1) {
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

	public function test_search_similar_topics_success()
	{
		$this->request->expects($this->once())
			->method('is_ajax')
			->willReturn(true);

		$call_count = 0;
		$this->request->expects($this->exactly(2))
			->method('variable')
			->willReturnCallback(function($param, $default, $raw = false) use (&$call_count) {
				$call_count++;
				if ($call_count === 1) {
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
