<?php $GLOBALS['header'] = 'meditation'; ?> 
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

	<?php $intro = get_post_meta($post->ID, 'ct-mod-intro', true); 
	if ($intro) : ?>
	<div class="page_header" style="padding-bottom:10px;">
		<?php echo do_shortcode($intro);?>
	</div>
	<?php endif; ?>
	
	<div class="sidebar">
		<a class="btn large red left" href="<?php bloginfo('siteurl');?>/meditacion/modulos" style="width:85%;">Ver módulos</a>
		<?php
/*
		$q = new WP_Query(array('post_type'=>'page',
							    'post_parent'=>$post->ID,
							    'order'=>'ASC',
							    'orderby'=>'menu_order'));
		ct_sidebar_contents($q->posts);
*/
		?>
	</div>
	<div class="main_col">
		<?php the_content(); ?>
	</div>
	
	

<?php get_footer();?>
<?php endwhile; endif; ?>