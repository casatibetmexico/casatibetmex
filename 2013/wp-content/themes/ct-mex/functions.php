<?php

$GLOBALS['use_validator'] = true;

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'event-thumb', 200, 200, true );
	add_image_size( 'sidebar-thumb', 70, 70, true );
	add_image_size( 'main-thumb', 150, 100, true );
	add_image_size( 'teacher-profile', 319, 414, true );
}

if (function_exists('bbp_register_template_stack')) bbp_register_template_stack('ct_template_stack', 0);

function ct_template_stack() {
	return dirname(__FILE__).'/bbpress/includes/';
}

add_action('init', 'ct_set_globals');
if ( ! function_exists( 'ct_set_globals' ) ) {
	function ct_set_globals() {
	
		//flush_rewrite_rules();  
	
		$GLOBALS['site_title'] = "Casa Tibet México";
		$GLOBALS['site_subtitle'] = "Primera representación cultural oficial del pueblo tibetano en Latinoamérica";
		$GLOBALS['site_theme'] = 'ct-mex';
	}
}

add_action( 'widgets_init', 'ct_mex_widgets_init' );
function ct_mex_widgets_init() {

	register_sidebar( array(
		'name' => 'Page Navigation',
		'id' => 'ct_page_nav',
		'before_widget' => '<div class="page_nav">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
	
}

add_action('wp_enqueue_scripts', 'ct_mex_enqueue_scripts', 0);
function ct_mex_enqueue_scripts() {
	global $use_feature, $use_faq;
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('ct-class', get_bloginfo('template_url').'/js/Class.js', array('jquery'));
	wp_enqueue_script('ct-site', get_bloginfo('template_url').'/js/casatibet.js', array('jquery'));
	wp_enqueue_script('ct-nav-menu', get_bloginfo('template_url').'/js/jquery.nav_menu.js', array('jquery'));
	wp_enqueue_script('ct-image-fader', get_bloginfo('template_url').'/js/jquery.image_fader.js', array('jquery'));
	
	if ($use_feature) wp_enqueue_script('ct-feature-slider', get_bloginfo('template_url').'/js/jquery.feature_slider.js',  array('jquery', 'ct-class'));
	
	if ($use_faq) wp_enqueue_script('ct-faq', get_bloginfo('template_url').'/js/jquery.faq.js', array('jquery'), '4.0');
	
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-datepicker-es', plugins_url('casa-tibet').'/js/jquery.ui.datepicker-es.js', array('jquery-ui-datepicker'));
	/* wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css'); */
	
}

add_action('wp_head', 'ct_mex_wp_head');
function ct_mex_wp_head() {
	global $domainTheme;
?>
<!-- <link href="http://fnt.webink.com/wfs/webink.css?project=7A36BC1B-1FA8-40BE-9194-DE0B97D81875&fonts=3ADF0713-3388-8371-8FC2-3CBA9D020557:f=AGaramondPro-Regular,4E5B817B-633D-E2C2-8120-FBBC0786B815:f=AGaramondPro-Semibold,DB6F18C0-2E60-CC71-F543-390ED488B3D2:f=AGaramondPro-Italic" rel="stylesheet" type="text/css"/> -->
<script type="text/javascript">
	ct.siteurl = '<?php echo $domainTheme->home; ?>';
	ct.theme = '<?php echo $domainTheme->stylesheet; ?>';
</script>
<?
}

/*
add_filter('get_avatar', 'get_no_gravatar', 1, 2);
function get_no_gravatar( $id_or_email, $size = '96', $default = '', $alt = false ) {
    $avatar = "<img alt='image_alt' src='#' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
	return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
};
*/

function ct_nav_menu($id) {

	$items = wp_get_nav_menu_items( 'Menu '.$id );
	if (!$items) $items = wp_get_nav_menu_items( $id );
	
	$ob = array();
	foreach((array) $items as $key=>$item) {
		
		$id = $item->ID;
		$parent = $item->menu_item_parent;
		$i = array('label'=>$item->title, 'url'=>$item->url, 'target'=>$item->target);
		if ($parent > 0) {
			$ob[$parent]['children'][] = $i;		
		} else {
			$ob[$id] = $i;
		}
		
	}
	
	$ob = array_values($ob);
	
	$GLOBALS['ob'] = $ob;
	get_template_part('menu', 'main');
	
}

function ct_get_nav_item($url) {
	global $ob;
	if ($ob) {
		foreach((array) $ob as $item) {
			if ($item['url'] == $url) return $item;
		}
	}
}

function ct_get_thumbnail($post, $size='thumb') {
	global $_wp_additional_image_sizes;
	$url = (gettype($post) == "object") ? wp_get_attachment_url( get_post_thumbnail_id($post->ID) )
										: $post;
										
	$url = ct_site_replace_domain($url);
	
	if ($url && (is_array($size) || $_wp_additional_image_sizes[$size])) {
	
		$path = pathinfo($url);
		
		if (is_array($size)) {
			$url = sprintf('%s/%s-%sx%s%s.%s', 
					   $path['dirname'], $path['filename'], $size[0], $size[1], 
					   ($size[2])?'c':'', $path['extension']);
		} else {
			$s = $_wp_additional_image_sizes[$size];
			$url = sprintf('%s/%s-%sx%s%s.%s', 
					   $path['dirname'], $path['filename'], $s['width'], $s['height'], 
					   ($s['crop']) ? 'c' : '', $path['extension']);
		}
		
	}
	return ($url) ? '<img src="'.$url.'" />' : '';
}

function ct_listing($type, $list) {
	$GLOBALS['ct_list'] = $list;
	get_template_part('list', $type);
}

/** TAGS **************************************************/

function ct_tag($label, $url=null, $css=array('tag')) {
	$href = ($url) ? 'data-href="'.$url.'"' : '';
	return '<div class="'.implode(' ', $css).'" '.$href.'>'.$label.'</div>';
}


/** SIDEBARS ************************************************/

function ct_pagination($q) {
	global $sbar_args;
	if ($sbar_args['href']) {
		echo '<div class="icon view"></div>';
	} else {
		if ($q->max_num_pages > 1) {
			$pagination = array();
			$paged = ($q->query_vars['paged'] > 1) ? $q->query_vars['paged'] : 1;
			if ($q->query_vars['paged'] > 1) 
				$pagination['prev'] = add_query_arg('pg', $paged-1);
			if ($q->query_vars['paged'] < $q->max_num_pages) 
				$pagination['next'] = add_query_arg('pg', $paged+1);
			$GLOBALS['pagination'] = $pagination;
			get_template_part('inc', 'pagination');
		}
	}
}

function ct_sidebar($type, $args=array(), $output=false) {
	$GLOBALS['sbar_args'] = $args;
	if ($output) {
		return ct_load_template_part('sidebar', $type);
	} else {
		get_template_part('sidebar', $type);
	}	
}

function ct_get_permalink($id=null, $type=null) {
	$perma = "";
	if ($id) {
		if (!$type) $type = gettype($id);
		switch($type) {
			case 'integer':
				$perma = get_permalink($id);
				break;
			case 'string':
				$page = get_page_by_path($id);
				if ($page) $perma = get_permalink($page->ID);
				break;
			case 'category':
				$perma = str_replace('category/', '', get_category_link($id));
				break;
			default:
				$term = get_term_by('id', $id, $type);
				$perma = '/'.$type.'/'.$term->slug;
				break;
		}
	} else {
		if (is_singular()) {
			global $post;
			if ($post) {
				$perma = ct_get_permalink($post->ID);
			}
		} else if (is_category()) {
			global $cat;
			if ($cat) {
				$perma = ct_get_permalink($cat, 'category');
			}
		}
	}
	
	$center = get_post_meta($id, 'ct_center', true);
	if ($center > 0 &&
	    in_array($center, get_option('ct_active_centers'))) {
		return ct_replace_domain($perma, get_post_meta($center, 'ct_center_domain', true));	
	} else {
		$site = ct_get_site_for_post($id);
		if ($site && $site != 'ct-mex') {
			$d = ct_get_domain_by_theme($site);
			return ct_replace_domain($perma, $d['url']);	
		}	
		return ct_site_replace_domain($perma);
		
	}	
	
}

function ct_get_excerpt($item, $len=30) {

	$GLOBALS['excerpt_length'] = $len;

	if (gettype($item) == 'object') {
		if ($item->post_excerpt) return $item->post_excerpt;
    	$text = strip_shortcodes( $item->post_content );
	} else {
		$text = $item;
	}
   

    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $text = strip_tags($text);
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
    if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
    } else {
            $text = implode(' ', $words);
    }

    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);

}
function ct_excerpt_length($length) {
	global  $excerpt_length;
    return ($excerpt_length) ? $excerpt_length : $length;
}
add_filter('excerpt_length', 'ct_excerpt_length');

function ct_get_external_link($url) {
	return (strstr($url, 'http://')) ? $url : 'http://'.$url;
}

function ct_query_args($args=array()) {
	$args = apply_filters('ct_query_args', $args);
	return $args;
}

/** FORUMS ************************************************************/

add_filter('the_title', 'ct_get_forum_title', 10, 2);
function ct_get_forum_title($title, $id=null) {
	if ($id) {
		$post = get_post( $id );
		return ($post->post_type == 'forum') ? $post->post_title : $title;
	} else {
		return $title;
	}
}

function print_filters_for( $hook = '' ) {
    global $wp_filter;
    print_r( ($hook) ? $wp_filter[$hook] : $wp_filter );

}

/** BREADCRUMBS *****************************************************/

function ct_breadcrumbs($parts, $end) {


	$trail = new bcn_breadcrumb_trail();
	$trail->opt = wp_parse_args(get_option('bcn_options'), $trail->opt);

	foreach($parts as $part) {
		switch($part) {
			case 'home':
				$site_name = get_option('blogname');
				$trail->add(new bcn_breadcrumb($site_name, $trail->opt['Hhome_template'], array('home'), get_home_url()));
				break;
			default:
				
				if (is_object($part)) {
					$p = $part;
				} else if (is_array($part)) {
					$q = new WP_Query($part);
					$p = $q->post;
				}
				if ($p) {
				
					
					if ($p->post_type != 'module') {
						//$trail->post_hierarchy($p->ID, $p->post_type, $p->post_parent);
					}
					
				
					$b = new bcn_breadcrumb($p->post_title, 
								$trail->opt['Hpost_' . $p->post_type . '_template'], 
								array('post-' . $p->post_type, 'current-item'), NULL, $p->ID);
					$b->set_template($trail->opt['Hpost_' . $p->post_type . '_template']);
					
					$perma = ct_get_permalink($p->ID);
					if ($_GET['ref']) {
						$perma = add_query_arg('ref', $_GET['ref'], $perma);
					}
					$b->set_url($perma);
					$trail->add($b);												
					
				}

				break;
		}				
	}
	
	if ($end->userdata) {
						
		$b = new bcn_breadcrumb($end->fullname, 
					'%htitle%', 
					array('post-user', 'current-item'), NULL, $end->id);
		$trail->add($b);
		
	} else {
		$b = new bcn_breadcrumb($end->post_title, 
					$trail->opt['Hpost_' . $end->post_type . '_template_no_anchor'], 
					array('post-' . $end->post_type, 'current-item'), NULL, $end->ID);
		$trail->add($b);
	}
	
	echo '<div class="page_nav"><div class="breadcrumbs" itemprop="breadcrumbs">';
	$trail->display(false, true, true);
	echo '</div></div>';
}

function ct_avatar_no_grav($no_grav) {
	return true;
}
add_filter( 'bp_core_fetch_avatar_no_grav', 'ct_avatar_no_grav', 10, 1);


function ct_include($template) {
	$GLOBALS['current_template'] = $template;
	return $template;
}
add_filter('template_include', 'ct_include', 10, 1);

?>