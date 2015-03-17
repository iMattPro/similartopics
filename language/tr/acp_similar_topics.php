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
	'PST_EXPLAIN'		=> 'Benzer Konular benzer (ilişkili) konuların bir listesini mevcut konu sayfasının altında gösterir.',
	'PST_LEGEND1'		=> 'Genel Ayarlar',
	'PST_ENABLE'		=> 'Benzer Konuları Göster',
	'PST_LEGEND2'		=> 'Yükleme ayarları',
	'PST_LIMIT'			=> 'Gösterilecek Benzer Konu sayısı',
	'PST_LIMIT_EXPLAIN'	=> 'Buradan kaç tane Benzer Konunun gösterileceğini belirleyebilirsiniz. Varsayılan değer 5 konudur.',
	'PST_TIME'			=> 'Arama periyodu',
	'PST_TIME_EXPLAIN'	=> 'Bu seçenek size Benzer Konular için arama periyodunu düzenleme imkanı verir. Örneğin, eğer “5 gün” olarak ayarlanmışsa sistem sadece son 5 gündeki benzer konuları gösterecek. Varsayılan değer 1 yıldır.',
	'PST_YEARS'			=> 'Yıl',
	'PST_MONTHS'		=> 'Ay',
	'PST_WEEKS'			=> 'Hafta',
	'PST_DAYS'			=> 'Gün',
	'PST_CACHE'			=> 'Benzer Konular önbellek uzunluğu',
	'PST_CACHE_EXPLAIN'	=> 'Önbelleklenmiş benzer konuların süresi bu süre sonunda geçecektir, saniye olarak. 0 olarak ayarlarsanız benzer konular önbellekleme özelliğini kapatırsınız.',
	'PST_LEGEND3'		=> 'Forum ayarları',
	'PST_NOSHOW_LIST'	=> 'Şurada gösterme',
	'PST_NOSHOW_TITLE'	=> 'Benzer Konuları şurada gösterme',
	'PST_IGNORE_SEARCH'	=> 'Şurada arama',
	'PST_IGNORE_TITLE'	=> 'Benzer konular için şurada arama',
	'PST_STANDARD'		=> 'Standart',
	'PST_ADVANCED'		=> 'Gelişmiş',
	'PST_ADVANCED_TITLE'=> 'Gelişmiş benzer konular ayarlarını yüklemek için tıklayın',
	'PST_ADVANCED_EXP'	=> 'Buradan benzer konuların seçileceği belirli forumları seçebilirsin. Sadece burada seçtiğiniz forumlardaki benzer konular şurada gösterilir <strong>%s</strong>.<br /><br />Tüm aranabilen forumlardan konuların Benzer Konularda gösterilmesini istiyorsanız hiç bir forumu seçmeyin.<br /><br /><code>CTRL</code>ye basılı tutup çoklu forum Seçimi/Seçimi Kaldırma işlemini yapabilirsiniz.',
	'PST_ADVANCED_FORUM'=> 'Gelişmiş forum ayarları',
	'PST_DESELECT_ALL'	=> 'Tümünü kaldır',
	'PST_LEGEND4'		=> 'Opsiyonel ayarlar',
	'PST_WORDS'			=> 'Dikkate alınmayacak kelimeler',
	'PST_WORDS_EXPLAIN'	=> 'Benzer konular bulunurken yoksayılacak özel kelimeleri ekle. (Not: Dilinizde sık kullanılan kelimeler zaten varsayılan olarak yoksayılmıştır.) Kelimeleri bir boşluk ile ayır. Büyük-küçük harf duyarlıdır. Maks. 255 karakter.',
	'PST_SAVED'			=> 'Benzer Konular ayarları güncellendi',
	'PST_FORUM_INFO'	=> '“Şurada gösterme”: seçili forumlarda benzer konuları göstermeyecek.<br />“Şurada arama” : Seçili forumlarda benzer konular  için aramayacak.',
	'PST_NO_MYSQL'		=> 'Benzer Konular forumunuzla uyumlu değil. Benzer Konular sadece MySQL 4 veya MySQL 5 veritabanında çalışır.',
	'PST_WARN_FULLTEXT'	=> 'Benzer Konular forumunuzla uyumlu değil. Benzer Konular MySQL 4 veya MySQL 5 veritabanı için gerekli olan FULLTEXT indekslemesini kullanır ve the “phpbb_topics” tablosu MyISAM depolama motoruna ayarlı olmalıdır (veya MySQL 5.6.4 veya daha yeni sürüm kullanıyorsanız InnoDB de izin verilenler arasındadır).<br /><br />Benzer Konuları kullanmak istiyorsanız veritabanınız güvenle FULLTEXT indeksi desteklemesine güncellenebilir. Benzer Konuları kaldırmaya karar verdiğinizde tüm değişiklikler geridöndürülebilir.',
	'PST_ADD_FULLTEXT'	=> 'Evet, enable support for FULLTEXT indexes',
	'PST_SAVE_FULLTEXT'	=> 'Veritabanınınz güncellendi. Benzer Konuları kullanarak eğlenebilirsiniz.',
	'PST_ERR_FULLTEXT'	=> 'Sizin veritabanı güncellendi olamazdı.',
	'PST_ERR_CONFIG'	=> 'Çok fazla forumlar forumlarda listesinde işaretlenmiştir. Küçük bir seçim ile tekrar deneyin.',
));
