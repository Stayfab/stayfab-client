<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Google Class
 *
 * Handles all google functions 
 *
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
if( !class_exists( 'WOO_Slg_Social_Google' ) ) {
	
	class WOO_Slg_Social_Google {
		
		var $google, $googleplus, $googleoauth2, $_google_user_cache;
		
		public function __construct(){
			
		}
		
		/**
		 * Include google Class
		 * 
		 * Handles to load google class
		 * 
		 * @package WooCommerce - Social Login
	 	 * @since 1.0.0
		 */
		public function woo_slg_load_google() {
			
			global $woo_slg_options;
			
			//google class declaration
			if( !empty( $woo_slg_options['woo_slg_enable_googleplus'] ) 
				&& !empty( $woo_slg_options['woo_slg_gp_client_id'] ) && !empty( $woo_slg_options['woo_slg_gp_client_secret'] ) ) {
				return true;
				
			} else {
				
				return false;
			}
		}
		
		/**
		 * Initialize API
		 * 
		 * Getting Initializes Google Plus API
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		public function woo_slg_initialize_google() {
		
			global $woo_slg_options;			
			//Google integration begins here
			// not isset state condition required, else google code executed when google called
			//and check wooslg is equal to google
			if( isset( $_GET['code'] ) && !isset( $_GET['state'] ) 
				&& isset( $_GET['wooslg'] ) && $_GET['wooslg'] == 'google' ) {
				
				//load google class
				$google = $this->woo_slg_load_google();
				
				//check google class is loaded
				if( !$google ) return false;
				
				
				$url = 'https://accounts.google.com/o/oauth2/token';
				$params = array(
					'code' => $_GET['code'],
					'client_id' => WOO_SLG_GP_CLIENT_ID,
					'client_secret' => WOO_SLG_GP_CLIENT_SECRET,
					'redirect_uri' => WOO_SLG_GP_REDIRECT_URL,
					'grant_type' => 'authorization_code'
				);
			
				$query		= http_build_query($params, '', '&');
				
				$wp_http_args	= array(
										'method'      => 'POST',
										'body'        => $query,
										'headers'     => 'Content-type: application/x-www-form-urlencoded',
										'cookies'     => array(),
								);
				
				$response		= wp_remote_request($url, $wp_http_args);
				$responseData	= wp_remote_retrieve_body( $response );
				
				if( is_wp_error( $response ) ) {
					$content = $response->get_error_message();
				} else {
					
					$responseData	= json_decode( $responseData );
					
					if( isset( $responseData->access_token ) && !empty( $responseData->access_token ) ) {
						$token	= $responseData->access_token;
						$userdata = $this->woo_slg_get_google_profile_data( $token );
						if( !empty( $userdata) ) {
							$_SESSION['woo_slg_google_user_cache']	= $userdata;
						}
					}
				}			
			}
		}
		
		/**
		 * Get Google User Data
		 * 
		 * Getting all the google+ connected user data
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		
		public function woo_slg_get_google_user_data() {
			
			$user_profile_data = '';
			
			$user_profile_data = isset( $_SESSION['woo_slg_google_user_cache'] ) ? $_SESSION['woo_slg_google_user_cache'] : '';
			return $user_profile_data;
		}
		
		/**
		 * Get Google Authorize URL
		 * 
		 * Getting Authentication URL connect with google+
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.0.0
		 */
		
		public function woo_slg_get_google_auth_url() {
		
			//load google class
			$google = $this->woo_slg_load_google();
			
			//check google class is loaded
			if( !$google ) return false;
		
			global $woo_slg_options;
			
			$authurl = '';			
			if( !empty( $woo_slg_options['woo_slg_enable_googleplus'] ) 
				&& !empty( $woo_slg_options['woo_slg_gp_client_id'] ) && !empty( $woo_slg_options['woo_slg_gp_client_secret'] ) )  {
					$url	=	'https://accounts.google.com/o/oauth2/auth';
					$params = array(
									'client_id' => WOO_SLG_GP_CLIENT_ID,
									'redirect_uri' => WOO_SLG_GP_REDIRECT_URL,
									'response_type' => 'code',
									'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
					);
					$authurl = $url.'?'.http_build_query($params, '', '&');
			}			
			return $authurl;
		}
		
		
		/**
		 * Get USerdata using access token
		 * 		 
		 * 
		 * @package WooCommerce - Social Login
		 * @since 1.4.8
		 */
		public function woo_slg_get_google_profile_data( $token ) {
			
			$profile_data	= array();
			
			if( isset( $token ) && !empty( $token ) ) { // if access token is not empty
				
				$url	= 'https://www.googleapis.com/oauth2/v1/userinfo';				
				$params =  array( 'access_token' => $token );				
				$queryurl = $url.'?'.http_build_query($params, '', '&');
								
				$wp_http_args	= array(
										'method'      => 'GET',
										'sslverify'   => false,
										'blocking'    => true,
										'cookies'     => array(),
										'httpversion' => '1.0',
								);
				
				$response		= wp_remote_request( $queryurl, $wp_http_args);				
				if ( !is_wp_error($response)) {
					
						$responseData = wp_remote_retrieve_body($response);
						$profile_data	= json_decode( $responseData );
				}
			}
			return apply_filters( 'woo_slg_get_google_profile_data', $profile_data, $token );
		}
	}
}
?>