<?php
class Plugin_Pushpad_Widget extends WP_Widget {
	function __construct() {
		parent::__construct ( 
				// Base ID of your widget
				'pushpad_widget', 
				
				// Widget name will appear in UI
				'Plugin Pushpad Notification Widget', 
				
				// Widget description
				array (
						'description' => 'Widget to show pushpad button to send notification' 
				) );
	}
	
	/**
	 * Function Creating widget front-end
	 */
	public function widget($args, $instance) {
		$api_selected = get_option ( 'apiSelected' );
		$project_id = null;
		$token = null;
		if (isset ( $api_selected )) {
			if ($api_selected == 'simple') {
				$notification_settings = get_option ( 'simple_api_settings', array () );
				$project_id = $notification_settings ['project_id'];
				$token = $notification_settings ['token'];
			} elseif ($api_selected == 'custom') {
				$notification_settings = get_option ( 'custom_api_settings', array () );
				$project_id = $notification_settings ['custom_api_project_id'];
				$token = $notification_settings ['custom_api_token'];
			}
		}
		
		$title = apply_filters ( 'widget_title', $instance ['widget-title'] );
		
		echo $args ['before_widget'];
		if (! empty ( $title ))
			echo $args ['before_title'] . $title . $args ['after_title'];
		
		if ($token != null && $token != '' && $project_id != null && $project_id != '') {
			echo '<p class="description">' . $instance ['widget-description'] . '</p>';
			if ($api_selected == 'simple') {
				echo '<p class="button-container"><a class="button" target="_blank" href="https://pushpad.xyz/projects/' . $project_id . '/subscription/edit">' . $instance ['widget-button-text'] . '</a></p>';
			} else {
				echo '<button class="customSubscription"><a id="SubscribeCustomNotif"  style="color:white;" target="_blank" >' . $instance ['widget-button-text'] . '</a></button>';
			}
		} elseif (is_user_logged_in () && current_user_can ( 'manage_options' )) {
			echo 'Please insert AUTH Token and Project ID here --->.' . ' <a href="' . admin_url ( 'admin.php?page=plugin-pushpad-setting' ) . '"> Plugin Pushpad Notification Setting</a>';
		}
		
		echo $args ['after_widget'];
	}
	
	/**
	 * Function to create widget backend
	 *
	 * {@inheritDoc}
	 *
	 * @see WP_Widget::form()
	 */
	public function form($instance) {
		$title = isset ( $instance ['widget-title'] ) ? $instance ['widget-title'] : 'Latest Blog';
		$description = isset ( $instance ['widget-description'] ) ? $instance ['widget-description'] : 'We will send notification if any new blog will be published.';
		$button_text = isset ( $instance ['widget-button-text'] ) ? $instance ['widget-button-text'] : 'Send Notifications';
		?>
<p>
	<label>Add Title:</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-title' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-title' ); ?>"
		type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
	<label>Add Description</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-description' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-description' ); ?>"
		type="text" value="<?php echo esc_attr( $description ); ?>" />
</p>

<p>
	<label>Add Button Text:</label> <input class="widefat"
		id="<?php echo $this->get_field_name( 'widget-button-text' ); ?>"
		name="<?php echo $this->get_field_name( 'widget-button-text' ); ?>"
		type="text" value="<?php echo esc_attr( $button_text ); ?>" />
</p>
<?php
	}
	/**
	 * Function to update widget by replacing old instances with new
	 */
	public function update($new_instance, $old_instance) {
		$instance = array ();
		$instance ['widget-title'] = (! empty ( $new_instance ['widget-title'] )) ? strip_tags ( $new_instance ['widget-title'] ) : '';
		$instance ['widget-description'] = (! empty ( $new_instance ['widget-description'] )) ? strip_tags ( $new_instance ['widget-description'] ) : '';
		$instance ['widget-button-text'] = (! empty ( $new_instance ['widget-button-text'] )) ? strip_tags ( $new_instance ['widget-button-text'] ) : '';
		return $instance;
	}
}

/**
 * Register and load the widget
 */
function add_plugin_pushpad_widget() {
	register_widget ( 'Plugin_Pushpad_Widget' );
}
add_action ( 'widgets_init', 'add_plugin_pushpad_widget' );
?>