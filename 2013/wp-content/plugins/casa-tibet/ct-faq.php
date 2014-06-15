<?php

/** CUSTOM POST : FAQ ***************************************************************/

add_action('init', 'ct_faq_init');
function ct_faq_init() {
	

register_post_type('question', array(	'label' => 'Preguntas', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'page', 'map_meta_cap' => true, 'hierarchical' => false,'rewrite' => false,'query_var' => false,'has_archive' => false,'menu_position' => 5,'supports' => array('title','editor','thumbnail'),'labels' => array (
  'name' => 'Preguntas',
  'singular_name' => 'Pregunta',
  'menu_name' => 'Preguntas',
  'add_new' => 'Agregar Pregunta',
  'add_new_item' => 'Agregar Nueva Pregunta',
  'edit' => 'Editar',
  'edit_item' => 'Editar Pregunta',
  'new_item' => 'Nuevo Pregunta',
  'view' => 'Ver Pregunta',
  'view_item' => 'Ver Pregunta',
  'search_items' => 'Buscar Pregunta',
  'not_found' => 'No Hay Preguntas',
  'not_found_in_trash' => 'No hay Preguntas en la Papelera',
  'parent' => 'Pregunta Superior',
),) );

register_taxonomy('faq-topic',array('question'),array( 'hierarchical' => true, 'label' => 'Topicos','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Topicos') );
 
}

/** SHORTCODE **********************************************************/

function ct_faq_shortcode( $atts, $content='' ) {
	

	$html = '<div class="section listing faq"><ul>';
	
	$topics = get_terms('faq-topic', 'order=ASC&orderby=name');
	
	foreach((array) $topics as $topic) {
	
		$html .= '<li class="list_header">'.$topic->name.'</li>';
	
		$args = array('post_type'=>'question',
					  'nopaging'=>true,
					  'order'=>'ASC',
					  'orderby'=>'title',
					  'tax_query'=>array());
		$args['tax_query'][] = array(
							'taxonomy' => 'faq-topic',
							'field' => 'slug',
							'terms' => $topic->slug
							);	
							
		$q = new WP_Query($args);
		
		foreach((array) $q->posts as $question) {
			$id = $question->ID;
			$html .= '<li class="question" data-question="'.$question->ID.'">';
			$html .= '<div class="faq_title">'.$question->post_title.'</div>';
			$html .= '<div class="answer"><p>'.$question->post_content.'</p></div>';
			$html .= '</li>';
		}
	
	}
	
	$html .= "</ul>";
	
	$html .= '<script type="text/javascript">';
	$html .= 'jQuery(".listing.faq").faq();';
	$html .= '</script>';
	return $html;
		
	
}
add_shortcode( 'ct-faq', 'ct_faq_shortcode' );

?>