<?php
namespace NirjharLo\WP_Plugin_Framework;

use NirjharLo\WP_Plugin_Framework\Engine\Init\Install as Install;
use NirjharLo\WP_Plugin_Framework\Engine\Init\Db as Db;
use NirjharLo\WP_Plugin_Framework\Engine\Init\Script as Script;

use NirjharLo\WP_Plugin_Framework\Engine\Src\Cpt as Cpt;
use NirjharLo\WP_Plugin_Framework\Engine\Src\Settings as Settings;
use NirjharLo\WP_Plugin_Framework\Engine\Src\Widget as Widget;
use NirjharLo\WP_Plugin_Framework\Engine\Src\Metabox as Metabox;
use NirjharLo\WP_Plugin_Framework\Engine\Src\Shortcode as Shortcode;
use NirjharLo\WP_Plugin_Framework\Engine\Src\RestApi as RestApi;

use NirjharLo\WP_Plugin_Framework\Engine\Lib\Cron as Cron;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin object to define the plugin
 * Follow: https://codex.wordpress.org/Plugin_API for details
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */

	final class PluginLoader {

		/**
		 * @var String
		 */
                protected $version = '1.4.4';


		/**
		 * Plugin Instance.
		 *
		 * @var PLUGIN_BUILD the PLUGIN Instance
		 */
		protected static $instance;


		/**
		 * Text domain to be used throughout the plugin
		 *
		 * @var String
		 */
		protected static $textDomain = 'textdomain';


		/**
		 * Minimum PHP version allowed for the plugin
		 *
		 * @var String
		 */
		protected static $phpVerAllowed = '7.1';


		/**
		 * DB tabble used in plugin
		 *
		 * @var String
		 */
		protected static $pluginTable = "_" . PLUGIN_NAME . "_db_table_name";


		/**
		 * DB tabble used in plugin
		 *
		 * @var String
		 */
		protected static $pluginName = PLUGIN_NAME;


		/**
		 * Plugin listing page links, along with Deactivate
		 *
		 * @var Array
		 */
		protected static $pluginPageLinks = array(
			array(
				'slug'  => '',
				'label' => '',
			),
		);


		/**
		 * Main Plugin Instance.
		 *
		 * @return PLUGIN_BUILD
		 */
		public static function instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				self::$instance->init();
			}

			return self::$instance;
		}


		/**
		 * Install plugin setup
		 */
		public function installation() {

			$install                 	 = new Install();
			$install::$textDomain      = self::$textDomain;
			$install::$phpVerAllowed   = self::$phpVerAllowed;
			$install::$pluginPageLinks = self::$pluginPageLinks;
			$install::execute();
		}


		/**
		 * Install plugin data
		 */
		public function dbInstall() {

				$db        = new Db();
				$db->table = self::$pluginTable;
				$db->sql   = '`ID` mediumint(9) NOT NULL AUTO_INCREMENT,
							`date` date NOT NULL,
							UNIQUE KEY `ID` (`ID`)';
				// $db->build();

			if ( get_option( '_' . PLUGIN_NAME . '_db_exist' ) === '0' ) {
				add_action( 'admin_notices', array( $this, 'dbNotInstalledErrorMsg' ) );
			}

			$options = array(
				array( 'option_name_1', '__value__1' ),
			);
			foreach ( $options as $value ) {
				update_option( $value[0], $value[1] );
			}
		}


		/**
		 * Notice of DB
		 */
		public function dbNotInstalledErrorMsg() { ?>

			<div class="notice notice-error is-dismissible">
				<p><?php esc_attr_e( 'Database table Not installed correctly.', 'textdomain' ); ?></p>
			</div>
			<?php
		}


	 	/**
		 * If CPT exists, include taht and flush the rewrite rules upon activation
		 */
		public function flushPermalinks() {

			if ( class_exists( 'NirjharLo\\WP_Plugin_Framework\\Engine\\Src\\Cpt' ) ) {
				new Cpt();
				flush_rewrite_rules();
			}
		}


		/**
		 * Include scripts
		 */
		public function scripts() {

				#new Script();
		}


		/**
		 * Custom corn class, register it while activation
		 */
		public function cronActivation() {

                        $cron     = new Cron();
                        $schedule = $cron->schedule(
                                array(
					'timestamp'  => current_time( 'timestamp' ),
					// 'schedule' can be 'hourly', 'daily', 'weekly' or anything custom as defined in PLUGIN_CRON
					'recurrence' => 'schedule',
					// Use custom_corn_hook to hook into any cron process, anywhere in the plugin.
					'hook'       => 'custom_cron_hook',
				)
			);
		}


		/**
		 * Uninstall plugin data
		 */
		public function dbUninstall() {

			$table_name = self::$pluginTable;

			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					'DROP TABLE IF EXISTS %s',
					$wpdb->prefix . $table_name
				)
			);

			$options = array(
				"_" . PLUGIN_NAME . "_db_exist",
			);
			foreach ( $options as $value ) {
				delete_option( $value );
			}
		}


		/**
		 * CRON callback
		 */
		private function doCronJobFunction() {

			// Do cron function
		}


		/**
		 * Run CRON action
		 */
		private function customCronHookCb() {

			add_action( 'custom_cron_hook', array( $this, 'doCronJobFunction' ) );
		}


		/**
		 * Uninstall CRON hook
		 */
		public function cronUninstall() {

			wp_clear_scheduled_hook( 'custom_cron_hook' );
		}


		/**
		 * Install Custom post types
		 */
		public function cpt() {

				new Cpt();
		}


		/**
		 * Include settings pages
		 */
		private function settings() {

				new Settings();
		}


		/**
		 * Include widget classes
		 */
		private function widgets() {

				new Widget();
		}


		/**
		 * Include metabox classes
		 */
		private function metabox() {

				new Metabox();
		}


		/**
		 * Include shortcode classes
		 */
		private function shortcode() {

				new Shortcode();
		}


		/**
		 * Instantiate REST API
		 */
		private function rest_api() {

			new RestApi();
		}


		/**
		 * Instantiate REST API
		 *
		 * @param Obj $result Login info
		 */
		private function preventUnauthorizedRestAccess( $result ) {
			// If a previous authentication check was applied,
			// pass that result along without modification.
			if ( true === $result || is_wp_error( $result ) ) {
					return $result;
			}

			// No authentication has been performed yet.
			// Return an error if user is not logged in.
			if ( ! is_user_logged_in() ) {
					return new WP_Error(
						'rest_not_logged_in',
						__( 'You are not currently logged in.' ),
						array( 'status' => 401 )
					);
			}

			return $result;
		}


		/**
		 * Instantiate the plugin
		 */
		public function init() {

			//Setup
			register_activation_hook( PLUGIN_FILE, array( $this, 'dbInstall' ) );
			register_activation_hook( PLUGIN_FILE, array( $this, 'flushPermalinks' ) );
			register_activation_hook( PLUGIN_FILE, array( $this, 'cronActivation' ) );

			// Remove the DB and CORN upon uninstallation,
			// Using $this won't work here.
			register_uninstall_hook( PLUGIN_FILE, array( 'NirjharLo\\WP_Plugin_Framework\\PluginLoader', 'dbUninstall' ) );
			register_uninstall_hook( PLUGIN_FILE, array( 'NirjharLo\\WP_Plugin_Framework\\PluginLoader', 'cronUninstall' ) );

			add_action( 'init', array( $this, 'installation' ) );
			add_action( 'init', array( $this, 'scripts' ) );


			//Features
			add_action( 'init', array( $this, 'cpt' ) );
			$this->customCronHookCb();
			$this->widgets();
			$this->metabox();
			$this->shortcode();
			$this->settings();

			//Gutenberg


			//API
			// Alternative method: add_action( 'rest_api_init', array($this, 'rest_api') );
			$this->rest_api();
			add_filter( 'rest_authentication_errors', array( $this, 'preventUnauthorizedRestAccess' ) );
		}
	}
