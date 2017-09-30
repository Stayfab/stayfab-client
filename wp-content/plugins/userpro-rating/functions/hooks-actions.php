<?php

add_filter('updb_default_options_array','userpro_rating_in_dashboard','11','1');
function userpro_rating_in_dashboard($array)
{
	
	$template_path= UPR_PLUGIN_DIR.'templates/';
	$olddata=$array['updb_available_widgets'];
	$newdata= array ('rating'=>array('title'=>'Rating', 'template_path'=>$template_path ));	
	if (is_array($olddata)){
    		$array['updb_available_widgets']=   array_merge($olddata,$newdata);
	}
	if(isset($array['updb_unused_widgets'])){
		$oldunsetwidgets=$array['updb_unused_widgets'];
	}
	$newunsetwidgets= array('rating');
	if (is_array($oldunsetwidgets)){
		$array['updb_unused_widgets']= array_merge($oldunsetwidgets,$newunsetwidgets);
	}

	return $array;
}
add_filter('updb_default_options_array','userpro_reviews_in_dashboard','12','1');
function userpro_reviews_in_dashboard($array)
{
	
	$template_path= UPR_PLUGIN_DIR.'templates/';
	$olddata=$array['updb_available_widgets'];
	$newdata= array ('reviews'=>array('title'=>'Review', 'template_path'=>$template_path ));
	if (is_array($olddata)){	
    		$array['updb_available_widgets'] = array_merge($olddata,$newdata);
	}

	$oldunsetwidgets=$array['updb_unused_widgets'];
	$newunsetwidgets= array('reviews');
	if (is_array($oldunsetwidgets)){
		$array['updb_unused_widgets'] = array_merge($oldunsetwidgets,$newunsetwidgets);
	}

	return $array;
}


add_filter('updb_default_options_array','userpro_top_rated_in_dashboard','13','1');
function userpro_top_rated_in_dashboard($array)
{

	$template_path= UPR_PLUGIN_DIR.'templates/';
	$olddata=$array['updb_available_widgets'];
	$newdata= array ('toprated'=>array('title'=>'5 Top Rated Posts', 'template_path'=>$template_path ));
	if (is_array($olddata)){
		$array['updb_available_widgets']=   array_merge($olddata,$newdata);
	}

	$oldunsetwidgets=$array['updb_unused_widgets'];
	$newunsetwidgets= array('toprated');
	if (is_array($oldunsetwidgets)){
		$array['updb_unused_widgets']= array_merge($oldunsetwidgets,$newunsetwidgets);
	}

	return $array;
}

add_filter('updb_default_options_array','userpro_top_rated_members_in_dashboard','14','1');

function userpro_top_rated_members_in_dashboard($array)
{

	$template_path= UPR_PLUGIN_DIR.'templates/';
	$olddata=$array['updb_available_widgets'];
	$newdata= array ('topratedmembers'=>array('title'=>'Top Rated Members', 'template_path'=>$template_path ));
	if (is_array($olddata)){
		$array['updb_available_widgets']=   array_merge($olddata,$newdata);
	}

	$oldunsetwidgets=$array['updb_unused_widgets'];
	$newunsetwidgets= array('topratedmembers');
	if (is_array($oldunsetwidgets)){
		$array['updb_unused_widgets']= array_merge($oldunsetwidgets,$newunsetwidgets);
	}

	return $array;
}

function top_rated_posts($args=array()){
		global $wpdb,$userpro;
		$query = $wpdb->prepare("SELECT post_id,meta_value FROM $wpdb->postmeta WHERE meta_key=%s order by cast(meta_value as unsigned) DESC LIMIT 5",'upr_rating_');
		$posts = $wpdb->get_results($query);
		$posts_arr = array();
		foreach( $posts as $post ){
			$rating = unserialize($post->meta_value);
			$rating = $rating[1];
			$posts_arr[$post->post_id] = $rating;	
		}
		arsort($posts_arr);
		$posts_count = count($posts_arr);
		$output = '';
		$output .= '<div class="wpb-coll-item">';
		foreach($posts_arr as $key=>$val)
		{
		$permalink = get_permalink($key);
		$thumbnail = $userpro->post_thumb($key, 50);;
		$title = get_the_title($key);
		$output .= '<div style="padding:0 0 4px"><div class="uci-thumb" style="width:50px"><a href="'.$permalink.'">'.$thumbnail.'</a></div>';
			$output .= '<div class="ur-content">';
			$output .= '<div class="ur-title"><a href="'.$permalink.'">'. $title . '</a></div>';
			$output .= '</div></div>';
		}
		$output.='</div>';
		
			return $output;
	}
	
if(userpro_rating_get_option('display_rating_on_post')=='1')
add_action('the_content', 'userpro_rate_post_content', 100);
function userpro_rate_post_content($content)
{

	global $post;
	if ($post->post_type == 'post') {
		$content.=userpro_rating_post("mypost".$post->ID);
	}
	return $content;
				

}
function userpro_rating_post($user_id){
	$content = '';
	$all_score = array();
	

	$all_score = get_option('upr_rating_'.$user_id);
	
	if(count($all_score)>0){
		if(is_array($all_score)){
			$score = array_sum($all_score)/count($all_score);
		}else{
			$score = 0;
		}
		update_user_meta($user_id,'user_rating',$score);
		$star_img_path = apply_filters('userpro_rating_stars_path', UPR_PLUGIN_URL.'images/');
		if( !empty( $all_score ) ){
			$all_score = '<div style="opacity:0.5;" id="rate-ratings"> </div>';
		}
		$data =  '<div class="rate-now"><span id="rate-'.$user_id.'"></span> <span id="rate-points"> '.round($score,1).'</span></div>'.$all_score.'
			<script type="text/javascript">jQuery(\'#rate-'.$user_id.'\').raty({
				halfShow : true,
				half: true,';
		
		if(is_rating_allowed($user_id) && is_user_logged_in()){
			$data .= 'click: function(score,evt){
						ratebyuid("'.$user_id.'",score);
					},';
		}else {
			$data .= 'readOnly: true,';
		}
		$data .= 'score: '.$score.',
				path: "'.$star_img_path.'"
				});</script>';

	}
	echo $data;
}



if(userpro_rating_get_option('enable_star_rating')==1)
{
	add_action('userpro_after_profile_img','userpro_rating_profile' ,10 , 1 );
	add_action('userpro_after_name_user_list', 'userpro_rating' , 10 , 1);
}
$check_rating = get_option('save_userpro_user_rating');
if(!$check_rating){

	add_action('init', 'save_userpro_user_rating' , 10 , 1);
	update_option('save_userpro_user_rating','1');
}

function save_userpro_user_rating(){

	$allusers = get_users();

	foreach ($allusers as $userid){

		$user_id = $userid->ID;
		$all_score = array();
		$all_score = get_option('upr_rating_'.$user_id);
		if(count($all_score)>0){
			if(is_array($all_score)){
				$score = array_sum($all_score)/count($all_score);
			}else{
				$score = 0;
			}
		}
		update_user_meta($user_id,'user_rating',$score);
	}
}

function userpro_rating($user_id){
	$content = '';
	$all_score = array();
	$all_score = get_option('upr_rating_'.$user_id);
	if(count($all_score)>0){
		if(is_array($all_score)){
			$score = array_sum($all_score)/count($all_score);
		}else{
			$score = 0;		
		}
		update_user_meta($user_id,'user_rating',$score);
		$star_img_path = apply_filters('userpro_rating_stars_path', UPR_PLUGIN_URL.'images/');
		$content =  '<div class="rate-now"><span id="rate-'.$user_id.'"></span> <span id="rate-points"> '.round($score,1).'ss</span></div><div id="count_user">'.round($score,1).'</div>
			<script type="text/javascript">jQuery(\'#rate-'.$user_id.'\').raty({
				halfShow : true,
				half: true,
				readOnly: true,
				score: '.$score.',
				path: "'.$star_img_path.'"
				});</script>';
	
	}
	echo $content;
}


function userpro_rating_profile($user_id){
	$content = '';
	$all_score = array();
	

	$all_score = get_option('upr_rating_'.$user_id);
	
	if(count($all_score)>0){
		if(is_array($all_score)){
			$score = array_sum($all_score)/count($all_score);
		}else{
			$score = 0;
		}
		update_user_meta($user_id,'user_rating',$score);
		$star_img_path = apply_filters('userpro_rating_stars_path', UPR_PLUGIN_URL.'images/');
		if( !empty( $all_score ) ){
			$all_score = '<div style="opacity:0.5;" id="rate-ratings">( '.count($all_score).'   ratings ) </div>';
		}
		$content =  '<div class="rate-now"><span id="rate-'.$user_id.'"></span> <span id="rate-points"> '.round($score,1).'</span></div>'.$all_score.'
			<script type="text/javascript">jQuery(\'#rate-'.$user_id.'\').raty({
				halfShow : true,
				half: true,';
		
		if(is_rating_allowed($user_id) && is_user_logged_in()){
		
			$content .= 'click: function(score,evt){
						ratebyuid("'.$user_id.'",score);
					},';
		}else {
			$content .= 'readOnly: true,';
		}
		$content .= 'score: '.$score.',
				path: "'.$star_img_path.'"
				});</script>';

	}
	echo $content;
}

function is_rating_allowed($pid) {
	if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
	$roles = $wp_roles->get_names();	
	$uid = get_current_user_id();
	$urole = get_current_user_role();
	$prole =  get_role_by_id($pid);
	$Resticted_roles = userpro_rating_get_option('usepro_rating_roles_can_rate');
	$Allowed_roles = userpro_rating_get_option('usepro_rating_'.$urole.'_can_rate');

	$following=0;
	$array = get_user_meta($pid,'_userpro_followers_ids');
	if(isset($array['0']))
	{	
		foreach($array['0'] as $key => $val)
		{	
		
		
			if(get_current_user_id()==$key )
			{
				$following=1;
		
			}
		}
	}

	if(userpro_rating_get_option('following_user')=='0')
	$following=1;
	
	if(is_array($Resticted_roles) && in_array($urole,$Resticted_roles))
	{
		return false;		
	}		
	if($Allowed_roles == null && $following=='1') {
		return true;
	}
	if ($Resticted_roles == null)
		$Resticted_roles = array();
	if(is_array($Allowed_roles) && in_array($prole, $Allowed_roles) && !in_array($urole, $Resticted_roles) && $following=='1'){
		return true;
	}else{
		return false;
	}
	return false;
}

function get_current_user_role() {
	global $wp_roles;
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	if(is_array($roles))
	$role = array_shift($roles);
	return isset($wp_roles->role_names[$role]) ? $role : false;
}

function get_role_by_id($uid) {
	global $wp_roles;
	$user = get_user_by('id', $uid);
	if(!empty($user))
	{
	$roles = $user->roles;
	if(is_array($roles))
	$role = array_shift($roles);
	return isset($wp_roles->role_names[$role]) ? $role : false;
	}
}

if(userpro_rating_get_option('enable_reviews')){
	add_action('userpro_after_profile_img','display_review_textarea' , 10 , 1);
}

function display_review_textarea($user_id){
	global $post;
	if(is_rating_allowed($user_id)) {
		if(isset($post->post_name) && $post->post_name == userpro_get_option('slug') && is_user_logged_in() && $user_id != get_current_user_id()) {
			$user_reviews = get_user_meta($user_id , 'user_reviews');
			if( !isset($user_reviews[0]) )
				$user_reviews[0] = array();
			if(array_key_exists(get_current_user_id() , $user_reviews[0])) {
				echo '<div>You have already submitted a review for this user.</div>';
				$page_id = get_option('userpro_review_page_link');
				$link = get_review_page_link($user_id);
			
			if($page_id)
				echo '<a href="'.$link.'">View Reviews</a>';
			}else{
				$link = get_review_page_link($user_id);
				echo '<a href="'.$link.'">Write Reviews</a>';
			}
		}
		else{
			$page_id = get_option('userpro_review_page_link');
				$link = get_review_page_link($user_id);
			if($page_id)
				echo '<a href="'.$link.'">View Reviews</a>';
		}
	}
}

add_action( 'save_post', 'userpro_is_review_shortcode' );

function userpro_is_review_shortcode($post_id){
	$post = get_post($post_id);
	$pattern = get_shortcode_regex();
	preg_match('/'.$pattern.'/s', $post->post_content, $matches);
	if (is_array($matches) && isset($matches[2]) && $matches[2] == 'userpro') {
		if(preg_match('/reviews/s', $matches[3])){
			update_option('userpro_review_page_link', $post_id);
			add_rewrite_rule("$post->post_name/([^/]+)/?",'index.php?page_id='.$post_id.'&up_username=$matches[1]', 'top');
			flush_rewrite_rules();
		}
	}
}

function get_review_page_link($user_id) {
	$page_id = get_option('userpro_review_page_link');
	$user = get_userdata( $user_id );
	$nice_url = userpro_get_option('permalink_type');
	if ($nice_url == 'ID') {
		$clean_user_login = $user_id;
	}
	if ($nice_url == 'username') {
		$clean_user_login = $user->user_login;
		$clean_user_login = str_replace(' ','-',$clean_user_login);
	}
	if ($nice_url == 'name'){
		$clean_user_login = $this->get_fullname_by_userid( $user_id );
	}
	if ($nice_url == 'display_name'){
		$clean_user_login = userpro_profile_data('display_name', $user_id);
		$clean_user_login = str_replace(' ','-',$clean_user_login);
		$clean_user_login = urlencode($clean_user_login);
	}
	if ( get_option('permalink_structure') == '' ) {
		$link = add_query_arg( 'up_username', $clean_user_login, get_page_link($page_id) );
	} else {
		$link = trailingslashit ( trailingslashit( get_page_link($page_id) ) . $clean_user_login );
	}
	return $link;
}

add_action('init' , 'userpro_rating_intial_setup');

function userpro_rating_intial_setup(){

		$page_id = get_option('userpro_review_page_link');
	if($page_id != '') {
		$post= get_post($page_id);
		add_rewrite_rule("$post->post_name/([^/]+)/?",'index.php?page_id='.$page_id.'&up_username=$matches[1]', 'top');
	}
}
add_filter( 'query_vars', 'userpro_rating_uid_query_var' );
function userpro_rating_uid_query_var( $query_vars ) {
	$query_vars[] = 'up_username';
	return $query_vars;
}
