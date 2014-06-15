<?php 
$GLOBALS['use_feature'] = true;
get_header();?>

<div class="col full">
	<?php get_banners('features', ct_query_args(array('slug'=>'destacados'))); ?>
</div>
<div class="col side">
	<?php ct_sidebar('search'); ?>
	<?php ct_sidebar('social-facebook'); ?>
	<?php ct_sidebar('social-twitter'); ?>
</div>
<div class="col main">
	<?php ct_sidebar('event-upcoming', array('limit'=>4, 'listing'=>'event-main', 'digest'=>true)); ?>
	<?php ct_sidebar('news', array('limit'=>4, 'title'=>'Noticias', 'listing'=>'news-main')); ?>

</div>

<?php get_footer();?>