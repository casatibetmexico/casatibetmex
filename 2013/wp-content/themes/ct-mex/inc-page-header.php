<?php if ($post) : ?>
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<?php if (get_post_thumbnail_id()) : ?>
			<div class="thumbnail page">
				<div class="overlay"><h1><?php the_title(); ?></h1></div>
				<?php echo ct_get_thumbnail($post, array(635,350,true)); ?>
			</div>
		<?php else : ?>
		<h1><?php the_title(); ?></h1>
		<?php endif; ?>		
		<?php if ($intro = get_post_meta($post->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
	</div>
<?php endif; ?>