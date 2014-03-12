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
class phpbb_functional_similar_topics_acp_test extends extension_functional_test_case
{
	public function setUp()
	{
		parent::setUp();
		$this->login();
		$this->admin_login();
		$this->set_extension('vse', 'similartopics', 'Precise Similar Topics');
		$this->enable_extension();
	}

	public function test_acp_pages()
	{
		// Load the main ACP page
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=settings&sid=' . $this->sid);

		// Load the advanced forum settings ACP page
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=settings&action=advanced&f=2&sid=' . $this->sid);

		$this->disable_extension();
		$this->purge_extension();
	}
}
