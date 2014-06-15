<?php global $ct_list; 
$list = array();
foreach((array) $ct_list as $e) {
	$month = date('Y-m', strtotime(get_post_meta($e->ID, 'ct_event_start', true)));
	$list[$month][] = $e;
}

?>
<?php foreach($list as $month=>$items) : ?>
	<li class="list_header"><?php echo date_i18n( 'F Y', strtotime($month) ); ?></li>
<?php foreach($items as $e) : ?>
<?php 
	$meta = get_post_custom($e->ID); 
	$event_type = array_pop(wp_get_post_terms( $e->ID, 'event-type' ));
	$teacher = get_post($meta['ct_teacher'][0]);
?>
	<li class="event_sidebar_item" data-href="<?php echo ct_get_permalink($e->ID); ?>">
		<?php 
		$thumb = ct_event_get_thumbnail($e, 'sidebar-thumb');
		if ($thumb) : ?><div class="thumbnail ct-image-fader"><?php  echo $thumb; ?></div><?php endif; ?>
		<div class="event_type"><?php echo ct_site_name($meta['ct_center'][0]);?></div>
		<h3><?php echo ct_get_excerpt($e->post_title, 9); ?></h3>
		<p class="teacher"><?php echo ct_event_get_period($e, 'start'); ?></p>

	</li>
<?php endforeach; ?>
<?php endforeach; ?>
<script type="text/javascript">
jQuery('.event_sidebar_item .ct-image-fader').imageFader({fade:500, w:70, h:70});
</script>