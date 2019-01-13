<?php
/*
 * Plugin Name: Pushpad - Web Push Notifications
 * Plugin URI: https://pushpad.xyz/docs/wordpress
 * Description: Real push notifications for your website. Uses the W3C Push API for Chrome, Firefox, Opera, Edge and supports Safari.
 * Version: 1.7.1
 * Author: Pushpad
 * Author URI: https://pushpad.xyz
 * Text Domain: pushpad
 */

register_deactivation_hook( __FILE__, 'pushpad_deactivate_plugin' );
include plugin_dir_path( __FILE__ ) . '/admin/pushpad-admin.php';
include plugin_dir_path( __FILE__ ) . '/admin/pushpad-settings.php';
include plugin_dir_path( __FILE__ ) . '/includes/widget.php';
include plugin_dir_path( __FILE__ ) . '/includes/shortcode.php';
include plugin_dir_path( __FILE__ ) . '/includes/metabox.php';
require_once plugin_dir_path( __FILE__ ) . '/pushpad/pushpad.php';
require_once plugin_dir_path( __FILE__ ) . '/pushpad/notification.php';

function pushpad_activate_plugin( $plugin ) {
	if( $plugin == plugin_basename( __FILE__ ) ) {
		exit ( wp_redirect ( admin_url ( 'admin.php?page=pushpad-admin' ) ) );
	}
}
add_action ( 'activated_plugin', 'pushpad_activate_plugin' );

function pushpad_deactivate_plugin() {
	
}

function pushpad_get_settings() {
	$default_settings = array(
		'api' => '',
		'token' => '',
		'project_id' => '',
		'subscribe_on_load' => false,
		'subscribed_notice' => 'You have successfully subscribed to push notifications. You can change your preferences at any time from browser settings.',
		'not_subscribed_notice' => 'You have blocked push notifications from browser preferences. Please change your browser settings and try again.',
		'unsupported_notice' => 'Sorry, your browser does not support web push notifications.'
	);
	$settings = get_option ( 'pushpad_settings', array() );
	return wp_parse_args( $settings, $default_settings );
}

function pushpad_admin_pages() {
	add_menu_page ( 'Pushpad', 'Pushpad', 'manage_options', 'pushpad-admin', 'pushpad_admin' );
	add_submenu_page ( 'pushpad-admin', 'Settings', 'Settings', 'manage_options', 'pushpad-settings', 'pushpad_settings' );
}
add_action ( 'admin_menu', 'pushpad_admin_pages' );

function pushpad_add_wp_head() {
	$pushpad_settings = pushpad_get_settings();

	if ( !isset($pushpad_settings ["api"]) || $pushpad_settings ["api"] != 'custom' ) return;

	wp_register_script( 'pushpad-script', plugins_url('/js/pushpad.js', __FILE__), array('jquery'));
	wp_enqueue_script( 'pushpad-script' );
?>

<script>
	(function(p,u,s,h,x){p.pushpad=p.pushpad||function(){(p.pushpad.q=p.pushpad.q||[]).push(arguments)};h=u.getElementsByTagName('head')[0];x=u.createElement('script');x.async=1;x.src=s;h.appendChild(x);})(window,document,'https://pushpad.xyz/pushpad.js');

	var pushpadSettings = {
		projectId: <?php echo "'" . esc_js ( $pushpad_settings ["project_id"] ) . "'" ?>,
		subscribeOnLoad: <?php echo ($pushpad_settings ["subscribe_on_load"] ? 'true' : 'false') ?>,
		subscribedNotice: <?php echo "'" . esc_js( esc_html( $pushpad_settings ["subscribed_notice"] ) ) . "'" ?>,
		notSubscribedNotice: <?php echo "'" . esc_js( esc_html( $pushpad_settings ["not_subscribed_notice"] ) ) . "'" ?>,
		unsupportedNotice: <?php echo "'" . esc_js( esc_html( $pushpad_settings ["unsupported_notice"] ) ) . "'" ?>
	};
</script>

<?php
}

add_action ( 'wp_head', 'pushpad_add_wp_head' );

function pushpad_script() {
	wp_enqueue_script ( 'pushpad-admin-script', plugins_url ( '/js/pushpad-admin.js', __FILE__ ) );
}
add_action ( 'admin_enqueue_scripts', 'pushpad_script' );

function pushpad_style() {
	wp_enqueue_style ( 'style', plugins_url ( '/css/style.css', __FILE__ ) );
}
add_action ( 'wp_enqueue_scripts', 'pushpad_style' );
add_action ( 'admin_enqueue_scripts', 'pushpad_style' );

function pushpad_notices() {
	$delivery_result = get_option ( 'pushpad_delivery_result', null );
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

function pushpad_express_notices() {
	if ( isset( $_GET['pushpad_status'] ) ) {
		$pushpad_settings = pushpad_get_settings();
		switch ( $_GET['pushpad_status'] ) {
	    case 'subscribed':
        echo '<div class="pushpad-notice">' . esc_html( $pushpad_settings ['subscribed_notice'] ) . ' <a href="#" onclick="javascript:this.parentNode.style.display=\'none\';" class="close">&times;</a></div>';
        break;
	    case 'not_subscribed':
        echo '<div class="pushpad-alert">' . esc_html( $pushpad_settings ['not_subscribed_notice'] ) . ' <a href="#" onclick="javascript:this.parentNode.style.display=\'none\';" class="close">&times;</a></div>';
        break;
	    case 'unsupported':
        echo '<div class="pushpad-alert">' . esc_html( $pushpad_settings ['unsupported_notice'] ) . ' <a href="#" onclick="javascript:this.parentNode.style.display=\'none\';" class="close">&times;</a></div>';
        break;
		}
	}
}
add_action( 'wp_footer', 'pushpad_express_notices' );
