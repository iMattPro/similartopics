# ![Precise Similar Topics](https://imattpro.github.io/logo/binoc1.png "Precise Similar Topics") Precise Similar Topics

A Similar Topics Extension for phpBB

This is an extension for phpBB that will find and display a list of similar (related) topics at the bottom of the current topic's page.

[![Build Status](https://github.com/iMattPro/similartopics/workflows/Tests/badge.svg)](https://github.com/iMattPro/similartopics/actions)
[![codecov](https://codecov.io/gh/iMattPro/similartopics/branch/master/graph/badge.svg?token=2lqwl0xQrN)](https://codecov.io/gh/iMattPro/similartopics)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/iMattPro/similartopics/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/iMattPro/similartopics/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/vse/similartopics/v/stable)](https://www.phpbb.com/customise/db/extension/precise_similar_topics/)

## Features
* Displays a list of the most similar/relevant topics at the bottom of the current topic page.
* Similar topics are sorted by their relevance.
* Adjust how many Similar Topics to display per page.
* Adjust the age-span of topics to display (i.e.: display similar topics from the past year only).
* Enable or disable similar topics in each forum.
* Exclude specific forums from being searched for similar topics.
* Specify which forums can share similar topics with other forums.
* A query caching option reduces SQL overhead on large boards.
* Permission settings for users and groups determine who can view similar topics (i.e.: don't show similar topics to guests).
* Multilingual support for stop-words (common words that are ignored).
* Multiple languages are supported. View the pre-installed [localizations](https://github.com/iMattPro/similartopics/tree/master/language).
* Compatible with most styles, more added with every release.
* Built-in compatibility with my "Topic Preview" extension.

## Awards
* Overall winner of the 2010 "Summer of MODs" competition at phpBB.com.
* Featured MOD of the Week in the phpBB Weekly Podcast, episode #161.

## Minimum Requirements
* phpBB 3.2.1 up to the most current version (3.3.x).
* PHP 5.4
* MySQL, MariaDB or PostgreSQL database (SQLite, Oracle and MS SQL Server not supported)

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

© 2013 - Matt Friedman
