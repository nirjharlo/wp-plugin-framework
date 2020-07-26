<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Build a sample metabox in editor screen
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'PLUGIN_METABOX' ) ) {

	final class PLUGIN_METABOX {


		/**
		 * Add Meetabox
		 *
		 * @return Void
		 */
		public function __construct() {

			//Adding the metabox. For custom post type use "add_meta_boxes_posttype" action
			add_action( 'add_meta_boxes', array( $this, 'register' ) );
			add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		}


		/**
		 * Metabox callback
		 *
		 * @return Html
		 */
		public function register() {

			add_meta_box(
				'meta-box-id',
				esc_html__( 'MetaBox Title', 'textdomain' ),
				array( $this, 'render' ),
				// Declare the post type to show meta box
				'post_type',
				'normal',
				'core'
			);
		}


		/**
		 * Metabox render HTML
		 *
		 * @return Html
		 */
		public function render() {

			wp_nonce_field( basename( __FILE__ ), 'metabox_name_nonce' ); ?>

			<p>
				<label for="metabox_name"><?php _e( "Custom Text", 'textdomain' ); ?></label>
    			<br />
    			<input class="widefat" type="text" name="metabox_field_name" id="metabox_field_name" value="<?php echo esc_attr( get_post_meta( $object->ID, 'metabox_name', true ) ); ?>" />
  			</p>
  			<?php
		}


		/**
 	    * Save the Metabox post data
 		*
 		* @param Array $atts
 		*
 		* @return Html
 		*/
		function save( $post_id, $post ) {

			//Check if doing autosave
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

			//Verify the nonce before proceeding.
			if ( !isset( $_POST['metabox_name_nonce'] ) || !wp_verify_nonce( $_POST['metabox_name_nonce'], basename( __FILE__ ) ) ) return;

			//Get the post type object.
			$post_type = get_post_type_object( $post->post_type );

			//Check if the current user has permission to edit the post.
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;

			if ( isset( $_POST['metabox_field_name'] ) ) {
				update_post_meta( $post_id, 'metabox_field_name', sanitize_text_field($_POST['metabox_field_name']) );
			}
		}
	}
} ?>
