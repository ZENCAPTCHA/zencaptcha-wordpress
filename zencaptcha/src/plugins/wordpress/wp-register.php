<?php
if ( ! defined( 'ABSPATH' ) ) exit;

    function zenca_wp_registration_form(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set()) {
            return;
        }

        echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance));
    }

    function zenca_wp_registration_post($sanitized_user_login, $user_email, $errors){
        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            return $errors->add('zencaptcha_error_message',ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert());
        }
        
        $verifyemail = get_option('zenca-wp-verifyemail') ? $user_email : "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            if($verification["message"] == "timeout_or_duplicate"){
                return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zenca_check_again_Alert() );
            }
            else{
                return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zenca_check_failed_Alert() );
            }    
        }

        if($verification["message"] != "valid"){
            return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zenca_check_again_Alert() );
        }

        if (!empty($user_email) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert());
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            return $errors->add('zencaptcha_error_message', ZENCAPTCHA_MAIN::zenca_no_access());
        }


        return $errors;
    }


    add_action( 'register_form', 'zenca_wp_registration_form', 20, 0 );
    add_action( 'register_post', 'zenca_wp_registration_post', 10, 3 );	