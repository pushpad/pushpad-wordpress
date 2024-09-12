<?php
/*
 * Plugin Name: Pushpad - Web Push Notifications
 * Plugin URI: https://pushpad.xyz/docs/wordpress
 * Description: Pushpad is the easiest way to add push notifications to your website.
 * Version: 2.1.2
 * Author: Pushpad
 * Author URI: https://pushpad.xyz
 * Text Domain: pushpad
 */

include plugin_dir_path( __FILE__ ) . '/admin/pushpad-settings.php';
include plugin_dir_path( __FILE__ ) . '/includes/widget.php';
include plugin_dir_path( __FILE__ ) . '/includes/shortcode.php';
include plugin_dir_path( __FILE__ ) . '/includes/metabox.php';
require_once plugin_dir_path( __FILE__ ) . '/pushpad/pushpad.php';
require_once plugin_dir_path( __FILE__ ) . '/pushpad/notification.php';

function pushpad_get_settings() {
	$default_settings = array(
		'token' => '',
		'project_id' => ''
	);
	$settings = get_option( 'pushpad_settings', array() );
	return wp_parse_args( $settings, $default_settings );
}

function pushpad_admin_pages() {
	add_submenu_page( 'options-general.php', 'Pushpad', 'Pushpad', 'manage_options', 'pushpad-settings', 'pushpad_settings' );
}
add_action( 'admin_menu', 'pushpad_admin_pages' );

function pushpad_add_wp_head() {
	$pushpad_settings = pushpad_get_settings();
	if ( !isset( $pushpad_settings["project_id"] ) ) return;
?>

<script>
	(function(p,u,s,h,x){p.pushpad=p.pushpad||function(){(p.pushpad.q=p.pushpad.q||[]).push(arguments)};h=u.getElementsByTagName('head')[0];x=u.createElement('script');x.async=1;x.src=s;h.appendChild(x);})(window,document,'https://pushpad.xyz/pushpad.js');
	pushpad('init', <?php echo "'" . esc_js ( $pushpad_settings["project_id"] ) . "'" ?>);
	pushpad('widget');
</script>

<?php
}
add_action ( 'wp_head', 'pushpad_add_wp_head' );

function pushpad_notices() {
	$delivery_result = get_option( 'pushpad_delivery_result', null );
	if ( $delivery_result ) {
		switch ( $delivery_result['status'] ) {
			case 'ok':
				echo '<div class="notice notice-success is-dismissible"><p>The push notifications for the latest post have been successfully sent.</p></div>';
				break;
			case 'error':
				echo '<div class="error is-dismissible"><p>An error occurred while sending the push notifications for the latest post. Please check your Pushpad settings, make sure that you have the cURL extension enabled and that firewall is not blocking outgoing connections to pushpad.xyz.<br>' . esc_html ( $delivery_result['message'] ) . '</p></div>';
				break;
		}
	}
	update_option ( 'pushpad_delivery_result', null );
}
add_action( 'admin_notices', 'pushpad_notices' );
