<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ZENCA_FLUENTFORMS{
    private $useremail="";

    public function zenca_add_zencaptcha_widget_fluentforms($submit_button, $form){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-fluentform-activate')) {
            return;
        }

		echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance))."<style>.zenc-captcha{margin-bottom:10px;}</style>";
        /*v2: return sprintf(
            '%s<style>%s</style>',
            wp_kses_post(zenca_insert_html_widget($zencaptchainstance)),
            '.zenc-captcha{margin-bottom:10px;}'
        );*/
    }


  public function zenca_email_zencaptcha_check_fluentforms($errorMessage, $field, $formData, $fields, $form) {
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


    public function zenca_verify_zencaptcha_fluentforms($insert_data, $data, $form ){
        
        $extend_reload_script = "<script>zencap.reset();</script>";
        $zencaptcha_solution = $data['zenc-captcha-solution'] ?? '';
        if ( empty( $zencaptcha_solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
            wp_send_json(
                [
                    'errors' => [
                        'zencaptcha-response' => [ $error_message ],
                    ],
                ],
                422
            );
        }

        $verifyemail = get_option('zenca-fluentform-verifyemail') ? $this->useremail : "";
        $verification = zenca_solution_verification($zencaptcha_solution, $verifyemail);
        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
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
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
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
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
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
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
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
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-fluentform-activate')) {
            return;
        }

        //TODO: do not render the widget in conversational form + do not verify 

        add_action( 'fluentform/render_item_submit_button', [ $this, 'zenca_add_zencaptcha_widget_fluentforms' ], 10, 2 );
        add_filter('fluentform/validate_input_item_input_email', [ $this, 'zenca_email_zencaptcha_check_fluentforms' ], 20, 5 );
        add_filter( 'fluentform/before_insert_submission', [ $this, 'zenca_verify_zencaptcha_fluentforms'], 30, 3 );
    }

}

$zencaptchaPlugin = new ZENCA_FLUENTFORMS();