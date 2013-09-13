<?php global $ct_list, $sbar_args; 

$countries = $sbar_args['country'];

if ($countries[0]=='all') {
	$countries = array();
	$c = get_terms('country', 'order=ASC');
	foreach($c as $term) {
		$countries[] = $term->slug;
	}
}

?>
<?php if ($countries && count($countries) > 1) : ?>
		
	<?php 
		$list = array();
		foreach($ct_list as $center) {
			$country = array_values(wp_get_post_terms($center->ID, 'country', array('fields'=>'all')));
			$key = $country[0]->slug;
			$list[$key][] = $center;
		}
	?>

	<?php foreach($countries as $country) : ?>
	<?php $c = get_term_by('slug', $country, 'country'); ?>
	
		<li class="list_header"><?php echo $c->name; ?></li>
	
	<?php foreach($list[$country] as $item) : ?>
		<?php $GLOBALS['item'] = $item;
		get_template_part('inc', 'item-center');?>
	<?php endforeach; ?>
	<?php endforeach; ?>
<?php else : ?>

	<?php foreach($ct_list as $item) : ?>
		<?php $GLOBALS['item'] = $item;
		get_template_part('inc', 'item-center');?>
	<?php endforeach; ?>

<?php endif; ?>