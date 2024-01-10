<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ZENCA_WC_REGISTER{



    public function zenca_inject_zencaptcha_widget_wc_register(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        //v1: echo zenca_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>";
        echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance))."<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }

    public function zenca_verify_zencaptcha_wc_register( $validation_error ) {
        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        $email = isset($_POST['email']) ?
        sanitize_text_field($_POST['email']) :
        '';
        //v1: $email = wp_unslash( $_POST['email'] );
        $verifyemail = get_option('zenca-woo-verifyemail') ? $email  : "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }


		return $validation_error;
    }


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-woo-register')) {
            return;
        }
    
        add_action( 'woocommerce_register_form', [ $this, 'zenca_inject_zencaptcha_widget_wc_register' ] );
        add_filter( 'woocommerce_process_registration_errors', [ $this, 'zenca_verify_zencaptcha_wc_register' ] );
    }

}

$zencaptchaPlugin = new ZENCA_WC_REGISTER();