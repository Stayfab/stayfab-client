<?php
/**
 * Adds Foo_Widget widget.
 */
class topRating_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'topRating_Widget', // Base ID
			__('Top Members', 'userpro_rating'), // Name
			array( 'description' => _e( '', 'userpro_rating' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $userpro;
     	        echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
	
		if(isset($instance['no_user'])){
			$no_user = $instance['no_user'];
		}else{
			$no_user = 5;
		}
		
		if($instance['to_show']) {
			$to_show = $instance['to_show'];
		}else {
			$to_show = 'star';
		}
		
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
			if($to_show == 'score') {
					echo '<div class="user-score">'.round($score , 2).'</div>'; 
				}elseif($to_show == 'star') {
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
				
		<?php  } ?>
				</div>
			<?php 
		}
		echo '</div>';
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = _e( 'New title', 'text_domain' );
		}
		
		if(isset($instance['no_user'])){
			$no_user = $instance['no_user'];
		}else{
			$no_user = 6;
		}
		if(isset($instance['to_show'])) {
			$to_show = $instance['to_show'];
		}else {
			$to_show = 'star';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo  $title; ?>">
		</p>
		<p>
		<label><?php echo 'Number of Users to show'; ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'no_user' ); ?>" name="<?php echo $this->get_field_name( 'no_user' ); ?>" type="text" value="<?php echo  $no_user; ?>">
		</p>
		<p>
			<label>Show Stars</label>
			<input type="radio" id="<?php echo $this->get_field_id( 'to_show' ); ?>" name="<?php echo $this->get_field_name( 'to_show' ); ?>" value="star" <?php echo ($to_show == 'star' ) ? 'checked="checked"' : ''; ?>>
		</p>
		<p>
			<label>Show Score</label>
			<input type="radio" id="<?php echo $this->get_field_id( 'to_show' ); ?>" name="<?php echo $this->get_field_name( 'to_show' ); ?>" value="score" <?php echo ($to_show == 'score' ) ? 'checked="checked"' : ''; ?>>
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['no_user'] = ( ! empty( $new_instance['no_user'] ) ) ? strip_tags( $new_instance['no_user'] ) : '';
		$instance['to_show'] = ( ! empty( $new_instance['to_show'] ) ) ? strip_tags( $new_instance['to_show'] ) : '';

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_toprating_widget() {
	register_widget( 'topRating_Widget' );
}
add_action( 'widgets_init', 'register_toprating_widget' );
