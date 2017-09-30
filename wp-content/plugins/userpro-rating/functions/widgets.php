<?php

add_action( 'widgets_init', 'userpro_rating_widget' );


function userpro_rating_widget() {
	register_widget( 'USERPRO_TOP_POST' );
	
}

class USERPRO_TOP_POST extends WP_Widget {

	function __construct()  {
		$widget_ops = array( 'classname' => 'userpro_top_post', 'description' => __('Show top 5 Rated Post', 'userpro_rating') );

		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'userpro_top_post' );

		parent::__construct( 'userpro_top_post', __('Show Top 5 Rated Post', 'userpro_rating'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $userpro_rating;

		$before_title = $args['before_title'];
		$after_title = $args['after_title'];
		// hard excluded by post type
		
		$title = apply_filters('widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( $title )
			echo $before_title . $title . $after_title;
		echo top_rated_posts();
		echo $args['after_widget'];
			
	}

	//Update the widget

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}


	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Show Top 5 Rated Post', 'userpro_rating') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'userpro_rating'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

	<?php
	}
}
?>
