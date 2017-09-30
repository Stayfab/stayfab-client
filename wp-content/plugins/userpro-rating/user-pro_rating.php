<?php
/*
Plugin Name: User Rating Add-on for UserPro
Plugin URI: http://codecanyon.net/item/user-rating-review-add-on-for-userpro/8943811
Description: Allow users to rate to each other.
Version: 3.6
Author: Deluxe Themes
Author URI: http://codecanyon.net/user/DeluxeThemes/portfolio?ref=DeluxeThemes
*/
?>
<?php
if(!defined('ABSPATH')) {exit;}

if(!class_exists('UPR_userPro_rating')) :

class UPR_userPro_rating {
	
	private static $_instance;
	
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct() {
		$this->define_constant();
		$this->includes();
		global $userpro;
		
		if(is_multisite()){
			require_once USERPRO_PLUGIN_DIR . "/functions/api.php";
		}
			require_once(UPR_PLUGIN_DIR.'/functions/defaults.php');		
			require_once(UPR_PLUGIN_DIR.'/admin/admin.php');		
		if(isset($userpro)){
			require_once(USERPRO_PLUGIN_DIR.'/functions/memberlist-functions.php');
			require_once(USERPRO_PLUGIN_DIR.'/functions/user-functions.php');
			require_once UPR_PLUGIN_DIR.'/functions/shortcode-main.php';
			require_once(UPR_PLUGIN_DIR.'/functions/hooks-actions.php');
			require_once(UPR_PLUGIN_DIR.'/functions/rating-widget.php');
			require_once(UPR_PLUGIN_DIR.'/functions/widgets.php');
		}else{
			add_action('admin_notices',array($this , 'UPR_userpro_activation_notices'));
			return 0;
		}
		add_action('wp_enqueue_scripts', array($this , 'load_styles') , 999);
		add_action('wp_enqueue_scripts', array($this,'load_assets') , 999);
		add_action('wp_head',array($this,'pluginname_ajaxurl'));
	}
	
	public function includes() {
		
		include_once(UPR_PLUGIN_DIR.'functions/class-upr-ajax.php');		

	}
	
	function pluginname_ajaxurl() {
		?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
	<?php
	}
	
	public function load_styles(){
		wp_register_style('ratenow_css', UPR_PLUGIN_URL.'assets/lib/jquery.raty.css');
		wp_register_style('rate-custom_css', UPR_PLUGIN_URL.'assets/rate-custom.css');
		wp_enqueue_style('ratenow_css');
		wp_enqueue_style('rate-custom_css');
	}
	
	public function load_assets(){
		wp_register_script('rate_jquery', UPR_PLUGIN_URL.'assets/lib/jquery.raty.js');
		wp_register_script('rate_js', UPR_PLUGIN_URL.'assets/rate.js');
		wp_enqueue_script('rate_jquery');
		wp_enqueue_script('rate_js');
		
	}
	
	public function define_constant(){
		
		define('USERPRO_PLUGIN_URL',WP_PLUGIN_URL.'/userpro/');
		define('USERPRO_PLUGIN_DIR',WP_PLUGIN_DIR.'/userpro/');
			
		define('UPR_PLUGIN_URL',WP_PLUGIN_URL.'/userpro-rating/');
		define('UPR_PLUGIN_DIR',WP_PLUGIN_DIR.'/userpro-rating/');
			
	}
	
	function UPR_userpro_activation_notices(){
		echo '<div class="error" role="alert"><p>Attention: User-Pro user Rating requires User-Pro to be installed and activated.</p></div>';
		return 0;
	}

}
endif;


function userpro_plugin_first() {
	// ensure path to this file is via main wp plugin path
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	if (in_array($this_plugin, $active_plugins)) { 
		unset($active_plugins[$this_plugin_key]);
		array_push($active_plugins , $this_plugin);

		update_option('active_plugins', $active_plugins);
	}
}

function userpro_rating_init() {
	load_plugin_textdomain('userpro-rating', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('init', 'userpro_rating_init');

add_action("activated_plugin", "userpro_plugin_first");

$UPR = UPR_userPro_rating::instance();



register_activation_hook(UPR_PLUGIN_DIR.'/user-pro_rating.php', 'UPR_activate_plugin');
function UPR_activate_plugin(){
	global $userpro;
	$UPR = UPR_userPro_rating::instance();
	if(isset($userpro)){
		
	}else{
		add_action('admin_notices',array($UPR , 'UPR_userpro_activation_notices'));
		return 0;
	}
}

require_once(dirname(__FILE__)."/admin/rating-updates-plugin.php");
new WPUpdatesPluginUpdater_1106( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));
?>
