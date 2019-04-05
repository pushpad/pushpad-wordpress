<?php
function pushpad_shortcode($atts) {
	return '<div id="pushpad-subscribe"></div>';
}

add_shortcode ( 'pushpad-button', 'pushpad_shortcode' );
