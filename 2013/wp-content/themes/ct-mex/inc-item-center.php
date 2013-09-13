<?php 
	global $item;
	$info = get_post_meta($item->ID, 'ct_center_info', true); 
	
	$locs = unserialize(get_post_meta($item->ID, 'ct_center_locations', true)); 
	$primary_loc = get_post_meta($item->ID, 'ct_center_primary_loc', true);
	$loc = $locs[$primary_loc];
	
	$country = array_values(wp_get_post_terms($item->ID, 'country', array('fields'=>'all')));
	$country = $country[0]->slug;
	
	$c_type = array_values(wp_get_post_terms($item->ID, 'center-type', array('fields'=>'all')));
	
	$title = ($country != 'otra') ? ($c_type[0]->slug == 'sangha-sede')  
											? 'Casa Tibet : '.$item->post_title 
											: 'Grupo de Estudio : '.$item->post_title
								  : $item->post_title;
	
	
?>
<li>
	<div class="name"><?php echo $title; ?></div>
	<p>
	<?php if ($loc['address_1']) : ?>
	<?php echo $loc['address_1'].' '.$loc['address_2'].'<br />';?>
	<?php endif;?>			
	
	<?php if ($loc['tel']): ?>
		<?php echo (count($loc['tel']) > 1) ? '<b>TELS:</b> '.implode('&nbsp;&bull;&nbsp;', $loc['tel']).'<br />'
										    : '<b>TEL:</b> '.$loc['tel'][0].'<br />'; ?>
		
		
	<?php endif;?>
	<?php if ($loc['email']) : ?>
		<?php echo '<b>EMAIL:</b> <a href="mailto:'.$loc['email'].'">'.$loc['email'].'</a><br />'; ?>
	<?php endif; ?>
	<?php if ($info['website'] || $info['facebook'] || $info['twitter']) : ?>
		<?php echo '<b>SITIOS:</b> '; ?>
		<?php 
		$sitios = array();
		if ($info['website']) $sitios[] = '<a href="'.ct_get_external_link($info['website']).'" target="_blank">'.$info['website'].'</a>';
		if ($info['facebook']) $sitios[] = '<a href="'.ct_get_external_link($info['facebook']).'" target="_blank">Facebook</a>';
		if ($info['twitter']) $sitios[] = '<a href="'.ct_get_external_link($info['twitter']).'" target="_blank">Twitter</a>';
		echo implode('&nbsp;&nbsp;', $sitios);
		?>
	<?php endif; ?>
	</p>
</li>