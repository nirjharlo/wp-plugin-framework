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
				$install->do();
			}
			*
			*/
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
				add_action( 'admin_notices', 'db_error_msg' );
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
			$tableName = 'plugin_db_table';

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



		//Add customization files
		public function customization() {

			require_once ('src/install.php');
			require_once ('src/db.php');
			require_once ('src/settings.php');
			require_once ('src/widgets.php');
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
		}



		public function __construct() {

			$this->helpers();
			$this->customization();

			register_activation_hook( PLUGIN_FILE, array( $this, 'db_install' ) );

			//remove the DB upon uninstallation
			register_uninstall_hook( PLUGIN_FILE, array( 'PLUGIN_BUILD', 'db_uninstall' ) ); //$this won't work here.

			add_action('init', array($this, 'installation'));
			add_action('init', array($this, 'functionality'));

			$this->widgets();
			$this->metabox();
			$this->shortcode();
			$this->settings();
		}
	}
} ?>