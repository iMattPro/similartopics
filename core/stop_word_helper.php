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

	/** @var bool Dirty flag to track if reload is needed */
	protected $dirty = true;

	/**
	 * Constructor
	 *
	 * @param ext_manager $extension_manager
	 * @param user $user
	 * @param string $php_ext
	 * @param bool $use_localized
	 * @param string $additional_ignore
	 */
	public function __construct(ext_manager $extension_manager, user $user, $php_ext, $use_localized = false, $additional_ignore = '')
	{
		$this->extension_manager = $extension_manager;
		$this->user = $user;
		$this->php_ext = $php_ext;
		$this->use_localized = $use_localized;
		$this->additional_ignore = $additional_ignore;
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
			$this->dirty = true;
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
			$this->dirty = true;
		}
	}

	/**
	 * Clean topic title (strip quotes, ampersands, stop words)
	 *
	 * @param string $text
	 * @return string
	 */
	public function clean_text($text)
	{
		// Strip HTML entities
		$text = str_replace(array('&quot;', '&amp;'), '', $text);

		if ($this->use_localized || !empty($this->additional_ignore))
		{
			$this->load_ignore_words();
			$filtered = array_filter(
				$this->make_word_array($text),
				array($this, 'filter_ignore_words')
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
		if ($this->dirty || $this->ignore_lookup === null)
		{
			$words = array();

			// Load localized ignore words (if needed)
			if ($this->use_localized)
			{
				$localized = $this->load_localized_words();
				if (is_array($localized))
				{
					$words = array_merge($words, $localized);
				}
			}

			// Load additional ignore words (if defined)
			if (!empty($this->additional_ignore))
			{
				$words = array_merge($words, $this->make_word_array($this->additional_ignore));
			}

			$this->ignore_lookup = array_flip(array_unique($words));
			$this->dirty = false;
		}
	}

	/**
	 * Load localized ignore words
	 *
	 * @return array
	 */
	protected function load_localized_words()
	{
		$words = array();
		$finder = $this->extension_manager->get_finder();
		$files = $finder
			->set_extensions(array('vse/similartopics'))
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
	 * @param string $text
	 * @return array
	 */
	protected function make_word_array($text)
	{
		$text = trim(preg_replace('#[^\p{L}\p{N}]+#u', ' ', $text));
		$words = explode(' ', utf8_strtolower($text));

		foreach ($words as $key => $word)
		{
			// Strip words of 2 characters or fewer
			if (utf8_strlen(trim($word)) < 3)
			{
				unset($words[$key]);
			}
		}

		return array_values($words);
	}

	/**
	 * Filter callback
	 *
	 * @param string $word
	 * @return bool
	 */
	protected function filter_ignore_words($word)
	{
		return !isset($this->ignore_lookup[$word]);
	}
}
