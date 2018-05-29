<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin upload for WordPress front end or backend
 * 
 * 
 */
if ( ! class_exists( 'PLUGIN_UPLOAD' ) ) {

	final class PLUGIN_UPLOAD extends WP_WIDGET {



		// Add basic form
		public function __construct() {

			if ( isset($_POST['UploadSubmit']) ) {
				$this->upload();
			}

			$this->form();
		}



		// Outputs the content of the widget
		public function form() { ?>

			<form method="POST" action="" enctype="multipart/form-data">
				<input name="UploadFile" type="file" multiple="false"/>
				<?php submit_button( __( 'Upload', 'stv' ), 'secondary', 'UploadSubmit' ); ?>
			</form>
			<?php
		}



		// Manage the Upload file
		public function upload() {

			$file = $_FILES['UploadFile'];
			$type = $file['type'];

			// Check in your file type
			if( $type != 'application/_TYPE_' ) {
				add_action( 'admin_notices', array( $this, 'file_type_error_admin_notice' ) );
			} else {

				if (!function_exists('wp_handle_upload')){
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					require_once(ABSPATH . 'wp-admin/includes/file.php');
					require_once(ABSPATH . 'wp-admin/includes/media.php');
				}

				$overrides = array( 'test_form' => false);
				$attachment_id = wp_handle_upload($file, $overrides);

				if( is_array( $attachment_id ) && array_key_exists( 'url', $attachment_id ) ) {
					$upload_id = $attachment_id['url'];

					add_action( 'admin_notices', array( $this, 'success_notice' ) );

					// Use $upload_id for any purpose. For example storing temporarily
					//update_option('some_token', $upload_id);
				} else {
					add_action( 'admin_notices', array( $this, 'file_error_admin_notice' ) );
					$upload_id = false;
				}
			}
		}


		// Notify wrong file type
		public function file_type_error_admin_notice() { ?>

			<div class="notice notice-success is-dismissible">
				<p><?php _e( 'Please Upload correct type of file only.', 'textdomain' ); ?></p>
 			</div>
		<?php
		}


		// Notify error in upload process
		public function file_error_admin_notice() { ?>

			<div class="notice notice-success is-dismissible">
				<p><?php _e( 'File Upload failed.', 'textdomain' ); ?></p>
 			</div>
		<?php
		}


		// Notify on success
		public function success_notice() { ?>

			<div class="notice notice-success is-dismissible">
				<p><?php _e( 'Successfully saved file details.', 'textdomain' ); ?></p>
 			</div>
		<?php
		}
	}
} ?>