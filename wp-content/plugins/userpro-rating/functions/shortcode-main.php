<?php

	/* Add extension shortcodes */

	add_action('userpro_custom_template_hook', 'userpro_rt_shortcodes', 99 );

	function userpro_rt_shortcodes($args) {
		global $userpro;
		$default_args=array(
				'modal_profile_saved'				=> __('Your profile has been saved!','userpro'),
				'template' 							=> null,
				'max_width'							=> userpro_get_option('width'),
				'uploads_dir'						=> $userpro->get_uploads_url(),
				'default_avatar_male'				=> userpro_url . 'img/default_avatar_male.jpg',
				'default_avatar_female'				=> userpro_url . 'img/default_avatar_female.jpg',
				'layout'							=> userpro_get_option('layout'),
				'margin_top'						=> 0,
				'margin_bottom'						=> '30px',
				'align'								=> 'center',
				'skin'								=> userpro_get_option('skin'),
				'required_text'						=> __('This field is required','userpro'),
				'password_too_short'				=> __('Your password is too short','userpro'),
				'passwords_do_not_match'			=> __('Passwords do not match','userpro'),
				'password_not_strong'				=> __('Password is not strong enough','userpro'),
				'keep_one_section_open'				=> 0,
				'allow_sections'					=> 1,
				'permalink'							=> '',
				'field_icons'						=> userpro_get_option('field_icons'),
				'profile_thumb_size'				=> 80,
					
				'register_heading' 					=> __('Register an Account','userpro'),
				'register_side'						=> __('Already a member?','userpro'),
				'register_side_action'				=> 'login',
				'register_button_action'			=> 'login',
				'register_button_primary'			=> __('Register','userpro'),
				'register_button_secondary'			=> __('Login','userpro'),
				'register_group'					=> 'default',
				'register_redirect'					=> '',
				'type'								=> userpro_mu_get_option('multi_forms_default'),
					
				'login_heading' 					=> __('Login','userpro'),
				'login_side'						=> __('Forgot your password?','userpro'),
				'login_side_action'					=> 'reset',
				'login_button_action'				=> 'register',
				'login_button_primary'				=> __('Login','userpro'),
				'login_button_secondary'			=> __('Create an Account','userpro'),
				'login_group'						=> 'default',
				'login_redirect'					=> '',
				'rememberme'						=> 'true',
					
				'delete_heading'					=> __('Delete Profile','userpro'),
				'delete_side'						=> __('Undo, back to profile','userpro'),
				'delete_side_action'				=> 'view',
				'delete_button_action'				=> 'view',
				'delete_button_primary'				=> __('Confirm Deletion','userpro'),
				'delete_button_secondary'			=> __('Back to Profile','userpro'),
				'delete_group'						=> 'default',
					
				'reset_heading'						=> __('Reset Password','userpro'),
				'reset_side'						=> __('Back to Login','userpro'),
				'reset_side_action'					=> 'login',
				'reset_button_action'				=> 'change',
				'reset_button_primary'				=> __('Request Secret Key','userpro'),
				'reset_button_secondary'			=> __('Change your Password','userpro'),
				'reset_group'						=> 'default',
					
				'change_heading'					=> __('Change your Password','userpro'),
				'change_side'						=> __('Request New Key','userpro'),
				'change_side_action'				=> 'reset',
				'change_button_action'				=> 'reset',
				'change_button_primary'				=> __('Change my Password','userpro'),
				'change_button_secondary'			=> __('Do not have a secret key?','userpro'),
				'change_group'						=> 'default',
					
				'list_heading'						=> __('Latest Members','userpro'),
				'list_per_page'						=> 5,
				'list_sortby'						=> 'registered',
				'list_order'						=> 'desc',
				'list_users'						=> '',
				'list_group'						=> 'default',
				'list_thumb'						=> 50,
				'list_showthumb'					=> 1,
				'list_showsocial'					=> 1,
				'list_showbio'						=> 0,
				'list_verified'						=> 0,
				'list_relation'						=> 'or',
					
				'online_heading'					=> __('Who is online now','userpro'),
				'online_thumb'						=> 30,
				'online_showthumb'					=> 1,
				'online_showsocial'					=> 0,
				'online_showbio'					=> 0,
				'online_mini'						=> 1,
				'online_mode'						=> 'vertical',
					
				'edit_button_primary'				=> __('Save Changes','userpro'),
				'edit_group'						=> 'default',
					
				'view_group'						=> 'default',
					
				'social_target'						=> '_blank',
				'social_group'						=> 'default',
					
				'card_width'						=> '250px',
				'card_img_width'					=> '250',
				'card_showbio'						=> 1,
				'card_showsocial'					=> 1,
					
				'link_target'						=> '_blank',
					
				'error_heading'						=> __('An error has occured','userpro'),
					
				'memberlist_table'					=> 0,
				'memberlist_table_columns'			=> 'user_id,picture,name,country,gender,role,email_user,message_user',
				'show_on_mobile'					=> 'picture,name,country,email_user,message_user',
					
				'memberlist_v2'						=> 1,
				'memberlist_v2_pic_size'			=> '86',
				'memberlist_v2_fields'				=> 'age,gender,country',
				'memberlist_v2_bio'					=> 1,
				'memberlist_v2_showbadges'			=> 1,
				'memberlist_v2_showname'			=> 1,
				'memberlist_v2_showsocial'			=> 1,
					
				'memberlist_pic_size'				=> '120',
				'memberlist_pic_topspace'			=> '15',
				'memberlist_pic_sidespace'			=> '30',
				'memberlist_pic_rounded'			=> 1,
				'memberlist_width'					=> '100%',
				'memberlist_paginate'				=> 1,
				'memberlist_paginate_top'			=> 1,
				'memberlist_paginate_bottom' 		=> 1,
				'memberlist_show_name'				=> 1,
				'memberlist_popup_view'				=> 0,
				'memberlist_withavatar'				=> 0,
				'memberlist_verified'				=> 0,
				'memberlist_filters'				=> '',
				'memberlist_default_search'			=> 1,
				'per_page'							=> 12,
				'sortby'							=> 'registered',
				'order'								=> 'desc',
				'relation'							=> 'and',
				'search'							=> 1,
				'exclude'							=> '',
					
				'show_social'						=> 1,
					
				'registration_closed_side'			=> __('Existing member? login','userpro'),
				'registration_closed_side_action'	=> 'login',
					
				'facebook_redirect'					=> 'profile',
					
				'logout_redirect'					=> '',
					
				'post_paginate'						=> 1,
				'postsbyuser_num'					=> '12',
				'postsbyuser_types'					=> 'post',
				'postsbyuser_mode'					=> 'grid',
				'postsbyuser_thumb'					=> 50,
				'postsbyuser_showthumb'				=> 1,
				'postsbyuser_taxonomy'				=> 'category',
				'postsbyuser_category'				=> '',
				'following_per_page'                => '4',
				'following_paginate'				=> '1',
				'followers_per_page'                =>'4',
				'followers_paginate'				=> '1',
				'publish_heading'					=> __('Add a New Post','userpro'),
				'publish_button_primary'			=> __('Publish','userpro'),
		);
		foreach ($default_args as $key => $val) {
			if(isset($args[$key])) {
				$$key = $args[$key];
			}else {
				$$key = $val;
			}
		}
		$i = rand(1, 1000);
		global $users;
		if ($args['template'] == 'rating') {
			if(isset($args['topmembers'])){
				$users = get_users(array('fields' => array( 'ID' )));
				$all_score = array();
				$topMember = array();
				foreach($users as $user) : $user_id = $user->ID;
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
				arsort($topMember , SORT_NUMERIC);
				include_once(UPR_PLUGIN_DIR.'templates/rating_topmemberlist.php');
			}elseif(is_user_logged_in()){
				$users = userpro_memberlist_loop($args);
				include_once(UPR_PLUGIN_DIR.'templates/rating_memberlist.php');
			}else{
				echo 'You Are not allowed to access this page. Please login to access It.';
			}
		}elseif ($args['template'] == 'reviews'){
			$user_id = userpro_get_view_user( get_query_var('up_username') );
			$user_reviews = get_user_meta($user_id , 'user_reviews');
			include_once(UPR_PLUGIN_DIR.'templates/review.php');
		}
	}
