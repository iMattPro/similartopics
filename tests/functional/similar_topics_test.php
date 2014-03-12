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
class extension_functional_similar_topics_test extends extension_functional_similar_topics_base
{
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
		// Test that the title of topic #4 is found
		$this->assertContains('Test Framework Topic 4', $crawler->filter('html')->text());

		// Load topic #4
		$crawler = self::request('GET', "viewtopic.php?t={$post4['topic_id']}&sid={$this->sid}");		
		// Test that the title of topic #5 is found
		$this->assertContains('Test Framework Topic 5', $crawler->filter('html')->text());
	}
}
