<?php
/**
*
* @package testing
* @copyright (c) 2014 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=' . $mode . '&sid=' . $this->sid);
	}
}
