<?php global $sbar_args, $ct_list; ?>

<?php foreach($ct_list as $item) : ?>
<?php
	$terms = array_values(wp_get_post_terms($item->ID, 'resource-type'));
?>
	<li class="simple_item resource" data-href="<?php echo ct_get_permalink($item->ID);?>">
		<div class="icon view"></div>
		<!-- <div class="tag"><?php echo $terms[0]->name; ?></div> -->
		<label><?php echo $item->post_title;?></label>
	</li>
<?php endforeach; ?>