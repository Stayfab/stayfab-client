<?php
/**
 * Vendor Registration Form Template
 *
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<h2><?php //esc_html_e( 'Vendor Registration Form', 'woocommerce-product-vendors' ); ?></h2>

<p><?php //esc_html_e( 'Submit the form below to become a vendor on this store.', 'woocommerce-product-vendors' ); ?></p>

<form action="" method="POST" class="wcpv-shortcode-registration-form" enctype="multipart/form-data">

	<?php do_action( 'wcpv_registration_form_start' ); ?>

	<?php if ( ! is_user_logged_in() ) { ?>
		
		
		<div class="clear"></div>
        
        <p class="form-row form-row-wide">
		<label for="wcpv-vendor-vendor-name"><?php esc_html_e( 'Firmanavn', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="vendor_name" id="wcpv-vendor-name" value="<?php if ( ! empty( $_POST['vendor_name'] ) ) echo esc_attr( trim( $_POST['vendor_name'] ) ); ?>" tabindex="1" />
		<em class="wcpv-field-note"><?php //esc_html_e( 'Important: This is the name that customers see when purchasing your products.  Please choose carefully.', 'woocommerce-product-vendors' ); ?></em>
	</p>

	<p class="form-row form-row-wide">
		<label for="wcpv-vendor-description"><?php esc_html_e( 'Beskrivelse', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<textarea placeholder="Beskriv din forretning og lidt om dig selv..." class="input-text" name="vendor_description" id="wcpv-vendor-description" rows="4" tabindex="2"><?php if ( ! empty( $_POST['vendor_description'] ) ) echo trim( $_POST['vendor_description'] ); ?></textarea>
	</p>
    
    
		
      
         <p class="form-row form-row-wide">
		<label for="wcpv-address"><?php esc_html_e( 'Adresse', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="address" id="wcpv-address" value="<?php if ( ! empty( $_POST['address'] ) ) echo esc_attr( trim( $_POST['address'] ) ); ?>" tabindex="3" />
		</p>
        
        
        <p class="form-row form-row-first">
		<label for="wcpv-by"><?php esc_html_e( 'By', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="by" id="wcpv-by" value="<?php if ( ! empty( $_POST['by'] ) ) echo esc_attr( trim( $_POST['by'] ) ); ?>" tabindex="4" />
		</p>
        
        <p class="form-row form-row-last">
		<label for="wcpv-zip"><?php esc_html_e( 'Postnummer', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="zip" id="wcpv-zip" value="<?php if ( ! empty( $_POST['zip'] ) ) echo esc_attr( trim( $_POST['zip'] ) ); ?>" tabindex="5" />
		</p>
         <div class="clear"></div>
         <p class="form-row form-row-first">
		<label for="wcpv-phone"><?php esc_html_e( 'Telefon', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
		<input class="input-text" type="text" name="phone" id="wcpv-phone" value="<?php if ( ! empty( $_POST['phone'] ) ) echo esc_attr( trim( $_POST['phone'] ) ); ?>" tabindex="6" />
		</p>
        
        
        
     
        <p class="form-row form-row-last">
			<label for="wcpv-email"><?php esc_html_e( 'Email', 'woocommerce-product-vendors' ); ?> <span class="required">*</span></label>
			<input type="email" class="input-text" name="email" id="wcpv-email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( trim( $_POST['email'] ) ); ?>" tabindex="7" />
		</p>
 	<div class="clear"></div>
		
	
	<?php } ?>
	
	<div class="clear"></div>
    <p class="form-row form-row-first">
		<label for="wcpv-cvr"><?php esc_html_e( 'CVR nummer', 'domain' ); ?><span class="required">*</span></label>
		<input type="number" class="input-text" name="cvr" id="wcpv-cvr" value=""  tabindex="8" />
	</p>
    
     <p class="form-row form-row-last">
		<label for="wcpv-paypal_email"><?php esc_html_e( 'PayPal Email	', 'domain' ); ?><span class="required">*</span></label>
		<input type="email" class="input-text" name="paypal_email" id="wcpv-paypal_email" value=""  tabindex="9" />
	</p>
<div class="clear"></div>
	<p class="form-row">
	<label for="wcpv-logo"><?php esc_html_e( 'Logo', 'domain' ); ?></label>
    <label style="font-weight:normal">Upload venligst dit logo i jpeg/jpg/png format. Filstørrelsen må højst være 2MB.</label>
    <input type="file" name="sortpicwq" id="sortpicwq" /> 
    <input type="hidden" id="image_b64_data" name="image_b64_data" />
	</p>
    
    <p class="form-row">
	<label for="wcpv-logo1"><?php esc_html_e( 'Salon/klinik foto', 'domain' ); ?></label>
    <label style="font-weight:normal">Upload venligst billeder af din salon/klinik i jpeg/jpg/png format. Filstørrelsen må højst være 2MB</label>
    <input type="file" name="sortpicwq1" id="sortpicwq1" /> 
   
    <!--<input type="button" value="Upload another photo" id="shop_photo_second">-->
   
    <input style="display:block" type="file" name="sortpicwq2" id="sortpicwq2" /> 
    
   <!-- <input style="display:none" type="button" value="Upload another photo" id="shop_photo_third">-->
     
   
    
    <input style="display:block" type="file" name="sortpicwq3" id="sortpicwq3" /> 
   
   
    <input type="hidden" id="image_b64_data1" name="image_b64_data1" />
    <input type="hidden" id="image_b64_data2" name="image_b64_data2" />
    <input type="hidden" id="image_b64_data3" name="image_b64_data3" />
	</p>

	<p class="form-row">
		<label for="wcpv-pwd"><?php esc_html_e( 'Vælg en adgangskode', 'domain' ); ?><span class="required">*</span></label>
		<input type="password" class="input-text" name="login_pwd" id="login_pwd-cvr"   tabindex="9" />
	</p>
    
	 <p>
		<label for="wcpv-tc"><input type="checkbox" name="tc" id="wcpv-tc"/><?php echo esc_html_e( '  Jeg accepterer', 'woocommerce-product-vendors' ).'<a href="http://stayfab.dk/generelle-vilkaar-stayfab-dk-samarbejdsaftale" target="_blank"> Betingelser</a>';  ?></label>
		
	</p>
    
     <p>
		<label for="wcpv-newletter"><input type="checkbox" name="newletter" id="newletter" value="1"/><?php esc_html_e( '  Tilmeld nyhedsbrevet', 'woocommerce-product-vendors' ); ?></label>
		
	</p>
	
	<?php do_action( 'wcpv_registration_form' ); ?>

	
    
    <p class="form-row">
		<input id="create_vendor" type="submit" class="button" name="register" value="<?php esc_attr_e( 'Opret', 'woocommerce-product-vendors' ); ?>" tabindex="8" />
	</p>

	<?php do_action( 'wcpv_registration_form_end' ); ?>

</form>
