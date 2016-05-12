<?php
function plugin_pushpad_shortcode($atts) {
	$atts = shortcode_atts ( array (
			'description' => 'If something new added, you will get notification for the same',
			'button_text' => 'Send Notification' 
	), $atts, 'send-notification-button' );
	
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
	
	$output = '';
	if ($token != null && $token != '' && $project_id != null && $project_id != '') {
		$output = '<div class="button-container">';
		$output .= '<p class="description">' . $atts ['description'] . '</p>';
		if ($api_selected == 'simple') {
			$output .= '<p class="button-container"><a class="button" target="_blank" href="https://pushpad.xyz/projects/' . $project_id . '/subscription/edit">' . $atts ['button_text'] . '</a></p>';
		} else {
			$output .= '<button class="customSubscription"><a id="SubscribeCustomNotif"  style="color:white;" target="_blank" >' . $atts ['button_text'] . '</a></button>';
		}
		$output .= '</div>';
	} elseif (is_user_logged_in () && current_user_can ( 'manage_options' )) {
		$output = 'Please insert AUTH Token and Project ID to send notification subscription.' . ' <a href="' . admin_url ( 'admin.php?page=plugin-pushpad-setting' ) . '">' . 'Go to Settings.' . '</a>';
	}
	
	return $output;
}

add_shortcode ( 'send-notification-button', 'plugin_pushpad_shortcode' );

?>
