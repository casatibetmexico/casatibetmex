<?php
$term =	$wp_query->queried_object;
$page = get_page_by_path('/programa');
$url = add_query_arg('course-type', $term->slug, get_permalink($page->ID));
wp_redirect($url);
?>