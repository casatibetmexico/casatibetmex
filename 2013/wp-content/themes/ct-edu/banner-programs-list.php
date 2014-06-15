<?php global $banners; ?>

<div class="section">
<ul class="listing cat">
<?php foreach((array) $banners as $banner) : ?>
	<li class="program" data-href="<?php echo get_post_meta($banner->ID, 'rm-banner-link', true);?>">
		<div class="thumbnail"><?php  echo ct_get_thumbnail($banner, array(205,200,true)); ?></div>
		<h2><?php echo $banner->post_title; ?></h2>
		<p><?php echo ct_get_excerpt(get_post_meta($banner->ID, 'rm-banner-caption', true), 45); ?></p>
		<div class="button_bar">
			<div class="btn red" data-href="<?php echo get_post_meta($banner->ID, 'rm-banner-link', true);?>"><div class="label"><?php _e('MÁS INFORMACIÓN'); ?></div></div>
		</div>
	</li>
<?php endforeach; ?>
</ul>
</div>