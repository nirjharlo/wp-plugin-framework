<?php
/**
 Plugin Name: WordPress Plugin Framework
 Plugin URI: https://github.com/nirjharlo/wp-plugin-framework/
 Description: Simple and Light WordPress plugin development framework for organized Object Oriented code for Developers.
 Version: 1.4.2
 Author: Nirjhar Lo
 Author URI: http://nirjharlo.com
 Text Domain: textdomain
 Domain Path: /asset/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( !defined( 'ABSPATH' ) ) exit;


//Define basic names
//Edit the "_PLUGIN" in following namespaces for compatibility with your desired name.
defined( 'PLUGIN_DEBUG' ) or define( 'PLUGIN_DEBUG', false );

defined( 'PLUGIN_PATH' ) or define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
defined( 'PLUGIN_FILE' ) or define( 'PLUGIN_FILE', plugin_basename( __FILE__ ) );

defined( 'PLUGIN_TRANSLATE' ) or define( 'PLUGIN_TRANSLATE', plugin_basename( plugin_dir_path( __FILE__ ) . 'asset/ln/' ) );

defined( 'PLUGIN_JS' ) or define( 'PLUGIN_JS', plugins_url( '/asset/js/', __FILE__  ) );
defined( 'PLUGIN_CSS' ) or define( 'PLUGIN_CSS', plugins_url( '/asset/css/', __FILE__ ) );
defined( 'PLUGIN_IMAGE' ) or define( 'PLUGIN_IMAGE', plugins_url( '/asset/img/', __FILE__ ) );


//The Plugin
require_once( 'autoload.php' );
function plugin() {
	if ( class_exists( 'PLUGIN_BUILD' ) ) return PLUGIN_BUILD::instance();
}

global $plugin;
$plugin = plugin(); ?>
