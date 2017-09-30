<?php
/**
 * Plugin Name: WooCommerce Vendor Order Email
 * Plugin URI: https://www.abc.com
 * Description: Demo plugin for adding a custom WooCommerce email that sends to products vendor an email when an order is received.
 * Author: WQ
 * Author URI: http://www.webqueue.org
 * Version: 0.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  Add a custom email to the list of emails WooCommerce should load
 *
 * @since 0.1
 * @param array $email_classes available email classes
 * @return array filtered available email classes
 */
function add_expedited_order_woocommerce_email( $email_classes ) {
 
    // include our custom email class
    require( 'includes/class-wc-expedited-order-email.php' );
 
    // add the email class to the list of email classes that WooCommerce loads
    $email_classes['WC_Expedited_Order_Email'] = new WC_Expedited_Order_Email();
 
    return $email_classes;
 
}
add_filter( 'woocommerce_email_classes', 'add_expedited_order_woocommerce_email' );