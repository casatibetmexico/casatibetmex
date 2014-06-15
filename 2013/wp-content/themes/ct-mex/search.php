<?php get_header(); ?>

<div class="col side">
	<?php ct_sidebar('search'); ?>
</div>
<div class="col main">
	<div class="page_header cat">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php printf(__('Búsqueda : "%s"'), $_GET['s']); ?></h1>
		</div>
	</div>
	<?php ct_sidebar('archive', array('limit'=>5, 'post_type'=>'search', 'listing'=>'search')); ?>
</div>

<?php get_footer();?>