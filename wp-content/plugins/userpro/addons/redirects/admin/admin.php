<?php

class userpro_rd_admin {

	var $options;

	function __construct() {
	
		/* Plugin slug and version */
		$this->slug = 'userpro';
		$this->subslug = 'userpro-redirection';
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$this->plugin_data = get_plugin_data( userpro_rd_path . 'index.php', false, false);
		$this->version = $this->plugin_data['Version'];
		
		/* Priority actions */
		add_action('userpro_admin_menu_hook', array(&$this, 'add_menu'), 9);
		//add_action('admin_enqueue_scripts', array(&$this, 'add_styles'), 9);
		add_action('admin_print_scripts-userpro_page_userpro-redirection' , array(&$this, 'add_admin_scripts'));
		add_action('admin_print_styles-userpro_page_userpro-redirection' , array(&$this, 'add_admin_styles'));
		add_action('admin_head', array(&$this, 'admin_head'), 9 );
		add_action('admin_init', array(&$this, 'admin_init'), 9);
		
	}
	
	
	function add_admin_scripts(){
		wp_register_script('userpro_rd_custom', userpro_rd_url . 'admin/scripts/userpro-redirection.js');
		wp_enqueue_script('userpro_rd_custom');
	}
	
	function add_admin_styles(){
		wp_register_style('userpro_rd_custom', userpro_rd_url . 'admin/css/userpro-redirection.css');
		wp_enqueue_style('userpro_rd_custom');
	}
	
	
	function admin_init() {
	
		$this->tabs = array(
			'login_redirects' => __('Login Redirects','userpro'),
			'register_redirects' => __('Registration Redirects','userpro'),
			'help' => __('Help','userpro'),
		);
		$this->default_tab = 'login_redirects';
		
		$this->options = get_option('userpro_rd');
		if (!get_option('userpro_rd')) {
			update_option('userpro_rd', userpro_rd_default_options() );
		}
		
	}
	
	function get_pending_verify_requests_count(){
		$pending = get_option('userpro_verify_requests');
		if (is_array($pending) && count($pending) > 0){
			return '<span class="upadmin-bubble-new">'.count($pending).'</span>';
		}
	}
	
	function admin_head(){
	
	}

	function add_styles(){
	
	}
	
	function add_menu() {
		add_submenu_page( 'userpro', __('Custom Redirects','userpro'), __('Custom Redirects','userpro'), 'manage_options', 'userpro-redirection', array(&$this, 'admin_page') );
	}

	function admin_tabs( $current = null ) {
			$tabs = $this->tabs;
			$links = array();
			if ( isset ( $_GET['tab'] ) ) {
				$current = $_GET['tab'];
			} else {
				$current = $this->default_tab;
			}
			foreach( $tabs as $tab => $name ) :
				if ( $tab == $current ) :
					$links[] = "<a class='nav-tab nav-tab-active' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				else :
					$links[] = "<a class='nav-tab' href='?page=".$this->subslug."&tab=$tab'>$name</a>";
				endif;
			endforeach;
			foreach ( $links as $link )
				echo $link;
	}

	function get_tab_content() {
		$screen = get_current_screen();
		if( strstr($screen->id, $this->subslug ) ) {
			if ( isset ( $_GET['tab'] ) ) {
				$tab = $_GET['tab'];
			} else {
				$tab = $this->default_tab;
			}
			require_once userpro_rd_path.'admin/panels/'.$tab.'.php';
		}
	}
	
	function save() {
		
		/* other post fields */
		foreach($_POST as $key => $value) {
			if ($key != 'submit') {
				if (!is_array($_POST[$key])) {
					$this->options[$key] = esc_attr($_POST[$key]);
				} else {
					$this->options[$key] = $_POST[$key];
				}
			}
		}
		
		update_option('userpro_rd', $this->options);
		echo '<div class="updated"><p><strong>'.__('Settings saved.','userpro').'</strong></p></div>';
	}

	function reset() {
		update_option('userpro_rd', userpro_rd_default_options() );
		$this->options = array_merge( $this->options, userpro_rd_default_options() );
		echo '<div class="updated"><p><strong>'.__('Settings are reset to default.','userpro').'</strong></p></div>';
	}
	
	function rebuild_pages() {
		userpro_rd_setup($rebuild=1);
		echo '<div class="updated"><p><strong>'.__('Your plugin pages have been rebuilt successfully.','userpro').'</strong></p></div>';
	}

	function admin_page() {

		if (isset($_POST['submit'])) {
			$this->save();
		}

		if (isset($_POST['reset-options'])) {
			$this->reset();
		}
		
		if (isset($_POST['rebuild-pages'])) {
			$this->rebuild_pages();
		}
		
	?>
	
		<div class="wrap <?php echo $this->slug; ?>-admin">
			
			<?php userpro_admin_bar(); ?>
			
			<h2 class="nav-tab-wrapper"><?php $this->admin_tabs(); ?></h2>

			<div class="<?php echo $this->slug; ?>-admin-contain">
				
				<?php $this->get_tab_content(); ?>
				
				<div class="clear"></div>
				
			</div>
			
		</div>

	<?php }

}

$userpro_rd_admin = new userpro_rd_admin();
