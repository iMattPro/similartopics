<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\functional;

/**
 * @group functional
 */
class similar_topics_base extends \phpbb_functional_test_case
{
	protected static function setup_extensions()
	{
		return array('vse/similartopics');
	}

	protected function setUp(): void
	{
		parent::setUp();
		$this->enable_similar_topics();
	}

	/**
	 * Enable Similar Topics (it is disabled when installed)
	 */
	public function enable_similar_topics()
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = 1
			WHERE config_name = 'similar_topics'";

		$this->db->sql_query($sql);

		$this->purge_cache();
	}
}
