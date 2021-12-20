<?php
namespace NirjharLo\WP_Plugin_Framework\Engine\Init;

/**
 * Add scripts to the plugin. CSS and JS.
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


	abstract class Script {


		public static $cssName;

		public static $scriptName;

		public static $ajaxObjName;


		/**
		 * Add scripts to front/back end heads
		 *
		 * @return Void
		 */
		public function __construct() {

			add_action( 'admin_head', array( $this, 'adminHeaderCss' ) );
			add_action( 'wp_head', array( $this, 'HeaderCss' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'backEndScript' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontEndScript' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'backEndStyle' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontEndStyle' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'backEndAjaxScript' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontEndAjaxScript' ) );
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

			wp_enqueue_script( $this->scriptName, PLUGIN_JS . 'ui.js', array() );
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function frontend_scripts() {

			wp_enqueue_script( $this->scriptName, PLUGIN_JS . 'ui.js', array() );
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function backEndStyle() {

			// Set condition to add script
			// if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'pageName' ) return;

			wp_enqueue_style( $this->cssName, PLUGIN_CSS . 'css.css' );
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function frontEndStyle() {

			wp_enqueue_style( $this->cssName, PLUGIN_CSS . 'css.css' );
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function backEndAjaxScript() {

			wp_enqueue_script( $this->ajaxScriptName, PLUGIN_JS . 'ajax.js', array('jquery') );
			wp_localize_script( $this->ajaxScriptName, $this->ajaxObjName, array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		}


		/**
		 * Enter scripts into pages
		 *
		 * @return String
		 */
		public function frontEndAjaxScript() {

			wp_enqueue_script( $this->ajaxScriptName, PLUGIN_JS . 'ajax.js', array('jquery') );
			wp_localize_script( $this->ajaxScriptName, $this->ajaxObjName, array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}
