<?php

/* CUSTOM POST *****************************************************/

register_taxonomy('post-cat',array('post'),array( 'hierarchical' => true, 'label' => 'Tipos de Entrada','show_ui' => true,'query_var' => false,'rewrite' => false, 'singular_label' => 'Tipo de Entrada') );

/* FILTERS *****************************************************/

function ct_news_archive_filter($args) {

	$config = array();
	$config['args'] = array('post_type'=>'post',
							'category_name'=>'noticias',
							 'posts_per_page'=>$args['limit'],
							 'paged'=>$_GET['pg'],
							 'order'=>'DESC',
							 'orderby'=>'date',
							 'meta_query'=>array());
	$config['no_results'] = __('Actualmente no se encuentra ningun entrada para esta fecha.');
	$config['title'] = __('Entradas Recientes');

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
	
	if ($args['category'] != 'all') {
		global $domainTheme;
		$config['args']['tax_query'][] =  array(
							'taxonomy' => 'site',
							'field' => 'slug',
							'terms' => $domainTheme->stylesheet
							);
	}
	
	if ($args['title']) {
		$config['title'] = $args['title'];
	}	
	
	if (ct_is_theme('ct-centro') || ct_is_theme('ct-mex')) 
		$config['args'] = ct_ctr_query_args($config['args'], ct_is_theme('ct-mex'));

	return $config;
}
add_filter('ct_archive-news', 'ct_news_archive_filter', 10, 1);

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_news_add_meta_boxes' );
function ct_news_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'post':
		case 'page': 
			add_meta_box( 
		        'ct_news_details', 'Detalles', 'ct_news_meta_details',
		        $postType, 'side', 'core'
		    );
		    add_meta_box( 
		        'ct_news_sidebar', 'Información Lateral', 'ct_teacher_meta_sidebar',
		        $postType, 'normal'
		    );
			break;
	}
}

function ct_news_meta_details($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_news_noncename' );
	$details = get_post_meta($post->ID, 'ct-news-details', true);
?>
	<b>Autor</b><br />
	<input type="text" name="ct-news-details[author]" value="<?php echo $details['author']; ?>" />
<?php
}

add_action( 'save_post', 'ct_news_save_postdata' );
function ct_news_save_postdata( $post_id ) {
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_news_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_news_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	
	if (isset($_POST['ct-news-details'])) {
		update_post_meta($post_id, 'ct-news-details', $_POST['ct-news-details']);
	}		
	
}

/* API FUNCTIONS ***********************************************/

function ct_news_get_latest($limit = 5) {

	global $post;

	$args = array('post_type'=>'post',
				 'posts_per_page'=>$limit,
				 'order'=>'DESC',
				 'orderby'=>'date',
				 'category_name'=>'noticias');
	
	if ($post) {
		$args['post__not_in'] = array($post->ID);
	}
	
	if (!ct_is_theme('ct-mex')) {
		global $domainTheme;
		$args['tax_query'][] =  array(
							'taxonomy' => 'site',
							'field' => 'slug',
							'terms' => $domainTheme->stylesheet
							);
	}
	
	
	if (ct_is_theme('ct-centro') || ct_is_theme('ct-mex')) $args = ct_ctr_query_args($args, ct_is_theme('ct-mex'));
	
	$q = new WP_Query($args); 
	return $q->posts;
}

?>