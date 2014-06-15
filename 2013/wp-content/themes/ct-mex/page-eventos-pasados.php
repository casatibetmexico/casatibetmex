<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('calendar', array('show_upcoming'=>false)); ?>
</div>
<div class="col main">
	<?php ct_sidebar('archive', array('limit'=>10, 'order'=>'DESC', 'post_type'=>'event', 
									  'archive'=>true, 'listing'=>'event-main')); ?>
</div>

<?php get_footer();?>