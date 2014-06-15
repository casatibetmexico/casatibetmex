<?php 
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>
<div class="col side">
	<?php if (get_post_thumbnail_id()) : ?>
		<?php echo ct_get_thumbnail($post, 'teacher-profile'); ?>
	<?php endif; ?>
	<div class="spacer"></div>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="page_content">
		<?php ct_sidebar('social-share'); ?>
		<?php the_content(); ?>
		
		<?php $events = ct_teacher_get_events($post->ID); ?>
		<?php if ($events) : ?>
		<div class="section" style="margin-top:25px;">
			<div class="title"><?php _e('Cursos Impartido'); ?></div>
			<ul class="listing teacher-listing">
			<?php ct_listing('teacher-events', $events); ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>