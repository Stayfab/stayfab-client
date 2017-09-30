<div class="updb-widget-style">
	<div class="updb-basic-info"><?php _e( '5 Top Rated Members', 'userpro-dashboard' );?></div>
<div class="updb-view-profile-details"><br>

<?php 
 function top_rated_members() {
	global $userpro;
    
	$no_user = 5;
	$to_show = 'star';
    $users = get_users(array('fields' => array( 'ID' )));

	 
	$topMember = array();
	foreach($users as $user) : $user_id = $user->ID;
	
	$all_score = array();
	$all_score = get_option('upr_rating_'.$user_id);

	if(count($all_score)>0){
		if(is_array($all_score)){
			$topMember[$user_id] = array_sum($all_score)/count($all_score);
		}else{
			$topMember[$user_id] = 0;
		}
	}else {
		$topMember[$user_id] = 0;
	}
	endforeach;
	$index = 0;
	arsort($topMember , SORT_NUMERIC);
	echo '<div class="top-user-rating">';
	foreach ($topMember as $user_id=>$score){
		if($index >= $no_user){
			break;
		}else{
			$index++;
		}
	
		$user = get_userdata($user_id);
	
		echo '<div style="min-height:40px;">
					<div class="display-name">
						<a href="'.$userpro->permalink($user_id).'">'.$user->display_name.'</a>
					</div>';
	
			
					echo '<div class="user-score">
								<div class="rate-now">
									<div id="widget-rate-'.$user_id.'">
									</div>
								</div>
						  </div>';

			$star_img_path = apply_filters('userpro_rating_stars_path', UPR_PLUGIN_URL.'images/');
				
			?>
				
			<script type="text/javascript">jQuery('#widget-rate-<?php echo $user_id; ?>').raty({
				halfShow : true,
				half: true,
				readOnly: true,
				score: <?php echo (isset($score)) ? $score : 0; ?>,
				path: "<?php echo $star_img_path; ?>"
				});</script> 
				
	
				</div>
			<?php 
		}
		echo '</div>';
	
	}


?>
		<?php echo top_rated_members();?>
</div>
</div>