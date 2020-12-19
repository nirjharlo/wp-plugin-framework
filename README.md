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

2. In `autoload.php` the `PLUGIN_BUILD` class includes all the files in instance and declares the classes inside them. You can remove existing files or add more files. It's recomended to put all the plugin features instances inside `PLUGIN_BUILD`. This will help in organising the code.

3. In `autoload.php` the installation and uninstallation classes contain possible situations, including DB installation and uninstallation features.

## Features

Go through the files in `/lib/class-` and `/src/class-`. First one contains classes for extra features, while the latter is using essential features.

### `/lib` Files

`/lib/class-cron.php` :: `PLUGIN_CRON` to schedule operations.

`/lib/class-api.php` :: `PLUGIN_API` to integrate 3rd party APIs.

`lib/table.php` :: `PLUGIN_TABLE` to display data tables.

`/lib/class-ajax.php` :: `PLUGIN_AJAX` to make AJAX requests.

`/lib/class-upload.php` :: `PLUGIN_UPLOAD` to upload a file.

`/lib/class-script.php` :: `PLUGIN_SCRIPT` to add required CSS and JS.

### `/src` Files

`/src/class-install.php` :: `PLUGIN_INSTALL` to handle activation process.

`/src/class-db.php` :: `PLUGIN_DB` to install database tables.

`/src/class-settings.php` :: `PLUGIN_SETTINGS` to create admin settings pages.

`/src/class-cpt.php` :: `PLUGIN_CPT` to create custom post type.

`/src/class-widget.php` :: `PLUGIN_WIDGET` to add custom widget.

`/src/class-metabox.php` :: `PLUGIN_METABOX` to add custom metabox in editor screen.

`/src/class-shortcode.php`:: `PLUGIN_SHORTCODE` to add and display shortcodes.

`/src/class-query.php`:: `PLUGIN_QUERY` to use post and user query. It uses `wp_pagenavi()` for breadceumbs

`/src/class-rest.php`:: `PLUGIN_CUSTOM_ROUTE` to extend REST API.
