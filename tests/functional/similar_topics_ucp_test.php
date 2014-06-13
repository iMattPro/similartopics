<?php
/**
*
* @package Precise Similar Topics testing
* @copyright (c) 2014 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace vse\similartopics\tests\functional;

/**
* @group functional
*/
class similar_topics_ucp_test extends similar_topics_base
{
	public function test_ucp_pages()
	{
		$this->add_lang_ext('vse/similartopics', 'similar_topics');

		$this->login();

		$crawler = self::request('GET', 'ucp.php?i=ucp_prefs&mode=view&sid=' . $this->sid);
		$this->assertContains($this->lang('DISPLAY_SIMILAR_TOPICS'), $crawler->filter('#cp-main')->text());
	}
}
