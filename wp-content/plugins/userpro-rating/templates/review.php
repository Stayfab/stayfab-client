<div id="userpro-reviews" data-user-id="<?php echo $user_id; ?>" class="userpro userpro-<?php echo $i; ?> userpro-id-<?php echo $user_id; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>

	<a href="#" class="userpro-close-popup"><?php _e('Close','userpro'); ?></a>
	
	<div class="userpro-centered <?php if (isset($header_only)) { echo 'userpro-centered-header-only'; } ?>">
	
		<?php if ( userpro_get_option('lightbox') && userpro_get_option('profile_lightbox') ) { ?>
		<div class="userpro-profile-img" data-key="profilepicture"><a href="<?php echo $userpro->profile_photo_url($user_id); ?>" class="userpro-tip-fade lightview" data-lightview-caption="<?php echo $userpro->profile_photo_title( $user_id ); ?>" title="<?php _e('View member photo','userpro'); ?>"><?php echo get_avatar( $user_id, $profile_thumb_size ); ?></a></div>
		<?php } else { ?>
		<div class="userpro-profile-img" data-key="profilepicture"><a href="<?php echo $userpro->permalink($user_id); ?>" title="<?php _e('View Profile','userpro'); ?>"><?php echo get_avatar( $user_id, $profile_thumb_size ); ?></a></div>
		<?php } ?>
		<div class="userpro-profile-name">
				<a href="<?php echo $userpro->permalink($user_id); ?>"><?php echo userpro_profile_data('display_name', $user_id); ?></a><?php echo userpro_show_badges( $user_id ); ?>
			</div>
		<div class="userpro-clear"></div>		
	</div>
	<div class="userpro-profile-img-after">
			
	</div><br><br>
	<div class="userpro-body">
		<?php 
			$onecnt=0;
			$twocnt=0;
			$threecnt=0;
			$fourcnt=0;
			$fivecnt=0;
			$maxlength;
			global $userpro;
			global $flg;	
			$x = 0;	
			$enable_reviews = userpro_rating_get_option('enable_reviews');
			$users_reviews=get_option('upr_rating_'.$user_id);
			if(is_array($users_reviews))
			{
				foreach($users_reviews as $users_review)
				{	
					$urating=round($users_review, 0);
				
					switch($urating)
					{
					
						case 1 :$onecnt=$onecnt+1;
						break;
						case 2 :$twocnt=$twocnt+1;	
						break;		
						case 3 :$threecnt=$threecnt+1;
						break;
						case 4 :$fourcnt=$fourcnt+1;
						break;
						case 5 :$fivecnt=$fivecnt+1;
						break;
					}
			
				}
		      }
		$all_score = get_option('upr_rating_'.$user_id);
		$val1=$onecnt;$val2=$twocnt;$val3=$threecnt;$val4=$fourcnt;$val5=$fivecnt;
		$total=count($all_score);
		$onecnt=($onecnt*150)/$total."px";;
		$twocnt=($twocnt*150)/$total."px";;
		$threecnt=($threecnt*150)/$total."px";;
		$fourcnt=($fourcnt*150)/$total."px";;
		$fivecnt=($fivecnt*150)/$total."px";
			
			
    $height = "15px";

    $a_width = "150px"; 

    $b_width = "150px";
if(is_array($all_score)){
			$score = array_sum($all_score)/count($all_score);
		}else{
			$score = 0;		
		}
	echo "<div class='rating_star'>";
echo "<div class='rating_count'>";
echo round($score,1);

echo "</div>";

echo "</div>";
	 echo "<div class='rating_graph'>";

            echo "5 Star <div style='width:$a_width; height:$height; background: #F0F0F0 ;display:inline-block;'><div style='width:$fivecnt; height:$height; background:rgb(238, 212, 75); margin-bottom: 5px;'>$val5</div></div></br>";

            echo "4 Star <div style='width:$a_width; height:$height; background: #F0F0F0 ;display:inline-block;'><div style='width:$fourcnt; height:$height; background: rgb(238, 212, 75);margin-bottom: 5px;'>$val4</div></div></br>";
            echo "3 Star <div style='width:$a_width; height:$height; background: #F0F0F0 ;display:inline-block;'><div style='width:$threecnt; height:$height; background: rgb(238, 212, 75); margin-bottom: 5px;'>$val3</div></div></br>";

    	    echo "2 Star <div style='width:$a_width; height:$height; background: #F0F0F0 ;display:inline-block;'><div style='width:$twocnt; height:$height; background: rgb(238, 212, 75);'>$val2</div></div></br>";	
	    echo "1 Star <div style='width:$a_width; height:$height; background: #F0F0F0 ;display:inline-block;'><div style='width:$onecnt; height:$height; background: rgb(238, 212, 75);'>$val1</div></div>";		

echo "</div><br><br>";	
  
			if($enable_reviews){
			if(empty($user_reviews['0']) || $user_reviews == '') {
				$maxlength = (userpro_rating_get_option('userpro_rating_review_length')) ? userpro_rating_get_option('userpro_rating_review_length') : '255';
				$user = $userpro->get_member_by( get_query_var('up_username') );
				if(is_user_logged_in() && $flg==0 && isset($user->ID) && ($user->ID != get_current_user_id())){
					echo '<textarea name="user_review" id="user_review" maxlength="'.$maxlength.'" placeholder="Write a review" data-reciever_userid="'.$user_id.'" data-sender_userid="'.get_current_user_id().'"></textarea>
					<div style="display:none;" id="review_msg">Thank you for submitting the review</div>';		
					$flg = 1;
				}
			}else 
			foreach ($user_reviews[0] as $uid => $review) {
			?>
				<div class="comment_box_t" id="comment_box_<?php echo $uid; ?>" data-uid="<?php echo $uid; ?>">
					<div class="review_header">
						<div class="review_header_content">

							<div class="review-header_rating">
								<div class="h-mr1">
									<div class="rating-basic">
										<div class="rating-basic_star-rating">
										<?php 
										if(!empty($all_score)){
											foreach($all_score as $ud => $src){
												if($ud == $uid){
			$data =  '<div class="rate-now"><span id="rate-'.$uid.'"></span> </div>
						<script type="text/javascript">jQuery(\'#rate-'.$uid.'\').raty({
							halfShow : true,
							half: true,';
		
			$data .= 'readOnly: true,';
					$data .= 'score: '.$src.',
							path: "'.apply_filters('userpro_rating_stars_path', UPR_PLUGIN_URL.'images/').'"
							});</script>';
			echo $data;

												}
											}
										}
										?>
									    	</div>
									</div>
        							</div>
      							</div><!-- end of hearder rating -->

							<div class="review-header_reviewer">
								<p class="t-body -size-m h-m0">
								  By <a href="<?php echo $userpro->permalink($uid); ?>"><?php echo userpro_profile_data('display_name', $uid); ?></a>,
								  <span class="review-header_date">
								   <?php 
									$time = strtotime($review['r_dt']);
									echo event_occured_time($time).' ago';
								   ?>
								  </span>
								</p>
							</div><!-- end of hearder reviewer -->

						</div><!-- end of hearder content -->
					</div><!-- end of review_header -->

					<?php 
						if(!userpro_rating_get_option('userpro_rating_review_show')) {
					?>
<div class="review-box">
					<div class="left_b">
						<a href="<?php echo $userpro->profile_photo_url($uid); ?>" class="userpro-tip-fade lightview" data-lightview-caption="<?php echo $userpro->profile_photo_title( $user_id ); ?>" title="<?php _e('View member photo','userpro'); ?>">

						<?php echo get_avatar( $uid, $profile_thumb_size ); ?></a>
						<div class="clear"></div>
						<div style="text-align: center">
							<a class="name_t" href="<?php echo $userpro->permalink($uid); ?>"><?php echo userpro_profile_data('display_name', $uid); ?></a>
						</div>
				</div>
					<?php } ?>
				<div class="right_text" id="review_sent_<?php echo $uid?>">
				<p id="review_<?php echo $uid; ?>"><?php echo $review['val']; ?></p></div>
</div>
				<div class="clear"></div>
				<?php 
		$user_reviews = get_user_meta($user_id , 'user_reviews');
	$current_user = wp_get_current_user();

/*reply review start*/
$user_reply = get_user_meta($user_id , 'user_reply' ,true);

	if( is_user_logged_in() && (isset($user_reviews)) ){
		if( !isset($user_reply[$uid]) && (userpro_rating_get_option('enable_reply')) && (get_current_user_id() !=$uid) &&  get_current_user_id() == $user_id)
		{	
	?>
		<textarea name="reply" placeholder="Write a reply" class="user_reply" data-sender_userid="<?php echo $uid; ?>" data-reciever_userid="<?php echo $user_id; ?>"></textarea>
		<div style="display:none;" class="blank_reply_msg">Please enter a reply</div>
		<div><a class="userpro-button secondary reply_button"><i>Reply</i></a> </div>
	<?php
		}
	}
		if( isset($user_reply[$uid]) ){
		?>
			<div class="reply_box_t" id="reply_box_<?php echo $uid; ?>" data-uid="<?php echo $uid; ?>">
				<div class="reply_media">
					<?php 
						if(!userpro_rating_get_option('userpro_rating_review_show')) {
					?>
					<div class="left_b">
						<a href="<?php echo $userpro->profile_photo_url($user_id); ?>" class="userpro-tip-fade lightview" data-lightview-caption="<?php echo $userpro->profile_photo_title( $user_id ); ?>" title="<?php _e('View member photo','userpro'); ?>">
						<?php echo get_avatar( $user_id, $profile_thumb_size ); ?>
						</a>
						<div class="clear"></div>
						<div style="text-align: center">
							<a class="name_t" href="<?php echo $userpro->permalink($user_id); ?>"><?php echo userpro_profile_data('display_name', $user_id); ?></a>
						</div>
					</div>
					<div class="right_text" id="review_sent_<?php echo $uid?>">
						<h6>Reply</h6>
						<p id="review_<?php echo $uid; ?>"><?php echo $user_reply[$uid]; ?></p>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		<?php
			}
		}
/*reply review end*/

$current_user = wp_get_current_user();
		if(in_array('administrator',$current_user->roles) ){
				?>
					<div style="float:right; margin-top: 2%;"><a href="#" class="userpro-button secondary" onclick="edit_review(<?php echo $uid;?> , <?php echo $user_id; ?>)"><i>Edit</i></a> <a href="#" class="userpro-button secondary" onclick="delete_review(<?php echo $uid; ?>)"><i>Delete</i></a></div>
				<?php 
				}
				?>
				</div>
		<?php
		}
		if( !isset($user_reviews[0]) )
			$user_reviews[0] = array();
		if(!array_key_exists(get_current_user_id() , $user_reviews[0])) {
			$maxlength = (userpro_rating_get_option('userpro_rating_review_length')) ? userpro_rating_get_option('userpro_rating_review_length') : '255';
			$user = $userpro->get_member_by( get_query_var('up_username') );
			if(is_user_logged_in() && $flg==0 && isset($user->ID) && ($user->ID != get_current_user_id())){ 
				echo '<textarea name="user_review" id="user_review" maxlength="'.$maxlength.'" placeholder="Write a review" data-reciever_userid="'.$user_id.'" data-sender_userid="'.get_current_user_id().'"></textarea>
					<div style="display:none;" id="review_msg">Thank you for submitting the review</div>';
				$flg = 1;
			}
		}
	}	?>
	</div>
</div>
<?php
function event_occured_time ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
?>
