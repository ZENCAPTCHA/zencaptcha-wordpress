<?php
class FLUENTFORMS_BY_ZEN{
    private $useremail="";

    public function add_zencaptcha_widget_fluentforms($submit_button, $form){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-fluentform-activate')) {
            return;
        }

		echo zencaptcha_insert_html_widget($zencaptchainstance)."<style>.zenc-captcha{margin-bottom:10px;}</style>";
    }


  public function email_zencaptcha_check_fluentforms($errorMessage, $field, $formData, $fields, $form) {
        $fieldName = $field['name'];
        $this->useremail ='';
        if (empty($formData[$fieldName])) {
            return ""; //depends on required or not - if it is required probably show an error!
        }
        $fieldvalue = $formData[$fieldName];
        if (!empty($fieldvalue)){
            $this->useremail =$fieldvalue;
        }
        
    
        return "";
    
  }


    public function verify_zencaptcha_fluentforms($insert_data, $data, $form ){
        
        $extend_reload_script = "<script>zencap.reset();</script>";
        $zencaptcha_solution = $data['zenc-captcha-solution'] ?? '';
        if ( empty( $zencaptcha_solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html();
            wp_send_json(
                [
                    'errors' => [
                        'zencaptcha-response' => [ $error_message ],
                    ],
                ],
                422
            );
        }

        $verifyemail = get_option('zen-fluentform-verifyemail') ? $this->useremail : "";
        $verification = solution_verification($zencaptcha_solution, $verifyemail);
        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            wp_send_json(
                [
                    'errors' => [
                        'zencaptcha-response' => [ $error_message.$extend_reload_script ],
                    ],
                ],
                422
            );
        }
    
        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            wp_send_json(
                [
                    'errors' => [
                        'zencaptcha-response' => [ $error_message.$extend_reload_script ],
                    ],
                ],
                422
            );
        }
    
    
        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert_no_html();
            wp_send_json(
                [
                    'errors' => [
                        'zencaptcha-response' => [ $error_message.$extend_reload_script ],
                    ],
                ],
                422
            );
        }
    
        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html();
            wp_send_json(
                [
                    'errors' => [
                        'zencaptcha-response' => [ $error_message.$extend_reload_script ],
                    ],
                ],
                422
            );
        }

    }



    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-fluentform-activate')) {
            return;
        }

        //TODO: do not render the widget in conversational form + do not verify 

        add_action( 'fluentform/render_item_submit_button', [ $this, 'add_zencaptcha_widget_fluentforms' ], 10, 2 );
        add_filter('fluentform/validate_input_item_input_email', [ $this, 'email_zencaptcha_check_fluentforms' ], 20, 5 );
        add_filter( 'fluentform/before_insert_submission', [ $this, 'verify_zencaptcha_fluentforms'], 30, 3 );
    }

}

$zencaptchaPlugin = new FLUENTFORMS_BY_ZEN();