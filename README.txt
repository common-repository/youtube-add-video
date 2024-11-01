=== Youtube Add Video ===
Version: 0.2
Plugin URI: http://www.muzungu.pl/moje-pluginy-do-wordpressa/youtube-add-video-plugin/
Author: Konrad Karpieszuk
Author URI: http://www.muzungu.pl
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8039289
Tags: youtube, video, last video, widget,
Requires at least: 2.7
Tested up to: 2.8.4
Stable tag: 0.2

Add YT video using Custom Fields and show last video in sidebar


== Description ==
This plugin does two things:
* Gives possibility to add YouTube video at end of post.
* Gives possibility to show somewhere in template (eg. in sidebar) last added video this way

== Installation ==
1. Unzip and upload the plugin folder to wp-content/plugins/
2. Activate the plugin in WP Admin -> Plugins.
3. If you want attach video to the end of post/page, add Custom Field with key "video" and with url to video in YT site as value for this key.
4. If you want to show somewhere in template last added video, call php function show_LastYT()

== Changelog ==
= 0.2 =
* When post is published, tag "video" is automatically added to it
* In function show_LastYT() you can argument which should be ID of person who added video (without argument function will show last video from anybody), eg show_LastYT(5) will show last video added by user whos ID is 5

= 0.1 =
* Works ;)
