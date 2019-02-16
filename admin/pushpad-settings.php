<?php
/**
 * Function for settings page
 */
function pushpad_settings() {
?>

<div class="wrap">
	<h1>Pushpad Settings</h1>

<?php
	$settings = pushpad_get_settings();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$settings ['token'] = isset ( $_POST ['token'] ) ? $_POST ['token'] : '';
		$settings ['project_id'] = isset ( $_POST ['project_id'] ) ? $_POST ['project_id'] : '';
		$settings ['subscribe_on_load'] = isset ( $_POST ['subscribe_on_load'] );
		if ( !empty ( $_POST ['subscribed_notice'] ) )
			$settings ['subscribed_notice'] = $_POST ['subscribed_notice'];
		if ( !empty ( $_POST ['not_subscribed_notice'] ) )
			$settings ['not_subscribed_notice'] = $_POST ['not_subscribed_notice'];
		if ( !empty ( $_POST ['unsupported_notice'] ) )
			$settings ['unsupported_notice'] = $_POST ['unsupported_notice'];
		
		update_option ( 'pushpad_settings', $settings );

		echo '<div class="notice notice-success is-dismissible"><p>Settings successfully updated.</p></div>';

		// service-worker.js
		$importScripts = "importScripts('https://pushpad.xyz/service-worker.js');";

		if (file_exists ( ABSPATH . 'service-worker.js' )) {
			$serviceWorkerContents = file_get_contents ( ABSPATH . 'service-worker.js' );
		} else {
			$serviceWorkerContents = '';
		}

		$newServiceWorkerContents = $importScripts . "\n\n" . $serviceWorkerContents;

		if ( strpos( $serviceWorkerContents, $importScripts ) === false ) {
			if ( is_writable ( ABSPATH . 'service-worker.js' ) || !file_exists ( ABSPATH . 'service-worker.js' ) && is_writable ( ABSPATH ) ) {
				file_put_contents( ABSPATH . 'service-worker.js', $newServiceWorkerContents );
			} else {
?>

				<p>
					The file service-worker.js in the root directory of Wordpress is not writable.
					Please change its permissions and try again. Otherwise replace its contents manually:
				</p>
				<pre class="pushpad"><code><?= esc_html ( $newServiceWorkerContents ) ?></code></pre>
				<p>
					Also make sure that the file is accessible at https://example.com/service-worker.js
					(for example https://example.com/wordpress/service-worker.js is invalid).
				</p>

<?php
			}
		}
		
	}
?>

	<form method="POST" id="pushpad-settings-form">

		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="token">Pushpad Auth Token</label></th>
					<td>
						<input type="text" name="token" id="token" value="<?php echo esc_attr ( $settings['token'] ) ?>">
						<p class="description">You can generate it in your <a href="https://pushpad.xyz/users/edit">account settings</a>.</p>
					</td>
				</tr>
				<tr>
					<th><label for="project_id">Pushpad Project ID</label></th>
					<td>
						<input type="text" name="project_id" id="project_id" value="<?php echo esc_attr ( $settings['project_id'] ) ?>">
						<p class="description">You can find it in the project settings on Pushpad.</p>
					</td>
				</tr>
				<tr>
					<th>Subscription</th>
					<td>
						<input type="checkbox" name="subscribe_on_load" id="subscribe_on_load" <?php if ( $settings['subscribe_on_load'] ) echo "checked"; ?>>
						<label for="subscribe_on_load">Prompt users on page load</label>
					</td>
				</tr>
				<tr>
					<th><label>Style</label></th>
					<td>
						<p class="description">
							You can style the Pushpad button by adding your CSS rules for <code>.pushpad-button</code> and <code>.pushpad-button.subscribed</code>.<br>
							You can style the Pushpad notices by adding your CSS rules for <code>.pushpad-notice</code> and <code>.pushpad-alert</code></span>.<br>
							Go to <i>Appearance -> Customize -> Additional CSS</i>.
						</p>
					</td>
				</tr>
				<tr>
					<th><label for="token">Custom text</label></th>
					<td>
						<input type="text" class="regular-text" name="subscribed_notice" id="subscribed_notice" value="<?php echo esc_attr ( $settings['subscribed_notice'] ) ?>" required>
						<p class="description">A notice that is displayed when a user successfully subscribes to push notifications.</p>
						<br>
						<input type="text" class="regular-text" name="not_subscribed_notice" id="not_subscribed_notice" value="<?php echo esc_attr ( $settings['not_subscribed_notice'] ) ?>" required>
						<p class="description">A notice that is displayed when subscription to push notifications fails because the user blocks them from browser settings.</p>
						<br>
						<input type="text" class="regular-text" name="unsupported_notice" id="unsupported_notice" value="<?php echo esc_attr ( $settings['unsupported_notice'] ) ?>" required>
						<p class="description">A notice that is displayed when the browser does not support push notifications.</p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" value="Save" class="button button-primary" id="submit" name="submit">
		</p>
	</form>
</div>

<?php
}
