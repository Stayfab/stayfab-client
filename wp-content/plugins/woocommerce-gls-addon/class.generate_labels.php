<?php

if (!class_exists('WGA_Custom_Bulk_Action')) {
 
	class WGA_Custom_Bulk_Action {
		
		public function __construct() {
			
			if(is_admin()) {
				add_filter('woocommerce_admin_order_actions' , array(&$this, 'addGenerateLabelAction') , 10 , 3); 
			}
		}

		public function generatePDFLabelsByOrderId($order_id){

			$options = get_option('woocommerce_wga_shipping_method_settings');
			
				$order = new WC_Order($order_id);
				
				$order->status = 'completed';
				
				if($order->status != 'completed'){
					$_GET['error_message'] = __('You Order Status is Not Completed.', WGA_TEXTDOMAIN);
					 return ''; 
				}
				
				if(!isset($options['title']) && 'GLS Shop' != $order->get_shipping_method()){
					$_GET['error_message'] = __('Order Shipping Method is not GLS Shop.', WGA_TEXTDOMAIN);
					 return ""; 
				}
				
				if(isset($options['title']) && $options['title'] != $order->get_shipping_method()){
					$_GET['error_message'] = __('Order Shipping Method is not GLS Shop.', WGA_TEXTDOMAIN);
					 return "";
				}

				$items = $order->get_items();
				
				$comments = $order->get_customer_order_notes();
				
				$post = get_post($order_id);
				$user = get_post_meta( $order_id, '_customer_user', true ) ;
				$user = get_user_by('id' , $user);
				$user = $user->data;
				
				$csv = "";
				$result = "";
				
				
					
					$csv_data = array();
					$weight  = 0;
					$parcels = array(array(
						"Comment"=> "",
						  "Reference" => "Parcel Reference" 
					));
					foreach($items as $item){
						
						$weight_object = new WC_Product($item['product_id']);
						$weight = $weight_object->get_weight();
						//$weight += $weight_object->get_weight();
						$parcels[0]["Weight"] += (empty($weight) || $weight == 0)?1:$weight;
					}
					
					$shop_number = get_post_meta($order_id, '_shop_name', true);
					
					$data  = array();
					if (is_numeric($shop_number)) {
        				$shop_details = wga_shop_by_number_func($shop_number);
						$data = array(
					  "Shipment" => get_bloginfo('name'),
					  "UserName"=> trim($options['gls_shop_user_name']),
					  "Password"=> trim($options['gls_shop_password']),
					  "Contactid" => trim($options['gls_shop_contact_id']),
					  "Customerid" => trim($options['gls_shop_customer_id']),
					  "ShipmentDate"=> date('Ymd'),
					  "Reference" => "Shipment Reference",
					  "Addresses"=> array(
						"AlternativeShipper" => null,
						"Delivery"=> array(
							"Reference" => "Delivery Reference",
						  "Name1"=> $shop_details->CompanyName,
						  "Name2"=> "",
						  "Name3"=> "",
						  "Street1"=> $shop_details->Streetname,
						  "CountryNum"=> getCountryNumFromCountryCode($shop_details->CountryCodeISO),
						  "ZipCode"=> $shop_details->ZipCode,
						  "City"=> $shop_details->CityName,
						  "Contact"=>  get_post_meta($order_id, '_billing_first_name' ,true) . ' ' .get_post_meta($order_id, '_billing_last_name' ,true),
						  
						  "Email"=> get_post_meta($order_id, '_billing_email' ,true),
						  "Phone"=> get_post_meta($order_id, '_billing_phone' ,true),
						  "Mobile"=> get_post_meta($order_id, '_billing_phone' ,true)
						)
					  ),
					  "Parcels"=> $parcels,
					  "Services"=> array(
					  "ShopDelivery"=> $shop_details->Number
					  ));
					} else {
						
						$details = $order->get_shipping_address();
						$details = explode("," ,$details);
						
						$data = array(
					  "Shipment" => get_bloginfo('name'),
					  "UserName"=> trim($options['gls_shop_user_name']),
					  "Password"=> trim($options['gls_shop_password']),
					  "Contactid" => trim($options['gls_shop_contact_id']),
					  "Customerid" => trim($options['gls_shop_customer_id']),
					  "ShipmentDate"=> date('Ymd'),
					  "Reference" => "Shipment Reference",
					  "Addresses"=> array(
						"AlternativeShipper" => null,
						"Delivery"=> array(
							"Reference" => "Delivery Reference",
						  "Name1"=> get_post_meta($order_id , 'company_first_name', true),
						  "Name2"=> get_post_meta($order_id , 'company_last_name', true),
						  "Name3"=> "",
						  "Street1"=> get_post_meta($order_id , 'company_address_1', true),
						  "CountryNum"=> getCountryNumFromCountryCode(utf8_decode(get_post_meta($order_id , 'company_country', true))),
						  "ZipCode"=> utf8_decode(get_post_meta($order_id , 'company_postcode', true)),
						  "City"=> get_post_meta($order_id , 'company_city', true),
						  "Contact"=> utf8_decode(get_post_meta($order_id , '_shipping_first_name' , true)) . ' ' . utf8_decode(get_user_meta($order_id , '_shipping_last_name' , true)) ,
						  "Email"=> get_post_meta($order_id , '_billing_email' , true),
						  "Phone"=> get_post_meta($order_id , '_billing_phone' , true),
						  "Mobile"=> get_post_meta($order_id , '_billing_phone' , true),
						)
					  ),
					  "Parcels"=>  $parcels,
					  "Services"=> array(
					  "ParcelDelivery"=> $shop_details->Number
					  ));
					}
					
					$data_string = json_encode($data);  
 
					$ch = curl_init('http://api.gls.dk/ws/DK/v1/CreateShipment');                                                                      
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
						'Content-Type: text/json', 
						'Content-Length: ' . strlen($data_string))                                                                       
					);                                                                                                                   

					$result = curl_exec($ch);
					$result = json_decode($result);

					if(isset($result->Message)){
						$_GET['error_message'] = $result->Message;
						$return['message'] = $result->Message;
						return $return;
					}
					
					$parcel_number = $result->Parcels[0]->ParcelNumber;
					$url = "https://gls-group.eu/DK/da/find-pakke?match=".$parcel_number."&txtAction=71000";
					$url = '<a href="'.$url.'">'.__('Tracking Link' , WGA_TEXTDOMAIN).'</a>';
					
					$emailTitle = $options['gls_shop_email_title'];
					$body = $options['gls_shop_email_body'];
					
					$name = get_post_meta($order_id , '_billing_first_name', true) ." ".get_post_meta($order_id , '_billing_last_name', true) ;
					
					$body = str_replace("[name]" , $name, $body);
					$body = str_replace("[link]" , $url, $body);
					
					$to = get_post_meta($order_id, '_billing_email', true); //$user->user_email;
					
					$headers = "From: ". get_bloginfo('title')." <" . strip_tags(get_bloginfo('admin_email')) . ">\r\n";
					$headers .= "Reply-To: ". strip_tags(get_bloginfo('admin_email')) . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					
					$current_user = wp_get_current_user();					
					$gls_auto_mail = get_user_meta($current_user->ID, 'gls_auto_mail', true);
					
					if($gls_auto_mail == 1){
						wp_mail($to, $emailTitle, $body,$headers);
					}
					
					$result = base64_decode($result->PDF)	;
					
				
				$return['pdf'] = $result;
				return $return;
				
			
}
		
		public function addGenerateLabelAction($actions , $the_order ){
			
			$actions['generate_label'] = array(
							'url' 		=> admin_url( 'post.php?post=' . $the_order->id . '&action=edit&perform=generateLabels&order_id='. $the_order->id ),
							'name' 		=> __( 'Generate Label', WGA_TEXTDOMAIN ),
							'action' 	=> "generate_label",
						);
			return $actions;	
		}
		
	}
}

$bulk = new WGA_Custom_Bulk_Action();