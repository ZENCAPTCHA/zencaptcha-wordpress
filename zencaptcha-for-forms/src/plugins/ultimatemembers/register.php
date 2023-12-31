<?php
//email check only in register

class UM_FORMS_REGISTRATION_BY_ZEN{

    public function inject_zencaptcha_widget_um_register() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        $um = UM();
        $fields = $um->fields();

        $error_message="";
		if ( $fields->is_error( "zencaptcha_failed_try_again_Alert" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert();
        }
        else if ( $fields->is_error( "zencaptcha_check_again_Alert" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
        }
        else if ( $fields->is_error( "zencaptcha_use_valid_email_Alert" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert();
        }
        else if ( $fields->is_error( "zencaptcha_no_access" ) ) {
			$error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access();
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
            echo '<div style="color:red;"><b>'.$error_message.'</b><br></div>'.zencaptcha_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style>';
        }
        else{
            echo zencaptcha_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style>';
        }
		
    }
    
    public function verify_zencaptcha_um_register(array $submitted_data, array $form_data = []){
        $email = '';
        if( isset($submitted_data['user_email'])) {
            $email = $submitted_data['user_email'];
        }

        if ( isset( $form_data['mode'] ) && $form_data['mode'] !== 'register') {
            return;
        }

        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert();
            UM()->form()->add_error( 'zencaptcha_failed_try_again_Alert', $error_message );
            return;
        }

        $verifyemail = get_option('zen-ultimatemember-verifyemail') ? $email : "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
            UM()->form()->add_error( 'zencaptcha_check_again_Alert', $error_message );
            return;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
            UM()->form()->add_error( 'zencaptcha_check_again_Alert', $error_message );
            return;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert();
            UM()->form()->add_error( 'zencaptcha_use_valid_email_Alert', $error_message );
            return;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access();
            UM()->form()->add_error( 'zencaptcha_no_access', $error_message );
            return;
        }

        return;

    }


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-ultimatememberregister-activate')) {
            return;
        }

        add_filter( 'um_after_register_fields', [ $this, 'inject_zencaptcha_widget_um_register' ], 100 );
        add_action( 'um_submit_form_errors_hook__registration', [ $this, 'verify_zencaptcha_um_register' ], 10, 2 );
 
        
    }

}

$zencaptchaPlugin = new UM_FORMS_REGISTRATION_BY_ZEN();
