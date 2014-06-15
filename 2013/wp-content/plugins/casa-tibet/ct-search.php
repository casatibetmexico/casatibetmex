<?php

/** FILTERS *************************************************************/

function ct_search_filter( $query ) {

   if ( !is_admin() && is_main_query() && $query->is_search()) {
    
      $query->set('posts_per_page', 10);
      $query->set('paged', $_GET['pg']);
      
      $query->set('post__not_in', array(ct_get_current_center()));
            
      if (ct_is_theme('ct-centro')) {
      	$mq = ($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : array();
      	$mq[] = array('key'=>'ct_center', 
      				  'value'=>ct_get_current_center(),
      				  'compare'=>'=');
      	$query->set('meta_query', $mq);
      }
        
  }
  
  return $query;
}
add_action( 'pre_get_posts', 'ct_search_filter', 10, 1);

function ct_search_archive_filter($args) {

	global $wp_query;
		
	$config = array();
	$config['query'] = $wp_query;
	$config['no_results'] = sprintf(__('Actualmente no se encuentra ningun resultado para los terminos "%s".'),
									$_GET['s']);
	$config['title'] = __('Resultados');
	

	return $config;
}
add_filter('ct_archive-search', 'ct_search_archive_filter', 10, 1);

?>