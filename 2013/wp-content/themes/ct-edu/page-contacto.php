<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$GLOBALS['use_validator'] = true;
$meta = get_post_custom($post->ID);
?>
<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<?php echo do_shortcode('[ct-form to="educacion@casatibet.org.mx" /]'); ?>
	<div class="spacer"></div>
</div>

<?php get_footer();?>
<?php endwhile; endif; ?>