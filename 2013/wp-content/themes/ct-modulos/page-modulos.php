<?php $GLOBALS['header'] = 'meditation'; ?> 
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

<?php
$modules = ct_get_module_index();
?>
<div class="page_header">
	<h1><?php the_title();?></h1>
	<?php dynamic_sidebar('ct_mod_header'); ?>
</div>
<div class="modules">
<?php for($i=0; $i<count($modules); $i++): 
	$mod = $modules[$i]['mod'];
	$children = $modules[$i]['children'];
?>
	<a href="<?php echo get_permalink($mod->ID);?>"><h1><?php echo $mod->post_title;?></h1></a>
	<p><?php echo $mod->post_excerpt; ?></p>
	<?php if (count($children) > 0):?>
	<ul>
	<?php for($j=0; $j<count($children); $j++): 
	$m = $children[$j]['mod']; 
	$c = $children[$j]['children']; ?>
	<li><a href="<?php echo get_permalink($m->ID);?>"><?php echo $m->post_title;?></a>
	<?php if ($children[$j]['children']) : ?>
		<p class="sub_sections">
			<?php for($k=0; $k<count($c); $k++): 
				 $m2 = $c[$k]['mod']; ?>
				 <a href="<?php echo get_permalink($m2->ID);?>"><?php echo $m2->post_title;?></a><br />
			<?php endfor; ?>
		</p>
	<?php endif; ?>
	</li>
	<?php endfor; ?>
	</ul>
	<?php endif;?>
	
<?php endfor; ?>
</div>

<?php get_footer();?>
<?php endwhile; endif; ?>