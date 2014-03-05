<?php
/**
*
* @package testing
* @copyright (c) 2014 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @group functional
*/
class phpbb_functional_similartopics_test extends extension_functional_test_case
{
	public function setUp()
	{
		parent::setUp();
		$this->login();
		$this->admin_login();
		$this->set_extension('vse', 'similartopics', 'Precise Similar Topics');
		$this->enable_extension();
		$this->enable_similar_topics();
	}

	public function enable_similar_topics()
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = '1'
			WHERE config_name = 'similar_topics'";

		$this->db->sql_query($sql);

		$this->purge_cache();
	}

	public function test_similar_topics()
	{
		// Create some generic topics (needed to exceed 50% fulltext limit)
		$post1 = $this->create_topic(2, 'Test Topic 1', 'This is a test topic posted by the testing framework.');
		$post2 = $this->create_topic(2, 'Test Topic 2', 'This is a test topic posted by the testing framework.');

		// Create some similar topics
		$post3 = $this->create_topic(2, 'Test Framework Topic 1', 'This is a test topic posted by the testing framework.');
		$post4 = $this->create_topic(2, 'Test Framework Topic 2', 'This is a test topic posted by the testing framework.');

		// Test similar topic shows up where expected
		$crawler = self::request('GET', "viewtopic.php?t={$post3['topic_id']}&sid={$this->sid}");
		$this->assertContains('Test Framework Topic 2', $crawler->filter('html')->text());
		$crawler = self::request('GET', "viewtopic.php?t={$post4['topic_id']}&sid={$this->sid}");
		$this->assertContains('Test Framework Topic 1', $crawler->filter('html')->text());
	}
}
