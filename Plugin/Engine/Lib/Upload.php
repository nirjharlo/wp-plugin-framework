<?php
namespace NirjharLo\WP_Plugin_Framework\Engine\Lib;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin upload for WordPress front end or backend
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Lib\\Upload' ) ) {

	final class Upload {


		/**
		 * Add basic form
		 *
		 * @return Void
		 */
		public function __construct() {

			if ( isset( $_POST['UploadSubmit'] ) ) {
				$this->upload_controller();
			}

			$this->upload_form();
		}


		/**
		 * Outputs the content of the widget
		 *
		 * @return Html
		 */
		private function upload_form() { ?>

			<form method="POST" action="" enctype="multipart/form-data">
				<input name="UploadFile" type="file" multiple="false"/>
				<?php submit_button( __( 'Upload', 'stv' ), 'secondary', 'UploadSubmit' ); ?>
			</form>
			<?php
		}


		/**
		 * Manage the Upload file
		 *
		 * @return Void
		 */
		private function upload_controller() {

			$file = $_FILES['UploadFile'];
			$type = $file['type'];

			// Check in your file type
			if ( $type != 'application/_TYPE_' ) {
				add_action( 'admin_notices', array( $this, 'file_type_error_admin_notice' ) );
			} else {

				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once ABSPATH . 'wp-admin/includes/image.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/media.php';
				}

				$overrides  = array( 'test_form' => false );
				$attachment = wp_handle_upload( $file, $overrides );

				if ( is_array( $attachment_id ) && array_key_exists( 'path', $attachment ) ) {
					$upload_path = $attachment['path'];

					add_action( 'admin_notices', array( $this, 'success_notice' ) );

					// Use $upload_path for any purpose. For example storing temporarily
					// update_option( 'some_token', $upload_path );
				} else {
					add_action( 'admin_notices', array( $this, 'file_error_admin_notice' ) );
					$upload_path = false;
				}
			}
		}


		/**
		 * Notify wrong file type
		 *
		 * @return Html
		 */
		private function file_type_error_admin_notice() {
			?>

			<div class="notice notice-error is-dismissible">
				<p><?php esc_attr_e( 'Please Upload correct type of file only.', 'textdomain' ); ?></p>
			 </div>
			<?php
		}


		/**
		 * Notify error in upload process
		 *
		 * @return Html
		 */
		private function file_error_admin_notice() {
			?>

			<div class="notice notice-error is-dismissible">
				<p><?php esc_attr_e( 'File Upload failed.', 'textdomain' ); ?></p>
			 </div>
			<?php
		}


		/**
		 * Notify on success
		 *
		 * @return Html
		 */
		private function success_notice() {
			?>

			<div class="notice notice-success is-dismissible">
				<p><?php esc_attr_e( 'Successfully saved file details.', 'textdomain' ); ?></p>
			 </div>
			<?php
		}
	}
} ?>
