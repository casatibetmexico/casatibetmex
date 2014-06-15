<?php global $ct_list, $sbar_args; 

$items = array();

foreach((array) $ct_list as $i) {
	$meta = get_metadata('post', $i->ID);
	$loc = $meta['ct_group_location'][0];
	$day = $meta['ct_group_weekday'][0];
	$items[$loc][$day][] = $i;
}	

?>


<?php //if ($sbar_args['loc'] == 'all') : ?>
<?php foreach((array) $sbar_args['locations'] as $loc=>$info) : ?>

<?php $GLOBALS['program'] = $items[$loc]; 
$GLOBALS['info'] = $info; ?>
<?php if ($GLOBALS['program']) : ?>

<?php get_template_part('inc', 'study-program'); ?>
	
<?php endif; ?>

<?php endforeach; ?>
<?php //endif; ?>