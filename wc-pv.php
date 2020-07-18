<?php
/**
 * Plugin Name: Phone Validator for WooCommerce
 * Plugin URI: https://github.com/Preciousomonze/woocommerce-phone-validator
 * Description: Phone Validator for WooCommerce Helps in validating international telephone numbers on woocommerce billing address.
 * Author: Precious Omonze (CodeXplorer )
 * Author URI: https://codeexplorer.ninja
 * Version: 1.1.1
 * Requires at least: 4.9
 * Tested up to: 5.2
 * WC requires at least: 3.0
 * WC tested up to: 3.6
 * 
 * Text Domain: woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}
// Make sure you update the version values when necessary.
define( 'WC_PV_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'WC_PV_PLUGIN_FILE', __FILE__ );
define( 'WC_PV_TEXT_DOMAIN', '' );
define( 'WC_PV_PLUGIN_VERSION', '1.1.1' );

/**
 * environment, should be either test or production
 * Note: if youre on localhost, even if you change this constant to production, it'll still use test :)
 */
$_wc_pv_env = 'production';

if( strpos( $_SERVER['SERVER_NAME'], 'localhost' ) !== false )
    $_wc_pv_env = 'test';

define( 'WC_PV_ENVIRONMENT', $_wc_pv_env );

//for global option meta access :)
//$wc_pv_option_meta = array();
// Custom fields names.
$wc_pv_woo_custom_field_meta = array(
    'billing_hidden_phone_field'     => '_wc_pv_phone_validator',
    'billing_hidden_phone_err_field' => '_wc_pv_phone_validator_err',
);

// include dependencies file
if ( ! class_exists( 'WC_PV_Dependencies' ) ) {
    include_once dirname(__FILE__) . '/includes/class-wc-pv-deps.php';
}

// Include the main class.
if ( ! class_exists('WC_PV') ) {
    include_once dirname(__FILE__) . '/includes/class-wc-pv.php';
}

/**
 * Run Baby run!
 */
function wc_pv(){
    return WC_PV::instance();
}

$GLOBALS['wc_pv'] = wc_pv();
