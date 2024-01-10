<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function zenca_wpcf7_dev_display_zencaptcha($formelements){
    $cf7_script_version = '0.0.22';

    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-cf7-activate')) {
        return $formelements;
    }

    wp_enqueue_script(
        'zencaptcha-cf7-script-enqueue', 
        zenca_plugin_url('src/plugins/cf7') . '/script.js', 
        array(), 
        $cf7_script_version, 
        true
    );
    
    return preg_match('/<div.*class=".*zenc-label-container.*".*<\/div>/', $formelements) ? $formelements : $formelements . zenca_insert_html_widget($zencaptchainstance);
}



function zenca_wpcf7_spam_check_by_zencaptcha($spam, $submission){
    if ( $spam ) {
        return $spam;
    }

    $contactForm = $submission->get_contact_form();

    $email_value = "";
    $tags = $contactForm->scan_form_tags();
    foreach ($tags as $tag) {
        if ($tag->type == 'email*') { //only REQUIRED email fields! others may be ignored!
            $tagType = $tag->type;
            $tagName = $tag->name;
            $email_value = $submission->get_posted_data($tagName);
            break;
        }
    }


	$zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-cf7-activate')) {
        return $spam;
    }

    if ($spam) {
        $submission->set_status( 'zencaptcha_try_again' );
        $submission->set_response( $contactForm->message( 'spam' ) ); 
        return true;
    }

    $solution = zenca_retrieve_solution_from_post();
    if ( empty( $solution ) ) {
        $spam=true;
        $submission->set_status( 'zencaptcha_try_again' );
        $submission->set_response( $contactForm->message( 'spam' ) ); 
        return $spam;
    }

    $verifyemail = get_option('zenca-cf7-verifyemail') ? $email_value : "";
    $verification = zenca_solution_verification($solution, $verifyemail);

    if (!$verification["success"]) {
        $submission->set_status( 'acceptance_missing' );
        $submission->set_response( $contactForm->message( 'validation_error' ) ); 
        return true;
    }

    if ($verification["message"] != "valid") {
        $submission->set_status( 'acceptance_missing' );
        $submission->set_response( $contactForm->message( 'validation_error' ) ); 
        return true;
    }

    if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
        //only works for LITE etc.. users!
        $submission->set_status( 'zencaptcha_use_valid_email' );
        $submission->set_response( $contactForm->message( 'validation_error' ) ); 
        return true;
    }

    $countrycode = $verification["countrycode"];
    $country_blacklist = get_option('zenca_country_blacklist');
    $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
    if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
        $submission->set_status( 'zencaptcha_no_access' ); 
        $submission->set_response( $contactForm->message( 'validation_error' ) );      
        return true;
    }

    return $spam;
}





add_filter('wpcf7_form_elements', 'zenca_wpcf7_dev_display_zencaptcha', 100, 1);
add_filter('wpcf7_spam', 'zenca_wpcf7_spam_check_by_zencaptcha', 100, 2);

