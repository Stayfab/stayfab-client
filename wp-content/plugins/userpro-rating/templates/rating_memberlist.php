<div class="userpro userpro-users userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>
	
	<div class="userpro-body userpro-body-nopad">
	
		<?php if ($search){ ?>
		<div class="userpro-search">
			<form class="userpro-search-form" action="" method="get">
				
				<?php if ($memberlist_default_search) { ?><input type="text" name="searchuser" id="searchuser" value="<?php echo get_query_var('searchuser'); ?>" placeholder="<?php _e('Search for a user...','userpro'); ?>" /><?php } ?>
				
				<?php do_action('userpro_modify_search_filters', $args); ?>
				
				<button type="submit" class="userpro-icon-search userpro-tip" title="<?php _e('Search','userpro'); ?>"></button>
				
				<button type="button" class="userpro-icon-remove userpro-clear-search userpro-tip" title="<?php _e('Clear your Search','userpro'); ?>"></button>
						
			</form>
		</div>
		<?php
		if (isset($users['total']) && !empty($users['total']) && $userpro->memberlist_in_search_mode($args) ){
			echo '<div class="userpro-search-results">'.$userpro->found_members( $users['total'] ).'</div>';
		}
		?>
		<?php } ?>
		
		<?php if ( $userpro->memberlist_in_search_mode($args) ) { ?>
				
		<?php if ( $memberlist_paginate == 1 && $memberlist_paginate_top == 1 && isset($users['paginate'])) { ?><div class="userpro-paginate top"><?php echo $users['paginate']; ?></div><?php } ?>
	
		<?php if (isset($users['users']) && !empty($users['users'])){ ?>
		
		<?php 
		$all_score = array();
		foreach($users['users'] as $user) : $user_id = $user->ID; 
		
				$all_score = get_option('upr_rating_'.$user_id);
				if(!$all_score){
					$user_score = 0;
				}else{
					$user_score = (isset($all_score[get_current_user_id()])) ? $all_score[get_current_user_id()] : 0;
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
				<?php 
				if(is_rating_allowed($user_id)){
				?>
					click: function(score,evt){
							ratebyuid('<?php echo $user_id; ?>',score);
						},
				<?php 
				} else {
				?>
					readOnly: true,
				<?php  } ?>
				score: <?php echo (isset($user_score)) ? $user_score : 0; ?>,
				path: "<?php echo $star_img_path; ?>"
				});</script>
			
		</div>

		<?php endforeach; ?>
		<?php } else { ?><div class="userpro-search-noresults"><?php _e('No users match your search. Please try again.','userpro'); ?></div><?php } ?>

		<?php if ($memberlist_paginate == 1 && $memberlist_paginate_bottom == 1 && isset($users['paginate'])) { ?><div class="userpro-paginate bottom"><?php echo $users['paginate']; ?></div><?php } ?>
		
		<?php } // initial results off/on ?>
	
	</div>

</div>
