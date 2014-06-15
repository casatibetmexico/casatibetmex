<?php

require_once(dirname(__FILE__) . '/../../../wp-load.php');

$args = array('post_type'=>'post',
			  'nopaging'=>true,
			  'fields'=>'ids');
$args['meta_query'][] = array('key'=>'ct_center',
							  'value'=>'',
							  'compare'=>'NOT EXISTS');
							  
$q = new WP_Query($args);
print "FOUND: ".count($q->posts);
foreach($q->posts as $p) {
	update_post_meta($p, 'ct_center', 0);
}

?>