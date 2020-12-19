<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Custom post type class
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'PLUGIN_CPT' ) ) {

	class PLUGIN_CPT {

		/**
		 * @var Array
		 */
		private $labels;

		/**
		 * @var Array
		 */
		private $args;

		/**
		 * @var String
		 */
		private static $menu_svg = '';


		/**
		 * Integrate the shortcode
		 *
		 * @return Void
		 */
		public function __construct() {

			$this->labels = $this->labels();
			$this->args = $this->args( $this->labels );

			//register_post_type( 'cpt_name', $this->args );
			//add_filter( 'post_updated_messages', array( $this, 'group_updated_messages' ) );
		}


		/**
		 * Define the labels
		 *
		 * @return Array
		 */
		public function labels() {

	      $labels = array(
	        'name'                => _x( '', 'Post Type General Name', 'textdomain' ),
	        'singular_name'       => _x( '', 'Post Type Singular Name', 'textdomain' ),
	        'menu_name'           => __( '', 'textdomain' ),
	        'parent_item_colon'   => __( '', 'textdomain' ),
	        'all_items'           => __( '', 'textdomain' ),
	        'view_item'           => __( '', 'textdomain' ),
	        'add_new_item'        => __( '', 'textdomain' ),
	        'add_new'             => __( '', 'textdomain' ),
	        'edit_item'           => __( '', 'textdomain' ),
	        'update_item'         => __( '', 'textdomain' ),
	        'search_items'        => __( '', 'textdomain' ),
	        'not_found'           => __( '', 'textdomain' ),
	        'not_found_in_trash'  => __( '', 'textdomain' ),
	      );

	      return $labels;
	    }


		/**
		 * Define the arguments
		 *
		 * @param Array $labels
		 *
		 * @return Array
		 */
	    public function args( $labels ) {

	      $args = array(
	          'label'               => __( '', 'textdomain' ),
	          'description'         => __( '', 'textdomain' ),
	          'labels'              => $labels,
	          'supports'            => array( 'title', 'editor', 'thumbnail' ),
	          'taxonomies'          => array( 'topics', 'post_tag' ),
	          'hierarchical'        => true,
	          'public'              => true,
			  'rewrite'			  	=> array( 'slug' => 'slug_name' ),
	          'show_ui'             => true,
	          'show_in_menu'        => true,
			  'menu_icon' 			=> 'data:image/svg+xml;base64,' . self::$menu_svg,
	          'show_in_nav_menus'   => true,
	          'show_in_admin_bar'   => true,
	          'menu_position'       => 5,
	          'can_export'          => true,
	          'has_archive'         => true,
	          'exclude_from_search' => false,
	          'publicly_queryable'  => true,
	          'capability_type'     => 'post',
	          'show_in_rest'        => true,
			  //Controls WP REST API behaviour
			  'rest_controller_class' => 'WP_REST_Terms_Controller',
	      );

	      return $args;
	    }


		/**
	 	 * Modify the cpt messages
		 *
		 * @param Array $messages
		 *
		 * @return Array
	 	 */
		 public function cpt_updated_messages( $messages ) {

			 global $post, $post_ID;

			 $messages['cpt_name'] = array(
				 0 => '',
				 1 => sprintf( __( 'CPT updated. <a href="%s">View CPT</a>', 'textdomain' ), esc_url( get_permalink( $post_ID ) ) ),
				 2 => __( 'field updated.', 'textdomain' ),
				 3 => __( 'field deleted.', 'textdomain' ),
				 4 => __( 'CPT updated.', 'textdomain' ),
				 5 => ( isset( $_GET['revision'] ) ? sprintf( __( 'CPT restored to revision from %s', 'textdomain' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false ),
				 6 => sprintf( __( 'CPT published. <a href="%s">View Cpt</a>', 'textdomain' ), esc_url( get_permalink( $post_ID ) ) ),
				 7 => __( 'CPT saved.', 'textdomain' ),
				 8 => sprintf( __( 'CPT submitted. <a target="_blank" href="%s">Preview cpt</a>', 'textdomain' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
				 9 => sprintf( __( 'CPT scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview cpt</a>', 'textdomain' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
				 10 => sprintf( __( 'CPT draft updated. <a target="_blank" href="%s">Preview cpt</a>', 'textdomain' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			 );

			 return $messages;
		 }
	}
}
