<?php
/**
 * Function for settings page
 */
function pushpad_settings() {
?>

<div class="wrap">
	<h1>Pushpad Settings</h1>

	<p><a href="https://pushpad.xyz/docs/wordpress" target="_blank">Documentation</a></p>

<?php
	$settings = pushpad_get_settings();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$settings ['token'] = isset ( $_POST ['token'] ) ? $_POST ['token'] : '';
		$settings ['project_id'] = isset ( $_POST ['project_id'] ) ? $_POST ['project_id'] : '';
		
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
					<th><label for="token">Auth Token</label></th>
					<td>
						<input type="text" name="token" id="token" value="<?php echo esc_attr ( $settings['token'] ) ?>">
						<p class="description">You can generate it in your <a href="https://pushpad.xyz/access_tokens">account settings</a>.</p>
					</td>
				</tr>
				<tr>
					<th><label for="project_id">Project ID</label></th>
					<td>
						<input type="text" name="project_id" id="project_id" value="<?php echo esc_attr ( $settings['project_id'] ) ?>">
						<p class="description">You can find it in the project settings on Pushpad.</p>
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
