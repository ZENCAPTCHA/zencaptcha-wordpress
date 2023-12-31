<?php

class Divi_Email_BY_ZEN {
    
    const DIVI_SCRIPT_VERSION = '0.0.14';
    const ZEN_DIVI_HANDLE = 'zencaptcha-divi-optin';
    const OBJECT = 'ZENCAPTCHAdiviFormObject';

    public function enqueue_zen_divi_script_email() {

        wp_enqueue_script(
			self::ZEN_DIVI_HANDLE,
			zencap_plugin_url('src/plugins/divi/optin.js'),
			[ 'jquery'],
			self::DIVI_SCRIPT_VERSION,
			true
        );

        wp_localize_script(
            self::ZEN_DIVI_HANDLE,
            self::OBJECT,
            [
                'zencaptcha_click_again'       => ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html(),
                'zencaptcha_failed_try_again' => ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html(),
                'zencaptcha_use_valid_a_valid_email' => ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert_no_html(),
                'zencaptcha_access_denied' => ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html(),
            ]
        );
    } 


    public function inject_zencaptcha_widget_divi_email($output, $single_name_field){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if ( et_core_is_fb_enabled() ) {
            return $output;
        }
        
        $capture = '<p class="et_pb_newsletter_button_wrap">';
        $newHTML = $errorHTML.zencaptcha_insert_html_widget($zencaptchainstance) . '<style>.zenc-captcha{margin-bottom:10px;}</style>'.$capture;
        return str_replace($capture, $newHTML, $output);
    }


    public function verify_zencaptcha_divi_email() {
        
        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message="zencaptcha_failed_try_again";
            et_core_die( esc_html( $error_message ) );
        }
    
        $postedEmail = $_POST['et_email'];
        $this->useremail = isset($postedEmail) ? trim(sanitize_text_field($postedEmail)) : '';
        $verifyemail = get_option('zen-divi-verifyemail') ? $this->useremail  : "";
        $verification = solution_verification($solution, $verifyemail);
    
        if (!$verification["success"]) {
            $error_message="zencaptcha_click_again";
            et_core_die( esc_html( $error_message ) );
        }
    
        if($verification["message"] != "valid"){
            $error_message="zencaptcha_click_again";
            et_core_die( esc_html( $error_message ) );
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message="zencaptcha_use_valid_a_valid_email";
            et_core_die( esc_html( $error_message ) );
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message="zencaptcha_access_denied";
            et_core_die( esc_html( $error_message ) );
        }

        return;
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
            if (!$zencaptchainstance->are_keys_set() || !get_option('zen-diviemailoptin-activate')) {
                return;
            }

            add_filter( 'et_pb_signup_form_field_html_submit_button', [ $this, 'inject_zencaptcha_widget_divi_email' ], 10, 2 );
            add_action( 'wp_ajax_et_pb_submit_subscribe_form', [ $this, 'verify_zencaptcha_divi_email' ], 9 );
            add_action( 'wp_ajax_nopriv_et_pb_submit_subscribe_form', [ $this, 'verify_zencaptcha_divi_email' ], 9 );
            add_action( 'wp_print_footer_scripts', [ $this, 'enqueue_zen_divi_script_email' ], 9 );
    }

}

$zencaptchaPlugin = new Divi_Email_BY_ZEN();