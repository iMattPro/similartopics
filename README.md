# ![Precise Similar Topics](http://mattfriedman.me/forum/images/binoc1.png "Precise Similar Topics") Precise Similar Topics

A Similar Topics Extension for phpBB

This is an extension for phpBB that will find and display a list of similar (related) topics at the bottom of the current topic's page.

[![Build Status](https://travis-ci.org/VSEphpbb/similartopics.svg?branch=master)](https://travis-ci.org/VSEphpbb/similartopics)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VSEphpbb/similartopics/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VSEphpbb/similartopics/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/vse/similartopics/v/stable)](https://www.phpbb.com/customise/db/extension/precise_similar_topics/)

## Features
* Displays a list of the most similar/relevant topics at the bottom of the current topic page.
* Uses a precise and light MySQL query to search topic titles for matches.
* Similar topics are sorted in order of decreasing relevance.
* Adjust how many Similar Topics to display per page.
* Adjust the age-span of topics to display (ie: display similar topics from the past year only).
* Individually enable/disable the display of similar topics in each forum.
* You can exempt certain forums from being searched for similar topics.
* You can define which forums can share similar topics with other forums.
* A query caching option reduces SQL overhead on large boards.
* Permission settings for users and groups determine who can view similar topics.
* Multi-lingual support for stop-words (common words that are ignored).
* Multiple languages are supported. View the pre-installed [localizations](https://github.com/VSEphpbb/similartopics/tree/master/language).
* Prosilver and Subsilver2 styles compatibility.
* Built-in compatibility with my "Topic Preview" extension.
* phpBB 3.1 and 3.2 compatibility.

## Awards
* Overall winner of the 2010 "Summer of MODs" competition at phpBB.com.
* Featured MOD of the Week in the phpBB Weekly Podcast, episode #161.

## Requirements
* phpBB 3.1.0 or higher
* PHP 5.3.3 or higher
* MySQL 4.0.1 or higher using MyISAM tables (InnoDB supported with MySQL 5.6.4 or higher).

## Install
1. [Download the latest validated release](https://www.phpbb.com/customise/db/extension/precise_similar_topics/).
2. Unzip the downloaded release and copy it to the `ext` directory of your phpBB board.
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Look for `Precise Similar Topics` under the Disabled Extensions list and click its `Enable` link.

## Uninstall
1. Navigate in the ACP to `Customise -> Manage extensions`.
2. Click the `Disable` link for Precise Similar Topics.
3. To permanently uninstall, click `Delete Data`, then delete the `similartopics` folder from `phpBB/ext/vse/`.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2013 - Matt Friedman (VSE)
