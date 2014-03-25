# ![PST II](http://mattfriedman.me/forum/images/binoc1.png "PST II") Precise Similar Topics II

A Similar Topics Extension for phpBB 3.1

This is an extension for phpBB 3.1 that will find and display a list of similar (related) topics at the bottom of the current topic's page.

[![Build Status](https://travis-ci.org/VSEphpbb/similartopics.png?branch=extension)](https://travis-ci.org/VSEphpbb/similartopics)

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
* Attractive Prosilver and Subsilver2 integration.
* Built-in support for integration with the "Topic Preview" extension

### Languages supported:
* English
* Dutch
* French
* German
* Polish
* Portuguese
* Romanian
* Serbian
* Spanish
* Swedish

## Awards
* Overall winner of the 2010 "Summer of MODs" competition at phpBB.com.
* Featured MOD of the Week in the phpBB Weekly Podcast, episode #161.

## Requirements
* phpBB 3.1-dev or higher
* PHP 5.3.3 or higher
* MySQL 4.0.1 or higher using MyISAM tables (InnoDB supported with MySQL 5.6.4 or higher).

## Installation
You can install this on the latest copy of the develop branch ([phpBB 3.1-dev](https://github.com/phpbb/phpbb3)) by following the steps below:

**Manual:**

1. Copy the entire contents of this repo to to `phpBB/ext/vse/similartopics/`
2. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
3. Click `Enable`.

**Git CLI:**

1. From the board root run the following git command:
`git clone -b extension https://github.com/VSEphpbb/similartopics.git phpBB/ext/vse/similartopics`
2. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
3. Click `Enable`.

Note: This extension is in development. Installation is only recommended for testing purposes and is not supported on live boards. This extension will be officially released following phpBB 3.1.0.

## Uninstallation
Navigate in the ACP to `Customise -> Extension Management -> Extensions` and click `Disable`.

To permanently uninstall, click `Delete Data` and then you can safely delete the `/ext/vse/similartopics` folder.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

© 2013 - Matt Friedman (VSE)
