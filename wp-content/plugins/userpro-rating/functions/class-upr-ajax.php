<?php

if(!defined('ABSPATH')) {exit;}

if(!class_exists('UPR_Ajax')) :

class UPR_Ajax {
	
	
	public function __construct() {
		$events = array(
				'ratebyuid' => true ,
				'review_submit'	=> true ,
				'review_delete'	=> true , 
				'reply_submit'	=> true ,
			);
		foreach ($events as $event => $nopriv) {
			add_action('wp_ajax_upr-'.$event, array($this, $event));
			if($nopriv) {
				add_action('wp_ajax_nopriv_upr-'.$event, array($this, $event));
			}
		}
	}
	
	public function ratebyuid() {
		$rating = get_option('upr_rating_'.$_POST['uid']);
		if(!$rating){
			$rating[get_current_user_id()] = $_POST['score'];
			add_option('upr_rating_'.$_POST['uid'],$rating);
			if(strstr($_POST['uid'],"mypost"))
			{
				$post_id=str_replace('mypost','',$_POST['uid']);
				update_post_meta($post_id,"upr_rating_",$rating);
			}
		}else{
			$rating[get_current_user_id()] = $_POST['score'];
			update_option('upr_rating_'.$_POST['uid'],$rating);
				
			if(strstr($_POST['uid'],'mypost'))
			{
				$post_id=str_replace('mypost','',$_POST['uid']);
				update_post_meta($post_id,"upr_rating_",$rating);
				
			}
		}
		die();
		
	}
	
	public function review_delete() {
		$sender_uid = $_POST['sender_uid'];
		$user_id = $_POST['user_id'];

		$user_reviews = get_user_meta($user_id,'user_reviews' , true);
		$user_reply = get_user_meta($user_id,'user_reply' , true);

		unset($user_reviews[$sender_uid]);
		if(isset($sender_uid)){
			unset($user_reply[$sender_uid]);
			update_user_meta($user_id , 'user_reply', $user_reply);
		}

		update_user_meta($user_id , 'user_reviews', $user_reviews);


		echo $sender_uid;
		die();
	}
	
	public function review_submit() {
		$review_dt = current_time( 'mysql' ); 
		$user_reviews = array();
		$user_reviews = get_user_meta($_POST['reciever_userid'] , 'user_reviews' ,true);
		$user_reviews[$_POST['sender_userid']] = array('val' => $_POST['review'],'r_dt' => $review_dt);
		update_user_meta($_POST['reciever_userid'] , 'user_reviews', $user_reviews);
		
		if( userpro_rating_get_option( 'userpro_rating_review_notification' )){
			global $userpro;
				$user = get_userdata($_POST['reciever_userid']);
				$display_name = ucfirst(userpro_profile_data('display_name', $_POST['sender_userid']));
			        $review_link = get_review_page_link($_POST['reciever_userid']);
				$subject = sprintf(__('%s has reviewed your profile!','userpro-rating'), $display_name);
			
				// message
				$body = __('Hi there,','userpro-rating') . "<br>";
				$body .= sprintf(__('%s has commented on your profile.','userpro-rating'), $display_name) . "<br>";
				$body .= sprintf(__('<a href='.$review_link.'>Click here to see the reviews.</a>','userpro-rating')) . "<br>";
				
			
				$headers = 'From: '.userpro_get_option('mail_from_name').' <'.userpro_get_option('mail_from').'>' . "\r\n";
				$headers .= "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
				wp_mail( $user->user_email , $subject, $body, $headers );
		}
	}

	public function reply_submit() {
		$reply_dt = current_time( 'mysql' );
		$user_reply = get_user_meta($_POST['reply_giver_userid'] , 'user_reply' ,true);

		$reply_receiver_userid = $_POST['reply_reciever_userid'];
		$reply_giver_userid = $_POST['reply_giver_userid'];
		$user_reply[$reply_receiver_userid] = $_POST['reply'];

		update_user_meta($reply_giver_userid , 'user_reply', $user_reply);
		
		if( userpro_rating_get_option( 'userpro_rating_reply_notification' )){

			$user = get_userdata($_POST['reply_reciever_userid']);
			$display_name = ucfirst(userpro_profile_data('display_name', $_POST['reply_giver_userid']));

		        $reply_link = get_review_page_link($_POST['reply_reciever_userid']);

			$subject = userpro_rating_get_option('reply_mail_s');

			// message
			$body = nl2br(userpro_rating_get_option('reply_mail_c'));
			$search = '{USERPRO_REPLY_URL}';
			$message = str_replace( $search, $reply_link, $body );
		
			$headers = 'From: '.userpro_get_option('mail_from_name').' <'.userpro_get_option('mail_from').'>' . "\r\n";
			$headers .= "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			wp_mail( $user->user_email , $subject, $message, $headers );
		}
	}
}

endif;
new UPR_Ajax();
?>
