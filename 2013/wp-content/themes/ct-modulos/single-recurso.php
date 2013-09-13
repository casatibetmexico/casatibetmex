<?php $GLOBALS['header'] = 'meditation'; ?> 
<?php get_header();?>

	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
	
	<div class="page_header">
		<h1><?php the_title();?></h1>
		<?php if ($_REQUEST['mod']) : ?>
		<div class="breadcrumbs">
		<a href="<?php echo get_permalink($_REQUEST['mod']); ?>">&lt; Regresar</a>
		</div>
		<?php else :?>
		<?php dynamic_sidebar('ct_mod_header'); ?>
		<?php endif; ?>
	</div>
	
	<div class="sidebar">
		<?php ct_sidebar_bibliography($post); ?>
	</div>
	<div class="main_col">
		<?php the_content(); ?>
	</div>
	
	<?php endwhile; endif; ?>
	
	

<?php get_footer();?>