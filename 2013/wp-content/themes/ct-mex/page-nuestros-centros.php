<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$meta = get_post_custom($post->ID);
?>

<?php get_header();?>

<div class="col side">
	
	<?php if ($meta['ct_sidebar'][0]) :?>
	<div class="sidebar">
		<?php echo do_shortcode($meta['ct_sidebar'][0]); ?>
	</div>
	<?php endif; ?>
</div>
<div class="col main">
	<div class="page_header" style="border-bottom:0px;">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php the_title(); ?></h1>
		<?php if ($meta['ct-mod-intro'][0]) : ?>
		<p><?php echo $meta['ct-mod-intro'][0]; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php ct_sidebar('archive', array('title'=>__('Directorio'), 
										  'country'=>($_REQUEST['country']) ? $_REQUEST['country'] : array('mexico','eua','guatemala'),
										  'center-type'=>($_REQUEST['center-type']) ? $_REQUEST['center-type'] : 'all',
										  'post_type'=>'center', 'nopaging'=>true, 'listing'=>'center')); ?>
		
		
		<!--
/*
<?php if ($sub_pages) :?>
		<div class="section">
		<ul class="listing page">
		<?php foreach($sub_pages as $p) :?>
			<?php if ($p->post_name == 'directorio') : ?>
			<?php foreach($types as $type) : ?>
				<li data-href="<?php echo add_query_arg('center-type', $type->slug, $root_url); ?>">
				<?php echo $type->name; ?></li>
			<?php endforeach; ?>
			<?php else : ?>
			<li data-href="<?php echo ct_get_permalink($p->ID); ?>"><?php echo $p->post_title; ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
		</div>
		<?php endif; ?>
*/
-->
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>