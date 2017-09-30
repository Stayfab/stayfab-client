<?php
/*
Plugin Name: Woocommerce GLS Addon
Author: Wordpress Academy Team
Author URI: http://w-academy.dk/
Description: An woocommerce extension that enables the user to search nearest GLS shop for shipping.
Version: 1.3.5
Text Domain: woocommerce-gls-addon
Domain Path: /i18n/languages/
*/
if (!defined('ABSPATH')) die();
define('WGA_TEXTDOMAIN', 'woocommerce-gls-addon');
load_plugin_textdomain(WGA_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');
require_once(dirname(__FILE__).'/woocommerce-dynamic-pricing.php');
require_once(dirname(__FILE__).'/ajax/class.wspakkeshop.php');
require_once(dirname(__FILE__)."/ajax/edd-woocommerce-gls-addon.php");
require_once(dirname(__FILE__) . '/ajax/search.php');
require_once(dirname(__FILE__) . '/class.shipping-method.php');
require_once(dirname(__FILE__) . '/class.generate_labels.php');
add_action('admin_notices', 'notices');
function wga_activate()
{
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        //    check_admin_referer( "deactivate-plugin_{$plugin}" );
        deactivate_plugins($plugin);
        unset($_GET['activate']);
    }
}
function notices()
{
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
?>
<div class="updated">
  <p>
    <?php
        _e('Woocommerce GLS Addon Require Woocommerce.', WG_TEXTDOMAIN);
?>
  </p>
</div>
<?php
    }
}
function generteLabelNotice(){
	?>
    <div class="updated">
	  <p>
		<?php
			_e('Error In Generting Labels : ' , WGA_TEXTDOMAIN); echo isset($_GET['error_message'])?$_GET['error_message']:'';
	?>
	  </p>
	</div>
    <?php
}
add_action('init', 'wga_script');
function wga_script()
{
	global $woocommerce;
    wp_register_style('wga-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('wga-style');
	if($woocommerce->version > '2.1.1'){
		wp_register_script('wga-script', plugins_url('js/script-2.1.1.js', __FILE__), 'jquery', '1.0', true);
	} else {
		wp_register_script('wga-script', plugins_url('js/script.js', __FILE__), 'jquery', '1.0', true);
	}
    wp_localize_script('wga-script', 'admin_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_script('wga-script');
	//wp_enqueue_script( 'wc-checkout', plugins_url( 'woocommerce-gls-addon/assets/js/frontend/checkout.js' , dirname(__FILE__) ), array( 'jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n' ), WC_VERSION, true );
}
function get_loader()
{
?>
<div class="field field-meta-box-mesage" id="loading-div">
  <div class="bubblingG"> <span id="bubblingG_1"> </span> <span id="bubblingG_2"> </span> <span id="bubblingG_3"> </span> </div>
</div>
<?php
}
add_action('save_post', 'add_custom_order_details' , 10 , 99);
function add_custom_order_details($post_id)
{
    $p = get_post($post_id);
    if ($p->post_type == 'shop_order') {
        //if (isset($_POST['shipping_method']) && in_array('wga_shipping_method' , $_POST['shipping_method'] ) ) {
			add_post_meta($post_id , '_shipping_type' , $_POST['shipping_type'], true);
			if(isset($_POST['shipping_type']) && $_POST['shipping_type'] == 'shop_to_gls_shop'){
				if (isset($_POST['shop']) && !empty($_POST['shop'])) {
					add_post_meta($post_id, '_shop_name', utf8_encode($_POST['shop']), true);
				} 
			} else if(isset($_POST['shipping_type']) && $_POST['shipping_type'] == 'shop_to_company_address'){
					add_post_meta($post_id , 'company_country', $_POST['company_country'], true);
					add_post_meta($post_id , 'company_first_name', $_POST['company_first_name'] , true);
					add_post_meta($post_id , 'company_last_name', $_POST['company_last_name'], true);
					add_post_meta($post_id , 'company_company', $_POST['company_company'] ,true);
					add_post_meta($post_id , 'company_address_1', $_POST['company_address_1'], true);
					add_post_meta($post_id , 'company_address_2', $_POST['company_address_2'] , true);
					add_post_meta($post_id , 'company_postcode', $_POST['company_postcode'] , true);
					add_post_meta($post_id , 'company_city', $_POST['company_city'], true);
					//update_post_meta($post_id , '_shipping_country', $_POST['company_country']);
					//update_post_meta($post_id , '_shipping_first_name', $_POST['company_first_name'] );
					//update_post_meta($post_id , '_shipping_last_name', $_POST['company_last_name']);
					//update_post_meta($post_id , '_shipping_company', $_POST['company_company'] );
					//update_post_meta($post_id , '_shipping_address_1', $_POST['company_address_1']);
					//update_post_meta($post_id , '_shipping_address_2', $_POST['company_address_2'] );
					//update_post_meta($post_id , '_shipping_postcode', $_POST['company_postcode'] );
					//update_post_meta($post_id , '_shipping_city', $_POST['company_city']);
				}
//				wga_order_shipping_to_display($post_id);
        }
    //}
    return $post_id;
}
add_action('add_meta_boxes', 'wga_meta_box');
function wga_meta_box()
{
    $license = get_option('woocommerce_wga_shipping_method_settings');
	/*$status = $license['edd_wga_license_status'];
    if ($status !== false && $status == 'valid')*/ {
        add_meta_box('wga_meta_box_panel', __('GLS : Nearest Shop Details', WGA_TEXTDOMAIN), 'wga_inner_custom_box', 'shop_order');
    }
}
function wga_inner_custom_box($post)
{
?>
<table>
  <tr>
    <td><?php
    $shop_number = get_post_meta($post->ID, '_shop_name', true);
    if (is_numeric($shop_number)) {
        $r = wga_shop_by_number_func($shop_number);
?>
<div class="row">
      <div class="wcol1"> <strong><?php echo $r->CompanyName;?></strong><br />
        <?php echo $r->Streetname2; ?><br />
        <?php echo $r->Streetname; ?><br />
        <?php echo $r->ZipCode; ?>
        <?php echo $r->CityName ; ?>
      </div>
      <div class="wcol2">
        <?php echo $r->timing ; ?>
      </div>
 </div>
      <?php
    } else {
?>
<div class="row">
      <div class="wcol1"> <strong><?php echo get_post_meta($post->ID , 'company_company', true);?></strong><br />
        <?php echo get_post_meta($post->ID , 'company_address_1', true) ; ?><br />
        <?php echo get_post_meta($post->ID , 'company_address_2', true); ?><br />
        <?php echo get_post_meta($post->ID , 'company_postcode', true); ?>
        <?php echo get_post_meta($post->ID , 'company_city', true) ; ?>
      </div>
 </div>
      <p><?php //_e('No Shop Selected' , WGA_TEXTDOMAIN); ?></p>
      <?php
    }
	
?> 
</td>
  </tr>
</table>
<br />
<a href="<?php echo get_edit_post_link($_GET['post']); //admin_url(); ?>&perform=generateLabels&order_id=<?php echo $_GET['post']; ?>" target="_blank" class="button button-primary button-large"><?php _e('Generate Label', WGA_TEXTDOMAIN);?></a>
<?php
}
add_action('woocommerce_checkout_process', 'wga_check_shop_name', 10, 3);
function wga_check_shop_name()
{
    global $woocommerce;
    if (isset($_POST['shipping_method']) && $_POST['shipping_method'][0] == 'wga_shipping_method') {
		if(isset($_POST['shipping_type']) && $_POST['shipping_type'] == 'shop_to_gls_shop'){
			if (!isset($_POST['shop']) && empty($_POST['shop'])) {
				//$woocommerce->add_error((__('<strong>Shop Name : </strong> Please Select Shop Name', 'woocommerce', WG_TEXTDOMAIN)));
				wc_add_notice((__('<strong>Shop Name : </strong> Please Select Shop Name', 'woocommerce', WG_TEXTDOMAIN)),'error');
			} 
		} else if(isset($_POST['shipping_type']) && $_POST['shipping_type'] == 'shop_to_company_address'){
			$_POST['company_country'] = $_POST['billing_country'];
			$_POST['company_first_name'] = $_POST['billing_first_name'];
			$_POST['company_last_name'] = $_POST['billing_last_name'];
			$_POST['company_company'] = $_POST['billing_company'];
			$_POST['company_address_1'] = $_POST['billing_address_1'];
			$_POST['company_postcode'] = $_POST['billing_postcode'];
			$_POST['company_city'] = $_POST['billing_city'];
				/*if( empty($_POST['company_country']) || empty($_POST['company_first_name']) || empty($_POST['company_last_name']) || empty($_POST['company_company']) || empty($_POST['company_address_1']) || empty($_POST['company_postcode']) || empty($_POST['company_city']) ){
					//$woocommerce->add_error((__('<strong>Comany Details : </strong> Please enter valid company details', 'woocommerce', WG_TEXTDOMAIN)));
					wc_add_notice((__('<strong>Comany Details : </strong> Please enter valid company details', 'woocommerce', WG_TEXTDOMAIN)),'error');
				}*/
			}
    }
}
function wga_plugin_path()
{
    return untrailingslashit(plugin_dir_path(__FILE__));
}
add_filter('woocommerce_locate_template', 'wga_woocommerce_locate_template', 10, 3);
function wga_woocommerce_locate_template($template, $template_name, $template_path)
{
    global $woocommerce;
    $_template = $template;
    if (!$template_path)
        $template_path = $woocommerce->template_url;
    $plugin_path = wga_plugin_path() . '/woocommerce/';
    // Look within passed path within the theme - this is priority
    $template    = locate_template(array(
        $template_path . $template_name,
        $template_name
    ));
    // Modification: Get the template from this plugin, if it exists
    if (!$template && file_exists($plugin_path . $template_name))
        $template = $plugin_path . $template_name;
    // Use default template
    if (!$template)
        $template = $_template;
    // Return what we found
    return $template;
}
function edd_sl_wga_plugin_updater() {
	// retrieve our license key from the DB
	$license = get_option('woocommerce_wga_shipping_method_settings');
	$license_key = $license['edd_wga_license_key'];
	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( EDD_WGA_STORE_URL, __FILE__, array( 
			'version' 	=> '1.3.5', 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => EDD_WGA_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Wordpress Academy Team'  // author of this plugin
		)
	);
}
add_action( 'admin_init', 'edd_sl_wga_plugin_updater' );
add_action('admin_footer', 'wga_footer_script');
function wga_footer_script(){
	if(isset($_GET['tab']) && $_GET['tab'] == 'shipping' && isset($_GET['section']) && $_GET['section'] == 'wc_wga_shipping_method'){
		$license = get_option('woocommerce_wga_shipping_method_settings');
					/*$license = $license['edd_wga_license_status'];*/
			/*		if($license != 'valid'){
	?>
    <script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#mainform").submit(function(){
			return activate_key();
		});
	});
	function activate_key(){
			var activation_key = jQuery("#woocommerce_wga_shipping_method_edd_wga_license_key").val();
			var data = {
				action: 'wga_activate',
				activation_key: activation_key
			};
			jQuery.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				data: data
			}).done(function(response){
				if(response == 'valid') location.reload();
				else jQuery("#error").html("Invalid Activation Key.");
			});
			return false;
	}
	</script>
    <?php
					} else */{
						?>
                        <script type="text/javascript">
							jQuery(document).ready(function(e) {
//								jQuery("#woocommerce_wga_shipping_method_dynamic_prices").removeAttr("name");
                                jQuery("#addCountry").click(function(){
									var country = jQuery("#woocommerce_wga_shipping_method_dynamic_prices").val();
									var e =  document.getElementById("woocommerce_wga_shipping_method_dynamic_prices");
									var countryName = e.options[e.selectedIndex].text;
									var index = jQuery(".dynamiv_pricing_div tr").length ;
									var html = '<tr><td>'+countryName+'</td>';
									html += '<td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country['+country+']['+index+'][starting_postcode]" value="0" />';
									html += '<td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country['+country+']['+index+'][ending_postcode]" value="0" />';
									html += '<td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country['+country+']['+index+'][min_weight]" />';
									html += '<td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country['+country+']['+index+'][max_weight]" />';
									html += '<td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country['+country+']['+index+'][price]" />';
									html += '<td><a href="#" class="removeCountry"><?php _e('Remove' , WGA_TEXTDOMAIN); ?><a></td>';
									html += '</tr>';
									jQuery(".dynamiv_pricing_div").append(html);
									return false;
								});
								jQuery("#addShopCountry").click(function(){
									var country = jQuery("#woocommerce_wga_shipping_method_shop_countires").val();
									var e =  document.getElementById("woocommerce_wga_shipping_method_shop_countires");
									var countryName = e.options[e.selectedIndex].text;
									var index = jQuery(".shop_country_div tr").length ;
									var html = '<tr><td><input type="checkbox" name="woocommerce_wga_shipping_method_shop_selected_countires['+country+']" value="'+country+'" checked="checked" />';
									html += '<td>'+countryName+'</td>';
									html += '<td><a href="#" class="removeCountry"><?php _e('Remove' , WGA_TEXTDOMAIN); ?><a></td>';
									html += '</tr>';
									jQuery(".shop_country_div").append(html);
									return false;
								});
								jQuery("body").on('click' , '.removeCountry', function(){
									jQuery(this).closest("tr").remove();
									return false;
								});
                            });
						</script>
                        <?php 
					}
	}
}
add_action('wp_ajax_wga_activate', 'wga_activate_func');
add_action('wp_ajax_nopriv_wga_activate', 'wga_activate_func');
function wga_activate_func(){
	$activation_key = $_POST['activation_key'];
	$option['edd_wga_license_key'] = $activation_key;
	update_option('woocommerce_wga_shipping_method_settings' , $option );
	edd_wga_activate_license();
}
add_action('init' , 'geneateLabels');
function geneateLabels(){
	if(isset($_GET['action']) && $_GET['perform'] == 'generateLabels' ){
		$order_id = isset($_GET['order_id'])?$_GET['order_id']:0;
		if($order_id != 0){
			global $bulk ;
			$content = $bulk->generatePDFLabelsByOrderId($order_id);
			if(isset($content['pdf'])){
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename='$filename");
				echo $content['pdf'];
				exit;
			} else {
				add_action('admin_notices' , 'generteLabelNotice');
			}
		}
	}
}
 
/* add_filter('woocommerce_calculate_totals' , 'wga_calculate_totals' , 10 , 3);
function wga_calculate_totals($el){
//	get_total();
	global $woocommerce;
	$customer_country = $woocommerce->session->get('customer');
	$customer_country = $customer_country['country'];
	$total_weight =  $woocommerce->cart->cart_contents_weight;
	$postcode = WC()->customer->get_shipping_postcode();
	$post_data = $_POST['post_data'];
	$post_data = getUrlParams($post_data);
	global $woocommerce;
	if(isset($_POST['shipping_method'] , $_POST['shipping_method'][0])){
		$wga_shipping_method = $_POST['shipping_method'][0];
	} else {
		$wga_shipping_method = $woocommerce->session->get_session_data();
		$wga_shipping_method = unserialize($wga_shipping_method['chosen_shipping_methods']);
		$wga_shipping_method = $wga_shipping_method[0];
	}
	
	$wga_options = get_option('woocommerce_wga_shipping_method_settings');
	$ship_to_shop = $wga_options['rate'];
	$company_price = $wga_options['company_rate'];
	if($wga_shipping_method == 'wga_shipping_method' && (float)$wga_options['max_free_shipping_price'] > 0 && ((float)$woocommerce->cart->cart_contents_total >= (float)$wga_options['max_free_shipping_price'])){
		return $el->shipping_total = 0;
	}
	if($wga_shipping_method == 'wga_shipping_method' && $post_data['shipping_type'] == 'shop_to_company_address'){
		$el->shipping_total = $company_price;
		if(isset($wga_options['dynamic_prices_country'][$customer_country]) && is_array($wga_options['dynamic_prices_country'][$customer_country])){
			$country_prices = $wga_options['dynamic_prices_country'][$customer_country];
			foreach($country_prices as $v){
				if($v['starting_postcode'] == 0 && $v['ending_postcode'] == 0){
					if($total_weight >= $v['min_weight'] && $total_weight <= $v['max_weight']){
						$el->shipping_total = $v['price'];
						break;
					}
				} else {
					if($postcode >= $v['starting_postcode'] && $postcode <= $v['ending_postcode']){
						if($total_weight >= $v['min_weight'] && $total_weight <= $v['max_weight']){
							$el->shipping_total = $v['price'];
							break;
						}
					}
				}
		}
	}
		$_GET['el'] = $el;
		add_filter('woocommerce_cart_shipping_method_full_label' , function($label , $method){
			$el = $_GET['el'];
			if($method->id == 'wga_shipping_method'){
				$label = $method->label;
				$label .= ': ' . wc_price($el->shipping_total);
			}
			return $label;
		}, 10 , 99);
		return $el->shipping_total;
	} else if($wga_shipping_method == 'wga_shipping_method' && $post_data['shipping_type'] == 'shop_to_gls_shop') {
		return $el->shipping_total = $ship_to_shop;
	} 
}*/
function getUrlParams($url) {
  $a = explode("&", $url);
    foreach ($a as $key => $value) {
      $b = explode("=", $value);
      $a[$b[0]] = $b[1];
      unset ($a[$key]);
    }
    return $a;
}
remove_all_filters('woocommerce_admin_shipping_fields');
add_filter('woocommerce_admin_shipping_fields' , 'wga_admin_shipping_fields' ); 
function wga_admin_shipping_fields($fields){
	//echo '<pre>';
	//print_r($fields);
	//exit;
	return array('first_name' => array('label' => 'First Name' , 'show' => true));
}
add_action('woocommerce_checkout_order_review' , 'checkoutPageHtml' );
function checkoutPageHtml(){
	if(is_ajax() || 1==1)
	{
	global $woocommerce;
	if(isset($_POST['shipping_method'] , $_POST['shipping_method'][0])){
		$wga_shipping_method = $_POST['shipping_method'][0];
	} else {
		$wga_shipping_method = $woocommerce->session->get_session_data();
		$wga_shipping_method = unserialize($wga_shipping_method['chosen_shipping_methods']);
		$wga_shipping_method = $wga_shipping_method[0];
	}
	
	if(empty($wga_shipping_method)){
		$wga_shipping_method = get_option('woocommerce_default_shipping_method');
	}

	if($wga_shipping_method == "wga_shipping_method"){
		$post_data = $_POST['post_data'];
		$post_data = getUrlParams($post_data);
		if($post_data['ship_to_different_address'] == 1){
			$country = isset($_POST['s_country'])?$_POST['s_country']:"";
		} else {
			$country = isset($_POST['country'])?$_POST['country']:"";
		}
		$shops = getSupportedCountries($country);
		$shipping_type = (isset($post_data['shipping_type']) && $post_data['shipping_type'] == 'shop_to_company_address')?'shop_to_company_address':'shop_to_gls_shop';
		$options = get_option('woocommerce_wga_shipping_method_settings');
				//$shipping_type =  'shop_to_company_address';
		if(isset($post_data['shipping_type']) ){
			if($shops){
				if($post_data['shipping_type'] == 'shop_to_company_address'){
					$shipping_type =  'shop_to_company_address';
				} else {
					$shipping_type =  'shop_to_gls_shop';
				}
			} else {
				$shipping_type =  'shop_to_other';	
			}
		} else {			
			if($shops ){
				$shipping_type =  'shop_to_gls_shop';
			} else {
					$shipping_type =  'shop_to_other';	
			}
		}
	?>
        <div class="add_info_wga" <?php if($wga_shipping_method == 'wga_shipping_method'){ ?> style="display:block;" <?php } else { ?> style="display:none;"  <?php } ?>>
        <?php 
				@extract($options);
				//$options['enable_ship_to_company'] = "no";//hardcoded, replace later to real option - Umid
		?>
        <?php   if($options['enable_ship_to_company'] == "yes"){ ?>
		
		
		<?php //if($shipping_type != 'shop_to_other'){ ?> 
             <ul class="shipping-slector">
             	<?php /* if($shops){ ?>
             	<li><input type="radio" name="shipping_type" value="shop_to_gls_shop" id="shop_to_gls_shop" <?php echo ($shipping_type == 'shop_to_gls_shop')?'checked="checked"':''; ?> /><?php _e('GLS Shop', WGA_TEXTDOMAIN); ?> </li>
                <?php }  */?>
                <?php //if($options['enable_ship_to_company'] == "yes"){ ?>
                <li><input type="radio" name="shipping_type" value="shop_to_company_address" id="shop_to_company_addrees" <?php echo 'checked="checked"'; // echo ($shipping_type == 'shop_to_company_address')?'checked="checked"':''; ?> /><?php _e('Company Address' , WGA_TEXTDOMAIN); ?></li>
                <?php //} ?>
             </ul>
             <?php /* if($shipping_type == 'shop_to_gls_shop'){
				 $postcode = (isset($post_data['wga_shop_postcode']) && !empty($post_data['wga_shop_postcode']))?$post_data['wga_shop_postcode']:$_POST['s_postcode'];
				  ?>
             <div class="gls_tab">
    		<input type="text" name="wga_shop_postcode" value="<?php echo $postcode; ?>" id="wga_shop_postcode" /><button type="button"  id="search-shop" class="button-large button-primary" onclick="return searchShop();" name="search_near_shop"><?php 
			if(!empty($button_title)) _e($button_title , WGA_TEXTDOMAIN); else  _e('Find Nearest Shop', WGA_TEXTDOMAIN);?></button>
            <div class="shops-list">
            <?php $o = (!empty($nearest_shop_limit))?$nearest_shop_limit:5; ?>
					<input type="hidden" id="number_shops" value="<?php echo $o;?>" />
            	<?php echo get_loader(); ?>
            	<ul>
                	<li><?php if(!empty($description)) _e($description ,WGA_TEXTDOMAIN ); else _e('Please Enter The Billing (street , zip) / Shipping (street , zip) Address and press "search button"' , WGA_TEXTDOMAIN );?>.</li>
                </ul>
            </div>
            </div>
            <?php } else if($shipping_type == 'shop_to_company_address'){ */ ?>
            <div class="company_tab">
            	<?php //do_action( 'woocommerce_checkout_shipping' ); 
//				$checkout = WC()->checkout; //new WC_Checkout();
//				$checkout->checkout_form_shipping();
			$post_data = isset($_POST['post_data'])?$_POST['post_data']:'';
			$post_data = getUrlParams($post_data);
echo getCompanyShippingForm($post_data);
				?>
            </div>
            <?php //} 
			/*}  else { //WC()->session->set( 'chosen_shipping_methods', 'free_shipping' ); ?><style>li.wga_shipping_method, #shipping_method_0_wga_shipping_method, label[for="shipping_method_0_wga_shipping_method"]{ display:none !important; } </style><!--<p style="margin-bottom:10px;"><strong><?php _e('Please choose another option below, we do not ship to the selected country with GLS' , WGA_TEXTDOMAIN); ?></strong></p>--><?php 
			$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		if ( isset( $_POST['shipping_method'] ) && is_array( $_POST['shipping_method'] ) ) {
			foreach ( $_POST['shipping_method'] as $i => $value ) {
				$chosen_shipping_methods[ $i ] = wc_clean( $value );
			}
		}
		//WC()->session->set( 'chosen_shipping_methods', array('free_shipping') );
			}  */ ?>
		
		
		<?php } else { ?>
		
		
		<?php if($shipping_type != 'shop_to_other'){ ?>
    		<h3><?php 
			 if(!empty($section_title)) _e($section_title, WGA_TEXTDOMAIN); else _e( 'Shipping Shop Information', WGA_TEXTDOMAIN ) ?></h3>
             <ul class="shipping-slector">
             	<?php if($shops){ ?>
             	<li><input type="radio" name="shipping_type" value="shop_to_gls_shop" id="shop_to_gls_shop" <?php echo ($shipping_type == 'shop_to_gls_shop')?'checked="checked"':''; ?> /><?php _e('GLS Shop', WGA_TEXTDOMAIN); ?> </li>
                <?php } ?>
                <?php //if($options['enable_ship_to_company'] == "yes"){ ?>
                <li><input type="radio" name="shipping_type" value="shop_to_company_address" id="shop_to_company_addrees" <?php echo ($shipping_type == 'shop_to_company_address')?'checked="checked"':''; ?> /><?php _e('Hjemmeadresse / Anden adresse' , WGA_TEXTDOMAIN); ?></li>
                <?php //} ?>
             </ul>
             <?php if($shipping_type == 'shop_to_gls_shop'){
				 $postcode = (isset($post_data['wga_shop_postcode']) && !empty($post_data['wga_shop_postcode']))?$post_data['wga_shop_postcode']:$_POST['s_postcode'];
				  ?>
             <div class="gls_tab">
    		<input type="text" name="wga_shop_postcode" value="<?php echo $postcode; ?>" id="wga_shop_postcode" /><button type="button"  id="search-shop" class="button-large button-primary" onclick="return searchShop();" name="search_near_shop"><?php 
			if(!empty($button_title)) _e($button_title , WGA_TEXTDOMAIN); else  _e('Find Nearest Shop', WGA_TEXTDOMAIN);?></button>
            <div class="shops-list">
            <?php $o = (!empty($nearest_shop_limit))?$nearest_shop_limit:5; ?>
					<input type="hidden" id="number_shops" value="<?php echo $o;?>" />
            	<?php echo get_loader(); ?>
            	<ul>
                	<li><?php if(!empty($description)) _e($description ,WGA_TEXTDOMAIN ); else _e('Please Enter The Billing (street , zip) / Shipping (street , zip) Address and press "search button"' , WGA_TEXTDOMAIN );?>.</li>
                </ul>
            </div>
            </div>
            <?php } else if($shipping_type == 'shop_to_company_address'){ ?>
            <div class="company_tab">
            	<?php //do_action( 'woocommerce_checkout_shipping' ); 
//				$checkout = WC()->checkout; //new WC_Checkout();
//				$checkout->checkout_form_shipping();
			$post_data = isset($_POST['post_data'])?$_POST['post_data']:'';
			$post_data = getUrlParams($post_data);
echo getCompanyShippingForm($post_data);
				?>
            </div>
            <?php } 
			} else { //WC()->session->set( 'chosen_shipping_methods', 'free_shipping' ); ?><style>li.wga_shipping_method, #shipping_method_0_wga_shipping_method, label[for="shipping_method_0_wga_shipping_method"]{ display:none !important; } </style><!--<p style="margin-bottom:10px;"><strong><?php _e('Please choose another option below, we do not ship to the selected country with GLS' , WGA_TEXTDOMAIN); ?></strong></p>--><?php 
			$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		if ( isset( $_POST['shipping_method'] ) && is_array( $_POST['shipping_method'] ) ) {
			foreach ( $_POST['shipping_method'] as $i => $value ) {
				$chosen_shipping_methods[ $i ] = wc_clean( $value );
			}
		}
		//WC()->session->set( 'chosen_shipping_methods', array('free_shipping') );
			}  ?>
		 
		<?php } ?> 
    </div>
	<?php 	} 
	}
}
register_uninstall_hook(__FILE__ , 'wga_uninstall');
function wga_uninstall(){
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();
	$option_name = 'woocommerce_wga_shipping_method_settings';
	$value = get_option($option_name);
	if(isset($value['remove_data']) && $value['remove_data'] == 'yes'){
		if ( !is_multisite() ) 
		{
			delete_option( $option_name );
		} 
		// For Multisite
		else 
		{
			// For regular options.
			global $wpdb;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();
			foreach ( $blog_ids as $blog_id ) 
			{
				switch_to_blog( $blog_id );
				delete_option( $option_name );  
			}
			switch_to_blog( $original_blog_id );
			// For site options.
			delete_site_option( $option_name );  
		}
	}
} 
function wga_order_shipping_to_display($order_id , $price){
	if($order_id > 0){
		$order = new WC_Order( $order_id );
		$shipping_type = get_post_meta($order_id  , '_shipping_type' , true);
		if($shipping_type == 'shop_to_company_address' || $shipping_type == 'shop_to_gls_shop'){
			global $woocommerce;		
	$wga_options = get_option('woocommerce_wga_shipping_method_settings');
	$ship_to_shop = $wga_options['rate'];
	$company_price = $wga_options['company_rate'];
	$customer_country = get_post_meta($order_id , 'company_country' , true);
	$postcode = get_post_meta($order_id , 'company_postcode', true);
	$total_weight = $woocommerce->cart->cart_contents_weight;
	if((float)$wga_options['max_free_shipping_price'] > 0 && ((float)$woocommerce->cart->cart_contents_total >= (float)$wga_options['max_free_shipping_price'])){
		$price = 0;
	} else if($shipping_type == 'shop_to_company_address'){
		$price = $company_price;
		if(isset($wga_options['dynamic_prices_country'][$customer_country]) && is_array($wga_options['dynamic_prices_country'][$customer_country])){
			$country_prices = $wga_options['dynamic_prices_country'][$customer_country];
			foreach($country_prices as $v){
				if($v['starting_postcode'] == 0 && $v['ending_postcode'] == 0){
					if($total_weight >= $v['min_weight'] && $total_weight <= $v['max_weight']){
						$price = $v['price'];
						break;
					}
				} else {
					if($postcode >= $v['starting_postcode'] && $postcode <= $v['ending_postcode']){
						if($total_weight >= $v['min_weight'] && $total_weight <= $v['max_weight']){
							$price = $v['price'];
							break;
						}
					}
				}
		}
	}
	} else if($shipping_type == 'shop_to_gls_shop') {
		$price = $ship_to_shop;
	}
		}
	}
	return $price;
}
add_action('woocommerce_new_order' , function($order_id){
	WC()->cart->total = (double)WC()->cart->total - (double)WC()->cart->shipping_total;
	WC()->cart->shipping_total = wga_order_shipping_to_display($order_id , WC()->cart->shipping_total);
	WC()->cart->total = (double)WC()->cart->total + (double)WC()->cart->shipping_total;
	return $order_id;
}, 10 , 99);


add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 10, 2); 
function hide_shipping_when_free_is_available( $rates, $package = '' ) {
	
	
	$post_data = $_POST['post_data'];
	$post_data = getUrlParams($post_data);
		if($post_data['ship_to_different_address'] == 1){
			$country = isset($_POST['s_country'])?$_POST['s_country']:"";
		} else {
			$country = isset($_POST['country'])?$_POST['country']:"";
		}
		$shops = getSupportedCountries($country);
		$shipping_type = (isset($post_data['shipping_type']) && $post_data['shipping_type'] == 'shop_to_company_address')?'shop_to_company_address':'shop_to_gls_shop';
		$options = get_option('woocommerce_wga_shipping_method_settings');
		if(isset($post_data['shipping_type']) ){
			if($shops)
				if($post_data['shipping_type'] == 'shop_to_company_address')
					$shipping_type =  'shop_to_company_address';
				else
					$shipping_type =  'shop_to_gls_shop';
			else 
						$shipping_type =  'shop_to_other';	
		} else {			
			if($shops )
				$shipping_type =  'shop_to_gls_shop';
			else
					$shipping_type =  'shop_to_other';	
		}
		
  	if (  $shipping_type == 'shop_to_other'  ) {
  		unset( $rates['wga_shipping_method'] );
		//WC()->session->set( 'chosen_shipping_methods', key($rates) ); 
	}
	return $rates;
} 