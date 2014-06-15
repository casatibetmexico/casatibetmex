<?php

function custom_login_css() {
	echo '<link rel="stylesheet" type="text/css" href="'.get_template_directory_uri().'/login/login-styles.css" />';
}
add_action('login_head', 'custom_login_css');

?>