<?php
/**
 * Email Addresses
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><table cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top;" border="0">

	<tr>

		<td valign="top" width="50%">

			<h3><?php _e( 'Billing address', 'woocommerce' ); ?></h3>

			<p><?php echo $order->get_formatted_billing_address(); ?></p>

		</td>

		<?php if ( get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no' && ( $shipping = $order->get_formatted_shipping_address() ) || 1==1) : ?>

		<td valign="top" width="50%">

			<h3><?php _e( 'Shipping address', 'woocommerce' ); ?></h3>

			<p><?php 
			$shipping_method = array_values($order->get_shipping_methods() );
				$shipping_method = $shipping_method[0]['method_id'];
				if($shipping_method == 'wga_shipping_method'  && get_post_meta($order->id , '_shop_name', true) != ""){
					$shop_number = get_post_meta($order->id, '_shop_name', true);
        $r = wga_shop_by_number_func($shop_number);
?>
	<strong><?php _e($r->CompanyName, WGA_TEXTDOMAIN);?></strong><br />
        <?php _e($r->Streetname2 , WGA_TEXTDOMAIN); ?><br />
        <?php _e($r->Streetname , WGA_TEXTDOMAIN); ?><br />
        <?php _e($r->ZipCode, WGA_TEXTDOMAIN); ?>
        <?php _e($r->CityName , WGA_TEXTDOMAIN); ?>
        <?php _e($r->timing , WGA_TEXTDOMAIN); ?>
 	<?php
				} else {
					$shipping = $order->get_formatted_shipping_address();
					echo $shipping;
			
				} ?></p>

		</td>

		<?php endif; ?>

	</tr>

</table>