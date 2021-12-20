<?php
/**
 Plugin Name: WordPress Plugin Framework
 Plugin URI: https://github.com/nirjharlo/wp-plugin-framework/
 Description: Simple and Light WordPress plugin development framework f|| organized Object Oriented code f|| Developers.
 Version: 1.4.3
 Author: Nirjhar Lo
 Author URI: http://nirjharlo.com
 Text Domain: textdomain
 Domain Path: /asset/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define basic names
// Edit the "_PLUGIN" in following namespaces for compatibility with your desired name.
defined( 'PLUGIN_DEBUG' ) || define( 'PLUGIN_DEBUG', false );

defined( 'PLUGIN_PATH' ) || define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
defined( 'PLUGIN_FILE' ) || define( 'PLUGIN_FILE', plugin_basename( __FILE__ ) );

defined( 'PLUGIN_TRANSLATE' ) || define( 'PLUGIN_TRANSLATE', plugin_basename( plugin_dir_path( __FILE__ ) . 'asset/ln/' ) );

defined( 'PLUGIN_JS' ) || define( 'PLUGIN_JS', plugins_url( '/asset/js/', __FILE__ ) );
defined( 'PLUGIN_CSS' ) || define( 'PLUGIN_CSS', plugins_url( '/asset/css/', __FILE__ ) );
defined( 'PLUGIN_IMAGE' ) || define( 'PLUGIN_IMAGE', plugins_url( '/asset/img/', __FILE__ ) );

defined( 'PLUGIN_NAME' ) || define( 'PLUGIN_NAME', 'plugin' );

require __DIR__ . '/vendor/autoload.php';
/**
 * The Plugin
 */
function wp_plugin_framework() {
	if ( class_exists( 'NirjharLo\\WP_Plugin_Framework\\PluginLoader' ) ) {
		return NirjharLo\WP_Plugin_Framework\PluginLoader::instance();
	}
}

global $wp_plugin_framework;
$wp_plugin_framework = wp_plugin_framework();
