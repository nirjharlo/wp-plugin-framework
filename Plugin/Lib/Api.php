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
                * API endpoint URL.
                *
                * @var string
                */
               protected $endpoint = '';

               /**
                * HTTP headers to be sent with the request.
                *
                * @var array
                */
               protected $header = array();

               /**
                * Expected data type. Either xml or json.
                *
                * @var string
                */
               protected $data_type = 'json';

               /**
                * Request method such as GET or POST.
                *
                * @var string
                */
               protected $call_type = 'GET';

               /**
                * Optionally set properties via constructor.
                *
                * @param array $config Configuration options.
                */
               public function __construct( array $config = array() ) {
                       foreach ( $config as $key => $value ) {
                               if ( property_exists( $this, $key ) ) {
                                       $this->$key = $value;
                               }
                       }
               }

               /**
                * Set endpoint URL.
                *
                * @param string $endpoint Endpoint URL.
                * @return self
                */
               public function endpoint( $endpoint ) {
                       $this->endpoint = $endpoint;
                       return $this;
               }

               /**
                * Set request headers.
                *
                * @param array $headers List of headers.
                * @return self
                */
               public function header( array $headers ) {
                       $this->header = $headers;
                       return $this;
               }

               /**
                * Expect JSON response.
                *
                * @return self
                */
               public function as_json() {
                       $this->data_type = 'json';
                       return $this;
               }

               /**
                * Expect XML response.
                *
                * @return self
                */
               public function as_xml() {
                       $this->data_type = 'xml';
                       return $this;
               }

               /**
                * Set HTTP method.
                *
                * @param string $type Request method.
                * @return self
                */
               public function method( $type ) {
                       $this->call_type = strtoupper( $type );
                       return $this;
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
                * Execute the HTTP request.
                *
                * @return mixed Raw API response or error string
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
                        return call_user_func( array( $this, $this->data_type ), $data );
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
