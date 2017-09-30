<?php
define('EDD_WGA_STORE_URL', 'http://plugins.w-academy.dk/'); 
define('EDD_WGA_ITEM_NAME', 'GLS integration for woocommerce'); 
if (!class_exists('EDD_SL_Plugin_Updater')) {
    include(dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php');
}

function edd_wga_register_option(){
    register_setting('edd_wga_license', 'edd_wga_license_key', 'edd_sanitize_license');
}
add_action('admin_init', 'edd_wga_register_option');

function edd_sanitize_license($new)
{
    $old = get_option('edd_wga_license_key');
    if ($old && $old != $new) {
        delete_option('edd_wga_license_status'); // new license has been entered, so must reactivate
    }
    return $new;
}

function edd_wga_activate_license(){
    $license    = get_option('woocommerce_wga_shipping_method_settings');
	
    $license    = trim($license['edd_wga_license_key']);
    // data to send in our API request
    $api_params = array(
        'edd_action' => 'activate_license',
        'license' => $license,
        'item_name' => urlencode(EDD_WGA_ITEM_NAME) // the name of our product in EDD
    );
	
    // Call the custom API.
    $response   = wp_remote_get(add_query_arg($api_params, EDD_WGA_STORE_URL), array(
        'timeout' => 15,
        'sslverify' => false
    ));
	
    if (is_wp_error($response))
        return false;
		
    $license_data = json_decode(wp_remote_retrieve_body($response));
	
   		 $license      = get_option('woocommerce_wga_shipping_method_settings');
    	$license['edd_wga_license_status'] = $license_data->license;
    	delete_option('woocommerce_wga_shipping_method_settings');
    	add_option('woocommerce_wga_shipping_method_settings', $license);
	
    echo $license_data->license;
	exit;
}

function edd_wga_deactivate_license(){
    if (isset($_POST['edd_license_deactivate'])) {
		
        if (!check_admin_referer('edd_wga_nonce', 'edd_wga_nonce'))
            return; // get out if we didn't click the Activate button
			
        $license    = get_option('woocommerce_wga_shipping_method_settings');
        $license    = trim($license['edd_wga_license_key']);
		
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $license,
            'item_name' => urlencode(EDD_WGA_ITEM_NAME) // the name of our product in EDD
        );
        
        $response   = wp_remote_get(add_query_arg($api_params, EDD_WGA_STORE_URL), array(
            'timeout' => 15,
            'sslverify' => false
        ));

        if (is_wp_error($response))
            return false;

        $license_data = json_decode(wp_remote_retrieve_body($response));

        if ($license_data->license == 'deactivated')
            delete_option('edd_wga_license_status');
    }
}
add_action('admin_init', 'edd_wga_deactivate_license');

function edd_wga_check_license(){
    global $wp_version;
    $license    = get_option('woocommerce_wga_shipping_method_settings');
    $license    = trim($license['edd_wga_license_key']);
    $api_params = array(
        'edd_action' => 'check_license',
        'license' => $license,
        'item_name' => urlencode(EDD_WGA_ITEM_NAME)
    );
    // Call the custom API.
    $response   = wp_remote_get(add_query_arg($api_params, EDD_WGA_STORE_URL), array(
        'timeout' => 15,
        'sslverify' => false
    ));
    if (is_wp_error($response))
        return false;
    $license_data = json_decode(wp_remote_retrieve_body($response));
    if ($license_data->license == 'valid') {
        echo 'valid';
        exit;
    } else {
        echo 'invalid';
        exit;
    }
}