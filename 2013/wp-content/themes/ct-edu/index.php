<?php 
$GLOBALS['use_feature'] = true;
get_header();?>
<div class="col full">
	<?php get_banners('features', ct_query_args(array('slug'=>'destacados'))); ?>
</div>
<div class="col side">
	<?php ct_sidebar('login'); ?>
	<div class="sidebar">
	<?php echo do_shortcode('[ct-courses program="programa-contemplativo" /]'); ?>
	<?php ct_sidebar('latest-resources'); ?>
	<?php /* ct_sidebar('archive', array('limit'=>5, 'title'=>__('Noticias'), 'href'=>ct_get_permalink('/noticias'), 
					 	'post_type'=>'news', 'listing'=>'news-sidebar')); */ ?>
	</div>
</div>
<div class="col main">
	<div class="section" data-href="<?php echo ct_get_permalink('/cursos'); ?>">
		<div class="title"><?php _e('Nuestros Programas') ?></div>
		<?php get_banners('programs', ct_query_args(array('slug'=>'programas'))); ?>
	</div>
	<?php ct_sidebar('archive', array('limit'=>5, 'title'=>__('Noticias'), 'href'=>ct_get_permalink('/noticias'), 
					 	'post_type'=>'news', 'listing'=>'news-main')); ?>
	<?php ct_sidebar('latest-activity'); ?>
</div>

<?php get_footer();?>