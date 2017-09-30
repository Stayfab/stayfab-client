<?php
/**
 * Admin cancelled order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-cancelled-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.5.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }


$customer_user = get_post_meta( $order->get_order_number(), '_customer_user', true );

$billing_first_name = get_post_meta($order->get_order_number(), '_billing_first_name', true);
$billing_last_name = get_post_meta($order->get_order_number(), '_billing_last_name', true);

if($_SESSION['send_to_customer'])             
{
		/////////      STARTS CUSTOMER TEMPLATE 
		
	
		 do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
         
         <p><?php echo 'Kære '.$billing_first_name.' '.$billing_last_name ; ?></p>

			<p><?php _e( "Din købte tilbud er desværre annulleret da behandler er forhindret (sygdom eller andet).", 'woocommerce' ); ?></p>

			<p><?php _e( "Vi sætter pengene tilbage på din konto med det samme. Dette kan tage op til 3 bankdage før pengene er disponible på din konto.", 'woocommerce' ); ?></p>

			<p><?php _e( "Undskyld ulejligheden.", 'woocommerce' ); ?></p>

			<p><?php _e( "Med venlig hilsen,<br>Stayfab!", 'woocommerce' ); ?></p>
		
		 <p><?php printf( __( 'The order #%d from %s has been cancelled. The order was as follows:', 'woocommerce' ), $order->get_order_number(), $order->get_formatted_billing_full_name() ); ?></p>
		
		 <?php
		 do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
		 do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
		 do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
		 do_action( 'woocommerce_email_footer', $email );
		
		
		/////////      ENDS CUSTOMER TEMPLATE 
	
}
else               
{
		/////////      STARTS ADMIN TEMPLATE 
		
		do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
		
		<p><?php printf( __( 'The order #%d from %s has been cancelled. The order was as follows:', 'woocommerce' ), $order->get_order_number(), $order->get_formatted_billing_full_name() ); ?></p>
		
		<?php
		do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
		do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
		do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
		do_action( 'woocommerce_email_footer', $email );
		
		/////////      ENDS ADMIN TEMPLATE 
	
}               


 