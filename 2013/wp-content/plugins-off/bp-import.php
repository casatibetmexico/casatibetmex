<?php 
/*
Plugin Name: BuddyPress Import
Description: Bring all wordpress users into buddypress system
Author: Joe Flumerfelt
Version: 0.1
*/

register_activation_hook(__FILE__, 'add_users_to_bp');

function add_users_to_bp() {

    $users = get_users();

    foreach ($users as $user) {
        update_user_meta($user->ID, 'last_activity', date("Y-m-d H:i:s"));
    }
}
?>