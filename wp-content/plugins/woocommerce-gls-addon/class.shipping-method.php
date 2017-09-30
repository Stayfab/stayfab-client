<?php
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    function wga_shipping_method_init() {
        if (!class_exists('WC_Wga_Shipping_Method')) {
            class WC_Wga_Shipping_Method extends WC_Shipping_Method {
                public function __construct() {
                    $this->id                      = 'wga_shipping_method';
                    $this->method_title            = __('GLS Shipping Method', WGA_TEXTDOMAIN);
                    $this->method_description      = __('Description of GLS shipping method', WGA_TEXTDOMAIN);
                    $this->enabled                 = "yes";
                    $this->remove_data             = "yes";
                    $this->enable_ship_to_company  = "yes";
                    $this->enable_shops_timing     = "yes";
                    $this->title                   = __("GLS Shop Shipping Method", WGA_TEXTDOMAIN);
                    $this->section_title           = '';
                    $this->button_title            = __('Find Nearest Shops', WGA__TEXTDOMAIN);
                    $this->description             = __('Please Enter The Billing (street , zip) / Shipping (street , zip) Address and press "search button"', WGA_TEXTDOMAIN);
                    $this->nearest_shop_limit      = 5;
                    $this->max_free_shipping_price = 0;
                    
                    $this->gls_shop_heading     = '';
                    $this->gls_shop_user_name   = '';
                    $this->gls_shop_password    = '';
                    $this->gls_shop_customer_id = '';
                    $this->gls_shop_contact_id  = '';
                    
                    $this->gls_shop_email_heading = "";
                    $this->gls_shop_email_title   = __('Order Tracking Code', WGA__TEXTDOMAIN);
                    $this->gls_shop_email_body    = '';
                    
                   // $this->  = __('invalid', WGA__TEXTDOMAIN);
                   // $this->edd_wga_license_key     = __('', WGA__TEXTDOMAIN);
                    $this->dynamic_prices_country  = array();
                    $this->shop_countires          = array();
                    $this->shop_selected_countires = array();
                    
                    $this->init();
                }
                function init() {
                    $this->init_form_fields();
                    $this->init_settings();
                    $this->enabled     = $this->settings['enabled'];
                    $this->remove_data = $this->settings['remove_data'];
                    
                    $this->title                   = $this->settings['title'];
                    $this->rate                    = $this->settings['rate'];
                    $this->enable_ship_to_company  = $this->settings['enable_ship_to_company'];
                    $this->company_rate            = $this->settings['company_rate'];
                    $this->enable_shops_timing     = $this->settings['enable_shops_timing'];
                    $this->section_title           = $this->settings['section_title'];
                    $this->button_title            = $this->settings['button_title'];
                    $this->description             = $this->settings['description'];
                    $this->nearest_shop_limit      = $this->settings['nearest_shop_limit'];
                    $this->max_free_shipping_price = $this->settings['max_free_shipping_price'];
                    
                    $this->gls_shop_user_name   = $this->settings['gls_shop_user_name'];
                    $this->gls_shop_password    = $this->settings['gls_shop_password'];
                    $this->gls_shop_customer_id = $this->settings['gls_shop_customer_id'];
                    $this->gls_shop_contact_id  = $this->settings['gls_shop_contact_id'];
                    
                    $this->gls_shop_email_title = $this->settings['gls_shop_email_title'];
                    $this->gls_shop_email_body  = $this->settings['gls_shop_email_body'];
                    
                  //  $this->edd_wga_license_status = $this->settings['edd_wga_license_status'];
                 //   $this->edd_wga_license_key    = $this->settings['edd_wga_license_key'];
                    $this->dynamic_prices_country = $thi->settings['dynamic_prices_country'];
                    
                    $this->shop_countires          = $thi->settings['shop_countires'];
                    $this->shop_selected_countires = $this->settings['shop_selected_countires'];
                    
                    add_action('woocommerce_update_options_shipping_' . $this->id, array(
                        $this,
                        'process_admin_options'
                    ));
                }
                function init_form_fields() {
                    global $woocommerce;
                 /*   if ($this->is_active())*/ {
                        
                        $this->form_fields = array(
                            'enabled' => array(
                                'title' => __('Enable', WGA_TEXTDOMAIN) . '/' . __('Disable', WGA_TEXTDOMAIN),
                                'type' => 'checkbox',
                                'label' => __('Enable', WGA_TEXTDOMAIN),
                                'default' => 'yes'
                            ),
                            'title' => array(
                                'title' => __('Method Title', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Title', WGA_TEXTDOMAIN),
                                'default' => __('GLS Shop', WGA_TEXTDOMAIN)
                            ),
                            'rate' => array(
                                'title' => __('Shipping Rate', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Rate for shipping', WGA_TEXTDOMAIN),
                                'default' => __('40.00', WGA_TEXTDOMAIN)
                            ),
                            'enable_ship_to_company' => array(
                                'title' => __('Enable Shipping To Company Address', WGA_TEXTDOMAIN),
                                'type' => 'checkbox',
                                'label' => __('Enable', WGA_TEXTDOMAIN),
                                'default' => 'yes'
                            ),
                            'enable_shops_timing' => array(
                                'title' => __('Show Shops Opening Hours', WGA_TEXTDOMAIN),
                                'type' => 'checkbox',
                                'label' => __('Enable', WGA_TEXTDOMAIN),
                                'default' => 'yes'
                            ),
                            'company_rate' => array(
                                'title' => __('Shipping To Company Rate', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Rate of shipping for company', WGA_TEXTDOMAIN),
                                'default' => __('50.00', WGA_TEXTDOMAIN)
                            ),
                            'max_free_shipping_price' => array(
                                'title' => __('Minimum order amount to get free shipping', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Minimum order amount to get free shipping', WGA_TEXTDOMAIN),
                                'default' => 0
                            ),
                            'section_title' => array(
                                'title' => __('Search GLS Shop', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Section Title', WGA_TEXTDOMAIN),
                                'default' => __('Search GLS Shop', WGA_TEXTDOMAIN)
                            ),
                            'button_title' => array(
                                'title' => __('Search Nearest Shop Button Text', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Button Text', WGA_TEXTDOMAIN),
                                'default' => __('Find Nearnest Shop', WGA_TEXTDOMAIN)
                            ),
                            'description' => array(
                                'title' => __('Description', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Description below the button', WGA_TEXTDOMAIN),
                                'default' => __('Please Enter The Billing (street , zip) / Shipping (street , zip) Address and press "search button"', WGA_TEXTDOMAIN)
                            ),
                            'nearest_shop_limit' => array(
                                'title' => __('Search Number of Shop Limits', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('Search Number of shop limits', WGA_TEXTDOMAIN),
                                'default' => 5
                            ),
                            'gls_shop_heading' => array(
                                'title' => __('Info for generate labels' , WGA_TEXTDOMAIN),
                                'type' => 'section_heading',
                                'description' => __('(this does not work in all contries, as the GLS web API are still new.) Please contact your local GLS sales team to get this info if nedded.  <a href="https://gls-group.eu/" target="_blank">https://gls-group.eu/</a>', WGA_TEXTDOMAIN)
                            ),
                            'gls_shop_user_name' => array(
                                'title' => __('Gls Shop User Name', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('', WGA_TEXTDOMAIN)
                            ),
                            'gls_shop_password' => array(
                                'title' => __('Gls Shop Password', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('', WGA_TEXTDOMAIN)
                            ),
                            'gls_shop_customer_id' => array(
                                'title' => __('Gls Shop Customer ID', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('', WGA_TEXTDOMAIN)
                            ),
                            'gls_shop_contact_id' => array(
                                'title' => __('Gls Shop Contact ID', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('', WGA_TEXTDOMAIN)
                            ),
                            
                            'gls_shop_email_heading' => array(
                                'title' => __('Email Template', WGA_TEXTDOMAIN),
                                'type' => 'section_heading',
                                'description' => __('When you generate a label, an email are sent to the customer, you can edit the content of the email below.', WGA_TEXTDOMAIN)
                            ),
                            'gls_shop_email_title' => array(
                                'title' => __('Email Subject', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('', WGA_TEXTDOMAIN),
                                'default' => __('Order Tracking Code', WGA_TEXTDOMAIN)
                            ),
                            'gls_shop_email_body' => array(
                                'title' => __('Email Body', WGA_TEXTDOMAIN),
                                'type' => 'textarea',
                                'description' => __('Use ', WGA_TEXTDOMAIN) . '[name],[link]' . __(' to insert Customer Name and Tracking Link', WGA_TEXTDOMAIN),
                                'default' => '<p>
Hi [name]<br />
Your order are packed and ready for GLS. You can follow your pack in a cuple of hours here
<br />
[link]
</p>'
                            ),
							'gls_dynamic_pricing_heading' => array(
                                'title' => __('Dynamic Pricing', WGA_TEXTDOMAIN),
                                'type' => 'section_heading',
                                'description' => ''
                            ),
                            'dynamic_prices' => array(
                                'title' => __('Dynamic Pricing', WGA_TEXTDOMAIN),
                                'type' => 'select',
                                'options' => wga_dynamic_pricing(),
                                'description' => wga_dynamic_pricing_html('addCountry')
                            ),
                            'dynamic_prices_country' => array(
                                'title' => __('', WGA_TEXTDOMAIN),
                                'type' => 'multibox',
                                'description' => ''
                            ),
							'sho_counties_heading' => array(
                                'title' => __('Shop Countries', WGA_TEXTDOMAIN),
                                'type' => 'section_heading',
                                'description' => __('Here you can add the countries that your gls option will work in. If a customer choose a country at checkout that are not added here, the gls option will disappear, and they will have to use another shipping option.', WGA_TEXTDOMAIN)
                            ),
                            'shop_countires' => array(
                                'title' => __('Shop Countires', WGA_TEXTDOMAIN),
                                'type' => 'select',
                                'options' => wga_dynamic_pricing(),
                                'description' => wga_dynamic_pricing_html('addShopCountry')
                            ),
                            'shop_selected_countires' => array(
                                'title' => __('', WGA_TEXTDOMAIN),
                                'type' => 'shop_country',
                                'description' => ''
                            ),
							'edd_wga_license_key' => array(
                                'title' => __('', WGA_TEXTDOMAIN),
                                'type' => 'hidden',
                                'description' => '',
                                'default' => ''
                            ),
                           // 'edd_wga_license_status' => array(
                             //   'title' => __('', WGA_TEXTDOMAIN),
                               // 'type' => 'hidden',
                               // 'description' => __('', WGA_TEXTDOMAIN),
                               // 'default' => __('invalid', WGA_TEXTDOMAIN)
                          //  ),
                            'remove_data' => array(
                                'title' => __('Remove Data On Uninstall', WGA_TEXTDOMAIN),
                                'type' => 'wga_checkbox',
                                'label' => __('Check this if you want to remove all its data on uninstall', WGA_TEXTDOMAIN),
                                'default' => 'yes',
                            )
                        );
                        
                        
                        
                 /*   } else {
                        
                        $license     = get_option('woocommerce_wga_shipping_method_settings');
                        $license_key = $license['edd_wga_license_key'];
                        
                        $button            = (!empty($license_key)) ? '<br /><br /><strong><span id="error"></span></strong>' : '<br /><br /><span id="error"></span>';
                        $this->form_fields = array(
                            'edd_wga_license_key' => array(
                                'title' => __('Activation Key', WGA_TEXTDOMAIN),
                                'type' => 'text',
                                'description' => __('(To activate enter the license key and press activate)' . $button, WGA_TEXTDOMAIN),
                                'default' => __('', WGA_TEXTDOMAIN)
                            ),
                            'edd_wga_license_status' => array(
                                'title' => __('', WGA_TEXTDOMAIN),
                                'type' => 'hidden',
                                'description' => __('', WGA_TEXTDOMAIN),
                                'default' => __('invalid', WGA_TEXTDOMAIN)
                            )
                            
                            
                        );*/
                    }
                }
                
                public function validate_multibox_field($key) {
                    $value = $this->get_option($key);
                    
                    if (isset($_POST[$this->plugin_id . $this->id . '_' . $key])) {
                        foreach ($_POST[$this->plugin_id . $this->id . '_' . $key] as $code => $values) {
                            foreach ($values as $keys => $v) {
                                foreach ($v as $array_key => $valid) {
                                    $_POST[$this->plugin_id . $this->id . '_' . $key][$code][$keys][$array_key] = floatval($valid);
                                    
                                }
                            }
                        }
                        $value = $_POST[$this->plugin_id . $this->id . '_' . $key];
                    } else {
                        $value = '';
                    }
                    
                    return $value;
                }
                
                public function validate_shop_country_field($key) {
                    
                    $value = $this->get_option($key);
                    
                    if (isset($_POST[$this->plugin_id . $this->id . '_' . $key])) {
                        
                        $value = $_POST[$this->plugin_id . $this->id . '_' . $key];
						
                    } else {
						
                        $value = '';
						
                    }
                    
                    return $value;
                }
				
				public function validate_wga_checkbox_field($key) {
					$status = 'no';
					if ( isset( $_POST[ $this->plugin_id . $this->id . '_' . $key ] ) && ( 1 == $_POST[ $this->plugin_id . $this->id . '_' . $key ] ) ) {
						$status = 'yes';
					}
			
					return $status;
				}
                
                //function is_active() {
                    
                  //  $license = get_option('woocommerce_wga_shipping_method_settings');
                   // $license = $license['edd_wga_license_status'];
                    //if ($license != 'valid')
                     //   return true;
                    //else
                      //  return false;
                    
              //  }
                
                function is_available($package) {
                    global $woocommerce;
                    
                    if ($this->enabled == "no") {
                        return false;
                    }
                    return apply_filters('woocommerce_shipping_' . $this->id . '_is_available', true);
                }
                
                public function calculate_shipping($package) {
                    
                    
                    $rate = array();
                    global $woocommerce;
                    $wga_options = get_option('woocommerce_wga_shipping_method_settings');
                    
                    if ((float) $wga_options['max_free_shipping_price'] > 0 && ((float) $woocommerce->cart->cart_contents_total >= (float) $wga_options['max_free_shipping_price'])) {
                        
                        $rate = array(
                            'id' => $this->id,
                            'label' => __($this->title, WGA_TEXTDOMAIN),
                            'cost' => '0.00',
                            'calc_tax' => 'per_order'
                        );
                        
                    } else {
                        $rate = array(
                            'id' => $this->id,
                            'label' => __($this->title, WGA_TEXTDOMAIN),
                            'cost' => $this->rate, 
                            'calc_tax' => 'per_order',
                        );
                    }
                    // Register the rate
                    $this->add_rate($rate);
                    
                }
                
                public function generate_multibox_html($k, $v) {
                    $options = array();
                    $options = get_option('woocommerce_wga_shipping_method_settings');
                    $options = $options[$k];
                    $country = wga_dynamic_pricing();
                    
                    
                    ob_start();
					?>
					
					<tr valign="top">
					<th class="forminp" colspan="2" style="font-weight:normal"><table class="dynamiv_pricing_div">
						<tr>
						  <th><?php
										_e('Country', WGA_TEXTDOMAIN);
					?></th>
						  <th><?php
										_e('Starting Postcode', WGA_TEXTDOMAIN);
					?></th>
						  <th><?php
										_e('Ending Postcode', WGA_TEXTDOMAIN);
					?></th>
						  <th><?php
										_e('Minmum Weight', WGA_TEXTDOMAIN);
										echo '	(';
										_e('Only Enter Numeric Value', WGA_TEXTDOMAIN);
										echo ') ';
					?>
						  </th>
						  <th><?php
										_e('Maximum Weight', WGA_TEXTDOMAIN);
										echo ' (';
										_e('Only Enter Numeric Value', WGA_TEXTDOMAIN);
										echo ') ';
					?></th>
						  <th><?php
										_e('Price', WGA_TEXTDOMAIN);
										echo ' (';
										_e('Only Enter Numeric Values', WGA_TEXTDOMAIN);
										echo ') ';
					?></th>
						  <th></th>
						</tr>
						
						<?php
										$count = 1;
										if (is_array($options)) {
											foreach ($options as $key => $v) {
												foreach ($v as $a) {
					?>
						<tr>
						  <td><?php
													echo $country[$key];
					?></td>
						  <td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country[<?php
													echo $key;
					?>][<?php
													echo $count;
					?>][starting_postcode]" value="<?php
													echo $a['starting_postcode'];
					?>" /></td>
						  <td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country[<?php
													echo $key;
					?>][<?php
													echo $count;
					?>][ending_postcode]" value="<?php
													echo $a['ending_postcode'];
					?>" /></td>
						  <td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country[<?php
													echo $key;
					?>][<?php
													echo $count;
					?>][min_weight]" value="<?php
													echo $a['min_weight'];
					?>" /></td>
						  <td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country[<?php
													echo $key;
					?>][<?php
													echo $count;
					?>][max_weight]" value="<?php
													echo $a['max_weight'];
					?>" /></td>
						  <td><input type="text" name="woocommerce_wga_shipping_method_dynamic_prices_country[<?php
													echo $key;
					?>][<?php
													echo $count;
					?>][price]" value="<?php
													echo $a['price'];
					?>" /></td>
						  <td><a href="#" class="removeCountry">
							<?php
													_e('Remove', WGA_TEXTDOMAIN);
					?>
							</a></td>
						</tr>
						<?php
													$count++;
												}
											}
										}
					?>
					  </table></th></tr>
					<?php
                    $contents = ob_get_contents();
                    ob_get_clean();
                    return $contents;
                }
                
                public function generate_shop_country_html($k, $v) {
                    $options = array();
                    $options = get_option('woocommerce_wga_shipping_method_settings');
                    $options = $options[$k];
                    $country = wga_dynamic_pricing();
                    
                    
                    ob_start();
					?>
					<tr valign="top">
					<th class="forminp" colspan="2" style="font-weight:normal"><table class="shop_country_div">
						<tr>
						  <td style="width:0px;">&nbsp;</th>
						  <td><?php
										_e('Country', WGA_TEXTDOMAIN);
					?></td>
						  <td></td>
						</tr>
						
						<?php
										$count = 1;
										if (is_array($options)) {
											foreach ($options as $key => $v) {
					?>
						<tr>
						  <td style="width:0px;"><input type="checkbox" name="woocommerce_wga_shipping_method_shop_selected_countires[<?php
												echo $key;
					?>]" value="<?php
												echo $key;
					?>" checked="checked" /></td>
						  <td><?php
												echo $country[$key];
					?></td>
						  <td><a href="#" class="removeCountry">
							<?php
												_e('Remove', WGA_TEXTDOMAIN);
					?>
							</a></td>
						</tr>
						<?php
												$count++;
											}
										}
					?>
					  </table></th></tr>
					<?php
                    $contents = ob_get_contents();
                    ob_get_clean();
                    return $contents;
                }
                
				public function generate_section_heading_html($k, $v) {
                    ob_start();
					?>
					
					<tr valign="top" class="section_heading" style="border-bottom:1px solid #000; border-top:1px solid #000;">
					<th class="forminp" colspan="2" style="font-weight:normal;">
                    	<?php echo '<h3>'. $this->form_fields[$k]['title'].'</h3>'; ?>
                    	<?php echo $this->form_fields[$k]['description']; ?>
                    </th></tr>
					<?php
                    $contents = ob_get_contents();
                    ob_get_clean();
                    return $contents;
                }
				
				public function generate_wga_checkbox_html( $key, $data ) {
    	$field    = $this->plugin_id . $this->id . '_' . $key;
    	$defaults = array(
			'title'             => '',
			'label'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array()
		);

		$data = wp_parse_args( $data, $defaults );

		if ( ! $data['label'] )
			$data['label'] = $data['title'];

		ob_start();
		?>
		<tr valign="top" style="border-top:1px solid #000; border-bottom:1px solid #000;">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<label for="<?php echo esc_attr( $field ); ?>">
					<input <?php disabled( $data['disabled'], true ); ?> class="<?php echo esc_attr( $data['class'] ); ?>" type="checkbox" name="<?php echo esc_attr( $field ); ?>" id="<?php echo esc_attr( $field ); ?>" style="<?php echo esc_attr( $data['css'] ); ?>" value="1" <?php checked( $this->get_option( $key ), 'yes' ); ?> <?php echo $this->get_custom_attribute_html( $data ); ?> /> <?php echo wp_kses_post( $data['label'] ); ?></label><br/>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
    }
				
                public function validate_settings_fields($form_fields = false) {
                    if (!$form_fields)
                        $form_fields = $this->get_form_fields();
                    
                    $this->sanitized_fields = array();
                    
                    foreach ($form_fields as $k => $v) {
                        if (empty($v['type']))
                            $v['type'] = 'text'; // Default to "text" field type.
                        
                        // Look for a validate_FIELDID_field method for special handling
                        if (method_exists($this, 'validate_' . $k . '_field')) {
                            $field                      = $this->{'validate_' . $k . '_field'}($k);
                            $this->sanitized_fields[$k] = $field;
                            
                            // Look for a validate_FIELDTYPE_field method
                        } elseif (method_exists($this, 'validate_' . $v['type'] . '_field')) {
                            $field                      = $this->{'validate_' . $v['type'] . '_field'}($k);
                            $this->sanitized_fields[$k] = $field;
                            
                            // Default to text
                        } else {
                            $field                      = $this->{'validate_text_field'}($k);
                            $this->sanitized_fields[$k] = $field;
                        }
                    }
                }
                
            }
        }
    }
    
    
    add_action('woocommerce_shipping_init', 'wga_shipping_method_init');
    function add_wga_shipping_method($methods) {
        $methods[] = 'WC_Wga_Shipping_Method';
        return $methods;
    }
    add_filter('woocommerce_shipping_methods', 'add_wga_shipping_method');
}

