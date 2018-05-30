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
			add_action( 'admin_menu', array( $this, 'add_settings' ) );
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
						<?php settings_fields("addPdfsId");
						do_settings_sections("addPdfs");
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

			add_settings_section( 'SettingsId', __( 'Section Name', 'textdomain' ), array( $this,'SectionCb' ), 'SettingsName' );
			register_setting( 'SettingsId', 'SettingsField' );
			add_settings_field( 'SettingsFieldName', __( 'Field Name', 'textdomain' ), array( $this, 'SettingsFieldCb' ), 'SettingsName', 'SettingsId' );
		}



		//Section description
		public function SectionCb() {

			echo '<p class="description">' . __( 'Set up settings', 'textdomain' ) . '</p>';
		}



		//Field explanation
		public function SettingsFieldCb() {

			echo '<input type="text" class="medium-text" name="SettingsFieldName" id="SettingsFieldName" value="' . get_option('SettingsFieldName') . '" placeholder="' . __( 'Enter Value', 'textdomain' ) . '" required />';
		}
	}
} ?>