<?php

    function zen_wp_registration_form(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set()) {
            return;
        }

        echo zencaptcha_insert_html_widget($zencaptchainstance);
    }

    function zen_wp_registration_post($sanitized_user_login, $user_email, $errors){
        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            return $errors->add('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert());
        }
        
        $verifyemail = get_option('zen-wp-verifyemail') ? $user_email : "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            if($verification["message"] == "timeout_or_duplicate"){
                return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert() );
            }
            else{
                return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zencaptcha_check_failed_Alert() );
            }    
        }

        if($verification["message"] != "valid"){
            return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert() );
        }

        if (!empty($user_email) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert());
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zencaptcha_no_access());
        }


        return $errors;
    }


    add_action( 'register_form', 'zen_wp_registration_form', 20, 0 );
    add_action( 'register_post', 'zen_wp_registration_post', 10, 3 );	