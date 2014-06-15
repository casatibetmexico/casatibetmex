<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$meta = get_post_custom($post->ID);

$center_type = get_term_by('slug', $_GET['center-type'], 'center-type');

if (!$_GET['country']) {
	switch($_GET['center-type']) {
		case 'sangha-sede': 
			$_GET['country'] = 'mexico,guatemala,eua';
			break;
	}
}

?>

<?php get_header();?>

<div class="col side">
	<div class="placeholder share"><div>SHARE PAGE</div></div>
	<div class="placeholder events"><div>PÁGINAS RELACIONADOS</div></div>
	
	<?php if ($meta['ct_sidebar'][0]) :?>
	<div class="sidebar">
		<?php echo do_shortcode($meta['ct_sidebar'][0]); ?>
	</div>
	<?php endif; ?>
</div>
<div class="col main">
	<div class="page_header" style="border-bottom:0px;">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php echo ($center_type) ? $center_type->name : the_title(); ?></h1>
		<?php if ($meta['ct-mod-intro'][0]) : ?>
		<p><?php echo $meta['ct-mod-intro'][0]; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php 
		$args = array('no_title'=>true,
					  'post_type'=>'center', 
					  'center-type'=>$_GET['center-type'],
					  'listing'=>'center');
		if ($_GET['country']) $args['country'] = explode(',', $_GET['country']);
		ct_sidebar('archive', $args); ?>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>