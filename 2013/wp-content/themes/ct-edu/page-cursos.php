<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('login'); ?>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<?php get_template_part('inc', 'page-header'); ?>
	<div class="page_content">
		<?php get_banners('programs-list', ct_query_args(array('slug'=>'programas'))); ?>
	</div>
</div>

<?php get_footer();?>
<?php endwhile; endif; ?>