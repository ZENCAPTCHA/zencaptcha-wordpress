<?php
//email check only in register

class PB_FORMS_REGISTRATION_BY_ZEN{

    private $useremail="";
    private $error_message;

    public function inject_zencaptcha_widget_pb_register() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;

        return zencaptcha_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style>';
		
    }
    
    public function verify_zencaptcha_pb_register($output_field_errors, $form_fields, $global_request, $form_type){

        if ($form_type != 'register') {
            return $output_field_errors;
        }

        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $this->error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert();
            $output_field_errors['zencerror'] =$this->error_message;
            return $output_field_errors;
        }

        $verifyemail = get_option('zen-profilebuilder-verifyemail') ? $this->useremail  : "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $this->error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
            $output_field_errors['zencerror'] =$this->error_message;
            return $output_field_errors;
        }

        if($verification["message"] != "valid"){
            $this->error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert();
            $output_field_errors['zencerror'] =$this->error_message;
            return $output_field_errors;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $this->error_message=ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert();
            $output_field_errors['zencerror'] =$this->error_message;
            return $output_field_errors;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $this->error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access();
            $output_field_errors['zencerror'] =$this->error_message;
            return $output_field_errors;
        }

        return $output_field_errors;

    }

    public function wppb_zencaptcha_email($message, $field, $request_data, $form_location){
        $this->useremail = isset($request_data['email']) ? $request_data['email'] : '';
        return $message;
    }

    public function general_top_error_message( $top_error_message ) {
		if ( ! $this->error_message ) {
			return $top_error_message;
		}

		return $top_error_message . '<p class="wppb-error">' . $this->error_message . '</p>';
	}


    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-profilebuilderregister-activate')) {
            return;
        }

        add_filter( 'wppb_after_form_fields', [ $this, 'inject_zencaptcha_widget_pb_register' ], 100 );
        add_filter( 'wppb_check_form_field_default-e-mail', [ $this, 'wppb_zencaptcha_email' ], 20, 4 );
        add_filter( 'wppb_output_field_errors_filter', [ $this, 'verify_zencaptcha_pb_register' ], 30, 4 );
        add_filter( 'wppb_general_top_error_message', [ $this, 'general_top_error_message' ] );
 
        
    }

}

$zencaptchaPlugin = new PB_FORMS_REGISTRATION_BY_ZEN();
