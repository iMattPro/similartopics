<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2017 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\tests\functional;

/**
 * @group functional
 */
class create_forum_test extends \phpbb_functional_test_case
{
	protected static function setup_extensions()
	{
		return array('vse/similartopics');
	}

	public function test_create_forum()
	{
		$this->add_lang('acp/forums');

		$this->login();
		$this->admin_login();

		$crawler = self::request('GET', "adm/index.php?i=acp_forums&mode=manage&sid={$this->sid}");
		$form = $crawler->selectButton($this->lang('CREATE_FORUM'))->form();
		$crawler = self::submit($form);
		$this->assertContainsLang('CREATE_FORUM', $crawler->filter('#main h1')->text());

		$form = $crawler->selectButton($this->lang('SUBMIT'))->form(array(
			'forum_name'	=> 'Test Forum',
		));
		$crawler = self::submit($form);

		self::assertGreaterThan(0, $crawler->filter('.successbox')->count());
	}
}
