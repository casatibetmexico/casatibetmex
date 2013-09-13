<?php

/** CUSTOM POST : CAUSES ***************************************************************/

add_action('init', 'ct_cause_init');
function ct_cause_init() {
	

register_post_type('cause', array(	'label' => 'Causas', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'cause', 'map_meta_cap' => true, 'hierarchical' => false,'rewrite' => 'causas','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor','thumbnail'),'labels' => array (
  'name' => 'Causas',
  'singular_name' => 'Causa',
  'menu_name' => 'Causas',
  'add_new' => 'Agregar Causa',
  'add_new_item' => 'Agregar Nueva Causa',
  'edit' => 'Causa',
  'edit_item' => 'Editar Causa',
  'new_item' => 'Nuevo Causa',
  'view' => 'Ver Causa',
  'view_item' => 'Ver Causa',
  'search_items' => 'Buscar Causa',
  'not_found' => 'No Hay Causas',
  'not_found_in_trash' => 'No hay Causas en la Papelera',
  'parent' => 'Causa Superior',
),) );


register_post_type('org', array(	'label' => 'Organizaciones', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'page', 'map_meta_cap' => true, 'hierarchical' => false,'rewrite' => 'organizaciones','query_var' => true,'has_archive' =>false,'menu_position' => 5,'supports' => array('title','editor','thumbnail'),'labels' => array (
  'name' => 'Organizaciones',
  'singular_name' => 'Organización',
  'menu_name' => 'Organizaciones',
  'add_new' => 'Agregar Organización',
  'add_new_item' => 'Agregar Nueva Organización',
  'edit' => 'Organización',
  'edit_item' => 'Editar Organización',
  'new_item' => 'Nuevo Organización',
  'view' => 'Ver Organización',
  'view_item' => 'Ver Organización',
  'search_items' => 'Buscar Organización',
  'not_found' => 'No Hay Organizaciones',
  'not_found_in_trash' => 'No hay Organizaciones en la Papelera',
  'parent' => 'Organización Superior',
),) );
 
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_cause_add_meta_boxes' );
function ct_cause_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'org':
			add_meta_box( 
		        'ct_org_sidebar', 'Información Lateral', 'ct_teacher_meta_sidebar',
		        $postType, 'normal'
		    );
		    add_meta_box( 
		        'ct_org_intro', 'Introducción', 'ct_mod_meta_intro',
		        $postType, 'normal', 'core'
		    );
			break;
	}
}

/** FILTERS ***************************************************************/

function ct_cause_org_archive_filter($args) {
									
	$config = array();
	$config['args'] = array('post_type'=>'org',
							'nopaging'=>true,
							 'order'=>'ASC',
							 'orderby'=>'title',
							 'tax_query'=>array(),
							 'meta_query'=>array());
	
	if ($args['limit']) {
		$config['args']['posts_per_page'] = $args['limit'];
		$config['args']['nopaging'] = false;
	}
							 
	$config['title'] = ($args['title']) ? $args['title'] : __('Organizaciones');
	$config['no_results'] = __('Actualmente no se encuentra ningun organización activo.');
	
	/*
if ($args['slug']) {
		$config['args']['tax_query'][] = array(
							'taxonomy' => 'publication-type',
							'field' => 'slug',
							'terms' => $args['slug']
							);
	}
*/

	return $config;
}
add_filter('ct_archive-org', 'ct_cause_org_archive_filter', 10, 1);

?>