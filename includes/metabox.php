<?php
/**
 * Function to add metabox in edit post page which is used to send notification if checkbox is checked
 */
function pushpad_metabox( $post ) {
	$pushpad_settings = get_option ( 'pushpad_settings' );

?>

	<input type="hidden" name="pushpad_metabox_form_data_available" value="1">
	<label><input name="pushpad_send_notification" type="checkbox" value="1" <?php if ( get_post_meta( $post->ID, 'pushpad_send_notification', true ) ) echo "checked"; ?>> Send push notifications</label>

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
 * Send the notification for a post if checkbox is checked
 */
function pushpad_save_post( $post_id, $post ) {
  if( 'inline-save' == $_POST['action'] )
    return;

	if ( ! current_user_can ( "edit_post", $post_id ) )
		return;

  $should_send = get_post_status ( $post_id ) != 'publish' ? isset ( $_POST ['pushpad_send_notification'] ) : false;

  update_post_meta ( $post_id, 'pushpad_send_notification', $should_send );
}
add_action ( 'save_post', 'pushpad_save_post', 10, 2 );

function pushpad_send_notification( $post_id ) {
  if( 'inline-save' == $_POST['action'] )
    return;

	$should_send = isset ( $_POST ['pushpad_metabox_form_data_available'] ) ? isset ( $_POST ['pushpad_send_notification'] ) : get_post_meta( $post_id, 'pushpad_send_notification', true );

  if ( ! $should_send )
  	return;

  $pushpad_settings = get_option ( 'pushpad_settings' );

  if ( !$pushpad_settings || empty( $pushpad_settings ['token'] ) || empty ( $pushpad_settings ['project_id'] ) )
  	return;

  $notification_title = html_entity_decode ( get_bloginfo ( 'name' ) );
  $notification_body = html_entity_decode ( get_the_title ( $post_id ) );
  $notification_url = get_permalink ( $post_id );

  Pushpad::$auth_token = $pushpad_settings ['token'];
  Pushpad::$project_id = $pushpad_settings ['project_id'];

  $notification = new Notification ( array (
  	'body' => $notification_body,
  	'title' => $notification_title,
  	'target_url' => $notification_url
  ) );

  try {
  	$notification->broadcast ();
  	update_option ( 'pushpad_delivery_result', array( 'status' => 'ok' ) );
    delete_post_meta( $post_id, 'pushpad_send_notification' );
  } catch (Exception $e) {
  	update_option ( 'pushpad_delivery_result', array( 'status' => 'error', 'message' => $e->getMessage() ) );
  }
}
add_action( 'publish_post', 'pushpad_send_notification', 10, 1 );
