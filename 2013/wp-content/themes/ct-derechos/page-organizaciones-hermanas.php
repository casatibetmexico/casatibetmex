<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('donate-bank'); ?>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<div class="page_header cat">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php the_title(); ?></h1>
		<?php if ($intro = get_post_meta($post->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
		</div>
	</div>
	<div class="page_content orgs ">
		<?php the_content(); 
		ct_sidebar('archive', array('post_type'=>'org', 
									'listing'=>'org'));?>
	</div>
	
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>