<?php
class WC_CHECKOUT_BY_ZEN{

    const WOO_SCRIPT_VERSION = '0.0.4';
    const ZEN_WC_HANDLE = 'zencaptcha-woo';
    const OBJECT = 'ZENCAPTCHAdiviFormObject';

    public function enqueue_script_checkout_wc() {

        wp_enqueue_script(
			self::ZEN_WC_HANDLE,
			zencap_plugin_url('src/plugins/woocommerce/script.js'),
			[ 'jquery', 'zencaptcha-widget'],
			self::WOO_SCRIPT_VERSION,
			true
        );
    }

    public function inject_zencaptcha_widget_wc_checkout(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        echo zencaptcha_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }

    public function verify_zencaptcha_wc_checkout( ) {
        if ( empty( $_POST ) ) {
            return;
        }
        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        $email = wp_unslash( $_POST['billing_email'] );
        $verifyemail = get_option('zen-woo-verifyemail') ? $email  : "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        return;
    }


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-woo-checkout')) {
            return;
        }
    
        add_action( 'woocommerce_after_checkout_billing_form', [ $this, 'inject_zencaptcha_widget_wc_checkout' ] );
        add_action( 'woocommerce_checkout_process', [ $this, 'verify_zencaptcha_wc_checkout' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script_checkout_wc' ] );
    }

}

$zencaptchaPlugin = new WC_CHECKOUT_BY_ZEN();