<?php
namespace NirjharLo\WP_Plugin_Framework\Engine\Init;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Implimentation of WordPress inbuilt functions for plugin activation.
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */

	final class Install {


		/**
		 * @var String
		 */
		public static $textDomain;


		/**
		 * @var String
		 */
		public static $phpVerAllowed;


		/**
		 * @var Array
		 */
		public static $pluginPageLinks;


		/**
		 * Execute plugin setup
		 *
		 * @return Void
		 */
		public static function execute() {

			add_action( 'plugins_loaded', array( __CLASS__, 'textDomainCb' ) );
			add_action( 'admin_notices', array( __CLASS__, 'phpVerIncompatibleCb' ) );
			add_filter( 'plugin_action_links', array( __CLASS__, 'menuPageLinkCb' ), 10, 2 );
		}


		/**
		 * Load plugin textdomain
		 *
		 * @return Void
		 */
		public static function textDomainCb() {

			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, self::$textDomain );

			unload_textdomain( self::$textDomain );
			load_textdomain( self::$textDomain, PLUGIN_LN . 'textdomain-' . $locale . '.mo' );
			load_plugin_textdomain( self::$textDomain, false, PLUGIN_LN );
		}


		/**
		 * Define low php verson errors
		 *
		 * @return String
		 */
		public static function phpVerIncompatibleCb() {

			if ( version_compare( phpversion(), self::$phpVerAllowed, '<' ) ) :
				$text      = __( 'The Plugin can\'t be activated because your PHP version', 'textdomain' );
				$text_last = __( 'is less than required ' . self::$phpVerAllowed . '. See more information', 'textdomain' );
				$text_link = 'php.net/eol.php'; ?>

				<div id="message" class="updated notice notice-success is-dismissible">
					<p><?php echo $text . ' ' . phpversion() . ' ' . $text_last . ': '; ?>
						<a href="http://php.net/eol.php/" target="_blank"><?php echo $text_link; ?></a>
					</p>
				</div>

			<?php endif;
			return;
		}


		/**
		 * Add settings link to plugin page
		 *
		 * @param Array  $links
		 * @param String $file
		 *
		 * @return Array
		 */
		public static function menuPageLinkCb( $links, $file ) {

			if ( self::$pluginPageLinks ) {
				static $this_plugin;
				if ( ! $this_plugin ) {
					$this_plugin = PLUGIN_FILE;
				}
				if ( $file == $this_plugin ) {
					$shift_link = array();
					foreach ( self::$pluginPageLinks as $value ) {
						$shift_link[] = '<a href="' . $value['slug'] . '">' . $value['label'] . '</a>';
					}
					foreach ( $shift_link as $val ) {
						array_unshift( $links, $val );
					}
				}
				return $links;
			}
		}
	}
