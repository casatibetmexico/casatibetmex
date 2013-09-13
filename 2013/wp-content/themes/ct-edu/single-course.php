<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header(); ?>

<?php 

$m_id = get_post_meta($post->ID, 'ct_course_materials', true);
if ($m_id) {
	$module = get_post($m_id);
}

?>

<div class="col side">
	<?php ct_sidebar('login'); ?>
	<?php if ($module) : ?>
	
	<div class="sidebar">
	
	<?php echo ct_mod_modules_shortcode(array('title'=>'Sesiones', 
											  'id'=>$module->ID, 
											  'ref'=>$post->ID,
											  'children'=>true)); ?>
	
	<?php if ($biblio = get_post_meta($module->ID, 'mod_bibliography', true)) : ?>
 	<div class="section biblio">
		<div class="title"><?php _e('Bibliografía');?></div>
		<?php echo $biblio; ?>
	</div>
	<?php endif; ?>
	
	</div>
	<script type="text/javascript">
		jQuery('ul.numbered li').each(function(index) {
			var html = jQuery(this).html();
			var num = ct.pad(index+1, 2);
			jQuery(this).html(num+'. '+html);
		});
	</script>

	
	<?php endif; ?>
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
		<h1><?php echo str_replace(':', ':<br />', get_the_title()); ?></h1>
		<?php endif; ?>		
		<?php if ($meta['ct-mod-intro'][0]) : ?>
		<p><?php echo $meta['ct-mod-intro'][0]; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php 
		if ($module) {
			echo apply_filters('the_content', $module->post_content);
		} else {
			the_content();
		}?>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>