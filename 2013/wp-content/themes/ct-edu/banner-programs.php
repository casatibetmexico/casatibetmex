<?php global $banners; ?>
<table class="programs"><tr>
<?php foreach((array) $banners as $banner) : ?>
	<td align="center">
	<a href="<?php echo get_post_meta($banner->ID, 'rm-banner-link', true);?>"><div class="thumbnail"><?php  echo ct_get_thumbnail($banner, array(205,200,true)); ?></div></a>
	<h2><?php echo $banner->post_title; ?></h2>
	<p><?php echo ct_get_excerpt(get_post_meta($banner->ID, 'rm-banner-caption', true)); ?></p>
	</td>
<?php endforeach; ?>
</tr><tr>
<?php foreach((array) $banners as $banner) : ?>
	<td class="banners">
	<div class="btn red" data-href="<?php echo get_post_meta($banner->ID, 'rm-banner-link', true);?>"><div class="label">MÁS INFORMACIÓN</div></div>
	</td>
<?php endforeach; ?>
</tr></table>

