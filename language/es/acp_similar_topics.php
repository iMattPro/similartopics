<?php
/**
*
* Precise Similar Topics [Spanish]
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
	'PST_TITLE_ACP'		=> 'Hilos Parecidos Precisos',
	'PST_EXPLAIN'		=> 'Hilos Parecidos Precisos muestra una lista de temas similares en la parte inferior de la página del tema actual.',
	'PST_LEGEND1'		=> 'Configuración general',
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
	'PST_LEGEND3'		=> 'Configuración de foro',
	'PST_NOSHOW_LIST'	=> 'No mostrar en',
	'PST_NOSHOW_TITLE'	=> 'No mostrar hilos parecidos en',
	'PST_IGNORE_SEARCH'	=> 'No buscar en',
	'PST_IGNORE_TITLE'	=> 'No buscar hilos parecidos en',
	'PST_STANDARD'		=> 'Estándar',
	'PST_ADVANCED'		=> 'Avanzado',
	'PST_ADVANCED_TITLE'=> 'Haz click para habilitar la configuración avanzada de hilos parecidos para',
	'PST_ADVANCED_EXP'	=> 'Aquí puedes seleccionar foros específicos de donde extraer hilos parecidos. Sólo los hilos parecidos que se encuentren en los foros que selecciones aquí se mostrarán en <strong>%s</strong>.<br /><br />No selecciones ningún foro si quieres que todos los foros que tengan habilitado la búsqueda en ellos sean mostrados en este foro.<br /><br />Seleccione/Deseleccione multiples foros manteniendo pulsada la tecla <code>CTRL</code> y haciendo clic en los foros deseados.',
	'PST_ADVANCED_FORUM'=> 'Configuración avanzada del foro',
	'PST_DESELECT_ALL'	=> 'Deseleccionar todos',
	'PST_LEGEND4'		=> 'Configuración opcional',
	'PST_WORDS'			=> 'Palabras especiales para ignorar',
	'PST_WORDS_EXPLAIN'	=> 'Añadir palabras especiales exclusivas de su foro en el que deben ser ignoradas en hilos parecidos. (Nota: Las palabras comunes en su lengua son ignorados por defecto.) Separe cada palabra con un espacio. Mayúsculas no minúsculas. Un máximo de 255 caracteres.',
	'PST_SAVED'			=> 'La configuración de Hilos Parecidos ha sido actualizada',
	'PST_FORUM_INFO'	=> '“No Mostrar En”: Deshabilitará la visualización de hilos parecidos en el foro seleccionado.<br />“No Buscar En” : Ignorará los foros seleccionados cuando se estén buscando hilos parecidos.',
	'PST_NO_MYSQL'		=> 'Hilos Parecidos no va a funcionar en tu foro. Hilos Parecidos requiere una base de datos MySQL 4 o MySQL 5.',
	'PST_WARN_FULLTEXT'	=> 'Hilos Parecidos no va a funcionar en tu foro. Hilos Parecidos utiliza índices FULLTEXT que requieren una base de datos MySQL 4 o MySQL 5 y la tabla “phpbb_topics” se debe establecer en el motor de almacenamiento MyISAM (o InnoDB también se permite cuando se utiliza con MySQL 5.6.4 o posterior).<br /><br />Si desea utilizar Hilos Parecidos, que podemos actualizar con seguridad su base de datos para apoyar índices FULLTEXT. Cualquier cambio realizado se revertirán si alguna vez decide quitar Hilos Parecidos.',
	'PST_ADD_FULLTEXT'	=> 'Sí, activar el soporte de índices FULLTEXT',
	'PST_SAVE_FULLTEXT'	=> 'Su base de datos se ha actualizado. Ahora puede disfrutar con Hilos Parecidos.',
	'PST_ERR_FULLTEXT'	=> 'Su base de datos no se pudo actualizar.',
	'PST_ERR_CONFIG'	=> 'Demasiados foros fueron marcados en la lista de foros. Inténtalo de nuevo con una selección más pequeña.',
));
