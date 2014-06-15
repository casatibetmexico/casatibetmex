<?php global $ct_list; ?>
<?php foreach((array) $ct_list as $item) : ?>
	<li data-href="<?php echo ct_get_permalink($item->ID); ?>">
		<div class="icon view"></div>
		<h2><?php echo $item->post_title; ?></h2>
		<p><?php echo ct_event_get_period($item); ?></p>
	</li>
<?php endforeach; ?>