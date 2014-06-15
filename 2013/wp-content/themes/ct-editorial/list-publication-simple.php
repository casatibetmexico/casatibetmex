<?php global $ct_list; ?>

<?php foreach($ct_list as $item) : ?>
	<li class="simple_item" data-href="<?php echo ct_get_permalink($item->ID); ?>">
		<div class="icon view right"></div>
		<?php echo $item->post_title; ?>
	</li>
<?php endforeach; ?>