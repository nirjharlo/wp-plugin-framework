<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Backend settings page class, can have settings fields or data table
 */
if ( ! class_exists( 'PLUGIN_SETTINGS' ) ) {

	final class PLUGIN_SETTINGS {

		public $capability;
		public $menuPage;
		public $subMenuPage;
		public $help;
		public $screen;



		// Add basic actions for menu and settings
		public function __construct() {

			$this->capability = 'manage_options';
			$this->menuPage = array( 'name' => '', 'heading' => '', 'slug' => '' );
			$this->subMenuPage = array(
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



		// Add a sample main menu page callback
		public function menu_page() {

			if ($this->menuPage) {
				add_menu_page(
					$this->menuPage['name'],
					$this->menuPage['heading'],
					$this->capability,
					$this->menuPage['slug'],
					array( $this, 'menu_page_callback' )
				);
			}
		}



		//Add a sample Submenu page callback
		public function sub_menu_page() {

			if ($this->subMenuPage) {
				$hook = add_submenu_page(
							$this->subMenuPage['parent_slug'],
							$this->subMenuPage['name'],
							$this->subMenuPage['heading'],
							$this->capability,
							// For the first submenu page, slug should be same as menupage.
							$this->subMenuPage['slug'],
							// For the first submenu page, callback should be same as menupage.
							array( $this, 'menu_page_callback' )
						);
					if ($this->subMenuPage['help']) {
						add_action( 'load-' . $hook, array( $this, 'help_tabs' ) );
					}
					if ($this->subMenuPage['screen']) {
						add_action( 'load-' . $hook, array( $this, 'screen_option' ) );
					}
				}
			}



		//Set screen option
		public function set_screen($status, $option, $value) {

    		if ( 'option_name_per_page' == $option ) return $value; // Related to PLUGIN_TABLE()
    			//return $status;
		}



		//Set screen option for Items table
		public function screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show per page', '' ),
						'default' => 10,
						'option'  => 'option_name_per_page' // Related to PLUGIN_TABLE()
						);
			add_screen_option( $option, $args );
			$this->Table = new PLUGIN_TABLE();
		}



		// Menu page callback
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
					<?php // Source /lib/table.php
						$table = new PLUGIN_TABLE();
						$table->prepare_items();
						$table->display(); ?>
					</form>
				<br class="clear">
			</div>
		<?php
		}



		// Add help tabs using help data
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



		//Add different types of settings and corrosponding sections
		public function add_settings() {

			add_settings_section( 'settings_id', __( 'Section Name', 'textdomain' ), array( $this,'section_cb' ), 'settings_name' );

			register_setting( 'settings_id', 'settings_field_name' );
			add_settings_field( 'settings_field_name', __( 'Field Name', 'textdomain' ), array( $this, 'settings_field_cb' ), 'settings_name', 'settings_id' );
		}



		//Section description
		public function section_cb() {

			echo '<p class="description">' . __( 'Set up settings', 'textdomain' ) . '</p>';
		}



		//Field explanation
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
