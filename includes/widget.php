<?php
class Pushpad_Widget extends WP_Widget {
	function __construct() {
		parent::__construct (
			// Base ID of your widget
			'pushpad_widget',

			// Widget name will appear in UI
			'Pushpad Button',

			// Widget description
			array (
				'description' => 'A button that allows visitors to subscribe to your push notifications.'
			) );
	}

	/**
	 * Widget front-end
	 */
	public function widget( $args, $instance ) {
		$pushpad_settings = get_option ( 'pushpad_settings' );

		if (!$pushpad_settings) {
			if (is_user_logged_in () && current_user_can ( 'manage_options' )) {
				echo 'You need to configure the plugin to use Pushpad shortcode: ' . ' <a href="' . admin_url ( 'admin.php?page=pushpad-settings' ) . '">settings</a>';
			} 
			return;
		}

		$title = apply_filters ( 'widget_title', $instance ['widget-title'] );

		echo $args ['before_widget'];

		if ( ! empty ( $title ) )
			echo $args ['before_title'] . esc_html ( $title ) . $args ['after_title'];

		echo '<p class="description">' . esc_html ( $instance ['widget-description'] ) . '</p>';

		echo '<div class="pushpad-button-wrapper">';
		echo '<button class="pushpad-button" data-subscribe-text="' . esc_html ( $instance ['widget-subscribe-button-text'] ) . '" data-unsubscribe-text="' . esc_html ( $instance ['widget-unsubscribe-button-text'] ) . '">' . esc_html ( $instance ['widget-subscribe-button-text'] ) . '</button>';
		echo '</div>';

		echo $args ['after_widget'];
	}

	/**
	 * Widget back-end
	 */
	public function form( $instance ) {
		$title = isset ( $instance ['widget-title'] ) ? $instance ['widget-title'] : 'Push Notifications';
		$description = isset ( $instance ['widget-description'] ) ? $instance ['widget-description'] : "We'll send you a notification when we publish something new.";
		$button_text = isset ( $instance ['widget-subscribe-button-text'] ) ? $instance ['widget-subscribe-button-text'] : 'Subscribe';
		$unsubscribe_button_text = isset ( $instance ['widget-unsubscribe-button-text'] ) ? $instance ['widget-unsubscribe-button-text'] : 'Subscribed';
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

<p>
	<label>Subscribe Button Text:</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-subscribe-button-text' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-subscribe-button-text' ); ?>"
		type="text" value="<?php echo esc_attr( $button_text ); ?>" />
</p>

<p>
	<label>Unsubscribe Button Text:</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-unsubscribe-button-text' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-unsubscribe-button-text' ); ?>"
		type="text" value="<?php echo esc_attr( $unsubscribe_button_text ); ?>" />
</p>

<?php
	}

	/**
	 * Function to update widget by replacing old instances with new
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array ();
		$instance ['widget-title'] = ( ! empty ( $new_instance ['widget-title'] ) ) ? strip_tags( $new_instance ['widget-title'] ) : '';
		$instance ['widget-description'] = ( ! empty ( $new_instance ['widget-description'] ) ) ? strip_tags( $new_instance ['widget-description'] ) : '';
		$instance ['widget-subscribe-button-text'] = ( ! empty ( $new_instance ['widget-subscribe-button-text'] ) ) ? strip_tags( $new_instance ['widget-subscribe-button-text'] ) : '';
		$instance ['widget-unsubscribe-button-text'] = ( ! empty ( $new_instance ['widget-unsubscribe-button-text'] ) ) ? strip_tags( $new_instance ['widget-unsubscribe-button-text'] ) : '';
		return $instance;
	}
}

/**
 * Register and load the widget
 */
function add_pushpad_widget() {
	register_widget ( 'Pushpad_Widget' );
}
add_action ( 'widgets_init', 'add_pushpad_widget' );
