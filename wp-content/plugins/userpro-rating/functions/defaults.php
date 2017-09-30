<?php

	/* get a global option */
	function userpro_rating_get_option( $option ) {
		$userpro_default_options = userpro_rating_default_options();
		$settings = get_option('userpro_rating');
		switch($option){
		
			default:
				if (isset($settings[$option])){
					return $settings[$option];
				} else {
					if(isset($userpro_default_options[$option]))
					return $userpro_default_options[$option];
				}
				break;
	
		}
	}
	
	/* set a global option */
	function userpro_rating_set_option($option, $newvalue){
		$settings = get_option('userpro_rating');
		$settings[$option] = $newvalue;
		update_option('userpro_rating', $settings);
	}
	
	/* default options */
	function userpro_rating_default_options(){
		$body = __('Hi there,','userpro-rating') . "\r\n\r\n";
		$body .= __('You have received a reply on your review.') . "\r\n\r\n";
		$body .= __('<a href="{USERPRO_REPLY_URL}">Click here</a>');

		$array['usepro_rating_roles_can_rate'] = '';
		$array['following_user'] = '0';
		$array['display_rating_on_post']='1';
		$array['userpro_rating_review_length'] = '255';
		$array['userpro_rating_review_show'] = 0;
		$array['usepro_rating_roles_can_rate'] = '';	
		$array['userpro_rating_envato_code']	= '';
		$array['enable_reviews'] = 1;
		$array['enable_reply'] = 0;
		$array['userpro_rating_reply_notification'] = 1;
		$array['reply_mail_s'] = __('Received Reply for Review');		
		$array['reply_mail_c'] = $body;

		if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();
		$roles = $wp_roles->get_names();
		foreach($roles as $k=>$v) {
			if ($k != 'administrator') {
				$array['usepro_rating_'.$k.'_can_rate'] = '';
			}
		}
		return apply_filters('userpro_rating_default_options_array', $array);
	}
