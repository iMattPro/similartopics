<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\driver;

use phpbb\di\service_collection;

class manager
{
	/**
	 * Array that contains all available similartopics drivers which are passed via the
	 * service container
	 * @var array|service_collection
	 */
	protected array|service_collection $similartopics_drivers;

	/**
	 * Construct a similar topics manager object
	 *
	 * @param array|service_collection $similartopics_drivers Avatar drivers passed via the service container
	 */
	public function __construct(array|service_collection $similartopics_drivers)
	{
		$this->register_similartopics_drivers($similartopics_drivers);
	}

	/**
	 * Get the driver object specified by the database sql layer
	 *
	 * @param string $sql_layer The database sql layer
	 * @return driver_interface|null Similar topics driver
	 */
	public function get_driver(string $sql_layer): driver_interface|null
	{
		return $this->similartopics_drivers[$sql_layer] ?? null;
	}

	/**
	 * Register similartopics drivers
	 *
	 * @param array|service_collection $similartopics_drivers Service collection of similartopics drivers
	 */
	protected function register_similartopics_drivers(array|service_collection $similartopics_drivers): void
	{
		if (!empty($similartopics_drivers))
		{
			foreach ($similartopics_drivers as $driver)
			{
				$this->similartopics_drivers[$driver->get_name()] = $driver;
			}
		}
	}
}
