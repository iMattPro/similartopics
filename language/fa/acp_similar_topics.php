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
	'PST_ENABLE_EXPLAIN'=> 'نمایش موضوعات مشابه در صفحهٔ گفت‌وگوی موضوع.',
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
	'PST_DYNAMIC'		=> 'نمایش پویای موضوعات مشابه',
	'PST_DYNAMIC_EXPLAIN'=> 'هنگام ایجاد موضوع جدید، هم‌زمان با تایپ عنوان توسط کاربر موضوعات مشابه را نمایش بده.',
	'PST_SENSE'			=> 'حساسیت جستجو',
	'PST_SENSE_EXPLAIN'	=> 'برای پایگاه‌های داده MySQL یا Postgres، می‌توانید حساسیت جستجو را روی مقداری بین 1 تا 10 تنظیم کنید. اگر هیچ موضوع مشابهی نمی‌بینید، از عدد کمتری استفاده کنید. تنظیم پیشنهادی: %d',
	'PST_LEGEND3'		=> 'تنظیمات انجمن',
	'PST_NOSHOW_LIST'	=> 'در این بخش نمایش نده',
	'PST_NOSHOW_TITLE'	=> 'موضوعات مشابه را در این انجمن نشان نده',
	'PST_IGNORE_SEARCH'	=> 'جستجوی موضوعات را در این انجمن انجام نده',
	'PST_IGNORE_TITLE'	=> 'جستجوی موضوعات مشابه را در این بخش انجام نده',
	'PST_STANDARD'		=> 'استاندارد',
	'PST_ADVANCED'		=> 'پیشرفته',
	'PST_ADVANCED_TITLE'=> 'کلیک جهت انجام تنظیمات پیشرفته',
	'PST_ADVANCED_EXP'	=> 'در اینجا می‌توانید انجمن‌های مشخصی را برای دریافت موضوعات مشابه انتخاب کنید. فقط موضوعات مشابه یافت‌شده در انجمن‌هایی که اینجا انتخاب می‌کنید در <strong>%s</strong> نمایش داده می‌شوند.<br><br>اگر می‌خواهید موضوعات مشابه از همه انجمن‌های قابل جستجو در این انجمن نمایش داده شوند، هیچ انجمنی را انتخاب نکنید.<br><br>برای انتخاب چند انجمن، کلید <samp>CTRL</samp> (یا <samp>&#8984;CMD</samp> در مک) را نگه دارید و کلیک کنید.',
	'PST_ADVANCED_FORUM'=> 'تنظیمات پیشرفته انجمن',
	'PST_DESELECT_ALL'	=> 'انتخاب نکردن',
	'PST_LEGEND4'		=> 'تنظیمات',
	'PST_WORDS'			=> 'کلمات سانسور شده',
	'PST_WORDS_EXPLAIN'	=> 'کلمات سانسوری که قصد دارید هنگام جستجوی موضوعات مشابه نمایش داده نشوند را وارد کنید. هر کلمه را با space جدا کنید.',
	'PST_SAVED'			=> 'تنظیمات موضوعات مشابه بروز رسانی شد',
	'PST_FORUM_INFO'	=> 'انتخاب گزنیه"در این بخش نمایش نده" باعث می شود موضوعات مشابه در انجمن انتخابی نمایش داده نشود<br>انتخاب گزینه" جستجوی موضوعات را در این بخش انجام نده" باعث می شود ، جستجو در انجمن انتخابی برای موضوعات مشابه انجام نشود.',
	'PST_NO_COMPAT'		=> 'افزونه موضوعات مشابه با سیستم انجمن شما سازگار نیست. این سیستم تنها روی دیتابیس های PostgreSQL or MySQL 4 or MySQL 5 کار میکند',
	'PST_ERR_CONFIG'	=> 'تعداد انجمن های انتخاب شده بیش از حد می باشد لطفا دوباره با یک انتخاب کوچکتر امتحان کنید.',
));
