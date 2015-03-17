<?php
/**
*
* Precise Similar Topics [Japanese]
*
* @copyright (c) 2013 Matt Friedman
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'PST_TITLE_ACP'		=> '正確な類似トピック',
	'PST_EXPLAIN'		=> '正確な類似トピックは現在のトピックのページの下部に類似(関連)したトピックの一覧を表示します。',
	'PST_LEGEND1'		=> '一般設定',
	'PST_ENABLE'		=> '類似トピックの表示',
	'PST_LEGEND2'		=> '読み込み設定',
	'PST_LIMIT'			=> '表示する類似トピック数',
	'PST_LIMIT_EXPLAIN'	=> '個々ではどれくらいの類似トピックを表示するか定義できます。デフォルトは5トピックです。',
	'PST_TIME'			=> '検索期間',
	'PST_TIME_EXPLAIN'	=> 'このオプションは類似トピックの検索期間を設定できます。例として、“5日間“を設定した場合、システムは過去5日間の中から類似トピックを表示します。デフォルトは1年間です。',
	'PST_YEARS'			=> '年間',
	'PST_MONTHS'		=> '月間',
	'PST_WEEKS'			=> '週間',
	'PST_DAYS'			=> '日間',
	'PST_CACHE'			=> '類似トピックのキャッシュの長さ',
	'PST_CACHE_EXPLAIN'	=> 'キャッシュされた類似トピックはこの時間の後に期限がきれます。類似トピックのキャッシュを無効にしたい場合、0を設定します。',
	'PST_LEGEND3'		=> 'フォーラム設定',
	'PST_NOSHOW_LIST'	=> '表示しない',
	'PST_NOSHOW_TITLE'	=> '類似トピックを表示しません',
	'PST_IGNORE_SEARCH'	=> '検索しない',
	'PST_IGNORE_TITLE'	=> '類似トピックについて検索しません',
	'PST_STANDARD'		=> '標準',
	'PST_ADVANCED'		=> 'カスタム',
	'PST_ADVANCED_TITLE'=> '次の類似トピックのカスタム設定をするにはクリック:',
	'PST_ADVANCED_EXP'	=> 'ここでは類似トピックを取ってくる特定のフォーラムを選択できます。ここで選択したフォーラムで見つかった類似トピックのみ<strong>%s</strong>に表示されます。<br /><br />全ての検索可能なフォーラムから類似トピックをこのフォーラムに表示したい場合、どのフォーラムも選択しないでください。<br /><br /><samp>CTRL</samp> (または MACでは<samp>&#8984;CMD</samp>)を押しながらクリックすることで複数のフォーラムを選択します。',
	'PST_ADVANCED_FORUM'=> 'カスタムフォーラム設定',
	'PST_DESELECT_ALL'	=> '全ての選択を解除',
	'PST_LEGEND4'		=> 'オプション設定',
	'PST_WORDS'			=> '無視する単語',
	'PST_WORDS_EXPLAIN'	=> '類似トピックを検索中に無視すべきフォーラムに固有の単語を追加します。(注: 現在の言語で共通とみなされている単語は、すでにデフォルトでは無視されます。) スペースで各単語を区切り、大文字小文字を区別せず、最大255文字です。',
	'PST_SAVED'			=> '類似トピック設定を更新しました',
	'PST_FORUM_INFO'	=> '“表示しない”: 選択したフォーラムで類似トピックを表示しません。<br />“検索しない” : 選択したフォーラムで類似トピックを検索しません。',
	'PST_NO_MYSQL'		=> '類似トピックはあなたのフォーラムと互換性がありません。類似トピックはMySQL4/5のデータベースでのみ実行できます。',
	'PST_WARN_FULLTEXT'	=> '類似トピックはあなたのフォーラムと互換性がありません。<br />類似トピックはMySQLのfulltextインデックスを用いるMyISAMまたはInnoDBテーブルでのみ使用出来ます。MySQL 5.6.4 以上がInnoDBテーブル上でfulltextインデックスが必要です。<br /><br />類似トピックを使用したい場合、データベースをfulltextインデックスに対応したものへアップデートしてください。類似トピックを削除することにした場合、これまでに行われた変更は元に戻されます。',
	'PST_ADD_FULLTEXT'	=> 'はい、fulltextインデックスのサポートは有効です',
	'PST_SAVE_FULLTEXT'	=> 'データベースを更新しました。類似トピックを使用可能です。',
	'PST_ERR_FULLTEXT'	=> 'データベースを更新できませんでした。',
	'PST_ERR_CONFIG'	=> 'あまりにも多くのフォーラムは、フォーラムのリストにマークされていた。小さい選択に再度お試しください。',
));
