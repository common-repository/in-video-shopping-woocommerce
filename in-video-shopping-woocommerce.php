<?php
/**
 * Plugin Name: In-video shopping for WooCommerce
 * Plugin URI: http://www.video-force.com/in-video-shopping-woocommerce
 * Description: Inspiration and sales connected. With video you can inspire people, with e-commerce you can sell your products.
 * Version: 1.0.2
 * Author: Video-Force
 * Author URI: http://www.video-force.com/
 * Developer: Ritesh Ksheersagar
 * Text Domain: in-video-shopping-woocommerce
 * Domain Path: /languages
 *
 * Copyright: © 2015 Video-Force.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/**
 * Check if WooCommerce is active only then allow this plugin to work
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    //Plugin code starts here


    $plugins_url = plugins_url ();
    $vf_options  = get_option( 'woocommerce_vf-settings_settings' );





    define( '_VF_PLUGIN_DIR_', plugin_dir_path( __FILE__ ) );
    define( '_VF_TUTORIAL_', $vf_options['vf_user_tutorial'] );
    /* define( '_WC_API_KEY_', $vf_options['wc_api_key'] );
     define( '_WC_CONSUMER_SECRET_', $vf_options['wc_consumer_secret'] );
     define( '_WC_STORE_URL_', $vf_options['wc_shop_url'] );*/
    define( '_VF_PLUGIN_PATH_', $plugins_url.'/in-video-shopping-woocommerce' );

    //global $VF_Rest_API;


    if ( ! class_exists( 'WC_Integration_VF' ) ) :


        /**
         * Class WC_Integration_VF
         *  Main class of the Video-Force woocommerce plugin
         */
        class WC_Integration_VF{

            /**
             * Stores the post type class
             * @see VF_Post_Type
             * @var mixed
             */
            private $VF_Post_Type;

            /**
             * Stores the API instance to cater the endpoint requests
             * @var VF_Rest_API
             */
            public  $VF_Rest_API;
            /**
             * Construct the plugin.
             */
            public function __construct () {



                add_action( 'plugins_loaded', array( $this, 'init' ) );
                include_once( 'includes/VF_Rest_API.php' );

                $this->VF_Rest_API  = new VF_Rest_API ();
                $this->VF_Post_Type = include_once( 'includes/VF_Post_Type.php' );

                if('yes' == _VF_TUTORIAL_ || (isset($_POST['woocommerce_vf-settings_vf_user_tutorial']) && 1 == $_POST['woocommerce_vf-settings_vf_user_tutorial'])){
                    require_once('sidekick/sidekick_embed.php');
                    //  add_action( 'admin_notices', array(  $this->VF_Post_Type, 'vf_tutorial_notice') );
                }
                if(isset($_POST['woocommerce_vf-settings_vf_user_tutorial']) && 1 == $_POST['woocommerce_vf-settings_vf_user_tutorial']){


                    wp_enqueue_script('vf_sidekick',_VF_PLUGIN_PATH_ .'/js/sidekick.js',array('jquery','jquery-ui-core', 'jquery-ui-accordion', 'sidekick-admin', 'sidekick' ), false, true);


                }




                if(((isset($_GET['page']) && 'wc-settings' == $_GET['page']) && (isset($_GET['tab']) && 'integration' == $_GET['tab']))){

                }else{
                    if(!$this->VF_Rest_API->authenticate_user('validateVfUser')){
                        add_action( 'admin_notices', array(  $this->VF_Post_Type, 'vf_admin_notice') );
                    }
                }

                add_action( 'admin_enqueue_scripts', array( $this, 'vf_add_js_css' ) );

                $this->vf_ajax_actions ();
                $this->vf_shortcodes ();


                //$this->vf_create_page ();
                $this->vf_download_video ();

                //plugin i18n
                add_action( 'plugins_loaded', array( $this, 'vf_load_plugin_textdomain' ) );
            }

            public function vf_download_video () {
                $video_url ='';
            }


            //plugin i18n load domain
            function vf_load_plugin_textdomain () {
                //echo dirname( plugin_basename( __FILE__ ) ) . '/languages/'; exit;
                load_plugin_textdomain( 'in-video-shopping-woocommerce', '', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            }

            /**
             * This creates the cart is initializing page on plugin activation for  after the user clicks on the checkout link in the player
             */
            public function vf_create_page () {
                // if ( isset( $_GET['activate'] ) && $_GET['activate']=='true' ){

                $new_page_title    =  __( 'vf-cart-generator', 'in-video-shopping-woocommerce' );
                $new_page_content  = '[vf_cart_generator]';
                $new_page_template = ''; //ex. template-custom.php. Leave blank if you don't want a custom page template.

                //don't change the code bellow, unless you know what you're doing

                if ( empty( $GLOBALS['wp_rewrite'] ) )
                    $GLOBALS['wp_rewrite'] = new WP_Rewrite();
                $page_check = get_page_by_title( $new_page_title );
                $new_page = array(
                    'post_type'    => 'page',
                    'post_title'   => $new_page_title,
                    'post_content' => $new_page_content,
                    'post_status'  => 'publish',
                    'post_author'  => 1,
                );
                if( !isset( $page_check->ID ) ){
                    $new_page_id = wp_insert_post( $new_page );
                    if( isset( $new_page_template ) ){
                        update_post_meta( $new_page_id, '_wp_page_template', $new_page_template );
                    }
                }

                // }

            }



            /**
             * Shortcode added in the page for initializing the cart
             */
            public function vf_shortcodes () {
                add_shortcode( 'vf_cart_generator', array( $this, 'vf_cart_generator' ) );
            }

            /**
             * Javascript code to add products to the cart on the initializing page
             */
            public function vf_cart_generator () {

                global $woocommerce;

                $urls     = urldecode( $_GET['data'] );
                $urls     = json_decode( stripslashes( $urls ) );
                $ajax     = '';
                $cart_url =	json_encode( $urls );
                $cart_base_url = $woocommerce->cart->get_cart_url();
                $checkout_url = $woocommerce->cart->get_checkout_url();

                foreach( $urls as $add_to_cart_url ){
                    //for( $i=0; $i<$add_to_cart_url['qty']; $i++ ){
                    $ajax .= "jQuery.get( '$add_to_cart_url' );";
                    // }
                }

                ?>
                <script>
                    var cart_base_url = '<?php echo $cart_base_url;?>'
                    var checkout_url = '<?php echo $checkout_url;?>'
                    var url = '<?php echo $cart_url;?>';
                    var url = JSON.parse( url );
                    var counter = 0;
                    jQuery( document ).ready( function () {
                        //console.log ( JSONObject );
                        var count =0;
                        vf_add_product_in_cart ();
                        function vf_add_product_in_cart (){

                            if( !url[counter] ){
                                console.log( 'lauda ghe' )
                                document.location.href = checkout_url ;
                                return false;
                            }

                            jQuery.ajax( {
                                url: cart_base_url + url[counter]
                            } ).done( function () {
                                    console.log( 'added chut' + url[counter] )
                                    counter++;
                                    // if( addToWCCartUrls[counter] ){

                                    vf_add_product_in_cart ();

                                    // }
                                } );
                        }
                    } );


                </script>

            <?php }

            /**
             * Ajax request hooks to customize the player
             */
            public function vf_ajax_actions () {
                //ajax submit on the customize
                add_action( 'wp_ajax_customize_player_ajax', array( $this->VF_Post_Type, 'customize_player_ajax' ) );
                add_action( 'wp_ajax_get_theme_by_id', array( $this->VF_Post_Type, 'get_theme_by_id' ) );
            }

            /**
             * Init function to load the plugin and add a tab in the integration of woocommerce
             */
            public function init () {
                //for all integrations
                // Checks if WooCommerce is installed.
                if ( class_exists( 'WC_Integration' ) ) {
                    // Include our integration class.
                    require_once 'includes/WC_Integration_main.php';
                    // Register the integration.
                    add_filter( 'woocommerce_integrations', array( $this, 'add_integration' ) );


                    add_action( 'init', array($this,'vf_create_page'));

                } else {
                    // throw an admin error if you like
                    _e( 'Woocommerce not installed or activated','in-video-shopping-woocommerce' );
                }
            }



            /**
             * This adds header rules for accepting cross scripting
             * @param $rules
             * @return string
             * @todo will be removed in the future not used anymore
             */
            public function vf_htaccess_rules( $rules ) {

                $access_rules = <<<EOD
                            # BEGIN Video-Force
                            Header set Access-Control-Allow-Origin "http://www.video-force.com"
                            Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
                            Header set Access-Control-Allow-Headers "Content-Type"
                            Header set Access-Control-Allow-Credentials: true
                            # END Video-Force\n
EOD;

                return $access_rules.$rules  ;
            }

            /**
             * Enqueue JS and CSS in the plugin
             */
            public function vf_add_js_css () {

                wp_enqueue_style( 'vf_skeleton', plugins_url( '/assets/css/skeleton.css', __FILE__ ) , false, '1.0.0', 'all' );
                wp_enqueue_style( 'vf_skeleton', plugins_url( '/assets/css/colors.css', __FILE__ ) , false, '1.0.0', 'all' );

            }

            /**
             * Add integration tab in woocommerce
             * @param $integrations
             * @return array
             */
            public function add_integration( $integrations ) {
                $integrations[] = 'WC_Integration_main';
                return $integrations;
            }



        }


        $WC_Integration_VF = new WC_Integration_VF( __FILE__ );

    endif;
}

