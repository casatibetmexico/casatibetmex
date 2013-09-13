=== Garee's Twitter Stream ===
Contributors: garee
Donate link: http://www.garee.ch/wordpress/garees-twitter-stream/
Tags: twitter, tweets
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: trunk

Flexibly integrate tweets on your blog.

== Description ==
*Garee's Twitter Stream* is a highly customizable Wordpress-plugin that shows either tweets of a twitter user, his favorites, one of his lists or the results of a twitter-search.
The output is generated from template-files or your own templates, which you can write with Mustache.

For more information check out the official [plugin-site](http://www.garee.ch/wordpress/garees-twitter-stream/)


Main-advantages:

* No javascript/ajax - only server-side scripting
* Tweet-requests are cached
* CSS only included if shortcode found on page
* highly customizable with Mustache-templates</li>

Just insert the shortcode anywhere on your blog: `[twitter_stream user='your_twitter_account']`

Use shortcode-options to further customize which tweets and how they should be displayed. Check out the examples on the [live-demo](http://www.garee.ch/wordpress/garees-twitter-stream/live-demo/)


== Installation ==

1. Download the plugin and unzip it
1. Upload the entire folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Goto the new plugin-page 'Garee's Twitter Stream' to get your shortcode
1. Place the shortcode anywhere in your blog

== Screenshots ==

1. Tweets displayed with the default-template
2. Shortcode and tweet rendered by Twitters embed-feature
3. Other integrated templates for showing tweets
4. Example of the minimal-template used in blog's sidebar
5. Shortcode-examples on the admin-page
6. Develop your own templates with Mustache-code, html and css

== Frequently Asked Questions ==


== Changelog ==

= 1.0 =
* Mustache.php version 0.9.0
* new shortcode-option "new_window" to open links in a new window
* FIX: in version 0.9 cache didn't work

= 0.9 =
* embed-template now default-template
* updated embed-code
* admin: added map to get values for geocode-option
* FIX: set in_reply_to for search-results
* admin: flattr-button now static (no more js)
* new shortcode-attributes 'since_id', 'max_id' and 'until' to define a time-window for the tweets to display

= 0.8 =
* load css and twitter-js also if shortcode found in text-widget
* force execution of shortcode in text-widget

= 0.7 =
* first official release
* fixes and documentation

= 0.6 =
* new attribute "geocode"
* FIX: load default-css if template-option is missing

== Upgrade Notice ==

= 1.0 =
There's a bug in version 0.9 that prevents cache from working!

= 0.9 =
If you didn't use the template-option of the shortcode, please check the output of the plugin. In this version the default-template changed to twitter's embedded 
