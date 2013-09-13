<?php global $ct_list; ?>
<?php foreach($ct_list as $e) : ?>
	<li class="news_sidebar_item" data-href="<?php echo get_permalink($e->ID); ?>">
		<?php 
		$thumb = ct_event_get_thumbnail($e, 'sidebar-thumb');
		if ($thumb) : ?><div class="thumbnail ct-image-fader"><?php  echo $thumb; ?></div><?php endif; ?>
		<h3><?php echo $e->post_title; ?></h3>
		<p><?php echo ct_get_excerpt($e, 10);?></p>
	</li>
<?php endforeach; ?>
<script type="text/javascript">
jQuery('.news_sidebar_item .ct-image-fader').imageFader({fade:500, w:70, h:70});
</script>