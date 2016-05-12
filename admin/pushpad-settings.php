<?php
/**
 * Function for settings page
 */
function pushpad_settings() {
	$default_settings = array(
		'api' => '',
		'token' => '',
		'project_id' => '',
		'gcm_sender_id' => '',
		'subscribe_on_load' => false
	);
	$settings = get_option ( 'pushpad_settings', array() );
	$settings = wp_parse_args( $settings, $default_settings );

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$settings ['api'] = isset ( $_POST ['api'] ) ? esc_html ( $_POST ['api'] ) : '';	
		$settings ['token'] = isset ( $_POST ['token'] ) ? esc_html ( $_POST ['token'] ) : '';
		$settings ['project_id'] = isset ( $_POST ['project_id'] ) ? esc_html ( $_POST ['project_id'] ) : '';
		$settings ['gcm_sender_id'] = isset ( $_POST ['gcm_sender_id'] ) ? esc_html ( $_POST ['gcm_sender_id'] ) : '';
		$settings ['subscribe_on_load'] = isset ( $_POST ['subscribe_on_load'] );
		
		update_option ( 'pushpad_settings', $settings );

		if ($settings ['api'] == 'custom') {
			if (file_exists ( dirname ( __FILE__ ) . '/../manifest.json' )) {
				$oldManifestJson = file_get_contents ( dirname ( __FILE__ ) . '/../manifest.json' );
			} else {
				$oldManifestJson = '{}';
			}

			$data = json_decode ( $oldManifestJson, true );
			
			$data ['gcm_sender_id'] = esc_html ( $settings ['gcm_sender_id'] );
			$data ['gcm_user_visible_only'] = true;
			$newManifestJson = json_encode ( $data );
			file_put_contents ( dirname ( __FILE__ ) . '/../manifest.json', $newManifestJson );
		}
	}
	
?>

<div class="wrap">
	<h1>Pushpad Settings</h1>

	<form method="POST" id="pushpad-settings-form">

		<table class="form-table">
			<tbody>
				<tr>
					<th>Which API are you using?</th>
					<td>
						<label> 
							<input type="radio" name="api" value="simple" <?php if ($settings['api'] == "simple" ) echo "checked"; ?>> Simple API
						</label>
						<br>
						<label> 
							<input type="radio" name="api" value="custom" <?php if ($settings['api'] == "custom" ) echo "checked"; ?>> Custom API
						</label>
					</td>
				</tr>
				<tr>
					<th><label for="token">Pushpad Auth Token</label></th>
					<td><input type="text" name="token" id="token" value="<?php echo $settings['token'] ?>"></td>
				</tr>
				<tr>
					<th><label for="project_id">Pushpad Project ID</label></th>
					<td><input type="text" name="project_id" id="project_id" value="<?php echo $settings['project_id'] ?>"></td>
				</tr>
				<tr class="custom-api-only">
					<th><label for="gcm_sender_id">GCM Sender ID</label></th>
					<td><input type="text" name="gcm_sender_id" id="gcm_sender_id" value="<?php echo $settings['gcm_sender_id'] ?>"></td>
				</tr>
				<tr class="custom-api-only">
					<th>Subscription</th>
					<td>
						<input type="checkbox" name="subscribe_on_load" id="subscribe_on_load" <?php if ( $settings['subscribe_on_load'] ) echo "checked"; ?>>
						<label for="subscribe_on_load">Prompt users on page load</label>
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
