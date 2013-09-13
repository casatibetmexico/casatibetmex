<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php 
	$meta = get_post_custom($post->ID); 
	$types = wp_get_post_terms( $post->ID, 'event-type' );
	$location = get_post_meta($post->ID, 'ct_event_location', true);
?>
<?php get_header();?>
<div class="col side event sidebar">

	<?php if ($location['map']) : ?>
	<div class="section">
		<div class="title">Mapa</div>
		<?php echo do_shortcode('[ct-embed type="gmap" width="315" height="330"]'.$location['map'].'[/ct-embed]'); ?>
	</div>
	<?php endif; ?>

	<?php ct_sidebar('event-upcoming', array('listing'=>'event-sidebar')); ?>
</div>
<div class="col main event">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		
		<div class="head_wrapper">
		<?php 
		$thumb = ct_event_get_thumbnail($post, 'event-thumb');
		if ($thumb) : ?>
			<div class="thumbnail"><?php  echo $thumb; ?></div>
		<?php endif; ?>
		<div class="event_type">
			<?php echo ct_site_tag($post); ?>
			<?php foreach((array) $types as $t) : ?>
			<?php echo ct_tag($t->name, get_term_link($t->slug, 'event-type')); ?> 
			<?php endforeach; ?>
		</div>
		<h1><?php the_title(); ?></h1>
		<?php if ($meta['ct_teacher'][0]) : ?>
		<?php $teacher = get_post($meta['ct_teacher'][0]); ?>
		<p class="teacher"><b>Impartido por:</b><br />
		<a href="<?php echo get_permalink($teacher->ID);?>">
		<span class="serif-italic"><?php echo $teacher->post_title; ?></span>
		</a></p>
		<?php endif;?>
		</div>
	</div>
	<div class="page_content">
		<?php ct_sidebar('event-details'); ?>
		<?php ct_sidebar('social-share'); ?>
		<?php the_content(); ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>