<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ZENCA_WC_RESET{



    public function zenca_inject_zencaptcha_widget_wc_lostpass(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        //v1: echo zenca_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>";
        echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance))."<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-woo-resetpass')) {
            return;
        }
    
        add_action( 'woocommerce_lostpassword_form', [ $this, 'zenca_inject_zencaptcha_widget_wc_lostpass' ] );
    }

}

$zencaptchaPlugin = new ZENCA_WC_RESET();