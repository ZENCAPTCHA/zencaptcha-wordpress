<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ZENCA_Avada_Forms {

public function zenca_inject_widget_avada($html, $args){
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if ( false === strpos( $html, '<button type="submit"' ) ) {
        return $html;
    }

    return zenca_insert_html_widget($zencaptchainstance) . "<style>.zenc-captcha{margin-bottom:10px;}</style>".$html;
}


public function zenca_verify_zencaptcha_avada() {
    //v1: $form_data = $_POST['formData'];
    $form_data = isset( $_POST['formData'] ) ?
			filter_var( wp_unslash( $_POST['formData'] ), FILTER_SANITIZE_FULL_SPECIAL_CHARS ) :
			[];
    $form_data = wp_parse_args( str_replace( '&amp;', '&', $form_data ) );

    $solution = $form_data['zenc-captcha-solution'] ?? '';
    $useremail = $form_data['email'] ?? '';
    if ( empty( $solution ) ) {
        $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
        die( wp_json_encode( [ 'status' => 'error', 'info' => [ 'zencaptcha' => $error_message ] ] ) );
    }

    $verifyemail = get_option('zenca-avada-verifyemail') ? $useremail  : "";
    $verification = zenca_solution_verification($solution, $verifyemail);

    if (!$verification["success"]) {
        $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
        die( wp_json_encode( [ 'status' => 'error', 'info' => [ 'zencaptcha' => $error_message ] ] ) );
    }

    if($verification["message"] != "valid"){
        $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
        die( wp_json_encode( [ 'status' => 'error', 'info' => [ 'zencaptcha' => $error_message ] ] ) );
    }

    if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
        //only works for LITE etc.. users!
        $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
        die( wp_json_encode( [ 'status' => 'error', 'info' => [ 'zencaptcha' => $error_message ] ] ) );
    }

    //be careful, this can block you out from your own admin panel if you are in a country that you blocked!
    $countrycode = $verification["countrycode"];
    $country_blacklist = get_option('zenca_country_blacklist');
    $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
    if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
        $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
        die( wp_json_encode( [ 'status' => 'error', 'info' => [ 'zencaptcha' => $error_message ] ] ) );
    }

    return $demo_mode;
        
}

public function __construct() {
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-avada-activate')) {
        return;
    }

    add_action( 'fusion_element_button_content', [ $this, 'zenca_inject_widget_avada' ], 10, 2 );
    add_filter( 'fusion_form_demo_mode', [ $this, 'zenca_verify_zencaptcha_avada' ] );
}

}

$zencaptchaPlugin = new ZENCA_Avada_Forms();