<?php 
	define("ABSPATH", str_replace("wp-content/plugins/garees-twitter-stream", "", dirname(__FILE__)));

	
	//The inclusion of these files allows full use of all functions of wordpress
	require_once(ABSPATH.'wp-load.php');
	require_once(ABSPATH.'wp-admin/includes/admin.php');
	
	require_once('twitteroauth/twitteroauth.php');
	
	/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
	$connection = new TwitterOAuth(GAREE_TWITTER_STREAM_CONSUMER_KEY, GAREE_TWITTER_STREAM_CONSUMER_SECRET, get_transient('GAREE_TWITTER_STREAM_OAUTH_TOKEN'), get_transient('GAREE_TWITTER_STREAM_OAUTH_TOKEN_SECRET'));
	
	/* Request access tokens from twitter */
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	
	/* Save the access tokens. Normally these would be saved in a database for future use. */
	update_option('garee_twitter_stream_access_token', $access_token['oauth_token']);
	update_option('garee_twitter_stream_access_token_secret', $access_token['oauth_token_secret']);
	
	/* If HTTP response is 200 continue otherwise send to connect page to retry */
	if (200 == $connection->http_code) {
	  header('Location: '.admin_url().'plugins.php?page=garees_twitter_stream');
	} else {
	  /* Save HTTP status for error dialog on connnect page.*/
	  $content = "error!";
	}
	
?>
<html>
<head>
<?php
	echo '<link rel="stylesheet" id="garees-admin-css"  href="' . plugins_url('garee_admin_thickbox.css', __FILE__) . '" type="text/css" media="all" />'. "\n";
	echo '<link rel="stylesheet" id="admin-css"  href="' . admin_url() . 'css/global.css" type="text/css" media="all" />'. "\n";
	echo '<link rel="stylesheet" id="admin-css"  href="' . admin_url() . 'css/wp-admin.css" type="text/css" media="all" />'. "\n";
	?>
</head>
<body>
<div id="gareeMain">
	<p><?php echo $content; ?></p>
</div>
</body>
</html>