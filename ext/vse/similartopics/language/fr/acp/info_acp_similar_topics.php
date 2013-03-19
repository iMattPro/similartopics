<?php
/**
 *
 * info_acp_similiar_topics [French]
 * 
 * @package language
 * @copyright (c) 2013 Matt Friedman - Geolim4.com (French Translation)
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
	'PST_TITLE_ACP'		=> 'Sujets similaires',
	'PST_TITLE'			=> 'Precise Similar Topics II',//Do not rename, even as in French
	'PST_LEGEND1'		=> 'Paramètres généraux',
	'PST_ENABLE'		=> 'Activer les sujets similaires',
	'PST_LEGEND2'		=> 'Enregistrer les paramètres',
	'PST_LIMIT'			=> 'Nom de sujets similaires a afficher',
	'PST_LIMIT_EXPLAIN'	=> 'Ici vous pouvez sélectionner le nombre de sujet similaire à afficher. 5 par défaut',
	'PST_TIME'			=> 'Période de recherche',
	'PST_TIME_EXPLAIN'	=> 'Cette option vous permet de choisir la periode de recherche des sujets similaires. Par exemple, si vous choisissez “5 jours” le système va seulement afficher les sujets créés depuis 5 jours. 1 an par défaut.',
	'PST_YEARS'			=> 'Ans',
	'PST_MONTHS'		=> 'Mois',
	'PST_WEEKS'			=> 'Semaines',
	'PST_DAYS'			=> 'Jours',
	'PST_CACHE'			=> 'Taille du cache des sujets similaires',
	'PST_CACHE_EXPLAIN'	=> 'La mise en cache des sujets similaires expiereras après cette période. Réglez sur 0 pour désactiver la mise en cache des sujets similaires.',
	'PST_LEGEND3'		=> 'Paramètres de forums',
	'PST_NOSHOW_LIST'	=> 'Ne pas afficher dans',
	'PST_NOSHOW_TITLE'	=> 'Ne pas afficher les sujet similaires dans',
	'PST_IGNORE_SEARCH'	=> 'Ne pas rechercher dans',
	'PST_IGNORE_TITLE'	=> 'Ne pas rechercher les sujet similaires dans',
	'PST_ADVANCED'		=> 'Avancé',
	'PST_ADVANCED_TITLE'=> 'Cliquez pour configurer les paramètres avancés de sujet similaire',
	'PST_ADVANCED_EXP'	=> 'Ici, vous pouvez sélectionner des forums spécifiques pour afficher les sujets similaires. Seuls les sujets similaires trouvés dans les forums que vous sélectionnez ici seront affiché dans <strong>%s</strong>. <br /> <br /> Ne sélectionnez aucun forum si vous souhaitez que les sujets similaires sois consultables depuis tout le forum.',
	'PST_DESELECT_ALL'	=> 'Tout dé-sélectionner ',
	'PST_LEGEND4'		=> 'Paramètres optionnels',
	'PST_WORDS'			=> 'Mots spéciaux à ignorer',
	'PST_WORDS_EXPLAIN'	=> 'Ajoutez des mots spéciaux uniques à votre forum qui doivent être ignorés lors de la recherche des sujets similaires. (Remarque: les mots qui sont actuellement considérés comme communs dans votre langue sont déjà ignorés par défaut.) Séparez chaque mot d’un espace. Insensible à la casse. Max. 255 caractères.',
	'PST_SAVED'			=> 'Paramètres des sujets similaire mit à jour',
	'PST_FORUM_INFO'	=> '"Ne pas afficher": N’afficheras pas de sujets similaires dans les forums sélectionnés <br /> "Ne pas rechercher dans":. Ne rechercheras pas de sujets similaires dans les forums sélectionnés.',
	'PST_WARNING'		=> 'Les sujets similaires ne fonctionneront pas avec sur forum. Ils nécessitent une base de donnée MySQL 4 ou 5.',
	'PST_LOG_MSG'		=> '<strong>Modifications des paramètres de sujets similaires</strong>',
));

// For permissions
$lang = array_merge($lang, array(
	'acl_u_similar_topics'	=> array('lang' => 'Peux voir les sujets similaires', 'cat' => 'misc'),
));
