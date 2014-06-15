<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$GLOBALS['use_validator'] = true;
$meta = get_post_custom($post->ID);
?>
<?php get_header();?>

<div class="col side">
	<?php if ($meta['ct_sidebar'][0]) :?>
	<div class="sidebar">
		<div class="title">Casa Tibet México</div>
		<?php echo do_shortcode($meta['ct_sidebar'][0]); ?>
	</div>
	<?php endif; ?>
</div>
<div class="col main">
	<?php the_content(); ?>
</div>

<?php get_footer();?>
<?php endwhile; endif; ?>