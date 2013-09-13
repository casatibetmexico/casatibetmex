<?php get_header();

$term = get_term_by('slug', $category_name, 'category');
?>

<div class="col side">
	<?php ct_sidebar('calendar'); ?>
</div>
<div class="col main">
	<div class="page_header cat">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php echo $term->name; ?></h1>
		</div>
	</div>
	<?php ct_sidebar('archive', array('limit'=>10, 'post_type'=>'news', 'listing'=>'news-main')); ?>
</div>

<?php get_footer();?>