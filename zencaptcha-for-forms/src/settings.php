<?php
defined('ABSPATH') or die('Access Denied');



?>

<div class="wrap">
    <h1><?php _e('Zencaptcha Settings', 'zencaptcha-for-forms') ?></h1>


    <?php if(!ZENCAPTCHA_MAIN::$instance->are_keys_set()) { 
        $current_domain = $_SERVER['HTTP_HOST'];
        $current_domain = preg_replace('/^www\./', '', $current_domain);
        
        ?>
        <div id="setting-error-settings_updated" class="settings-error error">
            <p>
                <?php printf(__('Get your Site Key and Secret Key at <a href="%s">Zencaptcha.com</a>. <b>How it works:</b> Add your website (for example enter <b>%s</b>) to your Zencaptcha.com dashboard and copy the Sitekey and Secretkey that are automatically generated with your website. Paste the Sitekey and Secretkey into the fields on the current Wordpress settings page.', 'zencaptcha-for-forms'), 'https://www.zencaptcha.com/?ref=wp', $current_domain); ?>
                </p>
            <div class="zencaptcha-notice-button">
                <a href="<?php echo esc_url(ZENCAPTCHA_REGISTRATION_URL); ?>" class="button button-primary" target="_blank">
                <?php _e('Get Site Key + Secret Key', 'zencaptcha-for-forms') ?>
                </a>
            </div>
            <br>
        </div>
                    
    <?php }
    else{
        ?>
        <p>📊 <?php _e('Want to see your statistics, add websites or upgrade your plan for premium features? Go to your ZENCAPTCHA dashboard.', 'zencaptcha-for-forms')?>
        <a href="<?php echo esc_url(ZENCAPTCHA_DASHBOARD_URL); ?>" class="install-now button" target="_blank">
                <?php _e('ZENCAPTCHA Dashboard', 'zencaptcha-for-forms') ?>
                </a>
                </p>
                <hr>
                <?php
    } ?>

    <form method="post" action="options.php">
        <?php settings_fields('zencaptcha-settings-group'); ?>

        <?php do_settings_sections('zencaptcha-admin'); ?>

        <table class="form-table">
            <tr class="zencaptcha-general-site-key">
                <th scope="row">
                    <label for="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_sitekey;?>">
                        <?php _e('Zencaptcha Site Key', 'zencaptcha-for-forms') ?>
                    </label>
                </th>
                <td>
                    <input type="text" required="required" class="regular-text" id="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_sitekey;?>" name="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_sitekey;?>" value="<?php echo esc_attr(get_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey)); ?>" />
                </td>
            </tr>
            <tr class="zencaptcha-general-secret-key">
                <th scope="row">
                    <label for="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_secret_key;?>">
                        <?php _e('Zencaptcha Secret Key', 'zencaptcha-for-forms') ?>
                    </label>
                </th>
                <td>
                    <input type="password" required="required" class="regular-text" id="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_secret_key;?>" name="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_secret_key;?>" value="<?php echo esc_attr(get_option(ZENCAPTCHA_MAIN::$zencaptcha_secret_key)); ?>" />
                </td>
            </tr>

            <?php
                $widget_lang = get_option(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang);
                $widget_lang_value = isset($widget_lang) ? esc_attr($widget_lang) : 'auto';
            ?>
        </table>


<div class="grid-container">
    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/wordpress-logo.png'); ?>" alt="WordPress">
        <br><h2>WordPress</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wp-login" name="zen-wp-login" value="1" <?php if(get_option('zen-wp-login')){print('checked');}?>>
                <?php _e('Login', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wp-register" name="zen-wp-register" value="1" <?php if(get_option('zen-wp-register')){print('checked');}?>>
                <?php _e('Register', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wp-resetpass" name="zen-wp-resetpass" value="1" <?php if(get_option('zen-wp-resetpass')){print('checked');}?>>
                <?php _e('Reset Password', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wp-comments-guests" name="zen-wp-comments-guests" value="1" <?php if(get_option('zen-wp-comments-guests')){print('checked');}?>>
                <?php _e('Comments (Guests)', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wp-comments-loggedin" name="zen-wp-comments-loggedin" value="1" <?php if(get_option('zen-wp-comments-loggedin')){print('checked');}?>>
                <?php _e('Comments (Logged in users)', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wp-verifyemail" name="zen-wp-verifyemail" value="1" <?php if(get_option('zen-wp-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>

    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/woocommerce-logo.png'); ?>" alt="WooCommerce">
        <br><h2>WooCommerce</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-woo-login" name="zen-woo-login" value="1" <?php if(get_option('zen-woo-login')){print('checked');}?>>
                <?php _e('Login', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-woo-register" name="zen-woo-register" value="1" <?php if(get_option('zen-woo-register')){print('checked');}?>>
                <?php _e('Register', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-woo-resetpass" name="zen-woo-resetpass" value="1" <?php if(get_option('zen-woo-resetpass')){print('checked');}?>>
                <?php _e('Reset Password', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-woo-checkout" name="zen-woo-checkout" value="1" <?php if(get_option('zen-woo-checkout')){print('checked');}?>>
                <?php _e('Checkout', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        
        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-woo-verifyemail" name="zen-woo-verifyemail" value="1" <?php if(get_option('zen-woo-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/elementor-pro-logo.png'); ?>" alt="Elementor Pro">
        <br><h2>Elementor Pro</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-elementor-activate" name="zen-elementor-activate" value="1" <?php if(get_option('zen-elementor-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-elementor-verifyemail" name="zen-elementor-verifyemail" value="1" <?php if(get_option('zen-elementor-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/wpforms-logo.png'); ?>" alt="WPForms">
        <br><h2>WPForms (and WPForms lite) </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wpforms-activate" name="zen-wpforms-activate" value="1" <?php if(get_option('zen-wpforms-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-wpforms-verifyemail" name="zen-wpforms-verifyemail" value="1" <?php if(get_option('zen-wpforms-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/contact-form-7-logo.png'); ?>" alt="Contact Form 7">
        <br><h2>Contact Form 7 </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-cf7-activate" name="zen-cf7-activate" value="1" <?php if(get_option('zen-cf7-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-cf7-verifyemail" name="zen-cf7-verifyemail" value="1" <?php if(get_option('zen-cf7-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/gravity-form-logo.png'); ?>" alt="Gravity Forms">
        <br><h2>Gravity Forms </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-gravity-activate" name="zen-gravity-activate" value="1" <?php if(get_option('zen-gravity-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-gravity-verifyemail" name="zen-gravity-verifyemail" value="1" <?php if(get_option('zen-gravity-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <!--Coming in the future-Ninja
        <div class="section">
        <img class="plugin-logos" src="php echo zencap_plugin_url('assets/images/ninja-forms-logo.png'); ?>" alt="Ninja Forms">
        <br><h2>Ninja Forms </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ninja-activate" name="zen-ninja-activate" value="1" php if(get_option('zen-ninja-activate')){print('checked');}?>>
                php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ninja-verifyemail" name="zen-ninja-verifyemail" value="1" php if(get_option('zen-ninja-verifyemail')){print('checked');}?>>
                php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>-->

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/mailchimp-logo.png'); ?>" alt="Mailchimp Forms">
        <br><h2>Mailchimp Forms (MC4WP) </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-mailchimp-activate" name="zen-mailchimp-activate" value="1" <?php if(get_option('zen-mailchimp-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-mailchimp-verifyemail" name="zen-mailchimp-verifyemail" value="1" <?php if(get_option('zen-mailchimp-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/hf-logo.png'); ?>" alt="HTML Forms">
        <br><h2>HTML Forms</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-htmlforms-activate" name="zen-htmlforms-activate" value="1" <?php if(get_option('zen-htmlforms-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-htmlforms-verifyemail" name="zen-htmlforms-verifyemail" value="1" <?php if(get_option('zen-htmlforms-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>


    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/avada-logo.png'); ?>" alt="Avada Forms">
        <br><h2>Avada Form Builder</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-avada-activate" name="zen-avada-activate" value="1" <?php if(get_option('zen-avada-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                ℹ️ <?php _e('Use "email" as the Field Name for the email address in your form.', 'zencaptcha-for-forms') ?>
                <br><br>
                <input class="zencap-checkbox" type="checkbox" id="zen-avada-verifyemail" name="zen-avada-verifyemail" value="1" <?php if(get_option('zen-avada-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/forminator-logo.png'); ?>" alt="Forminator">
        <br><h2>Forminator</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-forminator-activate" name="zen-forminator-activate" value="1" <?php if(get_option('zen-forminator-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-forminator-verifyemail" name="zen-forminator-verifyemail" value="1" <?php if(get_option('zen-forminator-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/fluent-forms-logo.png'); ?>" alt="Fluentform">
        <br><h2>Fluentform Forms</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-fluentform-activate" name="zen-fluentform-activate" value="1" <?php if(get_option('zen-fluentform-activate')){print('checked');}?>>
                <?php _e('Activate', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-fluentform-verifyemail" name="zen-fluentform-verifyemail" value="1" <?php if(get_option('zen-fluentform-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/divi-logo.png'); ?>" alt="Divi">
        <br><h2>Divi</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-divilogin-activate" name="zen-divilogin-activate" value="1" <?php if(get_option('zen-divilogin-activate')){print('checked');}?>>
                <?php _e('Login', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-divicontact-activate" name="zen-divicontact-activate" value="1" <?php if(get_option('zen-divicontact-activate')){print('checked');}?>>
                <?php _e('Contact', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-diviemailoptin-activate" name="zen-diviemailoptin-activate" value="1" <?php if(get_option('zen-diviemailoptin-activate')){print('checked');}?>>
                <?php _e('Email Optin', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-divi-verifyemail" name="zen-divi-verifyemail" value="1" <?php if(get_option('zen-divi-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/profile-builder-banner.png'); ?>" alt="Profile Builder">
        <br><h2>Profile Builder</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-profilebuilderlogin-activate" name="zen-profilebuilderlogin-activate" value="1" <?php if(get_option('zen-profilebuilderlogin-activate')){print('checked');}?>>
                <?php _e('Login', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-profilebuilderregister-activate" name="zen-profilebuilderregister-activate" value="1" <?php if(get_option('zen-profilebuilderregister-activate')){print('checked');}?>>
                <?php _e('Register', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-profilebuilderresetpass-activate" name="zen-profilebuilderresetpass-activate" value="1" <?php if(get_option('zen-profilebuilderresetpass-activate')){print('checked');}?>>
                <?php _e('Reset Password', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-profilebuilder-verifyemail" name="zen-profilebuilder-verifyemail" value="1" <?php if(get_option('zen-profilebuilder-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo zencap_plugin_url('assets/images/ultimate-member-logo.png'); ?>" alt="Ultimate Member">
        <br><h2>Ultimate Member</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ultimatememberlogin-activate" name="zen-ultimatememberlogin-activate" value="1" <?php if(get_option('zen-ultimatememberlogin-activate')){print('checked');}?>>
                <?php _e('Login', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ultimatememberregister-activate" name="zen-ultimatememberregister-activate" value="1" <?php if(get_option('zen-ultimatememberregister-activate')){print('checked');}?>>
                <?php _e('Register', 'zencaptcha-for-forms') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ultimatememberresetpass-activate" name="zen-ultimatememberresetpass-activate" value="1" <?php if(get_option('zen-ultimatememberresetpass-activate')){print('checked');}?>>
                <?php _e('Reset Password', 'zencaptcha-for-forms') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ultimatemember-verifyemail" name="zen-ultimatemember-verifyemail" value="1" <?php if(get_option('zen-ultimatemember-verifyemail')){print('checked');}?>>
                <?php _e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha-for-forms') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

</div>


<table class="form-table">
            <tr class="zencaptcha-widget-lang">
                <th scope="row">
                    <label for="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_sitekey;?>">
                    🗣️ <?php _e('Language', 'zencaptcha-for-forms') ?>
                    </label>
                </th>
            <td>
                <select autcomplete="none" type="select" name="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_widget_lang; ?>" id="<?php echo ZENCAPTCHA_MAIN::$zencaptcha_widget_lang; ?>">
                <option value="auto" <?php selected($widget_lang_value, 'auto'); ?>>
                    <?php _e('Automatic', 'zencaptcha-for-forms'); ?>
                </option>
                <?php 
                    foreach (ZENCAPTCHA_WIDGET_LANGUAGES as $lang_code => $lang_name) {
                        echo '<option value="' . $lang_code . '" ' . selected($lang_code, $widget_lang_value) . '>' . esc_html(__($lang_name, 'zencaptcha-for-forms')) . '</option>';
                    }
                ?>

                </select>
                <div class="description"><?php _e('<b>Recommended: </b> Leave this set to "Automatic" as the correct language will automatically be displayed to the user. If you select a language here, the widget will only be displayed in that language to every user.', 'zencaptcha-for-forms') ?></div>
            </td>
            </tr>

            <tr class="zencaptcha-autoload" style="vertical-align: top">
                <th scope="row">
                <!--🧩-->🛡️ <?php _e('The user needs to', 'zencaptcha-for-forms') ?>
                </th>
                <td>
                    <p>
                        <label for="checkbehaviour_fields">
                            <input type="radio" id="checkbehaviour_fields" name="widgetstarts" value="focus" <?php checked(get_option('widgetstarts'), "focus") ?> />
                            <?php _e('click into a form field for the robot check to begin.', 'zencaptcha-for-forms') ?>
                        </label>
                        <br />

                        <label for="checkbehaviour_widgets">
                            <input type="radio" id="checkbehaviour_widgets" name="widgetstarts" value="none" <?php checked(get_option('widgetstarts'), "none")  ?> />
                            <?php _e('click on the ZENCAPTCHA-Widget for the robot check to begin.', 'zencaptcha-for-forms') ?>
                        </label>
                    </p>
                </td>
            </tr>

            <!--<tr valign="top">
                <th scope="row">
                    <label for="email_blacklist">
                    📧 php _e('Custom email black list', 'zencaptcha-for-forms') ?> (php echo count(array_filter(explode("\n", get_option('email_blacklist')))); ?>)
                    </label>
                </th>
                <td>
                    <textarea rows="3" class="regular-text" id="email_blacklist" name="email_blacklist">php echo esc_attr(get_option('email_blacklist')); ?></textarea>
                    <div class="description">php _e('<b>Include custom email address domains you want to block. <br>One domain name per line.</b>', 'zencaptcha-for-forms') ?></div>
                </td>
            </tr>-->
            <tr style="vertical-align: top">
                <th scope="row">
                    <label for="country_blacklist">
                    🌍 <?php _e('Custom country black list', 'zencaptcha-for-forms') ?> (<?php echo count(array_filter(explode("\n", get_option('country_blacklist')))); ?>)
                    </label>
                </th>
                <td>
                    <textarea rows="3" class="regular-text" id="country_blacklist" name="country_blacklist"><?php echo esc_attr(get_option('country_blacklist')); ?></textarea>
                    <div class="description"><?php _e('<b>Include custom countries you want to block. <br>One country code per line.</b><br> Use ISO 3166-1 - (ISO 3166-1 alpha-2 codes). For example: DE for Germany, RU for Russia. <a href="https://www.iban.com/country-codes" target="_blank">See full list here.</a>', 'zencaptcha-for-forms') ?></div>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>

    <p>
        <?php
        printf(__('Thank you for using the <a href="%s">Zencaptcha</a> plugin by <a href="%s">Zencaptcha.com</a> .', 'zencaptcha-for-forms'),
            'https://wordpress.org/plugins/zencaptcha-for-forms/',
            'https://www.zencaptcha.com/?ref=wp-thanks'
        );
        ?>
    </p>
    <p><?php _e('Help us create a safer and more privacy-respecting web!', 'zencaptcha-for-forms'); ?></p>
    <p>
    <a href="https://www.facebook.com/zencaptchacom" target="_blank">
            <?php _e('Like us on Facebook', 'zencaptcha-for-forms') ?>
        </a> &middot;
        <a href="https://instagram.com/zencaptcha" target="_blank">
            <?php _e('Follow us on Instagram', 'zencaptcha-for-forms') ?>
        </a> &middot;
        <a href="https://www.facebook.com/sharer/sharer.php?u=zencaptcha.com" target="_blank">
            <?php _e('Share on Facebook', 'zencaptcha-for-forms') ?>
        </a>

    </p>
    <p style="opacity:0.8">Zencaptcha - Version <?php echo ZENCAPTCHA_MAIN::$version ?>.</p>
</div>