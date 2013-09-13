<?php

/** CUSTOM POST : EVENTOS ***************************************************************/

add_action('admin_enqueue_scripts', 'ct_event_enqueue_scripts', 0);
function ct_event_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-datepicker');
	//wp_enqueue_style('jquery-ui-style', plugins_url('casa-tibet').'/css/jquery-ui.css');
	wp_enqueue_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	wp_enqueue_script('jquery-ui-timepicker', plugins_url('casa-tibet').'/js/jquery.timepicker.js', array('jquery'));
}

add_action('init', 'ct_event_init');
function ct_event_init() {
	

register_post_type('event', array(	'label' => 'Eventos', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'event', 'map_meta_cap' => true, 'hierarchical' => false,'rewrite' => 'calendario','query_var' => true,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor','thumbnail'),'labels' => array (
  'name' => 'Eventos',
  'singular_name' => 'Evento',
  'menu_name' => 'Eventos',
  'add_new' => 'Agregar Evento',
  'add_new_item' => 'Agregar Nuevo Evento',
  'edit' => 'Editar',
  'edit_item' => 'Editar Evento',
  'new_item' => 'Nuevo Evento',
  'view' => 'Ver Evento',
  'view_item' => 'Ver Evento',
  'search_items' => 'Buscar Evento',
  'not_found' => 'No Hay Eventos',
  'not_found_in_trash' => 'No hay Eventos en la Papelera',
  'parent' => 'Evento Superior',
),) );

register_taxonomy('event-type',array('event'),array( 'hierarchical' => true, 'label' => 'Tipos de Evento','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Tipo de Evento') );
 
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_event_add_meta_boxes' );
function ct_event_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'event':
			add_meta_box( 
		        'ct_ctr_content', 'Organizado por:', 'ct_ctr_meta_attach_to_center',
		        $postType, 'side'	
		    );
			add_meta_box( 
		        'ct_event_teacher', 'Impartido por:', 'ct_teacher_meta_attach_teacher',
		        $postType, 'side'
		    );
		    add_meta_box( 
		        'ct_event_date_time', 'Fecha y Hora del Evento', 'ct_event_meta_date_time',
		        $postType, 'normal'
		    );
		    add_meta_box( 
		        'ct_event_location', 'Lugar', 'ct_event_meta_location',
		        $postType, 'normal'
		    );
		    add_meta_box( 
		        'ct_event_tickets', 'Costos y Boletos', 'ct_event_meta_tickets',
		        $postType, 'normal'
		    );
			break;
	}
}

function ct_event_meta_date_time($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_event_noncename' );
	$start = array();
	list($start['date'], $start['time']) = explode(' ', get_post_meta($post->ID, 'ct_event_start', true));
	$end = array();
	list($end['date'], $end['time']) = explode(' ', get_post_meta($post->ID, 'ct_event_end', true));
	$options = get_post_meta($post->ID, 'ct_event_options', true);
?>

	<table class="event_form">
		<tr>
			<td><label><?php _e('Inicio: ');?></label></td>
			<td><div class="date_picker"><input type="text" name="ct_event_start[date]" class="custom"
					value="<?php echo $start['date']; ?>" /></div>
			<div class="time_picker"><input type="text" name="ct_event_start[time]" class="custom"
				value="<?php echo $start['time']; ?>" ></div>
			</td>
		</tr>
		<tr class="event_end">
			<td><label><?php _e('Terminación: ');?></label></td>
			<td><div class="date_picker"><input type="text" name="ct_event_end[date]" class="custom"
					 value="<?php echo $end['date']; ?>" /></div>
			<div class="time_picker"><input type="text" name="ct_event_end[time]" class="custom" 
				 value="<?php echo $end['time']; ?>" /></div>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input id="ct_event_option_allday" type="checkbox" name="ct_event_options[allday]" value="1">
			&nbsp;<?php _e('¿Evento de todo el día?');?>&nbsp;&nbsp;&nbsp;<input id="ct_event_option_has_end" type="checkbox" name="ct_event_options[has_end]" value="1">&nbsp;<?php _e('¿Tiene tiempo de terminación?');?>
			</td>
		</tr>
	</table>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#ct_event_option_allday').click(function() {
				var inputs = jQuery('.time_picker input');
				if (jQuery(this).attr('checked')) {
					inputs.parent().hide();
					inputs.find('input').attr('disabled', true);
				} else {
					inputs.parent().show();
					inputs.find('input').attr('disabled', false);
				}
			});
			
			
			jQuery('#ct_event_option_has_end').click(function() {
				var row = jQuery('.event_end');
				if (jQuery(this).attr('checked')) {
					row.show();
					row.find('input').attr('disabled', false);
				} else {
					row.hide();
					row.find('input').attr('disabled', true);
				}
			});
		    jQuery('.date_picker input').datepicker({
		        dateFormat : 'yy-mm-dd'
		    });
		    jQuery('.time_picker').timepicker();
		    
		    <?php if ($options['allday'] == 1) : ?>
			jQuery('#ct_event_option_allday').click();
			var inputs = jQuery('.time_picker input');
			inputs.parent().hide();
			inputs.find('input').attr('disabled', true);
			<?php endif;?>
			
			<?php if ($options['has_end'] == 1) : ?>
			jQuery('#ct_event_option_has_end').click();
			var row = jQuery('.event_end');
			row.show();
			row.find('input').attr('disabled', false);
			<?php endif;?>
		});
	</script>
<?php
}

function ct_event_meta_location($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_event_noncename' );
	
	$location  = get_post_meta($post->ID, 'ct_event_location', true);

?>

	<table class="event_form">
		<tr>
			<td><label><?php _e('Nombre del Recinto: ');?></label></td>
			<td><input type="text" name="ct_event_location[name]" value="<?php echo $location['name']; ?>" style="width:100%;"/></td>
		</tr>
		<tr>
			<td><label><?php _e('Dirección: ');?></label></td>
			<td><input type="text" name="ct_event_location[address]" value="<?php echo $location['address']; ?>" style="width:100%;"/></td>
		</tr>
		<tr>
			<td><label><?php _e('Mapa (terminos de búsqueda): ');?></label></td>
			<td><input type="text" name="ct_event_location[map]" value="<?php echo $location['map']; ?>" style="width:100%;"/></td>
		</tr>
	</table>
<?php
}

function ct_event_meta_tickets($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_event_noncename' );
	$status  = get_post_meta($post->ID, 'ct_event_status', true);
	$tickets = get_post_meta($post->ID, 'ct_event_tickets', true);
	$options = get_post_meta($post->ID, 'ct_event_options', true);

?>

	<table id="event_form_tickets" class="event_form">
		<tr>
			<td><label><?php _e('¿El evento tiene costo?');?></label></td>
			<td><input id="ct_event_option_has_tickets" type="checkbox" name="ct_event_options[has_tickets]" value="1"></td>
		</tr>
		<tr>
			<td><label><?php _e('Precio: ');?></label></td>
			<td><input type="text" name="ct_event_tickets[price]" value="<?php echo $tickets['price']; ?>" /></td>
		</tr>
		<tr>
			<td><label><?php _e('No. de Boletos: ');?></label></td>
			<td><input type="text" name="ct_event_tickets[total]" value="<?php echo $tickets['total']; ?>" /></td>
		</tr>
		<tr>
			<td><label><?php _e('No. de Boletos Vendidos: ');?></label></td>
			<td><input type="text" name="ct_event_tickets[sold]" value="<?php echo $tickets['sold']; ?>" /></td>
		</tr>
		<tr>
			<td><label><?php _e('Estatus del Evento: ');?></label></td>
			<td><select name="ct_event_status">
				<option value=""><?php _e('-- Elige un estatus --');?></option>
				<option value="pre-sale"><?php _e('Pre-Venta');?></option>
				<option value="open"><?php _e('Abierto');?></option>
				<option value="sold-out"><?php _e('Agotado');?></option>
				<option value="wait-list"><?php _e('Lista de Espera');?></option>
			</select></td>
		</tr>
	</table>
	<script type="text/javascript">
		function toggleTicketRows(state) {
			var rows = jQuery('#event_form_tickets tr:gt(0)');
			if (state) {
				rows.show();
				rows.find('input, select').attr('disabled', false);
			} else {
				rows.hide();
				rows.find('input, select').attr('disabled', true);
			}
		}
		jQuery(document).ready(function() {
		
			jQuery('#ct_event_option_has_tickets').click(function() {
				toggleTicketRows(jQuery(this).is(':checked'));
			});
		
			<?php if ($options['has_tickets'] == 1) : ?>
			jQuery('#ct_event_option_has_tickets').click();
			toggleTicketRows(true);
			<?php else : ?>
			toggleTicketRows(false);
			<?php endif;?>
		
			jQuery('select[name=ct_event_status]').val('<?php echo $status; ?>');
		});
	</script>
<?php
}

add_action( 'save_post', 'ct_event_save_postdata' );
function ct_event_save_postdata( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_event_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_event_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;	
    	
    update_post_meta($post_id, 'ct_event_options', (array) $_POST['ct_event_options']);
	
	if (isset($_POST['ct_event_start'])) {  
		$start = $_POST['ct_event_start']['date'];
		if ($_POST['ct_event_start']['time']) $start .= ' '.$_POST['ct_event_start']['time'];
	  	update_post_meta($post_id, 'ct_event_start', $start);
	}
	if (isset($_POST['ct_event_end'])) {  
	  	$start = $_POST['ct_event_end']['date'];
		if ($_POST['ct_event_end']['time']) $start .= ' '.$_POST['ct_event_end']['time'];
	  	update_post_meta($post_id, 'ct_event_end', $start);
	}
	if (isset($_POST['ct_event_status'])) {  
	  	update_post_meta($post_id, 'ct_event_status', $_POST['ct_event_status']);
	}
	if (isset($_POST['ct_event_tickets'])) {  
	  	update_post_meta($post_id, 'ct_event_tickets', $_POST['ct_event_tickets']);
	}
	if (isset($_POST['ct_event_location'])) {  
	  	update_post_meta($post_id, 'ct_event_location', $_POST['ct_event_location']);
	}
	
}

/* FILTERS *****************************************************/

function ct_event_archive_filter($args) {

	$config = array();
	$config['args'] = array('post_type'=>'event',
							 'posts_per_page'=>$args['limit'],
							 'paged'=>$_GET['pg'],
							 'order'=>($args['order']) ? $args['order'] : 'ASC',
							 'meta_key'=>'ct_event_start',
							 'orderby'=>'meta_value',
							 'meta_query'=>array());

	if ($_GET['date']) {
			
		$parts = explode('-', $_GET['date']);
		$title_format = __('Eventos : %s');
		
		if (!$parts[2]) { // Month
		
			$config['no_results'] = __('Actualmente no se encuentra ningun evento para esta mes.');
		
			$parts[2] = '01';
			
			$date = implode('-', $parts);
			
			$config['title'] = sprintf($title_format, ucfirst(date_i18n('F, Y', strtotime($date))));
		
			$config['args']['meta_query']['relation'] = 'AND';
			$config['args']['meta_query'][] = array(
			 	'key'=>'ct_event_start',
			 	'value'=>date('Y-m-d H:i:s', strtotime($date.' 00:00:00')),
			 	'compare'=>'>=',
			 	'type'=>'DATETIME'
			);
			$config['args']['meta_query'][] = array(
			 	'key'=>'ct_event_start',
			 	'value'=>date('Y-m-d H:i:s', strtotime('+1 month', strtotime($date.' 23:59:59'))),
			 	'compare'=>'<=',
			 	'type'=>'DATETIME'
			);
			
		} else { // Day
		
			$config['no_results'] = __('Actualmente no se encuentra ningun evento para esta fecha.');
		
			$day = $parts[2];
			$date = implode('-', $parts);
			
			$config['title'] = sprintf($title_format, $day.' de '.ucfirst(date_i18n('F, Y', strtotime($date))));
			
			$config['args']['meta_query']['relation'] = 'AND';
			$config['args']['meta_query'][] = array(
			 	'key'=>'ct_event_start',
			 	'value'=>date('Y-m-d H:i:s', strtotime($date.' 00:00:00')),
			 	'compare'=>'>=',
			 	'type'=>'DATETIME'
			);
			$config['args']['meta_query'][] = array(
			 	'key'=>'ct_event_start',
			 	'value'=>date('Y-m-d H:i:s', strtotime($date.' 23:59:59')),
			 	'compare'=>'<=',
			 	'type'=>'DATETIME'
			);
		
		}
	} elseif ($args['archive']) { // Past Events
		$config['no_results'] = __('Actualmente no se encuentra ningun evento pasado.');
		$config['title'] = __('Eventos Pasados');
		$config['args']['meta_query'][] = array(
		 	'key'=>'ct_event_start',
		 	'value'=>date('Y-m-d H:i:s', strtotime('now')),
		 	'compare'=>'<',
		 	'type'=>'DATETIME'
		 );
	} else { // Upcoming Events [Default]
		$config['no_results'] = __('Actualmente no hay proximos eventos.');
		$config['title'] = __('Proximos Eventos');
		$config['args']['meta_query'][] = array(
		 	'key'=>'ct_event_start',
		 	'value'=>date('Y-m-d H:i:s', strtotime('now')),
		 	'compare'=>'>=',
		 	'type'=>'DATETIME'
		 );
	}

	return $config;
}
add_filter('ct_archive-event', 'ct_event_archive_filter', 10, 1);


/* API FUNCTIONS ***********************************************/

function ct_event_get_upcoming($limit = 5, $args=array()) {

	global $post;

	$args = array_merge(array('post_type'=>'event',
				 'posts_per_page'=>$limit,
				 'order'=>'ASC',
				 'meta_key'=>'ct_event_start',
				 'orderby'=>'meta_value',
				 'meta_query'=>array(
				 	array(
					 	'key'=>'ct_event_start',
					 	'value'=>date('Y-m-d h:i:s', strtotime('now')),
					 	'compare'=>'>=',
					 	'type'=>'DATETIME'
					 )
				  )), $args);
	
	if ($post) {
		$args['post__not_in'] = array($post->ID);
	}
	
	$args = ct_ctr_query_args($args);
	
	$q = new WP_Query($args); 
	return $q->posts;
}

function ct_event_get_thumbnail($post, $size='thumb') {
	$id = get_post_thumbnail_id($post->ID);
	if (!$id) {
		$teacher = get_post_meta($post->ID, 'ct_teacher', true);
		if ($teacher) return ct_get_thumbnail(get_post($teacher), $size);
	}
	return ct_get_thumbnail($post, $size);
}

function ct_event_get_period($post, $type="full") {
	$start = explode(' ', get_post_meta($post->ID, 'ct_event_start', true));
	$period = date_i18n('j F, Y', strtotime($start[0]));
	
	if ($start[1]) {
			$time = date_i18n('h:i a', strtotime($start[1]));
			$period .= ' @ '.$time;
	}
	
	if ($type == 'start') return $period;
	
	$end = explode(' ', get_post_meta($post->ID, 'ct_event_end', true));
	if ($end[0]) {
		$period .= ' - '.date_i18n('j F, Y', strtotime($end[0]));
		if ($end[1]) {
			$time = date_i18n('h:i a', strtotime($end[1]));
			$period .= ' @ '.$time;
		}
	}
	return $period;
}



?>