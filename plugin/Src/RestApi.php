<?php
namespace NirjharLo\WP_Plugin_Framework\Src;

use WP_REST_Controller as WP_REST_Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extending REST API framework of WordPress
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Src\\RestApi' ) ) {

	class RestApi extends WP_REST_Controller {


		/**
		 * REST API routes
		 *
		 * @return Object
		 */
		public function register_routes() {

			$version   = '1';
			$namespace = 'vendor/v' . $version;
			$base      = 'route';

			// Available options for methods are CREATABLE, READABLE, EDITABLE, DELETABLE
			register_rest_route(
				$namespace,
				'/' . $base,
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'callback' ),
					'permission_callback' => array( $this, 'permission' ),
					'args'                => array( 'sample', 'list', 'of', 'args' ),
				)
			);
		}


		/**
		 * The request handler
		 *
		 * @return Object
		 */
		protected function callback() {

			$params = $request->get_params();
			$items  = array();
			$data   = $this->prepare_item_for_response( $items, $request );

			if ( $data ) {
				return new WP_REST_Response( $data, 200 );
			} else {
				return new WP_Error( 'status_code', __( 'message', 'text-domain' ) );
			}
		}


		/**
		 * Prevent unauthorized access here
		 *
		 * @return Bool
		 */
		protected function permission() {

			return current_user_can( 'manage_options' );
		}


		/**
		 * Processing of request takes place here
		 *
		 * @param Array
		 * @param Object
		 *
		 * @return Array
		 */
		public function prepare_item_for_response( $items, $request ) {

			// Process the data in any way you like
			$data = compact( $items, $request );
			return $data;
		}
	}
}
