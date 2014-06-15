<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header(); ?>

<?php 

$m_id = get_post_meta($post->ID, 'ct_course_materials', true);
if ($m_id) {
	$module = get_post($m_id);
}

$meta = get_post_meta($post->ID);

?>

<div class="col side">
	
	
	<div class="section">
		<div class="title"><?php _e('Contenidos'); ?></div>
		
		<ul class="listing pages">
			<li class="ui simple_item" data-href="javascript:loadContent('intro');">
				<div class="icon view"></div><?php _e('Introducción y Estructura'); ?></li>
				
			<?php if ($meta['ct_course_objectives'][0]) :?>
			<li class="ui" data-href="javascript:loadContent('objectives');">
				<div class="icon view"></div><?php _e('Objetivos'); ?></li>
			<?php endif;?>
			
			<?php if ($meta['ct_course_sessions'][0]) :?>
			<li class="ui" data-href="javascript:loadContent('sessions');">
				<div class="icon view"></div><?php _e('Guia de Sesiones'); ?></li>
			<?php endif;?>
			
			<?php if ($meta['ct_course_meditations'][0]) :?>
			<li class="ui" data-href="javascript:loadContent('meditations');">
				<div class="icon view"></div><?php _e('Meditaciones'); ?></li>
			<?php endif;?>
			
			<?php if ($meta['ct_course_practice'][0]) :?>
			<li class="ui" data-href="javascript:loadContent('practice');">	
				<div class="icon view"></div><?php _e('Para la Vida Cotidiana'); ?></li>
			<?php endif;?>
			
			<?php if ($meta['ct_course_resources'][0]) :?>
			<li class="ui" data-href="javascript:loadContent('resources');">	
				<div class="icon view"></div><?php _e('Recursos'); ?></li>
			<?php endif;?>
			
			<?php if ($meta['ct_course_biblio'][0]) :?>
			<li class="ui" data-href="javascript:loadContent('biblio');">
				<div class="icon view"></div><?php _e('Bibliografía'); ?></li>
			<?php endif;?>
		</ul>
		
	</div>
	
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
	
		<div id="content-intro" class="content-section">
			<?php if ($module) : ?>
			<?php echo apply_filters('the_content', $module->post_content); ?>
			<?php else : ?>
			<?php the_content(); ?>
			<?php endif; ?> 
		</div>
		
		<?php if ($meta['ct_course_objectives'][0]) :?>
		<div id="content-objectives" class="content-section" style="display:none;">
			<?php echo apply_filters('the_content', $meta['ct_course_objectives'][0]); ?> 
		</div>
		<?php endif;?>
		
		<?php if ($meta['ct_course_sessions'][0]) :?>
		<div id="content-sessions" class="content-section" style="display:none;">
			<?php echo apply_filters('the_content', $meta['ct_course_sessions'][0]); ?> 
		</div>
		<?php endif;?>
		
		<?php if ($meta['ct_course_meditations'][0]) :?>
		<div id="content-meditations" class="content-section" style="display:none;">
			<?php echo apply_filters('the_content', $meta['ct_course_meditations'][0]); ?> 
		</div>
		<?php endif;?>
		
		<?php if ($meta['ct_course_practice'][0]) :?>
		<div id="content-practice" class="content-section" style="display:none;">
			<?php echo apply_filters('the_content', $meta['ct_course_practice'][0]); ?> 
		</div>
		<?php endif;?>
		
		<?php if ($meta['ct_course_resources'][0]) :?>
		<div id="content-resources" class="content-section" style="display:none;">
			<?php echo apply_filters('the_content', $meta['ct_course_resources'][0]); ?> 
		</div>
		<?php endif;?>
		
		<?php if ($meta['ct_course_biblio'][0]) :?>
		<div id="content-biblio" class="content-section" style="display:none;">
			<?php echo apply_filters('the_content', $meta['ct_course_biblio'][0]); ?> 
		</div>
		<?php endif;?>
		
	</div>
</div>

<script type="text/javascript">
	function loadContent(id) {
		jQuery('.content-section:visible').hide();
		jQuery('#content-'+id).show();
	}
</script>
<?php get_footer();?>
<?php endwhile; endif; ?>