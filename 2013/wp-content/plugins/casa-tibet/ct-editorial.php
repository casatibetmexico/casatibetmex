<?php

/** CUSTOM POSTS ***************************************************************/

add_action('init', 'ct_editorial_init');
function ct_editorial_init() {
	

register_post_type('publication', array(	'label' => 'Publicaciones', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'publication', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'publicaciones','query_var' => true,'has_archive' => true,'menu_position' => 5,'supports' => array('title','editor', 'thumbnail'),'labels' => array (
  'name' => 'Publicaciones',
  'singular_name' => 'Publicación',
  'menu_name' => 'Publicaciones',
  'add_new' => 'Agregar Publicación',
  'add_new_item' => 'Agregar Nuevo Publicación',
  'edit' => 'Editar',
  'edit_item' => 'Editar Publicación',
  'new_item' => 'Nuevo Publicación',
  'view' => 'Ver Publicación',
  'view_item' => 'Ver Publicación',
  'search_items' => 'Buscar Publicación',
  'not_found' => 'No Hay Publicaciones',
  'not_found_in_trash' => 'No hay Publicaciones en la Papelera',
  'parent' => 'Publicación Superior',
),) );

register_taxonomy('publication-type',array('publication'),array( 'hierarchical' => true, 'label' => 'Tipos de Publicaciones','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Tipo de Publicación') );

register_post_type('project', array(	'label' => 'Proyectos Editoriales', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'publication', 'map_meta_cap' => true, 'hierarchical' => true,'rewrite' => 'proyectos','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor', 'thumbnail'),'labels' => array (
  'name' => 'Proyectos',
  'singular_name' => 'Proyecto',
  'menu_name' => 'Proyectos',
  'add_new' => 'Agregar Proyecto',
  'add_new_item' => 'Agregar Nuevo Proyecto',
  'edit' => 'Editar',
  'edit_item' => 'Editar Proyecto',
  'new_item' => 'Nuevo Proyecto',
  'view' => 'Ver Proyecto',
  'view_item' => 'Ver Proyecto',
  'search_items' => 'Buscar Proyecto',
  'not_found' => 'No Hay Proyectos',
  'not_found_in_trash' => 'No hay Proyectos en la Papelera',
  'parent' => 'Proyecto Superior',
),) );
 
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_editorial_add_meta_boxes' );
function ct_editorial_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'publication':
			add_meta_box( 
		        'ct_pub_specs_sidebar', 'Especificaciones de la Publicación', 'ct_pub_specs_meta_sidebar',
		        $postType, 'side', 'core'
		    );
			add_meta_box( 
		        'ct_pub_sidebar', 'Información Lateral', 'ct_teacher_meta_sidebar',
		        $postType, 'normal'
		    );
		   /*
 add_meta_box( 
		        'ct_pub_details_sidebar', 'Detalles Adicionales', 'ct_pub_details_meta_sidebar',
		        $postType, 'side', 'core'
		    );
*/
			break;
		case 'project':
			add_meta_box( 
		        'ct_pub_paypal_sidebar', 'PayPal', 'ct_pub_paypal_meta_sidebar',
		        $postType, 'side', 'core'
		    );
			break;
	}
}

function ct_pub_paypal_meta_sidebar($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_editorial_noncename' );
	$paypal = get_post_meta($post->ID, 'ct_paypal', true);
?>
	<p><b>Cuenta de PayPal (email):</b><br />
	<input type="text" name="ct_paypal[email]" value="<?php echo $paypal['email']; ?>" style="width:100%;" /></p>
	<p><b>Concepto:</b><br />
	<input type="text" name="ct_paypal[concept]" value="<?php echo $paypal['concept']; ?>" style="width:100%;" /></p>
<?php
}

function ct_pub_specs_meta_sidebar($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_editorial_noncename' );
	$subtitle = get_post_meta($post->ID, 'ct_pub_subtitle', true);
	$pub_date = get_post_meta($post->ID, 'ct_pub_publish_date', true);
	$details = get_post_meta($post->ID, 'ct_pub_details', true);
?>
	<p><b>Sub-Título:</b><br />
	<input type="text" name="ct_pub_subtitle" value="<?php echo $subtitle; ?>" style="width:100%;" /></p>
	<p><b>Autor:</b><br />
	<input type="text" name="ct_pub_details[author]" value="<?php echo $details['author']; ?>" style="width:100%;" /></p>
	<p><b>Fecha Publicada:</b><br />
	<input class="datepicker" type="text" name="ct_pub_publish_date" value="<?php echo $pub_date; ?>" style="width:100px;" /></p>
	<p><b>Costo:</b><br />
	<input type="text" name="ct_pub_details[cost]" value="<?php echo $details['cost']; ?>" style="width:100%;" /></p>
	<p><b>Envio:</b><br />
	<input type="text" name="ct_pub_details[shipping]" value="<?php echo $details['shipping']; ?>" style="width:100%;" /></p>
	<p><b>Descarga:</b><br />
	<input id="ct_pub_download" type="text" class="media" name="ct_pub_details[download]" value="<?php echo $details['download']; ?>" style="width:100%;" /></p>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.datepicker').datepicker({
			        dateFormat : 'yy-mm-dd'
			    });
		});
		var cur_item = '';
		jQuery('input.media').click(function() {
			cur_item = jQuery(this).attr('id');
			openMediaBrowser(cur_item, {title:"Elije Archivo", button:"Select", multiple:false},
		 	function(attachment) {
		 		jQuery('#'+cur_item).val(attachment.url);
		 	});
		
			
		});
	</script>
<?php
}

function ct_pub_details_meta_sidebar($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_editorial_noncename' );
	
?>
	
<?php
}

add_action( 'save_post', 'ct_editorial_save_postdata' );
function ct_editorial_save_postdata( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_editorial_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_editorial_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	if (isset($_POST['ct_pub_details'])) {  
	  	update_post_meta($post_id, 'ct_pub_details', $_POST['ct_pub_details']);
	}
	if (isset($_POST['ct_paypal'])) {  
	  	update_post_meta($post_id, 'ct_paypal', $_POST['ct_paypal']);
	}
	
	if (isset($_POST['ct_pub_subtitle'])) {  
	  	update_post_meta($post_id, 'ct_pub_subtitle', $_POST['ct_pub_subtitle']);
	}
	if (isset($_POST['ct_pub_publish_date'])) {  
	  	update_post_meta($post_id, 'ct_pub_publish_date', $_POST['ct_pub_publish_date']);
	}
	
}

/* FILTERS *****************************************************/

function ct_editorial_blog_archive_filter($args) {

	$config = array();
	$config['args'] = array('post_type'=>'post',
							 'posts_per_page'=>$args['limit'],
							 'paged'=>($_GET['pg']) ? $_GET['pg'] : 1,
							 'order'=>'DESC',
							 'orderby'=>'date',
							 'meta_query'=>array());
	$config['no_results'] = __('Actualmente no se encuentra ningun entrada para esta fecha.');
	$config['title'] = __('Entradas Recientes');
	
	if ($args['category']) {
		$config['args']['category_name'] = $args['category'];
	}
	
	global $domainTheme;
	$config['args']['tax_query'][] = array(
							'taxonomy' => 'site',
							'field' => 'slug',
							'terms' => $domainTheme->stylesheet
							);

	if ($_GET['date']) {
			
		$parts = explode('-', $_GET['date']);
		$title_format = __('Entradas : %s');
		
		$config['args']['year'] = (int) $parts[0];
		$config['args']['monthnum'] = (int) $parts[1];
		
		if (!$parts[2]) { // Month
					
			$date = implode('-', $parts);
			$config['title'] = sprintf($title_format, ucfirst(date_i18n('F, Y', strtotime($date))));
			$config['no_results'] = __('Actualmente no se encuentra ningun entrada para esta mes.');
							
		} else { // Day
		
			$config['args']['day'] = (int) $parts[2];
				
			$day = $parts[2];
			$date = implode('-', $parts);
			
			$config['title'] = sprintf($title_format, $day.' de '.ucfirst(date_i18n('F, Y', strtotime($date))));
			$config['no_results'] = __('Actualmente no se encuentra ningun entrada para esta fecha.');
		
		}
	} 
	
	if ($args['title']) {
		$config['title'] = $args['title'];
	}

	return $config;
}
add_filter('ct_archive-editorial', 'ct_editorial_blog_archive_filter', 10, 1);

function ct_editorial_project_archive_filter($args) {
									
	$config = array();
	$config['args'] = array('post_type'=>'project',
							'nopaging'=>true,
							 'order'=>'ASC',
							 'orderby'=>'title',
							 'tax_query'=>array(),
							 'meta_query'=>array());
							 
	$config['title'] = ($args['title']) ? $args['title'] : __('Proyectos Activos');
	$config['no_results'] = __('Actualmente no se encuentra ningun proyecto activo.');

	return $config;
}
add_filter('ct_archive-project', 'ct_editorial_project_archive_filter', 10, 1);

function ct_editorial_publication_archive_filter($args) {
									
	$config = array();
	$config['args'] = array('post_type'=>'publication',
							'nopaging'=>true,
							 'order'=>'DESC',
							 'meta_key'=>'ct_pub_publish_date',
							 'orderby'=>'meta_value',
							 'tax_query'=>array(),
							 'meta_query'=>array());
	
	if ($args['limit']) {
		$config['args']['posts_per_page'] = $args['limit'];
		$config['args']['nopaging'] = false;
	}
							 
	$config['title'] = ($args['title']) ? $args['title'] : __('Proyectos Activos');
	$config['no_results'] = __('Actualmente no se encuentra ningun proyecto activo.');
	
	if ($args['slug']) {
		$config['args']['tax_query'][] = array(
							'taxonomy' => 'publication-type',
							'field' => 'slug',
							'terms' => $args['slug']
							);
	}

	return $config;
}
add_filter('ct_archive-publication', 'ct_editorial_publication_archive_filter', 10, 1);


/** SHORTCODES ***************************************************/

function ct_publications_shortcode($atts, $content) {

	return ct_sidebar('archive', array('title'=>($atts['title']) ? $atts['title'] : __('Publicaciones'),
								      'post_type'=>'publication', 
								      'listing'=>'publication-simple',
								      'slug'=>$atts['type']), true);

}
add_shortcode( 'ct-publications', 'ct_publications_shortcode' );

?>