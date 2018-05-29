<?php
/**
 Plugin Name: WordPress Plugin Framework
 Plugin URI: https://github.com/nirjharlo/wp-plugin-framework/
 Description: Simple and Light WordPress plugin development framework for organized Object Oriented code for Developers.
 Version: 1.1
 Author: Nirjhar Lo
 Author URI: http://stackoverflow.com/story/nirjhar-lo-206774
 Text Domain: textdomain
 Domain Path: /assets/ln
 License: GPLv2
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if (!defined('ABSPATH')) exit;


//Define basic names
//Edit the "_PLUGIN" in following namespaces for compatibility with your desired name.
defined('_PLUGIN_DEBUG') or define('_PLUGIN_DEBUG', false);

defined('_PLUGIN_PATH') or define('_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('_PLUGIN_FILE') or define('_PLUGIN_FILE', plugin_basename(__FILE__));

defined('_PLUGIN_EXECUTE') or define('_PLUGIN_EXECUTE', plugin_dir_path(__FILE__).'src/');
defined('_PLUGIN_HELPER') or define('_PLUGIN_HELPER', plugin_dir_path(__FILE__).'helper/');
defined('_PLUGIN_TRANSLATE') or define('_PLUGIN_TRANSLATE', plugin_basename( plugin_dir_path(__FILE__).'asset/ln/'));

//change /wp-plugin-framework/ with your /plugin-name/
defined('_PLUGIN_JS') or define('_PLUGIN_JS', plugins_url().'/wp-plugin-framework/asset/js/');
defined('_PLUGIN_CSS') or define('_PLUGIN_CSS', plugins_url().'/wp-plugin-framework/asset/css/');
defined('_PLUGIN_IMAGE') or define('_PLUGIN_IMAGE', plugins_url().'/wp-plugin-framework/asset/img/');


//The Plugin
require_once('autoload.php');
if ( class_exists( 'PLUGIN_BUILD' ) ) new PLUGIN_BUILD(); ?>