<?php

/** CUSTOM POST ***************************************************************/

add_action('init', 'ct_teacher_init');
function ct_teacher_init() {
	

register_post_type('teacher', array(	'label' => 'Maestros', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'teacher', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'maestros','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor', 'thumbnail', 'page-attributes'),'labels' => array (
  'name' => 'Maestros',
  'singular_name' => 'Maestro',
  'menu_name' => 'Maestros',
  'add_new' => 'Agregar Maestro',
  'add_new_item' => 'Agregar Nuevo Maestro',
  'edit' => 'Editar',
  'edit_item' => 'Editar Maestro',
  'new_item' => 'Nuevo Maestro',
  'view' => 'Ver Maestro',
  'view_item' => 'Ver Maestro',
  'search_items' => 'Buscar Maestro',
  'not_found' => 'No Hay Maestros',
  'not_found_in_trash' => 'No hay Maestros en la Papelera',
  'parent' => 'Maestro Superior',
),) );

register_taxonomy('teacher-type', array('teacher'),array( 'hierarchical' => true, 'label' => 'Tipos de Maestros','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Tipo de Maestro') );

register_post_type('quote', array(	'label' => 'Citas', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'teacher', 'map_meta_cap' => true, 'hierarchical' => false,'rewrite' => false,'query_var' => false,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor'),'labels' => array (
  'name' => 'Citas',
  'singular_name' => 'Cita',
  'menu_name' => 'Citas',
  'add_new' => 'Agregar Cita',
  'add_new_item' => 'Agregar Nuevo Cita',
  'edit' => 'Editar',
  'edit_item' => 'Editar Cita',
  'new_item' => 'Nuevo Cita',
  'view' => 'Ver Cita',
  'view_item' => 'Ver Cita',
  'search_items' => 'Buscar Cita',
  'not_found' => 'No Hay Citas',
  'not_found_in_trash' => 'No hay Citas en la Papelera',
  'parent' => 'Cita Superior',
),) );
 
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_teacher_add_meta_boxes' );
function ct_teacher_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'teacher':
		case 'page':
			add_meta_box( 
		        'ct_teacher_sidebar', 'Información Lateral', 'ct_teacher_meta_sidebar',
		        $postType, 'normal'
		    );
			break;
		case 'quote':
			add_meta_box( 
		        'ct_quote_source', 'Fuente', 'ct_quote_meta_source',
		        'quote', 'side', 'core'
		    );
			break;
	}
}

function ct_teacher_meta_attach_teacher($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_teacher_noncename' );
	$types = array_values(get_terms('teacher-type'));
	$teacher = get_post_meta($post->ID, 'ct_teacher', true);
?>
	<select name="ct_teacher" style="width:100%;">
<?php foreach((array) $types as $type) : ?>
		<option value="0">-- <?php echo $type->name; ?> --</option>
	<?php 
		  $teachers = ct_get_teachers($type->name); 
		  foreach((array) $teachers as $t) : ?>
			<option value="<?php echo $t->ID; ?>"><?php echo $t->post_title; ?></option>
	<?php endforeach; ?>
	
<?php endforeach; ?>
	</select>
	<script type="text/javascript">jQuery('select[name=ct_teacher]').val('<?php echo $teacher; ?>');</script>
<?php
}

function ct_teacher_meta_sidebar($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_teacher_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_sidebar',true)); 	
	wp_editor($info, 'sidebarcontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'sidebarcontent', 'teeny'=>true));
}

function ct_quote_meta_source($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_teacher_noncename' );
	
	$source = get_post_meta($post->ID, 'ct_quote_source', true);
	if ($source == 'other') $other = get_post_meta($post->ID, 'ct_quote_source_other', true);
	$context = get_post_meta($post->ID, 'ct_quote_source_context', true);
	
	$teachers = ct_get_teachers();
?>
	<p><b>Autor</b><br />
	<select name="ct_quote_source">
		<option>-- Elije un Maestro --</option>
<?php foreach((array) $teachers as $t) : ?>
		<option value="<?php echo $t->ID; ?>"><?php echo $t->post_title; ?></option>
<?php endforeach; ?>
		<option value="other">Otro</option>
	</select>
	<input type="text" name="ct_quote_source_other" value="<?php echo $other?>" 
			<?php if (!$other) echo 'disabled="true"'; ?>
			style="display:<?php echo ($other) ? '' : 'none'; ?>;width:100%;padding-top:5px;" /></p>
	<p><b>Contexto (ej: texto, lugar, etc)</b><br />
	<input type="text" name="ct_quote_source_context" value="<?php echo $context?>"
			style="width:100%;" /></p>
	<script type="text/javascript">
		var sel = jQuery('select[name=ct_quote_source]');
		sel.val('<?php echo $source; ?>');
		sel.change(function() {
			var input = jQuery('input[name=ct_quote_source_other]');
			if (jQuery(this).val() == 'other') {
				input.show();
				input.attr('disabled', false);
			} else {
				input.hide();
				input.attr('disabled', true);
			}
		});
	</script>
<?php
}

add_action( 'save_post', 'ct_teacher_save_postdata' );
function ct_teacher_save_postdata( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_teacher_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_teacher_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	if (isset($_POST['sidebarcontent'])) {  
	  	update_post_meta($post_id, 'ct_sidebar', $_POST['sidebarcontent']);
	}	
	
	if (isset($_POST['ct_quote_source'])) {  
	  	update_post_meta($post_id, 'ct_quote_source', $_POST['ct_quote_source']);
	  	update_post_meta($post_id, 'ct_quote_source_other', $_POST['ct_quote_source_other']);
	}
	if (isset($_POST['ct_quote_source_context'])) {  
	  	update_post_meta($post_id, 'ct_quote_source_context', $_POST['ct_quote_source_context']);
	}	
	
	if (isset($_POST['ct_teacher'])) {  
	  	update_post_meta($post_id, 'ct_teacher', $_POST['ct_teacher']);
	}
	
}

/* COLUMNS *********************************************************************/

add_filter("manage_edit-quote_columns", "ct_quote_edit_columns"); 
function ct_quote_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Cita",
    "teacher" => "Maestro"
  );
 
  return $columns;
}

add_action("manage_quote_posts_custom_column",  "ct_quote_custom_columns");
function ct_quote_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "teacher":
    	$source = get_post_meta($post->ID, 'ct_quote_source', true);
    	if ($source == 'other') {
    		echo get_post_meta($post->ID, 'ct_quote_source_other', true);
    	} else if ($source) {
    		$teacher = get_post($source);
    		echo '<a href="'.admin_url('post.php?post='.$source.'&action=edit').'">'.$teacher->post_title.'</a>';
    	}
    	break;
  }
}

/* API FUNCTIONS **************************************************************/

function ct_get_teacher($id) {
	if (is_numeric($id)) {
		return get_post($id);
	} else {
		$posts = get_posts(array('post_type'=>'teacher', 'name'=>$id));
		return $posts[0];
	}
}

function ct_get_teachers($term='', $args=array()) {
	$args['post_type'] = 'teacher';
	$args['order'] = 'ASC';
	$args['orderby'] = 'menu_order title';
	$args['tax_query'] = array();
	
	if ($term) {
		
		if (is_array($term)) {
			$args['tax_query']['relation'] = 'AND';
			foreach($term as $t) {
				$args['tax_query'][] = array(
								'taxonomy' => 'teacher-type',
								'field' => 'slug',
								'terms' => $t
								);
			}
		} else {
			$args['tax_query'][] = array(
								'taxonomy' => 'teacher-type',
								'field' => 'slug',
								'terms' => $term
								);
		}
	
		
	}
	
	$q = new WP_Query($args);
	return $q->posts;
}

/** SHORTCODES ********************************************************/

function ct_quote_shortcode( $atts, $content='' ) {
	ct_set_template_args($atts, $content);
	return ct_load_template_part('shortcode', 'quote');
}
add_shortcode( 'ct-quote', 'ct_quote_shortcode' );

?>