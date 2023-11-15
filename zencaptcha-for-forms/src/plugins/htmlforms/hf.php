<?php
class HF_BY_ZEN{

    const HF_SCRIPT_VERSION = '0.0.2';

    public function inject_zencaptcha_widget_mc4wp( $content ) {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
		return str_replace(
			'</form>',
            zencaptcha_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style></form>', 
            $content
		);
    }
    
    public function verify_zencaptcha_mc4wp( $errors, $form, $data ) {
        $email = '';
        if( isset($data['EMAIL']) ) {
            $email = $data['EMAIL'];
        }

        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $errors = 'zencaptcha_failed_try_again_Alert_no_html'; 
            return $errors;
        }

        $verifyemail = get_option('zen-htmlforms-verifyemail') ? $email : "";
        $verification = solution_verification($solution, $verifyemail);
        if (!$verification["success"]) {
            $errors = 'zencaptcha_check_again_Alert_no_html'; 
            return $errors;
        }

        if($verification["message"] != "valid"){
            $errors = 'zencaptcha_check_again_Alert_no_html'; 
            return $errors;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $errors = 'zencaptcha_use_valid_email_Alert_no_html'; 
            return $errors;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $errors = 'zencaptcha_no_access_no_html'; 
            return $errors;
        }     

		return $errors;
    }
    
    public function enqueue_scripts_htmlforms(){
        wp_enqueue_script(
			'zencaptcha-htmlforms-helper',
			zencap_plugin_url('src/plugins/htmlforms/script.js'),
			[],
			self::HF_SCRIPT_VERSION,
			true
		);
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-htmlforms-activate')) {
            return;
        }

        add_action( 'wp_print_footer_scripts', [ $this, 'enqueue_scripts_htmlforms' ], 9 );
        add_filter( 'hf_form_html', [ $this, 'inject_zencaptcha_widget_mc4wp' ], 10, 1 );
        add_filter( 'hf_validate_form', [ $this, 'verify_zencaptcha_mc4wp' ], 20, 3 ); 
        add_filter( 'hf_form_message_zencaptcha_failed_try_again_Alert_no_html', function( $message ) {
            return ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html();
        });
        add_filter( 'hf_form_message_zencaptcha_check_again_Alert_no_html', function( $message ) {
            return ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
        });
        add_filter( 'hf_form_message_zencaptcha_use_valid_email_Alert_no_html', function( $message ) {
            return ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert_no_html();
        });
        add_filter( 'hf_form_message_zencaptcha_no_access_no_html', function( $message ) {
            return ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html();
        });
        
    }

}

$zencaptchaPlugin = new HF_BY_ZEN();
