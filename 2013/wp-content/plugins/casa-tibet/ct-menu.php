<?php
class CT_Menu_Walker extends Walker_Nav_Menu {

	var $first_run = false;

    function start_el(&$output, $item, $depth, $args) {
    
    	if (!$this->first_run) {
    		$this->first_run = true;
    	} else {
    		$output .= '<li class="separator"></li>';
    	}
    	$output .= '<li data-href="'.$item->url.'">'.$item->title.'</li>';
		
   }
}
?>