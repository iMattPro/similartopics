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

use phpbb_functional_test_case;

/**
 * @group functional
 */
class create_forum_test extends phpbb_functional_test_case
{
	protected static function setup_extensions(): array
	{
		return array('vse/similartopics');
	}

	public function test_create_forum(): void
	{
		self::add_lang('acp/forums');

		self::login();
		self::admin_login();

		$crawler = self::request('GET', "adm/index.php?i=acp_forums&mode=manage&sid=$this->sid");
		$form = $crawler->selectButton(self::lang('CREATE_FORUM'))->form();
		$crawler = self::submit($form);
		self::assertContainsLang('CREATE_FORUM', $crawler->filter('#main h1')->text());

		$form = $crawler->selectButton(self::lang('SUBMIT'))->form(array(
			'forum_name'	=> 'Test Forum',
		));
		$crawler = self::submit($form);

		self::assertGreaterThan(0, $crawler->filter('.successbox')->count());
	}
}
