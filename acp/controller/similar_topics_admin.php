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
	 * @param manager  $similartopics
	 * @param language $language
	 * @param log      $log
	 * @param request  $request
	 * @param template $template
	 * @param user     $user
	 * @param string   $root_path
	 * @param string   $php_ext
	 */
	public function __construct(cache $cache, config $config, db_text $config_text, dbal $db, manager $similartopics, language $language, log $log, request $request, template $template, user $user, $root_path, $php_ext)
	{
		$this->cache         = $cache;
		$this->config        = $config;
		$this->config_text   = $config_text;
		$this->db            = $db;
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

		if ($this->request->variable('action', '') === 'advanced')
		{
			$this->advanced_settings();
		}
		else
		{
			$this->default_settings();
		}
	}

	/**
	 * Display/Save default settings
	 *
	 * @access protected
	 */
	protected function default_settings()
	{
		if ($this->request->is_set_post('submit'))
		{
			$this->check_form_key($this->form_key);

			// Set basic config settings
			$this->config->set('similar_topics', $this->request->variable('pst_enable', 0));
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

			// Set checkbox array form data
			$this->update_forum('similar_topics_hide', $this->request->variable('mark_noshow_forum', array(0), true));
			$this->update_forum('similar_topics_ignore', $this->request->variable('mark_ignore_forum', array(0), true));

			// Set PostgreSQL TS Name
			if ($this->similartopics && $this->similartopics->get_type() === 'postgres')
			{
				$ts_name = $this->request->variable('pst_postgres_ts_name', ($this->config['pst_postgres_ts_name'] ?: 'simple'));
				$this->config->set('pst_postgres_ts_name', $ts_name);
				$this->similartopics->create_fulltext_index('topic_title');
			}

			$this->cache->destroy('sql', TOPICS_TABLE);

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

		$this->template->assign_vars(array(
			'S_PST_ENABLE'    => $this->isset_or_default($this->config['similar_topics'], false),
			'PST_LIMIT'       => $this->isset_or_default($this->config['similar_topics_limit'], ''),
			'PST_CACHE'       => $this->isset_or_default($this->config['similar_topics_cache'], ''),
			'PST_SENSE'       => $this->isset_or_default($this->config['similar_topics_sense'], ''),
			'PST_WORDS'       => $this->isset_or_default($this->config_text_get('similar_topics_words'), ''),
			'PST_TIME'        => $this->get_pst_time($this->config['similar_topics_time'], $this->config['similar_topics_type']),
			'S_PST_NO_COMPAT' => $this->similartopics === null || !$this->similartopics->is_fulltext('topic_title'),
			'U_ACTION'        => $this->u_action,
		));

		// If postgresql, we need to make an options list of text search names
		if ($this->similartopics && $this->similartopics->get_type() === 'postgres')
		{
			$this->language->add_lang('acp/search');
			foreach ($this->get_cfgname_list() as $row)
			{
				$this->template->assign_block_vars('postgres_ts_names', array(
					'NAME'       => $row['ts_name'],
					'S_SELECTED' => $row['ts_name'] === $this->config['pst_postgres_ts_name'],
				));
			}
		}

		$forum_list = $this->get_forum_list();
		foreach ($forum_list as $row)
		{
			$this->template->assign_block_vars('forums', array(
				'FORUM_NAME'           => $row['forum_name'],
				'FORUM_ID'             => $row['forum_id'],
				'CHECKED_IGNORE_FORUM' => $row['similar_topics_ignore'] ? 'checked="checked"' : '',
				'CHECKED_NOSHOW_FORUM' => $row['similar_topics_hide'] ? 'checked="checked"' : '',
				'S_IS_ADVANCED'        => (bool) $row['similar_topic_forums'],
				'U_ADVANCED'           => "$this->u_action&amp;action=advanced&amp;f=" . $row['forum_id'],
				'U_FORUM'              => append_sid("{$this->root_path}viewforum.$this->php_ext", 'f=' . $row['forum_id']),
			));
		}
	}

	/**
	 * Display/Save advanced settings
	 *
	 * @access protected
	 */
	protected function advanced_settings()
	{
		$forum_id = $this->request->variable('f', 0);

		if ($this->request->is_set_post('submit'))
		{
			$this->check_form_key($this->form_key);

			$similar_topic_forums = $this->request->variable('similar_forums_id', array(0));
			$similar_topic_forums = !empty($similar_topic_forums) ? json_encode($similar_topic_forums) : '';

			$sql = 'UPDATE ' . FORUMS_TABLE . "
				SET similar_topic_forums = '" . $this->db->sql_escape($similar_topic_forums) . "'
				WHERE forum_id = $forum_id";
			$this->db->sql_query($sql);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'PST_LOG_MSG');

			$this->end('PST_SAVED');
		}

		$forum_name = '';
		$selected = array();
		if ($forum_id > 0)
		{
			$sql = 'SELECT forum_name, similar_topic_forums
				FROM ' . FORUMS_TABLE . "
				WHERE forum_id = $forum_id";
			$result = $this->db->sql_query($sql);
			while ($fid = $this->db->sql_fetchrow($result))
			{
				$selected = json_decode($fid['similar_topic_forums'], true);
				$forum_name = $fid['forum_name'];
			}
			$this->db->sql_freeresult($result);
		}

		$this->template->assign_vars(array(
			'S_ADVANCED_SETTINGS'    => true,
			'SIMILAR_FORUMS_OPTIONS' => make_forum_select($selected, false, false, true),
			'PST_FORUM_NAME'         => $forum_name,
			'U_ACTION'               => $this->u_action . '&amp;action=advanced&amp;f=' . $forum_id,
			'U_BACK'                 => $this->u_action,
		));
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
	 * Get list of PostgreSQL text search names
	 *
	 * @access protected
	 * @return array array of text search names
	 */
	protected function get_cfgname_list()
	{
		$sql = 'SELECT cfgname AS ts_name FROM pg_ts_config';
		$result = $this->db->sql_query($sql);
		$ts_options = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		return $ts_options;
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
