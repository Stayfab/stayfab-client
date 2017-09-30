<?php
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
<h3><?php _e("Extra User information for showing in map", "blank"); ?></h3>

<?php 
	$u_addr = esc_attr( get_the_author_meta( 'address', $user->ID ) );
	$u_city = esc_attr( get_the_author_meta( 'city', $user->ID ) );
	$u_lat = esc_attr( get_the_author_meta( 'lat', $user->ID ) );
	$u_long = esc_attr( get_the_author_meta( 'long', $user->ID ) );
	$u_country = esc_attr( get_the_author_meta( 'country', $user->ID ) );
	$u_rating =get_option('upr_rating_'.$user->ID);
	if(is_array($u_rating)){
			$score = array_sum($u_rating)/count($u_rating);
		}else{
			$score = 0;
		}

?>

<input type="button" id="btn_insert_user_store" value="Add this user to store" data-userid="<?php echo $user->ID;?>" onclick="return insert_into_store('<?php echo $user->ID;?>','<?php echo $u_addr;?>','<?php echo $u_city;?>','<?php echo $u_lat;?>','<?php echo $u_long;?>','<?php echo $u_country;?>','<?php echo $score;?>')"/>
<div class="wq_success_msg" id="wq_success_msg">

</div>
<table class="form-table">

<tr>
    <th><label for="address"><?php _e("Address"); ?></label></th>
    <td>
        <input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Please enter your address."); ?></span>
    </td>
</tr>

<tr>
    <th><label for="city"><?php _e("City"); ?></label></th>
    <td>
        <input type="text" name="city" id="city" value="<?php echo esc_attr( get_the_author_meta( 'city', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Please enter your city."); ?></span>
    </td>
</tr>
<tr>
    <th><label for="country"><?php _e("Country"); ?></label></th>
    <td>
        <input type="text" name="country" id="country" value="<?php echo esc_attr( get_the_author_meta( 'country', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Please enter your city."); ?></span>
    </td>
</tr>

<tr>
    <th><label for="lat"><?php _e("Latitude:"); ?></label></th>
    <td>
        <input type="text" name="lat" id="lat" value="<?php echo esc_attr( get_the_author_meta( 'lat', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Please enter your latitude."); ?></span>
    </td>
</tr>

<tr>
    <th><label for="long"><?php _e("Longitude."); ?></label></th>
    <td>
        <input type="text" name="long" id="long" value="<?php echo esc_attr( get_the_author_meta( 'long', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Please enter your longitude."); ?></span>
    </td>
</tr>
<tr>
    <th><label for="rating"><?php _e("Rating."); ?></label></th>
    <td>
        <input type="text" name="rate" id="rate" value="<?php echo $score; ?>" class="regular-text" readonly="readonly"/><br />
    </td>
</tr>

</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

update_user_meta( $user_id, 'address', $_POST['address'] );
update_user_meta( $user_id, 'city', $_POST['city'] );
update_user_meta( $user_id, 'lat', $_POST['lat'] );
update_user_meta( $user_id, 'long', $_POST['long'] );
update_user_meta( $user_id, 'country', $_POST['country'] );
}

/*              update user meta hook                    */
/*function wq_init() {
	add_filter( 'update_user_metadata', 'wq_update_stores', 10, 5 );
}

function wq_update_stores( $meta_id, $object_id, $meta_key, $meta_value, $prev_value ) {

	
	// here user id is $object_id;
	//update_post_meta($object_id);
	//update_post_meta(10805,"wq_option",$meta_value);
	

}

add_action( 'init', 'wq_init' );*/

add_action( 'wp_ajax_insert_into_store_action', 'insert_into_store_action_callback' );

add_action( 'wp_ajax_nopriv_insert_into_store_action', 'insert_into_store_action_callback' );

function insert_into_store_action_callback() 
{
	global $wpdb;
	$userid  = $_POST["userid"];
	$uaddr   = $_POST["uaddr"];
	$ucity   = $_POST["ucity"];
	$ulat    = $_POST["ulat"];
	$ulong   = $_POST["ulong"];
	$ucountry   = $_POST["ucountry"];
	$urate = $_POST["urate"];
	$udetails = get_userdata( $userid );
	$ulogin = $udetails->user_login;
	//$post_title = $ulogin;
	$post_id_exist = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $ulogin . "' and post_status='publish'" );
	
	$my_post = array(
	'post_title'    => $ulogin,
	'post_status'   => 'publish',
	'post_author'   => $userid,
	'post_type' => 'wpsl_stores'
	);
	
	// Insert the post into the database wpsl_stores
	$store_exists='';
	if(!$post_id_exist)
	{
		$post_id = wp_insert_post( $my_post );
		
		if(!is_wp_error($post_id))
		{
			update_post_meta($post_id,'wpsl_address',$uaddr);
			update_post_meta($post_id,'wpsl_city',$ucity);
			update_post_meta($post_id,'wpsl_lat',$ulat);
			update_post_meta($post_id,'wpsl_lng',$ulong);
			update_post_meta($post_id,'wpsl_country',$ucountry);
			update_post_meta($post_id,'wpsl_rate',$urate);
		}
		$store_exists = "New Store";	
	}
	else
	{
			update_post_meta($post_id_exist,'wpsl_address',$uaddr);
			update_post_meta($post_id_exist,'wpsl_city',$ucity);
			update_post_meta($post_id_exist,'wpsl_lat',$ulat);
			update_post_meta($post_id_exist,'wpsl_lng',$ulong);
			update_post_meta($post_id_exist,'wpsl_country',$ucountry);
			update_post_meta($post_id,'wpsl_rate',$urate);
		
		$store_exists = "Store Exists";	
	}
	
	echo $post_id_exist;
	
	wp_die(); 
}

?>