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
	/** Config key containing exact index identifier owned by this extension */
	public const OWNED_INDEX_CONFIG = 'pst_owned_index';

	/**
	 * Get the name of the driver
	 *
	 * @return string
	 */
	public function get_name(): string;

	/**
	 * Get the type of the driver, i.e.: mysql or postgres
	 *
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Generate the basic SQL query for similar topic searches
	 *
	 * @param int $topic_id    The ID of the main topic
	 * @param string $topic_title The title of the main topic
	 * @param int $length      The length of time of the search period
	 * @param float $sensitivity The search score weighting
	 * @return array An SQL query array
	 */
	public function get_query(int $topic_id, string $topic_title, int $length, float $sensitivity): array;

	/**
	 * Generate the SQL query for live AJAX similar-topic suggestions
	 *
	 * @param int $topic_id    The ID of the main topic
	 * @param string $topic_title The title of the main topic
	 * @param int $length      The length of time of the search period
	 * @param float $sensitivity The search score weighting
	 * @return array An SQL query array
	 */
	public function get_ajax_query(int $topic_id, string $topic_title, int $length, float $sensitivity): array;

	/**
	 * Check for database support
	 *
	 * @access public
	 * @return bool True if FULLTEXT is supported, false otherwise
	 */
	public function is_supported(): bool;

	/**
	 * Check if a column is a FULLTEXT index in topics table
	 *
	 * @access public
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return bool True if column is a FULLTEXT index, false otherwise
	 */
	public function is_fulltext(string $column = 'topic_title', string $table = TOPICS_TABLE): bool;

	/**
	 * Get all FULLTEXT indexes for a column in topics table
	 *
	 * @access public
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return array contains index names
	 */
	public function get_fulltext_indexes(string $column = 'topic_title', string $table = TOPICS_TABLE): array;

	/**
	 * Create a FULLTEXT index when missing.
	 * Implementations with config record only an index created by this call as owned.
	 *
	 * @access public
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return void
	 */
	public function create_fulltext_index(string $column = 'topic_title', string $table = TOPICS_TABLE): void;

	/**
	 * Drop only the exact index recorded as owned.
	 *
	 * @param string $column Name of the column
	 * @param string $table  Name of the table
	 * @return void
	 */
	public function drop_owned_fulltext_index(string $column = 'topic_title', string $table = TOPICS_TABLE): void;

	/**
	 * Get the database storage engine name
	 *
	 * @access public
	 * @return string The storage engine name
	 */
	public function get_engine(): string;

	/**
	 * Check if the database has built-in stop-word support
	 *
	 * @access public
	 * @return bool True if has stop-word support, false otherwise
	 */
	public function has_stopword_support(): bool;
}
