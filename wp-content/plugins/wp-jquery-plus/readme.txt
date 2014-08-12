=== WP jQuery Plus ===
Contributors: zslabs
Tags: jquery,google
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.0.1
License: GPLv2

Loads jQuery from Google using the exact jQuery version as your current WordPress install while still maintaining backwards comptability for the core WP jQuery library

== Description ==

There’s been a whole heap of discussions about the "correct" way to load jQuery onto your site. I thought I’d throw my solution into the ring. What this plugin does differently than others is it loads jQuery from Google - while providing a fallback to the local version in the event the CDN is down or your firewall blocks access to Google. It also uses the same version as WordPress does - automagically.

**WordPress 3.5 is now required for protocol relative URLs**

== Installation ==

1. Upload the `wp-jquery-plus` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can you add `feature-x`? =

Sure! I'm always open to knew ideas. Just create a new forum post and I'll take a gander.

== Changelog ==

= 1.0.1 =
* Tested in 3.8
* Updated description about fallback use-case.

= 1.0 =
* Make sure jQuery is enqueued
* This puppy should be ready to go! Now considered feature-complete.

= 0.5 =
* Added 3.6 check (since the jQuery handle was changed) and updated accordingly to work in both 3.5 and 3.6.

= 0.4.2 =
* Source credits given for local fallback

= 0.4.1 =
* Updated readme requirements

= 0.4 =
* Added local fallback to bundled jQuery in the event CDN is down

= 0.3 =
* Test push using https://github.com/benbalter/Github-to-WordPress-Plugin-Directory-Deployment-Script

= 0.2 =
* Removed CURL function (if Google is down, the entire Internet is down)

= 0.1 =
* Initial release