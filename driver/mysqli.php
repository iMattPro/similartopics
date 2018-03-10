<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2018 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\driver;

class mysqli implements driver_interface
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $engine;

	/**
	 * mysql constructor.
	 *
	 * @param \phpbb\db\driver\driver_interface $db
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db)
	{
		$this->db = $db;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return 'mysqli';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type()
	{
		return 'mysql';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity)
	{
		return array(
			'SELECT'	=> "f.forum_id, f.forum_name, t.*,
				MATCH (t.topic_title) AGAINST ('" . $this->db->sql_escape($topic_title) . "') AS score",

			'FROM'		=> array(
				TOPICS_TABLE	=> 't',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=>	array(FORUMS_TABLE	=> 'f'),
					'ON'	=> 'f.forum_id = t.forum_id',
				),
			),
			'WHERE'		=> "MATCH (t.topic_title) AGAINST ('" . $this->db->sql_escape($topic_title) . "') >= " . (float) $sensitivity . '
				AND t.topic_status <> ' . ITEM_MOVED . '
				AND t.topic_visibility = ' . ITEM_APPROVED . '
				AND t.topic_time > (UNIX_TIMESTAMP() - ' . (int) $length . ')
				AND t.topic_id <> ' . (int) $topic_id,
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_supported()
	{
		// FULLTEXT is supported on InnoDB since MySQL 5.6.4 according to
		// http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
		return $this->is_mysql() && ($this->get_engine() === 'myisam' || ($this->get_engine() === 'innodb' && phpbb_version_compare($this->db->sql_server_info(true), '5.6.4', '>=')));
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_index($column = 'topic_title')
	{
		$is_index = false;

		$sql = 'SHOW INDEX
			FROM ' . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Older MySQL versions didn't use Index_type, so fallback to Comment
			$index_type = isset($row['Index_type']) ? $row['Index_type'] : $row['Comment'];

			if ($index_type === 'FULLTEXT' && $row['Key_name'] === $column)
			{
				$is_index = true;
				break;
			}
		}

		$this->db->sql_freeresult($result);

		return $is_index;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create_fulltext_index($column = 'topic_title')
	{
		if (!$this->is_index($column))
		{
			$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ADD FULLTEXT (' . $column . ')';
			$this->db->sql_query($sql);
		}
	}

	/**
	 * Get the database storage engine name
	 *
	 * @access public
	 * @return string The storage engine name
	 */
	public function get_engine()
	{
		return $this->engine !== null ? $this->engine : $this->set_engine();
	}

	/**
	 * Set the database storage engine name
	 *
	 * @access public
	 * @return string The storage engine name
	 */
	public function set_engine()
	{
		$this->engine = '';

		if ($this->is_mysql())
		{
			$info = $this->get_table_info();

			// Modern MySQL uses 'Engine', but older may still use 'Type'
			foreach (array('Engine', 'Type') as $name)
			{
				if (isset($info[$name]))
				{
					$this->engine = strtolower($info[$name]);
					break;
				}
			}
		}

		return $this->engine;
	}

	/**
	 * Alter the database storage engine
	 *
	 * @access public
	 * @param string $engine The storage engine, i.e.: MYISAM|INNODB
	 * @return void
	 */
	public function alter_engine($engine = 'MYISAM')
	{
		$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' ENGINE = ' . $this->db->sql_escape(strtoupper($engine));
		$this->db->sql_query($sql);
		$this->set_engine();
	}

	/**
	 * Get topics table information
	 *
	 * @access protected
	 * @return mixed Array with the table info, false if the table does not exist
	 */
	protected function get_table_info()
	{
		$result = $this->db->sql_query('SHOW TABLE STATUS LIKE \'' . TOPICS_TABLE . '\'');
		$info = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $info;
	}

	/**
	 * Check if the database is using MySQL
	 *
	 * @access public
	 * @return bool True if is mysql, false otherwise
	 */
	protected function is_mysql()
	{
		return ($this->db->get_sql_layer() === 'mysql4' || $this->db->get_sql_layer() === 'mysqli');
	}
}
