<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('calendar', array('show_upcoming'=>true)); ?>
</div>
<div class="col main">
	<?php ct_sidebar('archive', array('limit'=>10, 'post_type'=>'event', 'listing'=>'event-main')); ?>
</div>

<?php get_footer();?>