<?php 
global $wp_query, $post;

$parent = get_post($post->post_parent);

$args['post_type'] = 'teacher';
$args['name'] = 'marco-antonio-karam';

query_posts($args);

?>

<?php 
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>
<div class="col side">
	<?php if (get_post_thumbnail_id()) : ?>
		<?php echo ct_get_thumbnail($post, 'teacher-profile'); ?>
	<?php endif; ?>
	<div class="spacer"></div>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php 
		$parts = array('home', $parent);
		ct_breadcrumbs($parts, $post);
		?>
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="page_content">
		<?php the_content(); ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>
