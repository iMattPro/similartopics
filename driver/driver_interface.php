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
	 * Check if a field is a FULLTEXT index
	 *
	 * @access public
	 * @param string $column Name of the field
	 * @return bool True if field is a FULLTEXT index, false otherwise
	 */
	public function is_index($column = 'topic_title');

	/**
	 * Make a field into a FULLTEXT index
	 *
	 * @access public
	 * @param string $column Name of the field
	 * @return void
	 */
	public function create_fulltext_index($column = 'topic_title');
}
