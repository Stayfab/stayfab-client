<?php

add_action('wp_ajax_wga_get_shops_list', 'wga_get_shops_list_func');
add_action('wp_ajax_nopriv_wga_get_shops_list', 'wga_get_shops_list_func');
function wga_get_shops_list_func(){
	
	$result = wga_get_shops_list();
	echo json_encode($result);
	exit;
		
}

function wga_get_shops_list($zip_code = 0){
	$gls = new wsPakkeshop('UTF-8');
	
	$zip_code = isset($_POST['zipcode'] )?$_POST['zipcode'] :$zip_code;
	$address = isset($_POST['address'])?$_POST['address']:"";
	$limit = isset($_POST['limit'])?$_POST['limit']:5;
	
	$shops = $gls->SearchNearestParcelShops($address, $zip_code , $limit);
	
	
	/**
	 * Skab et array til at returnere resultaterne til AJAX kaldet
	 */
	$results = array();
	if(!empty($shops)){
	foreach($shops as $shop)
	{
		$tmp = array();
		$tmp["Number"] = trim($shop->Number);
		$tmp["CompanyName"] = trim($shop->CompanyName);
		$tmp["Streetname"] = trim($shop->Streetname);
		$tmp["Streetname2"] = trim($shop->Streetname2);
		$tmp["ZipCode"] = trim($shop->ZipCode);
		$tmp["CityName"] = trim($shop->CityName);	
		$tmp["Telephone"] = trim($shop->Telephone);
		$tmp["timing"] = array();
		$from = '';
		$to = '';
		$day_name = 'Monday';
		$j = 0;
		$weekdays = $shop->OpeningHours->Weekday;
		for($i = 0; $i < count($weekdays); $i++){
			$day = $weekdays[$i];
		if($day->openAt->From  != $from && $day->openAt->To != $to){
				$tmp["timing"][$j]['from'] = $day->openAt->From ;
				$tmp['timing'][$j]['to'] = $day->openAt->To ;
				$tmp["timing"][$j]['start'] = $day->day;
				$k = (($j-1) < 0)?0:($j-1);
				$tmp["timing"][$k]['end'] = (!empty($weekdays[$i-1]->day))?$weekdays[$i-1]->day:'Saturday';
				$j++;
			}
				$from = $day->openAt->From ;
				$to = $day->openAt->To;
		}
		$timming_str = '';
		$show_timing = get_option('woocommerce_wga_shipping_method_settings');
		$show_timing = $show_timing['enable_shops_timing'];
		foreach($tmp['timing']  as $t){
			if($show_timing == 'yes'){
			$timming_str .= 	__($t['start'], WGA_TEXTDOMAIN) ." - ". __(@$t['end'], WGA_TEXTDOMAIN) ." (". $t['from']." - ". $t['to'].")<br />";
			} else {	$timming_str = '';}
		}
		$tmp['timing'] = $timming_str;
		//Skub det midlertidige array ind i resultat arrayet.
		$results[] = $tmp;		
	}
	}
	/**
	 * Konverter til JSON format og output det til browseren
	 */
	 
		return $results;
	
	 
}

function wga_shop_by_number_func($number){
	$gls = new wsPakkeshop('UTF-8');
	$shop = $gls->GetOneParcelShop($number);
	
	
		$tmp = array();
		$tmp["Number"] = trim($shop->Number);
		$tmp["CompanyName"] = trim($shop->CompanyName);
		$tmp["Streetname"] = trim($shop->Streetname);
		$tmp["Streetname2"] = trim($shop->Streetname2);
		$tmp["ZipCode"] = trim($shop->ZipCode);
		$tmp["CityName"] = trim($shop->CityName);	
		$tmp['CountryCode'] = $shop->CountryCode;
		$tmp["Telephone"] = trim($shop->Telephone);
		$tmp["CountryCodeISO"] = $shop->CountryCodeISO3166A2;
		$tmp["timing"] = array();
		$from = '';
		$to = '';
		$day_name = 'Monday';
		$j = 0;
		$weekdays = $shop->OpeningHours->Weekday;
		for($i = 0; $i < count($weekdays); $i++){
			$day = $weekdays[$i];
		if($day->openAt->From  != $from && $day->openAt->To != $to){
				$tmp["timing"][$j]['from'] = $day->openAt->From ;
				$tmp['timing'][$j]['to'] = $day->openAt->To ;
				$tmp["timing"][$j]['start'] = $day->day;
				$k = (($j-1) < 0)?0:($j-1);
				$tmp["timing"][$k]['end'] = (!empty($weekdays[$i-1]->day))?$weekdays[$i-1]->day:'Saturday';
				$j++;
			}
				$from = $day->openAt->From ;
				$to = $day->openAt->To;
		}
		
		$week_days_names = __('Monday' , WGA_TEXTDOMAIN);
		$week_days_names = __('Tuesday' , WGA_TEXTDOMAIN);
		$week_days_names = __('Wednesday' , WGA_TEXTDOMAIN);
		$week_days_names = __('Thursday' , WGA_TEXTDOMAIN);
		$week_days_names = __('Friday' , WGA_TEXTDOMAIN);
		$week_days_names = __('Saturday' , WGA_TEXTDOMAIN);
		$week_days_names = __('Sunday' , WGA_TEXTDOMAIN);
		
		
		$timming_str = '';
		foreach($tmp['timing']  as $t){
			$timming_str .= __($t['start'], WGA_TEXTDOMAIN) ." - ". __(@$t['end'], WGA_TEXTDOMAIN) ."(". $t['from']." - ". $t['to'].")<br />";
		}
		$tmp['timing'] = $timming_str;
		//Skub det midlertidige array ind i resultat arrayet.
		$results = $tmp;		
	return (object)$results;
}
?>