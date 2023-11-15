<?php

    if ( ! function_exists( 'zencaptcha_verify_post' ) ) {
        function zencaptcha_verify_post($useremail) {

            $postarray = retrieve_zencaptcha_solution_email_from_post($useremail);
            $solution = $postarray['solution'];
            $useremail = $postarray['useremail'];

            $verification = solution_verification($solution, $useremail);

            $result=null;
            $error_codes=[];

            if (!$verification["success"]) {
                if($verification["message"] == "timeout_or_duplicate"){
                    $result= ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
                    $error_codes = $verification["message"];
                    return apply_filters( 'zencaptcha_verify_request', $result, $error_codes );
                }
                else{
                    $result= ZENCAPTCHA_MAIN::zencaptcha_check_failed_Alert();
                    $error_codes = $verification["message"];
                    return apply_filters( 'zencaptcha_verify_request', $result, $error_codes );
                } 
            }

            if($verification["message"] != "valid"){
                $result= ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
                $error_codes = $verification["message"];
                return apply_filters( 'zencaptcha_verify_request', $result, $error_codes );
            }

            if (!empty($useremail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
                //only works for LITE etc.. users!
                $result= ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert();
                $error_codes = $verification["message"];
                return apply_filters( 'zencaptcha_verify_request', $result, $error_codes );
            }

            $countrycode = $verification["countrycode"];
            $country_blacklist = get_option('country_blacklist');
            $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
            if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
                $result= ZENCAPTCHA_MAIN::zencaptcha_no_access();
                $error_codes = $verification["message"];
                return apply_filters( 'zencaptcha_verify_request', $result, $error_codes );
            }

            return apply_filters( 'zencaptcha_verify_request', $result, $error_codes );
        }
    }

    function zencap_shortcode( $atts ) {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        return (string) apply_filters( 'zencap_zencaptchacaptcha_content', zencaptcha_insert_html_widget( $zencaptchainstance ) );
    }

    function zencaptcha_insert_html_widget($zencaptchainstance){   
        $sitekey = $zencaptchainstance->get_site_key();
        $lang = $zencaptchainstance->widget_lang();
        $addCallback=false;   //TODO: get this from options!
        $startcaptcha='none';
        //could use this instead: $startcaptcha = get_option('widgetstarts', 'none');
        if (get_option('widgetstarts')=="focus"){
            $startcaptcha='focus';
        }
        else if (get_option('widgetstarts')=="auto"){
            $startcaptcha='auto';
        }
        

        $callbackAttribute = '';  
        if ($addCallback) {
            $callbackAttribute = ' data-callback="zensolutionfound"';
        }
 
        if ($lang=='auto') {
            $langAttribute = '';
        }
        else{
            $langAttribute = ' data-lang="'.esc_html($lang).'"';
        }

        $startAttribute = ' data-start="'.$startcaptcha.'"';

        return sprintf(
            '<div class="zenc-captcha"  data-sitekey="%s" %s %s %s></div>
            <noscript>Enable Javascript for the website to work correctly.</noscript>',
        esc_html($sitekey),
        $callbackAttribute,
        $startAttribute,
        $langAttribute);
    }



    function zencaptcha_async_defer($tag, $handle, $src){
        if ($handle === 'zencaptcha-widget') {
            $tag = str_replace(" src", " async defer src", $tag);
        }
        return $tag;
    }

    function retrieve_zencaptcha_solution_email_from_post($useremail=''){
        $postedSolution = $_POST['zenc-captcha-solution'];
        $solution = isset($postedSolution) ? trim(sanitize_text_field($postedSolution)) : '';
        if (empty($useremail)) {
            $useremail = isset($_POST['zenc-email']) ? trim(sanitize_text_field($_POST['zenc-email'])) : '';
        }
        return array(
            "solution" => $solution,
            "useremail" => $useremail,
        );
    }

    function retrieve_zencaptcha_solution_from_post()
    {
        $postedSolution = $_POST['zenc-captcha-solution'];
        $solution = isset($postedSolution) ? trim(sanitize_text_field($postedSolution)) : '';
        return $solution;
    }

    function keys_not_set_response(){
        return array(
            "success" => false,
            "message" => 'keys_not_set',
            "fraudscore" => 0,
            "countrycode" => 'XX',  //not known,
            "emailvalid" => 'none'  //email not checked
        ); 
    }

    function correct_placeholder_response(){
        return array(
            "success" => true,
            "message" => 'valid',
            "fraudscore" => 30,   //server might be down => handle user with care
            "countrycode" => 'XX',  //not known,
            "emailvalid" => 'none'  //email not checked
        );
    }


    function solution_verification($solution, $email=""){
        $api_url = 'https://www.zencaptcha.com/captcha/siteverify';

        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set()) {
            return keys_not_set_response();
        }

        $secretkey = $zencaptchainstance->get_secret_key();

        $response_body = array(
            'secret' => $secretkey,
            'response' => $solution,
        );
        
        if ($email !== "") {
            $response_body["email"] = $email;
        }
        

		$request = array(
			'body' => $response_body,
        );

        $response = wp_remote_post( esc_url_raw( $api_url ), $request );

        if (is_wp_error($response)) {
            return correct_placeholder_response(); 
        }


        $response_code = wp_remote_retrieve_response_code( $response );

        $response_body = wp_remote_retrieve_body( $response );

        if (empty($response_body)) {
            return correct_placeholder_response(); 
        }

        $captcha_success = json_decode( $response_body, true );

		if ( $response_code != 200 ) {
			return correct_placeholder_response(); 
        }

		$success = isset( $captcha_success['success'] )
			? $captcha_success['success']
            : false;

        $message = isset( $captcha_success['message'] )
			? $captcha_success['message']
            : 'invalid_solution';
        
        $fraudscore = isset( $captcha_success['fraudscore'] )
			? $captcha_success['fraudscore']
            : 20;

        $countrycode = isset( $captcha_success['countrycode'] )
			? $captcha_success['countrycode']
            : 'XX';

        $emailvalid = isset( $captcha_success['emailvalid'] )
			? $captcha_success['emailvalid']
            : 'none';

        return array(
            "success" => $success,
            "message" => $message,
            "fraudscore" => $fraudscore,
            "countrycode" => $countrycode,
            "emailvalid" => $emailvalid  
        );
    }



    function zen_wp_lostpass_post($errors){

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
            return $errors;
        }

        $is_wc_request = isset( $_POST[ 'wc_reset_password' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'wc_reset_password' ] ) ) : '';

        //if it's a woocommerce lost password request but bot check is off stop here and let pass
        if($is_wc_request){
            if(!get_option('zen-woo-resetpass')){
                return $errors;
            }
        }
    
    
        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            return $errors->add('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert());
        }
        
        $verifyemail=""; //normally not email but username + not needed for logins
        $verification = solution_verification($solution, $verifyemail);
    
        if (!$verification["success"]) {
            return $errors->add('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert());
        }
    
        if($verification["message"] != "valid"){
            return $errors->add('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert());
        }
    
        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            return $errors->add('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_no_access());
        }
    
        return $errors;
    }