<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 * 
 * Handles generic Admin functionality and AJAX requests.
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */
class WOO_Slg_Admin {
	
	var $model, $scripts, $render;
	
	public function __construct() {
		
		global $woo_slg_model, $woo_slg_scripts,$woo_slg_render;
		
		$this->model = $woo_slg_model;
		$this->scripts = $woo_slg_scripts;
		$this->render = $woo_slg_render;
	}
	
	/**
	 * Register All need admin menu page
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_admin_menu_pages() {
		 
		$woo_slg_social_login = add_submenu_page( 'woocommerce' , __( 'WooCommerce Social Login', 'wooslg' ), __( 'Social Login', 'wooslg' ), 'manage_options', 'woo-social-login', array( $this, 'woo_slg_social_login' ) ); 
	}
	
	/**
	 * Add Social Login Page
	 * 
	 * Handles to load social login 
	 * page to show social login register data
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function woo_slg_social_login() {
		
		include_once( WOO_SLG_ADMIN . '/forms/woo-social-login-data.php' );
	}
	
	/**
	 * Pop Up On Editor
	 *
	 * Includes the pop up on the WordPress editor
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.1.1
	 */
	public function wps_deals_shortcode_popup() {
		
		include_once( WOO_SLG_ADMIN . '/forms/woo-slg-admin-popup.php' );
	}
	
	public function wps_deals_admin_ssl_notice(){
		
		global $woo_slg_options;
		
		$woo_social_order = get_option( 'woo_social_order' );	
		
		foreach ( $woo_social_order as $provider ) {
			
			global ${"woo_slg_social_".$provider};
			
			if( $woo_slg_options['woo_slg_enable_'.$provider] == "yes" && isset(${"woo_slg_social_".$provider}->requires_ssl) && ${"woo_slg_social_".$provider}->requires_ssl) {			?>
			<div class="error">
        		<p><?php _e( 'WooCommerce Social Login : <b>'. $provider .'</b> requires SSL for authentication. ', 'wooslg' ); ?></p>
    		</div>
    
	<?php }
		}
	
	}
	
	/**
	 * Add 'Social Profiles' column to the Users admin table
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.4.7
	 */
	public function woo_slg_add_user_columns ( $columns ) {
		
		return wps_array_insert_after( $columns, 'email', array( 'wps_social_login_profiles' => __( 'Primary Social Profile', 'wooslg' ) ) );
	}
	
	/**
	 * Render social profile icons in the 'Social Profiles' column of the Users admin table
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.4.7
	 */
	public function woo_slg_user_column_values ( $output, $column_name, $user_id ) {
		
		if ( $column_name === 'wps_social_login_profiles' ) {
			
			$wps_user = get_user_by( 'id', $user_id );
			if ( !empty( $user_id ) && !empty( $wps_user ) ){
				
				$wps_user_soc_login_prof = get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );
				if ( !empty( $wps_user_soc_login_prof ) ) {
					
					$provider	= WOO_SLG_IMG_URL . "/" . $wps_user_soc_login_prof . "-provider.png";
					$output 	.= '<img src="' . $provider . '" >';
				} else {
					$output .= __( 'N/A', 'wooslg');
				}
			}
		}
		
		return $output;
	}
	
	
	/**
	 * Render social profile icons in the user edit screen
	 *
	 * @package WooCommerce - Social Login
	 * @since 1.4.8
	 */
	function woo_slg_show_user_profiles( $user ) { 
		
		$user_id = $user->ID;
		$primaryProfile = __( 'N/A', 'wooslg');
		
		$linked_profiles = $this->render->woo_get_user_social_linked_profiles();
		
		//get primary social account type if exist
		$primary_social		= get_user_meta( $user_id, 'woo_slg_social_user_connect_via', true );		
		if ( !empty( $primary_social ) ) {					
				$provider	= WOO_SLG_IMG_URL . "/" . $primary_social . "-provider.png";
				$primaryProfile	= '<img src="' . $provider . '" >';
		}		
		?>
		
		<h2><?php _e( 'Social Profiles', 'wooslg' ); ?></h2>
			<table class="form-table">
				<tr>
					<th> <?php _e( 'Primary Social Profile', 'wooslg' ); ?></th>
					<td><?php echo $primaryProfile; ?></td>
				</tr>
				<tr>
					<th> <?php _e( 'Linked Social Profiles', 'wooslg' ); ?></th>
					<td>
					<?php
					$woo_linked_profiles = 0;
					if( !empty( $linked_profiles ) ) {
						
						foreach ( $linked_profiles as $profile => $value ) {
							if( $profile != $primary_social ) {
								$provider		= WOO_SLG_IMG_URL . "/" . $profile . "-provider.png";
								echo '<img src="'.$provider.'" class="woo-slg-linked-provider-image">';
								$woo_linked_profiles++;
							}
						}
					} 
					if( $woo_linked_profiles == 0) {
						_e( 'N/A', 'wooslg');
					}
					?>
					</td>
				</tr>
			</table>
	<?php
	}
	
	/**
	 * Adding Hooks
	 * 
	 * @package WooCommerce - Social Login
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		//add admin menu pages
		add_action ( 'admin_menu', array( $this, 'woo_slg_admin_menu_pages' ) );
		
		// mark up for popup
		add_action( 'admin_footer-post.php', array( $this,'wps_deals_shortcode_popup' ) );
		add_action( 'admin_footer-post-new.php', array( $this,'wps_deals_shortcode_popup' ) );
		if(!is_ssl()){
			add_action( 'admin_notices', array( $this,'wps_deals_admin_ssl_notice' ) ); 
		}
		
		// add social profiles column to the Users admin table
		add_filter( 'manage_users_columns',       array( $this, 'woo_slg_add_user_columns' ), 11 );
		add_filter( 'manage_users_custom_column', array( $this, 'woo_slg_user_column_values' ), 11, 3 );
		
		add_action( 'show_user_profile', array( $this, 'woo_slg_show_user_profiles' ) );
		add_action( 'edit_user_profile', array( $this, 'woo_slg_show_user_profiles' ) );
	}
}