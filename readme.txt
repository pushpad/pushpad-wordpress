=== Pushpad Web Push Notifications ===
Contributors: pushpad, collimarco
Tags: push notifications, web push notifications, web push, push api, push, notifications
Requires at least: 4.4.0
Tested up to: 4.8.0
Stable tag: 1.3

Real push notifications for your website. Uses the W3C Push API for Chrome and Firefox and supports Safari.

== Description ==

[Pushpad](https://pushpad.xyz) is the easiest way to add push notifications to your website.

Push notifications re-engage your users by notifying them when you publish something new.

Users receive push notifications even when are not surfing your website. They don't need to install any app or plugin.

**Pushpad Express** is the easiest way to create a channel to send push notifications to your users. It requires **zero setup**, **you don't need any technical skills** and you don't even need to sign up to developer's programs. Just create a project on Pushpad inserting your website name, logo and url and you're done (1 minute).

Go for **Pushpad Pro** if you are looking for a **professional service** which is perfectly integrated into your application and absolutely transparent to the end users. You have more power and control over every detail (for example you can customize the sender).

This plugin offers the following features:

- **Send push notifications** directly from Wordpress when you publish something new
- **Collect subscriptions** to push notifications

**Pull requests** are welcome and for convenience there is also a [repository on Github](https://github.com/pushpad/pushpad-wordpress).

== Installation ==

First you need to sign up to [Pushpad](https://pushpad.xyz) to use this plugin. Don't worry: sign up is free and we offer a large free tier.

1. Upload the plugin files to the `/wp-content/plugins/pushpad-web-push-notifications` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Pushpad->Settings screen to configure the plugin

Then you must add at least one method for collecting subscriptions to push notifications (e.g. add the Pushpad widget to the sidebar, so that your visitors can subscribe to notifications). Finally when you publish a post you can send notifications to the subscribers.

N.B. Make sure that you have the PHP cURL extension enabled, otherwise you might experience a blank screen when you try to publish a post. For example on Ubuntu run "sudo apt-get install php5-curl" and enable the extension in php.ini.

If you want to use custom solutions (different from those offered by the plugin) to subscribe your visitors to push notifications you can also read the [docs](https://pushpad.xyz/docs/wordpress) on Pushpad and do a manual installation. Then you can still use the plugin to send push notifications.

We also offer a great [support](https://pushpad.xyz/contact) if you need help.
