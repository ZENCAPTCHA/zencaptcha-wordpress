<?php
class WC_LOGIN_BY_ZEN{



    public function inject_zencaptcha_widget_wc_login(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        echo zencaptcha_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }

    public function verify_zencaptcha_wc_login( $validation_error ) {
        if ( ! doing_filter( 'woocommerce_process_login_errors' ) ) {
			return $validation_error;
        }
        
        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        $verifyemail = "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html();
            $validation_error->add( 'zencaptcha-error', $error_message );
            return $validation_error;
        }


		return $validation_error;
    }


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-woo-login')) {
            return;
        }
    
        add_action( 'woocommerce_login_form', [ $this, 'inject_zencaptcha_widget_wc_login' ] );
        add_filter( 'woocommerce_process_login_errors', [ $this, 'verify_zencaptcha_wc_login' ] );
    }

}

$zencaptchaPlugin = new WC_LOGIN_BY_ZEN();