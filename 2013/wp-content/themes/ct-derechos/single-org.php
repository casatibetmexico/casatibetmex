<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>
<div class="col side sidebar">
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main event org">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<?php $thumb = ct_get_thumbnail($post, array(200,200));
		if ($thumb) : ?><div class="thumb"><?php  echo $thumb; ?></div><?php endif; ?>
		<h1><?php the_title(); ?></h1>
		<?php if ($intro = get_post_meta($post->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
		</div>
	</div>
	<div class="page_content">
		<?php ct_sidebar('social-share'); ?>
		<?php the_content(); ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>