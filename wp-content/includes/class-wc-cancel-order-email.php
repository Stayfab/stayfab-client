<?php
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class WC_Cancel_Order_Email extends WC_Email {
 
 
 /**
 * Set email defaults
 *
 * @since 0.1
 */
public function __construct() {
 
    // set ID, this simply needs to be a unique name
    $this->id = 'wc_cancel_order';
 
    // this is the title in WooCommerce Email settings
    $this->title = 'Cancel deal';    //'Vendor email settings';
 
    // this is the description in WooCommerce email settings
    $this->description = 'cancel deal email settings';
 
    // these are the default heading and subject lines that can be overridden using the settings
    $this->heading = 'Annulleret ordre';
    $this->subject = 'Annulleret ordre';
 
    // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
    $this->template_html  = 'emails/customer-cancelled-order.php';
    $this->template_plain = 'emails/plain/customer-cancelled-order.php';
 	

	

	
   /* add_action( 'woocommerce_order_status_pending_to_cancelled_notification', array( $this, 'trigger' ) );
    add_action( 'woocommerce_order_status_on-hold_to_cancelled_notification',  array( $this, 'trigger' ) );*/
	
	add_action( 'woocommerce_order_status_cancelled',  array( $this, 'trigger' ));
 	
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
	
	
	
 
  
    $this->find[] = '{order_date}';
    $this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );
 
    $this->find[] = '{order_number}';
    $this->replace[] = $this->object->get_order_number();
 
    if ( ! $this->is_enabled() || ! $this->get_recipient() )
        return;
 
    

  	$mailer1 = WC()->mailer();
	
	$mails1 = $mailer1->get_emails();
	
	
	/*
	if ( ! empty( $mails1 ) ) {
	
		foreach ( $mails1 as $mail ) {
	
			if ( $mail->id == 'wc_cancel_order' ) {
						
						add_filter('woocommerce_email_recipient_cancelled_order', 'wq_customer_cancel_email_recipient', 10, 2);

						$mail->trigger( $order_id );
					
						remove_filter( 'woocommerce_email_recipient_cancelled_order','wq_customer_cancel_email_recipient');
			}
	
		 }
	
	}*/
 
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