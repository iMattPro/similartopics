<?php
/**
*
* @package testing
* @copyright (c) 2014 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\tests\functional;

/**
* @group functional
*/
class similar_topics_base extends \extension_functional_test_case
{
	public function setUp()
	{
		parent::setUp();
		$this->login();
		$this->admin_login();
		$this->alter_storage_engine();
		$this->set_extension('vse', 'similartopics', 'Precise Similar Topics');
		$this->enable_extension();
		$this->enable_similar_topics();
	}

	/**
	* Convert topics table storage engine to MyISAM
	*/
	public function alter_storage_engine()
	{
		$this->get_db();

		$this->db->sql_query('ALTER TABLE phpbb_topics ENGINE = MYISAM');
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
