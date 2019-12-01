<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Main plugin object to define the plugin
if ( ! class_exists( 'PLUGIN_BUILD' ) ) {

	final class PLUGIN_BUILD {



		public function installation() {

			/**
			*
			* Plugin installation
			*
			if (class_exists('PLUGIN_INSTALL')) {

				$install = new PLUGIN_INSTALL();
				$install->textDomin = 'textdomain';
				$install->phpVerAllowed = '';
				$install->pluginPageLinks = array(
												array(
													'slug' => '',
													'label' => ''
												),
											);
				$install->execute();
			}
			*
			*/
		}



		//Custom corn class, register it while activation
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


		public function db_install() {

			/**
			*
			* Install database by defining your SQL
			*
			if ( class_exists( 'PLUGIN_DB' ) ) {
				$db = new PLUGIN_DB();
				$db->table = 'plugin_db_table_name';
				$db->sql = "ID mediumint(9) NOT NULL AUTO_INCREMENT,
							date date NOT NULL,
							UNIQUE KEY ID (ID)";
				$db->build();
			}
			*
			*
			* Optionally check if the DB table is installed correctly
			*
			if (get_option('_plugin_db_exist') == '0') {
				add_action( 'admin_notices', array( $this, 'db_error_msg' ) );
			}
			*
			*/

			/**
			*
			* Install DB options
			*
			$options = array(
							array( 'option_name', '__value__' ),
						);
			foreach ($options as $value) {
				update_option( $value[0], $value[1] );
			}
			*
			*/
		}



		//Notice of DB
		public function db_error_msg() { ?>

			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Database table Not installed correctly.', 'textdomain' ); ?></p>
 			</div>
			<?php
		}



		public function db_uninstall() {

			/**
			*
			* Important table name declarition
			*
			$tableName = 'plugin_db_table_name';

			global $wpdb;
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}$tableName" );
			$options = array(
								'_plugin_db_exist'
							);
			foreach ($options as $value) {
				delete_option($value);
			}
			*
			*/
		}


		public function do_cron_job_function() {

			//Do cron function
		}


		public function custom_cron_hook_cb() {

			add_action('custom_cron_hook', array( $this, 'do_cron_job_function'));
		}


		public function cron_uninstall() {

			wp_clear_scheduled_hook('custom_cron_hook');
		}


		//Include scripts
		public function scripts() {

			if ( class_exists( 'PLUGIN_SCRIPT' ) ) new PLUGIN_SCRIPT();
		}



		//Include settings pages
		public function settings() {

			if ( class_exists( 'PLUGIN_SETTINGS' ) ) new PLUGIN_SETTINGS();
		}


		//Include widget classes
		public function widgets() {

			if ( class_exists( 'PLUGIN_WIDGET' ) ) new PLUGIN_WIDGET();
		}



		//Include metabox classes
		public function metabox() {

			if ( class_exists( 'PLUGIN_METABOX' ) ) new PLUGIN_METABOX();
		}



		//Include shortcode classes
		public function shortcode() {

			if ( class_exists( 'PLUGIN_SHORTCODE' ) ) new PLUGIN_SHORTCODE();
		}



		//Add functionality files
		public function functionality() {

			require_once ('src/install.php');
			require_once ('src/db.php');
			require_once ('src/query.php');
			require_once ('src/settings.php');
			require_once ('src/widget.php');
			require_once ('src/metabox.php');
			require_once ('src/shortcode.php');
		}



		//Call the dependency files
		public function helpers() {

			require_once ('lib/cron.php');
			require_once ('lib/api.php');
			require_once ('lib/table.php');
			require_once ('lib/ajax.php');
			require_once ('lib/upload.php');
			require_once ('lib/script.php');

			/**
			 * Available classes:
			 *
			 * PLUGIN_CORN, PLUGIN_API, PLUGIN_TABLE, PLUGIN_AJAX, PLUGIN_UPLOAD, PLUGIN_SCRIPT
			 */
		}



		public function __construct() {

			$this->helpers();
			$this->functionality();

			register_activation_hook( PLUGIN_FILE, array( $this, 'db_install' ) );
			register_activation_hook( PLUGIN_FILE, array($this, 'cron_activation' ));

			//remove the DB upon uninstallation
			register_uninstall_hook( PLUGIN_FILE, array( 'PLUGIN_BUILD', 'db_uninstall' ) ); //$this won't work here.
			register_uninstall_hook( PLUGIN_FILE, array( 'PLUGIN_BUILD', 'cron_uninstall' ) );

			add_action('init', array($this, 'installation'));
			add_action('init', array($this, 'custom_cron_hook_cb'));

			$this->scripts();
			$this->widgets();
			$this->metabox();
			$this->shortcode();
			$this->settings();
		}
	}
} ?>
