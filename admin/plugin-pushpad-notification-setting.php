<?php
/**
 * Function for Notification Setting page
 */
function plugin_pushpad_setting() {
	$simple_api_options = get_option ( 'simple_api_settings' );
	$custom_api_options = get_option ( 'custom_api_settings' );
	$api_selected = get_option ( 'apiSelected' );
	
	if (isset ( $_POST ['submit-simple-api-settings'] )) {
		
		$simple_api_options = array ();
		$api_selected = 'simple';
		
		if (isset ( $_POST ['token'] )) {
			$simple_api_options ['token'] = esc_html ( $_POST ['token'] );
		}
		
		if (isset ( $_POST ['project_id'] )) {
			$simple_api_options ['project_id'] = esc_html ( $_POST ['project_id'] );
		}
		
		if (isset ( $_POST ['notification_title'] )) {
			$simple_api_options ['notification_title'] = esc_html ( $_POST ['notification_title'] );
		}
		
		update_option ( 'simple_api_settings', $simple_api_options );
		update_option ( 'apiSelected', $api_selected );
	}
	if (isset ( $_POST ['submit-custom-api-settings'] )) {
		if (file_exists ( dirname ( __FILE__ ) . '/../manifest.json' )) {
			$oldManifestJson = file_get_contents ( dirname ( __FILE__ ) . '/../manifest.json' );
			$data = json_decode ( $oldManifestJson, true );
			
			$data ['gcm_sender_id'] = esc_html ( $_POST ['gcm_sender_id'] );
			$data ['gcm_user_visible_only'] = true;
			$newManifestJson = json_encode ( $data );
			file_put_contents ( dirname ( __FILE__ ) . '/../manifest.json', $newManifestJson );
		} else {
			$manifestFile = fopen ( dirname ( __FILE__ ) . "/../manifest.json", "w" ) or die ( "Unable to open file!" );
			$manifestData = '{"gcm_sender_id":"' . esc_html ( $_POST ['gcm_sender_id'] ) . '","gcm_user_visible_only":true}';
			fwrite ( $manifestFile, $manifestData );
			fclose ( $manifestFile );
		}
		$custom_api_options = array ();
		$api_selected = 'custom';
		
		if (isset ( $_POST ['gcm_sender_id'] )) {
			$custom_api_options ['gcm_sender_id'] = esc_html ( $_POST ['gcm_sender_id'] );
		}
		
		if (isset ( $_POST ['custom_api_token'] )) {
			$custom_api_options ['custom_api_token'] = esc_html ( $_POST ['custom_api_token'] );
		}
		
		if (isset ( $_POST ['custom_api_project_id'] )) {
			$custom_api_options ['custom_api_project_id'] = esc_html ( $_POST ['custom_api_project_id'] );
		}
		
		if (isset ( $_POST ['custom_api_notification_title'] )) {
			$custom_api_options ['custom_api_notification_title'] = esc_html ( $_POST ['custom_api_notification_title'] );
		}
		
		if (isset ( $_POST ['subscribe-on-load'] )) {
			$custom_api_options ['subscribe-on-load'] = 1;
		}
		
		update_option ( 'custom_api_settings', $custom_api_options );
		update_option ( 'apiSelected', $api_selected );
	}
	
	?>
<div class="wrap">
	<h1>Plugin Pushpad Notification : Settings</h1>
	<?php
	
	if (isset ( $_POST ['submit-notification'] )) {
		try {
			// Get PushPad configuration
			if (isset ( $simple_api_options ['token'] ))
				Pushpad::$auth_token = $simple_api_options ['token'];
			
			if (isset ( $simple_api_options ['project_id'] ))
				Pushpad::$project_id = $simple_api_options ['project_id'];
			
			$title = isset ( $_POST ['notification-title'] ) ? esc_html ( $_POST ['notification-title'] ) : '';
			$body = isset ( $_POST ['notification-body'] ) ? esc_html ( $_POST ['notification-body'] ) : '';
			$url = isset ( $_POST ['notification-url'] ) ? esc_html ( $_POST ['notification-url'] ) : '';
			
			$notitfication_args = array (
					'body' => $body,
					'title' => $title, // optional, defaults to your project name
					'target_url' => $url 
			); // optional, defaults to your project website
			
			$notification = new Notification ( $notitfication_args );
			$notification->broadcast ();
		} catch ( Exception $e ) {
			echo '<em style="color:red">' . $e->getMessage () . ' for ' . $api_selected . ' api </em>';
		}
	}
	
	if (isset ( $_POST ['submit-notification-custom'] )) {
		try {
			// Get PushPad configuration
			if (isset ( $custom_api_options ['custom_api_token'] ))
				Pushpad::$auth_token = $custom_api_options ['custom_api_token'];
			
			if (isset ( $custom_api_options ['custom_api_project_id'] ))
				Pushpad::$project_id = $custom_api_options ['custom_api_project_id'];
			
			$title = isset ( $_POST ['notification-title-custom'] ) ? esc_html ( $_POST ['notification-title-custom'] ) : '';
			$body = isset ( $_POST ['notification-body-custom'] ) ? esc_html ( $_POST ['notification-body-custom'] ) : '';
			$url = isset ( $_POST ['notification-url-custom'] ) ? esc_html ( $_POST ['notification-url-custom'] ) : '';
			
			$notitfication_args = array (
					'body' => $body,
					'title' => $title, // optional, defaults to your project name
					'target_url' => $url 
			); // optional, defaults to your project website
			
			$notification_custom = new Notification ( $notitfication_args );
			$notification_custom->broadcast ();
		} catch ( Exception $e ) {
			echo '<em style="color:red">' . $e->getMessage () . ' for ' . $api_selected . ' api </em>';
		}
	}
	?>
	<div class="metabox-holder">
		<div class="postbox ">
			<h3 class="hndle">
				<span>Send Notification Using</span>
			</h3>
			<div class="inside">
				<label> <input checked
					class="NotifAPIRadio" name="APIRadio" data-container="SimpleNotif"
					type="radio"> Simple API
				</label> <label> <input
					<?php if (get_option('apiSelected') == "custom" ) echo "checked" ; else echo ""; ?>
					class="NotifAPIRadio" name="APIRadio" data-container="CustomNotif"
					type="radio"> Custom API
				</label>
			</div>
		</div>
		<div id="SimpleNotif" class="postbox NotifSendContainer">
			<h3 class="hndle">
				<span>Settings For Simple API</span>
			</h3>
			<div class="inside">
				Please setup your AUTH Token and Project ID to get the plugin up and
				running. <br /> <br />
				<form method="POST">
					<table cellspacing="0">
						<tbody>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">AUTH
										Token:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" name="token"
									value="<?php echo isset($simple_api_options['token'])?$simple_api_options['token']: '' ?>"
									class="regular-text"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">Project
										ID:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" id="project_id"
									name="project_id"
									value="<?php echo isset($simple_api_options['project_id'])?$simple_api_options['project_id']: '' ?>"
									class="regular-text"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">Notification
										Title:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" name="notification_title"
									value="<?php echo isset($simple_api_options['notification_title'])?$simple_api_options['notification_title']: get_bloginfo( 'name' ) ?>"
									class="regular-text"></td>
							</tr>
						</tbody>
					</table>
					<input type="submit" name="submit-simple-api-settings"
						id="submit-simple-api-settings" class="button button-primary"
						value="Save">
				</form>
			</div>
		</div>
		<div id="CustomNotif" class="postbox NotifSendContainer"
			<?php if (get_option('apiSelected') != "custom" ) echo "style='display:none;'"?>>
			<h3 class="hndle">
				<span>Settings For Custom API</span>
			</h3>
			<div class="inside">
				<div class="about-text">Please setup your AUTH Token and Project ID
					to get the plugin up and running.</div>
				<form method="POST">
					<table cellspacing="0">
						<tbody>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">GCM
										Sender ID:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" name="gcm_sender_id"
									value="<?php echo isset($custom_api_options['gcm_sender_id'])?$custom_api_options['gcm_sender_id']: '' ?>"
									class="regular-text"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">AUTH
										Token:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" name="custom_api_token"
									value="<?php echo isset($custom_api_options['custom_api_token'])?$custom_api_options['custom_api_token']: '' ?>"
									class="regular-text"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">Project
										ID:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" name="custom_api_project_id"
									value="<?php echo isset($custom_api_options['custom_api_project_id'])?$custom_api_options['custom_api_project_id']: '' ?>"
									class="regular-text"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label class="block">Notification
										Title:</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text"
									name="custom_api_notification_title"
									value="<?php echo isset($custom_api_options['custom_api_notification_title'])?$custom_api_options['custom_api_notification_title']: get_bloginfo( 'name' ) ?>"
									class="regular-text"></td>
							</tr>
							<tr height="48px">
								<td colspan="3"><label> <input
										<?php if (isset($custom_api_options['subscribe-on-load']) && $custom_api_options['subscribe-on-load'] == "1" ) echo "checked" ; else echo ""; ?>
										id="subscribe-on-load" type="checkbox"
										name="subscribe-on-load" value="1"> Subscribe users on page
										load
								</label></td>
							</tr>
						</tbody>
					</table>
					<input type="submit" name="submit-custom-api-settings"
						id="submit-custom-api-settings" class="button button-primary"
						value="Save">
				</form>
			</div>
		</div>
		<div id="SimpleNotifTest" class="postbox NotifSendContainerTest">
			<h3 class="hndle">
				<span>Send Notification Using Simple API</span>
			</h3>
			<div class="inside">
				<form method="POST" id="push-notification">
					<table cellspacing="0">
						<tbody>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label>Notification
										Title</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text" id="notification-title"
									name="notification-title"
									value="<?php echo get_bloginfo( 'name' ) ?>"
									class="regular-text" maxlength="30"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label>Notification
										Body</label></th>
								<td width="5%"></td>
								<td align="left"><textarea id="notification-body"
										name="notification-body" class="regular-text" maxlength="80"
										rows="6" cols="46"></textarea></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label>Notification
										URL:</label></th>
								<td width="5%"></td>
								<td align="left"><input id="notification-url" type="text"
									name="notification-url" value="" class="regular-text"></td>
							</tr>
						</tbody>
					</table>
					<input type="submit" name="submit-notification"
						id="submit-notification" class="button button-primary"
						value="Send Notification">
				</form>
			</div>
		</div>
		<div
			<?php if (get_option('apiSelected') != "custom" ) echo "style='display:none;'"?>
			id="CustomNotifTest" class="postbox NotifSendContainerTest">
			<h3 class="hndle">
				<span>Send Notification Using Custom API</span>
			</h3>
			<div class="inside">
				<form method="POST" id="push-notification-custom">
					<table cellspacing="0">
						<tbody>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label>Notification
										Title</label></th>
								<td width="5%"></td>
								<td align="left"><input type="text"
									id="notification-title-custom" name="notification-title-custom"
									value="<?php echo get_bloginfo( 'name' ) ?>"
									class="regular-text" maxlength="30"></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label>Notification
										Body</label></th>
								<td width="5%"></td>
								<td align="left"><textarea id="notification-body-custom"
										name="notification-body-custom" class="regular-text"
										maxlength="80" rows="6" cols="46"></textarea></td>
							</tr>
							<tr height="48px">
								<th width="10%" align="left" scope="row"><label>Notification
										URL:</label></th>
								<td width="5%"></td>
								<td align="left"><input id="notification-url-custom" type="text"
									name="notification-url-custom" value="" class="regular-text"></td>
							</tr>
						</tbody>
					</table>
					<input type="submit" name="submit-notification-custom"
						id="submit-notification-custom" class="button button-primary"
						value="Send Notification">
				</form>
			</div>
		</div>
	</div>
</div>
<?php
}
?>