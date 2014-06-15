<?php 

/** PUBLIC FUNCTIONS ***********************************************/

function ct_get_module_index($args=array()) {
	$args = array_merge(array('post_type'=>'module',
							  'post_parent'=>0,
							  'order'=>'ASC',
							  'orderby'=>'menu_order',
							  'nopaging'=>true), $args);
	$q = new WP_Query($args);
	
	$res = array();
	for($i=0; $i<count($q->posts); $i++) {
		$temp = array();
		$temp['mod'] = $q->posts[$i];
		$temp['children'] = ct_get_module_index(array('post_parent'=>$temp['mod']->ID));
		$res[] = $temp;
	}
	
	return $res;
}

function ct_mod_get_resources_for_post($id) {
	$selected = get_post_meta($id, 'ct-mod-resources', true);
	if (!$selected) return array();
	$args = array('orderby'=>'title',
						  'order'=>'ASC',
						  'post_type'=>'resource',
					  	  'post__in'=>$selected,
						   'nopaging'=>true);
	$q = new WP_Query($args);
	return $q->posts;
}

function ct_mod_get_resources_as_listing($post) {
	$selected = get_post_meta($post->ID, 'ct-mod-resources', true);
	if (!$selected) return array();
	$listing = ct_res_create_listing($selected, null, true);
	return $listing;
}

/** CUSTOM POST ****************************************************/

add_action( 'widgets_init', 'ct_mod_widgets_init' );
function ct_mod_widgets_init() {

	register_sidebar( array(
		'name' => 'Modules Header',
		'id' => 'ct_mod_header',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="rounded">',
		'after_title' => '</h2>',
	) );
}


add_action('init', 'ct_mod_init');
function ct_mod_init() {

register_post_type('module', array(	'label' => 'Materiales', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'module', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'modules','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor', 'excerpt', 'page-attributes'),'labels' => array (
	  'name' => 'Materiales',
	  'singular_name' => 'Materiales',
	  'menu_name' => 'Materiales',
	  'add_new' => 'Agregar Material',
	  'add_new_item' => 'Agregar Nuevo Material',
	  'edit' => 'Editar',
	  'edit_item' => 'Editar Material',
	  'new_item' => 'Nuevo Material',
	  'view' => 'Ver Material',
	  'view_item' => 'Ver Material',
	  'search_items' => 'Buscar Material',
	  'not_found' => 'No Hay Materiales',
	  'not_found_in_trash' => 'No hay Materiales en la Papelera',
	  'parent' => 'Material Superior',
	),) );
 
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_mod_add_meta_boxes' );
function ct_mod_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'page':
			add_meta_box( 
		        'ct_mod_intro', 'Introducción', 'ct_mod_meta_intro',
		        'page', 'normal', 'core'
		    );
			break;
		case 'module':   
			add_meta_box( 
		        'ct_mod_intro', 'Introducción', 'ct_mod_meta_intro',
		        'module', 'normal', 'core'
		    );
		    add_meta_box( 
		        'ct_mod_options', 'Opciones de Módulo', 'ct_mod_meta_options',
		        'module', 'side'
		    );
		    
			/*
add_meta_box( 
		        'ct_mod_access', 'Control de Acceso', 'ct_mod_meta_access',
		        'module', 'side', 'core' 
		    );
*/
		    add_meta_box( 
		        'ct_mod_biblio', 'Bibliografía', 'ct_mod_meta_biblio',
		        'module', 'normal' 
		    );
		    add_meta_box( 
		        'ct_mod_resources', 'Recursos', 'ct_mod_meta_resources',
		        'module', 'normal' 
		    );
			break;
	}
}

function ct_mod_meta_access($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
?>
	<b>Contrase&ntilde;a</b><br />
	<input type="text" name="ct-mod-passcode" value="<?php echo get_post_meta($post->ID, 'ct-mod-passcode', true); ?>" />
<?php
}

function ct_mod_meta_options($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$options = get_post_meta($post->ID, 'ct-mod-options', true);
?>
	<div class="mod_options">
	<p><input type="checkbox" name="ct-mod-options[allow_download]" value="1" />&nbsp;Permitir descarga de recursos</p>
	<p><input type="checkbox" name="ct-mod-options[optional]" value="1" />&nbsp;¿Esta módulo es opcional?</p>
	</div>
	<script type="text/javascript">
		<?php if ($options['allow_download']) : ?> jQuery('.mod_options input:eq(0)').attr('checked', true);<?php endif; ?>
		<?php if ($options['optional']) : ?> jQuery('.mod_options input:eq(1)').attr('checked', true);<?php endif; ?>
	</script>
<?php
}

function ct_mod_meta_biblio($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'mod_bibliography',true)); 	
	wp_editor($info, 'bibliocontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'bibliocontent', 'teeny'=>true));
}

function ct_mod_meta_intro($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$intro = get_post_meta($post->ID, 'ct-mod-intro', true); 
?>	
	<textarea name="ct-mod-intro" style="width:100%;height:150px;resize:none;"><?php echo $intro; ?></textarea>
<?php
}

function ct_mod_meta_resources($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	
	$selected = get_post_meta($post->ID, 'ct-mod-resources', true);
	if (!$selected) $selected = array();
	$types = array_values(get_terms('resource-type'));
	
?>
	<div class="ct_mod_resource_selector">
		<div class="headers">
			<div class="col right">Lista de recursos relacionados a esta modulo:</div>
			<div class="col left">Selecciona recursos para agregar al modulo:</div>
		</div>
		<div class="col right">
			<ul class="res_listing"></ul>
		</div>
		<div class="col left">
			<ul class="res_listing"></ul>
		</div>
		<input type="hidden" name="ct-mod-resources" value="<?php echo implode(',', $selected);?>" />
	</div>
	<script type="text/javascript">
		<?php 
		
		$ob = ct_res_create_listing($selected);
		
		$ob['selected'] = array();
		
		if ($selected[0] && count($selected) > 0) {
		
			
			for($j=0; $j<count($selected); $j++) {
				$p = get_post($selected[$j]);
				$items[] = array('id'=>$p->ID, 'label'=>$p->post_title);
			}
			
			/*
$args = array('orderby'=>'title',
						  'order'=>'ASC',
						  'post_type'=>'resource',
					  	  'post__in'=>$selected);
			$q = new WP_Query($args);
			$items = array();
			for($j=0; $j<count($q->posts); $j++)  {
				$p = $q->posts[$j];
				$items[] = array('id'=>$p->ID, 'label'=>$p->post_title);
			}
*/
			$ob['selected'] = $items;
		}
		
		$args = json_encode($ob);

		?>
		
		CTResourceSelector.init(<?php echo $args;?>);
	</script>
<?php
}

add_action( 'save_post', 'ct_mod_save_postdata' );
function ct_mod_save_postdata( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	if (isset($_POST['bibliocontent'])) {  
	  	update_post_meta($post_id, 'mod_bibliography', $_POST['bibliocontent']);
	}
	
	if (isset($_POST['ct-mod-intro'])) {
		update_post_meta($post_id, 'ct-mod-intro', $_POST['ct-mod-intro']);
	}
	
	//if (isset($_POST['ct-mod-resources'])) {
		update_post_meta($post_id, 'ct-mod-resources', explode(',', $_POST['ct-mod-resources']));
	//}
	
	if (isset($_POST['ct-mod-passcode'])) {
		update_post_meta($post_id, 'ct-mod-passcode', $_POST['ct-mod-passcode']);
	}
	
	update_post_meta($post_id, 'ct-mod-options', $_POST['ct-mod-options']);
		
	
}

/* COLUMNS *********************************************************************/

add_filter("manage_edit-module_columns", "ct_mod_edit_columns"); 
function ct_mod_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Material"
  );
 
  return $columns;
}

add_action("manage_module_posts_custom_column",  "ct_mod_custom_columns");
function ct_mod_custom_columns($column){
  global $post;
 
  switch ($column) {
    
  }
}

/* WEB SERVICES ***********************************************/

add_action('init', 'ct_mod_register_services');
function ct_mod_register_services() {

	if (function_exists('rm_api_register')) {
		rm_api_register('ct.mod.requestAccess', 'ct_mod_request_access');
		function ct_mod_request_access() {
			$res = array('status'=>false);
			list($pass, $mod) = ct_mod_get_passcode($_REQUEST['m']);
			if ($pass === $_REQUEST['p']) {
				 $res['status'] = true;
				 $res['id'] = $mod;
			}
			rm_api_render($res);
			
		}
	}
		
}

/* ACCESS CONTROL *********************************************/

function ct_mod_get_passcode($id) {

	$passcode = get_post_meta($id, 'ct-mod-passcode', true);
	if ($passcode) return array($passcode, $id);
	
	$module = get_post($id);
	if ($module && $module->post_parent) {
		return ct_mod_get_passcode($module->post_parent);
	}
	return false;
}

function ct_mod_is_restricted($id) {
	list($passcode, $mod) = ct_mod_get_passcode($id);
	return ($passcode) ? true : false;
}

function ct_mod_can_view($id) {

	$access_codes = explode(',', $_COOKIE['ct_mod_access']);
	if ($access_codes && in_array((string)$id, $access_codes)) return true;
	
	$module = get_post($id);
	if ($module && $module->post_parent) {
		return ct_mod_can_view($module->post_parent);
	}
	
	return false;
}


/* SHORTCODES ******************************************************/

function ct_mod_get_modules($parent=0) {
	$res = array();
	$q = new WP_Query(array( 'order'=>'ASC',
							     'orderby'=>'menu_order title',
								 'nopaging'=>true,
								 'post_type'=>'module',
								 'post_parent'=>$parent));
	$posts = $q->posts;
	if ($posts) {
		foreach($posts as $p) {
			$ob = array('ob'=>$p);
			$children = ct_mod_get_modules($p->ID);
			if ($children) {
				$ob['children'] = $children;
			}
			$res[] = $ob;
		}
	}
	return $res;
}

function ct_mod_modules_shortcode( $atts, $content='' ) {
	
	global $post;
	
	$id = ($atts['id']) ? $atts['id'] : $post->ID;
	
	if ($id) {
		global $tPages;
		$tPages = ct_mod_get_modules(($atts['children']) ? $id : 0);
		ct_set_template_args($atts, $content);
		return ct_load_template_part('shortcode', 'materials');
	}
		
	
}
add_shortcode( 'ct-modules', 'ct_mod_modules_shortcode' );

/** CUSTOM BREADCRUMBS ********************************************/

function ct_mod_get_parents($module, &$parents, $exclude=false) {
	if ($module->post_parent && $module->post_parent != $exclude) {
		$p = get_post($module->post_parent);
		$parents[] = $p;
		if ($p->post_parent) ct_mod_get_parents($p, $parents, $exclude);
	}
}

function ct_custom_breadcrumbs($parts) {
	
}

?>