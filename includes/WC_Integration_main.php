<?php
/**
 * Integration Demo Integration.
 *
 * @package  WC_Integration_main
 * @category VF_settings
 * @author   WooThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Integration_main' ) ) :

    class WC_Integration_main extends WC_Integration {

        /**
         * Init and hook in the integration.
         */
        public function __construct() {
            global $woocommerce;

            $this->id                 = 'vf-settings';
            $this->method_title       = __( 'Video Force Settings', 'in-video-shopping-woocommerce' );

            $this->method_description = __( '<div class="get-api-container"> <h3>Get your API keys</h3><div class="api-btn-row"><p class="reg-text"> Register or login to obtain your API keys</p><p class="link-api-text"> <a target="_blank" class="vf_sign_up" href="http://www.video-force.com/api-key">Get your API keys now </a></p></div></div> <div class="vf_key_text">Paste your API keys</div>', 'in-video-shopping-woocommerce' );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables.
            $this->wc_api_key          = $this->get_option( 'wc_api_key' );
            $this->wc_consumer_secret  = $this->get_option( 'wc_consumer_secret' );
            $this->wc_shop_url         = $this->get_option( 'wc_shop_url' );
            $this->debug               = $this->get_option( 'debug' );

            // Actions.
            add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );

            // Filters.
            //add_filter( 'woocommerce_settings_api_sanitized_fields_' . $this->id, array( $this, 'sanitize_settings' ) );

        }


        /**
         * Initialize integration settings form fields.
         *
         * @return void
         */
        public function init_form_fields() {
            //echo 'here';
            $this->form_fields = array(
                /* 'wc_api_key' => array(
                'title'             => __( 'Woocommerce API Key', 'in-video-shopping-woocommerce' ),
                'type'              => 'text',
                'description'       => __( 'Enter with your API Key. You can find this in "User Profile" drop-down (top right corner) > API Keys.', 'in-video-shopping-woocommerce' ),
                'desc_tip'          => true,
                'default'           => '123456'
                ),
                'wc_consumer_secret' => array(
                'title'             => __( 'Woocommerce consumer secret key', 'in-video-shopping-woocommerce' ),
                'type'              => 'text',
                'description'       => __( 'Enter with your WC consumer secret. You can find this in "User Profile" drop-down (top right corner) > API Keys.', 'in-video-shopping-woocommerce' ),
                'desc_tip'          => true,
                'default'           => 'xxxxx'
                ),
                'wc_shop_url' => array(
                'title'             => __( 'Woocommerce shop URL', 'in-video-shopping-woocommerce' ),
                'type'              => 'text',
                'description'       => __( 'Enter your woocommerce shop url', 'in-video-shopping-woocommerce' ),
                'desc_tip'          => true,
                'default'           => 'xxxxx'
                ),*/
                'vf_user_public_key' => array(
                    'title'             => __( 'Public key', 'in-video-shopping-woocommerce' ),
                    'type'              => 'text',
                    /* 'description'       => __( 'Enter your Video-Force public key', 'in-video-shopping-woocommerce' ),*/
                    'desc_tip'          => false,
                    'default'           => ''
                ),
                'vf_user_private_key' => array(
                    'title'             => __( 'Private key', 'in-video-shopping-woocommerce' ),
                    'type'              => 'text',
                    /*'description'       => __( 'Enter your Video-Force private key', 'in-video-shopping-woocommerce' ),*/
                    'desc_tip'          => false,
                    'default'           => ''
                ),
                'vf_user_tutorial' => array(
                    'title'             => __( 'Step by step tutorial', 'in-video-shopping-woocommerce' ),
                    'label'             => __( 'Yes, I want to autostart a step by step tutorial to <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; create my first shoppable video', 'in-video-shopping-woocommerce' ),
                    'type'              => 'checkbox',
                    'description'       => __('Please note: to run step by step tutorials, Sidekick.pro will be automatically installed', 'in-video-shopping-woocommerce' ),
                    'desc_tip'          => true,
                    'default'           => 'yes'
                )
            );
        }


        /**
         * Generate Button HTML.
         */
        public function generate_button_html( $key, $data ) {
            $field    = $this->plugin_id . $this->id . '_' . $key;
            $defaults = array(
                'class'             => 'button-secondary',
                'css'               => '',
                'custom_attributes' => array(),
                'desc_tip'          => false,
                'description'       => '',
                'title'             => '',
            );

            $data = wp_parse_args( $data, $defaults );
            //print_r($data);
            ob_start();
            ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
                    <?php echo $this->get_tooltip_html( $data ); ?>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
                        <button class="<?php echo esc_attr( $data['class'] ); ?> vf_button" type="button" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" <?php echo $this->get_custom_attribute_html( $data ); ?>><?php echo wp_kses_post( $data['title'] ); ?></button>
                        <?php echo $this->get_description_html( $data ); ?>
                    </fieldset>
                </td>
            </tr>
            <?php
            return ob_get_clean();
        }


        /**
         * Santize our settings
         * @see process_admin_options()
         */
        public function sanitize_settings( $settings ) {
            // We're just going to make the api key all upper case characters since that's how our imaginary API works
            if ( isset( $settings ) &&
                isset( $settings['wc_api_key'] ) ) {
                $settings['wc_api_key'] = strtoupper( $settings['wc_api_key'] );
            }
            return $settings;
        }

        /**
         * Display errors by overriding the display_errors() method
         * @see display_errors()
         */
        public function display_errors( ) {

            // loop through each error and display it
            foreach ( $this->errors as $key => $value ) {
                ?>
                <div class="error">
                    <p><?php _e( 'Looks like you made a mistake with the ' . $value . ' field. Make sure it isn&apos;t longer than 20 characters', 'in-video-shopping-woocommerce' ); ?></p>
                </div>
            <?php
            }


        }

        public function process_admin_options() {
            $this->validate_settings_fields();
            if ( count( $this->errors ) > 0 ) {
                $this->display_errors();
                return false;
            } else {
                update_option( $this->plugin_id . $this->id . '_settings', apply_filters( 'woocommerce_settings_api_sanitized_fields_' . $this->id, $this->sanitized_fields ) );
                $this->init_settings();

                $vf_options  = get_option( 'woocommerce_vf-settings_settings' );

                if('' != $vf_options['vf_user_public_key'] && '' != $vf_options['vf_user_private_key']){
                    echo "<script> jQuery('#vf_register_notice').hide();  </script>";
                }



                return true;
            }
        }


    }

endif;