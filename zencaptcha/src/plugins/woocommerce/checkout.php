<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ZENCA_WC_CHECKOUT{

    const WOO_SCRIPT_VERSION = '0.0.4';
    const ZENCA_WC_HANDLE = 'zencaptcha-woo';
    const OBJECT = 'ZENCAPTCHAdiviFormObject';

    public function zenca_enqueue_script_checkout_wc() {

        wp_enqueue_script(
			self::ZENCA_WC_HANDLE,
			zenca_plugin_url('src/plugins/woocommerce/script.js'),
			[ 'jquery', 'zencaptcha-widget'],
			self::WOO_SCRIPT_VERSION,
			true
        );
    }

    public function zenca_inject_zencaptcha_widget_wc_checkout(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        //v1: echo zenca_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>";
        echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance))."<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }

    public function zenca_verify_zencaptcha_wc_checkout( ) {
        if ( empty( $_POST ) ) {
            return;
        }
        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        $email = isset($_POST['billing_email']) ?
        sanitize_text_field($_POST['billing_email']) :
        '';
        //v1: $email = wp_unslash( $_POST['billing_email'] );
        $verifyemail = get_option('zenca-woo-verifyemail') ? $email  : "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
            wc_add_notice( $error_message, 'error' );
            return;
        }

        return;
    }


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-woo-checkout')) {
            return;
        }
    
        add_action( 'woocommerce_review_order_before_submit', [ $this, 'zenca_inject_zencaptcha_widget_wc_checkout' ] );
        //add_action( 'woocommerce_after_checkout_billing_form', [ $this, 'zenca_inject_zencaptcha_widget_wc_checkout' ] );
        add_action( 'woocommerce_checkout_process', [ $this, 'zenca_verify_zencaptcha_wc_checkout' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'zenca_enqueue_script_checkout_wc' ] );
    }

}

$zencaptchaPlugin = new ZENCA_WC_CHECKOUT();