# WP Plugin Framework
[![Build Status](https://travis-ci.org/nirjharlo/wp-plugin-framework.svg?branch=master)](https://travis-ci.org/nirjharlo/wp-plugin-framework)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/nirjharlo/wp-plugin-framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nirjharlo/wp-plugin-framework/)

A WordPress plugin framework is a simple and light-weight base to build any standard WP plugin on top of it. Easily achieve high productivity.
It contains various items, such as Settings pages, Data tables, Widgets, Metaboxes, Custom Post Types, Shortcodes along with infrastructure for DB operations.

There are extra classes for API integration, AJAX, File upload and Cron jobs.

NOTE: Requires PHP 5.4 and up. Uses `cURL` for API integration class.

## Usage

1. Change the class namespaces, file namespaces, declarations in `wp-plugin-framework.php` and `install.php` before you start coding.
Also, change the `/asset` file paths in plugin file `wp-plugin-framework.php` to your chosen folder path name.
It's a precaution to avoid conflict.

2. In `plugin/PluginLoader.php` the `PluginLoader` class includes all the files in instance and declares the classes inside them. You can remove existing files or add more files. It's recomended to put all the plugin features instances inside `PluginLoader`. This will help in organising the code.

3. In `plugin/PluginLoader.php` the installation and uninstallation classes contain possible situations, including DB installation and uninstallation features.

## Features

Go through the files in `/lib/class-` and `/src/class-`. First one contains classes for extra features, while the latter is using essential features.

### `/plugin/lib` Files

`/plugin/lib/Cron.php` :: `Cron` to schedule operations.

`/plugin/lib/Api.php` :: `Api` to integrate 3rd party APIs.

`/plugin/lib/Table.php` :: `Table` to display data tables.

`/plugin/lib/Ajax.php` :: `Ajax` to make AJAX requests.

`/plugin/lib/Upload.php` :: `Upload` to upload a file.

`/plugin/lib/Script.php` :: `Script` to add required CSS and JS.

### `/plugin/src` Files

`/plugin/src/Install.php` :: `Install` to handle activation process.

`/plugin/src/Db.php` :: `Db` to install database tables.

`/plugin/src/Settings.php` :: `Settings` to create admin settings pages.

`/plugin/src/Cpt.php` :: `Cpt` to create custom post type.

`/plugin/src/Widget.php` :: `Widget` to add custom widget.

`/plugin/src/Metabox.php` :: `Metabox` to add custom metabox in editor screen.

`/plugin/src/Shortcode.php`:: `Shortcode` to add and display shortcodes.

`/plugin/src/Query.php`:: `Query` to use post and user query. It uses `wp_pagenavi()` for breadceumbs

`/plugin/src/RestApi.php`:: `RestApi` to extend REST API.
