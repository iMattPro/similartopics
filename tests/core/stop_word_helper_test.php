<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\core;

class stop_word_helper_test extends \phpbb_test_case
{
	/** @var \phpbb\cache\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $cache;

	/** @var \phpbb\extension\manager|\PHPUnit\Framework\MockObject\MockObject */
	protected $ext_manager;

	/** @var \phpbb\user|\PHPUnit\Framework\MockObject\MockObject */
	protected $user;

	/** @var string */
	protected $php_ext = 'php';

	protected function setUp(): void
	{
		parent::setUp();

		$this->cache = $this->createMock('\phpbb\cache\driver\driver_interface');
		$this->cache->method('get')->willReturn(false);
		$this->cache->method('put')->willReturn(true);
		$this->ext_manager = $this->createMock('\phpbb\extension\manager');
		$this->user = $this->createMock('\phpbb\user');
		$this->user->lang_name = 'en';
	}

	public function get_helper()
	{
		return new \vse\similartopics\core\stop_word_helper(
			$this->cache,
			$this->ext_manager,
			$this->user,
			$this->php_ext
		);
	}

	public function clean_text_test_data()
	{
		return [
			'No filtering' => [
				'The quick brown fox', false, '', 'The quick brown fox'
			],
			'HTML filtering only' => [
				'The &quot;quick&quot; &amp; brown fox', false, '', 'The quick  brown fox'
			],
			'Filter with localized stop words' => [
				'The quick brown fox', true, '', 'quick brown fox'
			],
			'Filter with additional ignore words' =>[
				'The quick brown fox', false, 'quick brown', 'the fox'
			],
			'Filter with additional and localized ignore words'	=> [
				'The quick brown fox', true, 'quick brown', 'fox'
			],
			'No filtering, everything empty' => [
				'', false, '', ''
			],
			'No filtering, with whitespaces' => [
				'   ', false, '', '   '
			],
		];
	}

	/**
	 * @dataProvider clean_text_test_data
	 */
	public function test_clean_text($text, $use_localized, $additional_ignore, $expected)
	{
		if ($use_localized)
		{
			$this->setup_finder_mock();
		}

		$helper = $this->get_helper();
		$helper->set_additional_ignore_words($additional_ignore);
		$helper->set_use_localized($use_localized);

		self::assertSame($expected, $helper->clean_text($text));
	}

	public function test_set_additional_ignore_words()
	{
		$helper = $this->get_helper();

		$helper->set_additional_ignore_words('test words');
		self::assertSame('example', $helper->clean_text('test my words example'));

		$helper->set_additional_ignore_words('different words');
		self::assertSame('test example', $helper->clean_text('test my words example'));
	}

	public function test_set_use_localized()
	{
		$this->setup_finder_mock();

		$helper = $this->get_helper();
		self::assertSame('The and example', $helper->clean_text('The and example'));

		$helper->set_use_localized(true);
		self::assertSame('example', $helper->clean_text('The and example'));
	}

	public function test_needs_reload_tracking()
	{
		$this->setup_finder_mock();

		$helper = $this->get_helper();
		$helper->set_additional_ignore_words('');
		$helper->set_use_localized(true);

		// The first call should load words
		$helper->clean_text('The example');

		// Second call with same settings should not reload
		$helper->clean_text('The test');

		// Changing settings should mark needs_reload and reload
		$helper->set_additional_ignore_words('example');
		$result = $helper->clean_text('The example test');
		self::assertSame('test', $result);
	}

	protected function setup_finder_mock()
	{
		$finder = $this->createMock('\phpbb\finder\finder');
		$finder->method('set_extensions')->willReturnSelf();
		$finder->method('prefix')->willReturnSelf();
		$finder->method('suffix')->willReturnSelf();
		$finder->method('extension_directory')->willReturnSelf();
		$finder->method('core_path')->willReturnSelf();
		$finder->method('get_files')->willReturn([__DIR__ . '/fixtures/search_ignore_words.php']);

		$this->ext_manager->method('get_finder')->willReturn($finder);
	}
}
