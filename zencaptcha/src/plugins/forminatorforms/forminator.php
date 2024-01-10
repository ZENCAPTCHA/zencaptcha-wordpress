<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ZENCA_FORMINATORFORMS{

    private $email_value="";
    const FORMINATOR_SCRIPT_VERSION = '0.0.1';

    public function zenca_inject_zencaptcha_widget_forminator( $html, string $button ) {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;

		return str_replace( '<button ', zenca_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style> <button ', $html );
    }
    
    public function zenca_verify_forminator($can_show, int $id, array $form_settings){
        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
            return [
                'can_submit' => false,
                'error'      => $error_message,
            ];
        }

        $verifyemail = get_option('zenca-forminator-verifyemail') ? $this->email_value : "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            return [
                'can_submit' => false,
                'error'      => $error_message,
            ];
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            return [
                'can_submit' => false,
                'error'      => $error_message,
            ];
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
            return [
                'can_submit' => false,
                'error'      => $error_message,
            ];
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
            return [
                'can_submit' => false,
                'error'      => $error_message,
            ];
        }

        return $can_show;

    }

    public function zenca_check_form_data( $submit_errors, $form_id, $field_data_array ) {
        $this->email_value='';
        foreach ($field_data_array as $arr) {
            $fieldvalue = isset($arr['value']) ? $arr['value'] : '';
            $fieldname = isset($arr['name']) ? $arr['name'] : '';
            if($fieldname=='email-1'){
                $this->email_value=$fieldvalue;
            }
        }

        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
            $submit_errors[]['email-1'] = $error_message;
            return $submit_errors;
        }

        $verifyemail = get_option('zenca-forminator-verifyemail') ? $this->email_value : "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            $submit_errors[]['email-1'] = $error_message;
            return $submit_errors;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
            $submit_errors[]['email-1'] = $error_message;
            return $submit_errors;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
            $submit_errors[]['email-1'] = $error_message;
            return $submit_errors;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
            $submit_errors[]['email-1'] = $error_message;
            return $submit_errors;
        }
    
        return $submit_errors;
    }

    public function zenca_enqueue_scripts_forminator(){
        wp_enqueue_script(
			'zencaptcha-forminator-helper',
			zenca_plugin_url('src/plugins/forminatorforms/script.js'),
			[],
			self::FORMINATOR_SCRIPT_VERSION,
			true
		);
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-forminator-activate')) {
            return;
        }

        add_action( 'wp_print_footer_scripts', [ $this, 'zenca_enqueue_scripts_forminator' ], 9 );
        add_filter( 'forminator_render_button_markup', [ $this, 'zenca_inject_zencaptcha_widget_forminator' ], 10, 2 );
        if(get_option('zenca-forminator-verifyemail')){
            add_filter( 'forminator_custom_form_submit_errors', [ $this, 'zenca_check_form_data'], 20, 3 );
        }
        else{
            add_filter( 'forminator_cform_form_is_submittable', [ $this, 'zenca_verify_forminator' ], 30, 3 );
        }    
        
    }

}

$zencaptchaPlugin = new ZENCA_FORMINATORFORMS();
