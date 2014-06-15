<?php

$center = ct_get_current_center();
$group_id = get_post_meta($center, 'ct_group_id', true);
if ($group_id) {
	$forum_id = groups_get_groupmeta( $group_id, 'forum_id' );
	$forum = get_post($forum_id);
	$perma = ct_replace_domain($forum->guid, get_post_meta($center, 'ct_center_domain', true));	
	wp_redirect($perma);
}

?>