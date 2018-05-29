<?php
/**
 * Implimentation of WordPress inbuilt API class
 *
 * Usage:
 * 
 * $api = new PLUGIN_API();
 * $api->endpoint = 'endpoint_url'
 * $api->header = array( "key: $val" )
 * $api->data_type = 'xml' or 'json'
 * $api->call_type = 'GET' or 'POST'
 * $api->call();
 * $data = $api->parse();
 * 
 */
if ( ! class_exists( 'PLUGIN_API' ) ) {

	class PLUGIN_API {


		public $endpoint;
		public $header;
		public $data_type;
		public $call_type;


		//Define the necessary database tables
		public function build() {

			$args = array(
						CURLOPT_URL => $this->endpoint,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => $this->call_type,
						CURLOPT_HTTPHEADER => $this->header,
						);

			return $args;
		}



		//Define the variables for db table creation
		public function call() {

			$curl = curl_init();

			curl_setopt_array( $curl, $this->build() );

			$result = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$result = "cURL Error #:" . $err;
			}

			return $result;
		}



		//Check options and tables and output the info to check if db install is successful
		public function parse($data) {

			call_user_func( array( $this, $this->data_type ), $data );
		}



		//Parse XML data type
		public function xml($data) {

			libxml_use_internal_errors(true);
			$parsed = ( ! $data || $data == '' ? false : simplexml_load_string( $data ) );

			if ( ! $parsed ) {
				return false;
				libxml_clear_errors();
			} else {
				return $parsed;
			}
		}



		//Parse JSON data type
		public function json($data) {

			$parsed = ( ! $data || $data == '' ? false : json_decode( $data, 1 ) );
			return $parsed;
		}
	}
} ?>