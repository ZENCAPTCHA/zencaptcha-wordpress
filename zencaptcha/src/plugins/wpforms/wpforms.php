<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function zenca_processURLParameters($fieldIdPrefix) {
    $fieldData = [];

    isset($_GET[$fieldIdPrefix . '0']) ?
    $fieldData[0]=sanitize_text_field($_GET[$fieldIdPrefix . '0']) :
    '';
    isset($_GET[$fieldIdPrefix . '1']) ?
    $fieldData[1]=sanitize_text_field($_GET[$fieldIdPrefix . '1']) :
    '';
    isset($_GET[$fieldIdPrefix . '2']) ?
    $fieldData[2]=sanitize_text_field($_GET[$fieldIdPrefix . '2']) :
    '';
    isset($_GET[$fieldIdPrefix . '3']) ?
    $fieldData[3]=sanitize_text_field($_GET[$fieldIdPrefix . '3']) :
    '';
    isset($_GET[$fieldIdPrefix . '4']) ?
    $fieldData[4]=sanitize_text_field($_GET[$fieldIdPrefix . '4']) :
    '';
    isset($_GET[$fieldIdPrefix . '5']) ?
    $fieldData[5]=sanitize_text_field($_GET[$fieldIdPrefix . '5']) :
    '';
    isset($_GET[$fieldIdPrefix . '6']) ?
        $fieldData[6]=sanitize_text_field($_GET[$fieldIdPrefix . '6']) :
        '';


    /*V1: foreach ($_GET as $key => $value) {
        if (strpos($key, $fieldIdPrefix) === 0) {
            $fieldId = substr($key, strlen($fieldIdPrefix));
            $decodedValue = $value;
            $sanitizedValue = sanitize_text_field($decodedValue);
            $fieldData[$fieldId] = $sanitizedValue;
        }
    }*/

    return $fieldData;
}

function zenca_wpf_dev_frontend_output_after( $form_data, $form ){
    if (isset($_GET['form'])) {
        $fieldIdPrefix = '_err_';
        $formValue = isset($_GET['form']) ? sanitize_text_field($_GET['form']) : '';
        $fieldData= zenca_processURLParameters($fieldIdPrefix);
    
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('form')) {
                    if (urlParams.has('wpf_error_message')) {
                        const captchaElement = document.querySelector('.zenc-captcha');
                        if (captchaElement) {
                            const viewportHeight = window.innerHeight;
                            const elementHeight = captchaElement.getBoundingClientRect().height;
                            const scrollPosition = captchaElement.getBoundingClientRect().top + window.scrollY - (viewportHeight / 2 - elementHeight / 2);
                            window.scrollTo(0, scrollPosition);
                        }
                    }";
    
        foreach ($fieldData as $fieldId => $fieldValue) {
            echo "document.getElementById('wpforms-" . esc_html($formValue) . "-field_" . esc_html($fieldId) . "').value = '" . esc_js($fieldValue) . "';\n";
        }
    
        echo "
    }
            });
        </script>";
    }
}

function zenca_wpf_loaded() {
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-wpforms-activate')) {
        return;
    }

    if (isset($_GET['wpf_error_message'])) {

         add_action( 'wpforms_frontend_output_after', 'zenca_wpf_dev_frontend_output_after', 10, 2 );

         //v1: $errormessage = urldecode($_GET['wpf_error_message']);

         $errormessage = sanitize_text_field(wp_unslash(urldecode($_GET['wpf_error_message'])));

         if($errormessage=="zencaptcha_failed_try_again_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
        }
        else if($errormessage=="zencaptcha_check_again_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_check_again_Alert_no_html();
        }
        else if($errormessage=="zencaptcha_use_valid_email_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_use_valid_email_Alert_no_html();
        }
        else if($errormessage=="zencaptcha_no_access"){
            $error_message=ZENCAPTCHA_MAIN::zenca_no_access_no_html();
        }
        else if($errormessage=="zencaptcha_fill_in_all_fields_Alert"){
            $error_message=ZENCAPTCHA_MAIN::zenca_fill_in_all_fields_Alert_no_html();
        }
        else{
            $error_message=ZENCAPTCHA_MAIN::zenca_failed_try_again_Alert_no_html();
        }

        wpforms()->process->errors[$_GET['form']]['footer'] = sprintf(
            esc_html__( '%s', 'zencaptcha' ),
            $error_message
        );
    }

}


function zenca_wpf_dev_display_submit_before(){
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-wpforms-activate')) {
        return;
    }

    echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance));

    //needs to overwrite as wpforms is obsessed with !important: https://wpforms.com/docs/how-to-style-wpforms-with-custom-css-beginners-guide/
    
    echo "<style>
    .zenc-spinner {width: 28px !important;height: 28px !important;display: inline-block !important;position: absolute !important;top: 0 !important;left: 0 !important;border: 3px solid #0000001a !important;border-left-color: #003399 !important;border-radius: 50% !important;animation: zenc-spin 1.3s linear infinite !important;box-sizing: border-box !important;}@keyframes zenc-spin {0% {transform: rotate(0deg) !important;}100% {transform: rotate(360deg) !important;}}.zenc-circular-progress-bar, .zenc-checkmark {position: absolute !important;width: 35px !important;height: 35px !important;top: 0 !important;left: 0 !important;}.zenc-circle {transition: stroke-dasharray 0.5s linear !important;}.zenc-left {position: relative !important;display: table !important;top: 0 !important;height: 100% !important;}.zenc-label {color: rgb(85, 85, 85) !important;font-size: 14px !important;}.zenc-left-container {display: table-cell !important;vertical-align: middle !important;}.zenc-label-container {position: relative !important;display: inline-block !important;height: 100% !important;width: 170px !important;}.zenc-label-td {position: relative !important;display: table !important;top: 0 !important;height: 100% !important;}.zenc-label-tc {display: table-cell !important;vertical-align: middle !important;}.zenc-left-content {position: relative !important;width: 30px !important;height: 30px !important;margin: 0 15px !important;cursor: pointer !important;}.zenc-checkbox {position: absolute !important;width: 28px !important;height: 28px !important;border-width: 1px !important;border-style: solid !important;border-color: rgb(145, 145, 145) !important;border-radius: 4px !important;background-color: #ffffff !important;top: 0 !important;left: 0 !important;}.zenc-captcha {position: relative !important;box-sizing: content-box !important;width: 300px !important;height: 74px !important;padding: 0 !important;margin: 0 !important;margin-bottom: 10px !important;border-width: 1px !important;border-style: solid !important;border-radius: 4px !important;border-color: rgb(224, 224, 224) !important;background-color: rgb(250, 250, 250) !important;display: block !important;font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, 'Helvetica Neue', Arial, sans-serif !important;}.zenc-captcha b {font-weight: 700 !important;}.zenc-container {display: flex !important;align-items: center !important;height: 74px !important;}.zenc-success .zenc-icon {animation: 1s ease-in both zenc-fade-in !important;}.zenc-content {white-space: nowrap !important;display: flex !important;flex-direction: column !important;margin: 4px 6px 0 10px !important;overflow-x: auto !important;flex-grow: 1 !important;}.zenc-captcha-solution {display: none !important;}.zenc-err-url {text-decoration: underline !important;font-size: 0.9em !important;}@keyframes zenc-fade-in {from {opacity: 0 !important;}to {opacity: 1 !important;}}.zenc-css-spinner {width: 28px !important;height: 28px !important;display: inline-block !important;position: absolute !important;top: 0 !important;left: 0 !important;border: 4px solid #0000001a !important;border-left-color: blue !important;border-radius: 50% !important;animation: zenc-css-spin 1s linear infinite !important;box-sizing: border-box !important;}@keyframes zenc-css-spin {0% {transform: rotate(0deg) !important;}100% {transform: rotate(360deg) !important;}}       
    </style>";
 
}

function zenca_wpf_dev_process( $fields, $entry, $form_data){
    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-wpforms-activate')) {
        return;
    }
    $email_value = '';
    $emailalreadyfound = false;

    $url_parameters = array(
        'form' => $form_data['id']
    );

        foreach ($fields as $field_id => $field) {
            $field_id = $field['id'];
            if($field['value']){
              $field_value = $field['value'];
              $url_parameters['_err_'.$field_id] = sanitize_text_field($field_value);  
            }
    
            if (get_option('zenca-wpforms-verifyemail') && $field['type'] === 'email' && !$emailalreadyfound) {
                if(!$email_value){
                    $email_value = $field['value'];
                    $emailalreadyfound = true;
                }
            }
        }


    $solution = zenca_retrieve_solution_from_post();
    if ( empty( $solution ) ) {
        $url_parameters['wpf_error_message'] = "zencaptcha_failed_try_again_Alert";
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }
    
    $verifyemail = get_option('zenca-wpforms-verifyemail') ? $email_value : "";
    $verification = zenca_solution_verification($solution, $verifyemail);

    if (!$verification["success"]) {
        $url_parameters['wpf_error_message'] = "zencaptcha_failed_try_again_Alert";
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    if($verification["message"] != "valid"){
        $url_parameters['wpf_error_message'] = "zencaptcha_check_again_Alert";
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }


    if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
        //only works for LITE etc.. users!
        $url_parameters['wpf_error_message'] = "zencaptcha_use_valid_email_Alert";
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    $countrycode = $verification["countrycode"];
    $country_blacklist = get_option('zenca_country_blacklist');
    $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
    if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
        $url_parameters['wpf_error_message'] = "zencaptcha_no_access";       
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }
}

add_action('wpforms_display_submit_before', 'zenca_wpf_dev_display_submit_before', 20);
add_action( 'wp_loaded', 'zenca_wpf_loaded');
add_action( 'wpforms_process', 'zenca_wpf_dev_process', 10, 3 );	