<?php

/**
 * Template Name: userlists
*/

get_header();

global $post;
$args = array( 
					'post_type' => 'product', 
					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC',
					'post_status' =>'all',
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(308,309),
												'operator' => 'IN',
											)
   										 )
			);		
	
	
	$loop = new WP_Query( $args );	

	$time_zone = getTimeZoneFromIpAddress();


	$timezone = $time_zone;
	
	$date = new DateTime('now', new DateTimeZone($timezone));
	
	$localtime = $date->format('d-m-Y h:i:s a');
	$localtime1 = $date->format('d-m-Y');
	$today = $localtime;

	$temp_product_arr = array();
	$temp_product_arr_all= array();
	$cntr = 0;
	while ( $loop->have_posts() ) : $loop->the_post(); 	
	
		
		$all_orders_of_product = rfm_get_orders_by_product(get_the_ID());
		$u_info = array();
		$ocntr = 0;
		foreach($all_orders_of_product as $k=>$v)
		{
			//echo $v->id.':'.$v->post->post_status.'<br>';
			$orderId = $v->id;
			$orderss = new WC_Order( $orderId );
			$u_email = $orderss->billing_email;
			$u_f_name = get_post_meta($orderId,'_billing_first_name',true);
			$u_l_name = get_post_meta($orderId,'_billing_last_name',true);
			
			$u_info[$ocntr]['u_email']=$u_email;
			$u_info[$ocntr]['u_f_name']=$u_f_name ;
			$u_info[$ocntr]['u_l_name']=$u_l_name;
			$ocntr++;
		}
		
	
		$user_id=$post->post_author;
		$user_vendor_name =  get_user_meta($user_id,'vendor_name',true);
		
		$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$user_vendor_name,
			        )
			 );
		$vendor_name = $vendor_term[0]->name;
		
		$rate_user  = get_user_by( 'login', $vendor_name );
			
		$product_expirary_date = get_post_meta( get_the_ID(), '_expire_date',true);     
		$product_expirary_time = get_post_meta( get_the_ID(), '_expire_time', true);
		
		$dagstilbud_from_date = get_post_meta( get_the_ID(), '_dagstilbud_from_date',true);     
		$dagstilbud_from_time = get_post_meta( get_the_ID(), '_dagstilbud_from_time', true);
		$dagstilbud_to_date = get_post_meta( get_the_ID(), '_dagstilbud_to_date',true);     
		$dagstilbud_to_time = get_post_meta( get_the_ID(), '_dagstilbud_to_time', true);
		
		
		if($product_expirary_date)
		{
			$product_date_arr = explode('/',$product_expirary_date);
	
			$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
			
			$temp_product_arr_all[] = get_the_title().'/'.($product_expiration).'/'.($today);
			
			if(strtotime($product_expiration) < strtotime($localtime))
			{
				//continue;
				//$temp_product_arr[] = $product_expiration;
				$temp_product_arr[$cntr]['product_id'] = get_the_ID();
				$temp_product_arr[$cntr]['product_title'] = get_the_title();
				$temp_product_arr[$cntr]['date'] = ($product_expiration).'/'.($today);
				$temp_product_arr[$cntr]['email'] = $rate_user->user_email;
				$temp_product_arr[$cntr]['user_name'] = $vendor_name;
				$temp_product_arr[$cntr]['user_id'] = $user_id;
				$temp_product_arr[$cntr]['user_info'] = $u_info;
				
				
			}
			
		}
	
		if($dagstilbud_from_date && $dagstilbud_to_date)
		{
			$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
			$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
			
			$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
			$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
			
			/*if(strtotime($dagstilbud_start_date) < strtotime($today))
			{
				if(strtotime($dagstilbud_end_date) < strtotime($today))
				{
					continue;
				}
				
			}*/
			$dagstilbud_start_date1 = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
			$temp_product_arr_all[] = get_the_title().'/'.($dagstilbud_start_date1).'/'.($today);
			
			if(strtotime($dagstilbud_start_date1) < strtotime($localtime))
			{
				//continue;
				$temp_product_arr[$cntr]['product_id'] = get_the_ID();
				$temp_product_arr[$cntr]['product_title'] = get_the_title();
				$temp_product_arr[$cntr]['date'] = ($dagstilbud_start_date1).'/'.($today);
				$temp_product_arr[$cntr]['email'] = $rate_user->user_email;
				$temp_product_arr[$cntr]['user_name'] = $vendor_name;
				$temp_product_arr[$cntr]['user_id'] = $user_id;
				$temp_product_arr[$cntr]['user_info'] = $u_info;
				
			
			}
			
		}
	
	$cntr++;
	endwhile;

	wp_reset_postdata();
	
	$query = new WP_Query($args);
	/*echo '<pre>';
	print_r($temp_product_arr);
	echo '</pre>';*/
	
	foreach($temp_product_arr as $key => $val)
	{
		$to_email = $val['user_emailarr'];
		$subject = 'Rate salonen!';
		$userid = $val['user_id'];
		$message = 'http://stayfab.dk/rate-vendor?rate_user_id='.$val['user_id'].'&rate_product_id='.$val['product_id'].'';
		
		$user_info_array = $val['user_info'];
		
		
		foreach($user_info_array as $kkk=>$vvv)
		{
			$bill_fname = $vvv['u_f_name'];
			$bill_lname = $vvv['u_l_name'];
			
			echo $vvv['u_email'].'<br>' ;
			
			$dir = ABSPATH.'/wp-content/plugins/woocommerce/templates/emails/customer-rate-vendor-email.php';
		
			ob_start();
			include($dir);
			$content = ob_get_clean();
			
			add_filter( 'wp_mail_content_type', 'set_content_type' );
			
			add_filter( 'wp_mail_from', 'wpb_sender_email' );
			add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
			wp_mail( $vvv['u_email'], $subject, $content );	
			
		}
		
			
	
			
		//
	}



/*function set_content_type( $content_type ) {
	return 'text/html';
}

function wpb_sender_name( $original_email_from ) {
	return 'Stayfab!';
	}
	function wpb_sender_email( $original_email_address ) {
	return get_option('admin_email');
	}
*/

function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	function getTimeZoneFromIpAddress(){
		$clientsIpAddress = get_client_ip();
	
		$clientInformation = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$clientsIpAddress));
	
		$clientsLatitude = $clientInformation['geoplugin_latitude'];
		$clientsLongitude = $clientInformation['geoplugin_longitude'];
		$clientsCountryCode = $clientInformation['geoplugin_countryCode'];
	
		$timeZone = get_nearest_timezone($clientsLatitude, $clientsLongitude, $clientsCountryCode) ;
	
		return $timeZone;
	
	}
	function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
		$timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
			: DateTimeZone::listIdentifiers();
	
		if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {
	
			$time_zone = '';
			$tz_distance = 0;
	
			//only one identifier?
			if (count($timezone_ids) == 1) {
				$time_zone = $timezone_ids[0];
			} else {
	
				foreach($timezone_ids as $timezone_id) {
					$timezone = new DateTimeZone($timezone_id);
					$location = $timezone->getLocation();
					$tz_lat   = $location['latitude'];
					$tz_long  = $location['longitude'];
	
					$theta    = $cur_long - $tz_long;
					$distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
						+ (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
					$distance = acos($distance);
					$distance = abs(rad2deg($distance));
					// echo '<br />'.$timezone_id.' '.$distance;
	
					if (!$time_zone || $tz_distance > $distance) {
						$time_zone   = $timezone_id;
						$tz_distance = $distance;
					}
	
				}
			}
			return  $time_zone;
		}
		return 'unknown';
	}







get_footer(); 