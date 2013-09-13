<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>
<div class="col side event">
	<?php $thumb = ct_get_thumbnail($post, array(315,500));
	if ($thumb) : ?><div class="thumbnail"><?php  echo $thumb; ?></div><?php endif; ?>
</div>
<div class="col main event">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="page_content">
		
		<?php if ($paypal = get_post_meta($post->ID, 'ct_paypal', true)) : 		
		echo do_shortcode('[pp_donate email="'.$paypal['email'].'" price="100.00" name="'.$paypal['concept'].'" return="'.ct_get_permalink($post->ID).'"/]');
		endif; ?>
		<?php the_content(); ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>