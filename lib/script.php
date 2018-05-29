<?php
/**
 * Add scripts to the plugin. CSS and JS.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'PLUGIN_SCRIPT' ) ) {

	final class PLUGIN_SCRIPT {


		public function __construct() {

			add_action( 'admin_head', array( $this, 'data_table_css' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'backend_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}



		// Table css for settings page data tables
		public function data_table_css() {

			// Set condition to add script
			// if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'pageName' ) return;

			$table_css = '<style type="text/css">
							.wp-list-table .column-ColumnName { width: 100%; }
						</style>';

			return $table_css;
		}



		// Enter scripts into pages
		public function backend_scripts() {

			// Set condition to add script
			// if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'pageName' ) return;

			wp_enqueue_script( 'jsName', _PLUGIN_JS . 'ui.js', array() );
			wp_enqueue_style( 'cssName', _PLUGIN_CSS . 'css.css' );
		}



		// Enter scripts into pages
		public function frontend_scripts() {

			wp_enqueue_script( 'jsName', _PLUGIN_JS . 'ui.js', array() );
			wp_enqueue_style( 'cssName', _PLUGIN_CSS . 'css.css' );
		}
	}
} ?>