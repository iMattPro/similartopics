<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests;

class ext_test extends \phpbb_test_case
{
	/** @var \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\DependencyInjection\ContainerInterface */
	protected $container;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\finder\finder */
	protected $extension_finder;

	/** @var \PHPUnit\Framework\MockObject\MockObject|\phpbb\db\migrator */
	protected $migrator;

	/** @var \vse\similartopics\ext */
	protected $ext;

	protected function setUp(): void
	{
		parent::setUp();

		// Stub the container
		$this->container = $this->createMock('\Symfony\Component\DependencyInjection\ContainerInterface');

		// Stub the ext finder and disable its constructor
		$this->extension_finder = $this->createMock('\phpbb\finder\finder');

		// Stub the migrator and disable its constructor
		$this->migrator = $this->createMock('\phpbb\db\migrator');

		$this->ext = new \vse\similartopics\ext(
			$this->container,
			$this->extension_finder,
			$this->migrator,
			'vse/similartopics',
			''
		);
	}

	public function test_is_enableable_returns_boolean()
	{
		$result = $this->ext->is_enableable();
		$this->assertIsBool($result);
	}

	public function test_ext_extends_base()
	{
		$this->assertInstanceOf('\phpbb\extension\base', $this->ext);
	}
}
