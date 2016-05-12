<?php
/**
 * Function For Admin Page
 */
function plugin_pushpad_admin() {
	?>

<div style="background-color: white; padding: 20px">
	<h3>How to use:</h3>
	<ul style="list-style: disc;">
		<li><b>Widget available :</b>Plugin Pushpad Notification Widget</li>
		<li><b>Default shortcode available :</b> [send-notification-button]</li>
		<li><b>Shortcode available for modifying description and button text :</b>
			[send-notification-button description="Get notifications"
			button_text="Subscribe" ]</li>
		<li><b>Metabox :</b> Automatically sends push notifications whenever
			new post is added. It can be disabled for any api if auth token and id
			of the corresponding api is not provided.</li>
	</ul>
	<em>Note: Follow <a
		href="<?php echo  admin_url( 'admin.php?page=plugin-pushpad-setting' )?>">Plugin Pushpad
			Notification Setting</a> for sending notifications manually
	</em>
</div>
<?php
}
?>