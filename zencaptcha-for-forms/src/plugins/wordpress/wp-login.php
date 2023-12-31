<?php

class WP_LOGIN_BY_ZEN{

    public function zen_wp_login_form(){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set()) {
            return;
        }

        echo zencaptcha_insert_html_widget($zencaptchainstance). "<style>.zenc-captcha{margin-bottom:10px;}</style>";
    
    }

    public function zen_wp_login_post($user, $password){

        if (! ( isset( $_POST['log'], $_POST['pwd'] ) && $this->is_wp_login_form())) {
			return $user;
		}
        

        if (is_wp_error($user) && isset($user->errors['empty_username']) && isset($user->errors['empty_password'])) {
            return $user;
        }

        if ( isset( $_POST['et_builder_submit_button'] ) ) { //direct login through divi - nothing to do with current page, so pass
            return $user;
        }


        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            return new \WP_Error('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert());
        }
        
        $verifyemail=""; //normally not email but username + not needed for logins
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            return new \WP_Error('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert());
        }

        if($verification["message"] != "valid"){
            return new \WP_Error('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert());
        }

        //be careful, this can block you out from your own admin panel if you are in a country that you blocked!
        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            return new \WP_Error('zencaptcha_error_message',ZENCAPTCHA_MAIN::zencaptcha_no_access());
        }

        return $user;
    }

    private function is_wp_login_form() {
        return ( did_action( 'login_init' ) && did_action( 'login_form_login' ) );
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-wp-login')) {
            return;
        }

        add_action( 'login_form', [ $this, 'zen_wp_login_form' ], 20, 0 );
        add_filter( 'wp_authenticate_user', [  $this, 'zen_wp_login_post' ], 30, 2 );

    }

}	

$zencaptchaPlugin = new WP_LOGIN_BY_ZEN();
