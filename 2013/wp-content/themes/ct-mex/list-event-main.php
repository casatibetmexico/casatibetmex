<?php global $ct_list; 
$list = array();
foreach((array) $ct_list as $e) {
	$month = date('Y-m', strtotime(get_post_meta($e->ID, 'ct_event_start', true)));
	$list[$month][] = $e;
}

?>
<?php foreach($list as $month=>$items) : ?>
	<?php if (!$_GET['date']) : ?>
	<li class="list_header"><?php echo date_i18n( 'F Y', strtotime($month) ); ?></li>
	<?php endif; ?>
<?php foreach($items as $e) : ?>
<?php 
	$meta = get_post_custom($e->ID); 
	$event_type = array_pop(wp_get_post_terms( $e->ID, 'event-type' ));
	$teacher = get_post($meta['ct_teacher'][0]);
?>
	<li class="event_item" data-href="<?php echo ct_get_permalink($e->ID); ?>">
		<?php 
		$thumb = ct_event_get_thumbnail($e, 'main-thumb');
		if ($thumb) : ?><div class="thumbnail ct-image-fader"><?php  echo $thumb; ?></div><?php endif; ?>
		<?php if (ct_is_theme('ct-mex')) : ?>
		<div class="event_type"><?php echo ct_site_tag($e);?></div>
		<?php endif; ?>
		<h3><?php echo $e->post_title; ?></h3>
		<p><?php echo ct_event_get_period($e); ?></p>
		<?php if ($meta['ct_teacher'][0] > 0) : ?>
		<p class="teacher">
		Impartido por: <?php echo $teacher->post_title; ?></p>
		<?php endif;?>
	</li>
<?php endforeach; ?>
<?php endforeach; ?>
<script type="text/javascript">
jQuery('.event_item .ct-image-fader').imageFader({fade:500, w:150, h:100});
</script>