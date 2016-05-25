<?php
/**
*
* Precise Similar Topics [Portuguese]
* Translated by The Crow: http://phpbbportugal.com - segundo as normas do Acordo Ortográfico
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
	'PST_TITLE_ACP'		=> 'Tópicos Semelhantes',
	'PST_EXPLAIN'		=> 'Tópicos Semelhantes apresenta uma lista de tópicos semelhantes, na parte inferior da página do tópico atual.',
	'PST_LEGEND1'		=> 'Configurações gerais',
	'PST_ENABLE'		=> 'Ativar Tópicos Semelhantes',
	'PST_LEGEND2'		=> 'Carregar configurações',
	'PST_LIMIT'			=> 'Número de Tópicos Semelhantes a exibir',
	'PST_LIMIT_EXPLAIN'	=> 'Indique o número de Tópicos Semelhantes a exibir. O padrão é 5 Tópicos.',
	'PST_TIME'			=> 'Período de pesquisa',
	'PST_TIME_EXPLAIN'	=> 'Indique o período de pesquisa dos Tópicos Semelhantes. Por exemplo, se selecionar <strong>5 dias</strong>, serão exibidos os Tópicos Semelhantes dos últimos cinco dias. O padrão é 1 ano.',
	'PST_YEARS'			=> 'Anos',
	'PST_MONTHS'		=> 'Meses',
	'PST_WEEKS'			=> 'Semanas',
	'PST_DAYS'			=> 'Dias',
	'PST_CACHE'			=> 'Duração do Cache de Tópicos Semelhantes',
	'PST_CACHE_EXPLAIN'	=> 'O Cache de Tópicos Semelhantes vai expirar após esse tempo em segundos. 0 desativar este recurso.',
	'PST_LEGEND3'		=> 'Configurações do fórum',
	'PST_NOSHOW_LIST'	=> 'Não exibir em',
	'PST_NOSHOW_TITLE'	=> 'Não exibir Tópicos Semelhantes em',
	'PST_IGNORE_SEARCH'	=> 'Não pesquisar em',
	'PST_IGNORE_TITLE'	=> 'Não pesquisar Tópicos Semelhantes em',
	'PST_STANDARD'		=> 'Padrão',
	'PST_ADVANCED'		=> 'Avançado',
	'PST_ADVANCED_TITLE'=> 'Clique para configurar as definições avançadas de Tópicos Semelhantes para',
	'PST_ADVANCED_EXP'	=> 'Aqui você pode selecionar os Fóruns de onde serão extraídos os Tópicos Semelhantes. Apenas Tópicos Semelhantes encontrados nos Fóruns que selecionar aqui serão exibidos em <strong>%s</strong>.<br /><br />Não selecione nenhum se deseja Tópicos Semelhantes de todos os Fóruns pesquisáveis a serem exibidos neste Fórum.<br /><br />Selecione/Desmarque múltiplos Fóruns clicando <code>CTRL</code> e clicando.',
	'PST_ADVANCED_FORUM'=> 'Configurações avançadas de fórum',
	'PST_DESELECT_ALL'	=> 'Desmarcar todos',
	'PST_LEGEND4'		=> 'Configurações opcionais',
	'PST_WORDS'			=> 'Palavras especiais para ignorar',
	'PST_WORDS_EXPLAIN'	=> 'Adicionar palavras especiais exclusivas para o seu fórum que deve ser ignorado em topicos semelhantes. (Nota: Palavras comuns na sua língua são ignorados por padrão.) Separar cada palavra com um espaço. A pesquisa não é sensível à maiúsculas/minúsculas. Máximo 255 caracteres.',
	'PST_SAVED'			=> 'As configurações de Tópicos Semelhantes foram atualizadas',
	'PST_FORUM_INFO'	=> '<strong>Não exibir em</strong>: Desativa a exibição de Tópicos Semelhantes nos Fóruns selecionados.<br /><strong>Não pesquisar em</strong>: Ignora os Fóruns selecionados na pesquisa de Tópicos Semelhantes.',
	'PST_NO_MYSQL'		=> 'Tópicos Semelhantes não irá funcionar com seu Fórum. Similar Topics requer uma Base de Dados MySQL 4 ou MySQL 5.',
	'PST_WARN_FULLTEXT'	=> 'Tópicos Semelhantes não irá funcionar com seu Fórum. Tópicos Semelhantes usa índices FULLTEXT, que exigem um banco de dados MySQL 4 ou MySQL 5 e a tabela “phpbb_topics” deve ser definido como o mecanismo de armazenamento MyISAM (ou InnoDB também é permitido quando usado com MySQL 5.6.4 ou mais recente).<br /><br />Se você quiser usar Tópicos Semelhantes, podemos seguramente atualizar seu banco de dados para suportar índices FULLTEXT. Todas as alterações feitas serão revertidas se você decidir que quer remover Tópicos Semelhantes.',
	'PST_ADD_FULLTEXT'	=> 'Sim, ativar o suporte para índices FULLTEXT',
	'PST_SAVE_FULLTEXT'	=> 'Seu banco de dados foi atualizado. Agora você pode usar o Tópicos Semelhantes.',
	'PST_ERR_FULLTEXT'	=> 'Seu banco de dados não pôde ser atualizado.',
	'PST_ERR_CONFIG'	=> 'Muitos fóruns foram marcados na lista de fóruns. Por favor, tente novamente com uma seleção menor.',
));
