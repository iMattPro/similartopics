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
class similar_topics_acp_test extends similar_topics_base
{
	public function acp_pages_data()
	{
		return array(
			array('settings'), // Load the main ACP page
			array('settings&action=advanced&f=2'), // Load the advanced forum settings ACP page
		);
	}

	/**
	* @dataProvider acp_pages_data
	*/
	public function test_acp_pages($mode)
	{
		$this->login();
		$this->admin_login();

		self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=' . $mode . '&sid=' . $this->sid);
	}
}
