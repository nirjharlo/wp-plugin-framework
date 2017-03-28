<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Implimentation of WordPress inbuilt functions for creating a settings page class.
if ( ! class_exists( 'PLUGIN_SETTINGS' ) ) {

	final class PLUGIN_SETTINGS {

		//Decalre following before calling create()
		/**
		$capability = 'manage_options';
		*/
		public $capability;
		//$menuPage = array( 'name' => '', 'heading' => '', 'slug' => '' );
		public $menuPage;
		/**
		$subMenuPage = array(
							array( 'name' => '', 'heading' => '', 'slug' => '', 'parent' => '', 'help' => true/false, 'screen' => true/false ) );
		*/
		public $subMenuPage;
		/**
		$help = array( array( 'slug' => '', 'help' => array(
					'info' => array(
								array(
									'id' => 'helpId',
									'title' => __( 'API Settings', 'InLinkMaster' ),
									'content' => __( 'helpDescription', 'InLinkMaster' ),
								),
							),
					'link' => '<p><a href="#">' . __( 'helpLink', 'InLinkMaster' ) . '</a></p>',
			) ) );
		*/
		public $help;
		/**
		$screen = ''
		*/
		public $screen;



		// Add basic actions for menu and settings
		public function __construct() {

			$this->capability = ($capability ? $capability : 'manage_options' );
			$this->ajax = new PLUGIN_AJAX();

			//Set the screen option
			//add_action( 'admin_menu', array( $this, 'add_settings' ) );
			//add_action( 'admin_head', array( $this, 'table_css' ) );
		}



		//Add the menu, submenu pages.
		public function execute() {

			if ($this->menuPage) {
				add_action( 'admin_menu', array( $this, 'menu_page' ) );
			}
			if ($this->subMenuPage) {
				foreach ($this->subMenuPage as $page) {
					add_action( 'admin_menu', array( $this, 'sub_menu_page' ) );
				}
			}
			if ($this->screen) {
				add_filter( 'set-screen-option', array( $this, 'set_screen' ), 10, 3 );
			}
		}



		// Add a sample main menu page callback
		public function menu_page() {

			if ($this->menuPage) {
				add_menu_page(
					$this->menuPage['name'],
					$this->menuPage['heading'],
					$this->capability,
					$this->menuPage['slug'],
					function() { ?>
						<div class="wrap">
							<h1><?php echo get_admin_page_title(); ?> <a href="#" class="hide-if-no-js page-title-action" id="addNewUrlSet"><?php _e( 'Add New', 'InLinkMaster' ); ?></a> </h1>
							<br class="clear">
							<?php settings_errors(); ?>
						</div>
					<?php
					}
				);
			}
		}



		//Add a sample Submenu page callback
		public function sub_menu_page() {

			if ($this->subMenuPage) {
				foreach ($this->subMenuPage as $page) {
					$hook = add_submenu_page(
								$this->subMenuPage['parent'],
								$this->subMenuPage['name'],
								$this->subMenuPage['heading'],
								$this->capability,
								$this->subMenuPage['slug'],
								function() { ?>
									<div class="wrap">
										<h1><?php echo get_admin_page_title(); ?></h1>
										<br class="clear">
										<?php settings_errors(); ?>
									</div>
								<?php
								}
							);
					if ($this->subMenuPage['help']) {
						add_action( 'load-' . $hook, array( $this, 'help_tabs' ) );
					}
					if ($this->subMenuPage['screen']) {
						add_action( 'load-' . $hook, array( $this, 'screen_option' ) );
					}
				}
			}
		}



		//Set screen option
		function set_screen($status, $option, $value) {
 
    		if ( 'link_group_per_page' == $option ) return $value;
    			return $status; 
		}



		//Set screen option for Links table
		public function screen_option() {

			$option = 'per_page';
			$args   = array(
						'label'   => __( 'Show per page', '' ),
						'default' => 10,
						'option'  => 'link_group_per_page'
						);
			add_screen_option( $option, $args );
			$this->internalLinkTable = new PLUGIN_TABLE();
		}



		// Add help tabs using help data
		public function help_tabs() {

			foreach ($this->help as $value) {
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

			add_settings_section( 'ApiSettingsId', __( 'API Configuration', 'InLinkMaster' ), array( $this,'ApiSettingsCb' ), 'ApiSettingsName' );

			register_setting( 'ApiSettingsId', 'InternalLinkApiKeyField', 'AfterSaveAPI' );
			add_settings_field( 'InternalLinkApiKeyName', __( 'Access ID', 'InLinkMaster' ), array( $this, 'InternalLinkApiKeyFieldCb' ), 'ApiSettingsName', 'ApiSettingsId' );

			register_setting( 'ApiSettingsId', 'InternalLinkApiPassField', 'AfterSaveAPI' );
			add_settings_field( 'InternalLinkApiPassName', __( 'Secrect Key', 'InLinkMaster' ), array( $this, 'InternalLinkApiPassFieldCb' ), 'ApiSettingsName', 'ApiSettingsId' );
		}



		//Section description
		public function InternalLinkCb() {

			echo '<p class="description">' . __( 'Set up internal linking', 'InLinkMaster' ) . '</p>';
		}


		//Section description
		public function ApiSettingsCb() {

			echo '<p class="description">' . __( 'Set up your MOZ API credentials', 'InLinkMaster' ) . '</p>';
		}



		//Field explanation
		public function InternalLinkApiKeyFieldCb() {

			echo '<input type="text" class="medium-text" name="InternalLinkApiKeyField" id="InternalLinkApiKeyField" value="' . get_option('InternalLinkApiKeyField') . '" placeholder="' . __( 'Enter Access Id', 'InLinkMaster' ) . '" required />';
		}



		//Field explanation
		public function InternalLinkApiPassFieldCb() {

			echo '<input type="password" class="regular-text" name="InternalLinkApiPassField" id="InternalLinkApiPassField" value="' . get_option('InternalLinkApiPassField') . '" placeholder="' . __( 'Enter Secrect Key', 'InLinkMaster' ) . '" required />';
		}
	}
} ?>