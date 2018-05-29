<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//Implimentation of MOZ API
if ( ! class_exists( 'INTERNAL_LINK_MASTER_API' ) ) {

	final class INTERNAL_LINK_MASTER_API {



		public static $host = 'https://lsapi.seomoz.com/linkscape';
		public static $endpoint = '/url-metrics';
		public $expires;
		public $accessID;
		public $secretKey;
		public $objectURL; //Set it before calling
		public $requestUrl; //Set it after $objectURL and before calling prepare() method
		public static $call_type = 'POST';



		//Refer to https://github.com/seomoz/SEOmozAPISamples/blob/master/php/signed_authentication_sample.php
		public function prepare() {

			$this->expires = time() + 300;

			$this->accessID = get_option('InternalLinkApiKeyField');
			$this->secretKey = get_option('InternalLinkApiPassField');

			$stringToSign = $this->accessID."\n".$this->expires;
			$binarySignature = hash_hmac('sha1', $stringToSign, $this->secretKey, true);
			$urlSafeSignature = urlencode(base64_encode($binarySignature));
			$cols = "34359738368";

			$this->requestUrl = self::$host . self::$endpoint .'/'. urlencode($this->objectURL) . '?Cols=' . $cols . '&AccessID=' . $this->accessID . '&Expires=' . $this->expires . '&Signature=' . $urlSafeSignature;
		}



		//Define the necessary database tables
		public function build() {

			$args = array(
						CURLOPT_URL => $this->requestUrl,
						CURLOPT_RETURNTRANSFER => true
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

			$parsed = ( ! $data || $data == '' ? false : json_decode( $data, 1 ) );
			return $parsed;
		}
	}
} ?>