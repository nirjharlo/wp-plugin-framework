# WP Plugin Framework
[![Build Status](https://travis-ci.org/nirjharlo/wp-plugin-framework.svg?branch=master)](https://travis-ci.org/nirjharlo/wp-plugin-framework)

A WordPress plugin framework base to build any standard WP plugin on top of it.
It contains various items, such as Settings pages, Data tables, Widgets, Metaboxes, Custom Post Types, Shortcodes along with infrastructure for DB operations.

There are extra classes for API integration, AJAX, File upload and Cron jobs.

NOTE: Requires PHP 5.4 and up. Uses `cURL` for API integration class.

## Usage

1. Change the class names, file names and declarations in `framework.php` according to need.

2. In `autoload.php` the `PLUGIN_BUILD` class includes all the files in process and decares the classes inside them. Modify them according to need.

3. Go through the files in `/lib/` and `/src/`. First one contains classes for extra features, while the src is using essential features.

4. In `autoload.php` the installation and uninstallation classes contain possible situations. Modify them as per need.