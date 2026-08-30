<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2013 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\acp\controller;

use phpbb\cache\driver\driver_interface as cache;
use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\db\driver\driver_interface as dbal;
use phpbb\extension\manager as ext_manager;
use phpbb\language\language;
use phpbb\log\log;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use vse\similartopics\driver\manager;
use vse\similartopics\driver\driver_interface as similartopics;

class similar_topics_admin
{
	/** @var cache */
	protected $cache;

	/** @var config */
	protected $config;

	/** @var db_text */
	protected $config_text;

	/** @var dbal */
	protected $db;

	/** @var ext_manager */
	protected $ext_manager;

	/** @var language */
	protected $language;

	/** @var log */
	protected $log;

	/** @var request */
	protected $request;

	/** @var similartopics */
	protected $similartopics;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var array */
	protected $times;

	/** @var string */
	protected $form_key;

	/** @var string */
	public $u_action;

	/**
	 * Admin controller constructor
	 *
	 * @access public
	 * @param cache    $cache
	 * @param config   $config
	 * @param db_text  $config_text
	 * @param dbal     $db
	 * @param ext_manager $extension_manager
	 * @param manager  $similartopics
	 * @param language $language
	 * @param log      $log
	 * @param request  $request
	 * @param template $template
	 * @param user     $user
	 * @param string   $root_path
	 * @param string   $php_ext
	 */
	public function __construct(cache $cache, config $config, db_text $config_text, dbal $db, ext_manager $extension_manager, manager $similartopics, language $language, log $log, request $request, template $template, user $user, $root_path, $php_ext)
	{
		$this->cache         = $cache;
		$this->config        = $config;
		$this->config_text   = $config_text;
		$this->db            = $db;
		$this->ext_manager   = $extension_manager;
		$this->similartopics = $similartopics->get_driver($this->db->get_sql_layer());
		$this->language      = $language;
		$this->log           = $log;
		$this->request       = $request;
		$this->template      = $template;
		$this->user          = $user;
		$this->root_path     = $root_path;
		$this->php_ext       = $php_ext;
		$this->form_key      = 'acp_similar_topics';
		$this->times         = [
			'd' => 86400, // one day
			'w' => 604800, // one week
			'm' => 2626560, // one month
			'y' => 31536000, // one year
		];
	}

	/**
	 * Set the u_action variable from the form/module
	 *
	 * @access public
	 * @param string $u_action
	 *
	 * @return similar_topics_admin $this
	 */
	public function set_u_action($u_action)
	{
		$this->u_action = $u_action;
		return $this;
	}

	/**
	 * Controller handler. Call this method from the ACP module.
	 *
	 * @access public
	 */
	public function handle()
	{
		$this->language->add_lang('acp_similar_topics', 'vse/similartopics');

		add_form_key($this->form_key);

		$this->default_settings();
	}

	/**
	 * Display/Save default settings
	 *
	 * @access protected
	 */
	protected function default_settings()
	{
		$forum_list = $this->get_forum_list();

		if ($this->request->is_set_post('submit'))
		{
			$this->check_form_key($this->form_key);

			$forum_ids = array();
			foreach ($forum_list as $forum)
			{
				$forum_ids[] = (int) $forum['forum_id'];
			}
			// request::variable() HTML-escapes JSON quotes. Decode only that escaping
			// before validation so unfiltered raw request data never reaches a query.
			$forum_rules_payload = htmlspecialchars_decode($this->request->variable(
				'forum_rules',
				'',
				false,
				\phpbb\request\request_interface::POST
			), ENT_COMPAT);
			$forum_rules = $this->parse_forum_rules($forum_rules_payload, $forum_ids);

			// Set basic config settings
			$this->config->set('similar_topics', $this->request->variable('pst_enable', 0));
			$this->config->set('similar_topics_dynamic', $this->request->variable('pst_dynamic', 0));
			$this->config->set('similar_topics_limit', abs($this->request->variable('pst_limit', 0))); // use abs for positive values only
			$this->config->set('similar_topics_cache', abs($this->request->variable('pst_cache', 0))); // use abs for positive values only
			$this->config_text_set('similar_topics_words', $this->request->variable('pst_words', '', true));

			// Set sensitivity
			$pst_sense = min(abs($this->request->variable('pst_sense', 5)), 10); // use abs for positive values only
			$this->config->set('similar_topics_sense', $pst_sense);

			// Set date/time config settings
			$pst_time = abs($this->request->variable('pst_time', 0)); // use abs for positive values only
			$pst_time_type = $this->request->variable('pst_time_type', '');
			$this->config->set('similar_topics_type', $pst_time_type);
			$this->config->set('similar_topics_time', $this->set_pst_time($pst_time, $pst_time_type));

			// Forum controls use positive language in the UI. Convert unchecked
			// forums to the inverse values stored by the existing database schema.
			$this->update_forum('similar_topics_hide', array_diff($forum_ids, $forum_rules['shown']));
			$this->update_forum('similar_topics_ignore', array_diff($forum_ids, $forum_rules['searchable']));

			// Save per-forum source overrides from the inline source picker.
			$this->update_forum_sources($forum_ids, $forum_rules['modes'], $forum_rules['sources']);

			// Set PostgreSQL TS Name
			if ($this->similartopics && $this->similartopics->get_type() === 'postgres')
			{
				$ts_name = $this->request->variable('pst_postgres_ts_name', ($this->config['pst_postgres_ts_name'] ?: 'simple'));
				$this->config->set('pst_postgres_ts_name', $ts_name);
				$this->similartopics->create_fulltext_index('topic_title');
			}

			$this->cache->destroy('sql', TOPICS_TABLE);
			$this->cache->destroy('sql', FORUMS_TABLE);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_MSG');

			$this->end('PST_SAVED');
		}

		// Build the time options select menu
		$time_options = array(
			'd' => 'PST_DAYS',
			'w' => 'PST_WEEKS',
			'm' => 'PST_MONTHS',
			'y' => 'PST_YEARS',
		);
		foreach ($time_options as $value => $label)
		{
			$this->template->assign_block_vars('similar_time_options', array(
				'VALUE'      => $value,
				'LABEL'      => $label,
				'S_SELECTED' => $value === $this->config['similar_topics_type'],
			));
		}

		// Build stepped cache duration options. Keep seconds in the submitted
		// value so existing storage and custom values remain compatible.
		$cache_durations = array(
			0     => 'PST_CACHE_OFF',
			300   => 'PST_CACHE_5_MINUTES',
			900   => 'PST_CACHE_15_MINUTES',
			1800  => 'PST_CACHE_30_MINUTES',
			3600  => 'PST_CACHE_1_HOUR',
			7200  => 'PST_CACHE_2_HOURS',
			14400 => 'PST_CACHE_4_HOURS',
			28800 => 'PST_CACHE_8_HOURS',
			43200 => 'PST_CACHE_12_HOURS',
			86400 => 'PST_CACHE_24_HOURS',
		);
		$cache_value = (int) $this->isset_or_default($this->config['similar_topics_cache'], 0);
		$cache_step = 0;
		$cache_distance = null;
		$cache_label = $this->language->lang('PST_CACHE_CUSTOM', $cache_value);
		$step = 0;
		foreach ($cache_durations as $seconds => $label)
		{
			$distance = abs($cache_value - $seconds);
			if ($cache_distance === null || $distance < $cache_distance)
			{
				$cache_step = $step;
				$cache_distance = $distance;
			}
			if ($cache_value === $seconds)
			{
				$cache_label = $this->language->lang($label);
			}

			$this->template->assign_block_vars('cache_duration_options', array(
				'INDEX'   => $step,
				'SECONDS' => $seconds,
				'LABEL'   => $this->language->lang($label),
			));
			$step++;
		}

		$this->template->assign_vars(array(
			'PST_VERSION'     => $this->get_extension_version(),
			'S_PST_ENABLE'    => $this->isset_or_default($this->config['similar_topics'], false),
			'S_PST_DYNAMIC'   => $this->isset_or_default($this->config['similar_topics_dynamic'], false),
			'PST_LIMIT'       => $this->isset_or_default($this->config['similar_topics_limit'], ''),
			'PST_CACHE'       => $this->isset_or_default($this->config['similar_topics_cache'], ''),
			'PST_CACHE_STEP'  => $cache_step,
			'PST_CACHE_LABEL' => $cache_label,
			'PST_SENSE'       => $this->isset_or_default($this->config['similar_topics_sense'], ''),
			'PST_WORDS'       => $this->isset_or_default($this->config_text_get('similar_topics_words'), ''),
			'PST_TIME'        => $this->get_pst_time($this->config['similar_topics_time'], $this->config['similar_topics_type']),
			'PST_SENSITIVITY' => $this->similartopics && $this->similartopics->get_engine() === 'innodb' ? 1 : 5,
			'S_PST_NO_COMPAT' => $this->similartopics === null || !$this->similartopics->is_fulltext('topic_title'),
			'U_ACTION'        => $this->u_action,
		));

		// If PostgreSQL, we need to make an options list of text search names
		if ($this->similartopics instanceof \vse\similartopics\driver\postgres)
		{
			$this->language->add_lang('acp/search');
			foreach ($this->similartopics->get_cfg_name_list() as $row)
			{
				$this->template->assign_block_vars('postgres_ts_names', array(
					'NAME'       => $row['ts_name'],
					'S_SELECTED' => $row['ts_name'] === $this->config['pst_postgres_ts_name'],
				));
			}
		}

		$custom_forum_count = 0;
		$forum_rules = array();
		$valid_forum_ids = array();
		foreach ($forum_list as $forum)
		{
			$valid_forum_ids[] = (int) $forum['forum_id'];
		}
		for ($source_count = 1, $forum_count = count($forum_list); $source_count <= $forum_count; $source_count++)
		{
			$this->template->assign_block_vars('source_count_labels', array(
				'COUNT' => $source_count,
				'LABEL' => $this->language->lang('PST_SOURCE_CUSTOM_COUNT', $source_count),
			));
		}

		foreach ($forum_list as $row)
		{
			$selected_forums = json_decode($row['similar_topic_forums'], true);
			$selected_forums = is_array($selected_forums) ? array_map('intval', $selected_forums) : array();
			$selected_forums = array_values(array_unique(array_intersect($selected_forums, $valid_forum_ids)));
			$selected_count = count($selected_forums);
			$custom_forum_count += $selected_count > 0 ? 1 : 0;
			$forum_rules[(int) $row['forum_id']] = array(
				'show'       => (int) !$row['similar_topics_hide'],
				'searchable' => (int) !$row['similar_topics_ignore'],
				'mode'       => $selected_count > 0 ? 'custom' : 'all',
				'sources'    => $selected_forums,
			);

			$this->template->assign_block_vars('forums', array(
				'FORUM_NAME'           => $row['forum_name'],
				'FORUM_ID'             => $row['forum_id'],
				'S_SHOW_FORUM'         => !$row['similar_topics_hide'],
				'S_SEARCHABLE_FORUM'   => !$row['similar_topics_ignore'],
				'S_CUSTOM_SOURCES'     => $selected_count > 0,
				'SOURCE_FORUMS'        => implode(',', $selected_forums),
				'SOURCE_COUNT'         => $selected_count,
				'SOURCE_SUMMARY'       => $selected_count > 0 ? $this->language->lang('PST_SOURCE_CUSTOM_COUNT', $selected_count) : $this->language->lang('PST_SOURCE_ALL'),
				'U_FORUM'              => append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $row['forum_id']),
			));

			$this->template->assign_block_vars('source_forums', array(
				'FORUM_NAME'   => $row['forum_name'],
				'FORUM_ID'     => $row['forum_id'],
				'S_SEARCHABLE' => !$row['similar_topics_ignore'],
			));
		}

		$this->template->assign_vars(array(
			'PST_FORUM_COUNT'        => count($forum_list),
			'PST_CUSTOM_FORUM_COUNT' => $custom_forum_count,
			'PST_FORUM_RULES'        => json_encode($forum_rules),
		));
	}

	/**
	 * Decode and validate compact per-forum rules before any settings are saved.
	 *
	 * @param string $payload   JSON forum rules keyed by forum ID
	 * @param array  $forum_ids Valid posting forum IDs
	 * @return array Normalized rule collections
	 */
	protected function parse_forum_rules($payload, array $forum_ids)
	{
		$rules = json_decode($payload, true);
		$normalized = array(
			'shown'      => array(),
			'searchable' => array(),
			'modes'      => array(),
			'sources'    => array(),
		);

		if (!is_array($rules) || count($rules) !== count($forum_ids))
		{
			$this->end('FORM_INVALID', E_USER_WARNING);
		}

		foreach ($rules as $forum_id => $rule)
		{
			if ((string) (int) $forum_id !== (string) $forum_id)
			{
				$this->end('FORM_INVALID', E_USER_WARNING);
			}
			$forum_id = (int) $forum_id;
			$valid_shape = false;
			if (is_array($rule))
			{
				$rule_keys = array_keys($rule);
				sort($rule_keys);
				$valid_shape = $rule_keys === array('mode', 'searchable', 'show', 'sources');
			}
			if (!in_array($forum_id, $forum_ids, true) || !$valid_shape
				|| !in_array($rule['show'], array(0, 1), true)
				|| !in_array($rule['searchable'], array(0, 1), true)
				|| !in_array($rule['mode'], array('all', 'custom'), true)
				|| !is_array($rule['sources']))
			{
				$this->end('FORM_INVALID', E_USER_WARNING);
			}

			$sources = array();
			foreach ($rule['sources'] as $source_id)
			{
				if (!is_int($source_id) || !in_array($source_id, $forum_ids, true) || in_array($source_id, $sources, true))
				{
					$this->end('FORM_INVALID', E_USER_WARNING);
				}
				$sources[] = $source_id;
			}
			if (($rule['mode'] === 'custom') !== !empty($sources))
			{
				$this->end('FORM_INVALID', E_USER_WARNING);
			}

			if ($rule['show'])
			{
				$normalized['shown'][] = $forum_id;
			}
			if ($rule['searchable'])
			{
				$normalized['searchable'][] = $forum_id;
			}
			$normalized['modes'][$forum_id] = $rule['mode'];
			$normalized['sources'][$forum_id] = implode(',', $sources);
		}

		if (count($normalized['modes']) !== count($forum_ids))
		{
			$this->end('FORM_INVALID', E_USER_WARNING);
		}

		return $normalized;
	}

	/**
	 * Get extension version from composer metadata.
	 *
	 * @return string
	 */
	protected function get_extension_version()
	{
		try
		{
			$metadata_manager = $this->ext_manager->create_extension_metadata_manager('vse/similartopics');
			return $metadata_manager->get_metadata('version');
		}
		catch (\phpbb\extension\exception $e)
		{
			return '';
		}
	}

	/**
	 * Check form key, trigger error if invalid
	 *
	 * @access protected
	 * @param string $form_key The form key value
	 */
	protected function check_form_key($form_key)
	{
		if (!check_form_key($form_key))
		{
			$this->end('FORM_INVALID', E_USER_WARNING);
		}
	}

	/**
	 * Get forums list
	 *
	 * @access protected
	 * @return array forum data rows
	 */
	protected function get_forum_list()
	{
		$sql = 'SELECT forum_id, forum_name, similar_topic_forums, similar_topics_hide, similar_topics_ignore
			FROM ' . FORUMS_TABLE . '
			WHERE forum_type = ' . FORUM_POST . '
			ORDER BY left_id ASC';
		$result = $this->db->sql_query($sql);
		$forum_list = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $forum_list;
	}

	/**
	 * Update the similar topics columns in the forums table
	 *
	 * @access protected
	 * @param string $column    The name of the column to update
	 * @param array  $forum_ids An array of forum_ids
	 */
	protected function update_forum($column, $forum_ids)
	{
		$this->db->sql_transaction('begin');

		// Set marked forums (in set) to 1
		$sql = 'UPDATE ' . FORUMS_TABLE . "
			SET $column = 1
			WHERE " . $this->db->sql_in_set('forum_id', $forum_ids, false, true);
		$this->db->sql_query($sql);

		// Set unmarked forums (not in set) to 0
		$sql = 'UPDATE ' . FORUMS_TABLE . "
			SET $column = 0
			WHERE " . $this->db->sql_in_set('forum_id', $forum_ids, true, true);
		$this->db->sql_query($sql);

		$this->db->sql_transaction('commit');
	}

	/**
	 * Update source overrides for every posting forum.
	 *
	 * Empty overrides mean "all globally searchable forums". Custom source IDs
	 * are restricted to known posting forums before being stored.
	 *
	 * @param array $forum_ids     Valid posting forum IDs
	 * @param array $source_modes  Per-forum all/custom modes
	 * @param array $source_forums Per-forum comma-separated source IDs
	 */
	protected function update_forum_sources(array $forum_ids, array $source_modes, array $source_forums)
	{
		$this->db->sql_transaction('begin');

		foreach ($forum_ids as $forum_id)
		{
			$selected = array();
			if (isset($source_modes[$forum_id]) && $source_modes[$forum_id] === 'custom' && !empty($source_forums[$forum_id]))
			{
				$selected = array_filter(array_map('intval', explode(',', $source_forums[$forum_id])));
				$selected = array_values(array_unique(array_intersect($selected, $forum_ids)));
			}

			$value = !empty($selected) ? json_encode($selected) : '';
			$sql = 'UPDATE ' . FORUMS_TABLE . "
				SET similar_topic_forums = '" . $this->db->sql_escape($value) . "'
				WHERE forum_id = " . (int) $forum_id;
			$this->db->sql_query($sql);
		}

		$this->db->sql_transaction('commit');
	}

	/**
	 * Store a config_text item in the database.
	 *
	 * @access protected
	 * @param string $name  Name of a config_text item
	 * @param string $value Value of a config_text item
	 */
	protected function config_text_set($name, $value)
	{
		$this->config_text->set($name, $value);
		$this->cache->put("_$name", $value);
	}

	/**
	 * Get a config_text value from the cache if it is cached, otherwise
	 * get it directly from the database.
	 *
	 * @access protected
	 * @param string $name Name of a config_text item
	 * @return string|null Value of a config_text item, either cached or from db
	 */
	protected function config_text_get($name)
	{
		if (($value = $this->cache->get("_$name")) === false)
		{
			$value = $this->config_text->get($name);

			$this->cache->put("_$name", $value);
		}

		return !empty($value) ? $value : null;
	}

	/**
	 * Calculate the time in seconds based on requested time period length
	 *
	 * @access protected
	 * @param int    $length user entered value
	 * @param string $type   years, months, weeks, days (y|m|w|d)
	 * @return int time in seconds
	 */
	protected function set_pst_time($length, $type = 'y')
	{
		$type = isset($this->times[$type]) ? $type : 'y';

		return (int) ($length * $this->times[$type]);
	}

	/**
	 * Get the correct time period length value for the form
	 *
	 * @access protected
	 * @param int    $time as a timestamp
	 * @param string $type years, months, weeks, days (y|m|w|d)
	 * @return int time converted to the given $type
	 */
	protected function get_pst_time($time, $type = '')
	{
		return isset($this->times[$type]) ? (int) round($time / $this->times[$type]) : 0;
	}

	/**
	 * Return a variable if it is set, otherwise default
	 *
	 * @access protected
	 * @param mixed $var     The variable to test
	 * @param mixed $default The default value to use
	 * @return mixed The value of the variable if set, otherwise default value
	 */
	protected function isset_or_default($var, $default)
	{
		return null !== $var ? $var : $default;
	}

	/**
	 * End script execution with a trigger_error message
	 *
	 * @access protected
	 * @param string $message Language key string
	 * @param int    $code    E_USER_NOTICE|E_USER_WARNING
	 * @return void
	 */
	protected function end($message, $code = E_USER_NOTICE)
	{
		trigger_error($this->language->lang($message) . adm_back_link($this->u_action), $code);
	}
}
