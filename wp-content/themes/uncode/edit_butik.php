<?php
$user = wp_get_current_user();
$user_name = $user->user_nicename;
//echo 	$user->ID;
$vendor_name = get_user_meta($user->ID,'vendor_name',true);	
$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'slug' =>$user_name,
			        )
			 );
			 
		 
if(isset($_POST['edit_vendor']))
{
	global $wpdb;
	
	$vendor_name 		= $_POST['vendor_name'];
	$vendor_pwd 		= sanitize_text_field($_POST['vendor_pwd']);
	$vendor_description = $_POST['vendor_description'];
	$address 			= $_POST['address'];
	$by 				= $_POST['by'];
	$zip				= $_POST['zip'];
	$phone				= $_POST['phone'];
	$email				= $_POST['email'];
	$cvr 				= $_POST['cvr'];
	$paypal_email		= $_POST['paypal_email'];
	
	
	$userdata = array(
						'ID'        =>  $user->ID,
						'user_pass' =>  $vendor_pwd // Wordpress automatically applies the wp_hash_password() function to the user_pass field.
        			); 
	if($vendor_pwd)
	{
		wp_update_user($userdata);
	}
	
	$user = wp_get_current_user();
	$user_name = $user->user_login;
	$user_email =  $user->user_email ;
	$vendor_name_danish = get_user_meta($user->ID,'vendor_name',true);	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$vendor_name_danish,
			        )
			 );
	
	$vendor_term_address 	= get_term_meta($vendor_term[0]->term_id, 'address', true);
	$vendor_term_by 		= get_term_meta($vendor_term[0]->term_id, 'by', true);
	$vendor_term_zip 		= get_term_meta($vendor_term[0]->term_id, 'zip', true);
	$vendor_term_phone 		= get_term_meta($vendor_term[0]->term_id, 'phone', true);
	$vendor_term_cvr 		= get_term_meta($vendor_term[0]->term_id, 'cvr', true);
			
	//echo $vendor_term_address.'/'.$vendor_term_by.'/'.$vendor_term_zip.'/'.$vendor_term_phone.'/'.$vendor_term_cvr;
	
	
	update_term_meta($vendor_term[0]->term_id, 'address', $address);
	update_term_meta($vendor_term[0]->term_id, 'by', $by);
	update_term_meta($vendor_term[0]->term_id, 'zip', $zip);
	update_term_meta($vendor_term[0]->term_id, 'phone', $phone);
	update_term_meta($vendor_term[0]->term_id, 'cvr', $cvr);
	
	$wpdb->update( $wpdb->term_taxonomy,array('description'=>$vendor_description),array( 'term_id' => $vendor_term[0]->term_id));
	
	
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
	$vendor_term_ = get_term_meta($vendor_term[0]->term_id, 'vendor_data', true);
	
	if($paypal_email)
	{
		$vendor_term_['paypal'] = $paypal_email;
		update_term_meta($vendor_term[0]->term_id, 'vendor_data', $vendor_term_);
	}
	
	if($_FILES['butik_logo']['name'] !='')
	{
		$attachment_id = media_handle_upload( 'butik_logo', 0 );
		$vendor_term_['logo'] = $attachment_id;
		update_term_meta($vendor_term[0]->term_id, 'vendor_data', $vendor_term_);
	}
	
	
	if($_FILES['butik_shopphoto']['name'] !='')
	{
		$attachment_id1 = media_handle_upload( 'butik_shopphoto', 0 );
		$vendor_term_['shopphoto'] = $attachment_id1;
		update_term_meta($vendor_term[0]->term_id, 'vendor_data', $vendor_term_);	
	}
	
	if($_FILES['butik_shopphoto1']['name'] !='')
	{
		$attachment_id2 = media_handle_upload( 'butik_shopphoto1', 0 );
		$vendor_term_['shopphoto1'] = $attachment_id2;
		update_term_meta($vendor_term[0]->term_id, 'vendor_data', $vendor_term_);	
	}
	
	if($_FILES['butik_shopphoto2']['name'] !='')
	{
		$attachment_id3 = media_handle_upload( 'butik_shopphoto2', 0 );
		$vendor_term_['shopphoto2'] = $attachment_id3;
		update_term_meta($vendor_term[0]->term_id, 'vendor_data', $vendor_term_);	
	}
	
	
	
	$success_msg = 'Dine ændringer er gemt.';
	
}


if($vendor_term && is_user_logged_in())
{
	$user = wp_get_current_user();
	$user_name = $user->user_login;
	$user_email =  $user->user_email ;
	$vendor_name = get_user_meta($user->ID,'vendor_name',true);	
	
	$vendor_term = get_terms( array(
					'taxonomy' => 'wcpv_product_vendors',
					'hide_empty' => false,
					'name' =>$vendor_name,
			        )
			 );
			 

	$vendor_term_meta = get_term_meta($vendor_term[0]->term_id)	;
	
			$vendor_address='';
			$vendor_by = '';
			$vendor_zip= '';
			$vendor_phone = '';
			$vendor_cvr = '';
			
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
					if($km =='phone')
					$vendor_phone = $vm[0];
					if($km =='cvr')
					$vendor_cvr = $vm[0];
					
				}
			}
			 
	$vendor_desc = term_description( $vendor_term[0]->term_id, 'wcpv_product_vendors' ) ;
	
	$vendor_term_logo = get_term_meta($vendor_term[0]->term_id, 'vendor_data', true);
	
	$logo_post_id = $vendor_term_logo['logo']; 
	
	$logo_post = get_the_guid($logo_post_id);
	
	$logo_image = wp_get_attachment_image_src($logo_post_id,'thumbnail');
	//print_r($logo_image);
	$shopphoto_post_id = $vendor_term_logo['shopphoto']; 
	
	
	
	$shopphoto_post = get_the_guid($shopphoto_post_id);
	
	

	$shopphoto_image=  wp_get_attachment_image_src($vendor_term_logo['shopphoto'],'thumbnail');
	$shopphoto1_image=  wp_get_attachment_image_src($vendor_term_logo['shopphoto1'] ,'thumbnail');
	$shopphoto2_image=  wp_get_attachment_image_src($vendor_term_logo['shopphoto2'] ,'thumbnail');
	
	$paypal_email = $vendor_term_logo['paypal']; 
	
	
?>
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
                                    <a href="http://stayfab.dk/opret-en-deal">Opret en deal</a></li>
                                   
                                    <li id="menu-item-11600" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11600">
                                    <a href="http://stayfab.dk/mine-produkter">Mine deals</a></li>
                                   
                                    <li id="menu-item-11601" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-11601">
                                    <a href="http://stayfab.dk/rediger-profil">Rediger min butik</a></li>
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
<br>


<div style="color:green" id="delete_deal_msg">
<?php echo $success_msg;?>
</div>

<form action="" method="POST" enctype="multipart/form-data">

        <p class="form-row form-row-first">
		<label for="wcpv-vendor-vendor-name"><?php esc_html_e( 'Firmanavn', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="vendor_name" id="vendor_name" value="<?php echo $vendor_name;?>" readonly />
		</p>
       
        <div class="clear"></div>
        

        <p class="form-row form-row-wide">
            <label for="wcpv-vendor-description"><?php esc_html_e( 'Beskrivelse', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
            <textarea name="vendor_description" id="vendor_description" rows="4" ><?php echo strip_tags($vendor_desc); ?></textarea>
            <div style="color:red" id="product_vendor_description"></div>
        </p>
    
    
       <p class="form-row form-row-wide">
		<label for="wcpv-address"><?php esc_html_e( 'Adresse', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="address" id="address" value="<?php echo $vendor_address; ?>" />
         <div style="color:red" id="product_address"></div>
		</p>
        
        
        <p class="form-row form-row-first">
		<label for="wcpv-by"><?php esc_html_e( 'By', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="by" id="by" value="<?php echo $vendor_by; ?>" />
         <div style="color:red" id="product_by"></div>
		</p>
        
        <p class="form-row form-row-last">
		<label for="wcpv-zip"><?php esc_html_e( 'Postnummer', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="zip" id="zip" value="<?php echo $vendor_zip; ?>" />
         <div style="color:red" id="product_zip"></div>
		</p>
         <div class="clear"></div>
         <p class="form-row form-row-first">
		<label for="wcpv-phone"><?php esc_html_e( 'Telefon', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="phone" id="phone" value="<?php echo $vendor_phone; ?>"/>
         <div style="color:red" id="product_phone"></div>
		</p>
        
    
        <p class="form-row form-row-last">
			<label for="wcpv-email"><?php esc_html_e( 'Email', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
			<input type="email" class="input-text" name="email" id="email" value="<?php echo $user_email;?>" readonly />
            
		</p>
 		<div class="clear"></div>
		
	
	
		<div class="clear"></div>
        <p class="form-row">
            <label for="wcpv-cvr"><?php esc_html_e( 'CVR nummer', 'domain' ); ?><span class="required">*</span></label>
            <input type="number" class="input-text" name="cvr" id="cvr" value="<?php echo $vendor_cvr;?>"  />
            <div style="color:red" id="product_cvr"></div>
        </p>
        
        <p class="form-row form-row-last">
			<label for="wcpv-paypal_email"><?php esc_html_e( 'PayPal Email', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
			<input type="email" class="input-text" name="paypal_email" id="paypal_email" value="<?php echo $paypal_email;?>" />
            
		</p>
       
        <p class="form-row">
        <img style="    max-width: 250px;    padding-bottom: 20px; outline: 1px solid black !important;" src="<?php echo $logo_post;?>">
        </p>

        <p class="form-row">
        <label for="wcpv-logo"><?php esc_html_e( 'Logo', 'domain' ); ?></label>
        <label style="font-weight:normal">Upload venligst dit logo i jpeg/jpg/png format.</label>
        <input type="file" name="butik_logo" id="butik_logo" class="butik_logo" />
        </p>
        
         <p class="form-row">
        <img src="<?php echo $shopphoto_image[0];?>">
        </p>
        <p class="form-row">
        <label for="wcpv-shopphoto"><?php esc_html_e( 'Butik foto', 'domain' ); ?></label>
        <label style="font-weight:normal">Upload venligst en foto af din butik i jpeg/jpg/png format.</label>
        <input type="file" name="butik_shopphoto" id="butik_shopphoto" class="butik_shopphoto" /> 
       
         <?php if($shopphoto1_image[0]) {
			?>
            <p class="form-row">
            <img src="<?php echo $shopphoto1_image[0];?>">
            </p>
           
		<?php
		}
		?>
         <input type="file" name="butik_shopphoto1" id="butik_shopphoto1" class="butik_shopphoto1" />
		  <?php if($shopphoto2_image[0]) {
			?>
            <p class="form-row">
            <img src="<?php echo $shopphoto2_image[0];?>">
            </p>
            
		<?php
		}
      	?>
         <input type="file" name="butik_shopphoto2" id="butik_shopphoto2" class="butik_shopphoto2" />
        </p>
         <?php /*?><p class="form-row">
        <img src="<?php echo $shopphoto1_image[0];?>">
        </p><?php */?>
        
        <div class="clear"></div>
		 <p class="form-row form-row-first">
		<label for="wcpv-vendor-vendor-name"><?php esc_html_e( 'Ny adgangskode', 'woocommerce-product-vendors' ); ?></label>
		<input class="input-text" type="password" name="vendor_pwd" id="vendor_pwd" value="" />
		</p>
		<div class="clear"></div>
	
        <p class="form-row">
            <input id="edit_vendor" type="submit" class="button" name="edit_vendor" value="<?php esc_attr_e( 'Gem', 'woocommerce-product-vendors' ); ?>" />
        </p>

</form>



<?php
}
else
{
	echo "Log ind venligst som en butik.";
	
}
?>