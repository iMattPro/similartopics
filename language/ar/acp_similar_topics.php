<?php
/**
*
* Precise Similar Topics [Arabic]
*
* @copyright (c) 2013 Matt Friedman
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Translated By : Bassel Taha Alhitary - www.alhitary.net
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
	'PST_TITLE_ACP'		=> 'المواضيع المُتشابهه',
	'PST_EXPLAIN'		=> 'سيتم عرض قائمة بمواضيع متشابهة ( لها علاقة ) في أسفل صفحة الموضوع الذي يتم مُشاهدته.',
	'PST_LEGEND1'		=> 'إعدادات عامة',
	'PST_ENABLE'		=> 'إظهار المواضيع المُتشابهه ',
	'PST_LEGEND2'		=> 'إعدادات التحميل',
	'PST_LIMIT'			=> 'عدد المواضيع المُتشابهه ',
	'PST_LIMIT_EXPLAIN'	=> 'تستطيع هنا تحديد عدد المواضيع المُتشابهه التي سيتم عرضها في صفحة المواضيع. العدد الإفتراضي هو 5 مواضيع.',
	'PST_TIME'			=> 'فترة البحث ',
	'PST_TIME_EXPLAIN'	=> 'تستطيع هنا ضبط فترة البحث عن المواضيع المُتشابهه. على سبيال المثال : إذا جعلتها 5 أيام , فالنظام سيعرض فقط المواضيع المُتشابهه نُشرت خلال الـ 5 أيام الماضية. الفترة الإفتراضية هي سنة واحدة.',
	'PST_YEARS'			=> 'سنوات',
	'PST_MONTHS'		=> 'شهور',
	'PST_WEEKS'			=> 'اسابيع',
	'PST_DAYS'			=> 'أيام',
	'PST_CACHE'			=> 'فترة الملفات المؤقتة',
	'PST_CACHE_EXPLAIN'	=> 'سيتم حذف الملفات المؤقتة للمواضيع المُتشابهه بعد تحديد الفترة هنا بالثواني. الصفر يعني تعطيل هذا الخيار.',
	'PST_LEGEND3'		=> 'إعدادات المنتدى',
	'PST_NOSHOW_LIST'	=> 'لا تعرض في',
	'PST_NOSHOW_TITLE'	=> 'لا تعرض المواضيع المُتشابهه في',
	'PST_IGNORE_SEARCH'	=> 'لا تبحث في',
	'PST_IGNORE_TITLE'	=> 'لا تبحث عن المواضيع المُتشابهه في',
	'PST_STANDARD'		=> 'قياسي',
	'PST_ADVANCED'		=> 'متقدم',
	'PST_ADVANCED_TITLE'=> 'انقر  لضبط الإعدادات المتقدمة لـ',
	'PST_ADVANCED_EXP'	=> 'تستطيع من هنا تحديد المنتديات التي تريد جلب المواضيع المُتشابهه منها. المواضيع المُتشابهه الموجودة فقط في المنتديات المُحددة ستظهر في <strong>%s</strong>. <br /><br />سيتم جلب المواضيع المُتشابهه من جميع المنتديات في حالة عدم تحديد أي منتدى.<br /><br />تحديد أو الغاء التحديد يكون بواسطة الضغط باستمرار على زر الكنترول CTRL والنقر على المنتدى المطلوب.',
	'PST_ADVANCED_FORUM'=> 'إعدادات متقدمة للمنتدى',
	'PST_DESELECT_ALL'	=> 'الغاء تحديد الكل',
	'PST_LEGEND4'		=> 'إعدادات اختيارية',
	'PST_WORDS'			=> 'كلمات مُستبعدة',
	'PST_WORDS_EXPLAIN'	=> 'سيتم استبعاد الكلمات التي ستضيفها هنا عند البحث عن المواضيع المُتشابهة. ( ملاحظة : يتم استبعاد الكلمات التي تعتبر شائعة في لغتك بصورة افتراضية ). اعمل مسافة بين كل كلمة وأخرى. هذا الخيار غير دقيق لتطابق الكلمات. الحد الأقصى هو 255 حرف.',
	'PST_SAVED'			=> 'تم تحديث إعدادات المواضيع المُتشابهه',
	'PST_FORUM_INFO'	=> '" لا تعرض في " : يعني عدم اظهار المواضيع المُتشابهه في المنتديات المُحددة.<br />" لا تبحث في " : يعني عدم البحث عن المواضيع المُتشابهه في المنتديات المُحددة.',
	'PST_NO_MYSQL'		=> 'إضافة "المواضيع المُتشابهه" لا تتوافق مع منتداك. فهي تعمل فقط على قاعدة البيانات MySQL 4 أو MySQL 5.',
	'PST_WARN_FULLTEXT'	=> 'إضافة "المواضيع المُتشابهه" لا تتوافق مع منتداك. فهي تستخدم فهرسة النص الكامل FULLTEXT والذي يتطلب توفر قاعدة البيانات MySQL 4 أو MySQL 5 لديك , وأيضاً ضبط الجدول “phpbb_topics” إلى أداة التخزين MyISAM ( أو الـ InnoDB مسموح أيضاً عند استخدامها مع MySQL 5.6.4 أو اصدار أحدث منه ).<br /><br />عليك تحديث قاعدة البيانات لديك بطريقة آمنة لكي تعمل إضافة "المواضيع المُتشابهه". وسيتم حذف التغييرات الجديدة في حالة حذف هذه الإضافة.',
	'PST_ADD_FULLTEXT'	=> 'نعم, تفعيل الدعم لفهرسة النص الكامل FULLTEXT',
	'PST_SAVE_FULLTEXT'	=> 'تم تحديث قاعدة البيانات لديك. تستطيع استخدام المواضيع المُتشابهه الآن.',
	'PST_ERR_FULLTEXT'	=> 'لم يتم تحديث قاعدة البيانات لديك.',
	'PST_ERR_CONFIG'	=> 'أيضا تميزت العديد من المنتديات في قائمة من المنتديات. يرجى المحاولة مرة أخرى مع مجموعة أصغر.',
));
