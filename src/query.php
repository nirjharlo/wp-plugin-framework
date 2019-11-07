<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * WP Query class for querying WP database
 * For more reference of arguments visit: https://gist.github.com/nirjharlo/5c6f8ac4cc5271f88376788e599c287b
 * This class depends on WP pagenavi plugin: https://wordpress.org/plugins/wp-pagenavi/
 */
if ( ! class_exists( 'PLUGIN_QUERY' ) ) {

	class PLUGIN_QUERY {

		public $display_count = NULL;

		public function __construct() {

			global $post, $ga_customers, $user;

			$this->display_count = get_option('posts_per_page', 10);

			$paged = get_query_var('paged') ?
			get_query_var('paged')
			 : (get_query_var('page') ?
			  	get_query_var('page') : 1);

			$args = $this->user_args($paged); // OR $this->post_args($paged)

			$the_query = new WP_Query( $args );

			// The Loop
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
      		$the_query->the_post();
	  			// Do Stuff
				} // end while
			} // endif

			if (function_exists('wp_pagenavi'))  {
				wp_pagenavi( array( 'query' => $the_query, 'echo' => true ) );//For user query add param 'type' => 'users'
			}

			// Reset Post Data
			wp_reset_postdata();
		}


		/**
		 *
		 */
		public function user_args($paged) {

			$offset = ( $paged - 1 ) * $this->display_count;

			$args = array (
				'role' => '', // user role
				'order' => '', //ASC or DESC
				'fields' => '', //
				'orderby' => '',
				'role__in' => '',
				'role__not_in'=> '',
				'include' => '',
				'exclude' => '',
				'blog_id' => '',
				'taxonomy' => '',
				'terms' => '',
				'number' => $this->display_count,
				'count_total' => true,
				'paged' => $paged,
				'offset' =>  $offset,
				'search' => '*'.esc_attr( $_GET['search'] ).'*',
				'meta_query' => array( // It supports nested meta query
					'relation' => 'AND', //or 'OR'
					array(
						'key'     => 'meta_key',
						'value'   => 'some_value',
						'compare' => 'LIKE' //like others
					),
					array(
						'key'     => 'meta_key',
						'value'   => 'some_value',
						'compare' => 'LIKE' //like others
					)
				)
			);

			return $args;
		}


		/**
		 *
		 */
		public function post_args($paged) {

			$offset = ( $paged - 1 ) * $this->display_count;

			$args = array (
				'post_type' => '', // array of type slugs
				'order' => 'ASC',
				'fields' => '', //
				'orderby' => '',
				'include' => '',
				'exclude' => '',
				'blog_id' => '',
				'taxonomy' => '',
				'terms' => '',
				'number' => $this->display_count,
				'count_total' => true,
				'paged' => $paged,
				'offset' =>  $offset,
				'search' => '*'.esc_attr( $_GET['search'] ).'*',
				'meta_query' => array( // It supports nested meta query
					'relation' => 'AND', //or 'OR'
					array(
						'key'     => 'meta_key',
						'value'   => 'some_value',
						'compare' => 'LIKE' //like others
					),
					array(
						'key'     => 'meta_key',
						'value'   => 'some_value',
						'compare' => 'LIKE' //like others
					)
				)
			);

			return $args;
		}
	}
} ?>
