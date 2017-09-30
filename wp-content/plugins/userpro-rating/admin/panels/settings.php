<script>
jQuery(function(){
	jQuery('#usepro_rating_roles_can_rate').change(function(){
<?php
				if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
				$roles = $wp_roles->get_names();
				foreach($roles as $k=>$v) {
					if ($k != 'administrator') {
						echo 'jQuery("#td-'.$k.'").show();';
					}
				}
?>
		var roles = jQuery('#usepro_rating_roles_can_rate').val();
		if(roles != null)
		for(var i = 0; i<roles.length; i++) {
			jQuery('#opt-'+roles[i]).attr('disabled' , 'disabled');
			jQuery('#td-'+roles[i]).hide();
		}
	});
	var roles = jQuery('#usepro_rating_roles_can_rate').val();
	if(roles != null)
	for(var i = 0; i<roles.length; i++) {
		jQuery('#opt-'+roles[i]).attr('disabled' , 'disabled');
		jQuery('#td-'+roles[i]).hide();
	}
});
</script>

<form method="post" action="">

<h3><?php _e('General Settings','userpro-rating'); ?></h3>
<table class="form-table">
<tr valign="top">
		<th scope="row"><label for="userpro_rating_envato_code"><?php _e('Envato Purchase code','userpro-rating'); ?></label></th>
		<td>
			<input type="text" style="width:300px !important;" name="userpro_rating_envato_code" id="userpro_rating_envato_code" value="<?php echo (userpro_rating_get_option('userpro_rating_envato_code')) ? userpro_rating_get_option('userpro_rating_envato_code') : ''; ?>" class="regular-text" />
			<span class="description"><?php _e('Enter your envato purchase code.','userpro-rating'); ?></span>
		</td>
</tr>
<tr valign="top">
		<th scope="row"><label for="roles_can_view_profiles[]"><?php _e('Restrict Roles From Rating','userpro'); ?></label></th>
		<td>
			<select name="usepro_rating_roles_can_rate[]" id="usepro_rating_roles_can_rate" multiple="multiple" class="chosen-select" style="width:300px" data-placeholder="<?php _e('Select roles','userpro'); ?>">
				<?php
				if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
				$roles = $wp_roles->get_names();
				foreach($roles as $k=>$v) {
					if ($k != 'administrator') {
				?>
				<option value="<?php echo $k; ?>" <?php userpro_is_selected($k, userpro_rating_get_option('usepro_rating_roles_can_rate') ); ?>><?php echo $v; ?></option>
				<?php }
				} ?>
			</select>
			<span class="description"><?php _e('Enter the roles you want to restrict from rating and reviewing the users.','userpro-rating'); ?></span>
		</td>
	</tr>

	<?php
		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		$roles = $wp_roles->get_names();
		foreach($roles as $k=>$v) {
			if ($k != 'administrator') {
	?>
	<tr id="td-<?php echo $k;?>" valign="top">
		<th scope="row"><label for="roles_can_view_profiles[]"><?php echo $v;  _e('Can Rate or submit Review to ','userpro'); ?></label></th>
		<td>
			<select  id="opt-<?php echo $k;?>" name="usepro_rating_<?php echo $k; ?>_can_rate[]" id="usepro_rating_<?php echo $k; ?>_can_rate[]" multiple="multiple" class="chosen-select" style="width:300px" data-placeholder="<?php _e('Select roles','userpro'); ?>">
				<?php 
				foreach($roles as $krole=>$vrole) {
			if ($k != 'administrator') {
				?>
				<option  value="<?php echo $krole; ?>" <?php userpro_is_selected($krole, userpro_rating_get_option('usepro_rating_'.$k.'_can_rate') ); ?>><?php echo $vrole; ?></option>
				<?php 
					}
				}
				?>
			</select>
			<span class="description"><?php _e('','userpro-rating'); ?></span>
		</td>
	</tr>
	<?php 
			}
		} 
	?>
	
	<tr valign="top">
		<th scope="row"><label for="userpro_rating_review_show"><?php _e('Enable Anonymous Reviews','userpro-rating'); ?></label></th>
		<td>
			<select name="userpro_rating_review_show" id="userpro_rating_review_show" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('userpro_rating_review_show')); ?>><?php _e('Yes','userpro'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('userpro_rating_review_show')); ?>><?php _e('No','userpro'); ?></option>
			</select>
			<span class="description"><?php _e('If enabled, then only review comments will be shown. If disabled, then user profiles and user names of the user who submitted the review comment will be shown on the review page.','userpro-rating'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="userpro_rating_review_length"><?php _e('Review Length','userpro-rating'); ?></label></th>
		<td>
			<input type="text" name="userpro_rating_review_length" id="userpro_rating_review_length" value="<?php echo (userpro_rating_get_option('userpro_rating_review_length')) ? userpro_rating_get_option('userpro_rating_review_length') : '255'; ?>" class="regular-text" />
			<span class="description"><?php _e('Limit number of characters for reviews. Default Max length is 255 characters','userpro-rating'); ?></span>
		</td>
	</tr>


	<tr valign="top">
		<th scope="row"><label for="enable_star_rating"><?php _e('Enable star rating','userpro-rating'); ?></label></th>
		<td>
			<select name="enable_star_rating" id="enable_star_rating" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('enable_star_rating')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('enable_star_rating')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="enable_reviews"><?php _e('Enable reviews','userpro-rating'); ?></label></th>
		<td>
			<select name="enable_reviews" id="enable_reviews" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('enable_reviews')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('enable_reviews')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>
		</td>
	</tr>
<tr valign="top">
		<th scope="row"><label for="following_user"><?php _e('Users will Rate to those users whom they follow','userpro-rating'); ?></label></th>
		<td>
			<select name="following_user" id="following_user" class="chosen-select" style="width:300px">
				<option value="1" <?php selected('1', userpro_rating_get_option('following_user')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected('0',userpro_rating_get_option('following_user')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>

		</td>
	</tr>	
	<tr valign="top">
		<th scope="row"><label for="userpro_rating_review_notification"><?php _e('Send email to user when someone writes review','userpro-rating'); ?></label></th>
		<td>
			<select name="userpro_rating_review_notification" id="userpro_rating_review_notification" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('userpro_rating_review_notification')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('userpro_rating_review_notification')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>
		</td>


	</tr>

<tr valign="top">
		<th scope="row"><label for="display_rating_on_post"><?php _e('Display Rating on post','userpro-rating'); ?></label></th>
		<td>
			<select name="display_rating_on_post" id="display_rating_on_post" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('display_rating_on_post')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('display_rating_on_post')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>
		</td>


	</tr>

<tr valign="top">
		<th scope="row"><label for="enable_reply"><?php _e('Enable Reply for Reviews','userpro-rating'); ?></label></th>
		<td>
			<select name="enable_reply" id="enable_reply" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('enable_reply')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('enable_reply')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row"><label for="userpro_rating_reply_notification"><?php _e('Send email to user when someone gives reply','userpro-rating'); ?></label></th>
		<td>
			<select name="userpro_rating_reply_notification" id="userpro_rating_reply_notification" class="chosen-select" style="width:300px">
				<option value="1" <?php selected(1, userpro_rating_get_option('userpro_rating_reply_notification')); ?>><?php _e('Yes','userpro-rating'); ?></option>
				<option value="0" <?php selected(0, userpro_rating_get_option('userpro_rating_reply_notification')); ?>><?php _e('No','userpro-rating'); ?></option>
			</select>
		</td>
	</tr>

</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','userpro-rating'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','userpro-rating'); ?>"  />
</p>

</form>
