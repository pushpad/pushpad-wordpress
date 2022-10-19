<?php
class Pushpad_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'pushpad_widget',
			'Pushpad Button',
			array( 'description' => 'A button that allows visitors to subscribe to your notifications.' )
		);
	}

	/**
	 * Widget front-end
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$title = apply_filters( 'widget_title', $instance['widget-title'] );
		if ( ! empty( $title ) ) echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		echo '<p class="description">' . esc_html( $instance['widget-description'] ) . '</p>';
		echo '<div id="pushpad-subscribe"></div>';
		echo $args['after_widget'];
	}

	/**
	 * Widget back-end
	 */
	public function form( $instance ) {
		$title = isset( $instance['widget-title'] ) ? $instance['widget-title'] : 'Subscribe to notifications';
		$description = isset( $instance['widget-description'] ) ? $instance['widget-description'] : "We'll send you a notification when we publish something new.";
?>

<p>
	<label>Title:</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-title' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-title' ); ?>"
		type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
	<label>Description</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-description' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-description' ); ?>"
		type="text" value="<?php echo esc_attr( $description ); ?>" />
</p>

<?php
	}

	/**
	 * Function to update widget by replacing old instances with new
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['widget-title'] = ( ! empty( $new_instance['widget-title'] ) ) ? strip_tags( $new_instance['widget-title'] ) : '';
		$instance['widget-description'] = ( ! empty( $new_instance['widget-description'] ) ) ? strip_tags( $new_instance['widget-description'] ) : '';
		return $instance;
	}
}

/**
 * Register and load the widget
 */
function add_pushpad_widget() {
	register_widget( 'Pushpad_Widget' );
}
add_action( 'widgets_init', 'add_pushpad_widget' );
