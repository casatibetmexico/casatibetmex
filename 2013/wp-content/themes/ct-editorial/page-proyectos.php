<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$meta = get_post_custom($post->ID);
?>

<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('donate-recurring'); ?>
	<?php ct_sidebar('info', array('content'=>$meta['ct_sidebar'][0])); ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<?php if (get_post_thumbnail_id()) : ?>
			<div class="thumbnail page">
				<div class="overlay"><h1><?php the_title(); ?></h1></div>
				<?php echo ct_get_thumbnail($post, array(635,350,true)); ?>
			</div>
		<?php else : ?>
		<h1><?php the_title(); ?></h1>
		<?php endif; ?>		
		<?php if ($meta['ct-mod-intro'][0]) : ?>
		<p><?php echo $meta['ct-mod-intro'][0]; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php the_content(); ?>

		<?php 
		$args = array('post_type'=>'project', 'listing'=>'project');
		ct_sidebar('archive', $args); ?>

	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>