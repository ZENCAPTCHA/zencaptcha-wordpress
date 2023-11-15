===  ZENCAPTCHA ===
Contributors: cmdgo
Tags: fake email, block spam, anti-spam, disposable email, temporary email, captcha, antispam, spam, contact, recaptcha, zencaptcha, gdpr
Requires at least: 5.0
Tested up to: 6.4.1
Requires PHP: 7.0.0
Stable tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

ZENCAPTCHA protects WordPress website forms from being abused by bots, spam, fake and disposable email addresses without the use of cookies and is fully GDPR compliant (maximum data protection). Get rid of users with invalid or fake email addresses, not solely bots, to optimize the integrity of your user base. ZENCAPTCHA is made in the EU.

== Description ==
 
Say goodbye to annoying bots, spam, and fake or disposable email addresses, all without the need for cookies. ZENCAPTCHA goes beyond conventional defenses and also ensures GDPR compliance for maximum data protection.

Unlock the true potential of your user base by using ZENCAPTCHA to not only stop bots, but also eliminate users with invalid and temporary email addresses, improving the integrity of your community and keeping your mailing list clean.

== Why ZENCAPTCHA? ==

ZENCAPTCHA makes it more difficult for bots and fraudulent users to submit spam through forms on WordPress websites, as ZENCAPTCHA checks at least 2 attack vectors (bot behaviour and users with fake email addresses). Furthermore, ZENCAPTCHA is Made in the EU and adheres to strict privacy regulations.

So in general:

* Blocks bots and spam
* Blocks fake and temporary email addresses (for example: keeps your newsletter list clean)
* GDPR compliant
* Cookieless (so no cookie banner)
* Increases user authenticity

== Privacy Notices ==

ZENCAPTCHA is designed to comply with the strictest privacy laws, including GDPR and more.

This plugin does not:

* Track users;
* Write any user personal data to the database;
* Use cookies.

Your website user's IP address and browser data may be sent to the ZENCAPTCHA servers on pages where you have enabled ZENCAPTCHA protection. However, ZENCAPTCHA anonymises sensitive data which are processed only on EU servers for maximum privacy and compliance.
Your website visitors can always click on ZENCAPTCHA's privacy policy when using your website and see the ZENCAPTCHA widget informing them about the non-tracking, cookie-less and privacy first experience.

For more details, please see the ZENCAPTCHA privacy policy at:

* [zencaptcha.com](https://www.zencaptcha.com/data/)
 
== Installation ==
 
1. Upload `zencaptcha` folder to the `/wp-content/plugins/` directory  
2. Activate the plugin through the 'Plugins' menu in WordPress  
3. Enter your Site Key and API Key in the Settings -> ZENCAPTCHA menu in WordPress  
4. Enable the desired integrations
 
== Frequently Asked Questions ==

= How to use the ZENCAPTCHA plugin? =

The ZENCAPTCHA plugin supports WordPress core and a lot of other plugins with forms automatically. You should select the forms or plugins you want to protect on the ZENCAPTCHA Settings Page in your Wordpress Admin Dashboard.
To use Zenaptcha, you need to create an account at [www.zencaptcha.com](https://www.zencaptcha.com/). You will then need to add your website there, which will create a Site Key and Secret Key. Go back to your Wordpress Admin Dashboard where you will find the ZENCAPTCHA plugin settings page and enter the Site Key and Secret Key you created earlier.

If you want to use shortcodes instead of the supported plugins:
**Shortcode**
For non-standard cases, you can also use the `[zencaptcha]` shortcode provided by the ZENCAPTCHA plugin.

To make ZENCAPTCHA work, the shortcode must be inside a <form ...> ... </form> tag.

**Shortcode Example**
`
<form method="post">
	Username: <input type="text" name="username">
    Email: <input type="text" name="zenc-email"> 
    [zencaptcha]
	<input type="submit" value="Send">
</form>
`

Or like this:

`
?>
<form method="post">
	Username: <input type="text" name="username">
    Email: <input type="text" name="zenc-email"> 
    <?php echo do_shortcode( '[zencaptcha]' ); ?>
	<input type="submit" value="Send">
</form>
<?php
`

Verification of the ZENCAPTCHA solution from shortcode (POST request):

`
$result = zencaptcha_verify_post();

if ( null !== $result ) {
    echo esc_html( $result );
    // Stop processing of the form as the solution is invalid (or the email address is invalid or the country is blocked)
}
`

= Does ZENCAPTCHA have a Website and privacy policy? =

Yes, please take a look at our website here: [www.zencaptcha.com](https://zencaptcha.com/). You can also find the relevant privacy policy here: [www.zencaptcha.com/data](https://zencaptcha.com/data)

== Forms and Plugins Supported ==

* WordPress Login Form
* WordPress Register Form
* WordPress Reset Password Form
* WordPress Comments
* Avada
* Contact Form 7
* Divi
* Elementor Pro Forms
* FluentForm
* Gravity Forms
* HTML Form
* Mailchimp For Wordpress (MC4WP)
* Ultimate Member Login Form
* Ultimate Member Register Form
* Ultimate Member Reset Password Form
* WooCommerce Login Form
* WooCommerce Register Form
* WooCommerce Checkout Form
* WooCommerce Lost Password Form
* WPForms
* Profile Builder Login Form
* Profile Builder Register Form
* Profile Builder Reset Password Form
* Forminator

== Screenshots ==
1. Settings
2. Overview

== Changelog ==

= 1.0.0 =
* Plugin Created

== Upgrade Notice ==
= 1.0.0 =
* Initial Version
