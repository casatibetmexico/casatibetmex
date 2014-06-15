<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$details = get_post_meta($post->ID, 'ct-news-details', true);
?>

<?php get_header();?>

<div class="col side sidebar">
	<?php if (get_post_thumbnail_id()) : ?>
		<div class="section">
			<?php echo ct_get_thumbnail($post); ?>
		</div>
		<div class="spacer"></div>
	<?php endif;?>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		
		<h1><?php if (has_term('extracto', 'post-cat')) :?>
		<div class="tag right"><?php _e('Extracto'); ?></div>
		<?php endif; ?>
		<?php echo str_replace(':', ':<br />', get_the_title()); ?></h1>
		<?php if ($intro = get_post_meta($post->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
		<?php if ($details['author']) : ?>
		<p><?php printf(__('por %s'), $details['author']); ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php ct_sidebar('social-share'); ?>
		<?php the_content(); ?>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>