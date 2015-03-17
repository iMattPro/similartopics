<?php
/**
*
* Precise Similar Topics [Persian]
* Translated by Meisam Noubari from IRAN in php-bb.ir
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
	'PST_TITLE_ACP'		=> 'موضوعات مشابه',
	'PST_EXPLAIN'		=> '"موضوعات مشابه" ابزاریست جهت نمایش ، موضوعات مشابه در قسمت پایین هر موضوع در انجمن ها',
	'PST_LEGEND1'		=> 'تنظیمات کلی',
	'PST_ENABLE'		=> 'نمایش موضوعات مشابه',
	'PST_LEGEND2'		=> 'بارگزاری تنظیمات',
	'PST_LIMIT'			=> 'تعداد موضوعات مشابه جهت نمایش',
	'PST_LIMIT_EXPLAIN'	=> 'در این قسمت شما میتوانید تعیین کنید که چه تعداد موضوع نمایش داده شود. مقدار پیش فرض تعداد 5 است.',
	'PST_TIME'			=> 'دوره جستجو',
	'PST_TIME_EXPLAIN'	=> 'در این قسمت شما میتوانید دوره جستجو موضوعات مشابه را تنظیم کنید. مثلا میتوانید تعداد 5 روز را مشخص کنید که یعنی نمایش موضوعات مشابهی که در 5 روز گذشته ارسال شده است. به طور پیش فرض ما 1 سال رو انتخاب کرده ایم.',
	'PST_YEARS'			=> 'سال',
	'PST_MONTHS'		=> 'ماه',
	'PST_WEEKS'			=> 'هفته',
	'PST_DAYS'			=> 'روز',
	'PST_CACHE'			=> 'زمان ذخیره سازی کش',
	'PST_CACHE_EXPLAIN'	=> 'کش یا نهان این افزونه بعد از تعیین زمان توسط شما منسوخ خواهد شد ، برای غیر فعال سازی 0 را وارد کنید.',
	'PST_LEGEND3'		=> 'تنظیمات انجمن',
	'PST_NOSHOW_LIST'	=> 'در این بخش نمایش نده',
	'PST_NOSHOW_TITLE'	=> 'موضوعات مشابه را در این انجمن نشان نده',
	'PST_IGNORE_SEARCH'	=> 'جستجوی موضوعات را در این انجمن انجام نده',
	'PST_IGNORE_TITLE'	=> 'جستجوی موضوعات مشابه را در این بخش انجام نده',
	'PST_STANDARD'		=> 'استاندارد',
	'PST_ADVANCED'		=> 'پیشرفته',
	'PST_ADVANCED_TITLE'=> 'کلیک جهت انجام تنظیمات پیشرفته',
	'PST_ADVANCED_EXP'	=> 'در اینجا شما میتوانید انجمن هایی که دوست دارید موضوعات مشابه در آن دیده شوند انتخاب کنید. به یاد داشته باشید که فقط انجمن هاییی که شما انتخاب میکنید شامل این افزونه خواهد شد.<br />',
	'PST_ADVANCED_FORUM'=> 'تنظیمات پیشرفته انجمن',
	'PST_DESELECT_ALL'	=> 'انتخاب نکردن',
	'PST_LEGEND4'		=> 'تنظیمات',
	'PST_WORDS'			=> 'کلمات سانسور شده',
	'PST_WORDS_EXPLAIN'	=> 'کلمات سانسوی که قصد دارید هنگام جستجوی موضوعات مشابه نمایش داده نشوند را وارد کنید. هر کلمه را با space میتوانید جدا کنید و ماکزمیم کاراکتر کلمات نیز 255 میباشد.',
	'PST_SAVED'			=> 'تنظیمات موضوعات مشابه به روز رسانی شد',
	'PST_FORUM_INFO'	=> 'انتخاب گزنیه"در این بخش نمایش نده" باعث می شود موضوعات مشابه در انجمن های انتخابی نمایش داده نشود<br />انتخاب گزینه" جستجوی موضوعات را در این بخش انجام نده" باعث می شود ، جستجو در بخش های انتخابی برای تاپیک های مشابه انجام نشود.',
	'PST_NO_MYSQL'		=> 'افزونه موضوعات مشابه با سیستم انجمن شما سازگار نیست. این سیستم تنها روی دیتابیس های MySQL 4 or MySQL 5 کار میکند',
	'PST_WARN_FULLTEXT'	=> 'افزونه موضوعات مشابه با سیستم انجمن شما سازگار نیست. افزونه موضوعات مشابه تنها با FULLTEXT و جدول “phpbb_topics” سازگار میباشد ',
	'PST_ADD_FULLTEXT'	=> 'بله ،فعال سازی برای FULLTEXT انجام شد',
	'PST_SAVE_FULLTEXT'	=> 'دیتابیس شما با موفقیت به روز رسانی شد. حالا میتوانید به راحتی از این افزونه لذت ببرید',
	'PST_ERR_FULLTEXT'	=> 'پایگاه داده شما نمی تواند به روز شود.',
	'PST_ERR_CONFIG'	=> 'بیش از حد بسیاری از انجمن ها در فهرست انجمن ها مشخص شد. لطفا دوباره با یک انتخاب کوچکتر امتحان کنید.',
));
