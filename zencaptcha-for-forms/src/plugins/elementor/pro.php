<?php

use Elementor\Plugin;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;
use ElementorPro\Modules\Forms\Module;
use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;

class ELEMENTOR_FORMS_BY_ZEN {

    private $useremail="";

	const FIELD_ID = 'zencaptcha';
	const ZENCAPTCHA_ELEMENTOR_HANDLE = 'zencaptcha-elementor-pro';
	const ADMIN_HANDLE = 'admin-elementor-pro-zencaptcha';
    const ZENCAPTCHA_HANDLE = 'zencaptcha';
    
    const ELEM_SCRIPT_VERSION = '0.0.20';
    const ELEM_ADMIN_VERSION = '0.0.20';
    
    public function after_enqueue_scripts_zen() {
        /* TODO: show message to setup sitekey and secretkey!
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!is_zencaptcha_enabled()){
            message
        }
        */
		wp_enqueue_script(
			self::ADMIN_HANDLE,
			zencap_plugin_url('src/plugins/elementor/admin.js'),
			[ 'elementor-editor' ],
			self::ELEM_ADMIN_VERSION,
			true
		);
    }

    private function load_jquery_listener() {
        wp_enqueue_script(
			self::ZENCAPTCHA_ELEMENTOR_HANDLE,
			zencap_plugin_url('src/plugins/elementor/script.js'),
			[ 'jquery'],
			self::ELEM_SCRIPT_VERSION,
			true
        );
    }
    
    public function zencaptcha_footer_script_elementor() {
		wp_enqueue_script(
			self::ZENCAPTCHA_ELEMENTOR_HANDLE,
			zencap_plugin_url('src/plugins/elementor/script.js'),
			[ 'jquery'],
			self::ELEM_SCRIPT_VERSION,
			true
		);
    }

	public function register_action( Module $module ) {
		$module->add_component( self::FIELD_ID, $this );
	}

	protected static function get_zencaptcha_name(): string {
		return self::FIELD_ID;
	}

	public static function get_site_key() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
		return $zencaptchainstance->get_site_key();
	}

	public static function get_secret_key() {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
		return $zencaptchainstance->get_secret_key();
	}

	public static function is_zencaptcha_enabled(): bool {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
        if (!$zencaptchainstance->are_keys_set()) {
            return false;
        }
		return true;
	}

	protected static function get_script_handle(): string {
		return 'elementor-' . static::get_zencaptcha_name() . '-api';
	}
    

    public function elementor_form_email_field_validation_zen( $field, $record, $ajax_handler ) {

        if (empty($field['value'])) {
            $this->useremail = "";
            return;
        }
        
        $this->useremail = $field['value'];
        return;        
    }

	public function validation( Form_Record $record, Ajax_Handler $ajax_handler ) {
        $fields = $record->get_field( [ 'type' => static::get_zencaptcha_name() ] );

        if ( empty( $fields ) ) {
			return;
        }

        $field = current( $fields );

        $solution = retrieve_zencaptcha_solution_from_post();
        if ( empty( $solution ) ) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_failed_try_again_Alert_no_html();
            $ajax_handler->add_error( $field['id'], $error_message );
            return;
        }

        $verifyemail = get_option('zen-elementor-verifyemail') ? $this->useremail  : "";
        $verification = solution_verification($solution, $verifyemail);

        if (!$verification["success"]) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            $ajax_handler->add_error( $field['id'], $error_message );
            return;
        }

        if($verification["message"] != "valid"){
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_check_again_Alert_no_html();
            $ajax_handler->add_error( $field['id'], $error_message );
            return;
        }

        if (!empty($verifyemail) && ($verification["emailvalid"] === "invalid_email" || $verification["emailvalid"] === "disposable_email")) {
            //only works for LITE etc.. users!
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_use_valid_email_Alert_no_html();
            $ajax_handler->add_error( $field['id'], $error_message );
            return;
        }

        $countrycode = $verification["countrycode"];
        $country_blacklist = get_option('country_blacklist');
        $cleaned_country_blacklist = ZENCAPTCHA_MAIN::$instance->cleanListUppercase($country_blacklist);
        if (stripos($cleaned_country_blacklist, $countrycode) !== false) {
            $error_message=ZENCAPTCHA_MAIN::zencaptcha_no_access_no_html();
            $ajax_handler->add_error( $field['id'], $error_message );
            return;
        }

		$record->remove_field( $field['id'] );
	}


	public function render_field( array $item, int $item_index, Widget_Base $widget ) {
        $zencaptchainstance = ZENCAPTCHA_MAIN::$instance;
		$zencaptcha_html = '<div class="elementor-field" id="form-field-' . $item['custom_id'] . '">';

		$this->add_render_attributes( $item, $item_index, $widget );

		$data    = $widget->get_raw_data();
		$form_id = $data['settings']['form_id'] ?? 0;


		$zencaptcha_html .=
			'<div class="elementor-zencaptcha">' .
			zencaptcha_insert_html_widget($zencaptchainstance).
			'</div>';

		$zencaptcha_html .= '</div>';

		echo $zencaptcha_html;
	}


	protected function add_render_attributes( array $item, int $item_index, Widget_Base $widget ) {
		$widget->add_render_attribute(
			[
				static::get_zencaptcha_name() . $item_index => [
					'class'        => 'elementor-zencaptcha',
					'data-sitekey' => static::get_site_key()
				],
			]
		);
	}


	public function add_field_type( $field_types ): array {
		$field_types = (array) $field_types;

		$field_types[ self::FIELD_ID ] = __( 'ZENCAPTCHA', 'elementor-pro' );

		return $field_types;
	}


	public function modify_controls( Controls_Stack $controls_stack, array $args ) {
		$control_id   = 'form_fields';
		$control_data = Plugin::$instance->controls_manager->get_control_from_stack(
			$controls_stack->get_unique_name(),
			$control_id
		);

		$term = [
			'name'     => 'field_type',
			'operator' => '!in',
			'value'    => [ self::FIELD_ID ],
		];

		foreach (['width', 'required'] as $field) {
            $control_data['fields'][$field]['conditions']['terms'][] = $term;
        }

		Plugin::$instance->controls_manager->update_control_in_stack(
			$controls_stack,
			$control_id,
			$control_data,
			[ 'recursive' => true ]
		);
	}

    public function filter_zencaptcha_field( $item ): array {
		if ( isset( $item['field_type'] ) && static::get_zencaptcha_name() === $item['field_type'] ) {
			$item['field_label'] = false;
		}

		return $item;
	}

    public function init() {
		$this->load_jquery_listener();

		add_action( 'elementor_pro/forms/register/action', [ $this, 'register_action' ] );

		add_filter( 'elementor_pro/forms/field_types', [ $this, 'add_field_type' ] );
		add_action(
			'elementor/element/form/section_form_fields/after_section_end',
			[ $this, 'modify_controls' ],
			10,
			2
		);
		add_action(
			'elementor_pro/forms/render_field/' . static::get_zencaptcha_name(),
			[ $this, 'render_field' ],
			10,
			3
		);
		add_filter( 'elementor_pro/forms/render/item', [ $this, 'filter_zencaptcha_field' ] );

		if (self::is_zencaptcha_enabled()) {
            add_action( 'elementor_pro/forms/validation/email', [ $this, 'elementor_form_email_field_validation_zen'], 10, 3 );
			add_action( 'elementor_pro/forms/validation', [ $this, 'validation' ], 20, 2 );
		}
	}
    

    public function __construct() {
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'after_enqueue_scripts_zen' ] );
		add_action( 'elementor/init', [ $this, 'init' ] );

		add_action( 'wp_print_footer_scripts', [ $this, 'zencaptcha_footer_script_elementor' ], 9 );
    }
}


$zencaptchaPlugin = new ELEMENTOR_FORMS_BY_ZEN();