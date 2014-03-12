<?php
/**
*
* @package testing
* @copyright (c) 2014 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @group functional
*/
class extension_functional_similar_topics_test extends extension_functional_test_case
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

	public function alter_storage_engine()
	{
		$this->get_db();

		$this->db->sql_query('ALTER TABLE phpbb_topics ENGINE = MYISAM');
	}

	public function enable_similar_topics()
	{
		$this->get_db();

		$sql = "UPDATE phpbb_config
			SET config_value = 1
			WHERE config_name = 'similar_topics'";

		$this->db->sql_query($sql);

		$this->purge_cache();
	}

	public function test_storage_engine()
	{
		$this->get_db();

		$result = $this->db->sql_query("SHOW TABLE STATUS LIKE 'phpbb_topics'");
		$info = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$engine = '';
		if (isset($info['Engine']))
		{
			$engine = strtolower($info['Engine']);
		}
		else if (isset($info['Type']))
		{
			$engine = strtolower($info['Type']);
		}

		$this->assertTrue($engine === 'myisam');
	}

	public function test_fulltext_topic_title()
	{
		$fulltext = false;

		$this->get_db();

		$sql = "SHOW INDEX
			FROM " . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// deal with older MySQL versions which didn't use Index_type
			$index_type = (isset($row['Index_type'])) ? $row['Index_type'] : $row['Comment'];

			if ($index_type == 'FULLTEXT' && $row['Key_name'] == 'topic_title')
			{
				$fulltext = true;
			}
		}

		$this->assertTrue($fulltext);
	}

	public function test_similar_topics()
	{
		// Create some basic topics
		$post1 = $this->create_topic(2, 'Test Topic 1', 'This is test topic 1 posted by the testing framework.');
		$post2 = $this->create_topic(2, 'Test Topic 2', 'This is test topic 2 posted by the testing framework.');
		$post3 = $this->create_topic(2, 'Test Topic 3', 'This is test topic 3 posted by the testing framework.');
		$post4 = $this->create_topic(2, 'Test Framework Topic 4', 'This is test topic 4 posted by the testing framework.');
		$post5 = $this->create_topic(2, 'Test Framework Topic 5', 'This is test topic 5 posted by the testing framework.');

		// Load topic #5
		$crawler = self::request('GET', "viewtopic.php?t={$post5['topic_id']}&sid={$this->sid}");
		// Test that the title of similar topic (#4) is found
		$this->assertContains('Test Framework Topic 4', $crawler->filter('html')->text());

		// Load topic #4
		$crawler = self::request('GET', "viewtopic.php?t={$post4['topic_id']}&sid={$this->sid}");
		// Test that the title of similar topic (#5) is found
		$this->assertContains('Test Framework Topic 5', $crawler->filter('html')->text());

		// Remove the extension
		$this->disable_extension();
		$this->purge_extension();
	}
}
