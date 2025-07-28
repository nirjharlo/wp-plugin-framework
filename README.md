# WP Plugin Framework

A lightweight foundation for rapid WordPress plugin development. The framework provides reusable classes for common tasks such as database setup, custom post types, REST API endpoints and more. Build feature rich plugins without rewriting boilerplate every time.

[![Build Status](https://travis-ci.org/nirjharlo/wp-plugin-framework.svg?branch=master)](https://travis-ci.org/nirjharlo/wp-plugin-framework)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/nirjharlo/wp-plugin-framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nirjharlo/wp-plugin-framework/)

## Requirements
- PHP 7.1+
- WordPress 5.0+

## Installation
```bash
composer install
```
For production use `composer install --no-dev`.

## Usage
1. Update namespaces in `wp-plugin-framework.php` and `Plugin/PluginLoader.php` to match your plugin name.
2. Instantiate any of the engine classes or extend them to add your own logic.
3. Register plugin features inside `PluginLoader` to keep everything organised.

## Features
- Settings pages, widgets, metaboxes and shortcodes
- Database migration helpers
- Fluent API integration class
- Cron scheduler wrapper
- REST API controller scaffold

## Contributing
Pull requests are welcome. For major changes please open an issue first to discuss what you would like to change.

## License
GPL-2.0-or-later. See [LICENSE](LICENSE).

See [CHANGELOG](CHANGELOG.md) for release notes.
