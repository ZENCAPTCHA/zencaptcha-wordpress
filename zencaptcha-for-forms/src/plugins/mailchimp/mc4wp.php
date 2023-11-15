<?php
class MC4WP_BY_ZEN{

    public function inject_zencaptcha_widget_mc4wp( $content, $form, $element ): string {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
		return str_replace(
			'<input type="submit"',
            zencaptcha_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style><input type="submit"', 
            $content
		);
    }
    
    public function verify_zencaptcha_mc4wp( $errors, $form ) {
        $email = '';
        if( isset($form->data['EMAIL']) ) {
            $email = $form->data['EMAIL'];
        }

        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $errors[] = 'zencaptcha_failed_try_again_Alert_no_html'; 
            return $errors;
        }

        $verifyemail = get_option('zen-mailchimp-verifyemail') ? $email : "";
        $verification = solution_verification($solution, $verifyemail);
        if (!$verification["success"]) {
            $errors[] = 'zencaptcha_check_again_Alert_no_html'; 
            return $errors;
        }

        if($verification["message"] != "valid"){
            $errors[] = 'zencaptcha_check_again_Alert_no_html'; 
            return $errors;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $errors[] = 'zencaptcha_use_valid_email_Alert_no_html'; 
            return $errors;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $errors[] = 'zencaptcha_no_access_no_html'; 
            return $errors;
        }     

		return $errors;
    }
    
    public function add_zencaptcha_error_messages( $messages, $form ) {
		$messages[ 'zencaptcha_failed_try_again_Alert_no_html' ] = [
            'type' => 'error',
            'text' => ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html(),
        ];

        $messages[ 'zencaptcha_check_again_Alert_no_html' ] = [
            'type' => 'error',
            'text' => ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html(),
        ];

        $messages[ 'zencaptcha_use_valid_email_Alert_no_html' ] = [
            'type' => 'error',
            'text' => ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert_no_html(),
        ];

        $messages[ 'zencaptcha_no_access_no_html' ] = [
            'type' => 'error',
            'text' => ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html(),
        ];

		return $messages;
	}

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zen-mailchimp-activate')) {
            return;
        }

        add_filter( 'mc4wp_form_messages', [ $this, 'add_zencaptcha_error_messages' ], 10, 2 );
        add_filter( 'mc4wp_form_content', [ $this, 'inject_zencaptcha_widget_mc4wp' ], 20, 3 ); 
        add_filter( 'mc4wp_form_errors', [ $this, 'verify_zencaptcha_mc4wp' ], 10, 2 );
        
    }

}

$zencaptchaPlugin = new MC4WP_BY_ZEN();
