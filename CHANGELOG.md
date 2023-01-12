## Changelog

### 1.6.0 - 2022-12-17

- Removed support for phpBB 3.1.x.
- Removed forum "f=" parameters from URLs.
- Optimized Javascript and other coding updates.
- Support added for ScaffoldBB style.

### 1.5.4 - 2020-12-01

- Fix for Postgresql databases where titles with single quotes would fatally error.
- Enabled AJAX interactions when saving settings in the ACP.
- Re-wrote the search period explanation in the ACP settings.
- Switched to using phpBB finder to locate foreign language ignore words files.

### 1.5.3 - 2020-01-10

- Compatibility issues with Prosilver styles addressed.
- Installation errors on phpBB 3.3.x will be reported to the user.

### 1.5.2 - 2019-06-26

- Defining your own ignore-words is no longer limited to 255 characters. The new limit is over 16 million characters.
- Languages added:
    - Hebrew

### 1.5.1 - 2018-06-01

- Fixed an issue where PHP using European decimal notation (comma instead of period) could cause an SQL error.
- Minor code fixes based on phpBB Extension team validation report.

### 1.5.0 - 2018-03-14

- Added support for forums using PostgreSQL databases (with thanks to hubaishan).
- Fixed a layout bug with the pagination buttons introduced in phpBB 3.2.2.
- Fixed potential SQL error bug when installing.

### 1.4.3 - 2018-01-03

- Added a new setting: Search Sensitivity. Allows users to adjust the weighting of similar topics matches. This was added because InnoDB which is now supported, weights results differently than MyISAM. This will allow users with InnoDB tables to improve their similar topics results by lowering the weighting/sensitivity.
- Converted all template syntax to Twig format.

### 1.4.2 - 2017-03-18

- Fix support for displaying topic icons
- Added a new core event to allow extensions to modify the rowset data for similar topics
- Styles support added:
    - we_clearblue style (3.2.x)
    - we_universal style (3.2.x)

### 1.4.1 - 2017-01-04

- Fixed a bug introduced in 1.4.0 that caused fatal errors when creating/updating a forum
- Integrated foreign language ignore-words directly into the extension since they are being removed from the phpBB core translations

### 1.4.0 - 2016-11-16

- Support for larger forums (no more "Too many forums selected" error in PST settings)
- Added compatibility with phpBB 3.2.x (Prosilver)
- Styles support added:
    - we_clearblue style (3.1.x)
- Languages added:
    - Brazilian-Portuguese
    - Russian

### 1.3.2 - 2016-03-23

- Respect phpBB's censored text setting in topic titles
- Lots of code refactoring under the hood
- Styles support added:
    - Anami style (3.1.x)
- Languages added:
    - Greek
    - Italian

### 1.3.1 - 2015-04-16

- Lots of code refactoring under the hood
- Add a check to prevent selecting too many forums in PST settings
- Styles support added:
    - we_universal style (3.1.x)
    - bb3-mobi style (3.1.x)
- Languages added:
    - Arabic
    - Czech
    - Croatian
    - Japanese
    - Turkish

### 1.3.0 - 2014-12-10

- First stable validated release as an extension
