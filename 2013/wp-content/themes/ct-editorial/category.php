<?php get_header();

$term = get_term_by('slug', $category_name, 'category');
$categories = get_categories( array('parent'=>$term->term_id) );
$has_children = (count($categories) > 0);

$type = ($term->slug == 'noticias') ? 'news' : 'editorial';

?>

<div class="col side">
	<?php if (!$has_children) ct_sidebar('calendar'); ?>
	<?php if ($has_children) ct_sidebar('donate-recurring'); ?>
</div>
<div class="col main">
	<div class="page_header cat">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php echo $term->name; ?></h1>
		<?php if ($term->description) : ?><p><?php echo $term->description; ?></p><?php endif; ?>
		</div>
	</div>
	
	<?php if (!$has_children) : ?>
	
	<?php ct_sidebar('archive', array('limit'=>10, 
									  'post_type'=>$type,
									  'category'=>$category_name, 
									  'listing'=>'news-main')); ?>
	
	<?php else : ?>
	
	<?php 
	
	
	foreach($categories as $c) {
		ct_sidebar('archive', array('title'=>$c->name,
									'href'=>ct_get_permalink($c->term_id, 'category'),
									'limit'=>3, 
									  'post_type'=>$type,
									  'category'=>$c->slug, 
									  'listing'=>'news-main')); 
	}
	
	?>
	
	
	<?php endif; ?>

</div>

<?php get_footer();?>