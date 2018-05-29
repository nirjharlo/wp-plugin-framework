<?php
/**
 * Doing AJAX the WordPress way.
 * Use this class in admin or user side
 */
if ( ! defined( 'ABSPATH' ) ) exit;

//AJAX helper class
if ( ! class_exists( 'PLUGIN_AJAX' ) ) {

	final class PLUGIN_AJAX {


		// Add basic actions
		public function __construct() {

			//Adding the AJAX
			add_action( 'admin_footer', array( $this, 'customName_js' ) );
			add_action( 'wp_ajax_customName', array( $this, 'customName' ) );
			add_action( 'wp_ajax_nopriv_customName', array( $this, 'customName' ) );
		}



		//Output the form
		public function form() { ?>

			<form id="addByAjax" method="POST" action="">
				<input type="text" name="textName" placeholder="<?php _e( 'Text', 'textdomain' ); ?>">
				<input id="AjaxSubmit" type="submit" name="submit" value="Submit">
			</form>
			<?php
		}



		//The javascript
		public function customName_js() { ?>

			<script type="text/javascript">
				jQuery(document).ready(function() {

					jQuery("#addByAjax form").submit(function() {

						event.preventDefault();

						var val = jQuery("input[name='textName']").val();

							jQuery.post(
								<?php if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { ?>
									'<?php echo admin_url("admin-ajax.php", "https"); ?>',
								<?php } else { ?>
									'<?php echo admin_url("admin-ajax.php"); ?>',
								<?php } ?>
								{ 'action': 'customName', 'val': val },
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



		//The data processor
		public function customName() {

			$val = $_POST['val'];

			// DO some stuff
			 
			$response = array( 'val' => $value );
			echo json_encode( $response );
			wp_die();
		}
	}
} ?>