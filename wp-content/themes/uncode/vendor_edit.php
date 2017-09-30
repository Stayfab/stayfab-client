<?php
/**
 * Register term fields
 */
add_action( 'init', 'register_vendor_custom_fields' );
function register_vendor_custom_fields() {
	add_action( WC_PRODUCT_VENDORS_TAXONOMY . '_add_form_fields', 'add_vendor_custom_fields' );
	add_action( WC_PRODUCT_VENDORS_TAXONOMY . '_edit_form_fields', 'edit_vendor_custom_fields', 10 );
	add_action( 'edited_' . WC_PRODUCT_VENDORS_TAXONOMY, 'save_vendor_custom_fields' );
	add_action( 'created_' . WC_PRODUCT_VENDORS_TAXONOMY, 'save_vendor_custom_fields' );
}

/**
 * Add term fields form
 */
function add_vendor_custom_fields() {

	wp_nonce_field( basename( __FILE__ ), 'vendor_custom_fields_nonce' );
	?>

	<?php /*?><div class="form-field">
		<label for="cvr"><?php _e( 'CVR nummer', 'domain' ); ?></label>
		<input type="number" name="cvr" id="cvr" value="" />
	</div><?php */?>
    
	<?php
	
}
/**
 * Edit term fields form
 */
function edit_vendor_custom_fields( $term ) {
	wp_nonce_field( basename( __FILE__ ), 'vendor_custom_fields_nonce' );
	?>
    <tr class="form-field">
		<th scope="row" valign="top"><label for="cvr"><?php _e( 'Adresse', 'domain' ); ?></label></th>
		<td>
			<input type="text" name="address" id="address" value="<?php echo get_term_meta( $term->term_id, 'address', true ); ?>" />
		</td>
	</tr>
    
    <tr class="form-field">
		<th scope="row" valign="top"><label for="cvr"><?php _e( 'By', 'domain' ); ?></label></th>
		<td>
			<input type="text" name="by" id="by" value="<?php echo get_term_meta( $term->term_id, 'by', true ); ?>" />
		</td>
	</tr>
    
    <tr class="form-field">
		<th scope="row" valign="top"><label for="cvr"><?php _e( 'Postnummer', 'domain' ); ?></label></th>
		<td>
			<input type="text" name="zip" id="zip" value="<?php echo get_term_meta( $term->term_id, 'zip', true ); ?>" />
		</td>
	</tr>
    
     <tr class="form-field">
		<th scope="row" valign="top"><label for="cvr"><?php _e( 'Telefon', 'domain' ); ?></label></th>
		<td>
			<input type="text" name="phone" id="phone" value="<?php echo get_term_meta( $term->term_id, 'phone', true ); ?>" />
		</td>
	</tr>
    
      <tr class="form-field">
		<th scope="row" valign="top"><label for="cvr"><?php _e( 'CVR nummer', 'domain' ); ?></label></th>
		<td>
			<input type="number" name="cvr" id="cvr" value="<?php echo get_term_meta( $term->term_id, 'cvr', true ); ?>" />
		</td>
	</tr>
	
	<?php
}
/**
 * Save term fields
 */
function save_vendor_custom_fields( $term_id ) {
	if ( ! wp_verify_nonce( $_POST['vendor_custom_fields_nonce'], basename( __FILE__ ) ) ) {
		return;
	}
	
	$old_address      = get_term_meta( $term_id, 'address', true );
	$new_address      = $_POST['address'];
	
	$old_by      = get_term_meta( $term_id, 'by', true );
	$new_by      = $_POST['by'];
	
	
	$old_zip      = get_term_meta( $term_id, 'zip', true );
	$new_zip      = $_POST['zip'];
	
	$old_phone      = get_term_meta( $term_id, 'phone', true );
	$new_phone      = $_POST['phone'];
	
	
	$old_cvr      = get_term_meta( $term_id, 'cvr', true );
	$new_cvr      = $_POST['cvr'];
	
	
	////   Address save from wp-admin
	if ( ! empty( $old_address ) && $new_address === '' ) {
		delete_term_meta( $term_id, 'address' );
	} else if ( $old_address !== $new_address ) {
		update_term_meta( $term_id, 'address', $new_address, $old_address );
	}
	
	////   by save from wp-admin
	if ( ! empty( $old_by ) && $new_by === '' ) {
		delete_term_meta( $term_id, 'by' );
	} else if ( $old_by !== $new_by ) {
		update_term_meta( $term_id, 'by', $new_by, $old_by );
	}
	
	////   zip save from wp-admin
	if ( ! empty( $old_zip ) && $new_zip === '' ) {
		delete_term_meta( $term_id, 'zip' );
	} else if ( $old_zip !== $new_zip ) {
		update_term_meta( $term_id, 'zip', $new_zip, $old_zip );
	}
	
	////   Phone save from wp-admin
	if ( ! empty( $old_phone ) && $new_phone === '' ) {
		delete_term_meta( $term_id, 'phone' );
	} else if ( $old_phone !== $new_phone ) {
		update_term_meta( $term_id, 'phone', $new_phone, $old_phone );
	}
	
	////   cvr save from wp-admin
	if ( ! empty( $old_cvr ) && $new_cvr === '' ) {
		delete_term_meta( $term_id, 'cvr' );
	} else if ( $old_cvr !== $new_cvr ) {
		update_term_meta( $term_id, 'cvr', $new_cvr, $old_cvr );
	}
	
	
	
	
}
add_action( 'wcpv_registration_form', 'vendors_reg_custom_fields' );
function vendors_reg_custom_fields() {
	?>
	
	<?php
}
add_action( 'wcpv_shortcode_registration_form_process', 'vendors_reg_custom_fields_save', 10, 2 );

function aladdin_get_image_id($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
		return $attachment[0];
		}
		
function base64_to_jpeg($base64_string, $output_file) {
			// open the output file for writing
			$ifp = fopen( $output_file, 'abc.png' ); 
			
			// split the string on commas
			// $data[ 0 ] == "data:image/png;base64"
			// $data[ 1 ] == <actual base64 string>
			$data = explode( ',', $base64_string );
			
			// we could add validation here with ensuring count( $data ) > 1
			fwrite( $ifp, base64_decode( $data[ 1 ] ) );
			
			// clean up the file resource
			fclose( $ifp ); 
			
			return $output_file; 
		}
		
		function base64_to_jpeg1($base64_string, $output_file) {
			// open the output file for writing
			$ifp = fopen( $output_file, 'abc1.png' ); 
			
			// split the string on commas
			// $data[ 0 ] == "data:image/png;base64"
			// $data[ 1 ] == <actual base64 string>
			$data = explode( ',', $base64_string );
			
			// we could add validation here with ensuring count( $data ) > 1
			fwrite( $ifp, base64_decode( $data[ 1 ] ) );
			
			// clean up the file resource
			fclose( $ifp ); 
			
			return $output_file; 
		}		
function vendors_reg_custom_fields_save( $args, $items ) {
	$term = get_term_by( 'name', $items['vendor_name'], WC_PRODUCT_VENDORS_TAXONOMY );
	
	
	
	if ( isset( $items['address'] ) && ! empty( $items['address'] ) ) {
		$address = $items['address'] ;
		update_term_meta( $term->term_id, 'address', $address );
	}
	
	if ( isset( $items['by'] ) && ! empty( $items['by'] ) ) {
		$by = $items['by'] ;
		update_term_meta( $term->term_id, 'by', $by );
	}
	
	if ( isset( $items['zip'] ) && ! empty( $items['zip'] ) ) {
		$zip = $items['zip'] ;
		update_term_meta( $term->term_id, 'zip', $zip );
	}
	
	if ( isset( $items['phone'] ) && ! empty( $items['phone'] ) ) {
		$phone = $items['phone'] ;
		update_term_meta( $term->term_id, 'phone', $phone );
	}
	
	if ( isset( $items['cvr'] ) && ! empty( $items['cvr'] ) ) {
		$cvr = $items['cvr'] ;
		update_term_meta( $term->term_id, 'cvr', $cvr );
	}
	
	if ( isset( $items['vendor_description'] ) && ! empty( $items['vendor_description'] ) ) {
		$vendor_description = $items['vendor_description'] ;
		update_term_meta( $term->term_id, 'vendor_description', $vendor_description );
	}
	
	
		
	if ( isset( $items['login_pwd'] ) && ! empty( $items['login_pwd'] ) ) {
		$login_pwd = $items['login_pwd'] ;
		update_term_meta( $term->term_id, 'login_pwd', $login_pwd );
	}
	
	
	if ( isset( $items['paypal_email'] ) && ! empty( $items['paypal_email'] ) ) {
		$paypal_email = $items['paypal_email'] ;
		
		$vendor_term_temp = get_term_meta($term->term_id, 'vendor_data', true);
		
		$vendor_term_temp['commission'] = get_option('wcpv_vendor_settings_default_commission');
			
		$vendor_term_temp['paypal'] = $paypal_email;
		//$vendor_term_temp['commission'] = 95;
		$vendor_term_temp['commission_type'] = 'percentage';
		$vendor_term_temp['instant_payout'] = 'yes';
		
		update_term_meta($term->term_id, 'vendor_data', $vendor_term_temp);
	}
	
			
		
		
		$img_str =  $items['image_b64_data'] ;
		$img_str1 =  $items['image_b64_data1'] ;
		$img_str2 =  $items['image_b64_data2'] ;
		$img_str3 =  $items['image_b64_data3'] ;
		
		$img_data = explode( ',', $img_str );
		$img_data1 = explode( ',', $img_str1 );
		$img_data2 = explode( ',', $img_str2 );
		$img_data3 = explode( ',', $img_str3 );
		
		if(strpos( $img_data[0], 'png') == true)
		{
			$img_ext = '.png';
		}
		elseif(strpos( $img_data[0], 'jpg') == true)
		{
			$img_ext = '.jpg';
		}
		elseif(strpos( $img_data[0], 'jpeg') == true)
		{
			$img_ext = '.jpeg';
		}
		
		
		
		if(strpos( $img_data1[0], 'png') == true)
		{
			$img_ext1 = '.png';
		}
		elseif(strpos( $img_data1[0], 'jpg') == true)
		{
			$img_ext1 = '.jpg';
		}
		elseif(strpos( $img_data1[0], 'jpeg') == true)
		{
			$img_ext1 = '.jpeg';
		}
		
		if(strpos( $img_data2[0], 'png') == true)
		{
			$img_ext2 = '.png';
		}
		elseif(strpos( $img_data2[0], 'jpg') == true)
		{
			$img_ext2 = '.jpg';
		}
		elseif(strpos( $img_data2[0], 'jpeg') == true)
		{
			$img_ext2 = '.jpeg';
		}
		
		if(strpos( $img_data3[0], 'png') == true)
		{
			$img_ext3 = '.png';
		}
		elseif(strpos( $img_data3[0], 'jpg') == true)
		{
			$img_ext3 = '.jpg';
		}
		elseif(strpos( $img_data3[0], 'jpeg') == true)
		{
			$img_ext3 = '.jpeg';
		}
		
		
		$ud = wp_upload_dir();
		
		$img_src = base64_to_jpeg($img_str,$ud['basedir'].'/'.'logo'.$term->term_id.$img_ext);
		
		$img_src1 = base64_to_jpeg1($img_str1,$ud['basedir'].'/'.'shopphoto1'.$term->term_id.$img_ext1);
		
		$img_src2 = base64_to_jpeg1($img_str2,$ud['basedir'].'/'.'shopphoto2'.$term->term_id.$img_ext2);
		$img_src3 = base64_to_jpeg1($img_str3,$ud['basedir'].'/'.'shopphoto3'.$term->term_id.$img_ext3);
		
		
		
		$url ='http://stayfab.dk/wp-content/uploads'.'/'.'logo'.$term->term_id.$img_ext;
		$url1 ='http://stayfab.dk/wp-content/uploads'.'/'.'shopphoto1'.$term->term_id.$img_ext1;
		$url2 ='http://stayfab.dk/wp-content/uploads'.'/'.'shopphoto2'.$term->term_id.$img_ext2;
		$url3 ='http://stayfab.dk/wp-content/uploads'.'/'.'shopphoto3'.$term->term_id.$img_ext3;
		
		update_term_meta( $term->term_id, 'logo_url',$url );
		update_term_meta( $term->term_id, 'shopphoto_url',$url1);
		update_term_meta( $term->term_id, 'shopphoto2_url',$url2);
		update_term_meta( $term->term_id, 'shopphoto3_url',$url3);
		
		
		
		$post_id = 1;
		$att_id = aladdin_get_image_id($url);
		$att_id1 = aladdin_get_image_id($url1);
		$att_id2 = aladdin_get_image_id($url2);
		$att_id3 = aladdin_get_image_id($url3);
		
		if($att_id){
		set_post_thumbnail( $post_id, $att_id );
		}else{
		// Need to require these files
		if ( !function_exists('media_handle_upload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		
		$tmp = download_url( $url );
		if( is_wp_error( $tmp ) ){
		// download failed, handle error
		}
		
		$desc = get_the_title($post_id);
		$file_array = array();
		
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^?]+.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;
		
		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
		@unlink($file_array['tmp_name']);
		$file_array['tmp_name'] = '';
		}
		
		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id, $desc );
		
		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
		@unlink($file_array['tmp_name']);
		return $id;
		}
		
		set_post_thumbnail( $post_id, $id );
		
		}
		
		$post_id = 2;
		if($att_id1){
		set_post_thumbnail( $post_id, $att_id1 );
		}else{
		// Need to require these files
		if ( !function_exists('media_handle_upload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		
		$tmp = download_url( $url1 );
		if( is_wp_error( $tmp ) ){
		// download failed, handle error
		}
		
		$desc = get_the_title($post_id);
		$file_array = array();
		
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^?]+.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;
		
		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
		@unlink($file_array['tmp_name']);
		$file_array['tmp_name'] = '';
		}
		
		// do the validation and storage stuff
		$id1 = media_handle_sideload( $file_array, $post_id, $desc );
		
		// If error storing permanently, unlink
		if ( is_wp_error($id1) ) {
		@unlink($file_array['tmp_name']);
		return $id1;
		}
		
		set_post_thumbnail( $post_id, $id1 );
		
		}
		
		$post_id = 2;
		if($att_id2){
		set_post_thumbnail( $post_id, $att_id2 );
		}else{
		// Need to require these files
		if ( !function_exists('media_handle_upload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		
		$tmp = download_url( $url2 );
		if( is_wp_error( $tmp ) ){
		// download failed, handle error
		}
		
		$desc = get_the_title($post_id);
		$file_array = array();
		
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^?]+.(jpg|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;
		
		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
		@unlink($file_array['tmp_name']);
		$file_array['tmp_name'] = '';
		}
		
		// do the validation and storage stuff
		$id2 = media_handle_sideload( $file_array, $post_id, $desc );
		
		// If error storing permanently, unlink
		if ( is_wp_error($id1) ) {
		@unlink($file_array['tmp_name']);
		return $id2;
		}
		
		set_post_thumbnail( $post_id, $id2 );
		
		}
		
		$post_id = 2;
		if($att_id3){
		set_post_thumbnail( $post_id, $att_id3 );
		}else{
		// Need to require these files
		if ( !function_exists('media_handle_upload') ) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		require_once(ABSPATH . "wp-admin" . '/includes/file.php');
		require_once(ABSPATH . "wp-admin" . '/includes/media.php');
		}
		
		$tmp = download_url( $url3 );
		if( is_wp_error( $tmp ) ){
		// download failed, handle error
		}
		
		$desc = get_the_title($post_id);
		$file_array = array();
		
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^?]+.(jpg|jpeg|gif|png)/i', $url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;
		
		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
		@unlink($file_array['tmp_name']);
		$file_array['tmp_name'] = '';
		}
		
		// do the validation and storage stuff
		$id3 = media_handle_sideload( $file_array, $post_id, $desc );
		
		// If error storing permanently, unlink
		if ( is_wp_error($id1) ) {
		@unlink($file_array['tmp_name']);
		return $id3;
		}
		
		set_post_thumbnail( $post_id, $id3 );
		
		}
		
		
		
			update_term_meta( $term->term_id, 'logo_postt_id',$id );
			update_term_meta( $term->term_id, 'shopphoto_postt_id',$id1 );
			update_term_meta( $term->term_id, 'shopphoto_postt_id1',$id2 );
			update_term_meta( $term->term_id, 'shopphoto_postt_id2',$id3 );
			
			$vendor_term_meta = get_term_meta($term->term_id)	;	
				
			$vendor_term_ = get_term_meta($term->term_id, 'vendor_data', true);
			
			$vendor_term_['logo'] = $id;
			$vendor_term_['shopphoto'] = $id1;
			$vendor_term_['shopphoto1'] = $id2;
			$vendor_term_['shopphoto2'] = $id3;
			update_term_meta($term->term_id, 'vendor_data', $vendor_term_);
			
}