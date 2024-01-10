<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ZENCA_Divi_Login {

public function zenca_inject_zencaptcha_widget_divi_login($output, $slug){
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if ( ! is_string( $output ) || et_core_is_fb_enabled() ) {
        return $output;
    }

    $errorHTML="";
    if (isset($_GET['error_message'])) {
        $errormessage = sanitize_text_field(wp_unslash(urldecode($_GET['error_message'])));
        if($errormessage=="zencaptcha_login_data_errors_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_login_data_errors_Alert();
        }
        else if($errormessage=="zencaptcha_failed_try_again_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert();
        }
        else if($errormessage=="zencaptcha_use_valid_email_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert();
        }
        else if($errormessage=="zencaptcha_no_access"){
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access();
        }
        else if($errormessage=="zencaptcha_fill_in_all_fields_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_fill_in_all_fields_Alert();
        }
        else{
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert();
        }
        $errorHTML = "<div class='error-message' style='color: red; background-color: white; border-radius: 10px; padding: 10px;margin-bottom:10px;'>" . wp_kses_post($error_message) . "</div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('error_message')) {
                    if (urlParams.has('error_message')) {
                        const captchaElement = document.querySelector('.zenc-captcha');
                        if (captchaElement) {
                            const viewportHeight = window.innerHeight;
                            const elementHeight = captchaElement.getBoundingClientRect().height;
                            const scrollPosition = captchaElement.getBoundingClientRect().top + window.scrollY - (viewportHeight / 2 - elementHeight / 2);
                            window.scrollTo(0, scrollPosition);
                        }
                    }
                }
            });
        </script>";
    }

    $capture    = '/(<p>[\s]*?<button)/';
    $newHTML = $errorHTML.wp_kses_post(zenca_insert_html_widget($zencaptchainstance)) . "<style>.zenc-captcha{margin-bottom:10px;}</style> \n" . '$1';
    return preg_replace($capture, $newHTML, $output);
}


public function zenca_verify_zencaptcha_divi_login( $user, string $password ) {
    if ( ! isset( $_POST['et_builder_submit_button'] ) ) {
        return $user;
    }

    if (is_wp_error($user) && isset($user->errors['empty_username']) && isset($user->errors['empty_password'])) {
        $error_message="zencaptcha_login_data_errors_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    $solution = zenca_retrieve_solution_from_post();
    if ( empty( $solution ) ) {
        $error_message="zencaptcha_failed_try_again_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    $verifyemail="";
    $verification = zenca_solution_verification($solution, $verifyemail);

    if (!$verification["success"]) {
        $error_message="zencaptcha_failed_try_again_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    if($verification["message"] != "valid"){
        $error_message="zencaptcha_failed_try_again_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    //be careful, this can block you out from your own admin panel if you are in a country that you blocked!
    $countrycode = $verification["countrycode"];
    $country_blacklist = get_option('zenca_country_blacklist');
    $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
    if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
        $error_message="zencaptcha_no_access"; 
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit; 
    }
        

    return $user;
}

public function __construct() {
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-divilogin-activate')) {
            return;
        }

    add_filter( 'et_pb_login_shortcode_output', [ $this, 'zenca_inject_zencaptcha_widget_divi_login' ], 10, 2 );
    add_filter( 'wp_authenticate_user', [ $this, 'zenca_verify_zencaptcha_divi_login' ], 10, 2 );
}

}

$zencaptchaPlugin = new ZENCA_Divi_Login();