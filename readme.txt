=== Pushpad Web Push Notifications ===
Contributors: pushpad, collimarco
Tags: push notifications, web push notifications, web push, push api, push, notifications
Requires at least: 4.4.0
Tested up to: 5.0
Stable tag: 1.8

Real push notifications for your website. Uses the W3C Push API for Chrome, Firefox, Opera, Edge and supports Safari.

== Description ==

[Pushpad](https://pushpad.xyz) is the easiest way to add push notifications to your website.

Push notifications re-engage your users by notifying them when you publish something new.

Users receive push notifications even when are not surfing your website. They don't need to install any app or plugin.

This plugin offers the following features:

- **Send push notifications** directly from Wordpress when you publish something new
- **Collect subscriptions** to push notifications

Pull requests are welcome and for convenience there is also a [repository on Github](https://github.com/pushpad/pushpad-wordpress).

== Installation ==

1. Sign up to [Pushpad](https://pushpad.xyz).
2. Upload the plugin files to the `/wp-content/plugins/pushpad-web-push-notifications` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Go to the 'Pushpad' screen in WordPress and configure the plugin.
5. Add at least one method for collecting subscriptions to push notifications (e.g. you can add the Pushpad widget to the sidebar, so that your visitors can subscribe to notifications).
6. Finally when you publish a post you can send notifications to the subscribers.

N.B. Make sure that you have the PHP cURL extension enabled, otherwise you might experience a blank screen when you try to publish a post. For example on Ubuntu run `sudo apt-get install php5-curl` and enable the extension in php.ini.
