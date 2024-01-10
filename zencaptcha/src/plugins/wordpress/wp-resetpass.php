<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function zenca_wp_lostpass_form(){
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set()) {
        return;
    }

    echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance));
 
}


add_action( 'lostpassword_form', 'zenca_wp_lostpass_form', 20, 0 );
