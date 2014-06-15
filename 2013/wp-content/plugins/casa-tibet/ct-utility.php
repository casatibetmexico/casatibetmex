<?php

/** PLUGIN FUNCTIONS *********************************************/

add_action('wp_enqueue_scripts', 'ct_enqueue_scripts');
function ct_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-cookie', plugins_url('casa-tibet').'/js/jquery.cookie.js', array('jquery'));
	wp_enqueue_script('ct-functions', plugins_url('casa-tibet').'/js/ct_functions.js', array('jquery','jquery-cookie'));
}



?>