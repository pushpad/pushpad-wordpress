<?php
/*
 * Plugin Name: PluginPushpad Push Notification
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: PluginPushpad Push Notification plugin is created for client request, which is used to push notification
 * Version: 1.0.0
 * Author: PluginPushpad
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 * Text Domain: my-toolset
 */
register_deactivation_hook ( __FILE__, 'plugin_pushpad_deactivate_plugin' );
include plugin_dir_path ( __FILE__ ) . '/admin/plugin-pushpad-admin.php';
include plugin_dir_path ( __FILE__ ) . '/admin/plugin-pushpad-notification-setting.php';
include plugin_dir_path ( __FILE__ ) . '/includes/widget.php';
include plugin_dir_path ( __FILE__ ) . '/includes/shortcode.php';
include plugin_dir_path ( __FILE__ ) . '/includes/metabox.php';
require_once plugin_dir_path ( __FILE__ ) . '/pushpad/pushpad.php';
require_once plugin_dir_path ( __FILE__ ) . '/pushpad/notification.php';

$api_selected = get_option ( 'apiSelected' );

if ($api_selected == 'simple') {
	$smiple_api_options = get_option ( 'simple_api_settings' );
	if (isset ( $smiple_api_options ['token'] )) {
		Pushpad::$auth_token = $smiple_api_options ['token'];
	}
	
	if (isset ( $smiple_api_options ['project_id'] )) {
		Pushpad::$project_id = $smiple_api_options ['project_id'];
	}
}

if ($api_selected == 'custom') {
	$custom_api_options = get_option ( 'custom_api_settings' );
	if (isset ( $custom_api_options ['custom_api_token'] )) {
		Pushpad::$auth_token = $custom_api_options ['custom_api_token'];
	}
	
	if (isset ( $custom_api_options ['custom_api_project_id'] )) {
		Pushpad::$project_id = $custom_api_options ['custom_api_project_id'];
	}
}

/**
 * Function to activate plugin
 */
function plugin_pushpad_activate_plugin() {
	exit ( wp_redirect ( admin_url ( 'admin.php?page=plugin-pushpad-admin' ) ) );
	flush_rewrite_rules ();
}
add_action ( 'activated_plugin', 'plugin_pushpad_activate_plugin' );
/**
 * function to deactivate plugin
 */
function plugin_pushpad_deactivate_plugin() {
	flush_rewrite_rules ();
}
/*
 * Function to add admin pages
 */
function plugin_pushpad_admin_pages() {
	add_menu_page ( 'Plugin Pushpad Notification', 'Plugin Pushpad Notification', 'manage_options', 'plugin-pushpad-admin', 'plugin_pushpad_admin', '', 5 );
	add_submenu_page ( 'plugin-pushpad-admin', 'Plugin Pushpad Notification Setting', 'Plugin Pushpad Notification Setting', 'manage_options', 'plugin-pushpad-setting', 'plugin_pushpad_setting' );
}
add_action ( 'admin_menu', 'plugin_pushpad_admin_pages' );

/*
 * Function to add script on header
 */
function add_wp_head() {
	$custom_api_options = get_option ( 'custom_api_settings', array () );
	echo '<link rel="manifest"  href="' . plugins_url ( 'manifest.json', __FILE__ ) . '">';
	?>
<script>
		(function(p,u,s,h,x){p.pushpad=p.pushpad||function(){(p.pushpad.q=p.pushpad.q||[]).push(arguments)};h=u.getElementsByTagName('head')[0];x=u.createElement('script');x.async=1;x.src=s;h.appendChild(x);})(window,document,'https://pushpad.xyz/pushpad.js');
	<?php
	echo "pushpad('init', " . $custom_api_options ["custom_api_project_id"] . ");";
	if ($custom_api_options ["subscribe-on-load"] == 1) {
		?>
			pushpad('subscribe');
		<?php }?>
		
		jQuery(document).ready(function(){
					jQuery(".customSubscription").on("click",function(e){
						e.preventDefault();
						var callback = function(isSubscribed, tags){
				 			if(isSubscribed === true){
							pushpad('unsubscribe');
						}else{
							pushpad('subscribe');
						}
						};
						pushpad('status', callback);
					});
				})
		
		</script>
<?php
}
add_action ( 'wp_head', 'add_wp_head' );

/**
 * function to js file
 */
function plugin_pushpad_script() {
	wp_enqueue_script ( 'plugin-pushpad-script', plugins_url ( '/js/plugin-pushpad-admin.js', __FILE__ ) );
}
add_action ( 'admin_enqueue_scripts', 'plugin_pushpad_script' );
function plugin_pushpad_frontend_style() {
	wp_enqueue_style ( 'style', plugins_url ( '/css/style.css', __FILE__ ) );
}
add_action ( 'wp_enqueue_scripts', 'plugin_pushpad_frontend_style' );
?>