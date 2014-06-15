<?php

add_action('wp_enqueue_scripts', 'ct_shortcode_enqueue_scripts');
function ct_shortcode_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-embed', plugins_url('casa-tibet').'/js/jquery.embed.js', array('jquery'));
}

function ct_load_template_part($template_name, $part_name=null) {
 	ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

function ct_set_template_args($atts, $content) {
	global $tArgs;
	$tArgs = array('atts'=>$atts,
				   'content'=>$content);
}

/* SHORT CODES *****************************************************/

function ct_attachment_shortcode( $atts, $content='' ) {
	
	$html = "";
	
	return $html;
}
add_shortcode( 'ct-attachment', 'ct_attachment_shortcode' );

function ct_form_shortcode( $atts, $content='' ) {
	
	$html = "";
		
	$template = ($atts['type']) ? $atts['type'] : 'contact';
		
	$html = rm_form($template, $atts, false);	
	
	return $html;
}
add_shortcode( 'ct-form', 'ct_form_shortcode' );

function ct_embed_shortcode( $atts, $content='' ) {
	
	$html = "";
		
	switch($atts['type']) {
		case 'gmap':
			if (!$atts['id']) $atts['id'] = 'map_embed';
			$html .= '<div id="'.$atts['id'].'" class="map"></div>';
			$html .= '<script type="text/javascript">';
			
			$z = ($atts['zoom']) ? $atts['zoom'] : 15;
			$url = sprintf('http://maps.google.com/maps?f=q&source=s_q&hl=es&geocode=&q=%s&z='.$z.'&iwloc=near&output=embed', urlencode($content));
			
			$html .= 'jQuery("#'.$atts['id'].'").embed({type:"'.$atts['type'].'", url:"'.$url.'", w:'.$atts['width'].', h:'.$atts['height'].'});';
			$html .= '</script>';
			break;
		case 'soundcloud':
			
			break;
		case 'vimeo':
		case 'youtube':
			if (!$atts['id']) $atts['id'] = 'video_'+$atts['media'];
			$html .= '<div id="'.$atts['id'].'" class="video"></div>';
			$html .= '<script type="text/javascript">';
			$html .= 'jQuery("#'.$atts['id'].'").embed({type:"'.$atts['type'].'", id:"'.$atts['media'].'", w:'.$atts['width'].', h:'.$atts['height'].'});';
			$html .= '</script>';
			break;
	}
	
	
	
	return $html;
}
add_shortcode( 'ct-embed', 'ct_embed_shortcode' );

function ct_intro_shortcode( $atts, $content='' ) {
	return '<div class="intro">'.$content.'</div>';
}
add_shortcode( 'ct-intro', 'ct_intro_shortcode' );

function ct_section_shortcode( $atts, $content='' ) {

	$title = '';
	
	if ($atts['title']) {
		$title = ($atts['href']) ? '<div class="title ui" data-href="'.ct_get_permalink($atts['href']).'">'.
								   '<div class="icon view"></div>'.
									$atts['title'].'</div>' 
								 : '<div class="title">'.$atts['title'].'</div>';
		
		$html = ($atts['href']) ? '<div class="section listing">' : '<div class="section">';
		$html .= $title;
		
	} else {
		$html = '<div class="section">';
	}
	
	$html .= do_shortcode($content);
	$html .= '</div>';
	return $html;
}
add_shortcode( 'ct-section', 'ct_section_shortcode' );

function ct_related_shortcode( $atts, $content='' ) {
	
	global $post;
	
	if ($post) {
	
		$parent = ($atts['id']) ? $atts['id'] : $post->post_parent;
		$args = array( 'order'=>'ASC',
							     'orderby'=>'menu_order title',
								 'nopaging'=>true,
								 'post_type'=>$post->post_type,
								 'post_parent'=>($atts['children']) ? $post->ID : $parent,
								 'post__not_in'=>array($post->ID));
	
		$args = ct_ctr_query_args($args);
		
		$q = new WP_Query($args);
								 
		$pages = $q->posts;
		if ($pages) {
			$GLOBALS['tPages'] = $pages;
			ct_set_template_args($atts, $content);
			return ct_load_template_part('shortcode', 'related');	
		}
	}
		
	
}
add_shortcode( 'ct-related', 'ct_related_shortcode' );

function ct_profile_shortcode($atts, $content) {
	ct_set_template_args($atts, $content);
	return ct_load_template_part('shortcode', 'profile');
}
add_shortcode( 'ct-profile', 'ct_profile_shortcode' );

function ct_order_filters($a, $b) {
	if ($a['term']->tax_order == $b['term']->tax_order) return 0;
	return ($a['term']->tax_order < $b['term']->tax_order) ? -1 : 1;
}

function ct_filter_shortcode($atts, $content) {
	global $tArgs;
	ct_set_template_args($atts, $content);
	
	
	$tax = get_taxonomy($atts['taxonomy']);
	$tArgs['atts']['title'] = $tax->label;
	
	$args = array('hide_empty'=>false);
	$terms = get_terms($atts['taxonomy'], $args);
	$items = array();
	foreach($terms as $t) {
		if ($t->parent > 0) {
			$p = $t->parent;
			$items[$p]['children'][] = $t;
		} else {
			$id = $t->term_id;
			$items[$id]['term'] = $t;
		}
	}
	
	$items = array_values($items);
	usort($items, 'ct_order_filters');
	$tArgs['items'] = $items;
	
	return ct_load_template_part('shortcode', 'filter');
}
add_shortcode( 'ct-filter', 'ct_filter_shortcode' );

?>