<?php

function zen_wp_lostpass_form(){
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set()) {
        return;
    }

    echo zencaptcha_insert_html_widget($zencaptchainstance);
 
}


add_action( 'lostpassword_form', 'zen_wp_lostpass_form', 20, 0 );
