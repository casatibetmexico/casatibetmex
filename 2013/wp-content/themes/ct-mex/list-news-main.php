<?php global $ct_list; ?>
<?php foreach((array) $ct_list as $item) : ?>
<?php 
	$meta = get_post_custom($e->ID); 
?>
	<li class="news_item" data-href="<?php echo ct_get_permalink($item->ID); ?>">
		<?php 
		$thumb = ct_get_thumbnail($item, 'main-thumb');
		if ($thumb) : ?><div class="thumbnail ct-image-fader"><?php  echo $thumb; ?></div><?php endif; ?>
		<?php if (ct_is_theme('ct-mex')) : ?>
		<div class="event_type"><?php echo ct_site_tag($item);?></div>
		<?php endif; ?>
		<h3><?php //echo ct_site_tag($item); ?>
		<?php echo $item->post_title; ?></h3>
		<p><?php echo ct_get_excerpt($item);?></p>
	</li>
<?php endforeach; ?>
<script type="text/javascript">
jQuery('.news_item .ct-image-fader').imageFader({fade:500, w:150, h:100});
</script>