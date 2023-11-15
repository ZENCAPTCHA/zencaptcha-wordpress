<?php
class PB_FORMS_LOGIN_BY_ZEN{
    private $error_message;

    public function inject_zencaptcha_widget_pb_login($login_form, array $form_args) {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;

        $login_button = '<p class="login-submit">';

		return str_replace( $login_button, zencaptcha_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style>' . $login_button, $login_form );

    }
    
    public function verify_zencaptcha_pb_login( $user, string $password){

        if ( ! did_action( 'wppb_process_login_start' ) ) {
			return $user;
        }
        
        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert();
            $code = 'error';
            return new WP_Error( $code, $error_message, 400 );
        }

        $verifyemail = "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
            $code = 'error';
            return new WP_Error( $code, $error_message, 400 );
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
            $code = 'error';
            return new WP_Error( $code, $error_message, 400 );
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access();
            $code = 'error';
            return new WP_Error( $code, $error_message, 400 );
        }

        return $user;

    }



    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-profilebuilderlogin-activate')) {
            return;
        }

        add_filter( 'wppb_login_form_before_content_output', [ $this, 'inject_zencaptcha_widget_pb_login' ], 10, 2 );
        add_filter( 'wp_authenticate_user', [ $this, 'verify_zencaptcha_pb_login' ], 10, 2 );
 
        
    }

}

$zencaptchaPlugin = new PB_FORMS_LOGIN_BY_ZEN();
