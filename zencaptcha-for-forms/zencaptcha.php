<?php
/**
 * Plugin ZENCAPTCHA
 *
 * @author               zencaptcha
 * @license              GPL-2.0-or-later
 * @wordpress-plugin
 *
 * Plugin Name:          ZENCAPTCHA
 * Plugin URI:           https://wordpress.org/plugins/zencaptcha-for-forms/
 * Description:          ZENCAPTCHA protects WordPress forms from abuse by bots and unfair users. Reduces spam, blocks fake and disposable email addresses and increases your user base quality without the use of cookies. Fully GDPR compliant. Does not decrease user experience.
 * Version:              1.0.0
 * Requires at least:    5.0
 * Requires PHP:         7.0
 * Author:               zencaptcha
 * Author URI:           https://www.zencaptcha.com
 * License:              GPL v2 or later
 * License URI:          https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:          zencaptcha-for-forms
 * Domain Path:          /languages/
 * 
 * WooCommerce min:      3.0
 * WooCommerce up to:    8.2.2
 */

#
# ZENCAPTCHA protects WordPress website forms from being abused by bots, spam, fake and disposable email addresses without the use of cookies and is fully GDPR compliant (maximum data protection). Get rid of users with invalid or fake email addresses, not solely bots, to optimize the integrity of your user base. ZENCAPTCHA is made in the EU.
if (!defined('ABSPATH')) exit;

const ZENCAPTCHA_VERSION = '1.0.0';
const ZENCAPTCHA_WIDGET_IMGS_VERSION = '0.0.17';
const ZENCAPTCHA_WIDGET_VERSION = '0.0.32';
const ZENCAPTCHA_CSS_VERSION = '0.0.15';

const ZENCAPTCHA_REGISTRATION_URL = 'https://www.zencaptcha.com/access/register';
const ZENCAPTCHA_DASHBOARD_URL = 'https://www.zencaptcha.com/software/dashboard/home';

const ZENCAPTCHA_PATH = __DIR__;
const ZENCAPTCHA_FILE = __FILE__;
const ZENCAPTCHA_SRC = ZENCAPTCHA_PATH . '/src';
define('ZENCAPTCHA_MAIN_FILE', __FILE__);
define('ZENCAPTCHA_MAIN_PLUGIN_PHP', 'zencaptcha.php');
define('TEXT_DOMAIN','zencaptcha-for-forms');

define('ZENCAPTCHA_WIDGET_LANGUAGES', [
    "bg" => "Bulgarian", "ca" => "Catalan", "cs" => "Czech",
    "da" => "Danish", "de" => "German", "el" => "Greek",
    "en" => "English", "et" => "Estonian", "es" => "Spanish",
    "fi" => "Finnish", "fr" => "French", "hr" => "Croatian",
    "hu" => "Hungarian", "it" => "Italian", "ja" => "Japanese",
    "lt" => "Lithuanian", "lv" => "Latvian", "nl" => "Dutch",
    "no" => "Norwegian", "pl" => "Polish", "pt" => "Portuguese",
    "ro" => "Romanian", "ru" => "Russian", "sk" => "Slovak",
    "sl" => "Slovenian", "sr" => "Serbian", "sv" => "Swedish",
    "uk" => "Ukrainian", "vi" => "Vietnamese", "zh" => "Chinese",
]);

define( 'ZENCAPTCHA_PLUGIN_BASENAME', plugin_basename( ZENCAPTCHA_FILE ) );

function zencap_plugin_url( $path = '' ) {
	$url = plugins_url( $path, ZENCAPTCHA_MAIN_FILE );

	if ( is_ssl()
	and 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}

require ZENCAPTCHA_SRC . '/main.php';