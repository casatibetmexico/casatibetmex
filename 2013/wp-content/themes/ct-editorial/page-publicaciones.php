<?php get_header();?>

<div class="col side">
	<?php ct_sidebar('donate-recurring'); ?>
</div>
<div class="col main">

	<?php if ($_GET['publication-type']) : ?>
	
	<?php 
	$term = get_term_by('slug', $_GET['publication-type'], 'publication-type');
	?>
	
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php echo $term->name; ?></h1>
		</div>
	</div>
	
	<?php 
	
	ct_sidebar('archive', array('no_title'=>true,
							      'post_type'=>'publication', 
							      'listing'=>'publication',
							      'slug'=>$term->slug,
							      'no_header'=>true)); 
	?>
	
	<?php else : ?>
	
	<div class="page_header cat">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<div class="head_wrapper">
		<h1><?php _e('Nuestros Publicaciones'); ?></h1>
		</div>
	</div>
	
	
	<?php 
	
	$types = array('libros', 'clasicos-de-bolsillo');
	for($i=0; $i<count($types); $i++) {
		$args = array('no_title'=>true,
					  'post_type'=>'publication', 
				      'listing'=>'publication',
				      'slug'=>$types[$i]);
		ct_sidebar('archive', $args);
	} 	
	?>
							      	
	<?php endif; ?>			      	
</div>

<?php get_footer();?>