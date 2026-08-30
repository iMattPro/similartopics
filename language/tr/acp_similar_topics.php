<?php
/**
*
* Precise Similar Topics [Turkish]
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
	'PST_TITLE_ACP'		=> 'Benzer Konular',
	'PST_EXPLAIN'		=> 'Benzer Konular eklentisi benzer (ilişkili) konuların bir listesini mevcut konu sayfasının altında gösterir.',
	'PST_LEGEND1'		=> 'Genel Ayarlar',
	'PST_ENABLE'		=> 'Benzer Konuları Göster',
	'PST_ENABLE_EXPLAIN'=> 'Benzer başlıkları konu tartışmalarında gösterir.',
	'PST_LEGEND2'		=> 'Yükleme ayarları',
	'PST_LIMIT'			=> 'Gösterilecek Benzer Konu sayısı',
	'PST_LIMIT_EXPLAIN'	=> 'Buradan kaç tane Benzer Konunun gösterileceğini belirleyebilirsiniz. Varsayılan değer 5 konudur.',
	'PST_TIME'			=> 'Arama periyodu',
	'PST_TIME_EXPLAIN'	=> 'Bu seçenek size Benzer Konular için arama periyodunu düzenleme imkanı verir. Örneğin, eğer “5 gün” olarak ayarlanmışsa sistem sadece son 5 gündeki benzer konuları gösterecek. Varsayılan değer 1 yıldır. Zaman sınırı olmaması için 0 olarak ayarlayın.',
	'PST_YEARS'			=> 'Yıl',
	'PST_MONTHS'		=> 'Ay',
	'PST_WEEKS'			=> 'Hafta',
	'PST_DAYS'			=> 'Gün',
	'PST_CACHE'			=> 'Benzer Konular önbellek uzunluğu',
	'PST_CACHE_EXPLAIN'	=> 'Önbelleklenmiş benzer konuların süresi bu süre sonunda geçecektir, saniye olarak. 0 olarak ayarlarsanız benzer konular önbellekleme özelliğini kapatırsınız.',
	'PST_DYNAMIC'		=> 'Dinamik benzer başlıkları göster',
	'PST_DYNAMIC_EXPLAIN'=> 'Kullanıcılar yeni başlık oluştururken başlık alanına yazdıkça benzer başlıkları gösterir.',
	'PST_SENSE'			=> 'Arama duyarlılığı',
	'PST_SENSE_EXPLAIN'	=> 'MySQL veya Postgres veritabanlarında arama duyarlılığını 1 ile 10 arasında bir değere ayarlayabilirsiniz. Benzer konu göremiyorsanız daha düşük bir sayı kullanın. Önerilen ayar: %d',
	'PST_LEGEND3'		=> 'Forum ayarları',
	'PST_NOSHOW_LIST'	=> 'Şurada gösterme',
	'PST_NOSHOW_TITLE'	=> 'Benzer Konuları şurada gösterme',
	'PST_IGNORE_SEARCH'	=> 'Şurada arama',
	'PST_IGNORE_TITLE'	=> 'Benzer konular için şurada arama',
	'PST_STANDARD'		=> 'Standart',
	'PST_ADVANCED'		=> 'Gelişmiş',
	'PST_ADVANCED_TITLE'=> 'Gelişmiş benzer konular ayarlarını yüklemek için tıklayın',
	'PST_ADVANCED_EXP'	=> 'Buradan benzer konuların seçileceği belirli forumları seçebilirsin. Sadece burada seçtiğiniz forumlardaki benzer konular şurada gösterilir <strong>%s</strong>.<br><br>Tüm aranabilen forumlardan konuların Benzer Konularda gösterilmesini istiyorsanız hiç bir forumu seçmeyin.<br><br><code>CTRL</code>ye basılı tutup çoklu forum Seçimi/Seçimi Kaldırma işlemini yapabilirsiniz.',
	'PST_ADVANCED_FORUM'=> 'Gelişmiş forum ayarları',
	'PST_DESELECT_ALL'	=> 'Tümünü kaldır',
	'PST_LEGEND4'		=> 'İsteğe bağlı ayarlar',
	'PST_WORDS'			=> 'Dikkate alınmayacak kelimeler',
	'PST_WORDS_EXPLAIN'	=> 'Benzer konular bulunurken yoksayılacak özel kelimeleri ekle. (Not: Dilinizde sık kullanılan kelimeler zaten varsayılan olarak yoksayılmıştır.) Kelimeleri bir boşluk ile ayır. Büyük-küçük harf duyarlıdır.',
	'PST_SAVED'			=> 'Benzer Konular ayarları güncellendi',
	'PST_FORUM_INFO'	=> '“Şurada gösterme”: seçili forumlarda benzer konuları göstermeyecek.<br>“Şurada arama” : Seçili forumlarda benzer konular  için aramayacak.',
	'PST_NO_COMPAT'		=> 'Benzer Konular forumunuzla uyumlu değil. Benzer Konular sadece MySQL 4 veya MySQL 5 veya PostgreSQL veritabanında çalışır.',
	'PST_JAVASCRIPT_REQUIRED' => 'Bu sayfadaki forum ayarlarını ve kaynak seçimlerini düzenlemek için JavaScript gereklidir. Bu ayarları değiştirmeden önce JavaScript\'i etkinleştirin.',
	'PST_ERR_CONFIG'	=> 'Çok fazla forumlar forumlarda listesinde işaretlenmiştir. Küçük bir seçim ile tekrar deneyin.',
	'PST_FEATURES' => 'Benzer konuların nerede görüneceğini seçin',
	'PST_FEATURES_EXPLAIN' => 'Her seçeneği forumun tamamı için açın veya kapatın.',
	'PST_TIME_UNIT' => 'Arama dönemi birimi',
	'PST_CACHE_OFF' => 'Kapalı',
	'PST_CACHE_5_MINUTES' => '5 dakika',
	'PST_CACHE_15_MINUTES' => '15 dakika',
	'PST_CACHE_30_MINUTES' => '30 dakika',
	'PST_CACHE_1_HOUR' => '1 saat',
	'PST_CACHE_2_HOURS' => '2 saat',
	'PST_CACHE_4_HOURS' => '4 saat',
	'PST_CACHE_8_HOURS' => '8 saat',
	'PST_CACHE_12_HOURS' => '12 saat',
	'PST_CACHE_24_HOURS' => '24 saat',
	'PST_CACHE_CUSTOM' => 'Özel: %d saniye',
	'PST_RESULTS' => 'Sonuçları şekillendirin',
	'PST_RESULTS_EXPLAIN' => 'Kaç benzer konunun görüneceğini ve bu konuların ne kadar yeni olması gerektiğini ayarlayın.',
	'PST_FORUM_RULES' => 'Her forumun nasıl katılacağına karar verin',
	'PST_FORUM_RULES_EXPLAIN' => 'Her forumda iki basit anahtar bulunur. “Kaynak seç” seçeneğini yalnızca bir forumun kendi arama havuzuna ihtiyacı olduğunda kullanın.',
	'PST_FORUMS_MANAGED' => 'forumlar',
	'PST_CUSTOM_RULES' => 'özel kaynak ayarları',
	'PST_FILTER_FORUMS' => 'Bir forum bulun…',
	'PST_NO_FORUM_MATCH' => 'Bu aramayla eşleşen forum yok.',
	'PST_SHOW_HERE' => 'Benzer konuları burada göster',
	'PST_SHOW_HERE_EXPLAIN' => 'Bu forumdaki ziyaretçiler benzer konuları görebilir.',
	'PST_SEARCHABLE' => 'Bu forumu kaynak olarak kullanılabilir hale getirin',
	'PST_SEARCHABLE_EXPLAIN' => 'Standart aramalar bu forumdaki konuları bulabilir.',
	'PST_SEARCH_SOURCES' => 'Bu forumun arama yaptığı yer',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Mevcut tüm forumları kullanın veya özel bir set seçin.',
	'PST_CHOOSE_SOURCES' => 'Kaynakları seçin',
	'PST_SOURCE_ALL' => 'Mevcut tüm forumlar',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d seçilen forum',
		2 => '%d seçilmiş forum',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Benzer konuların nereden geldiğini seçin',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Bu seçim yalnızca yukarıda gösterilen forum için geçerlidir. Başka hiçbir forumu değiştirmez.',
	'PST_SOURCE_ALL_EXPLAIN' => '“Kaynak olarak mevcut” anahtarı açık olan tüm forumlarda arama yapın. Yeni forumlar otomatik olarak katılır.',
	'PST_SOURCE_CUSTOM' => 'Yalnızca seçili forumlar',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Sabit bir forum listesinde arama yapın. Sıkıca ilgili bölümler için en iyisi.',
	'PST_FILTER_SOURCES' => 'Kaynak bir forum bulun…',
	'PST_SELECT_AVAILABLE' => 'Mevcut olanların tümünü seç',
	'PST_CLEAR' => 'Seçimi temizle',
	'PST_GLOBALLY_AVAILABLE' => 'Mevcut',
	'PST_GLOBALLY_UNAVAILABLE' => 'Genel olarak kullanılamaz',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'Özel seçenekler genel kullanılabilirlik anahtarlarını geçersiz kılar. Seçilen bir forum, "Genel olarak kullanılamaz" olarak işaretlenmiş olsa bile yine de aranabilir.',
	'PST_SELECT_ONE_SOURCE' => 'En az bir forum seçin veya "Mevcut tüm forumlar"ı kullanın.',
	'PST_APPLY_SOURCES' => 'Bu kaynakları kullanın',
	'PST_TUNING' => 'Eşleştirme ve performansta ince ayar yapın',
	'PST_TUNING_EXPLAIN' => 'Varsayılanlar çoğu toplulukta işe yarar. Bu kontrolleri yalnızca gerektiğinde ayarlayın.',
	'PST_READY_TO_SAVE' => 'Değişikliklerinizi uygulamaya hazır mısınız?',
	'PST_SAVE_EXPLAIN' => 'Bu sayfadaki tüm ayarlar birlikte kaydedilir.',
	'PST_SAVE_SETTINGS' => 'Ayarları kaydet',
));
