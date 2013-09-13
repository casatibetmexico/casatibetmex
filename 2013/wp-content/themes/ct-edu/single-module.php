<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header(); ?>

<?php 

$module = $post;

?>

<div class="col side">
	<?php ct_sidebar('login'); ?>
	
	<div class="sidebar">
	<?php echo ct_mod_modules_shortcode(array('title'=>'Sesiones', 
											  'id'=>$module->ID, 
											  'ref'=>$_GET['ref'],
											  'children'=>true)); ?>
											  
	<?php ct_sidebar('resources', array('post'=>$module)); ?>
	
	<?php if ($biblio = get_post_meta($module->ID, 'mod_bibliography', true)) : ?>
 	<div class="section">
		<div class="title"><?php _e('Referencias');?></div>
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
	
</div>
<div class="col main">
	<div class="page_header">
		<?php if ($_GET['ref']) {

					
			$parts = array('home');
			
			$q = new WP_Query('post_type=page&name=cursos');
			$parts[] = $q->post;
			$parts[] = get_post($_GET['ref']);
						
			$parents = array();
			ct_mod_get_parents($module, $parents, get_post_meta($_GET['ref'], 'ct_course_materials', true));
			if ($parents) {
				$parents = array_reverse($parents);
				foreach($parents as $p) {
					$parts[] = $p;
				}
			}
			
			ct_breadcrumbs($parts, $module);
			
			
		} else {
			dynamic_sidebar('ct_page_nav');
		}
		 ?>
		<h1><?php echo get_the_title($module->ID); ?></h1>
		<?php if ($intro = get_post_meta($module->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php echo apply_filters('the_content', $module->post_content); ?>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>