<?php
/*
 * This file is part of myCloud.
 *
 * (c) 2018 Matt Friedman
 *
 * This work is licensed under a Creative Commons
 * Attribution-NonCommercial-NoDerivs 3.0 Unported License.
 * http://creativecommons.org/licenses/by-nc-nd/3.0/
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
	 * @param string $field name of a field
	 * @return bool True if field is a FULLTEXT index, false otherwise
	 */
	public function is_index($field = 'topic_title');


	/**
	 * Make a field into a FULLTEXT index
	 *
	 * @access public
	 * @param string $field name of a field
	 * @return void
	 */
	public function create_fulltext_index($field = 'topic_title');
}
