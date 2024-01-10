<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ZENCA_Divi_Contact {
    //TODO: show Error message when invalid email!

    private $useremail = '';
    private $has_error = false;
    const DIVI_SCRIPT_VERSION = '0.0.6';
    const ZENCA_DIVI_HANDLE = 'zencaptcha-divi';

    public function zenca_enqueue_divi_script() {
        wp_enqueue_script(
			self::ZENCA_DIVI_HANDLE,
			zenca_plugin_url('src/plugins/divi/script.js'),
			[ 'jquery'],
			self::DIVI_SCRIPT_VERSION,
			true
        );
    } 

    public function zenca_inject_widget_divi_contact($output, $slug){
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if ( ! is_string( $output ) || et_core_is_fb_enabled() ) {
            return $output;
        }

        $errorHTML="";
        if (isset($_GET['error_message'])) {

                $errormessage = isset($_GET['error_message']) ?
        sanitize_text_field(wp_unslash(urldecode($_GET['error_message']))) :
        '';
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
            $errorHTML = " <div class='error-message' style='color: red; background-color: white; border-radius: 10px; padding: 10px;margin-bottom:10px;font-size:16px;'>" . wp_kses_post($error_message) . "</div>
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

        $capture = '<div class="et_contact_bottom_container">';
        $newHTML = $errorHTML.wp_kses_post(zenca_insert_html_widget($zencaptchainstance)) . '<style>.zenc-captcha{margin-bottom:25px !important;float:right !important;}</style><div style="clear: both;"></div>'.$capture;
        return str_replace($capture, $newHTML, $output);
    }


    public function zenca_verify_divi_contact( $value, string $tag, $attr, array $m ) {

        if ($tag !== 'et_pb_contact_form') {
            return $value;
        }
        

        $solution = zenca_retrieve_solution_from_post();
        if ( empty( $solution ) ) {
            $this->has_error = true; 
        }
    
        
        //v1: $postedEmail = $_POST['et_pb_contact_email_0'];
        $postedEmail = isset($_POST['et_pb_contact_email_0']) ?
        sanitize_text_field($_POST['et_pb_contact_email_0']) :
        '';
        $this->useremail = $postedEmail;
        $verifyemail = get_option('zenca-divi-verifyemail') ? $this->useremail  : "";
        $verification = zenca_solution_verification($solution, $verifyemail);
    
        if (!$verification["success"]) {
            $this->has_error = true; 
        }
    
        if($verification["message"] != "valid"){
            $this->has_error = true; 
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //TODO error message.
            //only works for LITE etc.. users!
            $this->has_error = true; 
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('zenca_country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $this->has_error = true; 
        }

        return $value;
    }

    function zenca_shortcode_attributes($attrs, $unused, $slug) {
        
        if ($slug == 'et_pb_contact_form') {
        if ($this->has_error) {
            $attrs['use_spam_service'] = 'on';
        }
        }
    
        return $attrs;
    
    }

    public function __construct() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
            if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-divicontact-activate')) {
                return;
            }

            add_filter( 'et_pb_contact_form_shortcode_output', [ $this, 'zenca_inject_widget_divi_contact' ], 10, 2 );
            add_filter( 'pre_do_shortcode_tag', [ $this, 'zenca_verify_divi_contact' ], 20, 4 );
            add_filter( 'et_pb_module_shortcode_attributes', [ $this, 'zenca_shortcode_attributes' ], 20, 3 );
            add_action( 'wp_enqueue_scripts', [ $this, 'zenca_enqueue_divi_script' ] );
    }

}

$zencaptchaPlugin = new ZENCA_Divi_Contact();