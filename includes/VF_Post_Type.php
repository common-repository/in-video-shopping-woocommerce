<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

//used for iframe
global $dimensions;
//Used to add a custom post type
/**
 * Class VF_Post_Type
 * @category Custom WP Post Type
 *
 */
class VF_Post_Type{
    /**
     * Stores all the video details from the API
     * @var
     */
    public $video_details;

    /**
     * An array of the positions a product can be displayed in
     *
     * @var array
     */
    public $display_positions = array(
        0  => 'Top extreme left',
        1  => 'Top left',
        2  => 'Top right',
        3  => 'Top extreme right',
        4  => 'Middle 1 extreme left',
        5  => 'Middle 1 left',
        6  => 'Middle 1 right',
        7  => 'Middle 1 extreme right',
        8  => 'Middle 2 extreme left',
        9  => 'Middle 2 left',
        10 => 'Middle 2 right',
        11 => 'Middle 2 extreme right',
        12 => 'Bottom extreme left',
        13 => 'Bottom left',
        14 => 'Bottom right',
        15 => 'Bottom extreme right',
    );

    /**
     * Cart positions in player
     *
     * @var array
     */
    public $cart_display_positions = array(
        1 => 'Top left',
        2 => 'Top right',
        3 => 'Bottom left',
        4 => 'Bottom  right',
    );

    /**
     * API instance of WC Rest API
     *
     * @var
     */
    //private $VF_WC_API_Client;

    /**
     * add all action hooks for CPT
     */
    function __construct() {

        if ( is_admin() ) {
            add_action( 'init', array($this, 'register_vf_post_type' ) );
            add_action( 'add_meta_boxes', array($this,'add_events_metaboxes') );
            add_action( 'save_post', array($this,'vf_save_video'), 1, 2 ); // save the custom fields
            add_action( 'admin_enqueue_scripts', array($this,'vf_enqueue_scripts') );//javascript load
            add_action( 'admin_menu', array($this, 'vf_customization_menu') );
            add_action( 'admin_menu', array($this, 'vf_translations_menu') );

            //Filters
            add_filter('post_updated_messages', array($this, 'custom_post_type_messages') );
            //add_filter('manage_video_posts_columns' , array($this, 'video_cpt_columns'));
        }
    }

    /**
     * Add translations menu in CPT
     */
    function vf_translations_menu() {
        add_submenu_page( 'edit.php?post_type=video', 'Translations', 'Translations', 'manage_options', 'translations', array($this, 'vf_translations_view'));
    }

    /**
     * Add customize template menu in CPT
     */
    function vf_customization_menu() {
        add_submenu_page( 'edit.php?post_type=video', 'Customize Player', 'Player settings', 'manage_options', 'player-themes', array($this, 'vf_customization_view'));

    }

    /*
    * Add custom colum in post type
    * */
    /* function video_cpt_columns($columns) {

    $new_columns = array(

    'video_views' => __('views', 'in-video-shopping-woocommerce'),
    );
    return array_merge($columns, $new_columns);
    }*/


    /**
     * Ajax add or edit theme of the player
     */
    function customize_player_ajax() {
        global $WC_Integration_VF;

        if(isset($_POST['form_data']['vf_video_theme_id'])){
            $params['vf_video_theme_id'] = sanitize_text_field( $_POST['form_data']['vf_video_theme_id'] );
        }

        //$params['vf_user_id'] =  $_POST['form_data']['vf_user_id'];
        $params['vf_theme_name']              = sanitize_text_field( $_POST['form_data']['vf_theme_name'] );
        $params['vf_theme_color']             = $_POST['form_data']['vf_theme_color'] ;
        $params['vf_theme_play_button_color'] = $_POST['form_data']['play_button_color'] ;

        //Controls are further serialized in vf_theme_control_meta
        $controls['vf_tcm_all']               = $_POST['form_data']['vf_tcm_all'];
        $controls['vf_tcm_big_play_button']   = $_POST['form_data']['vf_tcm_big_play_button'];
        $controls['vf_tcm_small_play_button'] = $_POST['form_data']['vf_tcm_small_play_button'];
        $controls['vf_tcm_control_bar']       = $_POST['form_data']['vf_tcm_control_bar'];
        $controls['vf_tcm_volume_bar']        = $_POST['form_data']['vf_tcm_volume_bar'];
        $controls['vf_tcm_fullscreen_button'] = $_POST['form_data']['vf_tcm_fullscreen_button'];
        $controls['vf_tcm_timers']            = $_POST['form_data']['vf_tcm_timers'];

        $params['vf_theme_control_meta']      = serialize($controls);

        //ob_clean();

        $vf_action  = $_POST['form_data']['vf_action'];

        switch($vf_action){
            case 'add':
                $result = $WC_Integration_VF->VF_Rest_API->add_theme($params);
                break;

            case 'edit':
                $result = $WC_Integration_VF->VF_Rest_API->edit_theme($params);
                break;
        }

        if( ob_get_length() ){
            ob_clean();
        }
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    /**
     * Get theme details by id
     */
    function get_theme_by_id(){
        global $WC_Integration_VF;

        $params['vf_video_theme_id'] = $_POST['theme_id'];

        //get theme from our API
        $theme = $WC_Integration_VF->VF_Rest_API->get_themes($params);


        $theme_control_meta             = unserialize($theme[0]['theme_control_meta']);
        $theme[0]['theme_control_meta'] = $theme_control_meta;


        $data = json_encode($theme[0]);

        if( ob_get_length() ){
            ob_clean();
        }

        print_r($data);
        wp_die();
    }

    /**
     * View function for template
     */
    function vf_customization_view() {
        global $WC_Integration_VF;
        if($this->check_if_valid_user()){
            $all_themes = $WC_Integration_VF->VF_Rest_API->get_themes();
            include_once(_VF_PLUGIN_DIR_.'views/customize.php');
        }
    }

    /**
     * View function for translations
     */
    function vf_translations_view(){
        global $WC_Integration_VF;

        if($this->check_if_valid_user()){
            //to check if the form is submitted
            if( isset($_POST['do_translate']) && 'true' == $_POST['do_translate'] ) {
                $params['vf_lang_meta'] = serialize($_POST['trans']);

                if(isset($_POST['vf_lang_id'])){
                    $params['vf_lang_id'] = $_POST['vf_lang_id'];
                    $result = $WC_Integration_VF->VF_Rest_API->edit_translations($params);
                }else{
                    $result = $WC_Integration_VF->VF_Rest_API->set_translations($params);
                }
            }

            //code here to get the stored translations
            $translations = $WC_Integration_VF->VF_Rest_API->get_translations();

            $translations = $translations[0];
            $lang_meta = unserialize($translations['lang_meta']);

            include_once(_VF_PLUGIN_DIR_.'views/get_translations.php');
        }
    }


    /**
     * Register a CPT
     */
    function register_vf_post_type() {

        $labels = array(
            'name'               =>__('Videos','in-video-shopping-woocommerce' ),
            'singular_name'      =>__( 'Video','in-video-shopping-woocommerce' ),
            'add_new'            =>__('Add New','in-video-shopping-woocommerce'),
            'add_new_item'       =>__('Add New Video','in-video-shopping-woocommerce'),
            'edit_item'          =>__('Edit Video','in-video-shopping-woocommerce'),
            'new_item'           =>__( 'New Video','in-video-shopping-woocommerce'),
            'all_items'          =>__( 'All Videos', 'in-video-shopping-woocommerce'),
            'view_item'          =>__( 'View Video', 'in-video-shopping-woocommerce'),
            'search_items'       =>__('Search Videos', 'in-video-shopping-woocommerce'),
            'not_found'          =>__('No videos found','in-video-shopping-woocommerce'),
            'not_found_in_trash' =>__( 'No videos found in Trash','in-video-shopping-woocommerce') ,
            'parent_item_colon'  =>'',
            'menu_name'          =>__('Video-Force','in-video-shopping-woocommerce')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'vf_video' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => _VF_PLUGIN_PATH_.'/assets/images/logo.png',
            'supports'           => array( 'title', 'author' )
        );

        register_post_type( 'video', $args );

    }

    /*
     *  custom messages for CPT
    */
    function custom_post_type_messages($messages) {
        global $post, $post_ID;

        $post_type = get_post_type( $post_ID );
        $obj = get_post_type_object($post_type);

        $singular = $obj->labels->singular_name;

        $viewLink = ($obj->public) ?  ' <a href="%s">View '.strtolower($singular).'</a>' : "";
        $previewLink = ($obj->public) ? ' <a target="_blank" href="%s">Preview '.strtolower($singular).'</a>': "";
        $schedPreviewLink = ($obj->public) ? ' <a target="_blank" href="%2$s">Preview '.strtolower($singular).'</a>': "";

        $messages[$post_type] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __($singular.' updated.'.$viewLink), esc_url( get_permalink($post_ID) ) ),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __($singular.' updated.'),
            5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __($singular.' published.'.$viewLink), esc_url( get_permalink($post_ID) ) ),
            7 => __('Page saved.'),
            8 => sprintf( __($singular.' submitted.'.$previewLink), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>.'.$schedPreviewLink), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __($singular.' draft updated.'.$previewLink), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );
        return $messages;
    }

    /**
     * Add meta boxes in CPT
     */
    function add_events_metaboxes() {
        if($this->check_if_valid_user()){
            add_meta_box('vf_video_settings', 'Video settings', array($this,'vf_video_settings'), 'video', 'normal', 'high');
            add_meta_box('vf_add_products', 'Add products', array($this,'vf_add_products'), 'video', 'normal', 'high');
            add_meta_box('vf_preview', 'Preview', array($this,'vf_video_preview'), 'video', 'normal', 'high');
            add_meta_box('vf_analytics', 'Video analytics', array($this,'vf_analytics'), 'video', 'side', 'default');

        }else{
            add_action( 'admin_notices', array($this, 'vf_admin_notice') );
            //add_action( 'admin_notices', array($this, 'vf_invalid_user_notice') );
        }


    }

    public function vf_invalid_user_notice() {

        ?>
        <div class="vf-notice">
            <div class="vf-notice-inner">
                <p><?php _e( '<strong>Important:</strong> Looks like your credentials are incorrect. please check your creds in Woocommerce integration tab.', 'in-video-shopping-woocommerce' ); ?></p>
            </div>
        </div>
    <?php
    }

    public function vf_tutorial_notice() {

        if ( ! get_user_meta(get_current_user_id(), 'vf_ignore_notice') ) {
            ?>
            <div class="vf-notice">
                <div class="vf-notice-inner">
                    <?php _e( '<a href="./?walkthrough_id=(4265)">Start In-video shopping tutorial.</a>', 'in-video-shopping-woocommerce' ); ?>
                    <a href="<?php  echo wp_nonce_url('?vf_ignore_notice=1','hide_msg', 'vf_nonce'); ?>">Dismiss</a>
                </div>
            </div>
        <?php
        }
    }


    public function vf_admin_notice() {

        ?>
        <div id="vf_register_notice" class="vf-notice">
            <div class="vf-notice-inner">
                <?php _e( '<a href="./admin.php?page=wc-settings&tab=integration">Click here to register In-video shopping for Woocommerce</a>', 'in-video-shopping-woocommerce' ); ?>
            </div>
        </div>
    <?php
    }

    public function vf_no_products_notice() {

        ?>
        <div class="vf-notice">
            <div class="vf-notice-inner">
                <p><?php _e( '<strong>Important:</strong>Looks like you have no products in your woocommerc store. If you do then please contact Support at <strong>Video-Force</strong> ', 'in-video-shopping-woocommerce' ); ?></p>
            </div>
        </div>
    <?php

    }




    /**
     * Enqueue scripts for CPT
     */
    function vf_enqueue_scripts(){
        //demo player
        // wp_enqueue_script('vf_videojs',_VF_PLUGIN_PATH_ .'/assets/player/video.min.js');
        wp_enqueue_script('vf_videojs',_VF_PLUGIN_PATH_ .'/assets/player/video.js');
        wp_enqueue_style( 'vf_videojs_css', _VF_PLUGIN_PATH_. '/assets/player/video-js.css', false, '1.0.0', 'all');
        wp_enqueue_script('vf_switch',_VF_PLUGIN_PATH_ .'/js/jquery.iphone-switch.js');
        wp_enqueue_script( 'jquery-ui' );

        //here new code image upload
        //wp_enqueue_script('jquery');
        // This will enqueue the Media Uploader script
        wp_enqueue_media();

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script('vf_scripts',_VF_PLUGIN_PATH_ .'/js/vf_script.js',array('jquery','jquery-ui-core', 'jquery-ui-accordion', 'wp-color-picker'), false, true);

        wp_localize_script('vf_scripts', 'vf_vars', array(
                'vf_nonce' => wp_create_nonce('vf-nonce'),
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );


    }

    /**
     * Product view in CPT
     */
    public function vf_add_products() {
        $product_details = $this->unserialize_video_details($this->video_details['product_details']);
        //$products =  $this->VF_WC_API_Client->get_products();
        $products =  $this->get_woocommerce_products();
        include_once(_VF_PLUGIN_DIR_.'views/products.php');
    }

    /**
     * Iframe preview of the video once saved
     */
    public function vf_video_preview() {
        global $post,$dimensions;
        if(isset($_GET['post'])){
            $post_id = $_GET['post'];

            $vf_video_id = null;
            //	$vf_status = null;
            $vf_video_id = get_post_meta($post_id, 'vf_video_id', TRUE);
            $vf_status 	 = get_post_status($post_id);

            include_once(_VF_PLUGIN_DIR_.'views/preview.php');
        }

    }

    /**
     * Load complete view of add/edit video
     */
    function vf_video_settings() {

        global $post,$WC_Integration_VF;


        //include_once(_VF_PLUGIN_DIR_.'includes/VF_WC_API_Client.php');

        //$this->VF_WC_API_Client = new VF_WC_API_Client(_WC_API_KEY_, _WC_CONSUMER_SECRET_, _WC_STORE_URL_);

        // Noncename needed to verify where the data originated
        echo '<input type="hidden" name="vf_meta_noncename" id="vf_meta_noncename" value="' .
            wp_create_nonce( plugin_basename(__FILE__) ) . '" />';



        $vf_video_id = get_post_meta($post->ID, 'vf_video_id', TRUE);
        if(!empty($vf_video_id)){

            $video_details = $WC_Integration_VF->VF_Rest_API->get_video(array('vf_hash' => $vf_video_id));
            $this->video_details = $video_details[0];
            // print_r($video_details); exit;
            $product_details = $this->unserialize_video_details($this->video_details['product_details']);
            // print_r($product_details); exit;
        }
        // print_r($this->get_woocommerce_products());
        // $products =  $this->VF_WC_API_Client->get_products();
        $products =  $this->get_woocommerce_products();
        //  print_R($products);
        if(empty($products->products)){
            add_action( 'admin_notices', array($this, 'vf_no_products_notice') );
        }
        $all_themes = $WC_Integration_VF->VF_Rest_API->get_themes();

        include_once(_VF_PLUGIN_DIR_.'views/admin_form.php');
        unset($products);


    }

    /*
    * Get all woocommerce products
    * */
    private function get_woocommerce_products(){

        $products_IDs = array();

        $loop = new WP_Query( array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1
        ) );

        while ( $loop->have_posts() ) : $loop->the_post();

            $theid = get_the_ID();

            $thetitle = get_the_title();
            $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id($theid));
            $featured_image = $featured_image[0];
            $short_description = get_the_excerpt();


            $full_product_list['products'][] = array(
                'id' => $theid,
                'title' => $thetitle,
                'featured_src' => $featured_image,
                'short_description' => $short_description
            );


        endwhile; wp_reset_query();

        return json_decode(json_encode($full_product_list));

    }

    /**
     * Unserialize video details
     *
     * @param $video_details
     * @return mixed
     */
    private function unserialize_video_details($video_details) {
        return unserialize($video_details);
    }

    /**
     *
     * Save a video in CPT and using API
     * @param $post_id
     * @param $post
     * @return mixed
     */
    function vf_save_video($post_id, $post) {
        global $WC_Integration_VF;
        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        $vf_nonce = null;
        if(isset($_POST['vf_meta_noncename']))
            $vf_nonce = $_POST['vf_meta_noncename'];

        if ( !wp_verify_nonce( $vf_nonce, plugin_basename(__FILE__) )) {
            return $post->ID;
        }

        // Is the user allowed to edit the post or page?
        if ( !current_user_can( 'edit_post', $post->ID ))
            return $post->ID;


        // print_r($_POST);    exit;

        $vf_video_id     = null;
        $vf_video_id     = get_post_meta($post->ID, 'vf_video_id', TRUE);
        $vf_status_video = get_post_status($post->ID);

        if( 'draft' == $vf_status_video ){
            $vf_status = 0;
        }elseif( 'publish' == $vf_status_video ){
            $vf_status = 1;

        }

        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though.
        $vf_post_array = array();


        $vf_post_array['vf_hash']              = $vf_video_id;
        $vf_post_array['vf_video_theme_id']    = $_POST['vf_video_theme_id'];
        $vf_post_array['vf_post_id']           =  $post->ID ;
        $vf_post_array['vf_status']            = $vf_status;
        $vf_post_array['vf_video_link']        = $_POST['vf_video_link'];
        $vf_post_array['vf_wc_product_id']     = $_POST['vf_wc_product_id'];
        $vf_post_array['vf_from_time']         = $_POST['vf_from_time'];
        $vf_post_array['vf_to_time']           = $_POST['vf_to_time'];
        $vf_post_array['vf_position']          = $_POST['vf_position'];
        $vf_post_array['vf_splash_image']      = $_POST['vf_splash_image'];
        $vf_post_array['vf_cart']              = $_POST['vf_cart'];
        $vf_post_array['vf_iframe_dimensions'] = $_POST['vf_iframe_dimensions'];
        $vf_post_array['vf_shop_id']           = $_POST['vf_shop_id'];
        $vf_post_array['vf_video_type_id']     = $_POST['vf_video_type_id'];
        $vf_post_array['vf_url_type_id']       = $_POST['vf_url_type_id'];


        if(!empty($vf_video_id)){

            $result = $WC_Integration_VF->VF_Rest_API->edit_video($vf_post_array);
            // var_dump($result);
            if(false != $result){
                // echo 'edited successfully';
            }else{
                // echo 'edit failed'; exit;
            }


        }else{

            $result = $WC_Integration_VF->VF_Rest_API->add_video($vf_post_array);
            if(false != $result){
                //add the vf_video_id in the post meta
                add_post_meta( $post->ID, 'vf_video_id', $result, true );
            }else{
                echo 'add failed'; exit;
            }


        }

    }

    public function vf_analytics(){
        global $post,$WC_Integration_VF;

        $vf_video_id = get_post_meta($post->ID, 'vf_video_id', TRUE);
        if(!empty($vf_video_id)){

            $video_details = $WC_Integration_VF->VF_Rest_API->get_video(array('vf_hash' => $vf_video_id));
            $this->video_details = $video_details[0];

        }

        include_once(_VF_PLUGIN_DIR_.'views/analytics.php');

    }


    public function check_if_valid_user(){
        global $WC_Integration_VF;

        $params = 'validateVfUser';
        if($WC_Integration_VF->VF_Rest_API->authenticate_user($params)){
            return true;
        }else{
            return false;
        }



    }






}
return new VF_Post_Type();



