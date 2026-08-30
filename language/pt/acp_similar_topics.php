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
	'PST_TITLE_ACP'		=> 'Precise Similar Topics',
	'PST_EXPLAIN'		=> 'Precise Similar Topics apresenta uma lista de temas semelhantes, na parte inferior da página do tópico atual.',
	'PST_LEGEND1'		=> 'Configurações gerais',
	'PST_ENABLE'		=> 'Ativar Tópicos Semelhantes',
	'PST_ENABLE_EXPLAIN'=> 'Mostrar tópicos semelhantes nas discussões dos tópicos.',
	'PST_LEGEND2'		=> 'Carregar configurações',
	'PST_LIMIT'			=> 'Número de Tópicos Semelhantes a exibir',
	'PST_LIMIT_EXPLAIN'	=> 'Indique o número de Tópicos Semelhantes a exibir. O padrão é 5 Tópicos.',
	'PST_TIME'			=> 'Período de pesquisa',
	'PST_TIME_EXPLAIN'	=> 'Indique período de pesquisa dos Tópicos Semelhantes. Por exemplo, se selecionar <strong>5 dias</strong>, serão exibidos os Tópicos Semelhantes dos últimos cinco dias. O padrão é 1 ano. Defina como 0 para não aplicar qualquer limite de tempo.',
	'PST_YEARS'			=> 'Anos',
	'PST_MONTHS'		=> 'Meses',
	'PST_WEEKS'			=> 'Semanas',
	'PST_DAYS'			=> 'Dias',
	'PST_CACHE'			=> 'Duração da Cache de Tópicos Semelhantes',
	'PST_CACHE_EXPLAIN'	=> 'A Cache de Tópicos Semelhantes vai expirar após esse tempo, em segundos. 0 para desativar a Cache de Tópicos Semelhantes.',
	'PST_DYNAMIC'		=> 'Mostrar tópicos semelhantes dinamicamente',
	'PST_DYNAMIC_EXPLAIN'=> 'Mostrar tópicos semelhantes enquanto os utilizadores escrevem no campo do título ao criar novos tópicos.',
	'PST_SENSE'			=> 'Sensibilidade da pesquisa',
	'PST_SENSE_EXPLAIN'	=> 'Em bases de dados MySQL ou Postgres, pode definir a sensibilidade da pesquisa entre 1 e 10. Utilize um número menor se não forem apresentados tópicos semelhantes. Definição recomendada: %d',
	'PST_LEGEND3'		=> 'Configurações do fórum',
	'PST_NOSHOW_LIST'	=> 'Não exibir em',
	'PST_NOSHOW_TITLE'	=> 'Não exibir Tópicos Semelhantes em',
	'PST_IGNORE_SEARCH'	=> 'Não pesquisar em',
	'PST_IGNORE_TITLE'	=> 'Não pesquisar Tópicos Semelhantes em',
	'PST_STANDARD'		=> 'Norma',
	'PST_ADVANCED'		=> 'Avançado',
	'PST_ADVANCED_TITLE'=> 'Clique para configurar definições avançadas de Tópicos Semelhantes para',
	'PST_ADVANCED_EXP'	=> 'Aqui pode selecionar os Fóruns de onde serão extraidos os Tópicos Semelhantes. Apenas Tópicos Semelhantes encontrados nos Fóruns que selecionar aqui serão exibidos em <strong>%s</strong>.<br><br>Não selecione nenhum se deseja Tópicos Semelhantes de todos os Fóruns pesquisáveis a serem exibidos neste Fórum.<br><br>Selecione/Desmarque múltiplos Fóruns clicando <code>CTRL</code> e clicando.',
	'PST_ADVANCED_FORUM'=> 'Configurações avançadas de fórum',
	'PST_DESELECT_ALL'	=> 'Desmarcar todos',
	'PST_LEGEND4'		=> 'Configurações opcionais',
	'PST_WORDS'			=> 'Palavras especiais para ignorar',
	'PST_WORDS_EXPLAIN'	=> 'Adicionar palavras especiais exclusivas para o seu fórum que deve ser ignorado em topicos semelhantes. (Nota: Palavras comuns na sua língua são ignorados por padrão.) Separar cada palavra com um espaço. De caso não é sensível.',
	'PST_SAVED'			=> 'As configurações de Tópicos Semelhantes foram atualizadas',
	'PST_FORUM_INFO'	=> '<strong>Não exibir em</strong>: Desativa a exibição de Tópicos Semelhantes nos Fóruns selecionados.<br><strong>Não pesquisar em</strong>: Ignora os Fóruns selecionados na pesquisa de Tópicos Semelhantes.',
	'PST_NO_COMPAT'		=> 'Similar Topics não irá funcionar com seu Fórum. Similar Topics requer uma Base de Dados MySQL 4 ou MySQL 5 ou PostgreSQL.',
	'PST_JAVASCRIPT_REQUIRED' => 'O JavaScript é necessário para editar as configurações dos fóruns e as seleções de fontes nesta página. Ative o JavaScript antes de alterar essas configurações.',
	'PST_ERR_CONFIG'	=> 'Too muitos fóruns foram marcados na lista de fóruns. Por favor, tente novamente com uma seleção menor.',
	'PST_FEATURES' => 'Escolha onde os tópicos semelhantes aparecem',
	'PST_FEATURES_EXPLAIN' => 'Ative ou desative cada opção para todo o fórum.',
	'PST_TIME_UNIT' => 'Unidade de período de pesquisa',
	'PST_CACHE_OFF' => 'Desligado',
	'PST_CACHE_5_MINUTES' => '5 minutos',
	'PST_CACHE_15_MINUTES' => '15 minutos',
	'PST_CACHE_30_MINUTES' => '30 minutos',
	'PST_CACHE_1_HOUR' => '1 hora',
	'PST_CACHE_2_HOURS' => '2 horas',
	'PST_CACHE_4_HOURS' => '4 horas',
	'PST_CACHE_8_HOURS' => '8 horas',
	'PST_CACHE_12_HOURS' => '12 horas',
	'PST_CACHE_24_HOURS' => '24 horas',
	'PST_CACHE_CUSTOM' => 'Personalizado: %d segundos',
	'PST_RESULTS' => 'Molde os resultados',
	'PST_RESULTS_EXPLAIN' => 'Defina quantos tópicos semelhantes aparecem e quão recentes esses tópicos devem ser.',
	'PST_FORUM_RULES' => 'Decida como cada fórum participa',
	'PST_FORUM_RULES_EXPLAIN' => 'Cada fórum possui duas opções simples. Use “Escolher fontes” somente quando um fórum precisar de seu próprio mecanismo de pesquisa.',
	'PST_FORUMS_MANAGED' => 'fóruns',
	'PST_CUSTOM_RULES' => 'configurações de fonte personalizadas',
	'PST_FILTER_FORUMS' => 'Encontre um fórum…',
	'PST_NO_FORUM_MATCH' => 'Nenhum fórum corresponde a essa pesquisa.',
	'PST_SHOW_HERE' => 'Mostrar tópicos semelhantes aqui',
	'PST_SHOW_HERE_EXPLAIN' => 'Os visitantes deste fórum podem ver tópicos semelhantes.',
	'PST_SEARCHABLE' => 'Disponibilize este fórum como fonte',
	'PST_SEARCHABLE_EXPLAIN' => 'Pesquisas padrão podem encontrar tópicos deste fórum.',
	'PST_SEARCH_SOURCES' => 'Onde este fórum pesquisa',
	'PST_SEARCH_SOURCES_EXPLAIN' => 'Use todos os fóruns disponíveis ou escolha um conjunto personalizado.',
	'PST_CHOOSE_SOURCES' => 'Escolha fontes',
	'PST_SOURCE_ALL' => 'Todos os fóruns disponíveis',
	'PST_SOURCE_CUSTOM_COUNT' => array(
		1 => '%d fórum selecionado',
		2 => '%d fóruns selecionados',
	),
	'PST_CHOOSE_FORUM_SOURCES' => 'Escolha de onde vêm tópicos semelhantes',
	'PST_SOURCE_MODAL_EXPLAIN' => 'Esta escolha aplica-se apenas ao fórum mostrado acima. Isso não muda nenhum outro fórum.',
	'PST_SOURCE_ALL_EXPLAIN' => 'Pesquise todos os fóruns cujo botão “disponível como fonte” esteja ativado. Novos fóruns ingressam automaticamente.',
	'PST_SOURCE_CUSTOM' => 'Somente fóruns selecionados',
	'PST_SOURCE_CUSTOM_EXPLAIN' => 'Pesquise uma lista fixa de fóruns. Melhor para seções estreitamente relacionadas.',
	'PST_FILTER_SOURCES' => 'Encontre um fórum de origem…',
	'PST_SELECT_AVAILABLE' => 'Selecione todos disponíveis',
	'PST_CLEAR' => 'Limpar seleção',
	'PST_GLOBALLY_AVAILABLE' => 'Disponível',
	'PST_GLOBALLY_UNAVAILABLE' => 'Não disponível globalmente',
	'PST_CUSTOM_OVERRIDE_NOTE' => 'As opções personalizadas substituem as opções de disponibilidade global. Um fórum selecionado ainda pode ser pesquisado mesmo quando marcado como “Não disponível globalmente”.',
	'PST_SELECT_ONE_SOURCE' => 'Escolha pelo menos um fórum ou use “Todos os fóruns disponíveis”.',
	'PST_APPLY_SOURCES' => 'Use essas fontes',
	'PST_TUNING' => 'Ajuste fino de correspondência e desempenho',
	'PST_TUNING_EXPLAIN' => 'Os padrões funcionam para a maioria das comunidades. Ajuste esses controles somente quando necessário.',
	'PST_READY_TO_SAVE' => 'Pronto para aplicar suas alterações?',
	'PST_SAVE_EXPLAIN' => 'Todas as configurações nesta página são salvas juntas.',
	'PST_SAVE_SETTINGS' => 'Salvar configurações',
));
