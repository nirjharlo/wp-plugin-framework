<?php
namespace NirjharLo\WP_Plugin_Framework\Engine\Lib;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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

class Api
{
    /**
     * API endpoint URL.
     *
     * @var string
     */
    protected $endpoint = '';


    /**
     * Request headers.
     *
     * @var array
     */
    protected $header = [];


    /**
     * Response data type.
     *
     * @var string
     */
    protected $dataType = 'json';


    /**
     * HTTP method to use.
     *
     * @var string
     */
    protected $callType = 'GET';


    /**
     * Initialise a new API request.
     */
    public function __construct( string $endpoint = '', array $header = [], string $method = 'GET', string $dataType = 'json' )
    {
        $this->endpoint  = $endpoint;
        $this->header    = $header;
        $this->callType  = $method;
        $this->dataType  = $dataType;
    }

    /**
     * Create a new instance fluently.
     */
    public static function make(): self
    {
        return new self();
    }

    /**
     * Set the endpoint.
     */
    public function endpoint( string $endpoint ): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Set request headers.
     */
    public function header( array $header ): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Set HTTP method.
     */
    public function method( string $method ): self
    {
        $this->callType = strtoupper( $method );

        return $this;
    }

    /**
     * Set data type.
     */
    public function dataType( string $dataType ): self
    {
        $this->dataType = $dataType;

        return $this;
    }


		/**
		 * Define the necessary database tables
		 *
		 * @return Array
		 */
    protected function build(): array
    {
        return [
            CURLOPT_URL            => $this->endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => $this->callType,
            CURLOPT_HTTPHEADER     => $this->header,
        ];
    }


		/**
		 * Define the variables for db table creation
		 *
		 * @return Array
		 */
    public function call()
    {
        $curl = curl_init();

        curl_setopt_array( $curl, $this->build() );

        $result = curl_exec( $curl );
        $err    = curl_error( $curl );

        curl_close( $curl );

        if ( $err ) {
            return 'cURL Error #:' . $err;
        }

        return $result;
    }


    /**
     * Parse the returned data based on the configured data type.
     */
    public function parse( string $data )
    {
        return call_user_func( [ $this, $this->dataType ], $data );
    }


    /**
     * Parse XML data type.
     */
    protected function xml( string $data )
    {
        libxml_use_internal_errors( true );
        $parsed = empty( $data ) ? false : simplexml_load_string( $data );
        libxml_clear_errors();

        return $parsed ?: false;
    }


    /**
     * Parse JSON data type.
     */
    protected function json( string $data )
    {
        return empty( $data ) ? false : json_decode( $data, true );
    }
}
