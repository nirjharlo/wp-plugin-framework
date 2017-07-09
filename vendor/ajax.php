<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//AJAX helper class
if ( ! class_exists( 'INTERNAL_LINK_MASTER_AJAX' ) ) {

	final class INTERNAL_LINK_MASTER_AJAX {



		// Add basic actions
		public function __construct() {

			$this->table = 'internal_link_master';

			//Adding the AJAX
			add_action( 'admin_footer', array( $this, 'addLink_js' ) );
			add_action( 'wp_ajax_addLink', array( $this, 'addLink' ) );
			add_action( 'wp_ajax_nopriv_addLink', array( $this, 'addLink' ) );
		}



		//Output the form
		public function form() { ?>

			<form method="POST" action="">
					<table class="widefat">
						<tr>
							<td style="width: 35%">
								<input type="url" name="hasUrl" class="regular-text" placeholder="<?php _e( 'Enter Has URL', 'InLinkMaster' ); ?>">
							</td>
							<td style="width: 35%">
								<input type="url" name="needUrl" class="regular-text" placeholder="<?php _e( 'Enter Need URL', 'InLinkMaster' ); ?>">
							</td>
							<td style="width: 20%">
								<input id="addByAjaxSubmit" type="submit" name="submit" value="Add URL" class="button-primary">
							</td>
							<td style="width: 10%">
								<span id="addByAjaxNotice"></span>
							</td>
						</tr>
					</table>
				</form>
			<?php
		}



		//The javascript
		public function addLink_js() { ?>

			<script type="text/javascript">
				jQuery(document).ready(function() {

					jQuery("#addByAjax").hide();
					jQuery("#addNewUrlSet").click(function(){
						jQuery("#addByAjax").toggle();
					});

					jQuery("#addByAjaxNotice").text("");

					jQuery("#addByAjax form").submit(function() {

						jQuery("#addByAjaxNotice").text("");

						event.preventDefault();

						var hasUrl = jQuery("input[name='hasUrl']").val();
						var needUrl = jQuery("input[name='needUrl']").val();

						if ( hasUrl != '' && needUrl != '' ) {
							jQuery("#addByAjaxSubmit").val("Adding ...").attr("disabled", "disabled");

							jQuery.post(
								<?php if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { ?>
									'<?php echo admin_url("admin-ajax.php", "https"); ?>',
								<?php } else { ?>
									'<?php echo admin_url("admin-ajax.php"); ?>',
								<?php } ?>
								{ 'action': 'addLink', 'hasUrl': hasUrl, 'needUrl': needUrl },
								function(response) {
									if ( response != '' && response != false && response != undefined ) {
										var data = JSON.parse(response);
										if (data.ping == 'true') {
											var newTableRow = '<tr><th scope="row" class="check-column"><input type="checkbox" name="bulk-select[]" value="' + data.id + '"></th><td>' + data.has_url + '</td><td>' + data.has_pa + '</td><td>' + data.need_url + '</td><td>' + data.need_pa + '</td></tr>';
											jQuery("#the-list .no-items").remove();
											jQuery("#the-list").append(newTableRow);
											jQuery("#addByAjaxSubmit").val("Add Another").removeAttr("disabled");
											jQuery("input[name='hasUrl'], input[name='needUrl']").val('');
											jQuery("#addByAjaxNotice").text("Added");
										} else if (data.ping == 'false') {
											jQuery("#addByAjaxSubmit").val("Try Again").removeAttr("disabled");
											jQuery("#addByAjaxNotice").text("Can't Add");
										}

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
		public function addLink() {

			$hasUrl = esc_url( $_POST['hasUrl'] );
			$needUrl = esc_url( $_POST['needUrl'] );

			$hasUrlPa = $this->APICall($hasUrl);
			$needUrlPa = $this->APICall($needUrl);

			global $wpdb;
			$wpdb->suppress_errors = true;
			$value = $wpdb->insert( $wpdb->prefix. $this->table, array( 'has_pa_url' => $hasUrl, 'has_pa' => $hasUrlPa, 'need_pa_url' => $needUrl, 'need_pa' => $needUrlPa ), array( '%s', '%s', '%s', '%s' ) );

			if ($value) {
				$hasUrlParsed = parse_url($hasUrl);
				$needUrlParsed = parse_url($needUrl);
				echo json_encode(
						array(
							'ping' => 'true',
							'id' => $value,
							'has_url' => (array_key_exists('path', $hasUrlParsed) ? $hasUrlParsed['path'] : '/'),
							'has_pa' => $hasUrlPa,
							'need_url' => (array_key_exists('path', $needUrlParsed) ? $needUrlParsed['path'] : '/'),
							'need_pa' => $needUrlPa
						));
			} else {
				echo json_encode(array('ping' => 'false'));
			}
			wp_die();
		}



		//API call
		public function APICall($url) {

			$api = new INTERNAL_LINK_MASTER_API();
			$api->objectURL = $url;
			$api->prepare();
			$data = $api->call();
			$parsed = $api->parse($data);
			return (array_key_exists('upa', $parsed) ? round($parsed['upa'], 2) : 0);
		}
	}
} ?>