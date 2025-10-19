<?php
/**
 *
 * Precise Similar Topics
 *
 * @copyright (c) 2025 Matt Friedman
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace vse\similartopics\core;

use phpbb\extension\manager as ext_manager;
use phpbb\user;

/**
 * A helper class to clean text and remove stop words (localized + additional)
 * for topic titles or other search-related strings.
 */
class stop_word_helper
{
	/** @var ext_manager */
	protected $extension_manager;

	/** @var user */
	protected $user;

	/** @var string */
	protected $php_ext;

	/** @var array|null Lookup table for fast filtering */
	protected $ignore_lookup;

	/** @var bool Whether localized ignore words should be loaded */
	protected $use_localized = false;

	/** @var string Additional ignore words string */
	protected $additional_ignore = '';

	/** @var bool Whether ignore words need to be reloaded */
	protected $needs_reload = true;

	/**
	 * Constructor
	 *
	 * @param ext_manager $extension_manager
	 * @param user $user
	 * @param string $php_ext
	 */
	public function __construct(ext_manager $extension_manager, user $user, $php_ext)
	{
		$this->extension_manager = $extension_manager;
		$this->user = $user;
		$this->php_ext = $php_ext;
	}

	/**
	 * Set additional ignore words
	 *
	 * @param string $words
	 */
	public function set_additional_ignore_words($words)
	{
		if ($this->additional_ignore !== $words)
		{
			$this->additional_ignore = $words;
			$this->needs_reload = true;
		}
	}

	/**
	 * Set whether to use localized ignore words
	 *
	 * @param bool $value
	 */
	public function set_use_localized($value)
	{
		$value = (bool) $value;
		if ($this->use_localized !== $value)
		{
			$this->use_localized = $value;
			$this->needs_reload = true;
		}
	}

	/**
	 * Clean text (strip quotes, ampersands, stop words)
	 *
	 * @param string $text
	 * @return string
	 */
	public function clean_text($text)
	{
		// Strip HTML entities
		$text = str_replace(['&quot;', '&amp;'], '', $text);

		if ($this->use_localized || !empty($this->additional_ignore))
		{
			$this->load_ignore_words();
			$filtered = array_filter(
				$this->make_word_array($text, true),
				[$this, 'filter_ignore_words']
			);
			$text = implode(' ', array_unique($filtered));
		}

		return $text;
	}

	/**
	 * Load ignore words into memory and build lookup table
	 */
	protected function load_ignore_words()
	{
		if ($this->needs_reload || $this->ignore_lookup === null)
		{
			// Load localized ignore words (if needed)
			$words = $this->use_localized ? $this->load_localized_words() : [];

			// Load additional ignore words (if defined)
			if (!empty($this->additional_ignore))
			{
				$words = array_merge($words, $this->make_word_array($this->additional_ignore, false));
			}

			$this->ignore_lookup = array_flip(array_unique($words));
			$this->needs_reload = false;
		}
	}

	/**
	 * Load localized ignore words
	 *
	 * @return array An array of ignore words from the user's language pack
	 */
	protected function load_localized_words()
	{
		$words = [];
		$finder = $this->extension_manager->get_finder();
		$files = $finder
			->set_extensions(['vse/similartopics'])
			->prefix('search_ignore_words')
			->suffix('.' . $this->php_ext)
			->extension_directory("/language/{$this->user->lang_name}")
			->core_path("language/{$this->user->lang_name}/")
			->get_files();

		if (current($files))
		{
			include current($files);
		}

		return $words;
	}

	/**
	 * Split text into word array
	 *
	 * @param string $text A string of text
	 * @param bool $filter_short Whether to filter out words < 3 characters
	 * @return array The original string of text, filtered into an array of individual words
	 */
	protected function make_word_array($text, $filter_short = false)
	{
		$text = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $text));
		$words = array_filter(explode(' ', utf8_strtolower($text)));

		return $filter_short ? array_filter($words, static function($word) {
			return utf8_strlen($word) >= 3;
		}) : $words;
	}

	/**
	 * Filter callback for array_filter to exclude stop words
	 *
	 * @param string $word Word to check
	 * @return bool True to keep word, false to remove it
	 */
	protected function filter_ignore_words($word)
	{
		return !isset($this->ignore_lookup[$word]);
	}
}
