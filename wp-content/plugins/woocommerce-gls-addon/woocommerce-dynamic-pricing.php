<?php

function wga_dynamic_pricing(){
	global $woocommerce;
	return $woocommerce->countries->get_shipping_countries();
	
}

function wga_dynamic_pricing_html($id){
	ob_start();
	?>

<button class="button" id="<?php echo $id; ?>">Add</button>
<?php
	$content = ob_get_contents();
	ob_get_clean();
	return $content;
}

function getCompanyShippingForm($data){
	?>
<div class="woocommerce-company-fields">
  <h3 id="ship-to-different-address">
    <label for="ship-to-different-address-checkbox" class="checkbox">Ship to a different address?</label>
    <input id="ship-to-different-address-checkbox" class="input-checkbox" type="checkbox" name="ship_to_different_address" value="1">
  </h3>
  <div class="company_address">
    <p class="form-row form-row-wide address-field update_totals_on_change validate-required" id="company_country_field">
    
      <?php 
	  $fields = array();
	  
	  $fields['company_country'] =  array(
	  														'type' => 'country' , 
															'options' => wga_dynamic_pricing() , 
															'label' => __('Country' , WGA_TEXTDOMAIN),
															'placeholder' => __('Country' , WGA_TEXTDOMAIN),
															'required' => true,
															 'custom_attributes' => array('id' => 'billing_country_chosen'),
													);
													
	$fields['company_first_name'] =  array(
	  														'type' => 'text' , 
															'label' => __('First Name' , WGA_TEXTDOMAIN) ,
															'placeholder' => __('First Name' , WGA_TEXTDOMAIN) ,
															'required' => true,
															 'class' => array('input-text') 
													);
	
	$fields['company_last_name'] =  array(
	  														'type' => 'text' , 
															'label' => __('Last Name' , WGA_TEXTDOMAIN) ,
															'placeholder' => __('Last Name' , WGA_TEXTDOMAIN) ,
															'required' => true,
															 'class' => array('input-text') 
													);
													
	$fields['company_company'] =  array(
	  														'type' => 'text' , 
															'label' => __('Company Name' , WGA_TEXTDOMAIN) ,
															'placeholder' => __('Company Name' , WGA_TEXTDOMAIN) ,
															'required' => false,
															 'class' => array('input-text') 
													);
													
	$fields['company_address_1'] =  array(
	  														'type' => 'text' , 
															'label' => __('Address' , WGA_TEXTDOMAIN) ,
															'placeholder' => __('Address 1' , WGA_TEXTDOMAIN) ,
															'required' => true,
															 'class' => array('input-text') 
													);
													
	$fields['company_address_2'] =  array(
	  														'type' => 'text' ,
															'required' => false,
															'placeholder' => __('Address 2' , WGA_TEXTDOMAIN) ,
															 'class' => array('input-text') 
													);

	$fields['company_postcode'] =  array(
	  														'type' => 'text' , 
															'label' => __('Postcode' , WGA_TEXTDOMAIN) ,
															'placeholder' => __('Postcode' , WGA_TEXTDOMAIN) ,
															'required' => true,
															 'class' => array('input-text') 
													);									
													
	$fields['company_city'] =  array(
	  														'type' => 'text' , 
															'label' => __('City' , WGA_TEXTDOMAIN) ,
															'placeholder' => __('City' , WGA_TEXTDOMAIN) ,
															'required' => true,
															 'class' => array('input-text') 
													);									
													
	
	foreach($fields as $k => $v)  
	  echo woocommerce_form_field($k , $v , isset($data[$k])?$data[$k]:''); 
	  
	 ?>
    
  </div>
</div>
<div style="clear:both;"></div>
<?php
}

function getCountryNumFromCountryCode($code = ""){
	
	
	$countryNum = array();
	
	$countryNum["AF"] = 4 ; 
$countryNum["AL"] = 8 ; 
$countryNum["DZ"] = 12 ; 
$countryNum["AS"] = 16 ; 
$countryNum["AD"] = 20 ; 
$countryNum["AO"] = 24 ; 
$countryNum["AI"] = 660 ; 
$countryNum["AQ"] = 10 ; 
$countryNum["AG"] = 28 ; 
$countryNum["AR"] = 32 ; 
$countryNum["AM"] = 51 ; 
$countryNum["AW"] = 533 ; 
$countryNum["AU"] = 36 ; 
$countryNum["AT"] = 40 ; 
$countryNum["AZ"] = 31 ; 
$countryNum["BS"] = 44 ; 
$countryNum["BH"] = 48 ; 
$countryNum["BD"] = 50 ; 
$countryNum["BB"] = 52 ; 
$countryNum["BY"] = 112 ; 
$countryNum["BE"] = 56 ; 
$countryNum["BZ"] = 84 ; 
$countryNum["BJ"] = 204 ; 
$countryNum["BM"] = 60 ; 
$countryNum["BT"] = 64 ; 
$countryNum["BO"] = 68 ; 
$countryNum["BA"] = 70 ; 
$countryNum["BW"] = 72 ; 
$countryNum["BV"] = 74 ; 
$countryNum["BR"] = 76 ; 
$countryNum["IO"] = 86 ; 
$countryNum["BN"] = 96 ; 
$countryNum["BG"] = 100 ; 
$countryNum["BF"] = 854 ; 
$countryNum["BI"] = 108 ; 
$countryNum["KH"] = 116 ; 
$countryNum["CM"] = 120 ; 
$countryNum["CA"] = 124 ; 
$countryNum["CV"] = 132 ; 
$countryNum["KY"] = 136 ; 
$countryNum["CF"] = 140 ; 
$countryNum["TD"] = 148 ; 
$countryNum["CL"] = 152 ; 
$countryNum["CN"] = 156 ; 
$countryNum["CX"] = 162 ; 
$countryNum["CC"] = 166 ; 
$countryNum["CO"] = 170 ; 
$countryNum["KM"] = 174 ; 
$countryNum["CG"] = 178 ; 
$countryNum["CD"] = 180 ; 
$countryNum["CK"] = 184 ; 
$countryNum["CR"] = 188 ; 
$countryNum["CI"] = 384 ; 
$countryNum["HR"] = 191 ; 
$countryNum["CU"] = 192 ; 
$countryNum["CY"] = 196 ; 
$countryNum["CZ"] = 203 ; 
$countryNum["DK"] = 208 ; 
$countryNum["DJ"] = 262 ; 
$countryNum["DM"] = 212 ; 
$countryNum["DO"] = 214 ; 
$countryNum["EC"] = 218 ; 
$countryNum["EG"] = 818 ; 
$countryNum["SV"] = 222 ; 
$countryNum["GQ"] = 226 ; 
$countryNum["ER"] = 232 ; 
$countryNum["EE"] = 233 ; 
$countryNum["ET"] = 231 ; 
$countryNum["FK"] = 238 ; 
$countryNum["FO"] = 234 ; 
$countryNum["FJ"] = 242 ; 
$countryNum["FI"] = 246 ; 
$countryNum["FR"] = 250 ; 
$countryNum["GF"] = 254 ; 
$countryNum["PF"] = 258 ; 
$countryNum["TF"] = 260 ; 
$countryNum["GA"] = 266 ; 
$countryNum["GM"] = 270 ; 
$countryNum["GE"] = 268 ; 
$countryNum["DE"] = 276 ; 
$countryNum["GH"] = 288 ; 
$countryNum["GI"] = 292 ; 
$countryNum["GR"] = 300 ; 
$countryNum["GL"] = 304 ; 
$countryNum["GD"] = 308 ; 
$countryNum["GP"] = 312 ; 
$countryNum["GU"] = 316 ; 
$countryNum["GT"] = 320 ; 
$countryNum["GN"] = 324 ; 
$countryNum["GW"] = 624 ; 
$countryNum["GY"] = 328 ; 
$countryNum["HT"] = 332 ; 
$countryNum["HM"] = 334 ; 
$countryNum["VA"] = 336 ; 
$countryNum["HN"] = 340 ; 
$countryNum["HK"] = 344 ; 
$countryNum["HU"] = 348 ; 
$countryNum["IS"] = 352 ; 
$countryNum["IN"] = 356 ; 
$countryNum["ID"] = 360 ; 
$countryNum["IR"] = 364 ; 
$countryNum["IQ"] = 368 ; 
$countryNum["IE"] = 372 ; 
$countryNum["IL"] = 376 ; 
$countryNum["IT"] = 380 ; 
$countryNum["JM"] = 388 ; 
$countryNum["JP"] = 392 ; 
$countryNum["JO"] = 400 ; 
$countryNum["KZ"] = 398 ; 
$countryNum["KE"] = 404 ; 
$countryNum["KI"] = 296 ; 
$countryNum["KP"] = 408 ; 
$countryNum["KR"] = 410 ; 
$countryNum["KW"] = 414 ; 
$countryNum["KG"] = 417 ; 
$countryNum["LA"] = 418 ; 
$countryNum["LV"] = 428 ; 
$countryNum["LB"] = 422 ; 
$countryNum["LS"] = 426 ; 
$countryNum["LR"] = 430 ; 
$countryNum["LY"] = 434 ; 
$countryNum["LI"] = 438 ; 
$countryNum["LT"] = 440 ; 
$countryNum["LU"] = 442 ; 
$countryNum["MO"] = 446 ; 
$countryNum["MK"] = 807 ; 
$countryNum["MG"] = 450 ; 
$countryNum["MW"] = 454 ; 
$countryNum["MY"] = 458 ; 
$countryNum["MV"] = 462 ; 
$countryNum["ML"] = 466 ; 
$countryNum["MT"] = 470 ; 
$countryNum["MH"] = 584 ; 
$countryNum["MQ"] = 474 ; 
$countryNum["MR"] = 478 ; 
$countryNum["MU"] = 480 ; 
$countryNum["YT"] = 175 ; 
$countryNum["MX"] = 484 ; 
$countryNum["FM"] = 583 ; 
$countryNum["MD"] = 498 ; 
$countryNum["MC"] = 492 ; 
$countryNum["MN"] = 496 ; 
$countryNum["ME"] = 499 ; 
$countryNum["MS"] = 500 ; 
$countryNum["MA"] = 504 ; 
$countryNum["MZ"] = 508 ; 
$countryNum["MM"] = 104 ; 
$countryNum["NA"] = 516 ; 
$countryNum["NR"] = 520 ; 
$countryNum["NP"] = 524 ; 
$countryNum["NL"] = 528 ; 
$countryNum["AN"] = 530 ; 
$countryNum["NC"] = 540 ; 
$countryNum["NZ"] = 554 ; 
$countryNum["NI"] = 558 ; 
$countryNum["NE"] = 562 ; 
$countryNum["NG"] = 566 ; 
$countryNum["NU"] = 570 ; 
$countryNum["NF"] = 574 ; 
$countryNum["MP"] = 580 ; 
$countryNum["NO"] = 578 ; 
$countryNum["OM"] = 512 ; 
$countryNum["PK"] = 586 ; 
$countryNum["PW"] = 585 ; 
$countryNum["PS"] = 275 ; 
$countryNum["PA"] = 591 ; 
$countryNum["PG"] = 598 ; 
$countryNum["PY"] = 600 ; 
$countryNum["PE"] = 604 ; 
$countryNum["PH"] = 608 ; 
$countryNum["PN"] = 612 ; 
$countryNum["PL"] = 616 ; 
$countryNum["PT"] = 620 ; 
$countryNum["PR"] = 630 ; 
$countryNum["QA"] = 634 ; 
$countryNum["RE"] = 638 ; 
$countryNum["RO"] = 642 ; 
$countryNum["RU"] = 643 ; 
$countryNum["RW"] = 646 ; 
$countryNum["SH"] = 654 ; 
$countryNum["KN"] = 659 ; 
$countryNum["LC"] = 662 ; 
$countryNum["PM"] = 666 ; 
$countryNum["VC"] = 670 ; 
$countryNum["WS"] = 882 ; 
$countryNum["SM"] = 674 ; 
$countryNum["ST"] = 678 ; 
$countryNum["SA"] = 682 ; 
$countryNum["SN"] = 686 ; 
$countryNum["RS"] = 688 ; 
$countryNum["SC"] = 690 ; 
$countryNum["SL"] = 694 ; 
$countryNum["SG"] = 702 ; 
$countryNum["SK"] = 703 ; 
$countryNum["SI"] = 705 ; 
$countryNum["SB"] = 90 ; 
$countryNum["SO"] = 706 ; 
$countryNum["ZA"] = 710 ; 
$countryNum["GS"] = 239 ; 
$countryNum["ES"] = 724 ; 
$countryNum["LK"] = 144 ; 
$countryNum["SD"] = 736 ; 
$countryNum["SR"] = 740 ; 
$countryNum["SJ"] = 744 ; 
$countryNum["SZ"] = 748 ; 
$countryNum["SE"] = 752 ; 
$countryNum["CH"] = 756 ; 
$countryNum["SY"] = 760 ; 
$countryNum["TW"] = 158 ; 
$countryNum["TJ"] = 762 ; 
$countryNum["TZ"] = 834 ; 
$countryNum["TH"] = 764 ; 
$countryNum["TL"] = 626 ; 
$countryNum["TG"] = 768 ; 
$countryNum["TK"] = 772 ; 
$countryNum["TO"] = 776 ; 
$countryNum["TT"] = 780 ; 
$countryNum["TN"] = 788 ; 
$countryNum["TR"] = 792 ; 
$countryNum["TM"] = 795 ; 
$countryNum["TC"] = 796 ; 
$countryNum["TV"] = 798 ; 
$countryNum["UG"] = 800 ; 
$countryNum["UA"] = 804 ; 
$countryNum["AE"] = 784 ; 
$countryNum["US"] = 840 ; 
$countryNum["UM"] = 581 ; 
$countryNum["UY"] = 858 ; 
$countryNum["UZ"] = 860 ; 
$countryNum["VU"] = 548 ; 
$countryNum["VE"] = 862 ; 
$countryNum["VN"] = 704 ; 
$countryNum["VG"] = 92 ; 
$countryNum["VI"] = 850 ; 
$countryNum["WF"] = 876 ; 
$countryNum["EH"] = 732 ; 
$countryNum["YE"] = 887 ; 
$countryNum["ZM"] = 894 ; 
$countryNum["ZW"] = 716 ; 
$countryNum["AX"] = 248 ; 
$countryNum["GG"] = 831 ; 
$countryNum["IM"] = 833 ; 
$countryNum["JE"] = 832 ; 
$countryNum["BL"] = 652 ; 
$countryNum["MF"] = 663 ; 
$countryNum["GB"] = 826 ; 
$countryNum["CW"] = 531 ; 
$countryNum["SX"] = 534 ; 
$countryNum["BQ"] = 535 ; 

	$code = trim($code);
	return isset($countryNum[$code])?$countryNum[$code]:false;
	
	
}

function getSupportedCountries($country = ""){
	
	$coountires = get_option('woocommerce_wga_shipping_method_settings');
	$countires  = $coountires['shop_selected_countires'];
/*	$coountires = array(
  'AT' => 'Austria',
  'BE' => 'Belgium',
  'HR' => 'Croatia',
  'CZ' => 'Czech Republic',
  'DK' => 'Denmark',
  'FI' => 'Finland',
  'FR' => 'France',
  'DE' => 'Germany',
  'HU' => 'Hungary',
  'IE' => 'Ireland',
  'LU' => 'Luxembourg',
  'NL' => 'Netherlands',
  'PL' => 'Poland',
  'RO' => 'Romania',
  'SK' => 'Slovakia',
  'SI' => 'Slovenia',
  'ES' => 'Spain',
  'GB' => 'United Kingdom');*/

  if(empty($country)) return true;
  
  if(isset($countires[$country])) return true;
  return false;
	
}
