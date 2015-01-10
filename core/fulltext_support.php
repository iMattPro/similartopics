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
	* @param \phpbb\db\driver\driver_interface
	* @access public
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db)
	{
		$this->db = $db;
	}

	/**
	* Check if the database is using MySQL
	*
	* @return bool True if is mysql, false otherwise
	*/
	public function is_mysql()
	{
		return ($this->db->get_sql_layer() == 'mysql4' || $this->db->get_sql_layer() == 'mysqli');
	}

	/**
	* Check for FULLTEXT index support
	*
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
	* @return string The storage engine name
	*/
	public function get_engine()
	{
		return (isset($this->engine)) ? $this->engine : $this->set_engine();
	}

	/**
	* Set the database storage engine name
	*
	* @return string The storage engine name
	*/
	public function set_engine()
	{
		$this->engine = '';

		if ($this->is_mysql())
		{
			$result = $this->db->sql_query('SHOW TABLE STATUS LIKE \'' . TOPICS_TABLE . '\'');
			$info = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if (isset($info['Engine']))
			{
				$this->engine = strtolower($info['Engine']);
			}
			else if (isset($info['Type']))
			{
				$this->engine = strtolower($info['Type']);
			}
		}

		return $this->engine;
	}

	/**
	* Check if a field is a FULLTEXT index
	*
	* @param string $field name of a field
	* @return bool True if field is a FULLTEXT index, false otherwise
	*/
	public function index($field = 'topic_title')
	{
		$sql = 'SHOW INDEX
			FROM ' . TOPICS_TABLE;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// deal with older MySQL versions which didn't use Index_type
			$index_type = (isset($row['Index_type'])) ? $row['Index_type'] : $row['Comment'];

			if ($index_type == 'FULLTEXT' && $row['Key_name'] == $field)
			{
				return true;
			}
		}

		$this->db->sql_freeresult($result);

		return false;
	}
}
