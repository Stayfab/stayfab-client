<div class="userpro userpro-users userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>
	
	<div class="userpro-body userpro-body-nopad">
	
		
			<?php if (isset($topMember) && !empty($topMember)){ ?>
		
		<?php 
		$index = 0;
		foreach($topMember as $user_id => $user_score) : 
		$user = get_userdata($user_id);
		
		if($index >= $args['topmembers']){
			break;
		}else{
			$index++;
		}
		?>
		
		<div class="userpro-user" data-pic_size="<?php echo $memberlist_pic_size; ?>">
			
			<a href="<?php echo $userpro->permalink($user_id); ?>" class="<?php userpro_user_via_popup($args); ?> userpro-user-img" data-up_username="<?php echo $userpro->id_to_member($user_id); ?>">
				<?php echo get_avatar( $user_id, $memberlist_pic_size ); ?>
			</a>
			
			<?php if ($memberlist_show_name){?>
			<div class="">
				<a href="<?php echo $userpro->permalink($user_id); ?>"><?php echo $user->display_name; ?></a>
			</div>
			<?php } ?>
			<div class="rate-now"><div id="rate-<?php echo $user_id; ?>"></div></div>
			<?php 
			if( userpro_rating_get_option('enable_reviews') ){
				$page_id = get_option('userpro_review_page_link');
				if($page_id){
					$link = get_review_page_link($user_id);
					echo '<a href="'.$link.'">View Reviews</a>';
				}
			}
			$star_img_path = apply_filters('userpro_rating_stars_path', UPR_PLUGIN_URL.'images/');
		?>
			<script type="text/javascript">jQuery('#rate-<?php echo $user_id; ?>').raty({
				halfShow : true,
				half: true,
				readOnly: true,
				score: <?php echo (isset($user_score)) ? $user_score : 0; ?>,
				path: "<?php echo $star_img_path; ?>"
				});</script>
			
		</div>
		
		<?php endforeach; ?>
		<?php } else { ?><div class="userpro-search-noresults"><?php _e('No users match your search. Please try again.','userpro'); ?></div><?php } ?>	
	</div>

</div>
