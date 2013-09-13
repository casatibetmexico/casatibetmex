<?php global $ct_list; ?>
<?php foreach((array) $ct_list as $item) : ?>
	<li data-href="<?php echo ct_get_permalink($item->ID); ?>">
		<div class="icon view"></div><?php echo $item->post_title; ?>
	</li>
<?php endforeach; ?>