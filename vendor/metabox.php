<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'PLUGIN_METABOX' ) ) {

	final class PLUGIN_METABOX {



		public function __construct() {

			//Adding the metabox
			add_action( 'add_meta_boxes', array( $this, 'register' ) );
			add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		}



		public function register() {

			add_meta_box(
				'meta-box-id',
				esc_html__( 'MetaBox Title', 'textdomain' ),
				array( $this, 'render' ),
				'nav-menus',
				'normal',
				'core'
			);
		}




		public function render() {

			<?php wp_nonce_field( basename( __FILE__ ), 'metaBoxName_nonce' ); ?>

			<p>
				<label for="metaBoxName"><?php _e( "Custom Text", 'myPlugintextDomain' ); ?></label>
    			<br />
    			<input class="widefat" type="text" name="metaBoxFieldName" id="metaBoxFieldName" value="<?php echo esc_attr( get_post_meta( $object->ID, 'metaBoxName', true ) ); ?>" />
  			</p>
  			<?php
		}



		//Save the post data
		function save( $post_id, $post ) {

			//Verify the nonce before proceeding.
			if ( !isset( $_POST['metaBoxName_nonce'] ) || !wp_verify_nonce( $_POST['metaBoxName_nonce'], basename( __FILE__ ) ) )
				return $post_id;

			//Get the post type object.
			$post_type = get_post_type_object( $post->post_type );

			//Check if the current user has permission to edit the post.
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;

			//Get the posted data and sanitize it for use as an HTML class.
			$new_meta_value = ( isset( $_POST['metaBoxName'] ) ? sanitize_html_class( $_POST['metaBoxName'] ) : '' );

			//Get the meta key.
			$meta_key = 'metaBoxName';

			//Get the meta value of the custom field key.
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			//If a new meta value was added and there was no previous value, add it.
			if ( $new_meta_value && '' == $meta_value ) {
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );
			} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
				update_post_meta( $post_id, $meta_key, $new_meta_value );
			} elseif ( '' == $new_meta_value && $meta_value ) {
				delete_post_meta( $post_id, $meta_key, $meta_value );
			}
		}
	}
} ?>