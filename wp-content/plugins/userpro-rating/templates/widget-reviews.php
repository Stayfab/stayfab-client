<div class="updb-widget-style">
	<div class="updb-basic-info"><?php _e( 'User Reviews', 'userpro-dashboard' );?></div>
	<div class="updb-view-profile-details"><br>
	<?php	
	global $userpro;
	$user_reviews = get_user_meta($user_id , 'user_reviews');
	if(!empty($user_reviews))
	foreach ($user_reviews[0] as $uid => $review)
	 {


	?>
	<div class="left_b">
		<a href="<?php echo $userpro->profile_photo_url($uid); ?>" class="userpro-tip-fade lightview" data-lightview-caption="<?php echo $userpro->profile_photo_title( $user_id ); ?>" title="<?php _e('View member photo','userpro'); ?>">
		<?php echo get_avatar( $uid, '80' ); ?></a>
		<div class="clear"></div>
		<div style="text-align: center">
			<a class="name_t" href="<?php echo $userpro->permalink($uid); ?>"><?php echo userpro_profile_data('display_name', $uid); ?></a>
		</div>
	</div>
					
	<div class="right_text" id="review_sent_<?php echo $uid?>">
		<i id="review_<?php echo $uid; ?>">"<?php echo $review; ?>"</i>
	</div>
	<div class="clear"></div> <?php } ?>
	</div>
</div>
