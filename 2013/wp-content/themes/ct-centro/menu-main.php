<?php global $ob; 

foreach($ob as $key=>$val) {
	if (strstr($val['url'], '/nosotros/')) {
	
		$page = get_page_by_path('/nosotros');
		
		$args = array('post_type'=>'page',
					  'post_parent'=>$page->ID,
					  'nopaging'=>true,
					  'order'=>'ASC',
					  'orderby'=>'menu_order',
					  'meta_key'=>'ct_page_show_menu',
					  'meta_value'=>1);
		$q = new WP_Query($args);
		foreach((array) $q->posts as $item) {
			$ob[$key]['children'][] = array('label'=>$item->post_title, 'url'=>ct_get_permalink($item->ID));
		}
	} else if (strstr($val['url'], '/educacion/')) {
		
		$page = get_page_by_path('/educacion');
		
		$args = array('post_type'=>'page',
					  'post_parent'=>$page->ID,
					  'nopaging'=>true,
					  'order'=>'ASC',
					  'orderby'=>'menu_order',
					  'meta_key'=>'ct_page_show_menu',
					  'meta_value'=>1);
		$q = new WP_Query($args);
		foreach((array) $q->posts as $item) {
			$ob[$key]['children'][] = array('label'=>$item->post_title, 'url'=>ct_get_permalink($item->ID));
		}
		
		
	}
}
?>
<ul id="ct_nav_menu"></ul>
<script type="text/javascript">jQuery("#ct_nav_menu").navMenu(<?php echo json_encode($ob); ?>);</script>
