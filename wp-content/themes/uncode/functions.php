<?php
/**
 * uncode functions and definitions
 *
 * @package uncode
 */

$ok_php = true;
if ( function_exists( 'phpversion' ) ) {
	$php_version = phpversion();
	if (version_compare($php_version,'5.3.0') < 0) $ok_php = false;
}
if (!$ok_php && !is_admin()) {
	$title = esc_html__( 'PHP version obsolete','uncode' );
	$html = '<h2>' . esc_html__( 'Ooops, obsolete PHP version' ,'uncode' ) . '</h2>';
	$html .= '<p>' . sprintf( wp_kses( 'We have coded the Uncode theme to run with modern technology and we have decided not to support the PHP version 5.2.x just because we want to challenge our customer to adopt what\'s best for their interests.%sBy running obsolete version of PHP like 5.2 your server will be vulnerable to attacks since it\'s not longer supported and the last update was done the 06 January 2011.%sSo please ask your host to update to a newer PHP version for FREE.%sYou can also check for reference this post of WordPress.org <a href="https://wordpress.org/about/requirements/">https://wordpress.org/about/requirements/</a>' ,'uncode', array('a' => 'href') ), '</p><p>', '</p><p>', '</p><p>') . '</p>';

	wp_die( $html, $title, array('response' => 403) );
}

/**
 * Load the main functions.
 */
require_once get_template_directory() . '/core/inc/main.php';
require_once get_template_directory() . '/wq_function.php';
/**
 * Load the admin functions.
 */
require_once get_template_directory() . '/core/inc/admin.php';

/**
 * Load the uncode export file.
 */
require_once get_template_directory() . '/core/inc/export/uncode_export.php';

/**
 * Font system.
 */
require_once get_template_directory() . '/core/font-system/font-system.php';

/**
 * Load the color system.
 */
require_once get_template_directory() . '/core/inc/colors.php';

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
require_once get_template_directory() . '/core/theme-options/assets/theme-mode/functions.php';

/**
 * Required: include OptionTree.
 */
load_template( get_template_directory() . '/core/theme-options/ot-loader.php' );

/**
 * Load the theme options.
 */
require_once get_template_directory() . '/core/theme-options/assets/theme-mode/theme-options.php';

/**
 * Load the main functions.
 */
require_once get_template_directory() . '/core/inc/performance.php';

/**
 * Load the theme meta boxes.
 */
require_once get_template_directory() . '/core/theme-options/assets/theme-mode/meta-boxes.php';

/**
 * Load TGM plugins activation.
 */
require_once get_template_directory() . '/core/plugins_activation/init.php';

/**
 * Load the media enhanced function.
 */
require_once( ABSPATH . WPINC . '/class-oembed.php' );
require_once get_template_directory() . '/core/inc/media-enhanced.php';

/**
 * Load the bootstrap navwalker.
 */
require_once get_template_directory() . '/core/inc/wp-bootstrap-navwalker.php';

/**
 * Load the bootstrap navwalker.
 */
require_once get_template_directory() . '/core/inc/uncode-comment-walker.php';

/**
 * Load menu builder.
 */
if ($ok_php) require_once get_template_directory() . '/partials/menus.php';

/**
 * Load header builder.
 */
if ($ok_php) require_once get_template_directory() . '/partials/headers.php';

/**
 * Load elements partial.
 */
if ($ok_php) require_once get_template_directory() . '/partials/elements.php';

/**
 * Custom template tags for this theme.
 */
require_once get_template_directory() . '/core/inc/template-tags.php';

/**
 * Helpers functions.
 */
require_once get_template_directory() . '/core/inc/helpers.php';

/**
 * Customizer additions.
 */
require_once get_template_directory() . '/core/inc/customizer.php';

/**
 * Customizer WooCommerce additions.
 */
if (class_exists( 'WooCommerce' )) {
	require_once get_template_directory() . '/core/inc/customizer-woocommerce.php';
}

/**
 * Load one click demo
 */
require_once get_template_directory() . '/core/one-click-demo/init.php';

/**
 * Load Jetpack compatibility file.
 */
require_once get_template_directory() . '/core/inc/jetpack.php';

require_once get_template_directory() . '/vendor_edit.php';

add_action( 'wp_ajax_subscribe_newsletter_action', 'subscribe_newsletter_action_callback' );
add_action( 'wp_ajax_nopriv_subscribe_newsletter_action', 'subscribe_newsletter_action_callback' );
function subscribe_newsletter_action_callback() 
{
	global $wpdb;
	$vendor_email = $_POST["vendor_email"];

	if($vendor_email)
	{

		$data = array(
				'email'     => $vendor_email,
				'status'    => 'subscribed',
				'firstname' => ' ',
				'lastname'  => ' '
			);
			
			syncMailchimp($data);
	}
			

	echo "SUCCESS";
	wp_die();
}
function syncMailchimp($data) {
				$apiKey = 'b6da72917e92bca53b301c9b71f8a46f-us15';             
				$listId = 'ab412c5e2e';
				
				//$apiKey = 'bbb5520fb1e41e3139d6709e9c41db65-us15';         //// this is binod api key
				//$listId = '3b3c2047e6';
			
				$memberId = md5(strtolower($data['email']));
				$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
				$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
				
				$json = json_encode(array(
					'email_address' => $data['email'],
					'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
					'merge_fields'  => array(
						'FNAME'     => $data['firstname'],
						'LNAME'     => $data['lastname']
					)
				));
			
				$ch = curl_init($url);
			
				curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 
			
				$result = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
			   
			  
				return $httpCode;
			}
			
function vendor_redirect_action($user_login, $user ) {

				$user_meta=get_userdata($user->ID);

					$user_roles=$user_meta->roles;
					
					foreach($user_roles as $k=>$v)
					{
						update_option('wq_idd',$v);
						if($v == 'wc_product_vendors_manager_vendor' || $v == 'wc_product_vendors_admin_vendor' )
						{
							//wp_redirect('http://stayfab.dk/vendor-dashboard');
							wp_redirect( home_url().'/vendor-dashboard' );
							exit;
						}
					}
					
				
}
add_action('wp_login', 'vendor_redirect_action',10,2);

function deal_func( $atts, $content = "" ) {
	
			ob_start();
			
			include( get_template_directory() . '/deals.php');
			$content = ob_get_clean();
			return $content;
}
add_shortcode( 'deal_lists', 'deal_func' );

function deal_category_func( $atts, $content = "" ) {
	
			$content  = '<br>';
			$content .='<div id="deal_by_zip">';
			$content .='Postnummer: <input id="wq-search-zip" type="text" value="'.$_GET["zip_search"].'" name="wq-search-zip" placeholder="">';
			$content .='<input id="wq-search-btn" type="submit" value="SØG">';
			$content .='</div><br>';
			$content .='<b><u>Behandling</u></b><br>';
			$content .= '<div id="div_product_category">';
			$content .= '<input type="checkbox" name="product_category" value="310"><label>Kvinder klip/frisør</label><br>';
			$content .= '<input type="checkbox" name="product_category" value="311"><label>Mænd klip/frisør</label><br>';
			$content .= '<input type="checkbox" name="product_category" value="312"><label>Kosmetolog behandlinger</label><br>';
			$content .= '<input type="checkbox" name="product_category" value="313"><label>Negle behandlinger</label><br>';
			$content .= '<input type="checkbox" name="product_category" value="424"><label>Elevbehandlinger</label><br>';
			$content .= '<input type="checkbox" name="product_category" value="314"><label>Andet</label><br>';
			$content .= '</div>';
			
			return $content;
}
add_shortcode( 'deal_category', 'deal_category_func' );

function deal_Frisør_func( $atts, $content = "" ) {
	
			$args = array( 
					'post_type' => 'product', 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(310,311),
												'operator' => 'IN',
											)
   										 )
			);	
			
			$loop = new WP_Query( $args );	
			$total_deal = $loop->found_posts;
			if(empty($total_deal))
			{
				$total_deal = 0;	
			}
			
			
			$content = '<h2 style="margin-top:10px;text-align:center;">'.$total_deal.'</h2>'.'<h3 style="text-align:center;margin-top: 10px;">Frisør</h3>';
			return $content;
}
add_shortcode( 'deal_Frisor', 'deal_Frisør_func' );

function deal_Kosmetolog_func( $atts, $content = "" ) {
	
			$args = array( 
					'post_type' => 'product', 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(312),
												'operator' => 'IN',
											)
   										 )
			);	
			
			$loop = new WP_Query( $args );	
			$total_deal = $loop->found_posts;
			if(empty($total_deal))
			{
				$total_deal = 0;	
			}
			
			
			$content = '<h2 style="margin-top:10px;text-align:center;">'.$total_deal.'</h2>'.'<h3 style="text-align:center;margin-top: 10px;">Kosmetolog</h3>';
			return $content;
}
add_shortcode( 'deal_Kosmetolog', 'deal_Kosmetolog_func' );

function deal_Negletekniker_func( $atts, $content = "" ) {
	
			$args = array( 
					'post_type' => 'product', 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(313),
												'operator' => 'IN',
											)
   										 )
			);	
			
			$loop = new WP_Query( $args );	
			$total_deal = $loop->found_posts;
			if(empty($total_deal))
			{
				$total_deal = 0;	
			}
			
			
			$content = '<h2 style="margin-top:10px;text-align:center;">'.$total_deal.'</h2>'.'<h3 style="text-align:center;margin-top: 10px;">Negletekniker</h3>';
			return $content;
}
add_shortcode( 'deal_Negletekniker', 'deal_Negletekniker_func' );

function deal_Spa_func( $atts, $content = "" ) {
	
			$args = array( 
					'post_type' => 'product', 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(314),
												'operator' => 'IN',
											)
   										 )
			);	
			
			$loop = new WP_Query( $args );	
			$total_deal = $loop->found_posts;
			if(empty($total_deal))
			{
				$total_deal = 0;	
			}
			$content = '<h2 style="margin-top:10px;text-align:center;">'.$total_deal.'</h2>'.'<h3 style="text-align:center;margin-top: 10px;">Spa</h3>';
			return $content;
}
add_shortcode( 'deal_Spa', 'deal_Spa_func' );


function opret_deal_shortcode_func( $atts, $content = "" ) {
	
			ob_start();
			
			include( get_template_directory() . '/opret_en_deal.php');
			$content = ob_get_clean();
			return $content;
}
add_shortcode( 'opret_deal_shortcode', 'opret_deal_shortcode_func' );



function mine_produkter_shortcode_func( $atts, $content = "" ) {
	
			ob_start();
			
			include( get_template_directory() . '/mine_produkter.php');
			$content = ob_get_clean();
			return $content;
}
add_shortcode( 'mine_produkter_shortcode', 'mine_produkter_shortcode_func' );

function rediger_profil_func( $atts, $content = "" ) {
	
			ob_start();
			
			include( get_template_directory() . '/edit_butik.php');
			$content = ob_get_clean();
			return $content;
}
add_shortcode( 'edit_butik_shortcode', 'rediger_profil_func' );


add_action( 'wp_ajax_grab_deal_list_action', 'grab_deal_list_action_callback' );
add_action( 'wp_ajax_nopriv_grab_deal_list_action', 'grab_deal_list_action_callback' );
function grab_deal_list_action_callback() 
{
	global $wpdb;
	$deal_cat = $_POST["deal_cat"];
	$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true);
	
	if($deal_cat =='99999')
	$args = array( 
					'post_type' => 'product', 
  					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC',
					'paged' =>$page,
					'post__not_in' => $hidden_product,
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(308,309),
												'operator' => 'IN',
											)
   										 )
			);	
	else
	$args = array( 
					'post_type' => 'product', 
  					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC',
					'paged' =>$page,
					'post__not_in' => $hidden_product,
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => $deal_cat,
												'operator' => 'IN',
											)
   										 )
			);	
			
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
	
	$time_zone = getTimeZoneFromIpAddress();
	
	
	$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";
	
	$date = new DateTime('now', new DateTimeZone($timezone));
	
	$localtime = $date->format('d-m-Y h:i:s a');
	$localtime1 = $date->format('d-m-Y H:i');
	$today = $localtime;
	
	
	
			
	$loop = new WP_Query( $args );
	
	$deal_html = '';
	$deal_html .= '<table style="margin-left: 50px;margin-bottom:20px">';
	//$deal_html .= '<tr>';
	
	//$deal_html .= '<th>Logo</th>';
	//$deal_html .= '<th>Vendor</th>';
	//$deal_html .= '<th>Bestil</th>';
	
	//$deal_html .= '</tr>';
	
  while ( $loop->have_posts() ) : $loop->the_post(); 
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
		$date_compare = get_the_title().'/'.$product_expiration.'/'.$localtime1.'<br>';
		if(strtotime($product_expiration) < strtotime($localtime1))
		{
			continue;
		}
		
		
	}
	
	if($dagstilbud_from_date)
	{
		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
		
		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
		
		$dagstilbud_start_date1 = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_to_time;
		$date_compare = get_the_title().'/'.$dagstilbud_start_date1.'/'.$localtime1.'<br>';
		if(strtotime($dagstilbud_start_date1) < strtotime($localtime1))
		{
		continue;	
		}
		
	}
 

	
 	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$terms = get_the_terms(get_the_ID(), 'product_cat' );
	$product_cat_id[] =array();
	
	foreach ($terms as $term) {
		$product_cat_id[] = $term->name;
	}
	unset($product_cat_id[0]);
	
	if(in_array('Dagstilbud',$product_cat_id))
		$wq_expirary_date =  $dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time;
	else
		$wq_expirary_date =  $product_expirary_date.' '.$product_expirary_time;
	
	
	global $post;
    $author_id=$post->post_author;
	
	$wq_vendor_name = get_user_meta($author_id,'vendor_name',true);
	
	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$wq_vendor_name,
			        )
			 );
	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	
	
	if(!$vendor_term)
	continue;
	
	
	$vendor_name = $vendor_term[0]->name;		 
	$user = get_user_by( 'login', $vendor_name );
	
	$u_rating =get_option('upr_rating_'.$user->ID);
	
	if(is_array($u_rating))
	{
		$vendor_rating = array_sum($u_rating)/count($u_rating);
	}else
	{
		$vendor_rating = 0;
	}
	
	global $wpdb;
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$author_id." ");
	
	foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	
	if($counter>0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value =0;
	$vendor_rating = $butik_rate_value;
	$star = '';
	if($vendor_rating<1)
	{
		if($vendor_rating<.5)
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		else
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>1 && $vendor_rating<2 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>2 && $vendor_rating<3 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>3 && $vendor_rating<4 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>4)
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
	}
	
	
	foreach($vendor_term_meta as $km=>$vm)
	{
		if($km =='address')
		$vendor_address = $vm[0];
		if($km =='vendor_data')
		$vendor_data = $vm[0];
	}
	$vendor_data = unserialize($vendor_data);
	$logo_post_id = $vendor_data['logo'];
	

	$feat_image = wp_get_attachment_url( $logo_post_id );
	
 
  	$deal_html .= '<tr>';
	$deal_html .= '<td style="width:10%;font-weight:100">'.'<a href="http://stayfab.dk/vendor/'.$vendor_term[0]->slug.'">'.'<img src="'.$feat_image.'">'.'</a>'.'</td>';	
	$deal_html .= '<td style="font-weight:100">'.get_the_title().'<br>'.'<a href >'.'<div data-author_id="'.$user->ID.'" class="get_vendor_all_deal">'.$vendor_name.'</div></a>'.substr($vendor_rating,0,4).' '.$star.'<br>'.$product_desc.'<br>'.$vendor_address.'&nbsp&nbsp&nbsp&nbsp'.'<b>'.$wq_expirary_date.'</b>'.'</td>';
	$deal_html .= '<td style="width:25%;font-weight:100">'.'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>'.'</td>';		
	$deal_html .= '<td style="width:15%;font-weight:100">'.'<input type="button" id="wq_addtocart" data-product_id="'.get_the_ID().'" value="KØB">'.'</td>';		
    $deal_html .= '</tr>';    
 unset($product_cat_id); 	
 endwhile; 
 $deal_html .= '</table>';

    


	echo $deal_html;
	wp_die();
}


add_action( 'wp_ajax_get_selected_category_deal_action', 'get_selected_category_deal_action_callback' );
add_action( 'wp_ajax_nopriv_get_selected_category_deal_action', 'get_selected_category_deal_action_callback' );
function get_selected_category_deal_action_callback() 
{
	global $wpdb;
	$checkValues = $_POST["checkValues"];
	$product_type = $_POST["product_type"];
	
	$cat_ids[] = array();
	foreach($checkValues as $v)
	{
		$cat_ids[] = $v;
	}
	//array_push($cat_ids,$product_type);
	$cat_ids = array_filter($cat_ids);
	
	if(empty($cat_ids))
	{
		array_push($cat_ids,$product_type);
	}
	
	$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true);
	$args = array( 
					'post_type' => 'product', 
					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC',
					'paged' =>$page,
					'post__not_in' => $hidden_product,
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => $cat_ids,
												'operator' => 'IN',
											)
   										 )
			);	
			
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
	
	$time_zone = getTimeZoneFromIpAddress();
	
	
	$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";
	
	$date = new DateTime('now', new DateTimeZone($timezone));
	
	$localtime = $date->format('d-m-Y h:i:s a');
	
	$localtime1 = $date->format('d-m-Y H:i');
	
	$today = $localtime;
	
	
	
	
	
	
	$loop = new WP_Query( $args );
	
	$deal_html = '';
	$deal_html .= '<table style="margin-left: 50px;margin-bottom:20px">';
	/*$deal_html .= '<tr>';
	
	$deal_html .= '<th>Logo</th>';
	$deal_html .= '<th>Vendor</th>';
	$deal_html .= '<th>Bestil</th>';
	$deal_html .= '</tr>';*/
	
  while ( $loop->have_posts() ) : $loop->the_post(); 	
	
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
		$date_compare = get_the_title().'/'.$product_expiration.'/'.$localtime1.'<br>';
		if(strtotime($product_expiration) < strtotime($localtime1))
		{
			continue;
		}
		
		
	}
	
	if($dagstilbud_from_date)
	{
		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
		
		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
		
		$dagstilbud_start_date1 = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_to_time;
		$date_compare = get_the_title().'/'.$dagstilbud_start_date1.'/'.$localtime1.'<br>';
		if(strtotime($dagstilbud_start_date1) < strtotime($localtime1))
		{
		continue;	
		}
		
	}

 	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$terms = get_the_terms(get_the_ID(), 'product_cat' );
	$product_cat_id[] =array();
	
	foreach ($terms as $term) {
		$product_cat_id[] = $term->name;
	}
	unset($product_cat_id[0]);
	
	if(in_array('Dagstilbud',$product_cat_id))
		$wq_expirary_date =  $dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time;
	else
		$wq_expirary_date =  $product_expirary_date.' '.$product_expirary_time;
	
	
	
	global $post;
    $author_id=$post->post_author;
	
	$wq_vendor_name = get_user_meta($author_id,'vendor_name',true);
	
	
	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$wq_vendor_name,
			        )
			 );
	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	
	
	if(!$vendor_term)
	continue;
	
	
	$vendor_name = $vendor_term[0]->name;		 
	$user = get_user_by( 'login', $vendor_name );
	
	$u_rating =get_option('upr_rating_'.$user->ID);
	
	if(is_array($u_rating))
	{
		$vendor_rating = array_sum($u_rating)/count($u_rating);
	}else
	{
		$vendor_rating = 0;
	}
	
	
	global $wpdb;
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$author_id." ");
	
	foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	
	if($counter>0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value =0;
	$vendor_rating = $butik_rate_value;
	
	$star = '';
	if($vendor_rating<1)
	{
		if($vendor_rating<.5)
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		else
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>1 && $vendor_rating<2 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>2 && $vendor_rating<3 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>3 && $vendor_rating<4 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>4)
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
	}
	
	
	foreach($vendor_term_meta as $km=>$vm)
	{
		if($km =='address')
		$vendor_address = $vm[0];
		if($km =='vendor_data')
		$vendor_data = $vm[0];
	}
	$vendor_data = unserialize($vendor_data);
	$logo_post_id = $vendor_data['logo'];
	

	$feat_image = wp_get_attachment_url( $logo_post_id );
 
 
  $deal_html .= '<tr>';
	$deal_html .= '<td style="width:10%;font-weight:100">'.'<a href="http://stayfab.dk/vendor/'.$vendor_term[0]->slug.'">'.'<img src="'.$feat_image.'">'.'</a>'.'</td>';	
	$deal_html .= '<td style="font-weight:100">'.get_the_title().'<br>'.'<a href >'.'<div data-author_id="'.$user->ID.'" class="get_vendor_all_deal">'.$vendor_name.'</div></a>'.substr($vendor_rating,0,4).' '.$star.'<br>'.$product_desc.'<br>'.$vendor_address.'&nbsp&nbsp&nbsp&nbsp'.'<b>'.$wq_expirary_date.'</b>'.'</td>';
	//$deal_html .= '<td>'.substr($vendor_rating,0,4).'<br>'.$star.'</td>';	
	//$deal_html .= '<td>'.$product_regular_price.' DKK /'.$product_sale_price.' DKK'.'</td>';	
	//$deal_html .= '<td>'.$product_expirary_date.' '.$product_expirary_time.'</td>';	
	//$deal_html .= '<td>'.$product_desc.'</td>';	
	//$deal_html .= '<td>'.$vendor_address.'</td>';
	$deal_html .= '<td style="width:25%;font-weight:100">'.'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>'.'</td>';
	$deal_html .= '<td style="width:15%;font-weight:100">'.'<input type="button" id="wq_addtocart" data-product_id="'.get_the_ID().'" value="KØB">'.'</td>';		
    $deal_html .= '</tr>';  
 unset($product_cat_id); 	
 endwhile; 
 $deal_html .= '</table>';
	
	
	
	
	
	
	echo $deal_html;
	
	wp_die();
}

add_action( 'wp_ajax_wq_add_to_cart_action', 'wq_add_to_cart_action_callback' );
add_action( 'wp_ajax_nopriv_wq_add_to_cart_action', 'wq_add_to_cart_action_callback' );
function wq_add_to_cart_action_callback(){
	global $wpdb,$woocommerce;
	
	$pid = $_POST["p_id"];
	$qty = $_POST["qty"];
	
	$woocommerce->cart->add_to_cart( $pid,$qty);
	
	echo true;
	wp_die();
}


add_action( 'wp_ajax_get_clicked_vendor_deal_action', 'get_clicked_vendor_deal_action_callback' );
add_action( 'wp_ajax_nopriv_get_clicked_vendor_deal_action', 'get_clicked_vendor_deal_action_callback' );
function get_clicked_vendor_deal_action_callback() 
{
	global $wpdb;
	$vendor_user_id = $_POST["vendor_user_id"];
	
	$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true);
	
	
	$args = array( 
					'post_type' => 'product', 
					'author' =>$vendor_user_id,
					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC', 
					'posts_per_page' =>200,
					'post__not_in' => $hidden_product,
			);	
			
			
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
	
	$time_zone = getTimeZoneFromIpAddress();
	
	
	$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";
	
	$date = new DateTime('now', new DateTimeZone($timezone));
	
	$localtime = $date->format('d-m-Y h:i:s a');
	
	$localtime1 = $date->format('d-m-Y H:i');
	
	$today = $localtime;	
			
	$loop = new WP_Query( $args );
	
	$deal_html = '';
	$deal_html .= '<table style="margin-left: 50px;margin-bottom:20px">';
	$deal_html .= '<tr>';
	/*$deal_html .= '<tr>';
	
	$deal_html .= '<th>Logo</th>';
	$deal_html .= '<th>Vendor</th>';
	$deal_html .= '<th>Bestil</th>';
	$deal_html .= '</tr>';*/
	
  while ( $loop->have_posts() ) : $loop->the_post(); 
	
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
		$date_compare = get_the_title().'/'.$product_expiration.'/'.$localtime1.'<br>';
		if(strtotime($product_expiration) < strtotime($localtime1))
		{
			continue;
		}
		
		
	}
	
	if($dagstilbud_from_date)
	{
		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
		
		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
		
		$dagstilbud_start_date1 = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_to_time;
		$date_compare = get_the_title().'/'.$dagstilbud_start_date1.'/'.$localtime1.'<br>';
		if(strtotime($dagstilbud_start_date1) < strtotime($localtime1))
		{
		continue;	
		}
		
	}
 
	
 	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$terms = get_the_terms(get_the_ID(), 'product_cat' );
	$product_cat_id[] =array();
	
	foreach ($terms as $term) {
		$product_cat_id[] = $term->name;
	}
	unset($product_cat_id[0]);
	
	if(in_array('Dagstilbud',$product_cat_id))
		$wq_expirary_date =  $dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time;
	else
		$wq_expirary_date =  $product_expirary_date.' '.$product_expirary_time;
	
	
	global $post;
    $author_id=$post->post_author;
	
	$wq_vendor_name = get_user_meta($author_id,'vendor_name',true);
	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$wq_vendor_name,
			        )
			 );
	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	
	
	if(!$vendor_term)
	continue;
	
	
	$vendor_name = $vendor_term[0]->name;		 
	$user = get_user_by( 'login', $vendor_name );
	
	$u_rating =get_option('upr_rating_'.$user->ID);
	
	if(is_array($u_rating))
	{
		$vendor_rating = array_sum($u_rating)/count($u_rating);
	}else
	{
		$vendor_rating = 0;
	}
	
	global $wpdb;
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$author_id." ");
	
	foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	
	if($counter>0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value =0;
	
	$vendor_rating = $butik_rate_value;
	
	$star = '';
	if($vendor_rating<1)
	{
		if($vendor_rating<.5)
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		else
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>1 && $vendor_rating<2 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>2 && $vendor_rating<3 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>3 && $vendor_rating<4 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>4)
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
	}
	
	
	foreach($vendor_term_meta as $km=>$vm)
	{
		if($km =='address')
		$vendor_address = $vm[0];
		if($km =='vendor_data')
		$vendor_data = $vm[0];
	}
	$vendor_data = unserialize($vendor_data);
	$logo_post_id = $vendor_data['logo'];
	

	$feat_image = wp_get_attachment_url( $logo_post_id );
 
 
  	$deal_html .= '<tr>';
	$deal_html .= '<td style="width:10%;font-weight:100">'.'<a href="http://stayfab.dk/vendor/'.$vendor_term[0]->slug.'">'.'<img src="'.$feat_image.'">'.'</a>'.'</td>';	
	$deal_html .= '<td style="font-weight:100">'.get_the_title().'<br>'.'<a href >'.'<div data-author_id="'.$user->ID.'" class="get_vendor_all_deal">'.$vendor_name.'</div></a>'.substr($vendor_rating,0,4).' '.$star.'<br>'.$product_desc.'<br>'.$vendor_address.'&nbsp&nbsp&nbsp&nbsp'.'<b>'.$wq_expirary_date.'</b>'.'</td>';
	//$deal_html .= '<td>'.substr($vendor_rating,0,4).'<br>'.$star.'</td>';	
	//$deal_html .= '<td>'.$product_regular_price.' DKK /'.$product_sale_price.' DKK'.'</td>';	
	//$deal_html .= '<td>'.$product_expirary_date.' '.$product_expirary_time.'</td>';	
	//$deal_html .= '<td>'.$product_desc.'</td>';	
	//$deal_html .= '<td>'.$vendor_address.'</td>';
	$deal_html .= '<td style="width:25%;font-weight:100">'.'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>'.'</td>';
	$deal_html .= '<td style="width:15%;font-weight:100">'.'<input type="button" id="wq_addtocart" data-product_id="'.get_the_ID().'" value="KØB">'.'</td>';		
    $deal_html .= '</tr>';   
  	
 unset($product_cat_id);
 endwhile; 
 $deal_html .= '</table>';
	
	echo $deal_html;
	
	wp_die();
}



function action_woocommerce_thankyou( $order_id ) { 
  	
	/*	ob_start();

		$url = 'http://stayfab.dk/wp-admin/admin-ajax.php?action=generate_wpo_wcpdf&document_type=packing-slip&order_ids='.$order_id.'&_wpnonce='.wp_create_nonce('generate_wpo_wcpdf');
		
		$ch = curl_init();
		
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_exec($ch);
		
		curl_close($ch);
		
		ob_clean();
	*/
	
	
	$order = new WC_Order( $order_id );
	$items = $order->get_items();
	$product_id = '';
	foreach ( $items as $item ) 
	{
		$product_id = $item['product_id'];
	}
	
	$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true);  ///   if product category is dagstilbud
	
	if($product_id)
	{
		$post_tmp = get_post($product_id);
		$author_id = $post_tmp->post_author;
		$receiver = get_current_user_id();
		
		$current_user = wp_get_current_user();
		$current_user_email = $current_user->user_email;
		
		$receiver_email = $current_user_email;  //get_post_meta($order_id, '_billing_email', true);
		$receiver_list = get_user_meta($author_id, 'receiver_list',true); 
		
		if(!$receiver_list)
		{
			$receiver_list = array();
		}
		array_push($receiver_list,$receiver_email);
		update_user_meta( $author_id,'receiver_list',$receiver_list);

	}
	
	if(!$hidden_product)
	{
		$hidden_product = array();	
	}
	
	$term_list = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
	
	if(in_array( 309 ,$term_list ))                    ////////  309 is the term_id of category "dagstilbud "
	{
		array_push($hidden_product,$product_id);
	}
	
	update_user_meta( get_current_user_id(),'product_hidden',$hidden_product);
	
	/////////    check vendor has offer or not   ///////////
	$vendor_has_offer = get_post_meta($order_id, 'vendor_has_offer', true);
	if($vendor_has_offer)
	{
		$billing_email = get_post_meta($order_id, '_billing_email', true);
		
		$to = $billing_email;
		$subject = 'There is a new deal you could be interested in…';
		$body = 'There is a new deal you could be interested in…';
		$headers = array('Content-Type: text/html; charset=UTF-8');

		//wp_mail( $to, $subject, $body, $headers );
		
	}
	
}
add_action( 'woocommerce_thankyou', 'action_woocommerce_thankyou', 10, 1 ); 


///////    extra checknox on checkout page    /////////////////
//add_action('woocommerce_after_order_notes', 'add_checkbox_checkout_field');
 
function add_checkbox_checkout_field($checkout)
{
	echo '<div id="customise_checkout_field">';
	woocommerce_form_field('vendor_new_offer', array(
		'type' => 'checkbox',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
		'label' => __('Send mig en notifikation næste gang frisøren har tilbud igen.') ,
	) , $checkout->get_value('vendor_new_offer'));
	echo '</div>';
}

add_action('woocommerce_checkout_update_order_meta', 'customise_checkout_field_update_order_meta');
 
function customise_checkout_field_update_order_meta($order_id)
{
	if (!empty($_POST['vendor_new_offer']) || $_POST['vendor_new_offer'] ) {
		update_post_meta($order_id, 'vendor_has_offer', sanitize_text_field($_POST['vendor_new_offer']));
		update_user_meta( get_current_user_id(),'check_vendor_has_offer',sanitize_text_field($_POST['vendor_new_offer']));
		
	}
}
///////    end adding extra checknox on checkout page    /////////////////
add_action( 'wp_ajax_get_deal_by_zipcode_action', 'get_deal_by_zipcode_action_callback' );
add_action( 'wp_ajax_nopriv_get_deal_by_zipcode_action', 'get_deal_by_zipcode_action_callback' );
function get_deal_by_zipcode_action_callback() 
{
	global $wpdb;
	$zipcode = $_POST["zip"];
	$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true);
	$args = array(
				  'post_type' => 'product', 
				  'post_status' =>'all',
				  'meta_key'          => '_final_expire_date',
  				  'orderby'           => 'meta_value_num',
 				  'order'             => 'ASC',
				  'post__not_in' =>$hidden_product, 
				  'meta_query' => array(
									array(
										'key'     => 'wpsl_zip',
										'value'   => $zipcode,
										'compare' => '=',
									),
								),
				);	
	
	
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
	
	$time_zone = getTimeZoneFromIpAddress();
	
	
	$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";
	
	$date = new DateTime('now', new DateTimeZone($timezone));
	
	$localtime = $date->format('d-m-Y h:i:s a');
	$localtime1 = $date->format('d-m-Y H:i');
	$today = $localtime;	
	
	
	
	$loop = new WP_Query( $args );
	
	$deal_html = '';
	$deal_html .= '<table style="margin-left: 50px;margin-bottom:20px">';
	$deal_html .= '<tr>';
	/*$deal_html .= '<tr>';
	
	$deal_html .= '<th>Logo</th>';
	$deal_html .= '<th>Vendor</th>';
	$deal_html .= '<th>Bestil</th>';
	$deal_html .= '</tr>';*/
	
  while ( $loop->have_posts() ) : $loop->the_post(); 
	
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
		$date_compare = get_the_title().'/'.$product_expiration.'/'.$localtime1.'<br>';
		if(strtotime($product_expiration) < strtotime($localtime1))
		{
			continue;
		}
		
		
	}
	if($dagstilbud_from_date)
	{
		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
		
		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
		
		$dagstilbud_start_date1 = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_to_time;
		$date_compare = get_the_title().'/'.$dagstilbud_start_date1.'/'.$localtime1.'<br>';
		if(strtotime($dagstilbud_start_date1) < strtotime($localtime1))
		{
		continue;	
		}
		
	}
 
	
 	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	
	
	
	$terms = get_the_terms(get_the_ID(), 'product_cat' );
	$product_cat_id[] =array();
	
	foreach ($terms as $term) {
		$product_cat_id[] = $term->name;
	}
	unset($product_cat_id[0]);
	
	if(in_array('Dagstilbud',$product_cat_id))
		$wq_expirary_date =  $dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time;
	else
		$wq_expirary_date =  $product_expirary_date.' '.$product_expirary_time;
	
	
	
	
	global $post;
    $author_id=$post->post_author;
	
	$wq_vendor_name = get_user_meta($author_id,'vendor_name',true);
	
		
	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$wq_vendor_name,
			        )
			 );
	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	
	
	if(!$vendor_term)
	continue;
	
	
	$vendor_name = $vendor_term[0]->name;		 
	$user = get_user_by( 'login', $vendor_name );
	
	$u_rating =get_option('upr_rating_'.$user->ID);
	
	if(is_array($u_rating))
	{
		$vendor_rating = array_sum($u_rating)/count($u_rating);
	}else
	{
		$vendor_rating = 0;
	}
	
	global $wpdb;
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$author_id." ");
	
	foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	
	if($counter>0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value =0;
	$vendor_rating = $butik_rate_value;
	$star = '';
	if($vendor_rating<1)
	{
		if($vendor_rating<.5)
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		else
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>1 && $vendor_rating<2 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">';
	}
	if($vendor_rating>2 && $vendor_rating<3 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>3 && $vendor_rating<4 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">';
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png" title="bad">'; 
	}
	if($vendor_rating>4)
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png" title="bad">';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png" title="bad">'; 
	}
	
	
	foreach($vendor_term_meta as $km=>$vm)
	{
		if($km =='address')
		$vendor_address = $vm[0];
		if($km =='vendor_data')
		$vendor_data = $vm[0];
	}
	$vendor_data = unserialize($vendor_data);
	$logo_post_id = $vendor_data['logo'];
	

	$feat_image = wp_get_attachment_url( $logo_post_id );
 
 		
	
	
  	$deal_html .= '<tr>';
	$deal_html .= '<td style="width:10%;font-weight:100">'.'<a href="http://stayfab.dk/vendor/'.$vendor_term[0]->slug.'">'.'<img src="'.$feat_image.'">'.'</a>'.'</td>';	
	$deal_html .= '<td style="font-weight:100">'.get_the_title().'<br>'.'<a href >'.'<div data-author_id="'.$user->ID.'" class="get_vendor_all_deal">'.$vendor_name.'</div></a>'.substr($vendor_rating,0,4).' '.$star.'<br>'.$product_desc.'<br>'.$vendor_address.'&nbsp&nbsp&nbsp&nbsp'.'<b>'.$wq_expirary_date.'</b>'.'</td>';
	//$deal_html .= '<td>'.substr($vendor_rating,0,4).'<br>'.$star.'</td>';	
	//$deal_html .= '<td>'.$product_regular_price.' DKK /'.$product_sale_price.' DKK'.'</td>';	
	//$deal_html .= '<td>'.$product_expirary_date.' '.$product_expirary_time.'</td>';	
	//$deal_html .= '<td>'.$product_desc.'</td>';	
	//$deal_html .= '<td>'.$vendor_address.'</td>';
	$deal_html .= '<td style="width:25%;font-weight:100">'.'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>'.'</td>';
	$deal_html .= '<td style="width:15%;font-weight:100">'.'<input type="button" id="wq_addtocart" data-product_id="'.get_the_ID().'" value="KØB">'.'</td>';		
    $deal_html .= '</tr>';   
 unset($product_cat_id);	
 endwhile;
 wp_reset_postdata(); 
 $deal_html .= '</table>';
	
	echo $deal_html;
	
	wp_die();
}

add_action( 'wp_ajax_delete_deal_action', 'delete_deal_action_callback' );
add_action( 'wp_ajax_nopriv_delete_deal_action', 'delete_deal_action_callback' );
function delete_deal_action_callback(){
	global $wpdb;
	
	$pid = $_POST["p_id"];
	$force_delete = false;
	
	wp_delete_post( $pid, $force_delete ); 
	
	echo true;
	wp_die();
}

add_action( 'wp_ajax_change_deal_status_action', 'change_deal_status_action_callback' );
add_action( 'wp_ajax_nopriv_change_deal_status_action', 'change_deal_status_action_callback' );
function change_deal_status_action_callback(){
	global $wpdb;
	
	$pid = $_POST["p_id"];
	$p_status = $_POST["p_status"];
	$force_delete = false;
	
	if($p_status == 'Aktiver')
	{
		$post_status = 'publish';
	}
	else
	{
		$post_status = 'trash';
	}
	
	
	$my_post = array(
      'ID'           => $pid,
      'post_status'   => $post_status
 	 );


   wp_update_post( $my_post );
	
	echo true;
	wp_die();
}


add_action( 'wp_ajax_wq_get_expirary_date_action', 'wq_get_expirary_date_action_callback' );
add_action( 'wp_ajax_nopriv_wq_get_expirary_date_action', 'wq_get_expirary_date_action_callback' );
function wq_get_expirary_date_action_callback(){
	global $wpdb;
	$p_id = $_POST["p_id"];
			
			$expire_date  = get_post_meta( $p_id, '_expire_date',true);
			$expire_time  = get_post_meta( $p_id, '_expire_time',true);
			
			$startup_date  = get_post_meta( $p_id, '_startup_date',true);
			$startup_time  = get_post_meta( $p_id, '_startup_time',true);
			
			
			$dagstilbud_from_date   = get_post_meta( $p_id, '_dagstilbud_from_date',true);
			$dagstilbud_to_date     = get_post_meta( $p_id, '_dagstilbud_to_date',true);
			$dagstilbud_from_time   = get_post_meta( $p_id, '_dagstilbud_from_time',true);
			$dagstilbud_to_time     = get_post_meta( $p_id, '_dagstilbud_to_time',true);
			
			
			
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
				
				
				$timezone = $time_zone;
				
				$date = new DateTime('now', new DateTimeZone($timezone));
				
				$localtime = $date->format('d-m-Y h:i:s a');
				
				$today = $localtime;
			
	$status_fail = false;			
	if($expire_date)
	{
		$product_date_arr = explode('/',$expire_date);

		$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
		
		if(strtotime($product_expiration) < strtotime($today))
		{
			$status_fail = true;
		}
		
	}
	if($dagstilbud_from_date)
	{
		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
		
		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
		
		if(strtotime($dagstilbud_end_date) < strtotime($today))
		{
			//if(strtotime($dagstilbud_end_date) > strtotime($today))
			//{
				$status_fail = true;
			//}
			
		}
	}
	
	echo $status_fail;
	wp_die();
}

if(isset($_POST['submit']))
{
	add_filter( 'wp_mail_from', 'wpb_sender_email' );
	add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
}



add_filter( 'cron_schedules', 'isa_add_every_three_minutes' );
function isa_add_every_three_minutes( $schedules ) {
    $schedules['every_three_minutes'] = array(
            'interval'  => 5*60,
            'display'   => __( 'Every 3 Minutes', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'isa_add_every_three_minutes' ) ) {
    wp_schedule_event( time(), 'every_three_minutes', 'isa_add_every_three_minutes' );
}

// Hook into that action that'll fire every three minutes
add_action( 'isa_add_every_three_minutes', 'delete_expired_deal_func' );
function delete_expired_deal_func() {
    
	update_option('today_wq','testing...');
	$args = array( 
					'post_type' => 'product', 
					'posts_per_page' =>999,
					'post_status' => array('publish','trash'), 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(308,309),
												'operator' => 'IN',
											)
   										 )
			);	
			
	$loop = new WP_Query( $args );	

	while ( $loop->have_posts() ) : $loop->the_post(); 	

	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$dagstilbud_from_date = get_post_meta( get_the_ID(), '_dagstilbud_from_date',true);     
	$dagstilbud_from_time = get_post_meta( get_the_ID(), '_dagstilbud_from_time', true);
	$dagstilbud_to_date = get_post_meta( get_the_ID(), '_dagstilbud_to_date',true);     
	$dagstilbud_to_time = get_post_meta( get_the_ID(), '_dagstilbud_to_time', true);
	
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
				
				
				$timezone = $time_zone;
				
				$date = new DateTime('now', new DateTimeZone($timezone));
				
				$localtime = $date->format('d-m-Y h:i');
				
				$today = $localtime;
		
	
	if($product_expirary_date)
	{
		$product_date_arr = explode('/',$product_expirary_date);

		$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
		
		
		
		if(strtotime($product_expiration) < strtotime($today))
		{
			$my_post = array(
			  'ID'           => get_the_ID(),
			  'post_status'   => 'trash'
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
		
		
		
		if(strtotime($dagstilbud_end_date) < strtotime($today))
		{
			$my_post = array(
			  'ID'           => get_the_ID(),
			  'post_status'   => 'trash'
			 );
			
		   
		   $product_idd = wp_update_post( $my_post ,true);
			if(is_wp_error($product_idd))
			{
				update_option('today_err'.get_the_ID(),$product_idd->get_error_message());
			}
		}
		
	}
	
	
	
	endwhile;
	wp_reset_postdata();
}


/*                            function for sending separate email after completing order            */
function filter_wpo_wcpdf_invoice_title( $var ) { 
    // make filter magic happen here... 
    return '<div>I am test content...</div>'; 
}; 
         

function mysite_woocommerce_order_status_completed( $order_id ) {
	$mailer = WC()->mailer();
	
	$mails = $mailer->get_emails();
	
	
	if ( ! empty( $mails ) ) {
	
		foreach ( $mails as $mail ) {
	
			if ( $mail->id == 'customer_completed_order' ) {
						
						add_filter( 'wpo_wcpdf_invoice_title', 'filter_wpo_wcpdf_invoice_title', 10, 1 ); 
						
						add_filter( 'woocommerce_email_recipient_customer_completed_order', 'your_email_recipient_filter_function', 10, 2);
				  		
						$mail->trigger( $order_id );
					
						remove_filter( 'wpo_wcpdf_invoice_title','filter_wpo_wcpdf_invoice_title'); 
						
						remove_filter( 'woocommerce_email_recipient_customer_completed_order','your_email_recipient_filter_function'); 
			}
	
		 }
	
	}
	
}
add_action( 'woocommerce_order_status_completed', 'mysite_woocommerce_order_status_completed', 10, 1 );


function your_email_recipient_filter_function($recipient, $object ) {
    global $wpdb;
    $order_id = $object->id;
	$order = new WC_Order( $order_id );
	$items = $order->get_items();
	
	foreach ( $items as $item ) 
	{
		$product_name = $item['name'];
		$product_id = $item['product_id'];
		$author_id = get_post_field ('post_author', $product_id);
		$user_info = get_userdata($author_id);
		$second_email = $user_info->user_email;
	}
	
	$recipient = $second_email;
    return $recipient;
}



function wq_vendor_email_recipient($recipient, $object)
{
    
	$order_id = $object->id;
	$order = new WC_Order( $order_id );
	$order_items = $order->get_items();
	foreach ($order_items as $item_id => $item_data) {
		
		$product_name = $item_data['name'];
		$product_id = $item_data['product_id'];
		$product_author = get_post_field( 'post_author', $product_id );
	}
 
 	$vendor_user = get_userdata( $product_author );
	$vendor_user_email = $vendor_user->user_email;
	
	$recipient = $vendor_user_email;
  
    return $recipient;
}

function wq_customer_cancel_email_recipient($recipient, $object)
{
	$order_id = $object->id;
	$order = new WC_Order( $order_id );
	$customer_email = $order->billing_email;
	$recipient = $customer_email;
	return $recipient;
}

/*                           end function for sending separate email after completing order            */

add_action( 'woocommerce_email_rate_vendor', 'wq_rate_vendor');


function wq_rate_vendor($recipient, $object ) {
   echo "Stayfab"; 
}

// WooCommerce Rename Checkout Fields
add_filter( 'woocommerce_checkout_fields' , 'custom_rename_wc_checkout_fields' );

// Change placeholder and label text
function custom_rename_wc_checkout_fields( $fields ) {
$fields['billing']['billing_postcode']['placeholder'] = 'Postnr. *';
$fields['billing']['billing_postcode']['label'] = '';
$fields['billing']['billing_city']['placeholder'] = 'By *';
$fields['billing']['billing_city']['label'] = '';
return $fields;
}

//add_image_size( 'wq-size', 220, 180,true );

function my_delete_user( $user_id ) {
		global $wpdb;

        $user_obj = get_userdata( $user_id );
        $user_login = $user_obj->user_login;
		$user_nicename = $user_obj->user_nicename;
		
		//$terme = get_term_by( 'name', $user_login, WC_PRODUCT_VENDORS_TAXONOMY );
		$terme = get_term_by( 'slug', $user_nicename, WC_PRODUCT_VENDORS_TAXONOMY );
		
		if($terme)
		wp_delete_term( $terme->term_id, WC_PRODUCT_VENDORS_TAXONOMY);
		
		
	
}
add_action( 'delete_user', 'my_delete_user' );

/////////////  rate butik  ajax ////////////////
add_action( 'wp_ajax_wq_rate_butik_action', 'wq_rate_butik_action_callback' );
add_action( 'wp_ajax_nopriv_wq_rate_butik_action', 'wq_rate_butik_action_callback' );
function wq_rate_butik_action_callback(){
	global $wpdb;
	
	$new_rate_val 	= $_POST["new_rate_val"];
	$butik_id 		= $_POST["butik_id"];
	$product_id 	= $_POST["product_id"];
	
	
	
	$wpdb->insert( 
		'wp_user_rate', 
		array( 
			'user_id' => $butik_id,	
			'product_id' => $product_id,
			'rate_given_by' =>get_current_user_id(),
			'rate_value' =>$new_rate_val
		)
	);
	
	
				///////  send mail to vendor when rate is done
			
			$subject = 'Din salon er ratet!';
			
			$vendor_name_after_rate = get_user_meta($butik_id,'vendor_name',true);
			
			$vendor_name_after_rate_info = get_userdata($butik_id);
			
			$dir = ABSPATH.'/wp-content/plugins/woocommerce/templates/emails/customer-rate-done-vendor-email.php';
		
			ob_start();
			include($dir);
			$content = ob_get_clean();
			
			add_filter( 'wp_mail_content_type', 'set_content_type' );
			
			add_filter( 'wp_mail_from', 'wpb_sender_email' );
			add_filter( 'wp_mail_from_name', 'wpb_sender_name' );
			
			
			wp_mail( $vendor_name_after_rate_info->user_email, $subject, $content );	
			
			remove_filter( 'wp_mail_content_type','set_content_type');
			remove_filter( 'wp_mail_from','wpb_sender_email');
			remove_filter( 'wp_mail_from_name','wpb_sender_name');
	
	
	
	echo true;
	wp_die();
}
/////////////  rate butik  ajax ends ////////////////


function set_content_type( $content_type ) {
	return 'text/html';
}

function wpb_sender_name( $original_email_from ) {
	return 'Stayfab!';
	}
	function wpb_sender_email( $original_email_address ) {
	return get_option('admin_email');
	}


add_action( 'init', 'register_check_product_expired');
function register_check_product_expired() {
    if (! wp_next_scheduled ( 'wq_check_product_expired' )) {
	wp_schedule_event(time(), 'hourly', 'wq_check_product_expired');
    }
}

add_action('wq_check_product_expired', 'do_this_hourly');

function do_this_hourly() 
{
	
	$args = array( 
					'post_type' => 'product', 
					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC',
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
					'name' =>get_the_author(),
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
			if(strtotime($dagstilbud_start_date1) < strtotime($localtime))
			{
				//continue;
				$temp_product_arr[$cntr]['product_id'] = get_the_ID();
				$temp_product_arr[$cntr]['product_title'] = get_the_title();
				$temp_product_arr[$cntr]['date'] = ($product_expiration).'/'.($today);
				$temp_product_arr[$cntr]['email'] = $rate_user->user_email;
				$temp_product_arr[$cntr]['user_name'] = $vendor_name;
				$temp_product_arr[$cntr]['user_id'] = $user_id;
				$temp_product_arr[$cntr]['user_info'] = $u_info;
			}
			
		}
	
	$cntr++;
	endwhile;
	$query = new WP_Query($args);
	
	
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
		
			
	
			
	}
}


function rfm_get_orders_by_product( $product_id ) {

    global $wpdb;

    $raw = "
        SELECT
            `items`.`order_id`,
            MAX(CASE WHEN `itemmeta`.`meta_key` = '_product_id' THEN `itemmeta`.`meta_value` END) AS `product_id`
        FROM
            `{$wpdb->prefix}woocommerce_order_items` AS `items`
        INNER JOIN
            `{$wpdb->prefix}woocommerce_order_itemmeta` AS `itemmeta`
        ON
            `items`.`order_item_id` = `itemmeta`.`order_item_id`
        WHERE
            `items`.`order_item_type` IN('line_item')
        AND
            `itemmeta`.`meta_key` IN('_product_id')
        GROUP BY
            `items`.`order_item_id`
        HAVING
            `product_id` = %d";

    $sql = $wpdb->prepare( $raw, $product_id );

    return array_map(function ($data) {
        return wc_get_order( $data->order_id );
    }, $wpdb->get_results( $sql ) );

}

add_action( 'woocommerce_order_status_cancelled', 'mysite_cancelled');
function mysite_cancelled($order_id) 
{
   // update_option('wq_can',$order_id);
	
		
	$mailer = WC()->mailer();
	
	$mails = $mailer->get_emails();
	
	if ( ! empty( $mails ) ) {
	
		foreach ( $mails as $mail ) {
	
			if ( $mail->id == 'cancelled_order' ) {
						
						$mail->trigger( $order_id );
			}

		 }
	
	}
	
	$_SESSION['send_to_customer'] = 'abc';
	
	if ( ! empty( $mails ) ) {
	
		foreach ( $mails as $mail ) {
	
			if ( $mail->id == 'cancelled_order' ) {
						
					
					add_filter('woocommerce_email_recipient_cancelled_order', 'wq_customer_cancel_email_recipient', 10, 2);
					
					$mail->trigger( $order_id );
					
					remove_filter( 'woocommerce_email_recipient_cancelled_order','wq_customer_cancel_email_recipient');
			}

		 }
	
	}
	
	unset($_SESSION['send_to_customer']);
}


function send_email_after_payment($vendor_id,$order_id,$year,$month){
	$param = array();
	
	$vendor_term_id = $vendor_id;
	$vendor_term_data = get_term_meta($vendor_term_id, 'vendor_data', true);
	$vendor_user_id = $vendor_term_data['admins'];
	
	$user_info = get_userdata($vendor_user_id);
	
	$param['customer_email'] = $user_info->user_email;
	
	
	//$param['customer_email'] = 'jiaur.webqueue@gmail.com';
	
	
	//$param['cc'] = 'binod.webqueue@gmail.com';
	
	if(isset($param['customer_email']) && !empty($param['customer_email'])){
	
	$cc = (isset($param['cc']) && !empty($param['cc'])) ? $param['cc'] : '';
	$to = $param['customer_email'];
	$subject = 'Udbetaling';
	
	
	$order = new WC_Order( $order_id );
	$ord_tot = $order->get_total() - $order->get_total_tax();
	
	$commission = $vendor_term_data['commission'];
	$commission_type = $vendor_term_data['commission_type'];
	if($commission_type =='percentage')
	{
		$commission_val = ($ord_tot*$commission)/100;
		
	}
	else
	{
		$commission_val = $commission;
		
	}
	if(!$commission_val)
	$commission_val = '0.00DKK';
	
	$order_date = $order->order_date;
	
	$wqorderdate = new DateTime($order_date);
	$period_from = $wqorderdate->format('d/m/Y');
	
	$period_option = get_option('wcpv_vendor_settings_payout_schedule');
	
	/*switch ($period_option) 
	{
		case "manual":
			$wqdate = new DateTime($order_date);
			$wqdate->add(new DateInterval('P1M'));
			$wq_period = $wqdate->format('d/m/Y');
			break;
		case "weekly":
			$wqdate = new DateTime($order_date);
			$wqdate->add(new DateInterval('P7D'));
			$wq_period = $wqdate->format('d/m/Y');
			break;
		case "biweekly":
			$wqdate = new DateTime($order_date);
			$wqdate->add(new DateInterval('P7D'));
			$wq_period = $wqdate->format('d/m/Y');
			break;
		case "monthly":
			$wqdate = new DateTime($order_date);
			$wqdate->add(new DateInterval('P1M'));
			$wq_period = $wqdate->format('d/m/Y');
			break;
	}*/
	
	
	$d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
	
	$f_date = "01/".$month."/".$year."";
	$l_date = "".$d."/".$month."/".$year."";
	
	
	
	
	ob_start();
	?>
			   Kære B2B kunde. <br /><br /><br />
		<table>
				<tr>
					<td>Din konto er nu blevet opgjort pr. <?php echo $f_date.' - '.$l_date;?> </td>
				</tr>
				
                <tr>
					<td>Til udbetaling den sidste bankdag i måneden på din din konto: <?php echo number_format($commission_val,2).' DKK';?></td>
				</tr>
				
                <tr>
					<td>For faktura se vedhæftet PDF fil. </td>
				</tr>
				
                <tr>
					<td><em>Denne skal gemmes og bruges i dit regnskab.</em></td>
				</tr>
                <tr>
					<td></td>
				</tr>
                 <tr>
					<td></td>
				</tr>
                <tr>
					<td>Med venlig hilsen</td>
				</tr>
                <tr>
					<td></td>
				</tr>
                <tr>
					<td>Stayfab</td>
				</tr>
            
		</table> 
			   
	<?php
	
	$body = ob_get_clean();
	
	$admin_email  	= get_option('admin_email');
	$blog_title = get_bloginfo( 'name' );
	
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'From: '.$blog_title.' <'.$admin_email.'>';
	
	//$headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
	if(!empty($cc)){
	$headers[] = 'Cc: '.$cc; // note you can just use a simple email address
	}
	
	wp_mail( $to, $subject, $body, $headers );
	
	}
}

 
 
 add_action('transition_post_status', 'wq_deal_assign_func', 10, 3);
 function wq_deal_assign_func($new_status, $old_status, $post) {
 if( 
        $old_status != 'publish' 
        && $new_status == 'publish' 
        && !empty($post->ID) 
        && in_array( $post->post_type, 
            array( 'product') 
            )
        ) {
          
		$vendor_term_id = $_POST['wcpv_product_term'];
		
		$term = get_term( $vendor_term_id, 'wcpv_product_vendors' );
		$name = $term->name;
		
		$users = get_users(array(
			'meta_key'     => 'vendor_name',
			'meta_value'     => $name,
		));
		
		$author_id = $users[0]->ID;
		
		if($_SESSION['deal_authorid_frontend'])
		{
			$arg = array(
						'ID' => $post->ID,
						'post_author' => $_SESSION['deal_authorid_frontend'],
						);
			update_post_meta($post->ID,'created_for_userid',$_SESSION['deal_authorid_frontend']);
			
		}
		else
		{
			
			if(!$_POST['wcpv_product_term'])
			$author_id ='0';
			$arg = array(
						'ID' => $post->ID,
						'post_author' => $author_id,
						);
			update_post_meta($post->ID,'created_for_userid',$author_id);
			update_post_meta($post->ID,'created_by','admin');
		}
		
		
		//wp_update_post( $arg );
		
		
		
		
		unset($_SESSION['deal_authorid_frontend']);
     }

  }
  

  function my_deal_assign_store_save( $post_id ) {

	// If this is just a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) )
		return;
	
		$vendor_term_id = $_POST['wcpv_product_term'];
	
		if($_POST['wcpv_product_term'])
		{
		
			$vendor_term = get_term( $vendor_term_id, 'wcpv_product_vendors' );
			
			$name = $vendor_term->name;
			
			$users = get_users(array(
				'meta_key'     => 'vendor_name',
				'meta_value'     => $name,
			));
			
			$author_id = $users[0]->ID;
			
			update_option('wq_vendor',$author_id);
			
			/*$arg = array(
			'ID' => $post_id,
			'post_author' => $author_id,
			);
			
			wp_update_post( $arg );*/
			
			$post_type = get_post_type($post_id);
			
			if($post_type == 'product')
			update_post_meta($post_id,'created_for_userid',$author_id);
		}
		else
		update_post_meta($post_id,'created_for_userid','0');
}
add_action( 'save_post', 'my_deal_assign_store_save' );
  
 

add_action('admin_footer', 'my_admin_footer_function');
function my_admin_footer_function() {
	?>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
		jQuery( function() {
			jQuery( "#dt_expire_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
			 jQuery('#dt_expire_time').timepicker({timeFormat: 'HH:mm',interval: 60, minTime: '7', maxTime: '9:00pm'});
		} );
	</script>    
    <?php
}



add_action( 'wp_ajax_wq_load_deal_info', 'wq_load_deal_info_callback' );
add_action( 'wp_ajax_nopriv_wq_load_deal_info', 'wq_load_deal_info_callback' );
function wq_load_deal_info_callback(){
	global $wpdb;
	
	$product_id 	= $_POST["product_id"];
	
	$_product = wc_get_product( $product_id );
	
	$regular_price 	= $_product->get_regular_price();
	$sale_price 	= $_product->get_sale_price();
	$normal_price 	= $_product->get_price();
	
	
	
	$my_postid = $product_id;
	$content_post = get_post($my_postid);
	$content = $content_post->post_content;
	//$content = apply_filters('the_content', $content);
	//$description = str_replace(']]>', ']]&gt;', $content);
	
	
	$terms = get_the_terms( $product_id, 'product_cat' );
			$product_cat_id[] =array();
			foreach ($terms as $term) {
				$product_cat_id[] = $term->name;
			}
	unset($product_cat_id[0]);		
			
	$category_count = count($product_cat_id);
	
	echo json_encode(
						array
						(
							'regular_price' => $regular_price,
							'description'   => $content, 
							'category' 		=>$product_cat_id,
							'cat_count'     =>$category_count
						)
					);
	wp_die();
}

