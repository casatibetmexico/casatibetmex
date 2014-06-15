<?php global $ct_list; ?>
<?php foreach((array) $ct_list as $item) : ?>
	<li class="search_item" data-href="<?php echo ct_get_permalink($item->ID); ?>">
		<div class="event_type"><?php echo ct_site_tag($item);?></div>
		<h3><?php echo $item->post_title; ?></h3>
		<p><?php echo ct_get_excerpt($item);?></p>
	</li>
<?php endforeach; ?>