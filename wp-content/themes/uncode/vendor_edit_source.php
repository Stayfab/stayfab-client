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

	<div class="form-field">
		<label for="facebook"><?php _e( 'Facebook', 'domain' ); ?></label>
		<input type="url" name="facebook" id="facebook" value="" />
	</div>

	<div class="form-field">
		<label for="twitter"><?php _e( 'Twitter', 'domain' ); ?></label>
		<input type="url" name="twitter" id="twitter" value="" />
	</div>
	<?php
}
/**
 * Edit term fields form
 */
function edit_vendor_custom_fields( $term ) {
	wp_nonce_field( basename( __FILE__ ), 'vendor_custom_fields_nonce' );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="facebook"><?php _e( 'Facebook', 'domain' ); ?></label></th>
		<td>
			<input type="url" name="facebook" id="facebook" value="<?php echo esc_url( get_term_meta( $term->term_id, 'facebook', true ) ); ?>" />
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="twitter"><?php _e( 'Twitter', 'domain' ); ?></label></th>
		<td>
			<input type="url" name="twitter" id="twitter" value="<?php echo esc_url( get_term_meta( $term->term_id, 'twitter', true ) ); ?>" />
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
	$old_fb      = get_term_meta( $term_id, 'facebook', true );
	$old_twitter = get_term_meta( $term_id, 'twitter', true );
	$new_fb      = esc_url( $_POST['facebook'] );
	$new_twitter = esc_url( $_POST['twitter'] );
	if ( ! empty( $old_fb ) && $new_fb === '' ) {
		delete_term_meta( $term_id, 'facebook' );
	} else if ( $old_fb !== $new_fb ) {
		update_term_meta( $term_id, 'facebook', $new_fb, $old_fb );
	}
	if ( ! empty( $old_twitter ) && $new_twitter === '' ) {
		delete_term_meta( $term_id, 'twitter' );
	} else if ( $old_twitter !== $new_twitter ) {
		update_term_meta( $term_id, 'twitter', $new_twitter, $old_twitter );
	}
}
add_action( 'wcpv_registration_form', 'vendors_reg_custom_fields' );
function vendors_reg_custom_fields() {
	?>
	<p class="form-row form-row-first">
		<label for="wcpv-facebook"><?php esc_html_e( 'Facebook', 'domain' ); ?></label>
		<input type="text" class="input-text" name="facebook" id="wcpv-facebook" value="<?php if ( ! empty( $_POST['facebook'] ) ) echo esc_attr( trim( $_POST['facebook'] ) ); ?>" />
	</p>

	<p class="form-row form-row-last">
		<label for="wcpv-twitter"><?php esc_html_e( 'Twitter', 'woocommerce-product-vendors' ); ?></label>
		<input type="text" class="input-text" name="twitter" id="wcpv-twitter" value="<?php if ( ! empty( $_POST['twitter'] ) ) echo esc_attr( trim( $_POST['twitter'] ) ); ?>" />
	</p>
	<?php
}
add_action( 'wcpv_shortcode_registration_form_process', 'vendors_reg_custom_fields_save', 10, 2 );
function vendors_reg_custom_fields_save( $args, $items ) {
	$term = get_term_by( 'name', $items['vendor_name'], WC_PRODUCT_VENDORS_TAXONOMY );
	if ( isset( $items['facebook'] ) && ! empty( $items['facebook'] ) ) {
		$fb = esc_url( $items['facebook'] );
		update_term_meta( $term->term_id, 'facebook', $fb );
	}
	if ( isset( $items['twitter'] ) && ! empty( $items['twitter'] ) ) {
		$twitter = esc_url( $items['twitter'] );
		update_term_meta( $term->term_id, 'twitter', $twitter );
	}
}
?>