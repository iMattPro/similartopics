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

interface driver_interface
{
	/**
	 * Get the name of the driver
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Get the type of the driver, i.e.: mysql or postgres
	 *
	 * @return string
	 */
	public function get_type();

	/**
	 * Generate the basic SQL query for similar topic searches
	 *
	 * @param int    $topic_id    The ID of the main topic
	 * @param string $topic_title The title of the main topic
	 * @param int    $length      The length of time of the search period
	 * @param float  $sensitivity The search score weighting
	 * @return array An SQL query array
	 */
	public function get_query($topic_id, $topic_title, $length, $sensitivity);

	/**
	 * Check for database support
	 *
	 * @access public
	 * @return bool True if FULLTEXT is supported, false otherwise
	 */
	public function is_supported();

	/**
	 * Check if a column is a FULLTEXT index in topics table
	 *
	 * @access public
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return bool True if column is a FULLTEXT index, false otherwise
	 */
	public function is_fulltext($column = 'topic_title', $table = TOPICS_TABLE);

	/**
	 * Get all FULLTEXT indexes for a column in topics table
	 *
	 * @access public
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return array contains index names
	 */
	public function get_fulltext_indexes($column = 'topic_title', $table = TOPICS_TABLE);

	/**
	 * Make a column into a FULLTEXT index in topics table
	 *
	 * @access public
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return void
	 */
	public function create_fulltext_index($column = 'topic_title', $table = TOPICS_TABLE);

	/**
	 * Get the database storage engine name
	 *
	 * @access public
	 * @return string The storage engine name
	 */
	public function get_engine();
}
