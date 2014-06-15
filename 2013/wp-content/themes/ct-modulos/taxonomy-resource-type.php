<?php $GLOBALS['header'] = 'meditation'; 

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

?> 
<?php get_header();?>

	<div class="page_header">
		<h1><?php echo $term->name;?></h1>
		<?php dynamic_sidebar('ct_mod_header'); ?>
	</div>

	<?php if ($term->description) :?>
	<div class="page_header">
		<p class="intro"><?php echo $term->description;?></p>
	</div>
	<?php endif; ?>
	
	<div class="modules">
	<div class="main_col">
	<ul>
	<?php 
	$children = get_terms('resource-type', array('parent'=>$term->term_id,'orderby'=> 'name', 'order'=> 'ASC'));
	if ($children) :
	?>
	<?php foreach($children as $c) : ?>
	
	<li><a href="<?php echo get_term_link($c->slug, 'resource-type');?>"><?php echo $c->name;?></a></li>
	
	<?php endforeach; ?>
	
	<?php else : ?>
	
	<?php query_posts(array( 'post_type'=>'recurso', 'resource-type'=>$term->slug, 'order'=>'ASC', 'orderby'=>'title')); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
		<li><a href="<?php echo get_permalink($post->ID);?>"><?php echo $post->post_title;?></a></li>
	<?php endwhile; endif; ?>
	
	<?php endif; ?>
	
	</div></ul></div>

<?php get_footer();?>