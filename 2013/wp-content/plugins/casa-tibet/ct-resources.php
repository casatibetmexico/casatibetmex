<?php

/** FUNCTIONS ****************************************************************/

function ct_res_get_resources_for_type($type=null) {
	$args = array('nopaging'=>true,
				  'orderby'=>'menu_order title',
				  'order'=>'ASC',
				  'post_type'=>'resource',
				  'tax_query'=>array());
	if ($type) {
		$args['tax_query'][] = array('taxonomy'=>'resource-type', 
										     'field'=>'slug', 
											 'terms'=>$type);
	}
	$q = new WP_Query($args);
	return $q->posts;
}

function ct_res_get_resource_types($parent=0) {
	$args = array('parent'=>$parent);
	if (!$parent) $args['hide_empty'] = false;
	return array_values(get_terms('resource-type', $args));
}

function ct_res_create_listing($selected, $types=null, $only_selected=false) {

	$ob = array('types'=>array());

	if (!$types) $types = ct_res_get_resource_types();
		
	for($i=0; $i<count($types); $i++) {
		$t = $types[$i];
		$type = array('label'=>$t->name);
		
		$sub_types = ct_res_get_resource_types($t->term_id);
		if (count($sub_types)) {
			$type['children'] = ct_res_create_listing($selected, $sub_types, $only_selected);
		} else {
			$resources = ct_res_get_resources_for_type($t->slug);
			$items = array();
			for($j=0; $j<count($resources); $j++)  {
				$p = $resources[$j];
				if ($only_selected) {
					if (in_array($p->ID, $selected)) {
						$items[] = array('id'=>$p->ID, 'label'=>$p->post_title);
					}
				} else {
					$items[] = array('id'=>$p->ID, 'label'=>$p->post_title, 'status'=>in_array($p->ID, $selected));
				}
				
			}
			$type['items'] = $items;
		}
		
			
		if ($type['children'] || $type['items']) $ob['types'][] =  $type;										 
	}
	
	return $ob;
	
}

function ct_res_get_latest($lim=5) {
	$args = array('orderby'=>'modified',
				  'order'=>'DESC',
				  'posts_per_page'=>$lim,
				  'post_type'=>'resource');
	$q = new WP_Query($args);
	return $q->posts;
}

/** CUSTOM POST ***************************************************************/

add_action('init', 'ct_res_init');
function ct_res_init() {
	

register_post_type('resource', array(	'label' => 'Recursos', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'resource', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'recursos','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor','page-attributes'), 'labels' => array (
  'name' => 'Recursos',
  'singular_name' => 'Recursos',
  'menu_name' => 'Recursos',
  'add_new' => 'Agregar Recurso',
  'add_new_item' => 'Agregar Nuevo Recurso',
  'edit' => 'Editar',
  'edit_item' => 'Editar Recurso',
  'new_item' => 'Nuevo Recurso',
  'view' => 'Ver Recurso',
  'view_item' => 'Ver Recurso',
  'search_items' => 'Buscar Recurso',
  'not_found' => 'No Hay Recursos',
  'not_found_in_trash' => 'No hay Recursos en la Papelera',
  'parent' => 'Recurso Superior',
),) );

register_taxonomy('resource-type',array('resource'),array( 'hierarchical' => true, 'label' => 'Tipos de Recursos','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Tipo de Recurso') );
 
}

/** FILTERS *****************************************************************/

add_filter('query_vars', 'ct_res_queryvars' );
function ct_res_queryvars( $qvars ) {
	$qvars[] = 'mod';
	return $qvars;
}


/* COLUMNS *********************************************************************/

add_filter("manage_edit-resource_columns", "ct_res_edit_columns"); 
function ct_res_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Recurso",
    "res-type" => "Tipo"
  );
 
  return $columns;
}

add_action("manage_resource_posts_custom_column",  "ct_res_custom_columns");
function ct_res_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "res-type":
    
    	$terms = array_values(wp_get_post_terms($post->ID, 'resource-type'));
    	if ($terms) {
			if ($terms[0]->parent) {
				$p = get_term( $terms[0]->parent, 'resource-type');
				echo '<a href="'.add_query_arg('resource-type', $p->slug).'">'.$p->name.'</a> > ';
			}
			echo '<a href="'.add_query_arg('resource-type', $terms[0]->slug).'"> '.$terms[0]->name.'</a>';
    	
    	}
      	
      	break;
  }
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_res_add_meta_boxes' );
function ct_res_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'resource':
			add_meta_box( 
		        'ct_res_link', 'Liga External', 'ct_res_meta_link',
		        'resource', 'side'
		    );
		    add_meta_box( 
		        'ct_res_attachments', 'Archivos de Media Adjuntos', 'ct_res_meta_attachment',
		        'resource', 'normal'
		    );
		    add_meta_box( 
		        'ct_res_biblio', 'Bibliografía', 'ct_res_meta_biblio',
		        'resource', 'normal', 'core'
		    );
			break;
	}
}

function ct_res_meta_link($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
?>
	<input type="text" name="ct-res-link" value="<?php echo get_post_meta($post->ID, 'ct-res-link', true); ?>" 
		   style="width:100%;" />
<?php
}

function ct_res_meta_biblio($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'res_bibliography',true)); 	
	wp_editor($info, 'bibliocontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'bibliocontent', 'teeny'=>true));
}

function ct_res_meta_attachment($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	
	

?>
	<div class="ct_res_attachments">
		<div class="header_bar">
			<a id="attachments_add_media" class="button">Añadir Media</a>
		</div>
		<ul class="attachment_listing"></ul>
	</div>
	<script type="text/javascript">
		<?php
			$attachments =& get_children('post_parent='.$post->ID.'&post_type=attachment');
			$ob = array('post'=>$post->ID, 'items'=>array());
			foreach($attachments as $a) {
				$item = array('id'=>$a->ID, 'label'=>$a->post_title, 'mime'=>$a->post_mime_type);
				$ob['items'][] = $item;
			}
			$args = json_encode($ob);
		?>
		CTResourceAttachments.init(<?php echo $args; ?>);
	</script>
<?php
}

add_action( 'save_post', 'ct_res_save_postdata' );
function ct_res_save_postdata( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	if (isset($_POST['ct-res-link'])) {  
	  	update_post_meta($post_id, 'ct-res-link', $_POST['ct-res-link']);
	}
	
	if (isset($_POST['bibliocontent'])) {  
	  	update_post_meta($post_id, 'res_bibliography', $_POST['bibliocontent']);
	}	
	
}

/* WEB SERVICES ***********************************************/

add_action('init', 'ct_res_register_services');
function ct_res_register_services() {

	if (function_exists('rm_api_register')) {
		rm_api_register('ct.res.setAttachments', 'ct_res_set_attachments');
		function ct_res_set_attachments() {
		
			$res = array('status'=>true);
			
			$p = (int) $_REQUEST['p'];
			
			// Remove all existing attachments
			$attachments =& get_children('post_parent='.$p.'&post_type=attachment');
			foreach($attachments as $a) {
				$args = array('ID'=>$a->ID, 'post_parent'=>0);
				wp_update_post($args);
			}
			$res['removed'] = count($attachments);
			
			if ($_REQUEST['attachments']) {
				$new = $_REQUEST['attachments'];
				foreach($new as $a) {
					$args = array('ID'=>(int) $a, 'post_parent'=>$p);
					wp_update_post($args);
				}
				$res['added'] = count($new);
			}
			
			rm_api_render($res);
			
		}
		
		rm_api_register('ct.res.removeAttachment', 'ct_res_remove_attachment');
		function ct_res_remove_attachment() {
		
			$res = array('status'=>true);
			
			$p = (int) $_REQUEST['p'];
			$args = array('ID'=>$p, 'post_parent'=>0);
			wp_update_post($args);
			
			rm_api_render($res);
			
		}
		
	}
		
}

/* FILTERS **********************************************************/

function ct_res_archive_filter($args) {
									
	$config = array();
	
	$config['title'] = ($args['title']) ? $args['title'] : __('Recursos');
	
	$config['args'] = array('post_type'=>'resource',
							'paged'=>$_GET['pg'],
							'posts_per_page'=>($args['limit']) ? $args['limit'] : 10,
							 'order'=>'DESC',
							 'orderby'=>'date title',
							 'tax_query'=>array(),
							 'meta_query'=>array());
							 
	if ($args['resource-type']) {
		$config['args']['tax_query'][] = array(
							'taxonomy' => 'resource-type',
							'field' => 'slug',
							'terms' => $args['resource-type']
							);
		$term = get_term_by('slug', $args['resource-type'], 'resource-type');
		$config['title'] = $term->name;
	}
	
	if ($_GET['s']) $config['args']['s'] = $_GET['s'];
							 
	
	$config['no_results'] = __('Actualmente no se encuentra ningun recurso en este categoria.');

	return $config;
}
add_filter('ct_archive-resource', 'ct_res_archive_filter', 10, 1);



?>