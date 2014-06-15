<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header(); ?>

<?php 

//$module = $post;

?>

<div class="col side">
	<?php ct_sidebar('login'); ?>
	<?php echo do_shortcode('[ct-filter taxonomy="resource-type" base="/biblioteca/" /]'); ?>
	
	<div class="sidebar">
	
	<?php if ($biblio = get_post_meta($post->ID, 'res_bibliography', true)) : ?>
 	<div class="section">
		<div class="title"><?php _e('Bibliografía');?></div>
		<?php echo $biblio; ?>
	</div>
	<?php endif; ?>
	
	</div>
	
	<?php //if (function_exists("wpptopdf_display_icon")) echo wpptopdf_display_icon();?>
	
</div>
<div class="col main">
	<div class="page_header">
		<?php if ($_GET['mod']) {
					
			$parts = array();
			$parts[] = get_post($_GET['ref']);
			$parts[] = get_post($_GET['mod']);
			
			ct_breadcrumbs($parts, $post);
			
		} else {
			dynamic_sidebar('ct_page_nav');
		}
		 ?>
		<h1><?php echo str_replace(':', ':<br />', get_the_title($post->ID)); ?></h1>
		<?php if ($intro = get_post_meta($post->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php ct_sidebar('social-share'); ?>
		<?php echo apply_filters('the_content', $post->post_content); ?>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>