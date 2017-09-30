<?php

/**

 * The header for our theme.

 *

 * Displays all of the <head> section and everything up till <div class="page-wrapper">

 *

 * @package uncode

 */



global $is_redirect, $redirect_page;



if ($redirect_page) {

	$post_id = $redirect_page;

} else {

	if (isset(get_queried_object()->ID) && !is_home()) {

		$post_id = get_queried_object()->ID;

	} else {

		$post_id = null;

	}

}



if (wp_is_mobile()) $html_class = 'touch';

else $html_class = 'no-touch';



if (is_admin_bar_showing()) $html_class .= ' admin-mode';



?><!DOCTYPE html>



<html class="<?php echo esc_attr($html_class); ?>" <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">

<?php if (wp_is_mobile()): ?>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

<?php else: ?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<?php endif; ?>

<link rel="profile" href="http://gmpg.org/xfn/11">



<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<link rel="stylesheet" href="/resources/demos/style.css">

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">



<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<?php

	global $LOGO, $metabox_data, $onepage, $fontsizes, $is_redirect, $menutype;



	if ($post_id !== null) {

		$metabox_data = get_post_custom($post_id);

		$metabox_data['post_id'] = $post_id;

	} else $metabox_data = array();



	$onepage = false;

	$background_div = $background_style = $background_color_css = '';



	if (isset($metabox_data['_uncode_page_scroll'][0]) && $metabox_data['_uncode_page_scroll'][0] == 'on') {

		$onepage = true;

	}



	$boxed = ot_get_option( '_uncode_boxed');

	$fontsizes = ot_get_option( '_uncode_heading_font_sizes');

	$background = ot_get_option( '_uncode_body_background');



	if (isset($metabox_data['_uncode_specific_body_background'])) {

		$specific_background = unserialize($metabox_data['_uncode_specific_body_background'][0]);

		if ($specific_background['background-color'] != '' || $specific_background['background-image'] != '') {

			$background = $specific_background;

		}

	}



	$back_class = '';

	if (!empty($background) && ($background['background-color'] != '' || $background['background-image'] != '')) {

		if ($background['background-color'] !== '') $background_color_css = ' style-'. $background['background-color'] . '-bg';

		$back_result_array = uncode_get_back_html($background, '', '', '', 'div');



		if ((strpos($back_result_array['mime'], 'image') !== false)) {

			$background_style .= (strpos($back_result_array['back_url'], 'background-image') !== false) ? $back_result_array['back_url'] : 'background-image: url(' . $back_result_array['back_url'] . ');';

			if ($background['background-repeat'] !== '') $background_style .= 'background-repeat: '. $background['background-repeat'] . ';';

			if ($background['background-position'] !== '') $background_style .= 'background-position: '. $background['background-position'] . ';';

			if ($background['background-size'] !== '') $background_style .= 'background-size: '. ($background['background-attachment'] === 'fixed' ? 'cover' : $background['background-size']) . ';';

			if ($background['background-attachment'] !== '') $background_style .= 'background-attachment: '. $background['background-attachment'] . ';';

		} else $background_div = $back_result_array['back_html'];

		if ($background_style !== '') $background_style = ' style="'.$background_style.'"';

		if (isset($back_result_array['async_class']) && $back_result_array['async_class'] !== '') {

			$back_class = $back_result_array['async_class'];

			$background_style .= $back_result_array['async_data'];

		}

	}



	$body_attr = '';

	if ($boxed === 'on') {

		$boxed_width = ' limit-width';

	} else {

		$boxed_width = '';

		$body_border = ot_get_option('_uncode_body_border');

		if ($body_border !== '' && $body_border !== 0) {

			$body_attr = ' data-border="' . esc_attr($body_border) . '"';

		}

	}



?>

<body <?php body_class($background_color_css); echo $body_attr; ?>>

	<?php echo uncode_remove_wpautop( $background_div ) ; ?>

	<?php do_action( 'before' );



	$body_border = ot_get_option('_uncode_body_border');

	if ($body_border !== '' && $body_border !== 0) {

		$general_style = ot_get_option('_uncode_general_style');

		$body_border_color = ot_get_option('_uncode_body_border_color');

		if ($body_border_color === '') $body_border_color = ' style-' . $general_style . '-bg';

		else $body_border_color = ' style-' . $body_border_color . '-bg';

		$body_border_frame ='<div class="body-borders" data-border="'.$body_border.'"><div class="top-border body-border-shadow"></div><div class="right-border body-border-shadow"></div><div class="bottom-border body-border-shadow"></div><div class="left-border body-border-shadow"></div><div class="top-border'.$body_border_color.'"></div><div class="right-border'.$body_border_color.'"></div><div class="bottom-border'.$body_border_color.'"></div><div class="left-border'.$body_border_color.'"></div></div>';

		echo $body_border_frame;

	}



	?>

<?php

if(isset($_POST['edit_submit']))

{
				$current_user = wp_get_current_user();
				$_SESSION['deal_authorid_frontend'] =$current_user->ID; 
				

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

			

			$time_zone = getTimeZoneFromIpAddress();

			

			

			$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";

			

			$date = new DateTime('now', new DateTimeZone($timezone));

			

			$localtime = $date->format('d-m-Y h:i:s a');

			

			$localtime1 = $date->format('d-m-Y H:i');

			$today = $localtime1;



	

	

	$deal_product_id = $_POST['edit_deal_id'];

	$product_title=$_POST['product_title'];

	$product_type=$_POST['product_type'];

	$product_category=$_POST['product_category'];

	$product_regular_price=$_POST['normal_price'];

	$product_sale_price=$_POST['sale_price'];

	$product_desc=$_POST['product_desc'];

	$product_expirary_date =$_POST['expire_date'];

	$product_expirary_time ='00:01';//$_POST['expire_time'];

	

	$dagstilbud_from_date   =$_POST['from_date'];

	$dagstilbud_to_date     =$_POST['to_date'];

	$dagstilbud_from_time   =$_POST['from_time'];

	$dagstilbud_to_time     =$_POST['to_time'];

	

	if($deal_product_id)

	{

		$deal_post = array(

						'ID'           => $deal_product_id,

						'post_content' => $product_desc,

						);

		wp_update_post( $deal_post );


		wp_set_object_terms($deal_product_id, array($product_type,$product_category), 'product_cat');
		//wp_set_object_terms($deal_product_id, array($product_type), 'product_cat');

		update_post_meta( $deal_product_id, '_regular_price',$product_regular_price  );

		update_post_meta( $deal_product_id, '_sale_price',$product_sale_price);

		update_post_meta( $deal_product_id, '_price', $product_sale_price );	
		
		if($product_type=='dagstilbud')

		{
			update_post_meta($deal_product_id, '_sold_individually', 'yes' );	
		}

		
		if($product_expirary_date)
		{
			$wq_date_arr = explode('/',$product_expirary_date);
			$wq_expiration_date = $wq_date_arr[0].'-'.$wq_date_arr[1].'-'.$wq_date_arr[2];
			$wqdate = new DateTime($wq_expiration_date);
			$wqdate->add(new DateInterval('P1M'));
			$new_product_expirary_date = $wqdate->format('d/m/Y');
	
		}
		
		//update_post_meta($deal_product_id, '_expire_date', $product_expirary_date );
		
		update_post_meta( $deal_product_id, '_expire_time', $product_expirary_time );

		
	
		update_post_meta( $deal_product_id, '_dagstilbud_from_date', $dagstilbud_from_date );

		update_post_meta( $deal_product_id, '_dagstilbud_to_date', $dagstilbud_to_date );

		update_post_meta( $deal_product_id, '_dagstilbud_from_time', $dagstilbud_from_time );

		update_post_meta( $deal_product_id, '_dagstilbud_to_time', $dagstilbud_to_time );


		if($product_type=='dagstilbud')

		{

			$p_date_arr = explode('/',$dagstilbud_to_date);
			$p_expiration_date = $p_date_arr[0].'-'.$p_date_arr[1].'-'.$p_date_arr[2].' '.$dagstilbud_from_time;
			update_post_meta( $deal_product_id, '_final_expire_date', $p_expiration_date );

		}

		else

		{
			//$p_date_arr = explode('/',$product_expirary_date);
			
			$p_date_arr = explode('/',$new_product_expirary_date);

			$p_expiration_date = $p_date_arr[0].'-'.$p_date_arr[1].'-'.$p_date_arr[2].' '.$product_expirary_time;
			
			update_post_meta( $deal_product_id, '_final_expire_date', $p_expiration_date );
			
			update_post_meta( $deal_product_id, '_expire_date', $new_product_expirary_date );
			update_post_meta( $deal_product_id, '_startup_date', $product_expirary_date );
		}

	}

	

	if($_REQUEST['wq_date'] =='true')

	{	

	$product_expirary_date = get_post_meta($deal_product_id,'_expire_date',true);

	$product_expirary_time =get_post_meta($deal_product_id,'_expire_time',true);

	

	$dagstilbud_from_date = get_post_meta($deal_product_id,'_dagstilbud_from_date',true);     

	$dagstilbud_from_time = get_post_meta($deal_product_id,'_dagstilbud_from_time', true);

	$dagstilbud_to_date = get_post_meta($deal_product_id,'_dagstilbud_to_date',true);     

	$dagstilbud_to_time = get_post_meta($deal_product_id,'_dagstilbud_to_time', true);

	

	if($product_expirary_date)

	{

		$product_date_arr = explode('/',$product_expirary_date);


		$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;

		if(strtotime($product_expiration) > strtotime($today))

		{

			$my_post = array(

			  'ID'           => $deal_product_id,

			  'post_status'   => 'publish'

			 );


		  $product_idd = wp_update_post( $my_post ,true);

			if(is_wp_error($product_idd))

			{

				update_option('today_err'.get_the_ID(),$product_idd->get_error_message());

			}

		}
	}

	if($dagstilbud_from_date)

	{

		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);

		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;

		

		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);

		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;

		

		

		update_option("wq_dddd",$_REQUEST['wq_date']);

		if(strtotime($dagstilbud_end_date) > strtotime($today))

		{

			$my_post = array(

			  'ID'           => $deal_product_id,

			  'post_status'   => 'publish'

			 );

	   $product_idd = wp_update_post( $my_post ,true);

			if(is_wp_error($product_idd))

			{

				update_option('today_err'.get_the_ID(),$product_idd->get_error_message());

			}

		}

		

	}

	

	}

		wp_redirect(get_permalink().'?edit_deal=true');

		exit();

}





if(isset($_POST['submit']))

{

	$current_user = wp_get_current_user();
	
	$product_title          =$_POST['product_title'];

	$product_type           =$_POST['product_type'];

	$product_category       =$_POST['product_category'];

	$product_regular_price  =$_POST['normal_price'];

	$product_sale_price     =$_POST['sale_price'];

	$product_desc           =$_POST['product_desc'];

	$product_expirary_date  =$_POST['expire_date'];

	$product_expirary_time  ='00:01';//$_POST['expire_time'];

	$dagstilbud_from_date   =$_POST['from_date'];

	$dagstilbud_to_date     =$_POST['to_date'];

	$dagstilbud_from_time   =$_POST['from_time'];

	$dagstilbud_to_time     =$_POST['to_time'];

	
	//$product_title          =$_POST['product_name_list'];
	
	$_SESSION['deal_authorid_frontend'] =$current_user->ID; 
	
	$post_id = wp_insert_post( array(

        'post_title' => $product_title,

        'post_content' => $product_desc,

        'post_status' => 'publish',

        'post_type' => "product",
		
		'author' =>$current_user->ID

    ) );

    wp_set_object_terms( $post_id, 'simple', 'product_type' );

	wp_set_object_terms($post_id, array($product_type,$product_category), 'product_cat');

    update_post_meta( $post_id, '_visibility', 'visible' );

    update_post_meta( $post_id, '_stock_status', 'instock');

    update_post_meta( $post_id, 'total_sales', '0' );

    update_post_meta( $post_id, '_downloadable', 'no' );

    update_post_meta( $post_id, '_virtual', 'yes' );

    update_post_meta( $post_id, '_regular_price',$product_regular_price  );

    update_post_meta( $post_id, '_sale_price',$product_sale_price);

    update_post_meta( $post_id, '_purchase_note', '' );

    update_post_meta( $post_id, '_featured', 'no' );

    update_post_meta( $post_id, '_weight', '' );

    update_post_meta( $post_id, '_length', '' );

    update_post_meta( $post_id, '_width', '' );

    update_post_meta( $post_id, '_height', '' );

    update_post_meta( $post_id, '_sku', '' );

    update_post_meta( $post_id, '_product_attributes', array() );

    update_post_meta( $post_id, '_sale_price_dates_from', '' );

    update_post_meta( $post_id, '_sale_price_dates_to', '' );

    update_post_meta( $post_id, '_price', $product_sale_price );

    

	if($product_type=='dagstilbud')

	{

		update_post_meta( $post_id, '_sold_individually', 'yes' );	

	}

		

	

    update_post_meta( $post_id, '_manage_stock', 'no' );

    update_post_meta( $post_id, '_backorders', 'no' );

    update_post_meta( $post_id, '_stock', '' );

	
	if($product_expirary_date)
	{
		$wq_date_arr = explode('/',$product_expirary_date);
		$wq_expiration_date = $wq_date_arr[0].'-'.$wq_date_arr[1].'-'.$wq_date_arr[2];
		$wqdate = new DateTime($wq_expiration_date);
		$wqdate->add(new DateInterval('P1M'));
		$new_product_expirary_date = $wqdate->format('d/m/Y');
	}
	
	

	//update_post_meta( $post_id, '_expire_date', $product_expirary_date );
	update_post_meta( $post_id, '_expire_date', $new_product_expirary_date );

	update_post_meta( $post_id, '_expire_time', $product_expirary_time );
	
	update_post_meta( $post_id, '_startup_date', $product_expirary_date );
	update_post_meta( $post_id, '_startup_time', $product_expirary_time );


	update_post_meta( $post_id, '_dagstilbud_from_date', $dagstilbud_from_date );

	update_post_meta( $post_id, '_dagstilbud_to_date', $dagstilbud_to_date );

	update_post_meta( $post_id, '_dagstilbud_from_time', $dagstilbud_from_time );

	update_post_meta( $post_id, '_dagstilbud_to_time', $dagstilbud_to_time );

	

		if($product_type=='dagstilbud')

		{

			$p_date_arr = explode('/',$dagstilbud_to_date);



			$p_expiration_date = $p_date_arr[0].'-'.$p_date_arr[1].'-'.$p_date_arr[2].' '.$dagstilbud_from_time;

			

			update_post_meta( $post_id, '_final_expire_date', $p_expiration_date );

		}

		else

		{

			//$p_date_arr = explode('/',$product_expirary_date);
			//$p_expiration_date = $p_date_arr[0].'-'.$p_date_arr[1].'-'.$p_date_arr[2].' '.$product_expirary_time;

			$p_date_arr = explode('/',$new_product_expirary_date);
			$p_expiration_date = $p_date_arr[0].'-'.$p_date_arr[1].'-'.$p_date_arr[2].' '.$product_expirary_time;

			update_post_meta( $post_id, '_final_expire_date', $p_expiration_date );

		}

			$wq_butik_name = get_user_meta($current_user->ID,'vendor_name',true);
			$vendor_term = get_terms( array(

					'taxonomy' => 'wcpv_product_vendors',

					'hide_empty' => false,

					'name' =>$wq_butik_name,

			        )

			 );


			$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	

			$vendor_address='';

			$vendor_by = '';

			$vendor_zip= '';

			if($vendor_term_meta)

			{
				foreach($vendor_term_meta as $km=>$vm)

				{
					if($km =='address')

					$vendor_address = $vm[0];

					if($km =='by')

					$vendor_by = $vm[0];

					if($km =='zip')

					$vendor_zip = $vm[0];
				}

			}

			

			$vendor_name = $vendor_term[0]->name;		 

			$user = get_user_by( 'login', $vendor_name );

			$vendor_city = get_post_meta($user->ID,'wpsl_city');

			$zipcode=$vendor_zip;

			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$zipcode."&sensor=false";

			$details=file_get_contents($url);

			$result = json_decode($details,true);

			$lat=$result['results'][0]['geometry']['location']['lat'];
			$lng=$result['results'][0]['geometry']['location']['lng'];

			update_post_meta($post_id,'wpsl_address',$vendor_address);

			update_post_meta($post_id,'wpsl_city',$vendor_by);

			update_post_meta($post_id,'wpsl_lat',$lat);

			update_post_meta($post_id,'wpsl_lng',$lng);

			update_post_meta($post_id,'wpsl_zip',$vendor_zip);

			
			wp_set_object_terms( $post_id, $vendor_term[0]->term_id, WC_PRODUCT_VENDORS_TAXONOMY );


		/// send mail if vendor has offer 


		$billing_email = get_user_meta(get_current_user_id(),'receiver_list',true);

		$billing_email = array_unique($billing_email);


		if($billing_email)

		{

			$to = $billing_email;

			$subject = 'There is a new deal you could be interested in…';

			$body = 'There is a new deal you could be interested in…';

			$headers = array('Content-Type: text/html; charset=UTF-8');

			

			foreach($billing_email as $be)

			{
				$userdata = get_user_by('email',$be);

				$has_offer = get_user_meta($userdata->ID, 'check_vendor_has_offer',true); 

				if($has_offer)

				{
					wp_mail( $be, $subject, $body, $headers );
				}

			}

		}

		wp_redirect(get_permalink().'?submit_deal=true');

		exit();

}



?> 

 

 

	<div class="box-wrapper<?php echo esc_html($back_class); ?>"<?php echo wp_kses_post($background_style); ?>>

		<div class="box-container<?php echo esc_attr($boxed_width); ?>">

		<script type="text/javascript">UNCODE.initBox();</script>

		<?php

			if ($is_redirect !== true) {

				if ($menutype === 'vmenu-offcanvas' || $menutype === 'menu-overlay' || $menutype === 'menu-overlay-center') {

					$mainmenu = new unmenu('offcanvas_head', $menutype);

					echo uncode_remove_wpautop( $mainmenu->html );

				}

				$mainmenu = new unmenu($menutype, $menutype);

				echo uncode_remove_wpautop( $mainmenu->html );

			}

			?>

			<script type="text/javascript">UNCODE.fixMenuHeight();</script>

			<div class="main-wrapper">

				<div class="main-container">

					<div class="page-wrapper<?php if ($onepage) echo ' main-onepage'; ?>">

						<div class="sections-container">