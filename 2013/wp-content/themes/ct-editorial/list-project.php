<?php global $ct_list; ?>
<?php foreach((array) $ct_list as $item) : ?>
<?php 
	$meta = get_post_custom($e->ID); 
?>
	<li class="project" data-href="<?php echo ct_get_permalink($item->ID); ?>">
		<?php 
		$thumb = ct_get_thumbnail($item, array(150,150,true));
		if ($thumb) : ?><div class="thumbnail ct-image-fader"><?php  echo $thumb; ?></div><?php endif; ?>
		<h3><?php echo $item->post_title; ?></h3>
		<p><?php echo ct_get_excerpt($item, 60);?></p>
	</li>
<?php endforeach; ?>
<script type="text/javascript">
jQuery('.project .ct-image-fader').imageFader({fade:500, w:150, h:150});
</script>