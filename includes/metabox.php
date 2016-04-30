<?php
/** 
 * Function to add metabox in edit post page which is used to autosend notification if checkbox is checked 
 */
function plugin_pushpad_metabox() {
	$validate_simple_api_credentials = 'disabled';
	$validate_custom_api_credentials = 'disabled';
	
	$api_selected = get_option ( 'apiSelected' );
	$project_id = null;
	$token = null;
	if (isset ( $api_selected )) {
		if ($api_selected == 'simple') {
			$simple_api_credentials = get_option ( 'simple_api_settings', array () );
			if (isset ( $simple_api_credentials ['token'] ) && isset ( $simple_api_credentials ['project_id'] ) && $simple_api_credentials ['token'] != '' && $simple_api_credentials ['project_id'] != '') {
				$validate_simple_api_credentials = null;
			}
			echo '<input name="autosend_notification" type="checkbox"	value="autosend_notification"' . $validate_simple_api_credentials . '>
                  <label>Auto Send Notification</label><br><br>';
			if ($validate_simple_api_credentials != null) {
				echo '<em><strong>Note: Please set valid auth token and project id For Simple api</strong></em>';
			}
		} elseif ($api_selected == 'custom') {
			$custom_api_credentials = get_option ( 'custom_api_settings', array () );
			if (isset ( $custom_api_credentials ['custom_api_token'] ) && isset ( $custom_api_credentials ['custom_api_project_id'] ) && $custom_api_credentials ['custom_api_token'] != '' && $custom_api_credentials ['custom_api_project_id'] != '') {
				$validate_custom_api_credentials = null;
			}
			echo '<input name="custom_autosend_notification" type="checkbox"	value="custom_autosend_notification"' . $validate_custom_api_credentials . '>
                  <label>Auto Send Notification</label><br><br>';
			if ($validate_custom_api_credentials != null) {
				echo '<em><strong>Note: Please set valid auth token and project id For Custom api</strong></em>';
			}
		} else {
			echo '<em><strong>Note: Please select api and set valid auth token and project id for same</strong></em>';
		}
	}
}

/**
 * function to add meta box
 */
function create_plugin_pushpad_metabox() {
	add_meta_box ( 'plugin-pushpad-metabox', 'Pluging Pushpad Metabox', 'plugin_pushpad_metabox', "post", "side", "high" );
}
add_action ( 'add_meta_boxes', 'create_plugin_pushpad_metabox' );

/**
 * Function to autosend notification while saving post , if checkbox is checked.
 */
function autosend_notification($post_id, $post) {
	if (! current_user_can ( "edit_post", $post_id ))
		return $post_id;
	
	if (defined ( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE)
		return $post_id;
	
	$slug = "post";
	if ($slug != $post->post_type)
		return $post_id;
	
	if (! isset ( $_POST ['autosend_notification'] ) && ! isset ( $_POST ['custom_autosend_notification'] )) {
		return $post_id;
	}
	
	$notification_title = get_bloginfo ( 'name' );
	$notification_url = get_permalink ( $post_id );
	$api_selected = get_option ( 'apiSelected' );
	$project_id = null;
	$token = null;
	if (isset ( $api_selected )) {
		if ($api_selected == 'simple') {
			$notification_settings = get_option ( 'simple_api_settings', array () );
			$notification_title = $notification_settings ['notification_title'];
			$project_id = $notification_settings ['project_id'];
			$token = $notification_settings ['token'];
		} elseif ($api_selected == 'custom') {
			$notification_settings = get_option ( 'custom_api_settings', array () );
			$notification_title = $notification_settings ['notification_title'];
			$project_id = $notification_settings ['custom_api_project_id'];
			$token = $notification_settings ['custom_api_token'];
		}
	}
	
	if ($token == null && $token == '' && $project_id == null && $project_id == '') {
		return $post_id;
	}
	$notification_body = get_the_title ( $post_id );
	
	if (strlen ( $notification_body ) > 80) {
		$notification_body = substr ( $notification_body, 0, 80 ) . '...';
	} elseif (empty ( $notification_body )) {
		$notification_body = get_bloginfo ( 'name' );
	}
	
	if (strlen ( $notification_title ) > 30) {
		$notification_title = substr ( $notification_title, 0, 30 ) . '...';
	}
	
	$notitfication_args = array (
			'body' => $notification_body,
			'title' => $notification_title,
			'target_url' => $notification_url 
	);
	
	$notification = new Notification ( $notitfication_args );
	$notification->broadcast ();
}

add_action ( 'save_post', 'autosend_notification', null, 2 );
?>
