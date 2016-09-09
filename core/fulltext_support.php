<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2014 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\core;

class fulltext_support
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $engine;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param  \phpbb\db\driver\driver_interface
	 */
	public function __construct(\phpbb\db\driver\driver_interface $db)
	{
		$this->db = $db;
	}

	/**
	 * Check if the database is using MySQL
	 *
	 * @access public
	 * @return bool True if is mysql, false otherwise
	 */
	public function is_mysql()
	{
		return ($this->db->get_sql_layer() === 'mysql4' || $this->db->get_sql_layer() === 'mysqli');
	}

	/**
	 * Check for FULLTEXT index support
	 *
	 * @access public
	 * @return bool True if FULLTEXT is supported, false otherwise
	 */
	public function is_supported()
	{
		// FULLTEXT is supported on InnoDB since MySQL 5.6.4 according to
		// http://dev.mysql.com/doc/refman/5.6/en/innodb-storage-engine.html
		return ($this->get_engine() === 'myisam' || ($this->get_engine() === 'innodb' && phpbb_version_compare($this->db->sql_server_info(true), '5.6.4', '>=')));
	}

	/**
	 * Get the database storage engine name
	 *
	 * @access public
	 * @return string The storage engine name
	 */
	public function get_engine()
	{
		return isset($this->engine) ? $this->engine : $this->set_engine();
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
	 * Check if a field is a FULLTEXT index
	 *
	 * @access public
	 * @param string $field name of a field
	 * @return bool True if field is a FULLTEXT index, false otherwise
	 */
	public function is_index($field = 'topic_title')
	{
		$is_index = false;

		$sql = 'SHOW INDEX
			FROM ' . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Older MySQL versions didn't use Index_type, so fallback to Comment
			$index_type = isset($row['Index_type']) ? $row['Index_type'] : $row['Comment'];

			if ($index_type === 'FULLTEXT' && $row['Key_name'] === $field)
			{
				$is_index = true;
				break;
			}
		}

		$this->db->sql_freeresult($result);

		return $is_index;
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
}
