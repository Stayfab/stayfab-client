<?php
/**
 * Vendor registration email to vendor.
 *
 * @version 2.0.21
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php esc_html_e( 'Kære '.$user_login , 'woocommerce-product-vendors' ); ?></p>

<p style="font-weight:bold;"><?php esc_html_e( 'Tak for henvendselsen' , 'woocommerce-product-vendors' ); ?></p>

<p><?php esc_html_e( 'Vi er glade for at du har valgt Stayfab! som din samarbejdspartner.' , 'woocommerce-product-vendors' ); ?></p>

<p><?php esc_html_e( 'Når vi har modtaget dine oplysninger, går der 2-4 dage før du modtager en mail med bruger login samt vejledning hvordan du opretter tilbud.' , 'woocommerce-product-vendors'); ?></p>

<p><?php esc_html_e( 'Med venlig hilsen,', 'woocommerce-product-vendors' ); ?></p>

<p><?php esc_html_e( 'Stayfab', 'woocommerce-product-vendors' ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>