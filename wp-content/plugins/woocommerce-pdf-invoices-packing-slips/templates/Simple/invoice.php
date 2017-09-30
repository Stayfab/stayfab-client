<style>
td{
font-size:11px;}
</style>
<?php global $wpo_wcpdf; ?>
<?php do_action( 'wpo_wcpdf_before_document', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
<table class="head container">
	<tr>
		<td class="header" style="text-align: right;">
		<?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_packing_slip_title', __( 'Invoice', 'wpo_wcpdf' ) );
		}
		?>
		</td>
	</tr>
	<tr>
		<td class="shop-info" style="padding-top: 15px; font-size:11px;    text-align: right;">
			<div class="shop-address"><?php $wpo_wcpdf->shop_address(); ?></div>
		</td>
	</tr>
</table>
<h2>TAK FOR DIT KØB</h2>
Vi håber, at du bliver glad for sin behandling.<br><br>
Rigtig god fornøjelse<br>
<h2 style="margin-top:0px">Stayfab!</h2>
<?php
$order_idd = $wpo_wcpdf->get_order_number();
 
$order_date = $wpo_wcpdf->get_invoice_date(); 
$order = new WC_Order( $order_idd );
$items = $order->get_items();
foreach ( $items as $item ) {
    $product_name = $item['name'];
    $product_id = $item['product_id'];
    $product_variation_id = $item['variation_id'];
	$product_expirary_date = get_post_meta( $product_id, '_expire_date',true);  
	
	
	$author_id = get_post_field ('post_author', $product_id);
	$user_info = get_userdata($author_id);
	
	$product_expirary_time = get_post_meta(  $product_id, '_expire_time', true);
	if(!$product_expirary_date)
	{
		$dagstilbud_from_date = get_post_meta(  $product_id, '_dagstilbud_from_date',true);     
		$dagstilbud_from_time = get_post_meta( $product_id, '_dagstilbud_from_time', true);
		$dagstilbud_to_date = get_post_meta( $product_id, '_dagstilbud_to_date',true);     
		$dagstilbud_to_time = get_post_meta(  $product_id, '_dagstilbud_to_time', true);
		$product_expirary_date =  $dagstilbud_from_date.' - '.$dagstilbud_to_date;	
		$product_expirary_time =  $dagstilbud_from_time.' - '.$dagstilbud_to_time;	
	}
}
$post_author = get_post_field( 'post_author', $product_id);
$deal_owner_first_name = get_user_meta( $post_author, 'billing_first_name', true );
$deal_owner_last_name = get_user_meta( $post_author, 'billing_last_name', true );
$deal_owner_addr1 = get_user_meta( $post_author, 'billing_address_1', true );
$deal_owner_addr2 = get_user_meta( $post_author, 'billing_address_2', true );
$deal_owner_pin = get_user_meta( $post_author, 'billing_postcode', true );
$deal_owner_by = get_user_meta( $post_author, 'billing_city', true );
$deal_owner_country = get_user_meta( $post_author, 'billing_country', true );
$deal_owner_tlf = get_user_meta( $post_author, 'billing_phone', true );
	$wq_vendor_name = get_user_meta($post_author,'vendor_name',true);
	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$wq_vendor_name,
			        )
			 );
	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	
	if($vendor_term_meta)
	{
		foreach($vendor_term_meta as $km=>$vm)
		{
			if($km =='address')
			$vendor_address = $vm[0];
			
			if($km =='zip')
			$vendor_zip = $vm[0];
			
			if($km =='by')
			$vendor_by = $vm[0];
			
			if($km =='phone')
			$vendor_phone = $vm[0];
		
		}
		
	}
?>
<table class="tg" style="font-family:Helvetica !important;font-size:9px !important;">
  <tr style="align:left !important;font-family:Helvetica !important;font-size:9px !important;">
    <th class="tg-9hbo" colspan="2" align="left" bgcolor="#9E9E9E">Faktura nr. <?php echo $wpo_wcpdf->invoice_number(); ?>
    
    <br><?php echo 'Ordre nr.: '.$order->get_order_number() ; ?>
    
     <br><?php echo 'Ordredato: '.$order_date ; ?></th>
  
  </tr>
  <tr>
    <td class="tg-9hbo">Solgt til:</td>
    <td class="tg-9hbo">Behandling hos:</td>
  </tr>
  <tr>
  
    <td style="color:#000000" class="tg-yw4l">
			<?php echo $order->get_formatted_billing_address(); ?><br/>
            <?php echo $order->billing_phone; ?>
    </td>
    <td class="tg-yw4l">
		<?php 
			echo $wq_vendor_name.'<br>' ; 
			echo $vendor_address.'<br>' ; 
			echo $vendor_zip.' '.$vendor_by.'<br>' ; 
			echo 'Denmark'.'<br>' ; 
			echo 'Tlf: '.$vendor_phone ; 
		
		?>
    </td>
  </tr>
  <tr>
    <td class="tg-9hbo">Betalingsmetode</td>
    <td class="tg-9hbo">Behandlings type +dato og tid og sted</td>
  </tr>
  <tr>
    <td class="tg-yw4l"><?php echo $order->payment_method_title; ?></td>
    <td class="tg-3ojc">
    <?php echo $product_name;?>
    <br>Sted:
    <br>
	<?php   echo $wq_vendor_name.'<br>' ; 
			echo $vendor_address.'<br>' ; 
			echo $vendor_zip.' '.$vendor_by.'<br>' ; 
			//echo $deal_owner_country.'<br>' ; 
			echo 'Tlf: '.$vendor_phone ; 
	?>
    <br>
    <div style="color:red">
    Dato: <?php echo $product_expirary_date ;?>
    <br>Tid: <?php echo $product_expirary_time; ?>
    </div>
    </td>
  </tr>
</table>
<?php do_action( 'wpo_wcpdf_after_document_label', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
<?php do_action( 'wpo_wcpdf_before_order_details', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
<br><br>
<table  class="products small-font">
        <thead>
        <tr bgcolor="#9E9E9E" class="table-headers">
            <th class="align-left"><?php _e( 'Produkt'); ?></th>
            <?php
            //if( $this->template_options['bewpi_show_sku'] ) {
                echo '<th class="align-left">' . __( "Varenummer") . '</th>';
           // }
            ?>
	        <th class="align-left"><?php _e( 'Pris'); ?></th>
            <th class="align-left"><?php _e( 'Antal'); ?></th>
 			<th class="align-left"><?php _e( 'Moms'); ?></th>
	        <!-- Tax -->
            <th class="align-right"><?php _e( 'Total'); ?></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach( $order->get_items( 'line_item' ) as $item_id => $item ) {
                $product = wc_get_product( $item['product_id'] );
				
				 ?>
                <tr class="product-row">
                    <td>
                        <?php echo $product->get_title(); ?>
                        <?php
                        global $wpdb;
                        if ( $metadata = $this->order->has_meta( $item_id ) ) {
                            foreach ( $metadata as $meta ) {
                                // Skip hidden core fields
                                if ( in_array( $meta['meta_key'], apply_filters( 'woocommerce_hidden_order_itemmeta', array(
                                    '_qty',
                                    '_tax_class',
                                    '_product_id',
                                    '_variation_id',
                                    '_line_subtotal',
                                    '_line_subtotal_tax',
                                    '_line_total',
                                    '_line_tax',
                                ) ) ) ) {
                                    continue;
                                }
                                // Skip serialised meta
                                if ( is_serialized( $meta['meta_value'] ) ) {
                                    continue;
                                }
                                // Get attribute data
                                if ( taxonomy_exists( wc_sanitize_taxonomy_name( $meta['meta_key'] ) ) ) {
                                    $term               = get_term_by( 'slug', $meta['meta_value'], wc_sanitize_taxonomy_name( $meta['meta_key'] ) );
                                    $meta['meta_key']   = wc_attribute_label( wc_sanitize_taxonomy_name( $meta['meta_key'] ) );
                                    $meta['meta_value'] = isset( $term->name ) ? $term->name : $meta['meta_value'];
                                } else {
                                    $meta['meta_key']   = apply_filters( 'woocommerce_attribute_label', wc_attribute_label( $meta['meta_key'], $product ), $meta['meta_key'] );
                                }
                               // echo '<div class="item-attribute"><span style="font-weight: bold;">' . wp_kses_post( rawurldecode( $meta['meta_key'] ) ) . ': </span>' . wp_kses_post( rawurldecode( $meta['meta_value'] ) ) . '</div>';
                            }
                        }
                        ?>
                    </td>
                        <?php
                       // if ( $this->template_options['bewpi_show_sku'] ) :
                            echo '<td>';
                            echo ( $product->get_sku() != '' ) ? $product->get_sku() : '-';
                            echo '</td>';
                       // endif;
                        ?>
                    <td>
                        <?php
                        if ( isset( $item['line_total'] ) ) {
                            if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) 
							{
 								echo '<del>' . wc_price( $order->get_item_subtotal( $item, false, true ), array( 'currency' => $order->get_order_currency() ) ) . '</del> ';
							 }
                            echo wc_price( ($order->get_item_total( $item, false, true ) + $item['line_tax']), array( 'currency' => $order->get_order_currency() ) );
							
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $item['qty'];
                        if ( $refunded_qty = $this->order->get_qty_refunded_for_item( $item_id ) )
                            echo '<br/><small class="refunded">-' . $refunded_qty . '</small>';
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $order->get_total_tax().'DKK';
                        ?>
                    </td>
                   
                    <td class="align-right item-total" width="">
                        <?php
                        if ( isset( $item['line_total'] ) ) {
                           // $incl_tax = (bool)$this->template_options[ 'bewpi_display_prices_incl_tax' ];
                            if ( isset( $item['line_subtotal'] ) && $item['line_subtotal'] != $item['line_total'] ) {
                                echo '<del>' . wc_price( $order->get_line_subtotal( $item, $incl_tax, true ), array( 'currency' => $order->get_order_currency() ) ) . '</del> ';
                            }
                            echo wc_price( ($order->get_line_total( $item, $incl_tax, true ) + $order->get_line_tax( $item, $incl_tax, true )), array( 'currency' => $order->get_order_currency() ) );
                        }
                        if ( $refunded = $order->get_total_refunded_for_item( $item_id ) ) {
                            echo '<br/><small class="refunded">-' . wc_price( $refunded, array( 'currency' => $order->get_order_currency() ) ) . '</small>';
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
            <!-- Space -->
            <tr class="space">
	        <td colspan="<?php echo $this->columns_count; ?>"></td>
        </tr>
       
        <!-- Table footers -->
        <!-- Subtotal -->
     
            <tr class="subtotal after-products">
                <td colspan="4"></td>
                <td><?php _e( 'Total:'); ?></td>
               <?php /*?> <td class="align-right"><?php echo number_format($order->get_total(),2).'DKK'; ?></td><?php */?>
               
               <td class="align-right"><?php echo $order->get_line_total( $item, $incl_tax, true ).'DKK';  ?></td>
               
               
            </tr>
      
        <!-- Discount -->
   <?php $moms_val = ($order->get_total()*25)/100; ?>
            <tr class="discount after-products">
                <td colspan="4"></td>
                <td><?php _e( 'Moms (25,000%)'); ?></td>
                <td class="align-right"><?php echo number_format($order->get_total_tax(),2).'DKK' ?></td>
            </tr>
      
        <!-- Shipping -->
        <?php
		$shipping_cost = $order->get_total_shipping();
		
		if(!$shipping_cost)
		$shipping_cost = '0' ;
		
		
		
		?>
           <tr class="shipping after-products">
                <td colspan="4"></td>
                <td><?php _e( 'Levering & Håndtering'); ?></td>
                <td class="align-right"><?php echo number_format($shipping_cost,2).'DKK'; ?></td>
            </tr>
      <tr class="discount after-products">
                <td colspan="4"></td>
                <td><?php _e( 'Total:'); ?></td>
          
              <td class="align-right"><?php echo number_format($order->get_total()).'DKK' ?></td>
            </tr>
        <!-- Subtotal -->
 <?php
$vendor_name = $user_info->user_login;
$vendor_term_by = get_term_by('name',  $vendor_name, 'wcpv_product_vendors');
$ord_tot = $order->get_total() - $order->get_total_tax();
			$vendor_term_ = get_term_meta($vendor_term_by->term_id, 'vendor_data', true);
			$commission = $vendor_term_['commission'];
			$commission_type = $vendor_term_['commission_type'];
			if($commission_type =='percentage')
			{
				$commission_val = ($ord_tot*$commission)/100;
				$percentage_text = ' ('.$commission.'%)';
			}
			else
			{
				$commission_val = $commission;
				$percentage_text ='';
			}
 ?>      
     </tbody>
    </table>
<?php do_action( 'wpo_wcpdf_after_document', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
