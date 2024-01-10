<?php
defined('ABSPATH') or die('Access Denied');



?>

<div class="wrap">
    <h1><?php esc_html_e('Zencaptcha Settings', 'zencaptcha') ?></h1>


    <?php if(!ZENCAPTCHA_MAIN::$instance->are_keys_set()) { 
        //v1: $current_domain = $_SERVER['HTTP_HOST'];
        $current_domain = isset($_SERVER['HTTP_HOST']) ?
        sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) :
        '';
        $current_domain = preg_replace('/^www\./', '', $current_domain);
        
        ?>
        <div id="setting-error-settings_updated" class="settings-error error">
            <p>
                <?php 
            $current_locale = get_user_locale();
            $locale_prefix = substr($current_locale, 0, 2);
            if ($current_locale === false) {
                $locale_prefix="en";
            }
            if ($locale_prefix === false) {
                $locale_prefix="en";
            }
    
            if ($locale_prefix === 'de') {
                printf(
                    wp_kses_post(
                            'Hol dir deinen Site-Key und Secret-Key auf <a href="%1$s">Zencaptcha.com</a>. <b>So funktioniert es:</b> Füge deine Website hinzu (zum Beispiel gib <b>%2$s</b> ein) in dein Zencaptcha.com-Dashboard und kopiere den automatisch generierten Site-Key und Secret-Key deiner Website. Füge den Site-Key und Secret-Key in die Felder auf der aktuellen WordPress-Einstellungsseite ein.'
                    ),
                    esc_url('https://www.zencaptcha.com/?ref=wp'),
                    esc_html($current_domain)
                );
            } elseif ($locale_prefix === 'fr') {
                printf(
                    wp_kses_post(
                            'Obtenez votre Site Key et votre Secret Key sur <a href="%1$s">Zencaptcha.com</a>. <b>Comment ça marche :</b> Ajoutez votre site web (par exemple, saisissez <b>%2$s</b>) dans votre tableau de bord Zencaptcha.com et copiez le Sitekey et le Secretkey qui sont automatiquement générés avec votre site web. Collez le Sitekey et le Secretkey dans les champs de la page actuelle des paramètres WordPress.'
                    ),
                    esc_url('https://www.zencaptcha.com/?ref=wp'),
                    esc_html($current_domain)
                );
            }
            else{
                printf(wp_kses_post(__(
                'Get your Site Key and Secret Key at <a href="%1$s">Zencaptcha.com</a>. <b>How it works:</b> Add your website (for example enter <b>%2$s</b>) to your Zencaptcha.com dashboard and copy the Sitekey and Secretkey that are automatically generated with your website. Paste the Sitekey and Secretkey into the fields on the current Wordpress settings page.'
        )),
        esc_url('https://www.zencaptcha.com/?ref=wp'),
        esc_html($current_domain)
    ); 
            }

?>
                </p>
            <div class="zencaptcha-notice-button">
                <a href="<?php echo esc_url(ZENCA_REGISTRATION_URL); ?>" class="button button-primary" target="_blank">
                <?php esc_html_e('Get Site Key + Secret Key', 'zencaptcha') ?>
                </a>
            </div>
            <br>
        </div>
                    
    <?php }
    else{
        ?>
        <p>📊 <?php esc_html_e('Want to see your statistics, add websites or upgrade your plan for premium features? Go to your ZENCAPTCHA dashboard.', 'zencaptcha')?>
        <a href="<?php echo esc_url(ZENCA_DASHBOARD_URL); ?>" class="install-now button" target="_blank">
                <?php esc_html_e('ZENCAPTCHA Dashboard', 'zencaptcha') ?>
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
                    <label for="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);?>">
                        <?php esc_html_e('Zencaptcha Site Key', 'zencaptcha') ?>
                    </label>
                </th>
                <td>
                    <input type="text" required="required" class="regular-text" id="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);?>" name="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);?>" value="<?php echo esc_attr(get_option(ZENCAPTCHA_MAIN::$zencaptcha_sitekey)); ?>" />
                </td>
            </tr>
            <tr class="zencaptcha-general-secret-key">
                <th scope="row">
                    <label for="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_secret_key);?>">
                        <?php esc_html_e('Zencaptcha Secret Key', 'zencaptcha') ?>
                    </label>
                </th>
                <td>
                    <input type="password" required="required" class="regular-text" id="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_secret_key);?>" name="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_secret_key);?>" value="<?php echo esc_attr(get_option(ZENCAPTCHA_MAIN::$zencaptcha_secret_key)); ?>" />
                </td>
            </tr>

            <?php
                $widget_lang = get_option(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang);
                $widget_lang_value = isset($widget_lang) ? esc_attr($widget_lang) : 'auto';
            ?>
        </table>


<div class="grid-container">
    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/wordpress-logo.png')); ?>" alt="WordPress">
        <br><h2>WordPress</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wp-login" name="zenca-wp-login" value="1" <?php if(get_option('zenca-wp-login')){print('checked');}?>>
                <?php esc_html_e('Login', 'zencaptcha') ?>
            </label>
        </div>

        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wp-register" name="zenca-wp-register" value="1" <?php if(get_option('zenca-wp-register')){print('checked');}?>>
                <?php esc_html_e('Register', 'zencaptcha') ?>
            </label>
        </div>
        
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wp-resetpass" name="zenca-wp-resetpass" value="1" <?php if(get_option('zenca-wp-resetpass')){print('checked');}?>>
                <?php esc_html_e('Reset Password', 'zencaptcha') ?>
            </label>
        </div>

        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wp-comments-guests" name="zenca-wp-comments-guests" value="1" <?php if(get_option('zenca-wp-comments-guests')){print('checked');}?>>
                <?php esc_html_e('Comments (Guests)', 'zencaptcha') ?>
            </label>
        </div>

        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wp-comments-loggedin" name="zenca-wp-comments-loggedin" value="1" <?php if(get_option('zenca-wp-comments-loggedin')){print('checked');}?>>
                <?php esc_html_e('Comments (Logged in users)', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wp-verifyemail" name="zenca-wp-verifyemail" value="1" <?php if(get_option('zenca-wp-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>

    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/woocommerce-logo.png')); ?>" alt="WooCommerce">
        <br><h2>WooCommerce</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-woo-login" name="zenca-woo-login" value="1" <?php if(get_option('zenca-woo-login')){print('checked');}?>>
                <?php esc_html_e('Login', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-woo-register" name="zenca-woo-register" value="1" <?php if(get_option('zenca-woo-register')){print('checked');}?>>
                <?php esc_html_e('Register', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-woo-resetpass" name="zenca-woo-resetpass" value="1" <?php if(get_option('zenca-woo-resetpass')){print('checked');}?>>
                <?php esc_html_e('Reset Password', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-woo-checkout" name="zenca-woo-checkout" value="1" <?php if(get_option('zenca-woo-checkout')){print('checked');}?>>
                <?php esc_html_e('Checkout', 'zencaptcha') ?>
            </label>
        </div>
        
        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-woo-verifyemail" name="zenca-woo-verifyemail" value="1" <?php if(get_option('zenca-woo-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/elementor-pro-logo.png')); ?>" alt="Elementor Pro">
        <br><h2>Elementor Pro</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-elementor-activate" name="zenca-elementor-activate" value="1" <?php if(get_option('zenca-elementor-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-elementor-verifyemail" name="zenca-elementor-verifyemail" value="1" <?php if(get_option('zenca-elementor-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/wpforms-logo.png')); ?>" alt="WPForms">
        <br><h2>WPForms (and WPForms lite) </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wpforms-activate" name="zenca-wpforms-activate" value="1" <?php if(get_option('zenca-wpforms-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-wpforms-verifyemail" name="zenca-wpforms-verifyemail" value="1" <?php if(get_option('zenca-wpforms-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/contact-form-7-logo.png')); ?>" alt="Contact Form 7">
        <br><h2>Contact Form 7 </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-cf7-activate" name="zenca-cf7-activate" value="1" <?php if(get_option('zenca-cf7-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-cf7-verifyemail" name="zenca-cf7-verifyemail" value="1" <?php if(get_option('zenca-cf7-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/gravity-form-logo.png')); ?>" alt="Gravity Forms">
        <br><h2>Gravity Forms </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-gravity-activate" name="zenca-gravity-activate" value="1" <?php if(get_option('zenca-gravity-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-gravity-verifyemail" name="zenca-gravity-verifyemail" value="1" <?php if(get_option('zenca-gravity-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <!--Coming in the future-Ninja
        <div class="section">
        <img class="plugin-logos" src="php echo esc_url(zenca_plugin_url('assets/images/ninja-forms-logo.png')); ?>" alt="Ninja Forms">
        <br><h2>Ninja Forms </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ninja-activate" name="zen-ninja-activate" value="1" php if(get_option('zen-ninja-activate')){print('checked');}?>>
                php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zen-ninja-verifyemail" name="zen-ninja-verifyemail" value="1" php if(get_option('zen-ninja-verifyemail')){print('checked');}?>>
                php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>-->

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/mailchimp-logo.png')); ?>" alt="Mailchimp Forms">
        <br><h2>Mailchimp Forms (MC4WP) </h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-mailchimp-activate" name="zenca-mailchimp-activate" value="1" <?php if(get_option('zenca-mailchimp-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-mailchimp-verifyemail" name="zenca-mailchimp-verifyemail" value="1" <?php if(get_option('zenca-mailchimp-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/hf-logo.png')); ?>" alt="HTML Forms">
        <br><h2>HTML Forms</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-htmlforms-activate" name="zenca-htmlforms-activate" value="1" <?php if(get_option('zenca-htmlforms-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-htmlforms-verifyemail" name="zenca-htmlforms-verifyemail" value="1" <?php if(get_option('zenca-htmlforms-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>


    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/avada-logo.png')); ?>" alt="Avada Forms">
        <br><h2>Avada Form Builder</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-avada-activate" name="zenca-avada-activate" value="1" <?php if(get_option('zenca-avada-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                ℹ️ <?php esc_html_e('Use "email" as the Field Name for the email address in your form.', 'zencaptcha') ?>
                <br><br>
                <input class="zencap-checkbox" type="checkbox" id="zenca-avada-verifyemail" name="zenca-avada-verifyemail" value="1" <?php if(get_option('zenca-avada-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/forminator-logo.png')); ?>" alt="Forminator">
        <br><h2>Forminator</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-forminator-activate" name="zenca-forminator-activate" value="1" <?php if(get_option('zenca-forminator-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-forminator-verifyemail" name="zenca-forminator-verifyemail" value="1" <?php if(get_option('zenca-forminator-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/fluent-forms-logo.png')); ?>" alt="Fluentform">
        <br><h2>Fluentform Forms</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-fluentform-activate" name="zenca-fluentform-activate" value="1" <?php if(get_option('zenca-fluentform-activate')){print('checked');}?>>
                <?php esc_html_e('Activate', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-fluentform-verifyemail" name="zenca-fluentform-verifyemail" value="1" <?php if(get_option('zenca-fluentform-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/divi-logo.png')); ?>" alt="Divi">
        <br><h2>Divi</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-divilogin-activate" name="zenca-divilogin-activate" value="1" <?php if(get_option('zenca-divilogin-activate')){print('checked');}?>>
                <?php esc_html_e('Login', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-divicontact-activate" name="zenca-divicontact-activate" value="1" <?php if(get_option('zenca-divicontact-activate')){print('checked');}?>>
                <?php esc_html_e('Contact', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-diviemailoptin-activate" name="zenca-diviemailoptin-activate" value="1" <?php if(get_option('zenca-diviemailoptin-activate')){print('checked');}?>>
                <?php esc_html_e('Email Optin', 'zencaptcha') ?>
            </label>
        </div>
        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-divi-verifyemail" name="zenca-divi-verifyemail" value="1" <?php if(get_option('zenca-divi-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/profile-builder-banner.png')); ?>" alt="Profile Builder">
        <br><h2>Profile Builder</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-profilebuilderlogin-activate" name="zenca-profilebuilderlogin-activate" value="1" <?php if(get_option('zenca-profilebuilderlogin-activate')){print('checked');}?>>
                <?php esc_html_e('Login', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-profilebuilderregister-activate" name="zenca-profilebuilderregister-activate" value="1" <?php if(get_option('zenca-profilebuilderregister-activate')){print('checked');}?>>
                <?php esc_html_e('Register', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-profilebuilderresetpass-activate" name="zenca-profilebuilderresetpass-activate" value="1" <?php if(get_option('zenca-profilebuilderresetpass-activate')){print('checked');}?>>
                <?php esc_html_e('Reset Password', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-profilebuilder-verifyemail" name="zenca-profilebuilder-verifyemail" value="1" <?php if(get_option('zenca-profilebuilder-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

    <div class="section">
        <img class="plugin-logos" src="<?php echo esc_url(zenca_plugin_url('assets/images/ultimate-member-logo.png')); ?>" alt="Ultimate Member">
        <br><h2>Ultimate Member</h2>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-ultimatememberlogin-activate" name="zenca-ultimatememberlogin-activate" value="1" <?php if(get_option('zenca-ultimatememberlogin-activate')){print('checked');}?>>
                <?php esc_html_e('Login', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-ultimatememberregister-activate" name="zenca-ultimatememberregister-activate" value="1" <?php if(get_option('zenca-ultimatememberregister-activate')){print('checked');}?>>
                <?php esc_html_e('Register', 'zencaptcha') ?>
            </label>
        </div>
        <div class="item">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-ultimatememberresetpass-activate" name="zenca-ultimatememberresetpass-activate" value="1" <?php if(get_option('zenca-ultimatememberresetpass-activate')){print('checked');}?>>
                <?php esc_html_e('Reset Password', 'zencaptcha') ?>
            </label>
        </div>

        <hr>
        <div class="item-premium">
            <label>
                <input class="zencap-checkbox" type="checkbox" id="zenca-ultimatemember-verifyemail" name="zenca-ultimatemember-verifyemail" value="1" <?php if(get_option('zenca-ultimatemember-verifyemail')){print('checked');}?>>
                <?php esc_html_e('+ Verify Email Adresses (Block invalid and disposable email addresses)', 'zencaptcha') ?> 
                <br><span class="premium-label">Lite</span> <span class="premium-label">PRO</span> <span class="premium-label">Enterprise</span>
            </label>
        </div>
    </div>

</div>


<table class="form-table">
            <tr class="zencaptcha-widget-lang">
                <th scope="row">
                    <label for="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_sitekey);?>">
                    🗣️ <?php esc_html_e('Language', 'zencaptcha') ?>
                    </label>
                </th>
            <td>
                <select autcomplete="none" type="select" name="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang); ?>" id="<?php echo esc_attr(ZENCAPTCHA_MAIN::$zencaptcha_widget_lang); ?>">
                <option value="auto" <?php selected($widget_lang_value, 'auto'); ?>>
                    <?php esc_html_e('Automatic', 'zencaptcha'); ?>
                </option>
                <?php 
                    foreach (ZENCA_WIDGET_LANGUAGES as $lang_code => $lang_name) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr($lang_code),
                            selected($lang_code, $widget_lang_value, false),
                            esc_html__($lang_name, 'zencaptcha')
                        );
                    }
                ?>

                </select>
                <div class="description"><?php echo wp_kses_post(__('<b>Recommended: </b> Leave this set to "Automatic" as the correct language will automatically be displayed to the user. If you select a language here, the widget will only be displayed in that language to every user.', 'zencaptcha')); ?></div>
            </td>
            </tr>

            <tr class="zencaptcha-autoload" style="vertical-align: top">
                <th scope="row">
                <!--🧩-->🛡️ <?php esc_html_e('The user needs to', 'zencaptcha') ?>
                </th>
                <td>
                    <p>
                        <label for="checkbehaviour_fields">
                            <input type="radio" id="checkbehaviour_fields" name="zenca_widgetstarts" value="focus" <?php checked(get_option('zenca_widgetstarts'), "focus") ?> />
                            <?php esc_html_e('click into a form field for the robot check to begin.', 'zencaptcha') ?>
                        </label>
                        <br />

                        <label for="checkbehaviour_widgets">
                            <input type="radio" id="checkbehaviour_widgets" name="zenca_widgetstarts" value="none" <?php checked(get_option('zenca_widgetstarts'), "none")  ?> />
                            <?php esc_html_e('click on the ZENCAPTCHA-Widget for the robot check to begin.', 'zencaptcha') ?>
                        </label>
                    </p>
                </td>
            </tr>

            <!--<tr valign="top">
                <th scope="row">
                    <label for="zenca_email_blacklist">
                    📧 php esc_html_e('Custom email black list', 'zencaptcha') ?> (php echo count(array_filter(explode("\n", get_option('zenca_email_blacklist')))); ?>)
                    </label>
                </th>
                <td>
                    <textarea rows="3" class="regular-text" id="zenca_email_blacklist" name="zenca_email_blacklist">php echo esc_attr(get_option('zenca_email_blacklist')); ?></textarea>
                    <div class="description">php esc_html_e('<b>Include custom email address domains you want to block. <br>One domain name per line.</b>', 'zencaptcha') ?></div>
                </td>
            </tr>-->
            <tr style="vertical-align: top">
                <th scope="row">
                    <label for="zenca_country_blacklist">
                    🌍 <?php esc_html_e('Custom country black list', 'zencaptcha') ?> (<?php esc_html_e( count(array_filter(explode("\n", get_option('zenca_country_blacklist'))))); ?>)
                    </label>
                </th>
                <td>
                    <textarea rows="3" class="regular-text" id="zenca_country_blacklist" name="zenca_country_blacklist"><?php echo esc_attr(get_option('zenca_country_blacklist')); ?></textarea>
                    <div class="description"><?php 
                    echo wp_kses_post(__('<b>Include custom countries you want to block. <br>One country code per line.</b><br> Use ISO 3166-1 - (ISO 3166-1 alpha-2 codes). For example: DE for Germany, RU for Russia. <a href="https://www.iban.com/country-codes" target="_blank">See full list here.</a>', 'zencaptcha'));
                     ?></div>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>

    <p>
        <?php
        printf(wp_kses_post(__('Thank you for using the <a href="%s">Zencaptcha</a> plugin by <a href="%s">Zencaptcha.com</a> .', 'zencaptcha')),
            'https://wordpress.org/plugins/zencaptcha/',
            'https://www.zencaptcha.com/?ref=wp-thanks'
        );
        ?>
    </p>
    <p><?php esc_html_e('Help us create a safer and more privacy-respecting web!', 'zencaptcha'); ?></p>
    <p>
    <a href="https://www.facebook.com/zencaptchacom" target="_blank">
            <?php esc_html_e('Like us on Facebook', 'zencaptcha') ?>
        </a> &middot;
        <a href="https://instagram.com/zencaptcha" target="_blank">
            <?php esc_html_e('Follow us on Instagram', 'zencaptcha') ?>
        </a> &middot;
        <a href="https://www.facebook.com/sharer/sharer.php?u=zencaptcha.com" target="_blank">
            <?php esc_html_e('Share on Facebook', 'zencaptcha') ?>
        </a>

    </p>
    <p style="opacity:0.8">Zencaptcha - Version <?php echo esc_attr(ZENCAPTCHA_MAIN::$version); ?>.</p>
</div>