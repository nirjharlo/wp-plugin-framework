<?php
/**
 * Doing AJAX the WordPress way.
 * Use this class in admin or user side
 *
 * @author     Nirjhar Lo
 * @version    1.2.1
 * @package    wp-plugin-framework
 */
if ( ! defined( 'ABSPATH' ) ) exit;

//AJAX helper class
if ( ! class_exists( 'PLUGIN_AJAX' ) ) {

	final class PLUGIN_AJAX {


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
				<input type="text" name="text_name" placeholder="<?php _e( 'Text', 'textdomain' ); ?>">
				<input id="ajax_submit" type="submit" name="submit" value="Submit">
			</form>
			<?php
		}


		/**
		 * The javascript
		 *
		 * @return Html
		 */
		public function custom_name_js() { ?>

			<script type="text/javascript">
				jQuery(document).ready(function() {

					jQuery("#add_by_ajax form").submit(function() {

						event.preventDefault();

						var val = jQuery("input[name='text_name']").val();

							jQuery.post(
								'<?php echo admin_url("admin-ajax.php"); ?>',
								{ 'action': 'custom_name', 'val': val },
								function(response) {
									if ( response != '' && response != false && response != undefined ) {

										var data = JSON.parse(response);
										// Do some stuff
									}
								}
							);
						}
					});
				});
			</script>
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
