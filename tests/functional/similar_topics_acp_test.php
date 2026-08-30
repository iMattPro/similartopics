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
	protected function setUp(): void
	{
		parent::setUp();

		$this->login();
		$this->admin_login();
	}

	public function acp_pages_data()
	{
		return array(
			'settings' => array('settings'), // Load the main ACP page
		);
	}

	/**
	 * @dataProvider acp_pages_data
	 */
	public function test_acp_pages($mode)
	{
		$this->add_lang_ext('vse/similartopics', 'acp_similar_topics');
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=' . $mode . '&sid=' . $this->sid);
		$this->assertContainsLang('PST_TITLE_ACP', $crawler->text());
		$this->assertContainsLang('PST_EXPLAIN', $crawler->text());
		if ($mode === 'settings')
		{
			$this->assertCount(1, $crawler->filter('#pst_enable'));
			$this->assertCount(1, $crawler->filter('.pst-version'));
			$metadata = json_decode(file_get_contents(__DIR__ . '/../../composer.json'), true);
			$this->assertSame($metadata['version'], trim($crawler->filter('.pst-version')->text()));
			$this->assertCount(1, $crawler->filter('#pst_dynamic'));
			$this->assertCount(1, $crawler->filter('#pst_limit'));
			$this->assertCount(1, $crawler->filter('#pst_time'));
			$this->assertCount(1, $crawler->filter('#pst_cache'));
			$this->assertCount(1, $crawler->filter('#pst-cache-slider'));
			$this->assertCount(1, $crawler->filter('#pst_sense'));
			$this->assertCount(1, $crawler->filter('textarea[name="pst_words"]'));
			$this->assertGreaterThan(0, $crawler->filter('.pst-forum-card')->count());
			$this->assertCount(1, $crawler->filter('#pst-source-modal'));
				$this->assertCount(1, $crawler->filter('#pst-forum-filter'));
				$this->assertCount(1, $crawler->filter('noscript .errorbox'));
				$this->assertCount(1, $crawler->filter('input[name="forum_rules"]'));
				$this->assertGreaterThan(0, $crawler->filter('input[id^="show-forum-"]')->count());
				$this->assertGreaterThan(0, $crawler->filter('input[id^="searchable-forum-"]')->count());
			if ($this->get_db()->get_sql_layer() === 'postgres')
			{
				$this->assertCount(1, $crawler->filter('select[name="pst_postgres_ts_name"]'));
			}
			else
			{
				$this->assertCount(0, $crawler->filter('select[name="pst_postgres_ts_name"]'));
			}
		}
		return $crawler;
	}

	public function test_acp_logs()
	{
		$this->add_lang_ext('vse/similartopics', array('acp_similar_topics', 'info_acp_similar_topics'));
		$crawler = self::request('GET', 'adm/index.php?i=\vse\similartopics\acp\similar_topics_module&amp;mode=settings&sid=' . $this->sid);
		$form = $crawler->selectButton('submit')->form();
		$crawler = self::submit($form);
		$this->assertContainsLang('PST_SAVED', $crawler->text());
		$crawler = self::request('GET', 'adm/index.php?i=acp_logs&mode=admin&sid=' . $this->sid);
		self::assertStringContainsString(strip_tags($this->lang('PST_LOG_MSG')), $crawler->text());
	}

	public function test_acp_permissions()
	{
		$this->add_lang_ext('vse/similartopics', 'permissions_similar_topics');
		$crawler = self::request('GET', 'adm/index.php?i=acp_permissions&mode=setting_group_global&sid=' . $this->sid);
		$form = $crawler->selectButton('submit')->form();
		$crawler = self::submit($form);
		$this->assertContainsLang('ACL_U_SIMILARTOPICS', $crawler->text());
	}

}
