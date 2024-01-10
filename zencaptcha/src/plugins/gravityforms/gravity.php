<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class ZENCA_GRAVITYFORMS{

    private $notification_message;
    private $useremail="";
    private $requiredEmailFound=false;

    private function setNotificationMessage($message) {
        $this->notification_message = $message;
    }

    // Example method to get the error message
    private function getNotificationMessage() {
        return $this->notification_message;
    }

    public function zenca_enqueue_zencaptcha_gravity_script(){
        wp_deregister_script('zencaptcha-widget');
        wp_enqueue_script('zencaptcha-widget-img', zenca_plugin_url('src/frontend/widget-imgs.js'), array(), ZENCA_WIDGET_VERSION, true);
    }

    public function zenca_add_zencaptcha_to_submit_button_gravity( $submit_button_input, $form ) {
        wp_deregister_script('zencaptcha-widget');
        wp_enqueue_script('zencaptcha-widget-img', zenca_plugin_url('src/frontend/widget-imgs.js'), array(), ZENCA_WIDGET_VERSION, true);
    
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set() || !get_option('zenca-gravity-activate')) {
            return $submit_button_input;
        }

        if ( is_admin() ) { //no need to show it during setup
			return $submit_button_input;
		}

        $style="<style>.gform_footer{flex-direction: column; !important;}.zencaptcha-widget-div{flex:0;}.input-container-gravity-submit{flex:0;margin-top:10px;order: 1;}</style>";
    
        $extended_div = $style."<div class='zencaptcha-widget-div'>".zenca_insert_html_widget($zencaptchainstance)."</div><div class='input-container-gravity-submit'>".$submit_button_input."</div>";
        return $extended_div;
    }

    public function zenca_verify_zencaptcha_gravity($validation_result, $context){

        if ( ! isset( $_POST['gform_submit'] ) ) {
            return $validation_result;
        }
    
        if ( isset( $_POST['gpnf_parent_form_id'] ) ) {
            return $validation_result;
        }
    
        $this->useremail = get_option('zenca-gravity-verifyemail') ? $this->useremail : "";

        $this->setNotificationMessage(zenca_verify_post($this->useremail));
    
        if ( $this->getNotificationMessage() === null ) {
            return $validation_result;
        }
    
        $validation_result['is_valid'] = false;
        $validation_result['form']['validationSummary'] = '1';
    
        return $validation_result;
    }
    
    public function zenca_gravity_form_validation_errors( $errors, array $form ) {
        if ( $this->getNotificationMessage() === null ) {
            return $errors;
        }
    
        $error['field_selector'] = '';
        $error['field_label']    = 'zencaptcha';
        $error['message']        = $this->notification_message;
    
        $errors[] = $error;
    
        return $errors;
    }

    public function zenca_gravity_form_validation_errors_markup( $validation_errors_markup, array $form ) {
		if ( $this->getNotificationMessage() === null ) {
			return $validation_errors_markup;
		}

		return preg_replace(
			'#<a .+zencaptcha: .+?/a>#',
			'<div>' . $this->notification_message . '</div>',
			$validation_errors_markup
		);
    }
    

    public function zenca_gravity_field_validation_zencaptcha ( $result, $value, $form, $field ) {
        if ( $field->get_input_type() !== 'email' || ! rgar( $result, 'is_valid' ) ) {
            return $result;
        }
        //maybe use required field only in future - $field->isRequired
        if (is_array($value) && !empty($value)) {
            $this->useremail = $value[0];
        }
        else{
            $this->useremail =$value;
        }
        return $result;
    }

    public function __construct() {
        add_action( 'gform_enqueue_scripts', [$this,'zenca_enqueue_zencaptcha_gravity_script'], 10, 2 );
        add_filter( 'gform_submit_button', [$this,'zenca_add_zencaptcha_to_submit_button_gravity'], 10, 2 );
        add_filter( 'gform_field_validation', [$this,'zenca_gravity_field_validation_zencaptcha'], 20, 4 );
        add_filter( 'gform_validation', [$this,'zenca_verify_zencaptcha_gravity'], 40, 2 );

        add_filter( 'gform_form_validation_errors', [$this,'zenca_gravity_form_validation_errors'], 40, 2 );
        add_filter( 'gform_form_validation_errors_markup', [$this,'zenca_gravity_form_validation_errors_markup'], 40, 2 );
    }
    

    
}

$zencaptchaPlugin = new ZENCA_GRAVITYFORMS();