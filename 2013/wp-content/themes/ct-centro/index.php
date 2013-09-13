<?php 
$GLOBALS['use_feature'] = true;
$center = ct_get_current_center();
$info = get_post_meta($center, 'ct_center_info', true);
get_header();?>

<div class="col full">
	<?php get_banners('features', ct_query_args(array('slug'=>'destacados'))); ?>
</div>
<div class="col side">
	<?php ct_sidebar('login'); ?>
	<?php ct_sidebar('social-facebook', array('url'=>$info['facebook'])); ?>
	<?php ct_sidebar('social-twitter'); ?>
</div>
<div class="col main">
	<?php ct_sidebar('event-upcoming', array('limit'=>4, 'listing'=>'event-main')); ?>
	<?php ct_sidebar('archive', array('limit'=>5, 'title'=>__('Noticias'), 'href'=>ct_get_permalink('/noticias'), 
					 	'post_type'=>'news', 'listing'=>'news-main')); ?>
</div>

<?php get_footer();?>