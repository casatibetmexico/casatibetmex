<?php $GLOBALS['header'] = 'meditation'; ?> 
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

<div class="page_header">
	<h1><?php the_title();?></h1>
</div>
<div class="modules">

	<div class="main_col">

	<?php 
	
	$root_terms = get_terms('resource-type', array('orderby'=> 'name', 'order'=> 'ASC'));
	foreach($root_terms as $r) : 
	
	if ($r->parent == 0) :
	?>
	
	<a href="<?php echo get_term_link($r->slug, 'resource-type');?>"><h1><?php echo $r->name;?></h1></a>
	<p><?php echo $r->description; ?></p>
	<!--
/*
<?php 
	$children = get_terms('resource-type', array('parent'=>$r->term_id,'orderby'=> 'name', 'order'=> 'ASC'));
	?>
	<ul>
	<?php foreach($children as $c) : ?>
	<li><a href="<?php echo get_term_link($c->slug, 'resource-type');?>"><?php echo $c->name;?></a></li>
	<?php endforeach; ?>
	</ul>
*/
-->
	<?php endif; ?>
	
	<?php endforeach; ?>
	
	</div>
	
</div>

<?php get_footer();?>
<?php endwhile; endif; ?>