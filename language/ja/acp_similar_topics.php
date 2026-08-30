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
	'PST_TITLE_ACP'		=> 'Precise Similar Topics （正確な類似トピック）',
	'PST_EXPLAIN'		=> 'Precise Similar Topics（正確な類似トピック）は現在のトピックのページ下部に類似(関連)したトピックの一覧を表示します。',
	'PST_LEGEND1'		=> '一般設定',
	'PST_ENABLE'		=> '類似トピックの表示',
	'PST_ENABLE_EXPLAIN'=> 'トピックのディスカッションに類似トピックを表示します。',
	'PST_LEGEND2'		=> '読み込み設定',
	'PST_LIMIT'			=> '表示する類似トピック数',
	'PST_LIMIT_EXPLAIN'	=> '類似トピックの表示数を定義できます。デフォルトは5トピックです。',
	'PST_TIME'			=> '検索期間',
	'PST_TIME_EXPLAIN'	=> 'このオプションは類似トピックの検索期間を設定できます。例として、“5日間“を設定した場合、システムは過去5日間の中から類似トピックを表示します。デフォルトは1年間です。期間を制限しない場合は0に設定してください。',
	'PST_YEARS'			=> '年間',
	'PST_MONTHS'		=> '月間',
	'PST_WEEKS'			=> '週間',
	'PST_DAYS'			=> '日間',
	'PST_CACHE'			=> '類似トピックのキャッシュの長さ',
	'PST_CACHE_EXPLAIN'	=> 'キャッシュされた類似トピックはこの時間の後に期限がきれます。類似トピックのキャッシュを無効にしたい場合、0を設定します。',
	'PST_DYNAMIC'		=> '類似トピックを動的に表示',
	'PST_DYNAMIC_EXPLAIN'=> '新しいトピックの作成時、タイトル欄への入力に合わせて類似トピックを表示します。',
	'PST_SENSE'			=> '検索感度',
	'PST_SENSE_EXPLAIN'	=> '検索感度を 1 から 10 の間で設定します。類似トピックが一つも表示されない場合は低い値を使用してください。推奨設定: %d',
	'PST_LEGEND3'		=> 'フォーラム設定',
	'PST_NOSHOW_LIST'	=> '表示しない',
	'PST_NOSHOW_TITLE'	=> '類似トピックを表示しません',
	'PST_IGNORE_SEARCH'	=> '検索しない',
	'PST_IGNORE_TITLE'	=> '類似トピックについて検索しません',
	'PST_STANDARD'		=> '標準',
	'PST_ADVANCED'		=> 'カスタム',
	'PST_ADVANCED_TITLE'=> '次の類似トピックのカスタム設定をするにはクリック:',
	'PST_ADVANCED_EXP'	=> 'ここでは類似トピックを取ってくる特定のフォーラムを選択できます。ここで選択したフォーラムで見つかった類似トピックのみ<strong>%s</strong>に表示されます。<br><br>全ての検索可能なフォーラムから類似トピックをこのフォーラムに表示したい場合、どのフォーラムも選択しないでください。<br><br><samp>CTRL</samp> (または MACでは<samp>&#8984;CMD</samp>)を押しながらクリックすることで複数のフォーラムを選択します。',
	'PST_ADVANCED_FORUM'=> 'カスタムフォーラム設定',
	'PST_DESELECT_ALL'	=> '全ての選択を解除',
	'PST_LEGEND4'		=> 'オプション設定',
	'PST_WORDS'			=> '無視する単語',
	'PST_WORDS_EXPLAIN'	=> '類似トピックを検索する際に無視すべき固有の単語を追加します（注: 現在の言語で共通とみなされている単語は、すでにデフォルトでは無視されます) 。スペースで各単語を区切ります。大文字小文字は区別しません。',
	'PST_SAVED'			=> '類似トピック設定を更新しました',
	'PST_FORUM_INFO'	=> '“表示しない”: 選択したフォーラムで類似トピックを表示しません。<br>“検索しない” : 選択したフォーラムで類似トピックを検索しません。',
	'PST_NO_COMPAT'		=> '類似トピックはあなたのフォーラムと互換性がありません。類似トピックはMySQL4/5/PostgreSQLのデータベースでのみ実行できます。',
	'PST_ERR_CONFIG'	=> 'あまりにも多くのフォーラムが、フォーラムのリストにマークされています。少ない選択数で再度お試しください。',
	'PST_FEATURES' => '類似のトピックが表示される場所を選択する',
	'PST_FEATURES_EXPLAIN' => '掲示板全体で各オプションを有効または無効にします。',
	'PST_TIME_UNIT' => '検索期間単位',
	'PST_CACHE_OFF' => 'オフ',
	'PST_CACHE_5_MINUTES' => '5分',
	'PST_CACHE_15_MINUTES' => '15分',
	'PST_CACHE_30_MINUTES' => '30分',
	'PST_CACHE_1_HOUR' => '1時間',
	'PST_CACHE_2_HOURS' => '2時間',
	'PST_CACHE_4_HOURS' => '4時間',
	'PST_CACHE_8_HOURS' => '8時間',
	'PST_CACHE_12_HOURS' => '12時間',
	'PST_CACHE_24_HOURS' => '24時間',
	'PST_CACHE_CUSTOM' => 'カスタム: %d 秒',
	'PST_RESULTS' => '結果を形にする',
	'PST_RESULTS_EXPLAIN' => '同様のトピックがいくつ表示されるか、およびそれらのトピックがどれくらい新しいものである必要があるかを設定します。',
	'PST_FORUM_RULES' => '各フォーラムの参加方法を決定する',
	'PST_FORUM_RULES_EXPLAIN' => '各フォーラムには 2 つの単純なスイッチがあります。 「ソースの選択」は、フォーラムが独自の検索プールを必要とする場合にのみ使用してください。',
	'PST_FORUMS_MANAGED' => 'フォーラム',
	'PST_CUSTOM_RULES' => 'カスタムソースルール',
	'PST_FILTER_FORUMS' => 'フォーラムを探して…',
	'PST_NO_FORUM_MATCH' => 'その検索に一致するフォーラムはありません。',
	'PST_SHOW_HERE' => '同様のトピックをここに表示します',
	'PST_SHOW_HERE_EXPLAIN' => 'このフォーラムの訪問者は同様のトピックを参照できます。',
	'PST_SEARCHABLE' => 'このフォーラムをソースとして利用できるようにする',
	'PST_SEARCHABLE_EXPLAIN' => '標準検索では、このフォーラムのトピックが見つかる場合があります。',
	'PST_SEARCH_SOURCES' => 'このフォーラムが検索する場所',
	'PST_SEARCH_SOURCES_EXPLAIN' => '利用可能なフォーラムをすべて使用するか、カスタム セットを選択してください。',
	'PST_CHOOSE_SOURCES' => 'ソースを選択してください',
	'PST_SOURCE_ALL' => '利用可能なすべてのフォーラム',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d 個のフォーラムが選択されました',
		2 => '%d 個の選択されたフォーラム',
	),
	'PST_CHOOSE_FORUM_SOURCES' => '類似のトピックの出所を選択する',
	'PST_SOURCE_MODAL_EXPLAIN' => 'この選択は、上記のフォーラムにのみ適用されます。他のフォーラムは変わりません。',
	'PST_SOURCE_ALL_EXPLAIN' => '「ソースとして利用可能」スイッチがオンになっているすべてのフォーラムを検索します。新しいフォーラムは自動的に参加します。',
	'PST_SOURCE_CUSTOM' => '選択されたフォーラムのみ',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'フォーラムの固定リストを検索します。密接に関連するセクションに最適です。',
	'PST_FILTER_SOURCES' => 'ソースフォーラムを探してください…',
	'PST_SELECT_AVAILABLE' => '利用可能なものをすべて選択してください',
	'PST_CLEAR' => '選択をクリア',
	'PST_GLOBALLY_AVAILABLE' => '利用可能',
	'PST_GLOBALLY_UNAVAILABLE' => '全体では利用不可',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'カスタムの選択は、グローバル可用性スイッチをオーバーライドします。選択したフォーラムは、「全体では利用不可」とマークされている場合でも検索できます。',
	'PST_SELECT_ONE_SOURCE' => '少なくとも 1 つのフォーラムを選択するか、「利用可能なすべてのフォーラム」を使用します。',
	'PST_APPLY_SOURCES' => 'これらのソースを使用してください',
	'PST_TUNING' => 'マッチングとパフォーマンスを微調整する',
	'PST_TUNING_EXPLAIN' => 'ほとんどのコミュニティではデフォルトで機能します。これらのコントロールは必要な場合にのみ調整してください。',
	'PST_READY_TO_SAVE' => '変更を適用する準備はできましたか?',
	'PST_SAVE_EXPLAIN' => 'このページのすべての設定は一緒に保存されます。',
	'PST_SAVE_SETTINGS' => '設定を保存する',
));
