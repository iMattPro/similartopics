<?php
/**
*
* similar_topics [Portuguese]
*
* @package language
* @copyright (c) 2010 Matt Friedman (Traduzido por The Crow: http://phpbbportugal.com - segundo as normas do Acordo Ortográfico)
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
	'PST_TITLE_ACP'		=> 'Tópicos Semelhantes',
	'PST_TITLE'			=> 'Precise Similar Topics II',
	'PST_LEGEND1'		=> 'Configurações Gerais',
	'PST_ENABLE'		=> 'Ativar Tópicos Semelhantes',
	'PST_LEGEND2'		=> 'Carregar Configurações',
	'PST_LIMIT'			=> 'Número de Tópicos Semelhantes a exibir',
	'PST_LIMIT_EXPLAIN'	=> 'Indique o número de Tópicos Semelhantes a exibir. O padrão é 5 Tópicos.',
	'PST_TIME'			=> 'Período de Pesquisa',
	'PST_TIME_EXPLAIN'	=> 'Indique período de pesquisa dos Tópicos Semelhantes. Por exemplo, se selecionar <strong>5 dias</strong>, serão exibidos os Tópicos Semelhantes dos últimos cinco dias. O padrão é 1 ano.',	
	'PST_YEARS'			=> 'Anos',
	'PST_MONTHS'		=> 'Meses',
	'PST_WEEKS'			=> 'Semanas',
	'PST_DAYS'			=> 'Dias',
	'PST_CACHE'			=> 'Duração da Cache de Tópicos Semelhantes',
	'PST_CACHE_EXPLAIN'	=> 'A Cache de Tópicos Semelhantes vai expirar após esse tempo, em segundos. 0 para desativar a Cache de Tópicos Semelhantes.',
	'PST_LEGEND3'		=> 'Configurações do Fórum',
	'PST_NOSHOW_LIST' 	=> 'Não exibir em',
	'PST_NOSHOW_TITLE'	=> 'Não exibir Tópicos Semelhantes em',
	'PST_IGNORE_SEARCH' => 'Não pesquisar em',
	'PST_IGNORE_TITLE'	=> 'Não pesquisar Tópicos Semelhantes em',
	'PST_ADVANCED'		=> 'Avançado',
	'PST_ADVANCED_TITLE'=> 'Clique para configurar definições avançadas de Tópicos Semelhantes para',
	'PST_ADVANCED_EXP'	=> 'Aqui pode selecionar os Fóruns de onde serão extraidos os Tópicos Semelhantes. Apenas Tópicos Semelhantes encontrados nos Fóruns que selecionar aqui serão exibidos em <strong>%s</strong>.<br /><br />Não selecione nenhum se deseja Tópicos Semelhantes de todos os Fóruns pesquisáveis a serem exibidos neste Fórum.',
	'PST_DESELECT_ALL'	=> 'Desmarcar todos',
	'PST_LEGEND4'		=> 'Configurações opcionais',
	'PST_WORDS'			=> 'Palavras especiais para ignorar',
	'PST_WORDS_EXPLAIN'	=> 'Adicionar palavras especiais exclusivas para o seu fórum que deve ser ignorado em topicos semelhantes. (Nota: Palavras comuns na sua língua são ignorados por padrão.) Separar cada palavra com um espaço. De caso não é sensível. Máximo 255 caracteres.',
	'PST_SAVED'			=> 'As configurações de Tópicos Semelhantes foram atualizadas',
	'PST_FORUM_INFO'	=> '<strong>Não exibir em</strong>: Desativa a exibição de Tópicos Semelhantes nos Fóruns selecionados.<br /><strong>Não pesquisar em</strong>: Ignora os Fóruns selecionados na pesquisa de Tópicos Semelhantes.',
	'PST_WARNING'		=> 'Similar Topics não irá funcionar com seu Fórum. Similar Topics requer uma Base de Dados MySQL 4 ou MySQL 5.',
	'PST_LOG_MSG'		=> '<strong>As configurações de Tópicos Semelhantes foram alteradas</strong>',

	//For UMIL Installer
	'PST_FULLTEXT_ADD'	=> 'Adicionando índice FULLTEXT: topic_title',
	'PST_FULLTEXT_DROP'	=> 'Removido índice FULLTEXT: topic_title',
));

// For permissions
$lang = array_merge($lang, array(
	'acl_u_similar_topics'    => array('lang' => 'Pode ver Tópicos Semelhantes', 'cat' => 'misc'),
));

?>