<?php
/**
 Plugin Name: WordPress Plugin Framework
 Plugin URI: https://github.com/nirjharlo/wp-plugin-framework/
 Description: Simple and Light WordPress plugin development framework for organized Object Oriented code for Developers.
 Version: 0.1
 Author: Nirjhar Lo
 Author URI: http://stackoverflow.com/story/nirjhar-lo-206774
 Text Domain: textdomain
 Domain Path: /assets/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('ABSPATH')) exit;


//Define basic names
//EDIT THE FOLLOWING FOR COMPATIBILITY
defined('PLUGIN_DEBUG') or define('PLUGIN_DEBUG', false);

defined('PLUGIN_PATH') or define('PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('PLUGIN_FILE') or define('PLUGIN_FILE', plugin_basename(__FILE__));

defined('PLUGIN_EXECUTE') or define('PLUGIN_EXECUTE', plugin_dir_path(__FILE__).'src/');
defined('PLUGIN_HELPER') or define('PLUGIN_HELPER', plugin_dir_path(__FILE__).'helper/');
defined('PLUGIN_TRANSLATE') or define('PLUGIN_TRANSLATE', plugin_basename( plugin_dir_path(__FILE__).'asset/ln/'));

//change /wp-plugin-framework/ with your /plugin-name/
defined('PLUGIN_JS') or define('PLUGIN_JS_PATH', plugins_url().'/wp-plugin-framework/asset/js/');
defined('PLUGIN_CSS') or define('PLUGIN_CSS_PATH', plugins_url().'/wp-plugin-framework/asset/css/');


//The Plugin
require_once('build.php');
if ( class_exists( 'PLUGIN_BUILD' ) ) new PLUGIN_BUILD(); ?>