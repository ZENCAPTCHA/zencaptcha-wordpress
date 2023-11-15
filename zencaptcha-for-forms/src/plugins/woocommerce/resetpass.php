<?php
class WC_RESET_BY_ZEN{



    public function inject_zencaptcha_widget_wc_lostpass(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        echo zencaptcha_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-woo-resetpass')) {
            return;
        }
    
        add_action( 'woocommerce_lostpassword_form', [ $this, 'inject_zencaptcha_widget_wc_lostpass' ] );
    }

}

$zencaptchaPlugin = new WC_RESET_BY_ZEN();