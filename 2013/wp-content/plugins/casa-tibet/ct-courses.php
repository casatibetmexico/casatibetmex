<?php

define('CT_COURSE_ROOT_FORUM', 'grupos-de-discusion');

/** CUSTOM POST ***************************************************************/

add_action('init', 'ct_course_init');
function ct_course_init() {
	

register_post_type('course', array(	'label' => 'Cursos', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'course', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'cursos','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor', 'page-attributes'),'labels' => array (
  'name' => 'Cursos',
  'singular_name' => 'Curso',
  'menu_name' => 'Cursos',
  'add_new' => 'Agregar Curso',
  'add_new_item' => 'Agregar Nuevo Curso',
  'edit' => 'Editar',
  'edit_item' => 'Editar Curso',
  'new_item' => 'Nuevo Curso',
  'view' => 'Ver Curso',
  'view_item' => 'Ver Curso',
  'search_items' => 'Buscar Curso',
  'not_found' => 'No Hay Cursos',
  'not_found_in_trash' => 'No hay Cursos en la Papelera',
  'parent' => 'Curso Superior',
),) );

register_taxonomy('course-type',array('course', 'study-group'),array( 'hierarchical' => true, 'label' => 'Tipos de Cursos','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Tipo de Curso') );

register_taxonomy('course-program',array('course'),array( 'hierarchical' => true, 'label' => 'Programas','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Programa') );

register_post_type('study-group', array(	'label' => 'Grupos', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'study-group', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'study-group','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title', 'excerpt'),'labels' => array (
  'name' => 'Grupos',
  'singular_name' => 'Grupo',
  'menu_name' => 'Grupos',
  'add_new' => 'Agregar Grupo',
  'add_new_item' => 'Agregar Nuevo Grupo',
  'edit' => 'Editar',
  'edit_item' => 'Editar Grupo',
  'new_item' => 'Nuevo Grupo',
  'view' => 'Ver Grupo',
  'view_item' => 'Ver Grupo',
  'search_items' => 'Buscar Grupo',
  'not_found' => 'No Hay Grupos',
  'not_found_in_trash' => 'No hay Grupos en la Papelera',
  'parent' => 'Grupo Superior',
),) );


 
}

/* COLUMNS *********************************************************************/

add_filter("manage_edit-course_columns", "ct_course_edit_columns"); 
function ct_course_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Curso",
    "program" => "Programa",
    "type" => "Tipo"
  );
 
  return $columns;
}

add_action("manage_course_posts_custom_column",  "ct_course_custom_columns");
function ct_course_custom_columns($column){
  global $post;
 
  switch ($column) {
  	case "program":
  		$terms = array_values(wp_get_post_terms($post->ID, 'course-program'));
    	if ($terms) {
    		$link = add_query_arg('course-program', $terms[0]->slug);
			echo '<a href="'.$link.'">'.$terms[0]->name.'</a>';
    	}
  		break;
  	case "type":
  		$terms = array_values(wp_get_post_terms($post->ID, 'course-type'));
    	if ($terms) {
    		$link = add_query_arg('course-type', $terms[0]->slug);
			echo '<a href="'.$link.'">'.$terms[0]->name.'</a>';
    	}
  		break;
  }
}

/** META BOXES ******************************************************************/

add_action( 'add_meta_boxes', 'ct_course_add_meta_boxes' );
function ct_course_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'course':  
		    
		    add_meta_box( 
		        'ct_course_community', 'Comunidad', 'ct_course_meta_community',
		        $postType, 'side' 
		    );
		    
		    add_meta_box( 
		        'ct_course_objectives', 'Objetivos', 'ct_course_meta_objectives',
		        $postType, 'normal' 
		    );
		    
		    /*
add_meta_box( 
		        'ct_course_structure', 'Estructura', 'ct_course_meta_structure',
		        $postType, 'normal' 
		    );
*/
		    
		    add_meta_box( 
		        'ct_course_sessions', 'Sesiones', 'ct_course_meta_sessions',
		        $postType, 'normal' 
		    );
		    
		    add_meta_box( 
		        'ct_course_meditations', 'Meditaciones', 'ct_course_meta_meditations',
		        $postType, 'normal' 
		    );
		    
		    add_meta_box( 
		        'ct_course_practice', 'Para la Vida Cotidiana', 'ct_course_meta_practice',
		        $postType, 'normal' 
		    );
		    
		    add_meta_box( 
		        'ct_course_resources', 'Recursos', 'ct_course_meta_resources',
		        $postType, 'normal' 
		    );
		    
		    add_meta_box( 
		        'ct_course_biblio', 'Bibliografía', 'ct_course_meta_biblio',
		        $postType, 'normal' 
		    );
		    
		    add_meta_box( 
		        'ct_course_materials', 'Materiales', 'ct_course_meta_materials',
		        $postType, 'normal', 'low' 
		    );
		    
			break;
		case 'study-group':
			if (current_user_can('administrator')) {
				add_meta_box( 
			        'ct_study_center', 'Organizado por:', 'ct_ctr_meta_attach_to_center',
			        $postType, 'side'	
			    );
			}
			
			add_meta_box( 'ct_study_details', 'Detalles', 'ct_study_meta_details', 
		    			  $postType, 'normal' ); 
			
			add_meta_box( 'ct_study_schedule', 'Horario', 'ct_study_meta_schedule', 
		    			  $postType, 'normal' ); 
		    			  
		    
			
			/*
		    $g_id = get_post_meta($post->ID, 'ct_group_id', true);
		    if ($g_id) {
		    	add_meta_box( 'ct_study_add_members', 'Añadir Nuevos Miembros', 'ct_ctr_meta_add_member', 
		    			  $postType, 'normal' ); 
			    add_meta_box( 
			        'ct_study_members', 'Miembros', 'ct_ctr_meta_members',
			        $postType, 'normal' 
			    );
		    }*/
		    
		    /*
		    add_meta_box(
		    	'ct_study_courses', 'Cursos:', 'ct_study_meta_courses',
		        $postType, 'normal'
		    );*/
			break;

	}
}

function ct_course_meta_objectives($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_objectives',true)); 	
	wp_editor($info, 'objectivescontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'objectivescontent', 'teeny'=>false));
}

function ct_course_meta_structure($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_structure',true)); 	
	wp_editor($info, 'structurecontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'structurecontent', 'teeny'=>false));
}

function ct_course_meta_sessions($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_sessions',true)); 	
	wp_editor($info, 'sessionscontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'sessionscontent', 'teeny'=>false));
}

function ct_course_meta_meditations($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_meditations',true)); 	
	wp_editor($info, 'meditationscontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'meditationscontent', 'teeny'=>false));
}

function ct_course_meta_practice($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_practice',true)); 	
	wp_editor($info, 'practicecontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'practicecontent', 'teeny'=>false));
}

function ct_course_meta_resources($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_resources',true)); 	
	wp_editor($info, 'resourcescontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'resourcescontent', 'teeny'=>false));
}

function ct_course_meta_biblio($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_noncename' );
	$info = html_entity_decode(get_post_meta($post->ID, 'ct_course_biblio',true)); 	
	wp_editor($info, 'bibliocontent', 
			  array('tinymce'=>true, 'wpautop'=>false,
			  		'textarea_name'=>'bibliocontent', 'teeny'=>false));
}



function ct_study_meta_details($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_course_noncename' );

	$level = get_post_meta($post->ID, 'ct_group_level', true);
	$locations = ct_ctr_get_locations(); 
	$loc = get_post_meta($post->ID, 'ct_group_location', true);
?>

	<table class="event_form">
		<tr>
			<td><label><?php _e('Ubicación: ');?></label></td>
			<td><select id="group_loc" name="ct_group_location" style="width:100%;">
				<?php foreach ((array) $locations as $index=>$l) : ?>
				<option value="<?php echo $index; ?>"><?php echo $l['name']; ?></option>
				<?php endforeach; ?>
			</select></div>
			</td>
		</tr>
		<tr>
			<td><label><?php _e('Nivel de Acceso: ');?></label></td>
			<td><select id="group_level" name="ct_group_level" style="width:50%;">
				<option value="0"><?php _e('Abierto al Público'); ?></option>
				<option value="1"><?php _e('Alumnos Regulares'); ?></option>
			</select></div>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#group_loc').val(<?php echo (int) $loc; ?>);
			jQuery('#group_level').val(<?php echo (int) $level; ?>);
		});
	</script>
<?php
}

function ct_study_meta_schedule($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_course_noncename' );

	$weekday = get_post_meta($post->ID, 'ct_group_weekday', true);	
	$start = get_post_meta($post->ID, 'ct_group_start', true);
	$end = get_post_meta($post->ID, 'ct_group_end', true);
?>

	<table class="event_form">
		<tr>
			<td><label><?php _e('Día: ');?></label></td>
			<td><select id="group_weekday" name="ct_group_weekday" style="width:100px;">
				<option value="0"><?php _e('Lunes'); ?></option>
				<option value="1"><?php _e('Martes'); ?></option>
				<option value="2"><?php _e('Miercoles'); ?></option>
				<option value="3"><?php _e('Jueves'); ?></option>
				<option value="4"><?php _e('Viernes'); ?></option>
				<option value="5"><?php _e('Sabado'); ?></option>
				<option value="6"><?php _e('Domingo'); ?></option>
			</select></div>
			</td>
		</tr>
		<tr>
			<td><label><?php _e('Hora de Inicio: ');?></label></td>
			<td><div class="time_picker"><input type="text" name="ct_group_start" class="custom"
				value="<?php echo $start; ?>" ></div>
			</td>
		</tr>
		<tr>
			<td><label><?php _e('Hora de Terminación: ');?></label></td>
			<td><div class="time_picker"><input type="text" name="ct_group_end" class="custom"
				value="<?php echo $end; ?>" ></div>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#group_weekday').val(<?php echo (int) $weekday; ?>);
		    jQuery('.time_picker').timepicker();
			
		});
	</script>
<?php
}

function ct_study_meta_courses($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_course_noncename' );
	
	$q = new WP_Query('post_type=course&nopaging=true&post_parent=0&order=ASC&orderby=menu_order title');
	$options = $q->posts;
	
	$courses = get_post_meta($post->ID, 'ct_study_courses', true);
	$listing = array('post'=>$post->ID);
	if ($courses) {
		$ids = explode(',', $courses);
		foreach((array) $options as $op) {
			if (in_array($op->ID, $ids)) {
				$listing['items'][] = array('id'=>$op->ID, 'label'=>$op->post_title);
			}
			
		}
	}
	
	
	
?>
	<p class="course_form"><select id="ct_study_course" style="width:80%;">
		<option value="0"><?php _e('-- Elige el curso que quieres agregar --'); ?></option>
<?php foreach((array) $options as $op) : ?>
		<option value="<?php echo $op->ID; ?>"><?php echo $op->post_title; ?></option>
<?php endforeach; ?>
	</select>&nbsp;<a class="button primary">Agregar Curso</a>
	<input type="hidden" name="ct_study_courses" value="<?php echo $courses; ?>" />
	</p>
	<ul class="course_listing"></ul>
	<script type="text/javascript">
		CTCourseListing.init(<?php echo json_encode($listing); ?>);
	</script>
<?php
}

function ct_course_meta_materials($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_course_noncename' );
	$m_id = get_post_meta($post->ID, 'ct_course_materials', true);
	$q = new WP_Query('post_type=module&nopaging=true&post_parent=0&order=ASC&orderby=menu_order title');
	$modules = $q->posts;
?>
	<p><select name="ct_course_materials" style="width:100%;">
		<option value="0"><?php _e('-- Elige los materiales para este curso --'); ?></option>
<?php foreach($modules as $mod) : ?>
		<option value="<?php echo $mod->ID; ?>"><?php echo $mod->post_title; ?></option>
<?php endforeach; ?>
	</select></p>
	<script type="text/javascript">
		jQuery('select[name=ct_course_materials]').val(<?php echo $m_id; ?>);
	</script>
<?php
}

function ct_course_meta_community($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_course_noncename' );
	
	$use_community = get_post_meta($post->ID, 'ct_course_use_community', true);
	
	$f_id = get_post_meta($post->ID, 'ct_course_forum', true);
	$forum = get_post($f_id);
?>
	<p><input type="checkbox" name="ct_course_use_community" value="1" /><?php _e('&nbsp;¿Activa soporte para comunidades?'); ?></p>
	<div class="community_info" style="display:none;">
		<p></p><b>Foro:</b><br />
		<a href="<?php echo admin_url('post.php?post='.$f_id.'&action=edit'); ?>"><?php echo $forum->post_title; ?></a></p>
	</div>
	<script type="text/javascript">
		var use_community = jQuery('input[name=ct_course_use_community]');
		var community_info = jQuery('.community_info');
		<?php if ($use_community || $f_id) : ?>
			use_community.attr('checked', true);
			community_info.show();
		<?php endif; ?>
		use_community.click(function() {
			if(jQuery(this).is(':checked')) {
				community_info.show();
			} else {
				community_info.hide();
			}
		});
	</script>
	<?php
}

function ct_course_activate_community($post_id) {
	$f_id = get_post_meta($post_id, 'ct_course_forum', true);
	if (!$f_id) {
		remove_action('save_post', 'ct_course_save_postdata');
		ct_course_create_forum($post_id);
		add_action('save_post', 'ct_course_save_postdata');
	}
	update_post_meta($post_id, 'ct_course_use_community', true);
}

function ct_course_deactivate_community($post_id) {
	$f_id = get_post_meta($post_id, 'ct_course_forum', true);
	if ($f_id) wp_delete_post($f_id, true);
	update_post_meta($post_id, 'ct_course_forum', '');
	update_post_meta($post_id, 'ct_course_use_community', false);
}

function ct_course_create_forum($post_id) {
	
	$u_id = get_current_user_id();
		
	// Root Forum			
	$q = new WP_Query('name='.CT_COURSE_ROOT_FORUM.'&post_type='.bbp_get_forum_post_type());
	$parent = $q->post;
	
	$p = get_post($post_id);
	if ($p->post_type == 'course') {
	
		$forum = array(
			'post_parent'    => ($parent->ID) ? $parent->ID : 0,
			'post_status'    => bbp_get_public_status_id(),
			'post_type'      => bbp_get_forum_post_type(),
			'post_author'    => $u_id,
			'post_password'  => '',
			'post_content'   => '',
			'post_title'     => $p->post_title,
			'menu_order'     => 0,
			'comment_status' => 'closed'
		);
		$f_id = wp_insert_post($forum);
		update_post_meta($post_id, 'ct_course_forum', $f_id);
	
	}
    
}

add_action( 'save_post', 'ct_course_save_postdata' );
function ct_course_save_postdata( $post_id ) {
	
    global $post_type;
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_course_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_course_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
  
	switch($post_type) {
		case 'study-group':
			if (isset($_POST['ct_group_level'])) {
				update_post_meta($post_id, 'ct_group_level', $_POST['ct_group_level']);
			}
			if (isset($_POST['ct_group_location'])) {
				update_post_meta($post_id, 'ct_group_location', $_POST['ct_group_location']);
			}
			if (isset($_POST['ct_group_weekday'])) {
				update_post_meta($post_id, 'ct_group_weekday', $_POST['ct_group_weekday']);
			}
			if (isset($_POST['ct_group_start'])) {
				update_post_meta($post_id, 'ct_group_start', $_POST['ct_group_start']);
			}
			if (isset($_POST['ct_group_end'])) {
				update_post_meta($post_id, 'ct_group_end', $_POST['ct_group_end']);
			}
			break;
		default:
			if ($_POST['ct_course_use_community']) {
				ct_course_activate_community($post_id);
			} else {
				ct_course_deactivate_community($post_id);
			}
			
			if (isset($_POST['objectivescontent'])) {  
			  	update_post_meta($post_id, 'ct_course_objectives', $_POST['objectivescontent']);
			}
			if (isset($_POST['structurecontent'])) {  
			  	update_post_meta($post_id, 'ct_course_structure', $_POST['structurecontent']);
			}
			if (isset($_POST['sessionscontent'])) {  
			  	update_post_meta($post_id, 'ct_course_sessions', $_POST['sessionscontent']);
			}
			if (isset($_POST['objectivescontent'])) {  
			  	update_post_meta($post_id, 'ct_course_meditations', $_POST['meditationscontent']);
			}
			if (isset($_POST['practicecontent'])) {  
			  	update_post_meta($post_id, 'ct_course_practice', $_POST['practicecontent']);
			}
			if (isset($_POST['resourcescontent'])) {  
			  	update_post_meta($post_id, 'ct_course_resources', $_POST['resourcescontent']);
			}
			if (isset($_POST['bibliocontent'])) {  
			  	update_post_meta($post_id, 'ct_course_biblio', $_POST['bibliocontent']);
			}
			
			if (isset($_POST['ct_course_materials'])) {
				update_post_meta($post_id, 'ct_course_materials', $_POST['ct_course_materials']);
			}
			
			if (isset($_POST['ct_study_courses'])) {
				update_post_meta($post_id, 'ct_study_courses', $_POST['ct_study_courses']);
			}
			break;
		
	}
	
	
	
	
	
}

add_action('wp_insert_post', 'ct_study_insert_post');
function ct_study_insert_post($post_id) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;

	global $post_type;
	switch($post_type) {
		case 'study-group':
		
			$p = get_post($post_id);
			$g_id = get_post_meta($post_id, 'ct_group_id', true);
			if ($p->post_status != 'auto-draft' && !$g_id) {
				
				$u_id = get_current_user_id();			
				$group = array('creator_id'=>$u_id,
						   'name'=>$p->post_title,
						   'slug'=>'study-group-'.$p->post_name,
						   'status'=>'hidden',
						   'enable_forum'=>0,
						   'date_created'=>current_time('mysql'));
				// TODO: $g_id = groups_create_group($group);
				if ($g_id) {
					update_post_meta($post_id, 'ct_group_id', $g_id);
					groups_update_groupmeta( $g_id, 'total_member_count', 1 );
					groups_edit_group_settings( $g_id, true, 'hidden', 'mods' );	
				}
			}
			break;
	}
}

add_action( 'before_delete_post', 'ct_course_delete_post');
function ct_course_delete_post($post_id) {
	global $post_type;
	switch($post_type) {
		case 'course':
			ct_course_deactivate_community($post_id);
			break;
		case 'study-group':
			$g_id = get_post_meta($post_id, 'ct_group_id', true);
			if ($g_id) groups_delete_group($g_id);
			break;
	}
}

/* FILTERS *****************************************************/

function ct_group_archive_filter($args) {
									
	$config = array();
	$config['args'] = array('post_type'=>'study-group',
							'nopaging'=>true,
							 'order'=>'ASC',
							 'orderby'=>'meta_value',
							 'meta_key'=>'ct_group_start',
							 'tax_query'=>array(),
							 'meta_query'=>array());
							 
	$config['title'] = ($args['title']) ? $args['title'] : __('Grupos de Estudio');
	$config['no_results'] = __('Actualmente no se encuentra ningun grupo en esta categoria.');
							 
	if ($args['course-type'] && $args['course-type'] != 'all') {
		$config['args']['tax_query'][] = array(
							'taxonomy' => 'course-type',
							'field' => 'slug',
							'terms' => $args['course-type']
							);	
	}

	if (isset($args['loc']) && $args['loc'] != 'all') {
	
		$config['args']['meta_query'][] = array(
							'key' => 'ct_group_location',
							'value' => $args['loc']
							);	
	}
	
	if (isset($args['level']) && $args['level'] != 'all') {
	
		$config['args']['meta_query'][] = array(
							'key' => 'ct_group_level',
							'value' => $args['level']
							);	
	}

	return $config;
}
add_filter('ct_archive-study-group', 'ct_group_archive_filter', 10, 1);

/** SHORTCODES ***********************************************************/

function ct_courses_shortcode( $atts, $content='' ) {
	
	global $post;
	
	if ($post) {
		
		$args = array('post_type'=>'course',
					  'order'=>'ASC',
				      'orderby'=>'menu_order',
					  'nopaging'=>true,
					  'tax_query'=>array());
		
		
								 
		if ($atts['program']) {
			$args['tax_query'][] = array(
								'taxonomy' => 'course-program',
								'field' => 'slug',
								'terms' => $atts['program']
							);
		}
		
		$q = new WP_Query($args);
		$pages = $q->posts;
		
		if ($pages) {
			$atts['title'] = __('Cursos');
			
			$items = array();
			foreach((array) $pages as $p) {
				$terms = array_values(wp_get_post_terms($p->ID, 'course-type'));
				$slug = $terms[0]->slug;
				$items[$slug][] = $p;
			}
			
			if (count($items) == 1) {
				$atts['no_header'] = true;
				$keys = array_keys($items);
				$term = get_term_by('slug', $keys[0], 'course-type');
				$atts['title'] = $term->name;
			}
			
			$GLOBALS['tPages'] = $items;
			ct_set_template_args($atts, $content);
			return ct_load_template_part('shortcode', 'courses');	
		}
	}
		
	return '';
	
}
add_shortcode( 'ct-courses', 'ct_courses_shortcode' );

?>