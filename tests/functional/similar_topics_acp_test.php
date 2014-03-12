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
	public function test_acp_pages()
	{
		// Load the main ACP page
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=settings&sid=' . $this->sid);

		// Load the advanced forum settings ACP page
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=settings&action=advanced&f=2&sid=' . $this->sid);
	}
}
