<form method="post" action="">

<h3><?php _e('Email Notification','userpro-rating'); ?></h3>
<table class="form-table">
	
	<tr valign="top">
		<th scope="row"><label for="reply_mail_s"><?php _e('Subject','userpro-rating'); ?></label></th>
		<td><input type="text" name="reply_mail_s" id="reply_mail_s" value="<?php echo userpro_rating_get_option('reply_mail_s'); ?>" class="regular-text" /></td>
	</tr>
	
	<tr valign="top">
		<th scope="row"><label for="reply_mail_c"><?php _e('Email Content','userpro-rating'); ?></label></th>
		<td><textarea name="reply_mail_c" id="reply_mail_c" class="large-text code" rows="10"><?php echo userpro_rating_get_option('reply_mail_c'); ?></textarea></td>
	</tr>
	
</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','userpro-rating'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','userpro-rating'); ?>"  />
</p>

</form>
