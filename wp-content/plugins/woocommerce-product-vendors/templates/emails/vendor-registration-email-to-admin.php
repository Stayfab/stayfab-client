<?php
/**
 * Vendor registration email to admin.
 *
 * @version 2.0.0
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$vendor_term = get_term_by('name', $vendor_name, 'wcpv_product_vendors');
$term_cvr = get_term_meta( $vendor_term->term_id, 'cvr', true);
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<h3><?php esc_html_e( 'Hej! En ny salon/klinik er blevet oprettet.', 'woocommerce-product-vendors' ); ?></h3>

<p><?php esc_html_e( 'Salon/klinik information:', 'woocommerce-product-vendors' ); ?></p>

<ul>
	<li><?php printf( esc_html__( 'Email', 'woocommerce-product-vendors' ) . ': %s', $user_email ); ?></li>
	<li><?php printf( esc_html__( 'Salon/klinik navn', 'woocommerce-product-vendors' ) . ': %s', stripslashes( $vendor_name ) ); ?></li>
	<li><?php printf( esc_html__( 'Salon/klinik beskrivelse', 'woocommerce-product-vendors' ) . ': %s', stripslashes( $vendor_desc ) ); ?></li>
    <li><?php echo 'CVR nr.: '.$term_cvr;?></li>
</ul>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
