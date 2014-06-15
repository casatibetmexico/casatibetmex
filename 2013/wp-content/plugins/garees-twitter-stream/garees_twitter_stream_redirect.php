<?php 
	define("ABSPATH", str_replace("wp-content/plugins/garees-twitter-stream", "", dirname(__FILE__)));

	
	//The inclusion of these files allows full use of all functions of wordpress
	require_once(ABSPATH.'wp-load.php');
	require_once(ABSPATH.'wp-admin/includes/admin.php');
	
	//define("GAREE_TWITTER_STREAM_OAUTH_CALLBACK", admin_url().'plugins.php?page=garees_twitter_stream');
	define("GAREE_TWITTER_STREAM_OAUTH_CALLBACK", plugin_dir_url(__FILE__)."garees_twitter_stream_callback.php");
	
	require_once('twitteroauth/twitteroauth.php');

	/* Build TwitterOAuth object with client credentials. */
	$connection = new TwitterOAuth(GAREE_TWITTER_STREAM_CONSUMER_KEY, GAREE_TWITTER_STREAM_CONSUMER_SECRET);
	 
	/* Get temporary credentials. */
	$request_token = $connection->getRequestToken(GAREE_TWITTER_STREAM_OAUTH_CALLBACK);
	
	/* Save temporary credentials to session. */
	$token = $request_token['oauth_token'];
	set_transient("GAREE_TWITTER_STREAM_OAUTH_TOKEN",$token,60);
	set_transient("GAREE_TWITTER_STREAM_OAUTH_TOKEN_SECRET",$request_token['oauth_token_secret'],60);
	
	 
	/* If last connection failed don't display authorization link. */
	switch ($connection->http_code) {
	  case 200:
	    /* Build authorize URL and redirect user to Twitter. */
	    $url = $connection->getAuthorizeURL($token);
	    header('Location: ' . $url); 
	    break;
	  default:
	    /* Show notification if something went wrong. */
	    $content = 'Could not connect to Twitter. Refresh the page or try again later.';
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