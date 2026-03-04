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

use phpbb\db\migrator;
use phpbb\finder\finder;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\DependencyInjection\ContainerInterface;
use vse\similartopics\ext;
use phpbb\extension\base;

class ext_test extends phpbb_test_case
{
	/** @var ContainerInterface|MockObject */
	protected ContainerInterface|MockObject $container;

	/** @var MockObject|finder */
	protected MockObject|finder $extension_finder;

	/** @var MockObject|migrator */
	protected MockObject|migrator $migrator;

	/** @var ext */
	protected ext $ext;

	protected function setUp(): void
	{
		parent::setUp();

		// Stub the container
		$this->container = $this->createMock(ContainerInterface::class);

		// Stub the ext finder and disable its constructor
		$this->extension_finder = $this->createMock(finder::class);

		// Stub the migrator and disable its constructor
		$this->migrator = $this->createMock(migrator::class);

		$this->ext = new ext(
			$this->container,
			$this->extension_finder,
			$this->migrator,
			'vse/similartopics',
			''
		);
	}

	public function test_is_enableable_returns_boolean(): void
	{
		$result = $this->ext->is_enableable();
		$this->assertIsBool($result);
	}

	public function test_ext_extends_base(): void
	{
		$this->assertInstanceOf(base::class, $this->ext);
	}
}
