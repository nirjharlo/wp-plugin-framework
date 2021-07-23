<?php
namespace NirjharLo\WP_Plugin_Framework\Lib;

/**
 * Add scripts to the plugin. CSS and JS.
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Lib\\Script' ) ) {

	final class Script {


		/**
		 * Add scripts to front/back end heads
		 *
		 * @return Void
		 */
		public function __construct() {

			add_action( 'admin_head', array( $this, 'data_table_css' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'backend_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}


		/**
		 * Table css for settings page data tables
		 *
		 * @return String
		 */
		public function data_table_css() {

			// Set condition to add script
			// if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'pageName' ) return;

			$table_css = '<style type="text/css">
							.wp-list-table .column-ColumnName { width: 100%; }
						</style>';

			return $table_css;
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function backend_scripts() {

			// Set condition to add script
			// if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'pageName' ) return;

			wp_enqueue_script( 'jsName', PLUGIN_JS . 'ui.js', array() );

			wp_enqueue_script( 'action-jsName', PLUGIN_JS . 'ajax.js', array() );
			wp_localize_script( 'action-jsName', 'ajax', array( 'url' => admin_url('admin-ajax.php') ) );

			wp_enqueue_style( 'cssName', PLUGIN_CSS . 'css.css' );
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function frontend_scripts() {

			wp_enqueue_script( 'jsName', PLUGIN_JS . 'ui.js', array() );

			wp_enqueue_script( 'action-jsName', PLUGIN_JS . 'ajax.js', array() );
			wp_localize_script( 'action-jsName', 'ajax', array( 'url' => admin_url('admin-ajax.php') ) );

			wp_enqueue_style( 'cssName', PLUGIN_CSS . 'css.css' );
		}
	}
} ?>
