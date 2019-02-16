<?php
function pushpad_shortcode($atts) {
	$atts = shortcode_atts ( array (
			'subscribe' => 'Subscribe',
			'unsubscribe' => 'Subscribed'
	), $atts, 'pushpad-button' );
	
	$pushpad_settings = get_option ( 'pushpad_settings' );

	if (!$pushpad_settings) {
		if (is_user_logged_in () && current_user_can ( 'manage_options' )) {
			return 'You need to configure the plugin to use Pushpad shortcode: ' . ' <a href="' . admin_url ( 'admin.php?page=pushpad-settings' ) . '">settings</a>';
		} else {
			return '';
		}
	}

	return '<button class="pushpad-button" data-subscribe-text="' . esc_html ( $atts ['subscribe'] ) . '" data-unsubscribe-text="' . esc_html ( $atts ['unsubscribe'] ) . '">' . esc_html ( $atts ['subscribe'] ) . '</button>';
}

add_shortcode ( 'pushpad-button', 'pushpad_shortcode' );
