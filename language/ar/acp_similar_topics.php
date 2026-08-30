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
	'PST_ENABLE_EXPLAIN'=> 'عرض المواضيع المشابهة في صفحات مناقشة المواضيع.',
	'PST_LEGEND2'		=> 'إعدادات التحميل',
	'PST_LIMIT'			=> 'عدد المواضيع المُتشابهه ',
	'PST_LIMIT_EXPLAIN'	=> 'تستطيع هنا تحديد عدد المواضيع المُتشابهه التي سيتم عرضها في صفحة المواضيع. العدد الإفتراضي هو 5 مواضيع.',
	'PST_TIME'			=> 'فترة البحث ',
	'PST_TIME_EXPLAIN'	=> 'تستطيع هنا ضبط فترة البحث عن المواضيع المُتشابهه. على سبيال المثال : إذا جعلتها 5 أيام , فالنظام سيعرض فقط المواضيع المُتشابهه نُشرت خلال الـ 5 أيام الماضية. الفترة الإفتراضية هي سنة واحدة. اضبط القيمة على 0 لعدم وجود حد زمني.',
	'PST_YEARS'			=> 'سنوات',
	'PST_MONTHS'		=> 'شهور',
	'PST_WEEKS'			=> 'اسابيع',
	'PST_DAYS'			=> 'أيام',
	'PST_CACHE'			=> 'فترة الملفات المؤقتة',
	'PST_CACHE_EXPLAIN'	=> 'سيتم حذف الملفات المؤقتة للمواضيع المُتشابهه بعد تحديد الفترة هنا بالثواني. الصفر يعني تعطيل هذا الخيار.',
	'PST_DYNAMIC'		=> 'عرض المواضيع المشابهة ديناميكياً',
	'PST_DYNAMIC_EXPLAIN'=> 'عرض المواضيع المشابهة أثناء كتابة المستخدمين في حقل عنوان الموضوع عند إنشاء مواضيع جديدة.',
	'PST_SENSE'			=> 'حساسية البحث',
	'PST_SENSE_EXPLAIN'	=> 'حدد درجة حساسية البحث من القيمة 1 و 10. استخدم القيمة الأقل إذا لا تشاهد أي نتائج للمواضيع المُتشابهه. الإعدادات الموصى بها : <samp>%d</samp>',
	'PST_LEGEND3'		=> 'إعدادات المنتدى',
	'PST_NOSHOW_LIST'	=> 'لا تعرض في',
	'PST_NOSHOW_TITLE'	=> 'لا تعرض المواضيع المُتشابهه في',
	'PST_IGNORE_SEARCH'	=> 'لا تبحث في',
	'PST_IGNORE_TITLE'	=> 'لا تبحث عن المواضيع المُتشابهه في',
	'PST_STANDARD'		=> 'قياسي',
	'PST_ADVANCED'		=> 'متقدم',
	'PST_ADVANCED_TITLE'=> 'انقر  لضبط الإعدادات المتقدمة لـ',
	'PST_ADVANCED_EXP'	=> 'تستطيع من هنا تحديد المنتديات التي تريد جلب المواضيع المُتشابهه منها. المواضيع المُتشابهه الموجودة فقط في المنتديات المُحددة ستظهر في <strong>%s</strong>. <br><br>سيتم جلب المواضيع المُتشابهه من جميع المنتديات في حالة عدم تحديد أي منتدى.<br><br>تحديد أو الغاء التحديد يكون بواسطة الضغط باستمرار على زر الكنترول CTRL والنقر على المنتدى المطلوب.',
	'PST_ADVANCED_FORUM'=> 'إعدادات متقدمة للمنتدى',
	'PST_DESELECT_ALL'	=> 'الغاء تحديد الكل',
	'PST_LEGEND4'		=> 'إعدادات اختيارية',
	'PST_WORDS'			=> 'كلمات مُستبعدة',
	'PST_WORDS_EXPLAIN'	=> 'سيتم استبعاد الكلمات التي ستضيفها هنا عند البحث عن المواضيع المُتشابهة. ( ملاحظة : يتم استبعاد الكلمات التي تعتبر شائعة في لغتك بصورة افتراضية ). اعمل مسافة بين كل كلمة وأخرى. هذا الخيار غير دقيق لتطابق الكلمات. الحد الأقصى هو 255 حرف.',
	'PST_SAVED'			=> 'تم تحديث إعدادات المواضيع المُتشابهه',
	'PST_FORUM_INFO'	=> '" لا تعرض في " : يعني عدم اظهار المواضيع المُتشابهه في المنتديات المُحددة.<br>" لا تبحث في " : يعني عدم البحث عن المواضيع المُتشابهه في المنتديات المُحددة.',
	'PST_NO_COMPAT'		=> 'إضافة "المواضيع المُتشابهه" لا تتوافق مع منتداك. فهي تعمل فقط على قاعدة البيانات MySQL 4 أو MySQL 5 أو PostgreSQL.',
	'PST_ERR_CONFIG'	=> 'أيضا تميزت العديد من المنتديات في قائمة من المنتديات. يرجى المحاولة مرة أخرى مع مجموعة أصغر.',
	'PST_FEATURES' => 'اختر مكان ظهور المواضيع المشابهة',
	'PST_FEATURES_EXPLAIN' => 'قم بتشغيل أو إيقاف كل خيار في المنتدى بأكمله.',
	'PST_TIME_UNIT' => 'وحدة فترة البحث',
	'PST_CACHE_OFF' => 'عن',
	'PST_CACHE_5_MINUTES' => '5 دقائق',
	'PST_CACHE_15_MINUTES' => '15 دقيقة',
	'PST_CACHE_30_MINUTES' => '30 دقيقة',
	'PST_CACHE_1_HOUR' => '1 ساعة',
	'PST_CACHE_2_HOURS' => 'ساعاتين',
	'PST_CACHE_4_HOURS' => '4 ساعات',
	'PST_CACHE_8_HOURS' => '8 ساعات',
	'PST_CACHE_12_HOURS' => '12 ساعة',
	'PST_CACHE_24_HOURS' => '24 ساعة',
	'PST_CACHE_CUSTOM' => 'مخصص: %d ثانية',
	'PST_RESULTS' => 'تشكيل النتائج',
	'PST_RESULTS_EXPLAIN' => 'قم بتعيين عدد المواضيع المشابهة التي تظهر ومدى حداثة هذه المواضيع.',
	'PST_FORUM_RULES' => 'قرر كيفية مشاركة كل منتدى',
	'PST_FORUM_RULES_EXPLAIN' => 'يحتوي كل منتدى على مفتاحين بسيطين. استخدم "اختيار المصادر" فقط عندما يحتاج المنتدى إلى مجموعة بحث خاصة به.',
	'PST_FORUMS_MANAGED' => 'المنتديات',
	'PST_CUSTOM_RULES' => 'قواعد المصدر المخصصة',
	'PST_FILTER_FORUMS' => 'ابحث عن منتدى...',
	'PST_NO_FORUM_MATCH' => 'لا توجد منتديات تطابق هذا البحث.',
	'PST_SHOW_HERE' => 'عرض مواضيع مماثلة هنا',
	'PST_SHOW_HERE_EXPLAIN' => 'يمكن لزوار هذا المنتدى رؤية مواضيع مماثلة.',
	'PST_SEARCHABLE' => 'جعل هذا المنتدى متاحا كمصدر',
	'PST_SEARCHABLE_EXPLAIN' => 'قد تجد عمليات البحث القياسية موضوعات من هذا المنتدى.',
	'PST_SEARCH_SOURCES' => 'حيث يبحث هذا المنتدى',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'استخدم كل منتدى متاح أو اختر مجموعة مخصصة.',
	'PST_CHOOSE_SOURCES' => 'اختر المصادر',
	'PST_SOURCE_ALL' => 'جميع المنتديات المتاحة',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d منتدى محدد',
		2 => '%d منتديان محددان',
		3 => '%d منتديات محددة',
		4 => '%d منتدى محددًا',
		5 => '%d منتدى محدد',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'اختر مصدر المواضيع المشابهة',
	'PST_SOURCE_MODAL_EXPLAIN' => 'ينطبق هذا الاختيار فقط على المنتدى الموضح أعلاه. ولا يغير أي منتدى آخر.',
	'PST_SOURCE_ALL_EXPLAIN' => 'ابحث في كل منتدى يكون مفتاح "متاح كمصدر" قيد التشغيل. المنتديات الجديدة تنضم تلقائيا.',
	'PST_SOURCE_CUSTOM' => 'المنتديات المختارة فقط',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'البحث في قائمة ثابتة من المنتديات. الأفضل للأقسام ذات الصلة الوثيقة.',
	'PST_FILTER_SOURCES' => 'ابحث عن منتدى المصدر...',
	'PST_SELECT_AVAILABLE' => 'حدد كل ما هو متاح',
	'PST_CLEAR' => 'مسح التحديد',
	'PST_GLOBALLY_AVAILABLE' => 'متاح',
	'PST_GLOBALLY_UNAVAILABLE' => 'غير متوفر عالميًا',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'تتجاوز الاختيارات المخصصة مفاتيح التوفر العالمية. لا يزال من الممكن البحث في المنتدى المحدد حتى عند وضع علامة "غير متوفر عالميًا".',
	'PST_SELECT_ONE_SOURCE' => 'اختر منتدى واحدًا على الأقل، أو استخدم "جميع المنتديات المتاحة".',
	'PST_APPLY_SOURCES' => 'استخدم هذه المصادر',
	'PST_TUNING' => 'صقل المطابقة والأداء',
	'PST_TUNING_EXPLAIN' => 'تعمل الإعدادات الافتراضية في معظم المجتمعات. اضبط عناصر التحكم هذه فقط عند الحاجة.',
	'PST_READY_TO_SAVE' => 'هل أنت مستعد لتطبيق تغييراتك؟',
	'PST_SAVE_EXPLAIN' => 'يتم حفظ كافة الإعدادات الموجودة في هذه الصفحة معًا.',
	'PST_SAVE_SETTINGS' => 'حفظ الإعدادات',
));
