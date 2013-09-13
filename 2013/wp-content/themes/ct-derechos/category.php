<?php get_header();

$term = get_term_by('slug', $category_name, 'category');
$categories = get_categories( array('parent'=>$term->term_id) );
$has_children = (count($categories) > 0);

$type = ($term->slug == 'noticias') ? 'news' : 'editorial';
$page = get_page_by_path('/derechos-humanos-2'); 

?>

<div class="col side">
	<?php if (!$has_children) ct_sidebar('calendar'); ?>
	<?php if ($has_children) : ?>
	<?php ct_sidebar('info', array('content'=>get_post_meta($page->ID, 'ct_sidebar', true))); ?>
	<?php endif;?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php echo $term->name; ?></h1>
		<?php if (!$has_children) : ?>
		<?php if ($term->description) : ?><p><?php echo $term->description; ?></p><?php endif; ?>
		<?php else : ?>
		<?php if ($intro = get_post_meta($page->ID, 'ct-mod-intro', true)) : ?>
		<p><?php echo $intro; ?></p>
		<?php endif; ?>
		<?php endif; ?>
		</div>
	</div>
	
	<?php if (!$has_children) : ?>
	
	<?php ct_sidebar('archive', array('limit'=>10, 
									  'post_type'=>$type,
									  'category'=>$category_name, 
									  'listing'=>'news-main')); ?>
	
	<?php else : ?>
	
	<div class="page_content">
		<?php echo apply_filters('the_content', $page->post_content); ?>
	</div>
	
	<?php 
	
	
	foreach($categories as $c) {
		ct_sidebar('archive', array('title'=>$c->name,
									'href'=>ct_get_permalink($c->term_id, 'category'),
									'limit'=>5, 
									  'post_type'=>$type,
									  'category'=>$c->slug, 
									  'listing'=>'news-main')); 
	}
	
	?>
	
	
	<?php endif; ?>

</div>

<?php get_footer();?>