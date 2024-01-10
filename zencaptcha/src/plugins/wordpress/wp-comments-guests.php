<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function zenca_wp_comment_after(){

    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
    if (!$zencaptchainstance->are_keys_set()) {
        return;
    }

    if (is_user_logged_in() && get_option('zenca-wp-comments-loggedin')) {
        ZENCAPTCHA_MAIN::$skipcaptcha=true;
        echo wp_kses_post(zenca_insert_html_widget($zencaptchainstance));
    }
    else if (!is_user_logged_in() && get_option('zenca-wp-comments-guests')) {
        ZENCAPTCHA_MAIN::$skipcaptcha=true;
        echo zenca_insert_html_widget($zencaptchainstance);
    }

    if (isset($_GET['error_message'])) {
        $posttext = sanitize_text_field($_GET['posttext']);
        $author= sanitize_text_field($_GET['author']);
        $errormessage = sanitize_text_field(wp_unslash(urldecode($_GET['error_message'])));

        if($errormessage=="zencaptcha_failed_try_again_Alert"){
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
       
        echo "<div class='error-message' style='color: red; background-color: white; border-radius: 10px; padding: 10px;margin-bottom:10px;font-size:16px;'>" . wp_kses_post($error_message) . "</div>
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
                    }";
            echo "document.getElementById('comment').value = '" . esc_js($posttext) . "';\n";  
            echo "document.getElementById('author').value = '" . esc_js($author) . "';\n";   
        echo "
    }
            });
        </script>";
    }
 
}


function zenca_wp_comment_post($data){

    $error_message="";

    $url_parameters = array(
        'error_message' => $error_message,
        'author' => sanitize_text_field($data['comment_author']),
        'posttext' => sanitize_text_field($data['comment_content']),
    );


    $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;

    if (ZENCAPTCHA_MAIN::$skipcaptcha) {
        ZENCAPTCHA_MAIN::$skipcaptcha=false;
        return $data;
    }

    if (!$zencaptchainstance->are_keys_set()) {
        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
        $error_message="zencaptcha_fill_in_all_fields_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    if ($data["user_id"] == 0 && !get_option('zenca-wp-comments-guests')) {
        return $data;
    }
    else if ($data["user_id"] != 0 && !get_option('zenca-wp-comments-loggedin')) {
        //not check email
        return $data;
    }

    if (empty($data['comment_author']) || empty($data['comment_author_email']) || empty($data['comment_content'])) {
        $error_message="zencaptcha_fill_in_all_fields_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }
    else if (!filter_var($data['comment_author_email'], FILTER_VALIDATE_EMAIL)) {
        // Customize the error message for an invalid email
        $error_message="zencaptcha_use_valid_email_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

    $posteremail = $data["user_id"] == 0 ? $data["comment_author_email"] : '';


    $solution = zenca_retrieve_solution_from_post();
    if ( empty( $solution ) ) {
        $error_message="zencaptcha_failed_try_again_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }
    
    $verifyemail = get_option('zenca-wp-verifyemail') ? $posteremail : "";
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

    if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
        //only works for LITE etc.. users!
        $error_message="zencaptcha_use_valid_email_Alert";
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }

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


    if($error_message !=""){
        $url_parameters['error_message'] = $error_message;
        $redirect_url = add_query_arg($url_parameters, wp_get_referer() ?: home_url());
        wp_safe_redirect($redirect_url);
        exit;
    }
        
        

    return $data;
}



add_action( 'comment_form_after_fields', 'zenca_wp_comment_after', 20, 0 );
add_action( 'comment_form_logged_in_after', 'zenca_wp_comment_after', 20, 0 );
add_filter( 'preprocess_comment', 'zenca_wp_comment_post', 10, 1 );	