<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main plugin object to define the plugin
 */
if ( ! class_exists( 'PLUGIN_BUILD' ) ) {

	final class PLUGIN_BUILD {

		/**
		 * @var String
		 */
		protected $version = '1.2.1';


		/**
		 * Plugin Instance.
		 *
		 * @var PLUGIN_BUILD the PLUGIN Instance
		 */
		protected static $_instance;


		/**
		 * Text domain to be used throughout the plugin
		 *
		 * @var String
		 */
		protected static $text_domain = 'textdomain';


		/**
		 * Minimum PHP version allowed for the plugin
		 *
		 * @var String
		 */
		protected static $php_ver_allowed = '5.3';


		/**
		 * DB tabble used in plugin
		 *
		 * @var String
		 */
		protected static $plugin_table = 'plugin_db_table_name';


		/**
		 * Plugin listing page links, along with Deactivate
		 *
		 * @var Array
		 */
		protected static $plugin_page_links = array(
			array(
				'slug' => '',
				'label' => ''
			) );


		/**
		 * Main Plugin Instance.
		 *
		 * @return PLUGIN_BUILD
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
				self::$_instance->init();
			}

			return self::$_instance;
		}


		/**
		 * Install plugin setup
		 *
		 * @return Void
		 */
		public function installation() {

			if (class_exists('PLUGIN_INSTALL')) {

				$install = new PLUGIN_INSTALL();
				$install->text_domain = self::$text_domain;
				$install->php_ver_allowed = self::$php_ver_allowed;
				$install->plugin_page_links = self::$plugin_page_links;
				$install->execute();
			}
		}


		/**
		 * Custom corn class, register it while activation
		 *
		 * @return Void
		 */
		public function cron_activation() {

			if ( class_exists( 'PLUGIN_CRON' ) ) {

				$cron = new PLUGIN_CRON();
				$schedule = $cron->schedule_task(
							array(
							'timestamp' => current_time('timestamp'),
							//'schedule' can be 'hourly', 'daily', 'weekly' or anything custom as defined in PLUGIN_CRON
							'recurrence' => 'schedule',
							// Use custom_corn_hook to hook into any cron process, anywhere in the plugin.
							'hook' => 'custom_cron_hook'
						) );
			}

		}


		/**
		 * Install plugin data
		 *
		 * @return Void
		 */
		public function db_install() {

			if ( class_exists( 'PLUGIN_DB' ) ) {

				$db = new PLUGIN_DB();
				$db->table = self::$plugin_table;
				$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
							date date NOT NULL,
							UNIQUE KEY ID (ID)";
				$db->build();
			}

			if (get_option( '_plugin_db_exist') == '0' ) {
				add_action( 'admin_notices', array( $this, 'db_error_msg' ) );
			}

			$options = array(
				array( 'option_name', '__value__' ),
			);
			foreach ( $options as $value ) {
				update_option( $value[0], $value[1] );
			}
		}


		/**
		 * Notice of DB
		 *
		 * @return string
		 */
		public function db_error_msg() { ?>

			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Database table Not installed correctly.', 'textdomain' ); ?></p>
 			</div>
			<?php
		}


		/**
		 * Uninstall plugin data
		 *
		 * @return Void
		 */
		public function db_uninstall() {

			$table_name = self::$plugin_table;

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}$table_name" );

			$options = array(
				'_plugin_db_exist'
			);
			foreach ( $options as $value ) {
				delete_option( $value );
			}
		}


		/**
		 * CRON callback
		 *
		 * @return Void
		 */
		public function do_cron_job_function() {

			//Do cron function
		}


		/**
		 * Run CRON action
		 *
		 * @return Void
		 */
		public function custom_cron_hook_cb() {

			add_action( 'custom_cron_hook', array( $this, 'do_cron_job_function' ) );
		}


		/**
		 * Uninstall CRON hook
		 *
		 * @return Void
		 */
		public function cron_uninstall() {

			wp_clear_scheduled_hook( 'custom_cron_hook' );
		}


		/**
		 * Include scripts
		 *
		 * @return Void
		 */
		public function scripts() {

			if ( class_exists( 'PLUGIN_SCRIPT' ) ) new PLUGIN_SCRIPT();
		}


		/**
		 * Include settings pages
		 *
		 * @return Void
		 */
		public function settings() {

			if ( class_exists( 'PLUGIN_SETTINGS' ) ) new PLUGIN_SETTINGS();
		}


		/**
		 * Include widget classes
		 *
		 * @return Void
		 */
		public function widgets() {

			if ( class_exists( 'PLUGIN_WIDGET' ) ) new PLUGIN_WIDGET();
		}


		/**
		 *Include metabox classes
		 *
		 * @return Void
		 */
		public function metabox() {

			if ( class_exists( 'PLUGIN_METABOX' ) ) new PLUGIN_METABOX();
		}


		/**
		 * Include shortcode classes
		 *
		 * @return Void
		 */
		public function shortcode() {

			if ( class_exists( 'PLUGIN_SHORTCODE' ) ) new PLUGIN_SHORTCODE();
		}


		/**
		 * Add the functionality files
		 * Available classes: PLUGIN_INSTALL, PLUGIN_DB, PLUGIN_METABOX, PLUGIN_QUERY, PLUGIN_SETTINGS, PLUGIN_SHORTCODE, PLUGIN_WIDGET
		 *
		 * @return Void
		 */
		public function functionality() {

			require_once( 'src/install.php' );
			require_once( 'src/db.php' );
			require_once( 'src/query.php' );
			require_once( 'src/settings.php' );
			require_once( 'src/widget.php' );
			require_once( 'src/metabox.php' );
			require_once( 'src/shortcode.php' );
		}


		/**
		 * Call the dependency files
		 * Available classes: PLUGIN_CORN, PLUGIN_API, PLUGIN_TABLE, PLUGIN_AJAX, PLUGIN_UPLOAD, PLUGIN_SCRIPT
		 *
		 * @return Void
		 */
		public function helpers() {

			require_once( 'lib/cron.php' );
			require_once( 'lib/api.php' );
			require_once( 'lib/table.php' );
			require_once( 'lib/ajax.php' );
			require_once( 'lib/upload.php' );
			require_once( 'lib/script.php' );
		}


		/**
		 * Instantiate the plugin
		 *
		 * @return Void
		 */
		public function init() {

			$this->helpers();
			$this->functionality();

			register_activation_hook( PLUGIN_FILE, array( $this, 'db_install' ) );
			register_activation_hook( PLUGIN_FILE, array($this, 'cron_activation' ) );

			//remove the DB and CORN upon uninstallation
			//$this won't work here.
			register_uninstall_hook( PLUGIN_FILE, array( 'PLUGIN_BUILD', 'db_uninstall' ) );
			register_uninstall_hook( PLUGIN_FILE, array( 'PLUGIN_BUILD', 'cron_uninstall' ) );

			add_action( 'init', array( $this, 'installation' ) );
			add_action( 'init', array( $this, 'custom_cron_hook_cb' ) );

			$this->scripts();
			$this->widgets();
			$this->metabox();
			$this->shortcode();
			$this->settings();
		}
	}
} ?>
