<?php
/**
 * Function for admin page
 */
function pushpad_admin() {
?>

<div class="wrap">
	<h1>Getting started with the Pushpad plugin</h1>
  <p>If you don't have a Pushpad account yet, you must <a href="https://pushpad.xyz/users/sign_up" target="_blank">create an account</a>.</p>
  <p>Then go to <i>Pushpad -> Settings</i> to configure this plugin.</p>
  
  <h2>1. Collecting subscriptions</h2>
  <p>In order to send push notifications, you first need to collect subscribers.</p>
  <p>Use at least one of the following methods.</p>
  <h3>Widget</h3>
  <p>Go to <i>Appearance -> Widgets</i> and add the <i>Pushpad Button</i>.</p>
  <h3>Shortcode</h3>
  <p>Inside any page or post you can use <code>[pushpad-button]</code> or <code>[pushpad-button subscribe="Subscribe" unsubscribe="Unsubscribe"]</code>.</p>
  <h3>Browser prompt</h3>
  <p>If you use Pushpad Pro, you can ask your visitors to subscribe to push notifications on page load by enabling an option in <i>Pushpad -> Settings</i>.</p>

  <h2>2. Sending push notifications</h2>
  <p>When you create a new post, this plugin displays an option to send a notification to the subscribers.</p>
  <p>You can also send notifications from the <a href="https://pushpad.xyz/projects/" target="_blank">Pushpad dashboard</a>.</p>
</div>

<?php
}
