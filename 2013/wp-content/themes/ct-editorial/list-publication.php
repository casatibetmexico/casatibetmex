<?php global $sbar_args, $ct_list; 

if ($sbar_args['slug']) {
	$type = get_term_by('slug', $sbar_args['slug'], 'publication-type');
}

?>

<?php if ($type && !$sbar_args['no_header']) : ?><li class="list_header"><?php echo $type->name; ?></li><?php endif; ?>
<?php foreach($ct_list as $item) : ?>
<?php 
	$subtitle = get_post_meta($item->ID, 'ct_pub_subtitle', true);
	$details = get_post_meta($item->ID, 'ct_pub_details', true);
	$pub_date = get_post_meta($item->ID, 'ct_pub_publish_date', true);
?>
	<li class="publication" data-href="<?php echo get_permalink($item->ID); ?>">
		<?php 
		$thumb = ct_get_thumbnail($item, array(100,125));
		if ($thumb) : ?><div class="thumbnail ct-image-fader"><?php  echo $thumb; ?></div><?php endif; ?>
		<h2><?php echo $item->post_title; ?></h2>
		<?php if ($subtitle) : ?><h4 class="serif-italic"><?php echo $subtitle; ?></h4><?php endif; ?>
		
		
		
		<?php if ($details['author']) printf(__('<p class="author">por %s (%s)</p>'), 
									  $details['author'], 
									  ucfirst(date_i18n('F Y', strtotime($pub_date)))); ?>
		<p class="details">
		<?php if ($details['cost']):?>
			<?php printf(__('<span class="price">$%s MXN</span><br />'), $details['cost']); ?>
			<?php if ($details['shipping']) printf('<span class="shipping">(+ %s MXN DE ENVIO)</span>', $details['shipping']); ?>
		<?php else : ?>
			<?php printf(__('<span class="price">%s</span><br />'), __('GRATIS')); ?>
		<?php endif; ?>
		</p>
		
	</li>
<?php endforeach; ?>

<script type="text/javascript">
jQuery('.publication .ct-image-fader').imageFader({fade:500, w:100, h:125});
</script>