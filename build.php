<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Main plugin object to define the plugin
if ( ! class_exists( 'PLUGIN_BUILD' ) ) {
	
	final class PLUGIN_BUILD {



		public function installation() {

			/**
			*
			* plugin installation
			*
			$install = if (class_exists('PLUGIN_INSTALL')) new PLUGIN_INSTALL();
			$install->textDomin = '';
			$install->phpVerAllowed = '';
			$install->pluginPageLinks = array(
											array(
												'slug' => '', 'label' => '' ), );
			$install->do();
			*
			*/
		}



		public function db_install() {

			/**
			*
			* Install database by defining your SQL
			*
			$db = if ( class_exists( 'PLUGIN_DB' ) ) new PLUGIN_DB();
			$db->table = 'plugin_db_table_name';
			$db->sql = 'ID mediumint(9) NOT NULL AUTO_INCREMENT,

						//ENTER SQL

						date date NOT NULL,
						UNIQUE KEY ID (ID)';
			$db->build();
			if (get_option('_plugin_db_exist') == '0') {
				add_action( 'admin_notices', 'db_error_msg' );
			}
			*
			*/

			/**
			*
			* Install db options
			*
			$options = array(
							array( 'option_name', '__value__' ),
						);
			foreach ($$options as $value) {
				update_option( $value[0], $value[1] );
			}
			*
			*/
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



		//Add customization files
		public function customization() {

			require_once ('src/');
			require_once ('src/');
			require_once ('src/');
		}



		//Include settings pages
		public function functionality() {

			/*
			*
			* Add Settings functionality
			*
			$settings = if ( class_exists( 'PLUGIN_SETTINGS' ) ) new PLUGIN_SETTINGS();
			$settings->capability = 'manage_options';
			$settings->screen = ;
			$settings->menuPage = array( 'name' => '', 'heading' => '', 'slug' => '' );
			$settings->subMenuPage = array(
										array(
											'name' => '',
											'heading' => '',
											'slug' => '',
											'parent_slug' => '',
											'help' => true/false,
											'screen' => true/false ) );
			$settings->help = array(
								array( 'slug' => '',
										'help' => array(
													'info' => array(
																array(
																	'id' => 'helpId',
																	'title' => __( 'Title', 'textdomain' ),
																	'content' => __( 'helpDescription', 'textdomain' ),
																),
															),
										'link' => '<p><a href="#">' . __( 'helpLink', 'textdomain' ) . '</a></p>',
								) ) );
			$settings->execute();
			*
			*/
		}



		//Include widget classes
		public function widgets() {

			if ( class_exists( 'PLUGIN_WIDGET' ) ) new PLUGIN_WIDGET();
		}



		//Notice of DB
		public function db_error_msg() { ?>

			<div class="notice notice-error is-dismissible">
				<p><?php _e( 'Database table Not installed correctly.', 'textdomain' ); ?></p>
 			</div>
			<?php
		}



		//-----// Framework code ->



		//Call the dependency files
		public function helpers() {

			require_once ('helper/api.php');
			require_once ('helper/db.php');
			require_once ('helper/table.php');
			require_once ('helper/ajax.php');
			require_once ('helper/install.php');
			require_once ('helper/settings.php');
			require_once ('helper/widgets.php');
		}



		public function __construct() {

			$this->helpers();
			$this->customization();

			register_activation_hook( PLUGIN_FILE, array( $this, 'db_install' ) );
			register_uninstall_hook( PLUGIN_FILE, array( 'PLUGIN_BUILD', 'db_uninstall' ) );

			add_action('init', array($this, 'installation'));
			add_action('init', array($this, 'functionality'));

			$this->widgets();
		}
	}
} ?>