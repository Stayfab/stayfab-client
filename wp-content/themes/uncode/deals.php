<?php
//if(is_user_logged_in())
//{
$hidden_product = get_user_meta(get_current_user_id(), 'product_hidden',true); 
		$selected = 'checked="checked"';
		if($_GET['zip_search'])
		{
			
			global $wpdb;
			$zipcode=$_GET['zip_search'];
			$selected = 'checked="checked"';
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
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(309),
												'operator' => 'IN',
											)
   										 )
				);	
			
			
		}
		
?>

 <div id="div_product_type" style="float:left;text-align: center;margin-left: 40%;">
 <!--<input type="radio" name="product_type" value="99999" checked="checked"><label>Alle</label>-->
 <input type="radio" name="product_type" id="rdotype1" value="309" <?php echo $selected;?>><label>Dagstilbud</label>
 <input type="radio" name="product_type" id="rdotype2" value="308"><label>Månedstilbud</label> 
 </div>
 <div id="msg_adding_deal">
 </div>
<br />
<?php $page = get_query_var('paged')?get_query_var('paged'):1;?>
<div id="deal_lists_wq">
<table style="margin-left: 50px;margin-bottom:20px">
  <?php /*?><tr>
   <th>Logo</th>
    <th>Vendor</th>
    <th>Bestil</th>
  </tr><?php */?>
<?php

if(!$_GET['zip_search'])
{
$args = array( 
					'post_type' => 'product', 
					'meta_key'          => '_final_expire_date',
  					'orderby'           => 'meta_value_num',
 					'order'             => 'ASC',
					'post__not_in' => $hidden_product,
					'tax_query' => array(
											array(
												'taxonomy' => 'product_cat',
												'terms' => array(309),
												'operator' => 'IN',
											)
   										 )
			);		
	
}

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
$today = $localtime;




$loop = new WP_Query( $args );	
$cat_arr=array();
$table_row=array();
while ( $loop->have_posts() ) : $loop->the_post(); 	
	
	$product_expirary_date = get_post_meta( get_the_ID(), '_expire_date',true);     
	$product_expirary_time = get_post_meta( get_the_ID(), '_expire_time', true);
	
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
	//echo $date_compare;

	$product_regular_price=get_post_meta(get_the_ID(),'_regular_price',true);
	$product_sale_price=get_post_meta(get_the_ID(),'_sale_price',true);
	
	$product_desc= get_the_content();
	
	
	
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
	
?> 

<?php

$categories = get_the_terms( get_the_ID(), 'product_cat' );
$cat_id ='';
foreach( $categories as $category ) {
  
	if($category->term_id=='309')
	{
		$cat_id = 309;
	}
	if($category->term_id=='308')
	{
		$cat_id = 308;
	}
}
$cat_arr[] = $cat_id;


ob_start();
?>

 
  	<tr>
       <td style="width:10%;    text-align: center;">
        
       <a href="http://stayfab.dk/vendor/<?php echo $vendor_term[0]->slug;?>"><img src="<?php echo $feat_image;?>"></a>
       </td>
        <td style="font-weight:100">
        <?php echo get_the_title();?>
        <br>
       <a href='' ><div data-zip="<?php echo $vendor_zip;?>"  data-author_id="<?php echo $user->ID;?>" class="get_vendor_all_deal"><?php echo $vendor_name;?></div></a>
		<?php echo substr($vendor_rating,0,4);?><?php echo $star;?>
        <br>
        <?php echo $product_desc;?>
         <br>
        <?php echo $vendor_address;?>
        &nbsp;&nbsp;&nbsp;&nbsp;<?php 
		if(in_array('Dagstilbud',$product_cat_id))
		echo '<b>'.$dagstilbud_from_date.' '.$dagstilbud_from_time.' -- '.$dagstilbud_to_date.' '.$dagstilbud_to_time.'</b>';
		else
		echo '<b>'.$product_expiration.'</b>';
		
		?>
       </td>
        <td style="width:25%;font-weight:100"><?php echo 'Før pris     : '.$product_regular_price.'DKK'.'<br>'.'<span style="color:red">Tilbudspris: '.$product_sale_price.'DKK</span>';?></td>
        <td style="width:15%"><input type="button" id="wq_addtocart" data-product_id="<?php echo get_the_ID();?>" value="KØB"></td>
  	</tr>
<?php unset($product_cat_id);?>
<?php $table_row[] = ob_get_clean(); ?>
<?php  endwhile; ?>

<?php
		arsort($cat_arr);
		foreach($cat_arr as $indx=>$val)
		{
			 echo $table_row[$indx];
			
		}
		if(count($table_row) == 0)
		{
			echo 'No Deals found... ';	
		}
?>
</table>
</div>

<?php
//}
/*else
{
	echo "Log ind venligst for at oprette en deal.";	
}*/
?>
