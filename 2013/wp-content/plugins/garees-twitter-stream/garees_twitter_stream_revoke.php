<?php 
	define("ABSPATH", str_replace("wp-content/plugins/garees-twitter-stream", "", dirname(__FILE__)));
	
	//The inclusion of these files allows full use of all functions of wordpress
	require_once(ABSPATH.'wp-load.php');
	require_once(ABSPATH.'wp-admin/includes/admin.php');
	
	delete_option('garee_twitter_stream_access_token');
	delete_option('garee_twitter_stream_access_token_secret');

	header('Location: '.admin_url().'plugins.php?page=garees_twitter_stream');

	
?>