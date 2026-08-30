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
	'PST_TIME_EXPLAIN'	=> 'در این قسمت شما میتوانید دوره جستجو موضوعات مشابه را تنظیم کنید. مثلا میتوانید تعداد 5 روز را مشخص کنید که یعنی نمایش موضوعات مشابهی که در 5 روز گذشته ارسال شده است. به طور پیش فرض ما 1 سال رو انتخاب کرده ایم. برای برداشتن محدودیت زمانی، مقدار را روی 0 تنظیم کنید.',
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
	'PST_JAVASCRIPT_REQUIRED' => 'برای ویرایش تنظیمات انجمن و انتخاب منابع در این صفحه، JavaScript لازم است. پیش از تغییر این تنظیمات، JavaScript را فعال کنید.',
	'PST_ERR_CONFIG'	=> 'تعداد انجمن های انتخاب شده بیش از حد می باشد لطفا دوباره با یک انتخاب کوچکتر امتحان کنید.',
	'PST_FEATURES' => 'محل نمایش موضوعات مشابه را انتخاب کنید',
	'PST_FEATURES_EXPLAIN' => 'هر گزینه را برای کل انجمن روشن یا خاموش کنید.',
	'PST_TIME_UNIT' => 'واحد دوره جستجو',
	'PST_CACHE_OFF' => 'خاموش',
	'PST_CACHE_5_MINUTES' => '5 دقیقه',
	'PST_CACHE_15_MINUTES' => '15 دقیقه',
	'PST_CACHE_30_MINUTES' => '30 دقیقه',
	'PST_CACHE_1_HOUR' => '1 ساعت',
	'PST_CACHE_2_HOURS' => '2 ساعت',
	'PST_CACHE_4_HOURS' => '4 ساعت',
	'PST_CACHE_8_HOURS' => '8 ساعت',
	'PST_CACHE_12_HOURS' => '12 ساعت',
	'PST_CACHE_24_HOURS' => '24 ساعت',
	'PST_CACHE_CUSTOM' => 'سفارشی: %d ثانیه',
	'PST_RESULTS' => 'نتایج را شکل دهید',
	'PST_RESULTS_EXPLAIN' => 'تعداد موضوعات مشابه را تنظیم کنید و آن موضوعات باید چقدر جدید باشند.',
	'PST_FORUM_RULES' => 'تصمیم بگیرید که هر انجمن چگونه شرکت می کند',
	'PST_FORUM_RULES_EXPLAIN' => 'هر انجمن دارای دو سوئیچ ساده است. تنها زمانی از «انتخاب منابع» استفاده کنید که یک انجمن به مجموعه جستجوی خود نیاز دارد.',
	'PST_FORUMS_MANAGED' => 'انجمن ها',
	'PST_CUSTOM_RULES' => 'تنظیمات منبع سفارشی',
	'PST_FILTER_FORUMS' => 'یافتن یک انجمن…',
	'PST_NO_FORUM_MATCH' => 'هیچ انجمنی با آن جستجو مطابقت ندارد.',
	'PST_SHOW_HERE' => 'موضوعات مشابه را در اینجا نشان دهید',
	'PST_SHOW_HERE_EXPLAIN' => 'بازدیدکنندگان در این انجمن می توانند موضوعات مشابه را مشاهده کنند.',
	'PST_SEARCHABLE' => 'این انجمن را به عنوان منبع در دسترس قرار دهید',
	'PST_SEARCHABLE_EXPLAIN' => 'جستجوهای استاندارد ممکن است موضوعاتی را از این انجمن پیدا کنند.',
	'PST_SEARCH_SOURCES' => 'جایی که این انجمن جستجو می کند',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'از هر فروم موجود استفاده کنید یا یک مجموعه سفارشی را انتخاب کنید.',
	'PST_CHOOSE_SOURCES' => 'منابع را انتخاب کنید',
	'PST_SOURCE_ALL' => 'همه انجمن های موجود',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d انجمن انتخاب شده',
		2 => '%d انجمن انتخاب شده',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'انتخاب کنید که موضوعات مشابه از کجا می آیند',
	'PST_SOURCE_MODAL_EXPLAIN' => 'این انتخاب فقط برای انجمن نشان داده شده در بالا اعمال می شود. هیچ انجمن دیگری را تغییر نمی دهد.',
	'PST_SOURCE_ALL_EXPLAIN' => 'هر انجمنی را که سوئیچ «در دسترس به عنوان منبع» روشن است جستجو کنید. انجمن های جدید به صورت خودکار ملحق می شوند.',
	'PST_SOURCE_CUSTOM' => 'فقط انجمن های منتخب',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'لیست ثابتی از انجمن ها را جستجو کنید. بهترین برای بخش های کاملا مرتبط.',
	'PST_FILTER_SOURCES' => 'یافتن یک انجمن منبع…',
	'PST_SELECT_AVAILABLE' => 'همه موجود را انتخاب کنید',
	'PST_CLEAR' => 'پاک کردن انتخاب',
	'PST_GLOBALLY_AVAILABLE' => 'موجود است',
	'PST_GLOBALLY_UNAVAILABLE' => 'به‌صورت سراسری در دسترس نیست',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'انتخاب های سفارشی سوئیچ های در دسترس بودن جهانی را لغو می کنند. یک تالار گفتمان انتخاب شده را می‌توان جستجو کرد، حتی زمانی که علامت‌گذاری شده «به‌صورت سراسری در دسترس نیست».',
	'PST_SELECT_ONE_SOURCE' => 'حداقل یک انجمن را انتخاب کنید یا از «همه انجمن‌های موجود» استفاده کنید.',
	'PST_APPLY_SOURCES' => 'از این منابع استفاده کنید',
	'PST_TUNING' => 'تطبیق و عملکرد دقیق',
	'PST_TUNING_EXPLAIN' => 'پیش فرض ها برای اکثر جوامع کار می کنند. این کنترل ها را فقط در صورت نیاز تنظیم کنید.',
	'PST_READY_TO_SAVE' => 'آماده اعمال تغییرات خود هستید؟',
	'PST_SAVE_EXPLAIN' => 'همه تنظیمات در این صفحه با هم ذخیره می شوند.',
	'PST_SAVE_SETTINGS' => 'تنظیمات را ذخیره کنید',
));
