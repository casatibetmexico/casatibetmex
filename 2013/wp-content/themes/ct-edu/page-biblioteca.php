<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('login'); ?>
	<?php ct_sidebar('search', array('title'=>'Buscar Recursos')); ?>
	<?php echo do_shortcode('[ct-filter taxonomy="resource-type" /]'); ?>
	<?php //ct_sidebar('resource-types', array('title'=>'Categorias', 'parent'=>0)); ?>
	<?php ct_sidebar('latest-resources', array('title'=>__('Lo más reciente'))); ?>
</div>
<div class="col main">

	
	
	<div class="page_header cat">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php _e('Biblioteca'); ?></h1>
		<?php if ($intro = get_post_meta($post->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
		</div>
	</div>
	
	<?php ct_sidebar('archive', array('limit'=>15, 'post_type'=>'resource', 'listing'=>'resource',
									  'resource-type'=>$_GET['resource-type'])); ?>
							      		      	
</div>

<?php get_footer();?>