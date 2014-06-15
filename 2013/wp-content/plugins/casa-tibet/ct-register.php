<?php
require_once(dirname(__FILE__) . '/../../../wp-load.php');

if ($_POST) {
	
	if (email_exists($_POST['signup-email'])) {
		wp_redirect(add_query_arg('error', 'email-exists', $_POST['return-url']));
		exit();
	
	} else {
	
		$userdata = array('user_login'=>$_POST['signup-username'],
			  'user_email'=>$_POST['signup-email'],
			  'first_name'=>$_POST['signup-name-first'],
			  'last_name'=>$_POST['signup-name-last'],
			  'display_name'=>$_POST['signup-name-first'].' '.$_POST['signup-name-last'],
			  'user_pass'=>$_POST['signup-pass']);
		$u_id = wp_insert_user($userdata);
		
		$user = get_user_by('id', $u_id);
		$user->add_role('bbp_participant');
	
		update_user_meta( $u_id, 'show_admin_bar_front', 'false');
		update_user_meta( $u_id, 'show_admin_bar_admin', 'false');
		wp_redirect(add_query_arg('success', '1', $_POST['return-url']));
		exit();
		
	}
	
	

	
}

?>