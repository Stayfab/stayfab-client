<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package uncode
 */


global $metabox_data, $is_redirect, $menutype;

$limit_width = $limit_content_width = $footer_content = $footer_text_content = $footer_icons = $footer_full_width = '';
$alignArray = array('left','right');

$general_style = ot_get_option('_uncode_general_style');

$footer_last_style = ot_get_option( '_uncode_footer_last_style');
$footer_last_bg = ot_get_option('_uncode_footer_bg_color');
$footer_last_bg = ($footer_last_bg == '') ? ' style-'.$footer_last_style.'-bg' : ' style-'.$footer_last_bg.'-bg';

$post_type = isset( $post->post_type ) ? $post->post_type : 'post';
if (is_archive() || is_home()) $post_type .= '_index';
if (is_404()) $post_type = '404';
if (is_search()) $post_type = 'search_index';

/** Get page width info **/
if (isset($metabox_data['_uncode_specific_footer_width'][0]) && $metabox_data['_uncode_specific_footer_width'][0] !== '') {
	if ($metabox_data['_uncode_specific_footer_width'][0] === 'full') $footer_full_width = true;
	else $footer_full_width = false;
} else {
	$footer_generic_width = ot_get_option( '_uncode_'.$post_type.'_footer_width');
	if ($footer_generic_width !== '') {
		if ($footer_generic_width === 'full') $footer_full_width = true;
		else $footer_full_width = false;
	}
	else
	{
		$footer_full = ot_get_option( '_uncode_footer_full');
		$footer_full_width = ($footer_full !== 'on') ? false : true;
	}
}
if (!$footer_full_width) $limit_content_width = ' limit-width';

if (isset($metabox_data['_uncode_specific_footer_block'][0]) && $metabox_data['_uncode_specific_footer_block'][0] !== '') {
	$footer_block = $metabox_data['_uncode_specific_footer_block'][0];
} else {
	$footer_block = ot_get_option('_uncode_' . $post_type . '_footer_block');
	if ($footer_block === '' && $footer_block !== 'none') {
		$footer_block = ot_get_option('_uncode_footer_block');
	}
}

if (isset($footer_block) && !empty($footer_block) && $footer_block !== 'none' && defined( 'WPB_VC_VERSION' )) {
	$footer_block = apply_filters( 'wpml_object_id', $footer_block, 'post' );
	$footer_block_content = get_post_field('post_content', $footer_block);
	if ($footer_full_width) {
		$footer_block_content = preg_replace('#\s(unlock_row)="([^"]+)"#', ' unlock_row="yes"', $footer_block_content);
		$footer_block_content = preg_replace('#\s(unlock_row_content)="([^"]+)"#', ' unlock_row_content="yes"', $footer_block_content);
		$footer_block_counter = substr_count($footer_block_content, 'unlock_row_content');
		if ($footer_block_counter === 0) $footer_block_content = str_replace('[vc_row ', '[vc_row unlock_row="yes" unlock_row_content="yes" ', $footer_block_content);
	} else {
		$footer_block_content = preg_replace('#\s(unlock_row)="([^"]+)"#', ' unlock_row="yes"', $footer_block_content);
		$footer_block_content = preg_replace('#\s(unlock_row_content)="([^"]+)"#', ' unlock_row_content="no"', $footer_block_content);
		$footer_block_counter = substr_count($footer_block_content, 'unlock_row_content');
		if ($footer_block_counter === 0) $footer_block_content = str_replace('[vc_row ', '[vc_row unlock_row="yes" unlock_row_content="no" ', $footer_block_content);
	}
	$footer_content .= uncode_remove_wpautop($footer_block_content);
}

$footer_position = ot_get_option('_uncode_footer_position');
if ($footer_position === '') $footer_position = 'left';

$footer_copyright = ot_get_option('_uncode_footer_copyright');
if ($footer_copyright !== 'off') {
	$footer_text_content = '&copy; '.date("Y").' '.get_bloginfo('name') . ' ' . esc_html__('All rights reserved','uncode');
}

$footer_text = ot_get_option('_uncode_footer_text');
if ($footer_text !== '' && $footer_copyright === 'off') {
	$footer_text_content = uncode_the_content($footer_text);
}

if ($footer_text_content !== '') {
	$footer_text_content = '<div class="site-info uncell col-lg-6 pos-middle text-'.$footer_position.'">'.$footer_text_content.'</div><!-- site info -->';
}

$footer_social = ot_get_option('_uncode_footer_social');
if ($footer_social !== 'off') {
	$socials = ot_get_option( '_uncode_social_list','',false,true);
	if (isset($socials) && !empty($socials) && count($socials) > 0) {
		foreach ($socials as $social) {
			if ($social['_uncode_social'] === '') continue;
			$footer_icons .= '<div class="social-icon icon-box icon-box-top icon-inline"><a href="'.esc_url($social['_uncode_link']).'" target="_blank"><i class="'.esc_attr($social['_uncode_social']).'"></i></a></div>';
		}
	}
}

if ($footer_icons !== '') $footer_icons = '<div class="uncell col-lg-6 pos-middle text-'.($footer_position === 'center' ? $footer_position : $alignArray[!array_search($footer_position, $alignArray)]).'">' . $footer_icons . '</div>';

if (($footer_text_content !== '' || $footer_icons !== '')) {
	switch ($footer_position) {
		case 'left':
			$footer_text_content = $footer_text_content . $footer_icons;
			break;
		case 'center':
			$footer_last_bg .= ' footer-center';
			$footer_text_content = $footer_icons . $footer_text_content;
			break;
		case 'right':
			$footer_text_content = $footer_icons . $footer_text_content;
			break;
	}
	$footer_last_bg .= ' footer-last';
	if (strpos($menutype ,'vmenu') !== false) $footer_last_bg .= ' desktop-hidden';
	$footer_content .= uncode_get_row_template($footer_text_content, $limit_width, $limit_content_width, $footer_last_style, $footer_last_bg, false, false, false);
}?>
							</div><!-- sections container -->
						</div><!-- page wrapper -->
					<?php if ($is_redirect !== true) : ?>
					<footer id="colophon" class="site-footer">
						<?php
							if (function_exists('qtranxf_getLanguage')) $footer_content = __($footer_content);
							echo $footer_content;
						?>
					</footer>
					<?php endif; ?>
				</div><!-- main container -->
			</div><!-- main wrapper -->
		</div><!-- box container -->
	</div><!-- box wrapper -->
	<?php
	$footer_uparrow = ot_get_option('_uncode_footer_uparrow');
	if (wp_is_mobile()) {
		$footer_uparrow_mobile = ot_get_option('_uncode_footer_uparrow_mobile');
		if ($footer_uparrow_mobile === 'off') $footer_uparrow = 'off';
	}
	if ($footer_uparrow !== 'off') {
		$scroll_higher = '';
		if (strpos($menutype ,'vmenu') === false) {
			if ($limit_content_width === '') $scroll_higher = ' footer-scroll-higher';
		}
		echo '<div class="style-light footer-scroll-top'.$scroll_higher.'"><a href="#" class="scroll-top"><i class="fa fa-angle-up fa-stack fa-rounded btn-default btn-hover-nobg"></i></a></div>';
	}
	$vertical = (strpos($menutype, 'vmenu') !== false || $menutype === 'menu-overlay') ? true : false;
	if (!$vertical) {

		$search_animation = ot_get_option('_uncode_menu_search_animation');
		if ($search_animation === '' || $search_animation === '3d') $search_animation = 'contentscale';

	?>
	<div class="overlay overlay-<?php echo $search_animation; ?> style-dark style-dark-bg overlay-search" data-area="search" data-container="box-container">
		<div class="mmb-container"><div class="menu-close-search mobile-menu-button menu-button-offcanvas mobile-menu-button-dark lines-button x2 overlay-close close" data-area="search" data-container="box-container"><span class="lines"></span></div></div>
		<div class="search-container"><?php get_search_form( true ); ?></div>
	</div>

	<?php }

	wp_footer(); ?>
<script>    


window.onload = function(){
    console.log('window loaded');
	setTimeout(function()
	{ 
		console.log('timeout');
		/*jQuery(".wq_myrate").each(function() {
			
			rate = jQuery(this).attr('data_wqrate');
			jQuery(this).addClass('testingg');
		});*/
		
		jQuery("div").each(function( index ) {
		rate = jQuery(this).attr('data_wqrate');
		if(rate)
		{
			 console.log('rate'+rate);	
		}
		});
		
	}, 3000);
	
}
</script>
<script>
jQuery(document).ready(function() {
		
		jQuery('input[name="expire_time"]').keypress(function(evt) {
		
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) 
			{
			if(charCode==58)
			{
				return true;	
			}
			evt.preventDefault();
			}
			return true;
		
		});
		
		jQuery('input[name="from_time"]').keypress(function(evt) {
		
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) 
			{
			if(charCode==58)
			{
				return true;	
			}
			evt.preventDefault();
			}
			return true;
		
		});
		
		jQuery('input[name="to_time"]').keypress(function(evt) {
		
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) 
			{
			if(charCode==58)
			{
				return true;	
			}
			evt.preventDefault();
			}
			return true;
		
		});
		
		
		
		jQuery(document).on( 'click', '#shop_photo_third', function() 
		{
			
			jQuery(this).hide();
			jQuery('#sortpicwq3').css('display','block');
			
		});
		
		
		jQuery(document).on( 'click', '#shop_photo_second', function() 
		{
			
			jQuery(this).hide();
			jQuery('#sortpicwq2').css('display','block');
			jQuery('#shop_photo_third').css('display','block');
		});
		
		
		
		jQuery(document).on( 'click', '#shop_photo_1', function() 
		{
			
			jQuery(this).hide();
			jQuery('#butik_shopphoto1').css('display','block');
			jQuery('#shop_photo_2').css('display','block');
		});
		
		jQuery(document).on( 'click', '#shop_photo_2', function() 
		{
			
			jQuery(this).hide();
			jQuery('#butik_shopphoto2').css('display','block');
			
		});
		
		jQuery(document).on( 'click', '#wq_searchsubmit', function() 
		{
			
			jQuery('#searchsubmit').click();
			
		});
	
		jQuery('#chk_opret_deal').change(function() {
			if(jQuery(this).is(":checked")) 
			{
				jQuery('#edit_submit').prop('disabled', false);
				jQuery('#opret_deal_submit').prop('disabled', false);
			}
			else
			{
				jQuery('#edit_submit').prop('disabled', true);
				jQuery('#opret_deal_submit').prop('disabled', true);
			}
			  
    	});
	
		jQuery(document).on( 'click', '#edit_vendor', function() {
			
			jQuery("#product_vendor_description").html(" ");
			jQuery("#product_address").html(" ");
			jQuery("#product_by").html(" ");
			jQuery("#product_zip").html(" ");
			jQuery("#product_phone").html(" ");
			jQuery("#product_cvr").html(" ");
			
			vendor_description 	=document.getElementById('vendor_description').value;
			address 			=document.getElementById("address").value;
			by 					=document.getElementById("by").value;
			zip 				=document.getElementById("zip").value;
			phone 				=document.getElementById("phone").value;
			cvr 				=document.getElementById("cvr").value;
			
	
			if(vendor_description == '')
				{
					jQuery("#vendor_description").focus();
					jQuery("#product_vendor_description").html("Please type vendor_description...");
					return false;
				}
				
			if(address == '')
				{
					jQuery("#address").focus();
					jQuery("#product_address").html("Please type address...");
					return false;
				}
			if(by == '')
				{
					jQuery("#by").focus();
					jQuery("#product_by").html("Please type By...");
					return false;
				}
				
			if(zip == '')
				{
					jQuery("#zip").focus();
					jQuery("#product_zip").html("Please type zip...");
					return false;
				}
				
			if(phone == '')
				{
					jQuery("#phone").focus();
					jQuery("#product_phone").html("Please type phone...");
					return false;
				}
				
			if(cvr == '')
				{
					jQuery("#cvr").focus();
					jQuery("#product_cvr").html("Please type cvr...");
					return false;
				}
			
			return true;
		});	
			
		jQuery('input[type="file"]').change(function(){
            file_data = jQuery(this).prop('files');
			file_name = file_data[0]['name'];
			ext = file_data[0]['type'];
			current_img_id = jQuery(this).attr("id");
			console.log(jQuery(this).attr("id"));
			if(file_data[0]['size']>2*1024*1024)
			{
				alert("Max size allowed 2MB");
				document.getElementById(current_img_id).value = "";
			}
			if(ext.indexOf('png') >-1 || ext.indexOf('jpeg') >-1 || ext.indexOf('jpg') >-1)
			{
				
			}
			else
			{
				alert("Upload venligst dit logo i jpeg/jpg/png format");
				document.getElementById(current_img_id).value = "";
				
			}
			
			
        });
        
		
		jQuery(document).on( 'click', '#wq_delete', function() {
			
			
			if(confirm("Er du sikker på at du vil slette denne deal?"))
			{
			jQuery('#delete_deal_msg').text('Vent venligst...');
			p_id = jQuery(this).attr('data-product_id');
			//jQuery('#wq_delete').val("Deleting...");
			var data = {
					'action': 'delete_deal_action',
					'p_id' : p_id,
					
				};
				
				jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
				
					//console.log('lists'+response);
					if(response)
					{
						//jQuery('#wq_delete').val("Deleted...");
						window.location = "http://stayfab.dk/mine-produkter?delete_deal=true";
					}
				
				});
			
			
			}
		});
		
		
		jQuery(document).on( 'click', '#wq_activate_deactivate', function(evt) {
			
			evt.preventDefault();
			p_id = jQuery(this).attr('data-product_id');
			p_status = jQuery(this).attr('data-status');
			if(p_status=='Aktiver')
			{
				console.log(p_status);
				
				var data = {
					'action': 'wq_get_expirary_date_action',
					'p_id' : p_id,
				};
				jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
					
					if(response=='1')
					{
							window.location = "http://stayfab.dk/opret-en-deal?deal_product_id="+p_id+'&wq_date=true';
					}
					else
					{
						if(p_status=='Aktiver')
						{
							alert_msg = 'Vil du aktivere dette produkt?';	
						}
						else
						{
							alert_msg = 'Vil du deaktivere dette produkt?';	
						}
						
						
						
						if(confirm(alert_msg))
						{
						jQuery('#delete_deal_msg').text('Vent venligst...');
						var data = {
								'action': 'change_deal_status_action',
								'p_id' : p_id,
								'p_status' : p_status,
								};
							
							jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
							
								//console.log('lists'+response);
								if(response)
								{
									window.location = "http://stayfab.dk/mine-produkter?active_deactive_deal="+p_status;
								}
							
							});
							
						}
						else
						{
							jQuery('#delete_deal_msg').text('');	
						}
					}
					
				
				});
			}
			else
			{
			//console.log(p_id+p_status);
			
						if(p_status=='Aktiver')
						{
							alert_msg = 'Vil du aktivere dette produkt?';	
						}
						else
						{
							alert_msg = 'Vil du deaktivere dette produkt?';	
						}
			
			//alert("Are you sure to "+p_status+"?");
						if(confirm(alert_msg))
						{
							jQuery('#delete_deal_msg').text('Vent venligst...');
							var data = {
									'action': 'change_deal_status_action',
									'p_id' : p_id,
									'p_status' : p_status,
									};
								
								jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
								
									//console.log('lists'+response);
									if(response)
									{
										window.location = "http://stayfab.dk/mine-produkter?active_deactive_deal="+p_status;
									}
								
								});
							
							
						}
			
			}
		});
	
	
	
		jQuery(document).on( 'click', '#opret_deal_submit', function(ev) {
			
			jQuery("#product_type_error").html("");
			jQuery("#product_sale_price_err").html("");
			jQuery("#wq_timepick_error").html("");
			jQuery("#wq_to_time_error").html("");
			jQuery("#wq_from_time_error").html("");
			
			
			forprice = parseInt(document.getElementById("normal_price").value);
			sale_price = parseInt(document.getElementById("sale_price").value);
			
			discount_price = parseInt(forprice*.20);
					
			calculated_sale_price = parseInt(forprice*.80);
			
			discount_price_månedstilbud = parseInt(forprice*.30);
			calculated_sale_price_månedstilbud = parseInt(forprice*.70);		
			
			//product_title =document.getElementById('product_title').value;// jQuery("#product_title").val();
            product_type =document.getElementById("product_type").value;// jQuery("#product_type  :selected").text();
			
			
			product_category =document.getElementById("product_category").value;// jQuery("#product_category  :selected").text();
			
			
			if(document.querySelector("#expire_date")){
				product_expirary_date =document.getElementById("expire_date").value;
			}
			
			if(document.querySelector("#expire_time")){
				product_expirary_time =document.getElementById("expire_time").value;
			}
			
			if(document.querySelector("#from_date")){
				product_from_date =document.getElementById("from_date").value;
			}
			
			if(document.querySelector("#to_date")){
				product_to_date =document.getElementById("to_date").value;
			}
			
			//product_expirary_date =document.getElementById("expire_date").value;
			//product_expirary_time =document.getElementById("expire_time").value;
			//product_from_date =document.getElementById("from_date").value;
			//product_to_date =document.getElementById("to_date").value;
			
			
			console.log(sale_price);
			
				/*if(product_title == '')
				{
					jQuery("#product_title").focus();
					jQuery("#product_title_error").html("Please type product name...");
					return false;
				}*/
			
				if(product_type == 'select')
				{
					jQuery("#product_type").focus();
					jQuery("#product_type_error").html("Please select product type...");
					return false;
				}
				if(product_category == 'select')
				{
					jQuery("#product_category").focus();
					jQuery("#product_type_error1").html("Vælg venligst en kategori.");
					return false;
				}
				
				if(!sale_price)
				{
					jQuery("#sale_price").focus();
					return false;
				}
				
				if(product_type =='månedstilbud' && sale_price>calculated_sale_price_månedstilbud)
				{
					jQuery("#sale_price").focus();
					jQuery('#wq_deal_type_error_msg').html('(min. 30% af før prisen)') ;
					jQuery("#product_sale_price_err").html("Angiv venligst en pris som minimum er 30% under før pris.");
					//console.log(forprice+'/'+sale_price+'/'+calculated_sale_price_månedstilbud);
					return false;
				}
				
				if(product_type =='månedstilbud'  && product_expirary_date == '')
				{
					jQuery("#expire_date").focus();
					//jQuery("#product_title_error").html("Please type product name...");
					return false;
				}
				
				
				
				if(product_type =='dagstilbud' && sale_price>calculated_sale_price)
				{
					jQuery("#sale_price").focus();
					jQuery('#wq_deal_type_error_msg').html('(min. 20% af før prisen)') ;
					jQuery("#product_sale_price_err").html("Angiv venligst en pris som minimum er 20% under før pris.");
					
					return false;
				}
				
				if(product_type =='dagstilbud' && product_from_date == '')
				{
					jQuery("#from_date").focus();
					//jQuery("#product_title_error").html("Please type product name...");
					return false;
				}
				
				
				if(product_type =='dagstilbud' && product_to_date == '')
				{
					jQuery("#to_date").focus();
					//jQuery("#product_title_error").html("Please type product name...");
					return false;
				}
				
				
				/*if(document.querySelector("#expire_time"))
				{
					var hh_array = product_expirary_time.split(":");
					
					console.log(hh_array.length);
					product_expirary_time_hour = hh_array[0];
					product_expirary_time_mins = hh_array[1]; 
					if(!product_expirary_time)
					{
						jQuery("#expire_time").focus();
						return false;
					}
					
					if(hh_array.length > 2)
					{
						jQuery("#expire_time").focus();
						jQuery("#wq_timepick_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					if(product_expirary_time_hour > 23)
					{
						jQuery("#expire_time").focus();
						jQuery("#wq_timepick_error").html("Please type correct time format (23:59).");
						return false;
					}
					if(product_expirary_time_mins > 59)
					{
						jQuery("#expire_time").focus();
						jQuery("#wq_timepick_error").html("Please type correct time format (23:59).");
						return false;
					}
					
				}*/
				
				
				console.log('I am here');
				
				if(product_type =='dagstilbud')
				{
					if(document.querySelector("#from_time")){
					product_from_time =document.getElementById("from_time").value;
					
					var from_hh_array = product_from_time.split(":");
					
					console.log(from_hh_array);
					
					product_from_time_hour = from_hh_array[0];
					product_from_time_mins = from_hh_array[1]; 
					if(!product_from_time)
					{
						jQuery("#from_time").focus();
						return false;
					}
					
					if(from_hh_array.length > 2)
					{
						jQuery("#from_time").focus();
						jQuery("#wq_from_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					if(product_from_time_hour > 23)
					{
						jQuery("#from_time").focus();
						jQuery("#wq_from_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					if(product_from_time_mins > 59)
					{
						jQuery("#from_time").focus();
						jQuery("#wq_from_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					
					
				}
				
					if(document.querySelector("#to_time")){
					product_from_time =document.getElementById("to_time").value;
					
					var from_hh_array = product_from_time.split(":");
					
					console.log(from_hh_array);
					
					product_from_time_hour = from_hh_array[0];
					product_from_time_mins = from_hh_array[1]; 
					if(!product_from_time)
					{
						jQuery("#to_time").focus();
						return false;
					}
					
					if(from_hh_array.length > 2)
					{
						jQuery("#to_time").focus();
						jQuery("#wq_to_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					if(product_from_time_hour > 23)
					{
						jQuery("#to_time").focus();
						jQuery("#wq_to_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					if(product_from_time_mins > 59)
					{
						jQuery("#to_time").focus();
						jQuery("#wq_to_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					
					
				}
				}
				
				
			return true;
			
        });
		
		
		jQuery(document).on( 'click', '#edit_submit', function() {
			jQuery("#product_type_error").html("");
			jQuery("#product_sale_price_err").html("");
			
			forprice = parseInt(document.getElementById("normal_price").value);
			sale_price = parseInt(document.getElementById("sale_price").value);
			
			discount_price = parseInt(forprice*.20);
					
			calculated_sale_price = parseInt(forprice*.80);
			
			discount_price_månedstilbud = parseInt(forprice*.30);
			calculated_sale_price_månedstilbud = parseInt(forprice*.70);		
			
			
            product_type =document.getElementById("product_type").value;// jQuery("#product_type  :selected").text();
			product_category =document.getElementById("product_category").value;// jQuery("#product_category  :selected").text();
			product_expirary_date =document.getElementById("expire_date").value;
			product_expirary_time =document.getElementById("expire_time").value;
			product_from_date =document.getElementById("from_date").value;
			product_to_date =document.getElementById("to_date").value;
						
				if(product_type == 'select')
				{
					jQuery("#product_type").focus();
					jQuery("#product_type_error").html("Please select product type...");
					return false;
				}
				if(product_category == 'select')
				{
					jQuery("#product_category").focus();
					jQuery("#product_type_error1").html("Vælg venligst en kategori.");
					return false;
				}
				
				
				if(product_type =='månedstilbud' && sale_price>calculated_sale_price_månedstilbud)
				{
					jQuery("#sale_price").focus();
					jQuery("#product_sale_price_err").html("Angiv venligst en pris som minimum er 30% under før pris.");
					//console.log(forprice+'/'+sale_price+'/'+calculated_sale_price_månedstilbud);
					return false;
				}
				
				if(product_type =='månedstilbud'  && product_expirary_date == '')
				{
					jQuery("#expire_date").focus();
					//jQuery("#product_title_error").html("Please type product name...");
					return false;
				}
				
				
				
				if(product_type =='dagstilbud' && sale_price>calculated_sale_price)
				{
					jQuery("#sale_price").focus();
					jQuery("#product_sale_price_err").html("Angiv venligst en pris som minimum er 20% under før pris.");
					
					return false;
				}
				
				if(product_type =='dagstilbud' && product_from_date == '')
				{
					jQuery("#from_date").focus();
					//jQuery("#product_title_error").html("Please type product name...");
					return false;
				}
				
				
				if(product_type =='dagstilbud' && product_to_date == '')
				{
					jQuery("#to_date").focus();
					return false;
				}
				
				/*if(document.querySelector("#expire_time"))
				{
					var hh_array = product_expirary_time.split(":");
				
				console.log(hh_array.length);
				product_expirary_time_hour = hh_array[0];
				product_expirary_time_mins = hh_array[1]; 
				
				if(!product_expirary_time)
				{
					jQuery("#expire_time").focus();
					return false;
				}
				
				if(hh_array.length > 2)
				{
					jQuery("#expire_time").focus();
					jQuery("#wq_timepick_error").html("Please type correct time format (23:59).");
					return false;
				}
				
				if(product_expirary_time_hour > 23)
				{
					jQuery("#expire_time").focus();
					jQuery("#wq_timepick_error").html("Please type correct time format (23:59).");
					return false;
				}
				if(product_expirary_time_mins > 59)
				{
					jQuery("#expire_time").focus();
					jQuery("#wq_timepick_error").html("Please type correct time format (23:59).");
					return false;
				}
					
				}*/
				
				if(product_type =='dagstilbud')
				{
					if(document.querySelector("#from_time")){
					product_from_time =document.getElementById("from_time").value;
					
					var from_hh_array = product_from_time.split(":");
					
					console.log(from_hh_array);
					
					product_from_time_hour = from_hh_array[0];
					product_from_time_mins = from_hh_array[1]; 
					if(!product_from_time)
					{
						jQuery("#from_time").focus();
						return false;
					}
					
					if(from_hh_array.length > 2)
					{
						jQuery("#from_time").focus();
						jQuery("#wq_from_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					if(product_from_time_hour > 23)
					{
						jQuery("#from_time").focus();
						jQuery("#wq_from_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					if(product_from_time_mins > 59)
					{
						jQuery("#from_time").focus();
						jQuery("#wq_from_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					
					
				}
				
					if(document.querySelector("#to_time")){
					product_from_time =document.getElementById("to_time").value;
					
					var from_hh_array = product_from_time.split(":");
					
					console.log(from_hh_array);
					
					product_from_time_hour = from_hh_array[0];
					product_from_time_mins = from_hh_array[1]; 
					if(!product_from_time)
					{
						jQuery("#to_time").focus();
						return false;
					}
					
					if(from_hh_array.length > 2)
					{
						jQuery("#to_time").focus();
						jQuery("#wq_to_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					if(product_from_time_hour > 23)
					{
						jQuery("#to_time").focus();
						jQuery("#wq_to_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					if(product_from_time_mins > 59)
					{
						jQuery("#to_time").focus();
						jQuery("#wq_to_time_error").html("Please type correct time format (23:59).");
						return false;
					}
					
					
					
				}
					
				}
				
				
				
				
			return true;
			
        });
	
		
		jQuery(document).on( 'click', '#wq_addtocart', function() {
			p_id = jQuery(this).attr('data-product_id');
			//jQuery("#msg_adding_deal").html("Adding Deals to cart ....");
            console.log(p_id);
			wq_add_to_cart(p_id);
        });
		
		jQuery(document).on( 'click', '#wq_myproduct', function() {
			window.location = "http://stayfab.dk/mine-produkter";
        });

		jQuery(document).on( 'click', '#wq-search-btn', function() {
			jQuery("#deal_lists_wq").html("Vent venligst...");
			zip = jQuery("#wq-search-zip").val();
			radius = jQuery("#wq-radius-dropdown").val();
						jQuery("#rdotype1").attr('disabled', false);
						jQuery("#rdotype1").attr('checked', true);
						jQuery("#rdotype2").attr('disabled', false);
			var data = {
					'action': 'get_deal_by_zipcode_action',
					'zip' : zip,
					'radius' : radius,
				};
				
				jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
				
					//console.log('lists'+response);
					if(response)
					{
						console.log(response);
						jQuery("#deal_lists_wq").html(response);
					}
				
				});
			
        });
		
		
		jQuery(document).on( 'click', '.get_vendor_all_deal', function(){
		
		 jQuery("#deal_lists_wq").html("Vent venligst....");
		 vendor_user_id = jQuery(this).attr('data-author_id');
				
		 
		 console.log("Vendor Clicked"+vendor_user_id);
		
		
				var data = {
					'action': 'get_clicked_vendor_deal_action',
					'vendor_user_id' : vendor_user_id,
				};
				
				jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
				
					
					if(response)
					{
						
						jQuery("#rdotype1").attr('disabled', true);
						jQuery("#rdotype1").attr('checked', false);
						jQuery("#rdotype2").attr('disabled', true);
						jQuery("#rdotype2").attr('checked', false);
						jQuery("#deal_lists_wq").html(response);	
					}
				
				});
		 
		 
		 
				 return false;
		
		 });
		
		
		
		jQuery('input[name=product_category]').change(function() {
		
		
						jQuery("#rdotype1").attr('disabled', false);
						jQuery("#rdotype1").attr('checked', true);
						jQuery("#rdotype2").attr('disabled', false);
			
			var checkValues = jQuery('input[name=product_category]:checked').map(function()
			{
			 return jQuery(this).val();
			}).get(); 
			
			console.log('Selected Cat:'+checkValues);
			product_type = jQuery('input[name=product_type]:checked').val(); 
			
			jQuery("#deal_lists_wq").html("Vent venligst....");
			
				var data = {
					'action': 'get_selected_category_deal_action',
					'checkValues' : checkValues,
					'product_type' : product_type
				};
				
				jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
				
					
					if(response)
					{
						jQuery("#deal_lists_wq").html(response);	
					}
				
				});
				
		
		});
	
	
	jQuery('input[type=radio][name=product_type]').change(function() {
       console.log(this.value);
	   deal_cat = this.value;
	   
						
	   grab_deal_list(deal_cat);
    });
	
	
		jQuery("#create_vendor").click(function(e){
			//firm = jQuery('#wcpv-vendor-name').val();
			//jQuery('#wcpv-vendor-name').val(wcpv-username);
			
			vendor_email = jQuery('#wcpv-email').val();
			newletter = jQuery('#newletter').val();
			if (jQuery('#newletter').is(":checked"))
			{
			  var data = {
				'action': 'subscribe_newsletter_action',
				'vendor_email' : vendor_email,
			};
			
			jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
			
				
				if(response)
				{
					console.log(response);	
				}
			
			});
			  
			  
			}
			
			
			
		
		});
		
		if(document.querySelector("#sortpicwq")){
			document.getElementById("sortpicwq").addEventListener("change", readFile);
		}
		
		if(document.querySelector("#sortpicwq1")){
			document.getElementById("sortpicwq1").addEventListener("change", readFile1);
		}
		
		if(document.querySelector("#sortpicwq2")){
			document.getElementById("sortpicwq2").addEventListener("change", readFile2);
		}
		if(document.querySelector("#sortpicwq3")){
			document.getElementById("sortpicwq3").addEventListener("change", readFile3);
		}
		
		
       
		
		function readFile() {
		  if (this.files && this.files[0]) {
			var FR= new FileReader();
			FR.addEventListener("load", function(e) {
			  abcd = e.target.result;
			  image_data_b64 = abcd.split(",");
			  jQuery("#image_b64_data").val(abcd);
			}); 
			FR.readAsDataURL( this.files[0] );
		  }
		}
		
		function readFile1() {
		  if (this.files && this.files[0]) {
			
			var FR1= new FileReader();
			
			FR1.addEventListener("load", function(e) {
			  //document.getElementById("img").src       = e.target.result;
			 // document.getElementById("b64").innerHTML = e.target.result;
			  abcd1 = e.target.result;
			  image_data_b64 = abcd1.split(",");
			  
			
			  jQuery("#image_b64_data1").val(abcd1);
			  
			}); 
			
			FR1.readAsDataURL( this.files[0] );
		  }
		  
		}
		
		function readFile2() {
		  if (this.files && this.files[0]) {
			var FR= new FileReader();
			FR.addEventListener("load", function(e) {
			  abcd = e.target.result;
			  image_data_b64 = abcd.split(",");
			  jQuery("#image_b64_data2").val(abcd);
			}); 
			FR.readAsDataURL( this.files[0] );
		  }
		}
		
		function readFile3() {
		  if (this.files && this.files[0]) {
			var FR= new FileReader();
			FR.addEventListener("load", function(e) {
			  abcd = e.target.result;
			  image_data_b64 = abcd.split(",");
			  jQuery("#image_b64_data3").val(abcd);
			}); 
			FR.readAsDataURL( this.files[0] );
		  }
		}
        
    });
	
	function grab_deal_list(deal_cat)
	{
		jQuery("#deal_lists_wq").html("Vent venligst....");
		
		var data = {
				'action': 'grab_deal_list_action',
				'deal_cat' : deal_cat,
			};
			
			jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
			
				
				if(response)
				{
					console.log(response);
					jQuery("#deal_lists_wq").html(response);	
				}
			
			});
		
	}
</script>
<script>
function wqFunction() {
    var x = document.getElementById("product_type").value;
    console.log(x);
	if(x=='månedstilbud')
	{
		jQuery("#product_expire_date").css('display','block');
		jQuery("#product_expire_date_dagstilbud").css('display','none');
		jQuery('#wq_deal_type_error_msg').html('(min. 30% af før prisen)') ;
		console.log('i am månedstilbud');
	}
	else if(x=='dagstilbud')
	{
		jQuery("#product_expire_date").css('display','none');
		jQuery("#product_expire_date_dagstilbud").css('display','block');
		
		jQuery('#wq_deal_type_error_msg').html('(min. 20% af før prisen)') ;
		
		console.log('i am not månedstilbud');
	}
	
	else if(x=='elevbehandlinger')
	{
		jQuery("#product_expire_date").css('display','block');
		jQuery("#product_expire_date_dagstilbud").css('display','none');
	}
}

function wqLoad_deal_info()
{
	 var product_id = jQuery("#product_title option:selected").attr("data-id");//document.getElementById("product_title").value;
	 console.log(jQuery("#product_title option:selected").attr("data-id"));
	 jQuery("#product_category").val('select');
	 
	 jQuery("#product_title_loading").html('Vent venligst...');
	 var data = {
					'action': 'wq_load_deal_info',
					'product_id' : product_id,
				};
			
			jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) 
			{
				if(response)
				{
			
					deal_data = JSON.parse(response);
					console.log(deal_data.category);
					
					for (var i = 1; i <= deal_data.cat_count; i++) {
						
						if(deal_data.category[i] ==='Negle behandlinger' || deal_data.category[i] ==='Kvinder klip/frisør' || deal_data.category[i] ==='Mænd klip/frisør' || deal_data.category[i] ==='Kosmetolog behandlinger' || deal_data.category[i] ==='Elevbehandlinger' || deal_data.category[i] ==='Andet')
						{
							console.log('Category:'+deal_data.category[i]);
							jQuery("#product_category1").val(deal_data.category[i]);
							jQuery('#product_category1').attr('disabled', 'disabled');
							
							jQuery("#product_category").val(deal_data.category[i]);
							
						}
					}
					
					
				
					jQuery("#normal_price").val(deal_data.regular_price);
					jQuery('#normal_price').attr('readonly', true);	
					jQuery("#product_desc").val(deal_data.description);	
					jQuery('#product_desc').attr('readonly', true);	
					jQuery("#product_title_loading").html('');
				}
			});
}


function wq_add_to_cart(p_id)
		{
			
			 var data = {
					'action': 'wq_add_to_cart_action',
					'p_id': p_id,
					'qty': 1
				};
				
				jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
				
				if(response)
				{
					console.log("added to cart");
					//jQuery("#wq_success_msg_addtocart").html("Added to cart");
					//window.location.reload();
					//jQuery("#msg_adding_deal").html("Added to cart ....");
					window.location = "http://stayfab.dk/checkout";
				}
					
				});
		}
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
jQuery( function() {
jQuery( "#expire_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
jQuery( "#from_date" ).datepicker({ 
	dateFormat: 'dd/mm/yy' ,
	onSelect: function( selectedDate ) {
        jQuery( "#to_date" ).datepicker("option", "minDate", selectedDate );
        setTimeout(function(){
            jQuery( "#to_date" ).val(selectedDate);
        }, 16);     
    }
});
jQuery( "#to_date" ).datepicker({ dateFormat: 'dd/mm/yy' });

} );

</script>

<script>
function openModal() {
  document.getElementById('myModal').style.display = "block";
}

function closeModal() {
  document.getElementById('myModal').style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}
</script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>
jQuery( function() {
   // jQuery('#expire_time').timepicker({timeFormat: 'HH:mm',interval: 60, minTime: '7', maxTime: '9:00pm'});
	//jQuery('#from_time').timepicker({timeFormat: 'HH:mm',interval: 60, minTime: '7', maxTime: '9:00pm'});
	//jQuery('#to_time').timepicker({timeFormat: 'HH:mm',interval: 60, minTime: '7', maxTime: '9:00pm'});
});
</script>-->
</body>
</html>
<?php
/*$vv = get_term_meta( 463, 'vendor_data', true );

echo '<pre>';
print_r($vv);
echo '</pre>';*/
$vendor_id = '467';
$vendor_term_id = $vendor_id;
	$vendor_term_data = get_term_meta($vendor_term_id, 'vendor_data', true);
	$vendor_user_id = $vendor_term_data['admins'];
	
	$user_info = get_userdata($vendor_user_id);
$order_id = '12129';	
//send_email_after_payment($vendor_id,$order_id);
//send_email_after_payment($vendor_id,$order_id,'2017','08')
?>