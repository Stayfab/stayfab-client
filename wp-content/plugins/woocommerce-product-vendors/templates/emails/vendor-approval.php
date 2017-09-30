<?php
/**
 * Vendor approval.
 *
 * @version 2.0.21
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<?php 
$vendor_email = $email->recipient;
$vendor_user = get_user_by( 'email', $vendor_email);
$vendor_pwd = get_user_meta($vendor_user->ID,'new_password',true);
$vendor_name_danish = get_user_meta($vendor_user->ID,'vendor_name',true);
 ?>
<p><?php esc_html_e( 'Kære '.$vendor_name_danish , 'woocommerce-product-vendors' ); ?></p>

<p style="font-weight:bold;"><?php esc_html_e( 'Din Stayfab profil er nu oprettet' , 'woocommerce-product-vendors' ); ?></p>

<p><?php esc_html_e( 'Vi er glade for at kunne fortælle, at vi nu har oprettet din stayfab profil med:' , 'woocommerce-product-vendors' ); ?></p>

<ul>
	<li><?php printf( esc_html__( 'Login link: %s', 'woocommerce-product-vendors' ), '<a href="' . esc_url( wp_login_url() ) . '">' . wp_login_url() . '</a>' ); ?></li>
	<li><?php printf( esc_html__( 'Email: %s', 'woocommerce-product-vendors' ),  $vendor_email ); ?></li>
	<li><?php printf( esc_html__( 'Adgangskode: %s', 'woocommerce-product-vendors' ), $vendor_pwd ); ?></li>
</ul>

<p><?php esc_html_e( 'På næste side finder du vejledning, hvordan du opretter på tilbud/deal.' , 'woocommerce-product-vendors' ); ?></p>

<p><?php echo 'Vil du læse <a href="http://stayfab.dk/generelle-vilkaar-stayfab-dk-samarbejdsaftale">vilkårene</a> for dit nye profil eller har du spørgsmål til Stayfab er du meget velkommen til at ringe til os på Tlf: 31491325'; ?></p>

<p><?php esc_html_e( 'Med venlig hilsen,', 'woocommerce-product-vendors' ); ?></p>

<p><?php esc_html_e( 'Stayfab', 'woocommerce-product-vendors' ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
