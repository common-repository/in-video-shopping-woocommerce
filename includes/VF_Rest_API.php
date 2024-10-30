<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class VF_Rest_API
 * @category API
 *
 * Serves
 */
class VF_Rest_API{

    /**
     * @const API endpoint
     */
    const _API_ENDPOINT = 'wp-api/v1.0/';

    /**
     * @const API endpoint base url
     */
    const _API_BASE_URL_ = 'http://api.video-force.com/';

    /**
     * API complete url
     *
     * @var string
     */
    private $api_url;

    /**
     * User public key
     * @var
     */
    private $user_public_key;

    /**
     * Users private key
     * @var
     */
    private $user_private_key;

    /**
     * Video-Force public key
     * @var
     */

    /**
     * Construct and set the needed keys etc.
     */
    public function __construct() {
        $this->api_url          = self::_API_BASE_URL_ . self::_API_ENDPOINT;
        $vf_options             =  get_option( 'woocommerce_vf-settings_settings' );
        $this->user_public_key  = $vf_options['vf_user_public_key'];
        $this->user_private_key = $vf_options['vf_user_private_key'];



    }

    public function check_if_empty_api_keys(){
        if('' == $this->user_public_key && '' == $this->user_private_key ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Add a video
     *
     * @param array $params
     * @return mixed
     */
    public function add_video( $params = array() ) {
        $result = $this->make_api_call( 'videos/add', $params, 'POST' );
       // print_r($result);
        return $this->parse_response( $result );
    }

    /**
     * Edit a video
     *
     * @param array $params
     * @return mixed
     */
    public function edit_video( $params = array() ) {
        $result = $this->make_api_call( 'videos/edit', $params, 'POST' );
        // print_r($result);
        return $this->parse_response( $result );
    }

    /**
     * Get a video
     *
     * @param array $params
     * @return mixed
     */
    public function get_video( $params = array() ) {
        $result = $this->make_api_call( 'videos/get', $params, 'POST' );
        // print_r($result);
        return $this->parse_response( $result );
    }

    /**
     * Add a player theme
     *
     * @param array $params
     * @return mixed
     */
    public function add_theme( $params = array()) {
        //echo"in api add";
        $result = $this->make_api_call( 'player/theme/add', $params, 'POST' );
        //print_r($result);
        return $this->parse_response( $result );
    }

    /**
     * Edit a player theme
     *
     * @param $params
     * @return mixed
     */
    public function edit_theme( $params ) {
        // print_r($params);
        $result = $this->make_api_call( 'player/theme/edit', $params, 'POST' );
        //print_r($result);exit;
        return $this->parse_response( $result );
    }

    /**
     * Get all player themes or by ID
     * @param string $params
     * @return mixed
     */
    public function get_themes( $params='' ) {
        $result = $this->make_api_call( 'player/theme/get', $params, 'POST' );
        // print_r($result);

        return $this->parse_response( $result );
    }

    /**
     * Get all translations
     * @param string $params
     * @return mixed
     */
    public function get_translations( $params='' ) {
        $result = $this->make_api_call( 'lang/get', $params, 'POST' );

        // print_r($result);
        return $this->parse_response( $result );
    }

    /**
     * Set all translations
     *
     * @param string $params
     * @return mixed
     */
    public function set_translations( $params='' ) {
        $result = $this->make_api_call( 'lang/add', $params, 'POST' );
        // print_r($result);

        return $this->parse_response( $result );
    }

    /**
     * Edit all translations
     *
     * @param string $params
     * @return mixed
     */
    public function edit_translations( $params='' ) {
        $result = $this->make_api_call( 'lang/edit', $params, 'POST' );
        //print_r($result);

        return $this->parse_response( $result );
    }

    /**
     * Authenticate user
     *
     * @param string $params
     * @return mixed
     */
    public function authenticate_user( $params='' ) {
        $result = $this->make_api_call( 'user/validate', $params, 'POST' );
        //print_r($result);

        return $this->parse_response( $result );
    }


    /**
     * Parse the response and Json decode it
     * @param $result
     * @return mixed
     */
    private function parse_response( $result ) {
        $array = json_decode( $result, true );

        if( 'success' === $array['status'] ) {
            return $array['response'];
        }else{
            /* echo "Error code: ".$array['code'] . "<br>";
            echo "Error Message: ".$array['error'] . "<br>";
            return false;*/
        }

    }

    /**
     * Make a curl request to the endpoint
     *
     * @param $endpoint
     * @param array $params
     * @param string $method
     * @return mixed|string|void
     */
    public function make_api_call( $endpoint, $params = array(), $method = 'GET' ) {
        $this->url = '';
       // echo $endpoint;
    //print_r($params);
        $json_data = $this->createHash( $params );

        $query =  http_build_query( array('encReq' => $json_data) );
        if('GET' === $method){
            $query = '/'. $query;
            $this->url = $this->api_url . $endpoint . $query;
        }else{
            $this->url = $this->api_url . $endpoint;
        }

        // echo $this->url;
        $ch = curl_init();


        //echo $this->api_url; exit;
        // Set up the enpoint URL
        curl_setopt( $ch, CURLOPT_URL, $this->url );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );


        if ( 'POST' === $method ) {
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $query );
        } else if ( 'DELETE' === $method ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
        }

        $return = curl_exec( $ch );



        $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

       //print_r($return);
        // $return = json_decode( $return , true);


        if ( empty( $return ) ) {
            $response = array(
                'status' => 'failure',
                'error'  => "cURL HTTP error ' . $code . '",
                'code'   => $code
            );

            $return = json_encode( $response );
        }
        return $return;
    }


    /**
     * Creates the json string to be sent with hashed signature
     *
     * @param $data
     * @return bool|string
     */
    public function createHash( $data ) {
        // User Public/Private Keys


        if( isset( $data ) ) {

            $data = json_encode( $data );
            // Generate content verification signature
            $signature = base64_encode( hash_hmac( 'sha256', $data, $this->user_private_key, TRUE) );

            // Prepare json data to be submitted
            return $json_data = $this->setRequestData( $data , $signature, $this->user_public_key );

            // Finally submit to api end point
            return $arrResponseData = $this->sendRequest( $url ,$json_data );
        }else{
            return false;
        }
    }

    /**
     * Set request data
     * @param $data
     * @param $signature
     * @param $publicKeys
     * @return string
     */
    public function setRequestData( $data , $signature , $publicKeys ) {

        return $json_data = htmlentities( rawurlencode( json_encode( array( 'data'=>$data, 'signature'=>$signature, 'publicKey'=>$publicKeys, 'timestamp' => time() ) ) ) );

    }

}


?>