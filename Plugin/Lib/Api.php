<?php
namespace NirjharLo\WP_Plugin_Framework\Lib;

/**
 * Implimentation of WordPress inbuilt API class
 *
 * Usage:
 *
 * $api = new \Api();
 * $api->endpoint = 'endpoint_url'
 * $api->header = array( "key: $val" )
 * $api->data_type = 'xml' or 'json'
 * $api->call_type = 'GET' or 'POST'
 * $api->call();
 * $data = $api->parse();
 *
 * @author     Nirjhar Lo
 * @package    wp-plugin-framework
 */
if ( ! class_exists( 'NirjharLo\\WP_Plugin_Framework\\Lib\\Api' ) ) {

	class Api {


		/**
		 * @var String
		 */
		public $endpoint;


		/**
		 * @var Array
		 */
		public $header;


		/**
		 * @var String
		 */
		public $data_type;


		/**
		 * @var String
		 */
		public $call_type;


		/**
		 * Define the properties inside a instance
		 *
		 * @return Void
		 */
		public function __construct() {

			$this->endpoint  = '';
			$this->header    = array();
			$this->data_type = ''; // xml or json
			$this->call_type = '';
		}


		/**
		 * Define the necessary database tables
		 *
		 * @return Array
		 */
		protected function build() {

			$args = array(
				CURLOPT_URL            => $this->endpoint,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => $this->call_type,
				CURLOPT_HTTPHEADER     => $this->header,
			);

			return $args;
		}


		/**
		 * Define the variables for db table creation
		 *
		 * @return Array
		 */
		public function call() {

			$curl = curl_init();

			curl_setopt_array( $curl, $this->build() );

			$result = curl_exec( $curl );
			$err    = curl_error( $curl );

			curl_close( $curl );

			if ( $err ) {
				$result = 'cURL Error #:' . $err;
			}

			return $result;
		}


		/**
		 * Check options and tables and output the info to check if db install is successful
		 *
		 * @return Array
		 */
		public function parse( $data ) {

			call_user_func( array( $this, $this->data_type ), $data );
		}


		/**
		 * Parse XML data type
		 *
		 * @return Array
		 */
		protected function xml( $data ) {

			libxml_use_internal_errors( true );
			$parsed = ( ! $data || $data == '' ? false : simplexml_load_string( $data ) );

			if ( ! $parsed ) {
				return false;
				libxml_clear_errors();
			} else {
				return $parsed;
			}
		}


		/**
		 * Parse JSON data type
		 *
		 * @return Array
		 */
		protected function json( $data ) {

			$parsed = ( ! $data || $data == '' ? false : json_decode( $data, 1 ) );
			return $parsed;
		}
	}
}
