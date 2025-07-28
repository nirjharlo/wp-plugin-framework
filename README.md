# WP Plugin Framework

A lightweight foundation for rapidly building WordPress plugins using modern, object oriented practices.

The framework ships with ready to use classes for settings pages, custom post types, widgets, metaboxes and shortcodes. Helpers for database installation, REST API integration, AJAX requests, cron jobs and file uploads are also included.

Requires **PHP 5.4+** and the `cURL` extension for the API helper class.

## Installation

```bash
composer install --no-dev  # Production
composer install           # Development
./vendor/bin/phpcs --config-set installed_paths ../../wp-coding-standards/wpcs
```

## Usage

1. Update namespaces and folder paths in `wp-plugin-framework.php` and `Plugin/PluginLoader.php` before extending the framework.
2. Register your features inside `PluginLoader` for a clean and organised structure.
3. Review the classes in `Plugin/Lib` and `Plugin/Src` and extend them as needed.

## Directory overview

- **Plugin/Lib** – Optional helpers such as `Api`, `Ajax`, `Cron`, `Upload` and more.
- **Plugin/Src** – Core classes for database setup, settings pages, widgets and REST API endpoints.
- **asset** – Example JavaScript, CSS and translation files.

## Contributing

Pull requests are welcome. Please ensure coding standards are met by running `phpcs` before committing.

## License

WP Plugin Framework is open-sourced software licensed under the [GPLv2 or later](LICENSE).

## Changelog

All notable changes are documented in [CHANGELOG.md](CHANGELOG.md).
