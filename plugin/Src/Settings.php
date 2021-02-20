<?php
namespace NirjharLo\WP_Plugin_Framework\Src;

use NirjharLo\WP_Plugin_Framework\Lib\Table as Table;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Backend settings page class, can have settings fields or data table
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'Settings' ) ) {

	class Settings {


		/**
		 * @var String
		 */
		public $capability;


		/**
		 * @var Array
		 */
		public $menu_page;


		/**
		 * @var Array
		 */
		public $sub_menu_page;


		/**
		 * @var Array
		 */
		public $help;


		/**
		 * @var String
		 */
		public $screen;


		/**
		 * @var Object
		 */
		 public $table;


		/**
		 * Add basic actions for menu and settings
		 *
		 * @return Void
		 */
		public function __construct() {

			$this->capability = 'manage_options';
			$this->menu_page = array( 'name' => '', 'heading' => '', 'slug' => '' );
			$this->sub_menu_page = array(
									'name' => '',
									'heading' => '',
									'slug' => '',
									'parent_slug' => '',
									'help' => '',//true/false,
									'screen' => '',//true/false
								);
			$this->helpData = array(
								array(
								'slug' => '',
								'help' => array(
											'info' => array(
														array(
															'id' => 'helpId',
															'title' => __( 'Title', 'textdomain' ),
															'content' => __( 'Description', 'textdomain' ),
														),
													),
											'link' => '<p><a href="#">' . __( 'helpLink', 'textdomain' ) . '</a></p>',
											)
								)
							);
			$this->screen = ''; // true/false

			/**
			 * Add menues and hooks
			 *
			add_action( 'admin_init', array( $this, 'add_settings' ) );
			add_action( 'admin_menu', array( $this, 'menu_page' ) );
			add_action( 'admin_menu', array( $this, 'sub_menu_page' ) );
			add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );
			 *
			 */
		}


		/**
		 * Add a sample main menu page callback
		 *
		 * @return Void
		 */
		public function menu_page() {

			if ($this->menu_page) {
				add_menu_page(
					$this->menu_page['name'],
					$this->menu_page['heading'],
					$this->capability,
					$this->menu_page['slug'],
					array( $this, 'menu_page_callback' )
				);
			}
		}


		/**
		 * Add a sample Submenu page callback
		 *
		 * @return Void
		 */
		public function sub_menu_page() {

			if ( $this->sub_menu_page ) {
				foreach ( $this->sub_menu_page as $page ) {
					$hook = add_submenu_page(
							$page['parent_slug'],
							$page['name'],
							$page['heading'],
							$this->capability,
							// For the first submenu page, slug should be same as menupage.
							$page['slug'],
							// For the first submenu page, callback should be same as menupage.
							array( $this, 'menu_page_callback' )
						);
					if ( $page['help'] ) {
						add_action( 'load-' . $hook, array( $this, 'help_tabs' ) );
					}
					if ( $page['screen'] ) {
						add_action( 'load-' . $hook, array( $this, 'screen_option' ) );
					}
				}
			}
		}


		/**
		 * Set screen
		 *
		 * @param String $status
		 * @param String $option
		 * @param String $value
		 *
		 * @return String
		 */
		public function set_screen($status, $option, $value) {

			$user = get_current_user_id();

			switch ($option) {
				case 'option_name_per_page':
					update_user_meta($user, 'option_name_per_page', $value);
					$output = $value;
					break;
			}

    		if ( $output ) return $output; // Related to PLUGIN_TABLE()
		}


		/**
		 * Set screen option for Items table
		 *
		 * @return Void
		 */
		public function screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show per page', 'textdomain' ),
						'default' => 10,
						'option'  => 'option_name_per_page' // Related to PLUGIN_TABLE()
						);
			add_screen_option( $option, $args );
			$this->table = new Table(); // Source /lib/table.php
		}


		/**
		 * Menu page callback
		 *
		 * @return Html
		 */
		public function menu_page_callback() { ?>

			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<br class="clear">
				<?php settings_errors();

					/**
					 * Following is the settings form
					 */ ?>
					<form method="post" action="">
						<?php settings_fields("settings_id");
						do_settings_sections("settings_name");
						submit_button( __( 'Save', 'textdomain' ), 'primary', 'id' ); ?>
					</form>

					<?php
					/**
					 * Following is the data table class
					 */ ?>
					<form method="post" action="">
					<?php
						$this->table->prepare_items();
						$this->table->display(); ?>
					</form>
				<br class="clear">
			</div>
		<?php
		}


		/**
		 * Add help tabs using help data
		 *
		 * @return Void
		 */
		public function help_tabs() {

			foreach ($this->helpData as $value) {
				if ($_GET['page'] == $value['slug']) {
					$this->screen = get_current_screen();
					foreach( $value['info'] as $key ) {
						$this->screen->add_help_tab( $key );
					}
					$this->screen->set_help_sidebar( $value['link'] );
				}
			}
		}


		/**
		 * Add different types of settings and corrosponding sections
		 *
		 * @return Void
		 */
		public function add_settings() {

			add_settings_section( 'settings_id', __( 'Section Name', 'textdomain' ), array( $this,'section_cb' ), 'settings_name' );

			register_setting( 'settings_id', 'settings_field_name' );
			add_settings_field( 'settings_field_name', __( 'Field Name', 'textdomain' ), array( $this, 'settings_field_cb' ), 'settings_name', 'settings_id' );
		}


		/**
		 * Section description
		 *
		 * @return Html
		 */
		public function section_cb() {

			echo '<p class="description">' . __( 'Set up settings', 'textdomain' ) . '</p>';
		}


		/**
		 * Field explanation
		 *
		 * @return Html
		 */
		public function settings_field_cb() {

			//Choose any one from input, textarea, select or checkbox
			/**
			echo '<input type="text" class="medium-text" name="settings_field_name" id="settings_field_name" value="' . get_option('settings_field_name') . '" placeholder="' . __( 'Enter Value', 'textdomain' ) . '" required />';
			echo '<textarea name="settings_field_name" id="settings_field_name" value="' . get_option('settings_field_name') . '>'. __( 'Enter Value', 'textdomain' ) . '</textarea>';
			echo '<select name="settings_field_name" id="settings_field_name"><option value="value" ' . selected( 'value', get_option('settings_field_name'), false) . '>Value</option></select>';
			echo '<input type="checkbox" id="settings_field_name" name="settings_field_name" value="1"' . checked( 1, get_option('settings_field_name'), false ) . '/>';
			*/
		}
	}
} ?>
