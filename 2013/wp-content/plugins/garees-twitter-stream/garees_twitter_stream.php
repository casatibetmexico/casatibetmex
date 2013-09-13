<?php
/*
Plugin Name: Garee's Twitter Stream
Plugin URI: http://www.garee.ch/wordpress/garees-twitter-stream/
Description: Garee's Twitter Stream allows you to integrate tweets on your blog 
Version: 1.0
Author: Sebastian Forster
Author URI: http://www.garee.ch/wordpress/
License: GPL2
*/

/*  Copyright 2011  Sebastian Forster  (email : garee@gmx.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!defined('GAREE_MUSTACHEPHP')) {
	include_once('Mustache.php');
	define('GAREE_MUSTACHEPHP', true);
}

/**
 * Generate links from urls, twittter-tags and twitter-users
 */
function garees_twitter_stream_link($input, $blank) {
	return garees_twitter_stream_link_user(garees_twitter_stream_link_hash(garees_twitter_stream_link_url($input, $blank), $blank), $blank);
}
function garees_twitter_stream_link_user($input, $blank) {
	$pattern = '/(^|[\W])@(\w+)/i';
	if ($blank)
		$replacement = '$1@<a href="http://twitter.com/$2" target="_blank">$2</a>';   // neues fenster?
	else 
		$replacement = '$1@<a href="http://twitter.com/$2">$2</a>';   // neues fenster?
	$output = preg_replace($pattern, $replacement, $input);
	return $output;
}
function garees_twitter_stream_link_hash($input, $blank) {
	$pattern = '/(?:^| )[\#]+([\w]+)/i';
	if ($blank)
		$replacement = ' <a href="http://search.twitter.com/search?q=&tag=$1&lang=all" target="_blank">#$1</a>';
	else 
		$replacement = ' <a href="http://search.twitter.com/search?q=&tag=$1&lang=all">#$1</a>';
	$output = preg_replace($pattern, $replacement, $input);
	return $output;
}
function garees_twitter_stream_link_url($input, $blank) {
	$pattern = ' /(https?:\/\/([a-zA-Z0-9.?=&_\-\/~]+))/';
	if ($blank)
		$replacement = ' <a href="$1" target="_blank">$2</a>';
	else
		$replacement = ' <a href="$1">$2</a>';
	$output = preg_replace($pattern, $replacement, $input);
	return $output;
}

/**
 * Calculate the relative time
 **/ 
function garees_twitter_stream_relative_time($date) {
	$delta = (strtotime("now") - strtotime($date));
	$output = '';
	if ($delta < 1) {
		$output = 'just now';
	} else if ($delta < 60) {
		$output .= $delta . ' seconds ago';
	} else if($delta < 120) {
		$output .= 'a minute ago';
	} else if($delta < (45*60)) {
		$output .= round($delta / 60) . ' minutes ago';
	} else if($delta < (2*60*60)) {
		$output .= 'an hour ago';
	} else if($delta < (24*60*60)) {
		$output .= round($delta / 3600) . ' hours ago';
	} else if($delta < (48*60*60)) {
		$output .= 'a day ago';
	} else {
		$output .= round($delta / 86400) . ' days ago';
	}
	return $output;
}

/**
 * Build and encode the query
 **/ 
function garees_twitter_stream_build_http_query( $query ){
    $query_array = array();
    foreach( $query as $key => $key_value ){
        $query_array[] = $key . '=' . urlencode( $key_value );
    }
    return implode( '&', $query_array );
}

/**
 * Output an error
 **/ 
function garees_twitter_stream_error($msg) {
	return "<span style='color:red'>Garee's Twitter Stream Error : ".$msg."</span>";
}

/**
 * Main-Function: Get the IDs and run gallery-shortcode
 **/ 
function garees_twitter_stream($atts, $content = "") {
	
	extract(shortcode_atts(array(
	  'id' => null,
	  'user' => null,	
	  'list' => null,
	  'favorites' => null,	
	  'search' => null,
	  'geocode' => null,
	  'lang' => null, //  Restricts tweets to the given language, given by an ISO 639-1 code.
	  'result_type' => null, // mixed: Include both popular and real time results in the response. 
							 // recent: return only the most recent results in the response
							 // popular: return only the most popular results in the response.
	  'exclude_replies' => null,
	  'include_rts' => null,
	  'new_window' => null,
	  'template' => null,
	  'count' => 10,
	  'since_id' => null,
	  'max_id' => null,
	  'until' => null,
	  'cache_time' => 15,
	  'date_format' => get_option('date_format'),
	), $atts));	
	
	// add some default-values to the query-string
	$query_attributes = array(
		'include_entities' => true,
		'count' => $count,
		'per_page' => $count,
		'rpp' => $count,
		'lang' => $lang,
		'result_type' => $result_type,
		'exclude_replies' => $exclude_replies,
		'include_rts' => $include_rts,	
		//'geocode' => $geocode,
		'page' => 1,
	);
	
	if (isset($id)) {   // get just one particular tweet  http://api.twitter.com/1/statuses/show/136931605234192384.json
		$query = "http://api.twitter.com/1/statuses/show/" . urlencode($id) . ".json?" . garees_twitter_stream_build_http_query($query_attributes);
	} else if (isset($list)) {   // format "garee76/tech"
		$list_parts = explode("/", $list);
		if (count($list_parts) == 2) {
			$query_attributes['slug'] = $list_parts[1];
			$query_attributes['owner_screen_name'] = $list_parts[0];
			$query = "http://api.twitter.com/1/lists/statuses.json?" . garees_twitter_stream_build_http_query($query_attributes);	
		} else 	{
			return garees_twitter_stream_error("Wrong format for 'list'! Needs to be user/list");
		}
	} else if (isset($user)) {
		$query_attributes['screen_name'] = urlencode($user);
		if (isset($since_id))
			$query_attributes['since_id'] = urlencode($since_id);
		if (isset($max_id))
			$query_attributes['max_id'] = urlencode($max_id);
		$query = "http://api.twitter.com/1/statuses/user_timeline.json?" . garees_twitter_stream_build_http_query($query_attributes);
	} else if (isset($favorites)) {
		$query = "http://api.twitter.com/1/favorites/" . urlencode($favorites) . ".json?" . garees_twitter_stream_build_http_query($query_attributes);
	} else if (isset($search) || isset($geocode)) {
		$query_attributes['q'] = urlencode($search);
		$query_attributes['geocode'] = urlencode($geocode);
		if (isset($since_id))
			$query_attributes['since_id'] = urlencode($since_id);
		if (isset($until))
			$query_attributes['until'] = urlencode($until);	
		$query = "http://search.twitter.com/search.json?" . garees_twitter_stream_build_http_query($query_attributes);
	} else {
		$query = "http://api.twitter.com/1/statuses/public_timeline.json?" . garees_twitter_stream_build_http_query($query_attributes);
	}	
	
	//return $query;
	
	$m = new Mustache;
	
	$data = array();
	
	// open template_file, submitted template or default
	if (!is_null($template)) {
		$tmpl = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/' . $template. '.html');					
	} elseif ($content != "") {
		$tmpl = $content;	
	} else {
		$tmpl = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/default.html');		
	}
		
	//$stream = wp_remote_fopen($query);
	//print_r(json_decode($stream, true));
	
	$cache_name = "garee" . md5($query);
	
	if ( false === ( $tweets = get_transient( $cache_name ) ) ) {
		$data['origin'] = "query";
		
		$stream = wp_remote_fopen($query);
		if (strstr($stream,'{"error":"Rate limit exceeded'))   // detect {"error":"Rate limit exceeded. Clients may not make more than 150 requests per hour.","request":"\/statuses\/show\/29640123173.json"}
			return garees_twitter_stream_error("Rate limit exceeded! (check your cache-time-setting)");
		
		$tweets = garees_twitter_stream_filter($stream);
		//$stream = "test";
		unset($stream);
		set_transient($cache_name, $tweets, 60*$cache_time );
	} else {
		$data['origin'] = "cache";
	}
	
	// attach twitter-results
	$data['items'] = garees_twitter_stream_prepare($tweets, $date_format, $new_window);
	
	// attach additional data
	$data['template_dir'] = plugin_dir_url(__FILE__)."templates/".$template."/";
	
	// exclude the following attributes from gallery-shortcode
	$forget = array();
	// include other attributes in gallery-shortcode
	foreach ($atts as $key => $value) {
		if (!in_array($key, $forget))
			$data[$key] = $value;
	}
			
	// render the template		
	//return print_r($data);				
	return $m->render($tmpl, $data);
}


/**
 * Decode json-stream and keep stuff that we need (called when getting new data)
 */
function garees_twitter_stream_filter($stream) {
	$tweets = json_decode($stream, true);
	
	if (isset($tweets['results'])) 
		$input = $tweets['results'];
	else if (isset($tweets['id'])) 
		$input[0] = $tweets;
	else
		$input = $tweets;
	
	$output = array();
	
	$m = new Mustache;
	
	$i = 0;
	foreach($input as $tweet_in) {
		
		$tweet_out['tweet_is_retweet'] = isset($tweet_in['retweeted_status']);
		
		if ($tweet_out['tweet_is_retweet']) {
			$tweet_out['retweeted_by_name'] = isset($tweet_in['from_user_name']) ? $tweet_in['from_user_name'] : $tweet_in['user']['name'];
			$tweet_out['retweeted_by_screen_name'] = isset($tweet_in['from_user']) ? $tweet_in['from_user'] : $tweet_in['user']['screen_name'];
			$tweet_out['avatar_url'] = $tweet_in['retweeted_status']['user']['profile_image_url'];
			$tweet_out['tweet_text_raw'] = $tweet_in['retweeted_status']['text'];
			$tweet_out['user_screen_name'] = $tweet_in['retweeted_status']['user']['screen_name'];
			$tweet_out['user_name'] = $tweet_in['retweeted_status']['user']['name'];
			
			$tweet_out['reply_user_id'] = $tweet_in['retweeted_status']['in_reply_to_user_id_str'];
			$tweet_out['reply_tweet_id'] = $tweet_in['retweeted_status']['in_reply_to_status_id_str'];
			$tweet_out['reply_user_name'] = $tweet_in['retweeted_status']['in_reply_to_screen_name'];
			
		} else {			
			$tweet_out['retweeted_by_name'] = "";
			$tweet_out['retweeted_by_screen_name'] = "";
			$tweet_out['avatar_url'] = isset($tweet_in['profile_image_url']) ? $tweet_in['profile_image_url'] : $tweet_in['user']['profile_image_url'];
			$tweet_out['tweet_text_raw'] = $tweet_in['text'];		
			$tweet_out['user_screen_name'] = isset($tweet_in['from_user']) ? $tweet_in['from_user'] : $tweet_in['user']['screen_name'];
			$tweet_out['user_name'] = isset($tweet_in['from_user_name']) ? $tweet_in['from_user_name'] : $tweet_in['user']['name'];
			
			if (isset($tweet_in['iso_language_code'])) {   // search
				$tweet_out['reply_user_id'] = isset($tweet_in['to_user_id_str']) ? $tweet_in['to_user_id_str'] : null;
				$tweet_out['reply_user_name'] = isset($tweet_in['to_user']) ? $tweet_in['to_user'] : null;
			} else {
				$tweet_out['reply_user_id'] = $tweet_in['in_reply_to_user_id_str'];
				$tweet_out['reply_user_name'] = $tweet_in['in_reply_to_screen_name'];
			}
			$tweet_out['reply_tweet_id'] = isset($tweet_in['in_reply_to_status_id_str']) ? $tweet_in['in_reply_to_status_id_str'] : null;
		}		
	
		
		$tweet_out['tweet_source'] = $tweet_in['source'];
			
		$tweet_out['tweet_time_raw'] = $tweet_in['created_at'];		

		$tweet_out['tweet_id'] = $tweet_in['id_str'];
	
		$twitter_base = 'https://twitter.com/';
		$tweet_out['user_url'] = $twitter_base . $tweet_out['user_screen_name'];
		$tweet_out['tweet_url'] = $tweet_out['user_url'] . "/status/" . $tweet_out['tweet_id'];
		$tweet_out['reply_url'] = $twitter_base . 'intent/tweet?in_reply_to=' . $tweet_out['tweet_id'];
		$tweet_out['retweet_url'] = $twitter_base . 'intent/retweet?tweet_id=' . $tweet_out['tweet_id'];
		$tweet_out['favorite_url'] = $twitter_base . 'intent/favorite?tweet_id=' . $tweet_out['tweet_id'];			
	
 		//$tweet_out['user'] = $m->render('<a class="tweet_user" href="{{user_url}}">{{user_screen_name}}</a>', $tweet_out); 
        //$tweet_out['avatar'] = $m->render('<a class="tweet_avatar" href="{{user_url}}"><img src="{{avatar_url}}" alt="{{user_screen_name}}\'s avatar" title="{{user_screen_name}}\'s avatar" border="0"/></a>', $tweet_out) ;
		
        $tweet_out['reply_action'] = $m->render('<a class="tweet_action tweet_reply" href="{{reply_url}}">reply</a>', $tweet_out);
        $tweet_out['retweet_action'] = $m->render('<a class="tweet_action tweet_retweet" href="{{retweet_url}}">retweet</a>', $tweet_out);
        $tweet_out['favorite_action'] = $m->render('<a class="tweet_action tweet_favorite" href="{{favorite_url}}">favorite</a>', $tweet_out);	
		
		$tweet_out['tweet_has_image'] = isset($tweet_in['entities']['media'][0]['type']) ? ($tweet_in['entities']['media'][0]['type'] == "photo") : false;
		if ($tweet_out['tweet_has_image']) {
			$tweet_out['tweet_image_url'] = $tweet_in['entities']['media'][0]['url'];
			$tweet_out['tweet_image_expanded_url'] = $tweet_in['entities']['media'][0]['expanded_url'];
			$tweet_out['tweet_image_media_url'] = $tweet_in['entities']['media'][0]['media_url'];
			$tweet_out['tweet_image_display_url'] = $tweet_in['entities']['media'][0]['display_url'];		
			//$tweet_out['tweet_image_link'] = $m->render('<a href="{{tweet_image_url}}" class="tweet_action tweet_show_image" target="_blank">{{tweet_image_display_url}}</a>', $tweet_out);
			//$tweet_out['tweet_image'] = $m->render('<a href="{{tweet_image_url}}" target="_blank"><img src="{{tweet_image_media_url}}:thumb" title="{{tweet_text_raw}}" alt="{{tweet_text_raw}}" class="tweet_image" /></a>', $tweet_out);
		}
		//$tweet_out['tweet_text'] = garees_twitter_stream_link($tweet_out['tweet_has_image'] ? str_replace($tweet_out['tweet_image_url'], "", $tweet_out['tweet_text_raw'] ) : $tweet_out['tweet_text_raw']);
		$output[$i] = $tweet_out;
		$i++;
	}
	return $output;
}

/**
 * Prepare data before rendering
 */
function garees_twitter_stream_prepare($tweets, $date_format, $new_window) {
	$m = new Mustache;
	// go through tweets and extend the array
	foreach($tweets as &$tweet) {
		if (isset($new_window)) {
			$tweet['user'] = $m->render('<a class="tweet_user" href="{{user_url}}" target="_blank">{{user_screen_name}}</a>', $tweet); 
			$tweet['avatar'] = $m->render('<a class="tweet_avatar" href="{{user_url}}" target="_blank"><img src="{{avatar_url}}" alt="{{user_screen_name}}\'s avatar" title="{{user_screen_name}}\'s avatar" border="0"/></a>', $tweet) ;
			$tweet['tweet_text'] = garees_twitter_stream_link($tweet['tweet_has_image'] ? str_replace($tweet['tweet_image_url'], "", $tweet['tweet_text_raw'] ) : $tweet['tweet_text_raw'], true);
			$tweet['tweet_image_link'] = $m->render('<a href="{{tweet_image_url}}" class="tweet_action tweet_show_image" target="_blank">{{tweet_image_display_url}}</a>', $tweet);
			$tweet['tweet_image'] = $m->render('<a href="{{tweet_image_url}}" target="_blank"><img src="{{tweet_image_media_url}}:thumb" title="{{tweet_text_raw}}" alt="{{tweet_text_raw}}" class="tweet_image"/></a>', $tweet);
		} else {
			$tweet['user'] = $m->render('<a class="tweet_user" href="{{user_url}}">{{user_screen_name}}</a>', $tweet); 
			$tweet['avatar'] = $m->render('<a class="tweet_avatar" href="{{user_url}}"><img src="{{avatar_url}}" alt="{{user_screen_name}}\'s avatar" title="{{user_screen_name}}\'s avatar" border="0"/></a>', $tweet) ;
			$tweet['tweet_text'] = garees_twitter_stream_link($tweet['tweet_has_image'] ? str_replace($tweet['tweet_image_url'], "", $tweet['tweet_text_raw'] ) : $tweet['tweet_text_raw'], false);
			$tweet['tweet_image_link'] = $m->render('<a href="{{tweet_image_url}}" class="tweet_action tweet_show_image">{{tweet_image_display_url}}</a>', $tweet);
			$tweet['tweet_image'] = $m->render('<a href="{{tweet_image_url}}"><img src="{{tweet_image_media_url}}:thumb" title="{{tweet_text_raw}}" alt="{{tweet_text_raw}}" class="tweet_image" /></a>', $tweet);
		}	
		
		$tweet['tweet_time_formatted'] = date($date_format, strtotime($tweet['tweet_time_raw']));
		$tweet['tweet_time_relative'] = garees_twitter_stream_relative_time($tweet['tweet_time_raw']);
		$tweet['tweet_time'] = $m->render('<span class="tweet_time"><a href="{{tweet_url}}" title="view tweet on twitter">{{tweet_time_relative}}</a></span>', $tweet);
	}
	return $tweets;
}


add_filter('the_posts', 'garees_twitter_stream_scripts_and_styles'); // the_posts gets triggered before wp_head
add_filter('widget_text', 'garees_twitter_stream_scripts_and_styles_widget'); // load css if shortcode is found in text-widget

/*
 * Find shortcode and extract css-filename to enqueue the correct stylesheet
 */    
function garees_twitter_stream_scripts_and_styles($posts){
	if (empty($posts)) return $posts;
 
	$shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
	$css_files = array();
	foreach ($posts as $post) {
		// find shortcodes with template (and extract template-name)
		if (preg_match_all("/\[twitter_stream[0-9a-zA-Z =\/,\._\-'\"]* template=['\"]{0,1}([0-9a-zA-Z_]*)['\" \]][0-9a-zA-Z =\/,\._\-'\"]*/", $post->post_content, $matches, PREG_PATTERN_ORDER) > 0) {	
			$shortcode_found = true; // bingo!
			$css_files = $matches[1];
			//array_merge($css_files, $matches[1]);
			//break;
		}
		// find shortcodes without template (default-shortcode)
		if (preg_match("/\[twitter_stream[0-9a-zA-Z =\/,\._\-'\"]*\]/", $post->post_content, $matches) > 0) {	
			$shortcode_found = true; // bingo!
			if (stristr($matches[0],"template") === false)
				array_push($css_files, "default");
			//break;
		}		
	}
 
	if ($shortcode_found) {
		// enqueue here
		wp_enqueue_script('garees-twitter-stream-js', "https://platform.twitter.com/widgets.js");
		foreach($css_files as $css_file) {
			if (file_exists(plugin_dir_path(__FILE__)."templates/" . $css_file.".css")) {
				wp_enqueue_style('garees-twitter-stream-'.$css_file, plugin_dir_url(__FILE__).'templates/'.$css_file.".css");
			}
		}
	}
 
	return $posts;
}

/*
 * Find shortcode and extract css-filename to enqueue the correct stylesheet if in text-widget!
 */    
function garees_twitter_stream_scripts_and_styles_widget($text) {
	
	$shortcode_found = false;
	$css_file = null;
	if (preg_match_all("/\[twitter_stream[0-9a-z =']* template=['\"]{0,1}([0-9a-z _]*)['\" \]]/", $text, $matches, PREG_PATTERN_ORDER) > 0) {	
		$shortcode_found = true; // bingo!   !!! Findet nur in der ersten Post (var wird überschrieben, man müsste push machen) !!!!!
		$css_files = $matches[1]; 
	}
	if ($shortcode_found) {
		// enqueue here
		wp_enqueue_script('garees-twitter-stream-js', "https://platform.twitter.com/widgets.js");
		foreach($css_files as $css_file) {
			if (file_exists(plugin_dir_path(__FILE__)."templates/" . $css_file.".css")) {
				//$text .= '<link rel="stylesheet" id="garees-twitter-stream-'.$css_file.'"  href="' . plugin_dir_url(__FILE__) . 'templates/'.$css_file . '.css" type="text/css" media="all" />'. "\n";
				wp_enqueue_style('garees-twitter-stream-'.$css_file, plugin_dir_url(__FILE__).'templates/'.$css_file.".css");
			}
		}
	}
	
	return $text;
}

// add shortcodes
add_shortcode( 'twitter_stream', 'garees_twitter_stream' );
add_shortcode( 'twitter_stream_template', 'garees_twitter_stream' );


/*
 * Register the Plugin-Description-Page
 */
function garees_twitter_stream_plugin_menu() {
	add_plugins_page("Garee's Twitter Stream", "Garee's Twitter Stream", 'read', 'garees_twitter_stream', 'garees_twitter_stream_show_menu');
}

/*
 * Include CSS- and JS-File in the header
 */ 
function garees_twitter_stream_head() {
		
	if(is_admin()) {
	
		// load admin css
		if(!defined('GAREE_ADMINCSS_IS_LOADED')) {
			echo '<link rel="stylesheet" id="garees-admin-css"  href="' . plugins_url('garee_admin.css', __FILE__) . '" type="text/css" media="all" />'. "\n";
			define('GAREE_ADMINCSS_IS_LOADED', true);
		}
				
		// Javascript für Flattr einfügen
	//	if(!defined('GAREE_FLATTRSCRIPT_IS_LOADED')) {
	//		echo '<script type="text/javascript" src="' . GAREE_FLATTRSCRIPT . '"></script>';
	//		define('GAREE_FLATTRSCRIPT_IS_LOADED', true);
	//	}
	}
}

/*
 * Insert a Description (Settings)-Link on the plugin-overview
 */    
function garees_twitter_stream_plugin_actions( $links, $file ){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="plugins.php?page=garees_twitter_stream">' . __('Description') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}


/*
 * Generate Description-Page for the Admin
 */
function gareeBoxTwitterStream() {
?>

<div id="gareeBox"> <small>If you like Garee's Twitter Stream plugin, you can buy me a coffee!<br />
  </small><br />
  <a href="http://flattr.com/thing/439635/Garees-Twitter-Stream" target="_blank"> <img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
  <!--</noscript>-->
  <br />or<br />
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
  </form>
</div>
<?php
}

/*
 * Generate Description-Page for the Admin
 */
function garees_twitter_stream_show_menu() {
	gareeBoxTwitterStream();
?>
<script type="text/javascript">
<!--
var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
//-->
</script>
<div id='gareeMain'>
  <h1>Garee's Twitter Stream</h1>
  <p>Just insert the following shortcode anywhere in your blog (for use in a widget: use the text-widget and insert the shortcode there) </p>
  <pre>[twitter_stream user='your_twitter_account'] </pre>
  <h2>Attributes</h2>
  <p>Here's a list of all attributes the shortcode accepts. Additional attributes may be available depending on the chosen template</p>
  <dl>
    <dt>template</dt>
    <dd> a predefined template consisting of a html- and optionally a css-file in the subdirectory &quot;templates&quot; of the plugin-folder. If left blank twitter's &quot;embed&quot;-template  is chosen.</dd>
    <dd>
      <?php
if ($handle = opendir(plugin_dir_path(__FILE__) . "templates")) {
	
    while (false !== ($file = readdir($handle))) {		
        if ($file != "." && $file != ".." ) {
			$file_arr = explode(".", $file);
			if (isset($file_arr[1])) {
				if ($file_arr[1]=="html") {
					$template_html[$file_arr[0]] = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/' . $file);	
				} else if ($file_arr[1]=="css") {
					$template_css[$file_arr[0]] = wp_remote_fopen(plugin_dir_url(__FILE__) . 'templates/' . $file);
				}
			}
        }
    }
    closedir($handle);
	
	echo "<table><tr><th>name</th><th>html</th><th>css</th><th>description</th></tr>";
	foreach($template_html as $key => $value)  { 
		echo "<td>$key</td><td>yes (<a href='".plugin_dir_url(__FILE__)."templates/".$key.".html' target='_blank' class='tooltip'>show<span class='classic code'>".htmlspecialchars($value)."</span></a>)</td>";
		if (isset($template_css[$key]))
			echo "<td>yes (<a href='".plugin_dir_url(__FILE__)."templates/".$key.".css' target='_blank' class='tooltip'>show<span class='classic code'>".$template_css[$key]."</span></a>)</td>"; 
		else 
			echo "<td>no</td>";	
		
		if (preg_match("/{{!(.*)}}/", $value, $matches) == 1) {
			echo "<td>".make_clickable(trim($matches[1]))."</td>";
		} else {
			echo "<td>-</td>";		
		}
		echo "</tr>";
	}
	echo "</table>";
}

?>
    </dd>
    <dd> If the template-attribute is missing you have to define a template yourself (*). This can be done using the following enclosing shortcut:</dd>
  </dl>
  <pre>[twitter_stream_template]*[/twitter_stream_template]</pre>
  <dl>
    <dd> Check out the examples below to see how to define your own template. </dd>
    <dt>user</dt>
    <dd>If you would like the plugin to display the tweets of a certain user, set  &quot;user&quot; to this twitter-user. This option cannot be combined with any of the following options: &quot;list&quot;, &quot;favorites&quot;, &quot;search&quot;</dd>
    <dt>list</dt>
    <dd>If you would like the plugin to display the tweets from certain user's list, set  &quot;list&quot; to &quot;user/list&quot;. This option cannot be combined with any of the following options: &quot;user&quot;, &quot;favorites&quot;, &quot;search&quot;</dd>
    <dt>favorites</dt>
    <dd>If you would like the plugin to display the favorite tweets of a certain user, set  &quot;favorites&quot; to this twitter-user. This option cannot be combined with any of the following options: &quot;user&quot;, &quot;list&quot;, &quot;search&quot;</dd>
    <dt>search</dt>
    <dd>If you would like the plugin to display the results of a twitter-search, enter any search-term as option &quot;search&quot;. This option cannot be combined with any of the following options: &quot;user&quot;, &quot;list&quot;, &quot;favorites&quot;</dd>
    <dt>geocode</dt>
    <dd>Limit your search to a specific area. &quot;geocode&quot; has to be of the form &quot;lat,lon,radius&quot; (eg. &quot;53.273,-7.494,50mi&quot;). Can be combined with &quot;search&quot;, but not with &quot;user&quot;, &quot;list&quot; or &quot;favorites&quot; <span class="version">(v0.6+)</span></dd>
    <dd>
    <?php  echo "find coordinates: <img onclick=\"tb_show('google maps','".plugin_dir_url(__FILE__)."garees_twitter_stream_googlemap.php?TB_iframe=true&height=' + eval(window.y-100) + '&width=' + eval(window.x-100),false)\" style='cursor:pointer;' src='".plugin_dir_url(__FILE__)."images/map.png' />";
	?> <span class="version">(v0.9+)</span></dd>

    <dt>count</dt>
    <dd>Specify how many tweets should be displayed. The default-value is 10.</dd>
    <dt>since_id</dt>
    <dd>Returns results with an ID greater than (that is, newer than) or equal to the specified ID. <span class="version">(v0.9+)</span></dd>
    <dt>max_id</dt>
    <dd>Returns results with an ID less than (that is, older than) or equal to the specified ID. (Doesn't work for search-option. Use 'until' instead.)<span class="version"> (v0.9+)</span></dd>
    <dt>until</dt>
    <dd>Returns tweets generated before the given date. Date should be formatted as YYYY-MM-DD. (Only works for the search-option. Use 'max_id' instead.) <span class="version">(v0.9+)</span></dd>
    <dt>lang</dt>
    <dd>Restricts tweets to the given language, given by an <a href="http://www.loc.gov/standards/iso639-2/php/English_list.php" target="_blank">ISO 639-1</a> code.</dd>
    <dt>result_type</dt>
    <dd> Specifies what type of search results you would prefer to receive. The current default is &quot;mixed.&quot; Valid values include: &quot;mixed&quot;, &quot;recent&quot; and &quot;popular&quot;</dd>
    <dt>inlcude_rts</dt>
    <dd> Set to true, t or 1 to inlcude retweets.</dd>
    <dt>exclude_replies</dt>
    <dd>Set to true, t or 1 to exclude replies.</dd>
    <dt>cache_time</dt>
    <dd>Specify how long the tweets should be cached (in minutes). The default cache-time is 15 minutes. (a low value may result in rate limit exceeding and no answers from twitter.com!)</dd>
    <dt>date_format</dt>
    <dd> Choose the format for the Mustache-tag {{tweet_date_formatted}}. For more information check out the syntax of <a href="http://php.net/manual/en/function.date.php" target="_blank">PHP-Date-function</a>'s format-parameter.</dd>
    <dt>new_window</dt>
    <dd>Set to true, t or 1 to open any links in the tweet in a new window. <span class="version">(v1.0+)</span></dd>
  </dl>
  <h2>Some Examples</h2>
  <p>The following examples are shown on my plugin-site: <a href="http://www.garee.ch/wordpress/garees-twitter-stream/live-demo/" target="_blank">Live-Demo</a></p>
  <h3>Example 1</h3>
  Show 5 tweets from the user CoupaCafe including retweets:
  <pre>[twitter_stream user="CoupaCafe" count=5 include_rts="true"]</pre>
  <h3>Example 2</h3>
  <p>Show latest 2 tweets from CoupaCafe using the list-template including retweets and open any links in a separate window</p>
  <pre>[twitter_stream template=&quot;list&quot; user=&quot;CoupaCafe&quot; new_window=&quot;true&quot; include_rts=&quot;true&quot; count=2]</pre>
   <h3>Example 3</h3>
  The minimal-template for use in a sidebar showing 3 popular tweets for the hashtag #cardinals within a 200 miles radius of Standford University:
  <pre>[twitter_stream template="minimal" search="#cardinals" result_type="popular" geocode="37.422590,-122.16541,200mi" count=3]</pre>
  <h3>Example 4</h3>
  The template "latesttweets" showing the 3 latest tweets of CoupaCafe's student list.
  <pre>[twitter_stream template="latesttweets" list="CoupaCafe/students" count=3]</pre>
  <h3>Example 5</h3>
  "Tweetboxes" showing 2 of CoupaCafe's favorites tweets:
  <pre>[twitter_stream template="tweetboxes" favorites="CoupaCafe" count=2]</pre>
  Can be customized with the custom-options "background" and "text":
  <pre>[twitter_stream template="tweetboxes" favorites="CoupaCafe" background="#333" text="rgba(220,180,200,0.8)" count=2]</pre>
  <h3>Example 6</h3>
  Showing one tweet via its ID using the twittersweet-template
  <pre>[twitter_stream template="twittersweet" id="144612482730827776"]</pre>
  alternate design with the option "dark" set to true:
  <pre>[twitter_stream dark="true" template="twittersweet" id="144612482730827776"]</pre>
  <h3>Example 7</h3>
  The twitterbird-template showing the last tweet of the user "garee76" (excluding replies)
  <pre>[twitter_stream template="twitterbird" user="garee76" exclude_replies="true" count=1]</pre>
  small version:
  <pre>[twitter_stream small="true" template="twitterbird" user="garee76" exclude_replies="true" count=1]</pre>
  <h3>Example 8</h3>
  show only images with the image-template
  <pre>[twitter_stream template="images" user="garee76" count=100]</pre>
  <h3>Example 9</h3>
  Showing avatars of 12 tweet-users of the public twitter-timeline. Set cache-time to 60 minutes.
  <pre>[twitter_stream template="avatars" cache_time=60 count=12]</pre>
  <h3>Example 10</h3>
  Define your own template and show in a sentence the time of user's last tweet
  <pre>[twitter_stream_template user="garee76" count=1]My latest tweet was sent {{#items}}{{tweet_time_relative}}{{/items}}[/twitter_stream_template]</pre>
  <h2>Template-Files</h2>
  <p>A couple of templates come with the plugin. You can write and install additional templates yourself.  A template consists of a html-file with the Mustache-template and (optionally) a css-file with the same filename as the html-file. If your css-code has images and javascript-files, put them in a subfolder of the templates-folder named after your template. Then upload all files via FTP and insert the shortcode for your template. If you define your template directly in the shortcode, you cannot link to a additional css-file. Of course you can include css-styling directly into the html-code of your template.</p>
  <h2>Mustache-Templates</h2>
  <p>The following components can be used to build your own html-template. Additional components can be generated by inserting custom attributes in the shortcode. The components are only available in the html-template, not in the css-file!  Check out the template-files in the plugin to see how it's still possible to change css-values with custom attributes from your shortcode. For more infos about Mustache-syntax check out the PHP-implementation and the manuals on <a href="http://mustache.github.com/" target="_blank">http://mustache.github.com/</a></p>
  <dl class='mustache_list'>
    <dt>{{user_screen_name}}</dt>
    <dd>screen name of the user who posted the tweet</dd>
    <dt>{{user_name}}</dt>
    <dd>full name of the user who posted the tweet</dd>
    <dt>{{{user}}}</dt>
    <dd>the user's screen name linked to his twitter-profile. Generated using the following template: <span class="using">&lt;a class=&quot;tweet_user&quot; href=&quot;{{user_url}}&quot;&gt;{{user_screen_name}}&lt;/a&gt;</span></dd>
    <dt>{{avatar_url}}</dt>
    <dd>url of the user's profile-image</dd>
    <dt>{{{avatar}}}</dt>
    <dd>the user's profile-image linked to the tweet. Generated using the following template: <span class="using">&lt;a class=&quot;tweet_avatar&quot; href=&quot;{{user_url}}&quot;&gt;&lt;img src=&quot;{{avatar_url}}&quot; alt=&quot;{{user_screen_name}}'s avatar&quot; title=&quot;{{user_screen_name}}'s avatar&quot; border=&quot;0&quot;/&gt;&lt;/a&gt;</span></dd>
    <dt>{{tweet_text_raw}}</dt>
    <dd>tweet text plain, no links</dd>
    <dt>{{{tweet_text}}}</dt>
    <dd>tweet text including links to twitter-tags and -users and any other links made clickable</dd>
    <dt>{{tweet_is_retweet}}</dt>
    <dd>true if tweet is a retweet</dd>
    <dt>{{retweeted_by_screen_name}}</dt>
    <dd>if tweet is a retweet: screen name of the user that retweeted</dd>    
    <dt>{{retweeted_by_name}}</dt>
    <dd>if tweet is a retweet: full name of the user that retweeted</dd>
    <dt>{{{tweet_source}}}</dt>
    <dd>source of the tweet with clickable links</dd>
    <dt>{{tweet_time_raw}}</dt>
    <dd>tweet time</dd>
    <dt>{{tweet_time_formatted}}</dt>
    <dd>tweet time (formatted with the date_format-option or automatically if option missing)</dd>
    <dt>{{tweet_time_relative}}</dt>
    <dd>relative tweet time (e.g. 5 min ago)</dd>
    <dt>{{{tweet_time}}}</dt>
    <dd>relative tweet time linked to the tweet. Generated using the following template: <span class="using">&lt;span class=&quot;tweet_time&quot;&gt;&lt;a href=&quot;{{tweet_url}}&quot; title=&quot;view tweet on twitter&quot;&gt;{{tweet_time_relative}}&lt;/a&gt;</span></dd>
    <dt>{{tweet_has_image}}</dt>
    <dd>true if the tweet contains an image</dd>
    <dt>{{tweet_image_url}}</dt>
    <dd>the url which leads to the twitter-page that shows the image. (short form)</dd>
    <dt>{{tweet_image_expanded_url}}</dt>
    <dd>the url which leads to the twitter-page that shows the image. (expanded form)</dd>
    <dt>{{tweet_image_media_url}}</dt>
    <dd>the url of the image (twitter offers 4 sizes: &quot;thumb&quot;, &quot;small&quot;, &quot;medium&quot;, &quot;large&quot;. For the large image use &quot;{{tweet_image_url}}:large&quot;. Also check out the template &quot;images&quot;.</dd>
    <dt>{{tweet_image_display_url}}</dt>
    <dd>shortended url to display as link to the image</dd>
    <dt>{{{tweet_image_link}}}</dt>
    <dd>text-link to the image on twitter.com. Generated using the following template: <span class="using">&lt;a href=&quot;{{tweet_image_url}}&quot; class=&quot;tweet_action tweet_show_image&quot; target=&quot;_blank&quot;&gt;{{tweet_image_display_url}}&lt;/a&gt;</span></dd>
    <dt>{{{tweet_image}}}</dt>
    <dd>tweet_image as thumbnail linked to the image on twitter.com. Generated using the following template: <span class="using">&lt;a href=&quot;{{tweet_image_url}}&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;{{tweet_image_media_url}}:thumb&quot; title=&quot;{{tweet_text_raw}}&quot; alt=&quot;{{tweet_text_raw}}&quot; class=&quot;tweet_image&quot; /&gt;&lt;/a&gt;</span></dd>
    <dt>{{tweet_url}}</dt>
    <dd>url to the tweet on twitter.com.</dd>
    <dt>{{reply_url}}</dt>
    <dd>url to reply to the current tweet.</dd>
    <dt>{{retweet_url}}</dt>
    <dd>url to retweet the current tweet.</dd>
    <dt>{{favorite_url}}</dt>
    <dd>url to add the current tweet to favorites.</dd>
    <dt>{{{reply_action}}}</dt>
    <dd>link to reply to the current tweet. Generated using the following template: <span class="using">&lt;a class=&quot;tweet_action tweet_reply&quot; href=&quot;{{reply_url}}&quot;&gt;reply&lt;/a&gt;</span></dd>
    <dt>{{{retweet_action}}}</dt>
    <dd> link to retweet the current tweet. Generated using the following template: <span class="using">&lt;a class=&quot;tweet_action tweet_retweet&quot; href=&quot;{{retweet_url}}&quot;&gt;retweet&lt;/a&gt;</span></dd>
    <dt>{{{favorite_action}}}</dt>
    <dd>link to add the current tweet to favorites. Generated using the following template: <span class="using">&lt;a class=&quot;tweet_action tweet_favorite&quot; href=&quot;{{favorite_url}}&quot;&gt;favorite&lt;/a&gt;</span></dd>
    <dt>{{template_dir}}</dt>
    <dd>url of the template-directory. Can be used to get access to the template's image-files from the html-template</dd>
  </dl>
  <p>Moreover any shortcode-option is available as mustache-tag. E.g. if you make a twitter-search and set a value for "search" then you can use {{search}} in your template.</p>
</div>
<?php
}

if (!is_admin())                                   // make sure shortcode is done in the widget
  add_filter('widget_text', 'do_shortcode', 11);   // http://hackadelic.com/the-right-way-to-shortcodize-wordpress-widgets

// register actions for admins
if(is_admin()) {
	add_action('admin_menu', 'garees_twitter_stream_plugin_menu');
	add_action('admin_head', 'garees_twitter_stream_head');
	add_action('plugin_action_links','garees_twitter_stream_plugin_actions',10, 2);
	add_action('admin_enqueue_scripts', 'garees_twitter_stream_enqueue');
}

/*
 * Include CSS- and JS-File in the header (thickbox)
 */ 
function garees_twitter_stream_enqueue() {
	// activate thickbox
	if(!isset($_GET['page']))
		return;
	if( $_GET['page'] != "garees_twitter_stream" )
        return;
	wp_enqueue_script( 'thickbox', get_option('home').'/wp-includes/js/thickbox/thickbox.js', array ('thickbox'), '3.1-20080430' );
	echo "<link rel='stylesheet' href='".get_option('home')."/wp-includes/js/thickbox/thickbox.css?ver=20080613' type='text/css' media='all' />\n";
}

?>
