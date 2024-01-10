<?php
if ( ! defined( 'ABSPATH' ) ) exit;
class ZENCA_PB_FORMS_RESET{
    private $error_message;
    private $username_email = '';

    public function zenca_inject_zencaptcha_widget_pb_reset($html) {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;

        $submit_button = '<p class="form-submit">';

		return str_replace( $submit_button, zenca_insert_html_widget($zencaptchainstance).'<style>.zenc-captcha{margin-bottom:10px;}</style>' . $submit_button, $html );

    }
    
    public function zenca_verify_zencaptcha_pb_reset( $output, string $tag, $attr, array $m){

        if ( 'wppb-recover-password' !== $tag ) {
			return $output;
        }
        
        $post_value = isset($_POST['action']) ? sanitize_text_field(wp_unslash($_POST['action'])) : '';

        if (!isset($_POST['action']) || ('recover_password' && 'recover_password' !== $post_value)) {
            return $output;
        }
        
        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $this->error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert();
            $this->username_email = isset( $_POST['username_email'] ) ?
			sanitize_text_field( wp_unslash( $_POST['username_email'] ) ) :
            '';
            $_POST['username_email'] = '';
        }

        $verifyemail = "";
        $verification = zenca_solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $this->error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert();
            $this->username_email = isset( $_POST['username_email'] ) ?
			sanitize_text_field( wp_unslash( $_POST['username_email'] ) ) :
            '';
            $_POST['username_email'] = '';
        }

        if($verification["message"] != "valid"){
            $this->error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert();
            $this->username_email = isset( $_POST['username_email'] ) ?
			sanitize_text_field( wp_unslash( $_POST['username_email'] ) ) :
            '';
            $_POST['username_email'] = '';
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $this->error_message=ZENCAPTCHA_MAIN::zenca_no_access();
            $this->username_email = isset( $_POST['username_email'] ) ?
			sanitize_text_field( wp_unslash( $_POST['username_email'] ) ) :
            '';
            $_POST['username_email'] = '';
        }

        return $output;

    }


    public function zenca_recover_password_displayed_zencaptcha( $message ) {
		if ( ! $this->error_message ) {
			return $message;
		}

		$_POST['username_email'] = $this->username_email;

		return '<p class="wppb-warning">' . $this->error_message . '</p>';
	}

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-profilebuilderresetpass-activate')) {
            return;
        }

        add_action( 'wppb_recover_password_before_content_output', [ $this, 'zenca_inject_zencaptcha_widget_pb_reset' ] );
		add_filter( 'pre_do_shortcode_tag', [ $this, 'zenca_verify_zencaptcha_pb_reset' ], 10, 4 );
		add_filter( 'wppb_recover_password_displayed_message1', [ $this, 'zenca_recover_password_displayed_zencaptcha' ] );
    }

}

$zencaptchaPlugin = new ZENCA_PB_FORMS_RESET();
