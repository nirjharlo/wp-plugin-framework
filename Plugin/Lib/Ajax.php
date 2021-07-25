<?php
namespace NirjharLo\WP_Plugin_Framework\Lib;

/**
 * Doing AJAX the WordPress way.
 * Use this class in admin or user side
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// AJAX helper class
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Lib\\Ajax' ) ) {

	final class Ajax {


		/**
		 * Add basic actions
		 *
		 * @return Void
		 */
		public function __construct() {

			add_action( 'wp_footer', array( $this, 'custom_name_js' ) );
			add_action( 'wp_ajax_custom_name', array( $this, 'custom_name' ) );
			add_action( 'wp_ajax_nopriv_custom_name', array( $this, 'custom_name' ) );
		}


		/**
		 * Output the form
		 *
		 * @return Html
		 */
		public function form() { ?>

			<form id="add_by_ajax" method="POST" action="">
				<input type="text" name="text_name" placeholder="<?php esc_attr_e( 'Text', 'textdomain' ); ?>">
				<input id="ajax_submit" type="submit" name="submit" value="Submit">
			</form>
			<?php
		}


		/**
		 * The data processor
		 *
		 * @return Json
		 */
		public function custom_name() {

			$val = $_POST['val'];

			// DO some stuff

			$response = array( 'val' => $value );
			echo json_encode( $response );
			wp_die();
		}
	}
} ?>
