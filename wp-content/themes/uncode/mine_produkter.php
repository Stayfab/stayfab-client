<?php
if(is_user_logged_in())
{
	$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true); 	
	if(isset($_GET['delete_deal']))
	{
		$success_msg = 'Din deal er blevet slettet.';
	}
	if(isset($_GET['active_deactive_deal']))
	{
		$success_msg = 'Deal has been '.$_GET['active_deactive_deal'].'.';
	}
	$wq_user = wp_get_current_user();
	$wq_user_name = $wq_user->user_login;
	$wq_user_email =  $wq_user->user_email ;
	$wq_vendor_name = get_user_meta($wq_user->ID,'vendor_name',true);	
?>
<?php $page = get_query_var('paged')?get_query_var('paged'):1;?>
<?php if($success_msg){
	?>
<div style="color:green" id="delete_deal_msg">
<?php echo $success_msg;?>
</div>
<?php } ?>
<div class="row-inner">
    <div class="pos-top pos-center align_left column_parent col-lg-12 boomapps_vccolumn single-internal-gutter">
        <div class="uncol style-light"><div class="uncoltable">
            <div class="uncell  boomapps_vccolumn no-block-padding">
                <div class="uncont">
                    <div class="vc_wp_custommenu wpb_content_element">
                        <div class="widget widget_nav_menu">
                            <div class="menu-butik-dashboard-menu-container">
                                <ul id="menu-butik-dashboard-menu" class="menu-smart sm menu-horizontal" data-smartmenus-id="15006345128595247">
                                    
                                    <li id="menu-item-11599" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11599">
                                    <a href="http://stayfab.dk/opret-en-deal">Opret et tilbud</a></li>
                                   
                                    <li id="menu-item-11600" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11600">
                                    <a href="http://stayfab.dk/mine-produkter">Mine tilbud</a></li>
                                   
                                    <li id="menu-item-11601" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11601">
                                    <a href="http://stayfab.dk/rediger-profil">Rediger min salon/klinik</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<script id="script-129970" type="text/javascript">UNCODE.initRow(document.getElementById("script-129970"));</script>
</div>
<?php
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
?>
<div id="deal_lists_wq">
<table style="margin-bottom:20px">
  <!--<tr>
    <th>Logo</th>
    <th>Deal</th>
    <th>Rediger</th>
    <th>Slet</th>
    <th>Aktiver/Deaktiver</th>
  </tr>-->
<?php
$args = array( 
					'post_type' => 'product', 
					'posts_per_page' =>999,
					'author' =>get_current_user_id(), 
					'orderby' =>'date',
					'post__not_in' => $hidden_product,
					'post_status' => array('publish','trash'), 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(309),
												'operator' => 'IN',
											)
   										 )
			);		
$time_zone = getTimeZoneFromIpAddress();
$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";
$date = new DateTime('now', new DateTimeZone($timezone));
$localtime = $date->format('d-m-Y h:i:s a');
$localtime1 = $date->format('d-m-Y H:i');
?>
<?php
	$today = $localtime1;
	
	$args11 = array( 
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
			
	$loop = new WP_Query( $args11 );	
	while ( $loop->have_posts() ) : $loop->the_post(); 	
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$dagstilbud_from_date = get_post_meta( get_the_ID(), '_dagstilbud_from_date',true);     
	$dagstilbud_from_time = get_post_meta( get_the_ID(), '_dagstilbud_from_time', true);
	$dagstilbud_to_date = get_post_meta( get_the_ID(), '_dagstilbud_to_date',true);     
	$dagstilbud_to_time = get_post_meta( get_the_ID(), '_dagstilbud_to_time', true);
	
			
	$product_startup_date = get_post_meta(get_the_ID(),'_startup_date',true);
	$product_startup_time =get_post_meta(get_the_ID(),'_startup_time',true);	
	
	if($product_expirary_date)
	{
		$product_date_arr = explode('/',$product_expirary_date);
		$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
		
		
		$product_srartup_date_arr = explode('/',$product_startup_date);
		$product_srartup_date = $product_srartup_date_arr[0].'-'.$product_srartup_date_arr[1].'-'.$product_srartup_date_arr[2].' '.$product_startup_time;
		
		// echo 'man '.get_the_ID().'-->'.$product_expiration.'-->'.$today.'<br>';
		
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
		
		/*if(strtotime($product_srartup_date) < strtotime($today))
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
		*/
		
		
	}
	
	if($dagstilbud_from_date)
	{
		$dagstilbud_from_date_arr = explode('/',$dagstilbud_from_date);
		$dagstilbud_start_date = $dagstilbud_from_date_arr[0].'-'.$dagstilbud_from_date_arr[1].'-'.$dagstilbud_from_date_arr[2].' '.$dagstilbud_from_time;
		
		$dagstilbud_to_date_arr = explode('/',$dagstilbud_to_date);
		$dagstilbud_end_date = $dagstilbud_to_date_arr[0].'-'.$dagstilbud_to_date_arr[1].'-'.$dagstilbud_to_date_arr[2].' '.$dagstilbud_to_time;
		
		
		//echo 'dags '.get_the_ID().'-->'.$dagstilbud_end_date.'-->'.$today.'<br>';
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
?>
<?php
$loop = new WP_Query( $args );	
if($loop->found_posts>0)
echo '<label>Dagstilbud</label>';
while ( $loop->have_posts() ) : $loop->the_post(); 	
	$product_expirary_date = get_post_meta( get_the_ID(), '_expire_date',true);     
	$product_expirary_time = get_post_meta( get_the_ID(), '_expire_time', true);
	
	$today = $localtime;
	
	
	$product_date_arr = explode('/',$product_expirary_date);
	$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
	
	
	/*if($product_expirary_date)
	{
		
		if(strtotime($product_expiration) < strtotime($today))
		{
			continue;
		}
	}*/
	
	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$dagstilbud_from_date = get_post_meta( get_the_ID(), '_dagstilbud_from_date',true);     
	$dagstilbud_from_time = get_post_meta( get_the_ID(), '_dagstilbud_from_time', true);
	$dagstilbud_to_date = get_post_meta( get_the_ID(), '_dagstilbud_to_date',true);     
	$dagstilbud_to_time = get_post_meta( get_the_ID(), '_dagstilbud_to_time', true);
	
			$terms = get_the_terms(get_the_ID(), 'product_cat' );
			$product_cat_id[] =array();
			
			foreach ($terms as $term) {
				$product_cat_id[] = $term->name;
			}
			unset($product_cat_id[0]);
	
	
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
			if($km =='vendor_data')
			$vendor_data = $vm[0];
			
		}
		$vendor_data = unserialize($vendor_data);
		
		$vendor_zip = get_post_meta(get_the_ID(),'wpsl_zip',true);
		if(isset($vendor_data['logo']))
		{
			$logo_post_id = $vendor_data['logo'];
			
			
		
			$feat_image = wp_get_attachment_url( $logo_post_id );
		}
		else
		{
			$feat_image ='';
		}
	}
	
	$vendor_name = $vendor_term[0]->name;		 
	
	$user = get_user_by( 'login', $vendor_name );
	
	$u_rating =get_option('upr_rating_'.$user->ID);
	
	global $wpdb;
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$user->ID." ");
	
	foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	
	if($counter>0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value =0;
	
	
	
	if(is_array($u_rating))
	{
		$vendor_rating = array_sum($u_rating)/count($u_rating);
	}else
	{
		$vendor_rating = 0;
	}
	$vendor_rating = $butik_rate_value;
	$star = '';
	if($vendor_rating<1)
	{
		if($vendor_rating<.5)
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		else
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
	}
	if($vendor_rating>1 && $vendor_rating<2 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
	}
	if($vendor_rating>2 && $vendor_rating<3 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
	}
	if($vendor_rating>3 && $vendor_rating<4 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
	}
	if($vendor_rating>4)
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >'; 
	}
	
if(get_post_status( get_the_ID() ) == 'trash')
$b_label = 'Aktiver';
if(get_post_status( get_the_ID() ) == 'publish')
$b_label = 'Deaktiver';
?>  
  	<tr>
		
        <td style="width:10%"><img src="<?php echo $feat_image;?>"></td>
        <td style="width:40%;font-weight:100">
        <?php echo get_the_title();?>
        <br>
        <div data-zip="<?php echo $vendor_zip;?>"  data-author_id="<?php echo $user->ID;?>" class="get_vendor_all_deal"><?php echo $vendor_name;?></div>
        <?php echo substr($vendor_rating,0,4);?><?php echo $star;?>
        <br>
        <?php echo $product_desc;?>
         <br>
        <?php echo $vendor_address;?>
        &nbsp;&nbsp;&nbsp;&nbsp;<?php if(in_array('Dagstilbud',$product_cat_id))
		echo '<b>'.$dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time.'</b>';
		else
		echo '<b>'.$product_expirary_date.' '.$product_expirary_time.'</b>';?>
        </td>
         <td style="width:25%;font-weight:100"><?php echo 'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>';?></td>
        <td style="width:10%">
        <form action="/opret-en-deal" method="get">
        <input type="text" style="display:none" id="deal_product_id" name="deal_product_id" value="<?php echo get_the_ID();?>">
        <input type="submit" id="wq_Rediger" class="wq_Rediger"  data-product_id="<?php echo get_the_ID();?>" value="Rediger">
        </form></td>
        <td style="width:10%"><input type="button" id="wq_delete" data-product_id="<?php echo get_the_ID();?>" value="Slet"></td>
        <td style="width:10%"><input type="button" id="wq_activate_deactivate" data-status="<?php echo $b_label;?>" data-product_id="<?php echo get_the_ID();?>" value="<?php echo $b_label;?>"></td>
  	</tr>
<?php
unset($product_cat_id);
  endwhile;
   ?>
</table>
<table style="margin-bottom:20px">
  <!--<tr>
    <th>Logo</th>
    <th>Deal</th>
    <th>Rediger</th>
    <th>Slet</th>
    <th>Aktiver/Deaktiver</th>
  </tr>-->
<?php
$args = array( 
					'post_type' => 'product', 
					'posts_per_page' =>999,
					'author' =>get_current_user_id(), 
					'orderby' =>'date',
					'post__not_in' => $hidden_product,
					'post_status' => array('publish','trash'), 
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(308),
												'operator' => 'IN',
											)
   										 )
			);		
$time_zone = getTimeZoneFromIpAddress();
$timezone = $time_zone;//'Europe/London';  //perl: $timeZoneName = "MY TIME ZONE HERE";
$date = new DateTime('now', new DateTimeZone($timezone));
$localtime = $date->format('d-m-Y h:i:s a');
$localtime1 = $date->format('d-m-Y H:i');
?>
<?php
	
				
	$today = $localtime1;
	
    
	
	$args11 = array( 
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
			
	$loop = new WP_Query( $args11 );	
	while ( $loop->have_posts() ) : $loop->the_post(); 	
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$dagstilbud_from_date = get_post_meta( get_the_ID(), '_dagstilbud_from_date',true);     
	$dagstilbud_from_time = get_post_meta( get_the_ID(), '_dagstilbud_from_time', true);
	$dagstilbud_to_date = get_post_meta( get_the_ID(), '_dagstilbud_to_date',true);     
	$dagstilbud_to_time = get_post_meta( get_the_ID(), '_dagstilbud_to_time', true);
	
			
		
	
	if($product_expirary_date)
	{
		$product_date_arr = explode('/',$product_expirary_date);
		$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
		
		// echo 'man '.get_the_ID().'-->'.$product_expiration.'-->'.$today.'<br>';
		
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
		
		
		//echo 'dags '.get_the_ID().'-->'.$dagstilbud_end_date.'-->'.$today.'<br>';
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
?>
<?php
	
$loop = new WP_Query( $args );	
if($loop->found_posts>0)
echo '<label>Månedstilbud</label>';
while ( $loop->have_posts() ) : $loop->the_post(); 	
	$product_expirary_date = get_post_meta( get_the_ID(), '_expire_date',true);     
	$product_expirary_time = get_post_meta( get_the_ID(), '_expire_time', true);
	
	$today = $localtime;
	
	
	$product_date_arr = explode('/',$product_expirary_date);
	$product_expiration = $product_date_arr[0].'-'.$product_date_arr[1].'-'.$product_date_arr[2].' '.$product_expirary_time;
	
	
	/*if($product_expirary_date)
	{
		
		if(strtotime($product_expiration) < strtotime($today))
		{
			continue;
		}
	}*/
	
	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	$product_expirary_date = get_post_meta(get_the_ID(),'_expire_date',true);
	$product_expirary_time =get_post_meta(get_the_ID(),'_expire_time',true);
	
	$dagstilbud_from_date = get_post_meta( get_the_ID(), '_dagstilbud_from_date',true);     
	$dagstilbud_from_time = get_post_meta( get_the_ID(), '_dagstilbud_from_time', true);
	$dagstilbud_to_date = get_post_meta( get_the_ID(), '_dagstilbud_to_date',true);     
	$dagstilbud_to_time = get_post_meta( get_the_ID(), '_dagstilbud_to_time', true);
	
			$terms = get_the_terms(get_the_ID(), 'product_cat' );
			$product_cat_id[] =array();
			
			foreach ($terms as $term) {
				$product_cat_id[] = $term->name;
			}
			unset($product_cat_id[0]);
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>get_the_author(),
			        )
			 );
	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;	
	
	if($vendor_term_meta)
	{
		foreach($vendor_term_meta as $km=>$vm)
		{
			if($km =='address')
			$vendor_address = $vm[0];
			if($km =='vendor_data')
			$vendor_data = $vm[0];
			
		}
		$vendor_data = unserialize($vendor_data);
		
		$vendor_zip = get_post_meta(get_the_ID(),'wpsl_zip',true);
		if(isset($vendor_data['logo']))
		{
			$logo_post_id = $vendor_data['logo'];
			
			
		
			$feat_image = wp_get_attachment_url( $logo_post_id );
		}
		else
		{
			$feat_image ='';
		}
	}
	
	$vendor_name = $vendor_term[0]->name;		 
	
	$user = get_user_by( 'login', $vendor_name );
	
	$u_rating =get_option('upr_rating_'.$user->ID);
	
	//////////////
	
	global $wpdb;
	$rate_results = $wpdb->get_results( "SELECT COUNT(ID) total_rate ,SUM(rate_value) as total_rate_val FROM wp_user_rate where user_id=".$user->ID." ");
	
	foreach($rate_results as $r)
		{
			$counter = $r->total_rate;
			$tot_rate_val = $r->total_rate_val;
		}
	
	if($counter>0)
	$butik_rate_value =number_format($tot_rate_val/$counter,2);
	else
	$butik_rate_value =0;
	/////////////////
	
	if(is_array($u_rating))
	{
		$vendor_rating = array_sum($u_rating)/count($u_rating);
	}else
	{
		$vendor_rating = 0;
	}
	
	$vendor_rating = $butik_rate_value;
	
	$star = '';
	if($vendor_rating<1)
	{
		if($vendor_rating<.5)
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		else
		$star = '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
	}
	if($vendor_rating>1 && $vendor_rating<2 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >';
	}
	if($vendor_rating>2 && $vendor_rating<3 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="3" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
	}
	if($vendor_rating>3 && $vendor_rating<4 )
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >';
		$star .= '<img alt="5" src="http://stayfab.dk/wp-content/themes/uncode/images/star-off.png"  >'; 
	}
	if($vendor_rating>4)
	{
		$star =  '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="2" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="1" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >'; 
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-on.png"  >';
		$star .= '<img alt="4" src="http://stayfab.dk/wp-content/themes/uncode/images/star-half.png"  >'; 
	}
	
if(get_post_status( get_the_ID() ) == 'trash')
$b_label = 'Aktiver';
if(get_post_status( get_the_ID() ) == 'publish')
$b_label = 'Deaktiver';
?>  
  	<tr>
		
        <td style="width:10%"><img src="<?php echo $feat_image;?>"></td>
        <td style="width:40%;font-weight:100">
        <?php echo get_the_title();?>
        <br>
        <div data-zip="<?php echo $vendor_zip;?>"  data-author_id="<?php echo $user->ID;?>" class="get_vendor_all_deal"><?php echo $vendor_name;?></div>
        <?php echo substr($vendor_rating,0,4);?><?php echo $star;?>
        <br>
        <?php echo $product_desc;?>
         <br>
        <?php echo $vendor_address;?>
        &nbsp;&nbsp;&nbsp;&nbsp;<?php if(in_array('Dagstilbud',$product_cat_id))
		echo '<b>'.$dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time.'</b>';
		else
		echo '<b>'.$product_expirary_date.' '.$product_expirary_time.'</b>';?>
        </td>
         <td style="width:25%;font-weight:100"><?php echo 'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>';?></td>
        <td style="width:10%">
        <form action="/opret-en-deal" method="get">
        <input type="text" style="display:none" id="deal_product_id" name="deal_product_id" value="<?php echo get_the_ID();?>">
        <input type="submit" id="wq_Rediger" class="wq_Rediger"  data-product_id="<?php echo get_the_ID();?>" value="Rediger">
        </form></td>
        <td style="width:10%"><input type="button" id="wq_delete" data-product_id="<?php echo get_the_ID();?>" value="Slet"></td>
        <td style="width:10%"><input type="button" id="wq_activate_deactivate" data-status="<?php echo $b_label;?>" data-product_id="<?php echo get_the_ID();?>" value="<?php echo $b_label;?>"></td>
  	</tr>
<?php
unset($product_cat_id);
  endwhile;
   ?>
</table>
</div>
<?php
}
else
{
	echo "Ingen tilbud fundet. Log ind venligst.";	
}
?>