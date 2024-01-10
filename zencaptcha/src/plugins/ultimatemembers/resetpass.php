<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ZENCA_UM_FORMS_RESET{

    public function zenca_inject_zencaptcha_widget_um_reset() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        $um = UM();
        $fields = $um->fields();

        $error_message="";
		if ( $fields->is_error( "zencaptcha_failed_try_again_Alert" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert();
        }
        else if ( $fields->is_error( "zencaptcha_check_again_Alert" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert();
        }
        else if ( $fields->is_error( "zencaptcha_use_valid_email_Alert" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert();
        }
        else if ( $fields->is_error( "zencaptcha_no_access" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zenca_no_access();
		}

        if($error_message!=""){
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                        const captchaElement = document.querySelector('.zenc-captcha');
                        if (captchaElement) {
                            const viewportHeight = window.innerHeight;
                            const elementHeight = captchaElement.getBoundingClientRect().height;
                            const scrollPosition = captchaElement.getBoundingClientRect().top + window.scrollY - (viewportHeight / 2 - elementHeight / 2);
                            window.scrollTo(0, scrollPosition);
                        }
                    });
             </script>";
            echo '<div style="color:red;"><b>'.wp_kses_post($error_message).'</b><br></div>'.zenca_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style>';
        }
        else{
            //v1: echo zenca_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-top:10px;margin-bottom:10px;}</style>';
            echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance))."<style>.zenc-captcha{margin-bottom:10px;margin-bottom:10px;}</style>";
        }
		
    }
    
    public function zenca_verify_zencaptcha_um_reset(array $submitted_data, array $form_data = []){

        if ( isset( $form_data['mode'] ) && $form_data['mode'] !== 'password') {
            return;
        }

        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert();
            UM()->form()->add_error( 'zencaptcha_failed_try_again_Alert', $error_message );
            return;
        }

        $verifyemail = "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert();
            UM()->form()->add_error( 'zencaptcha_check_again_Alert', $error_message );
            return;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert();
            UM()->form()->add_error( 'zencaptcha_check_again_Alert', $error_message );
            return;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access();
            UM()->form()->add_error( 'zencaptcha_no_access', $error_message );
            return;
        }

        return;

    }


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-ultimatememberresetpass-activate')) {
            return;
        }

        add_filter( 'um_after_password_reset_fields', [ $this, 'zenca_inject_zencaptcha_widget_um_reset' ], 100 );
        add_action( 'um_reset_password_errors_hook', [ $this, 'zenca_verify_zencaptcha_um_reset' ], 10, 2 );
 
        
    }

}

$zencaptchaPlugin = new ZENCA_UM_FORMS_RESET();