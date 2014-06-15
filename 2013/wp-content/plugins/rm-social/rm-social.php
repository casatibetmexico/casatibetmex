<?php
/*
Plugin Name: RM Social Sharing
Description: Provides a simple interface for sharing content with common social networking sites.
Author: Joe Flumerfelt
Version: 0.1
*/

add_action('wp_enqueue_scripts', 'rm_social_scripts', 0);
function rm_social_scripts() {
	wp_enqueue_style('rm-social-css', plugins_url('rm-social.css', __FILE__));
	wp_enqueue_script('jquery-popup', plugins_url('jquery.popupWindow.js', __FILE__), array('jquery'));
	wp_enqueue_script('rm-social', plugins_url('rm.social.js', __FILE__), array('rm-class'));
}

function rm_social_get_url($type, $target, $title='', $image='', $summary='') {
	$title = urlencode($title);
	switch($type) {
		case 'facebook': 
			$args = array('s=100', 'p[url]='.$target);
			if ($title) $args[] = 'p[title]='.$title;
			if ($image) $args[] = 'p[images][0]='.$image;
			if ($summary) $args[] = 'p[summary]='.$summary;
			//return 'http://www.facebook.com/sharer.php?'.implode('&', $args);
			return 'https://www.facebook.com/sharer/sharer.php?u='.$target;
			break;
		case 'twitter': 
			return sprintf('http://twitter.com/home?status=%s+%s', $title, $target);
			break;
		case 'gplus':
			return sprintf('https://plus.google.com/share?url=%s', $target);
			break;
	}
}

function rm_social_get_tweets($url) {
 
    $json_string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url=' . $url);
    $json = json_decode($json_string, true);
 
    return intval( $json['count'] );
}
 
function rm_social_get_likes($url) {
 
    $json_string = file_get_contents('http://graph.facebook.com/?ids=' . $url);
    $json = json_decode($json_string, true);
 
    return intval( $json[$url]['shares'] );
}
 
function rm_social_get_plusones($url) {
 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    $curl_results = curl_exec ($curl);
    curl_close ($curl);
 
    $json = json_decode($curl_results, true);
 
    return intval( $json[0]['result']['metadata']['globalCounts']['count'] );
}

?>