<?php
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
/**
 * A custom vendor Order WooCommerce Email class
 *
 * @since 0.1
 * @extends \WC_Email
 */
class WC_Extra_Order_Email extends WC_Email {
 
 
 /**
 * Set email defaults
 *
 * @since 0.1
 */
public function __construct() {
 
    // set ID, this simply needs to be a unique name
    $this->id = 'wc_extra_order';
 
    // this is the title in WooCommerce Email settings
    $this->title = 'Purchased deal';    //'Vendor email settings';
 
    // this is the description in WooCommerce email settings
    $this->description = 'Purchased deal settings';
 
    // these are the default heading and subject lines that can be overridden using the settings
    $this->heading = 'Your deal had been purchased';
    $this->subject = 'Your deal had been purchased';
 
    // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
    $this->template_html  = 'emails/customer-processing-order-deal-purchased.php';
    $this->template_plain = 'emails/plain/customer-processing-order.php';
 	$_SESSION['email_send_to_vendor'] = 'send';
	
    // Trigger on new paid orders
    add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
    add_action( 'woocommerce_order_status_failed_to_processing_notification',  array( $this, 'trigger' ) );
	add_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $this, 'trigger' ) );
 
    // Call parent constructor to load any other defaults not explicity defined here
    parent::__construct();
 
    // this sets the recipient to the settings defined below in init_form_fields()
    $this->recipient = $this->get_option( 'recipient' );
 
    // if none was entered, just use the WP admin email as a fallback
    if ( ! $this->recipient )
        $this->recipient = get_option( 'admin_email' );
}



public function trigger( $order_id ) {
 
    // bail if no order ID is present
    if ( ! $order_id )
        return;
 
    // setup order object
    $this->object = new WC_Order( $order_id );
	
	
	$order = new WC_Order( $order_id );
	
	$order_items = $order->get_items();
	
	foreach ($order_items as $item_id => $item_data) {
		
		$product_name = $item_data['name'];
		$product_id = $item_data['product_id'];
		$product_author = get_post_field( 'post_author', $product_id );
	}
 
 	$vendor_user = get_userdata( $product_author );
	$vendor_user_email = $vendor_user->user_email;
 
    // bail if shipping method is not expedited
   /* if ( ! in_array( $this->object->get_shipping_method(), array( 'Three Day Shipping', 'Next Day Shipping' ) ) )
        return;
 */
    // replace variables in the subject/headings
    $this->find[] = '{order_date}';
    $this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );
 
    $this->find[] = '{order_number}';
    $this->replace[] = $this->object->get_order_number();
 
    if ( ! $this->is_enabled() || ! $this->get_recipient() )
        return;
 
    // woohoo, send the email!
  
  
  
  	/*$mailer = WC()->mailer();
	
	$mails = $mailer->get_emails();
	
	if ( ! empty( $mails ) ) {
	
		foreach ( $mails as $mail ) {
	
			if ( $mail->id == 'customer_processing_order' ) {
						
						add_filter( 'wpo_wcpdf_invoice_title', 'filter_wpo_wcpdf_invoice_title', 10, 1 ); 
						
						//add_filter( 'woocommerce_email_recipient_customer_completed_order', 'your_email_recipient_filter_function', 10, 2);
						
						add_filter('woocommerce_email_recipient_customer_processing_order', 'wq_vendor_email_recipient', 10, 2);

				  		
						$mail->trigger( $order_id );
					
						remove_filter( 'wpo_wcpdf_invoice_title','filter_wpo_wcpdf_invoice_title'); 
						
						//remove_filter( 'woocommerce_email_recipient_customer_completed_order','your_email_recipient_filter_function'); 
						
						remove_filter( 'woocommerce_email_recipient_customer_processing_order','wq_vendor_email_recipient');
			}
	
		 }
	
	}
  */	ob_start();
  
  
  
  
		$url = 'http://stayfab.dk/wp-admin/admin-ajax.php?action=generate_wpo_wcpdf&document_type=packing-slip&order_ids='.$order_id.'&_wpnonce='.wp_create_nonce('generate_wpo_wcpdf');
		
		$ch = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_exec($ch);
		
		curl_close($ch);
		
		ob_clean();
  
  
  
    if($vendor_user_email)
    $this->send( $vendor_user_email, $this->get_subject(), $this->get_content(), $this->get_headers(), array(ABSPATH . '/uploads/'.$order_id.'.pdf') );
	
	//$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
}

/**
 * get_content_html function.
 *
 * @since 0.1
 * @return string
 */
public function get_content_html() {
    ob_start();
    woocommerce_get_template( $this->template_html, array(
        'order'         => $this->object,
        'email_heading' => $this->get_heading()
    ) );
    return ob_get_clean();
}
 
 
/**
 * get_content_plain function.
 *
 * @since 0.1
 * @return string
 */
public function get_content_plain() {
    ob_start();
    woocommerce_get_template( $this->template_plain, array(
        'order'         => $this->object,
        'email_heading' => $this->get_heading()
    ) );
    return ob_get_clean();
}


/**
 * Initialize Settings Form Fields
 *
 * @since 0.1
 */
public function init_form_fields() {
 
    $this->form_fields = array(
        'enabled'    => array(
            'title'   => 'Enable/Disable',
            'type'    => 'checkbox',
            'label'   => 'Enable this email notification',
            'default' => 'yes'
        ),
        'recipient'  => array(
            'title'       => 'Recipient(s)',
            'type'        => 'text',
            'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to Vendor.'  ),
            'placeholder' => '',
            'default'     => ''
        ),
        'subject'    => array(
            'title'       => 'Subject',
            'type'        => 'text',
            'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
            'placeholder' => '',
            'default'     => ''
        ),
        'heading'    => array(
            'title'       => 'Email Heading',
            'type'        => 'text',
            'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
            'placeholder' => '',
            'default'     => ''
        ),
        'email_type' => array(
            'title'       => 'Email type',
            'type'        => 'select',
            'description' => 'Choose which format of email to send.',
            'default'     => 'html',
            'class'       => 'email_type',
            'options'     => array(
                'plain'     => 'Plain text',
                'html'      => 'HTML', 'woocommerce',
                'multipart' => 'Multipart', 'woocommerce',
            )
        )
    );
}
 
} 