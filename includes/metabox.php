<?php
/**
 * Function to add metabox in edit post page which is used to send notification if checkbox is checked
 */
function pushpad_metabox() {
	$pushpad_settings = get_option ( 'pushpad_settings' );

?>

	<label><input name="autosend_notification" type="checkbox" value="autosend_notification"> Send Notification</label>

<?php
	if ( !$pushpad_settings || empty( $pushpad_settings ['token'] ) || empty ( $pushpad_settings ['project_id'] ) ) {
		echo '<p>You need to configure the plugin to use Pushpad: ' . ' <a href="' . admin_url ( 'admin.php?page=pushpad-settings' ) . '">settings</a></p>';
		return;
	}
}

/**
 * Function to add meta box
 */
function create_pushpad_metabox() {
	add_meta_box ( 'pushpad-metabox', 'Pushpad', 'pushpad_metabox', "post", "side", "high" );
}
add_action ( 'add_meta_boxes', 'create_pushpad_metabox' );

/**
 * Function to auto-send notification while saving post if checkbox is checked
 */
function autosend_notification( $post_id, $post ) {
	if ( ! current_user_can ( "edit_post", $post_id ) )
		return;

	if ( wp_is_post_revision( $post_id ) )
		return;

	if ( defined( "DOING_AUTOSAVE" ) && DOING_AUTOSAVE )
		return;

	$slug = "post";
	if ( $slug != $post->post_type )
		return;

	if ( ! isset ( $_POST ['autosend_notification'] ) )
		return;

	$pushpad_settings = get_option ( 'pushpad_settings' );

	if ( !$pushpad_settings || empty( $pushpad_settings ['token'] ) || empty ( $pushpad_settings ['project_id'] ) )
		return;

	$notification_title = get_bloginfo ( 'name' );
	$notification_body = get_the_title ( $post_id );
	$notification_url = get_permalink ( $post_id );

	$project_id = $notification_settings ['project_id'];
	$token = $notification_settings ['token'];

	if ( strlen( $notification_title ) > 30 ) {
		$notification_title = substr( $notification_title, 0, 27 ) . '...';
	}

	if ( strlen( $notification_body ) > 90 ) {
		$notification_body = substr( $notification_body, 0, 87 ) . '...';
	}

	Pushpad::$auth_token = $pushpad_settings ['token'];
	Pushpad::$project_id = $pushpad_settings ['project_id'];

	$notification = new Notification ( array (
		'body' => $notification_body,
		'title' => $notification_title,
		'target_url' => $notification_url
	) );

	try {
		$notification->broadcast ();
		add_filter('redirect_post_location', function( $location ) {
			return add_query_arg( 'pushpad-notice', 'delivery-ok', $location );
		});
	} catch (Exception $e) {
		add_filter('redirect_post_location', function( $location ) {
			return add_query_arg( 'pushpad-notice', 'delivery-error', $location );
		});
	}
}

add_action ( 'save_post', 'autosend_notification', null, 2 );
