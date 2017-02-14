<?php
/**
 * Function for settings page
 */
function pushpad_settings() {
?>

<div class="wrap">
	<h1>Pushpad Settings</h1>

<?php
	$default_settings = array(
		'api' => '',
		'token' => '',
		'project_id' => '',
		'subscribe_on_load' => false
	);
	$settings = get_option ( 'pushpad_settings', array() );
	$settings = wp_parse_args( $settings, $default_settings );

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$settings ['api'] = isset ( $_POST ['api'] ) ? $_POST ['api'] : '';	
		$settings ['token'] = isset ( $_POST ['token'] ) ? $_POST ['token'] : '';
		$settings ['project_id'] = isset ( $_POST ['project_id'] ) ? $_POST ['project_id'] : '';
		$settings ['subscribe_on_load'] = isset ( $_POST ['subscribe_on_load'] );
		
		update_option ( 'pushpad_settings', $settings );

		if ($settings ['api'] == 'custom') {
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
	}
?>

	<form method="POST" id="pushpad-settings-form">

		<table class="form-table">
			<tbody>
				<tr>
					<th>Which product are you using?</th>
					<td>
						<label> 
							<input type="radio" name="api" value="simple" <?php if ($settings['api'] == "simple" ) echo "checked"; ?>> Pushpad Express
						</label>
						<br>
						<label> 
							<input type="radio" name="api" value="custom" <?php if ($settings['api'] == "custom" ) echo "checked"; ?>> Pushpad Pro (requires HTTPS)
						</label>
					</td>
				</tr>
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
				<tr class="custom-api-only">
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
							You can style the Pushpad button by adding your CSS rules for <code>.pushpad-button</code><span class="custom-api-only"> or <code>.pushpad-button.subscribed</code></span>.<br>
							Go to <i>Appearance -> Customize -> Additional CSS</i>.
						</p>
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
