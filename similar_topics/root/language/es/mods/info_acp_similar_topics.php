<?php
/**
*
* similar_topics [Spanish]
*
* @package language
* @version $Id: info_acp_similar_topics.php 15 9/30/11 8:08 PM VSE $
* @copyright (c) 2010 Matt Friedman
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	//For ACP Page
	'PST_TITLE_ACP'		=> 'Hilos Parecidos',
	'PST_TITLE'			=> 'Hilos Parecidos Precisos II',
	'PST_LEGEND1'		=> 'Configuración General',
	'PST_ENABLE'		=> 'Habilitar Hilos Parecidos',
	'PST_LEGEND2'		=> 'Cargar configuración',
	'PST_LIMIT'			=> 'Número de Hilos Parecidos que deben mostrarse',
	'PST_LIMIT_EXPLAIN'	=> 'Aquí puedes definir cuántos Hilos (mensajes) Parecidos deben mostrarse. El número por defecto es 5 hilos.',
	'PST_TIME'			=> 'Período de búsqueda',
	'PST_TIME_EXPLAIN'	=> 'Esta opción permite configurar el período de búsqueda de Hilos Parecidos. Por ejemplo, si se establece en "5 días" el sistema sólo mostrará hilos similares publicados en los últimos 5 días. Por defecto el período es de 1 año.',	
	'PST_YEARS'			=> 'Años',
	'PST_MONTHS'		=> 'Meses',
	'PST_WEEKS'			=> 'Semanas',
	'PST_DAYS'			=> 'Días',
	'PST_CACHE'			=> 'Longitud de la caché de Hilos Parecidos',
	'PST_CACHE_EXPLAIN'	=> 'La caché de hilos parecidos expirará después del tiempo indicado, en segundos. Ponlo a 0 si quieres deshabilitar la caché de hilos parecidos.',
	'PST_LEGEND3'		=> 'Configuración de Foro',
	'PST_NOSHOW_LIST' 	=> 'No mostrar en',
	'PST_NOSHOW_TITLE'	=> 'No mostrar hilos parecidos en',
	'PST_IGNORE_SEARCH' => 'No buscar en',
	'PST_IGNORE_TITLE'	=> 'No buscar hilos parecidos en',
	'PST_ADVANCED'		=> 'Avanzado',
	'PST_ADVANCED_TITLE'=> 'Haz click para habilitar la configuración avanzada de hilos parecidos para',
	'PST_ADVANCED_EXP'	=> 'Aquí puedes seleccionar foros específicos de donde extraer hilos parecidos. Sólo los hilos parecidos que se encuentren en los foros que selecciones aquí se mostrarán en <strong>%s</strong>.<br /><br />No selecciones ningún foro si quieres que todos los foros que tengan habilitado la búsqueda en ellos sean mostrados en este foro.',
	'PST_DESELECT_ALL'	=> 'Deseleccionar todos',
	'PST_LEGEND4'		=> 'Configuración opcional',
	'PST_WORDS'			=> 'Palabras especiales para ignorar',
	'PST_WORDS_EXPLAIN'	=> 'Añadir palabras especiales exclusivas de su foro en el que deben ser ignoradas en hilos parecidos. (Nota: Las palabras comunes en su lengua son ignorados por defecto.) Separe cada palabra con un espacio. Mayúsculas no minúsculas. Un máximo de 255 caracteres.',
	'PST_SAVED'			=> 'La configuración de Hilos Parecidos ha sido actualizada',
	'PST_FORUM_INFO'	=> '“No Mostrar En”: Deshabilitará la visualización de hilos parecidos en el foro seleccionado.<br />“No Buscar En” : Ignorará los foros seleccionados cuando se estén buscando hilos parecidos.',
	'PST_WARNING'		=> 'Hilos Parecidos no va a funcionar en tu foro. Hilos Parecidos requiere una base de datos MySQL 4 o MySQL 5.',
	'PST_LOG_MSG'		=> '<strong>Se modificó la configuración de hilos parecidos</strong>',

	//For UMIL Installer
	'PST_FULLTEXT_ADD'	=> 'Añadiendo índice FULLTEXT: topic_title',
	'PST_FULLTEXT_DROP'	=> 'Eliminando índice FULLTEXT: topic_title',
));

// For permissions
$lang = array_merge($lang, array(
	'acl_u_similar_topics'    => array('lang' => 'Puede visualizar hilos parecidos', 'cat' => 'misc'),
));

?>