<?php

add_action('init', 'ct_site_init');
function ct_site_init() {

	global $cur_domain, $domainTheme, $wp_rewrite;
	$parts = explode('.', $domainTheme->currentdomain);
	$cur_domain = $parts[0];
	
	register_nav_menus(
	    array(
	      'ct-edu-menu' => __( 'Educación : Menu Principal' ),
	      'ct-editorial-menu' => __( 'Editorial : Menu Principal' ),
	      'ct-derechos-menu' => __( 'Altruismo : Menu Principal' ),
	      'ct-centro-menu' => __( 'Centros : Menu Principal' ),
	      'ct-mex-menu' => __( 'Casa Tibet México : Menu Principal' )
	    )
	);
	
register_taxonomy('site',array('post', 'banner'),array( 'hierarchical' => true, 'label' => 'Sitios','show_ui' => true,'query_var' => true,'rewrite' => false, 'singular_label' => 'Sitio') );
 
}

function ct_site_tag($post) {
	
	$css = array('tag', 'right');

	switch($post->post_type) {
		default:
			$center = get_post_meta($post->ID, 'ct_center', true);
			$label = ct_site_name($center);
			if ($center > 0) {
				$url = 'http://'.get_post_meta($center, 'ct_center_domain', true);
				$css[] = 'center';	
			} else {
								
				$site = ct_get_site_for_post($post->ID);
				if ($site && $site != 'ct-mex') {
					$d = ct_get_domain_by_theme($site);
					$url = 'http://'.$d['url'];
					$label = $d['blogname'];
					$css[] = $d['theme'];
				} else {
					$url = $domainTheme->home;
				}				
				
			}
			break;
	}
	return ct_tag($label, $url, $css);
}


function ct_get_site_for_post($id) {
	$terms = array_values(wp_get_post_terms($id, 'site'));
	return $terms[0]->slug;
}

function ct_get_domain_by_theme($theme) {
	global $domainTheme;
	foreach($domainTheme->options as $op) {
		if ($op['theme'] == $theme) {
			return $op;
		}
	}
	
}

function ct_site_name($center) {
	if ($center) {
		$c = get_post($center);
		//return sprintf(__('Casa Tibet : %s'), $c->post_title);
		return $c->post_title;
	} 
	return __('Casa Tibet México');
}


function ct_site_get_url_parts($url) {
	preg_match("#(https?://)(.*)?#ie", $url, $res);
	return explode("/", $res[2]);
}

/** COLUMNS ********************************************************/

add_filter("manage_edit-banner_columns", "ct_site_edit_columns", 20); 
function ct_site_edit_columns($columns){
  if (current_user_can('administrator')) $columns['ct-site'] = 'Sitio';
  return $columns;
}

add_action("manage_banner_posts_custom_column",  "ct_site_custom_columns", 20);
function ct_site_custom_columns($column){
  global $post;
 
  switch ($column) {
  	case "ct-site":
    	$terms = array_values(wp_get_post_terms($post->ID, 'site'));
    	if ($terms) {
    		$link = add_query_arg('site', $terms[0]->slug);
    		if ($terms[0]->slug == 'ct-centro') {
    			$center_id = get_post_meta($post->ID, 'ct_center', true);
    			$c = get_post($center_id);
    			$label = $terms[0]->name.' ('.$c->post_title.')';
    		} else {
    			$label = $terms[0]->name;
    		}
    		
			echo '<a href="'.$link.'">'.$label.'</a>';
    	}
      	break;
  }
}

/** FILTERS ********************************************************/

function ct_site_query_args($args=array()) {
	
	global $domainTheme;	
	$site = $domainTheme->stylesheet;

	if (!$args['tax_query']) $args['tax_query'] = array();
	$args['tax_query'][] = array(
							'taxonomy' => 'site',
							'field' => 'slug',
							'terms' => $site
							);
							
    return $args;
}
add_filter('ct_query_args', 'ct_site_query_args', 10, 1);


/** OTHER ************************************************************/

function ct_site_get_current_admin() {
	global $ct_site;
	if (!current_user_can('administrator') && current_user_can('editor')) {
		if (current_user_can('ct_editor_edu')) {
			return 'ct-edu';
		} else if (current_user_can('ct_coordinator') || 
				   current_user_can('ct_facilitator')) {
			return 'ct-centro';
		}
	} else {
		return 'ct-mex';
	}
}

function ct_site_get_domains($use_themes=false) {
	global $domainTheme;	
	
	$domains = array();
	
	/* $siteurl = get_option('home'); */
	
	$alloptions = wp_load_alloptions();
	if ($use_themes) {
		$domains['ct-mex'] = $alloptions['home'];
		foreach($domainTheme->options as $op) {
			$theme = $op['theme'];
			$domains[$theme] = 'http://'.$op['url'];
		}
	} else {
		$domains[] = $alloptions['home'];
		foreach($domainTheme->options as $op) {
			$domains[] = 'http://'.$op['url'];
		}
	}
	return $domains;
}

function ct_is_theme($theme) {
	global $domainTheme;
	
	if (is_array($theme)) {
		return in_array($domainTheme->stylesheet, $theme);
	} else {
		return ($domainTheme->stylesheet == $theme);
	}
	
}

function ct_replace_domain($str, $domain) {

	if (!strstr('http://', $domain)) $domain = 'http://'.$domain;
	$domains = ct_site_get_domains();
	$str = str_replace($domains, $domain, $str);
		
	return $str;
}

if (!is_admin()) {
	add_action('send_headers', 'ct_site_buffer_start');
	add_action('shutdown', 'ct_site_buffer_end', 1);	
	
	function ct_site_replace_domain($str, $js=false) {
	
		$siteurl = get_option('home');
		$domains = ct_site_get_domains();
		
		foreach($domains as $key=>$val) {
			$domains[$key] = ($js) ? str_replace('"', '', json_encode($val)) : $val;
		}
		
		$str = str_replace($domains, 
								($js) ? str_replace('"', '', json_encode($siteurl)) : $siteurl, 
								$str);
		
		return $str;
	}

	function ct_site_buffer_callback($buffer) {
		return ct_site_replace_domain($buffer, true);
	}
	
	function ct_site_buffer_start() {
		ob_start('ct_site_buffer_callback');
	}
	
	function ct_site_buffer_end() {
		
		if (ob_get_status(true)) {
			ob_end_clean();
		}
			 
	}

}

?>