<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
//$meta = get_post_custom($post->ID);
//$locations = ct_ctr_get_locations();
print_r($locations);
?>

<?php get_header();?>
	
<div class="col side sidebar">

	<div class="section">
		<div class="title"><?php _e('Filtros'); ?></div>
		<form id="group-filters" method="GET">
			<?php if ($locations) : ?>
			<div class="filter">
			<b><?php _e('Ubicaciones:'); ?></b><br />
			<select id="filter-loc" name="loc" style="width:80%;">
				<option value="all">Todos</option>
				<?php foreach((array) $locations as $index=>$loc) : ?>
				<option value="<?php echo $index; ?>"><?php echo $loc['name']; ?></option>
				<?php endforeach; ?>
			</select>
			</div>
			<?php endif; ?>
			
			<div class="filter">
			<b><?php _e('Tipos de Cursos:'); ?></b><br />
			<select id="filter-course-type" name="course_type" style="width:80%;">
				<option value="all">Todos</option>
				<?php $item = ct_get_nav_item(get_permalink($post->ID)); ?>
				<?php foreach((array) $item['children'] as $elem) : ?>
				<?php if (strstr($elem['url'], 'course-type') > -1): ?>
				<?php $slug = explode('/', $elem['url']); ?>
				<option value="<?php echo $slug[4]; ?>"><?php echo $elem['label']; ?></option>
				<?php endif; ?>
				<?php endforeach; ?>
			</select>
			</div>
			
			<div class="filter">
			<b><?php _e('Nivel de Acceso:'); ?></b><br />
			<select id="filter-level" name="level" style="width:80%;">
				<option value="all">Todos</option>
				<option value="0"><?php _e('Abierto al Público'); ?></option>
				<option value="1"><?php _e('Alumnos Regulares'); ?></option>
			</select>
			</div>			
		</form>
		<script type="text/javascript">
			<?php if (isset($_REQUEST['loc'])) : ?>
				jQuery('#filter-loc').val('<?php echo $_REQUEST['loc']; ?>'); 
			<?php endif; ?>
			<?php if (isset($_REQUEST['course-type'])) : ?>
				jQuery('#filter-course-type').val('<?php echo $_REQUEST['course-type']; ?>'); 
			<?php endif; ?>
			<?php if (isset($_REQUEST['level'])) : ?>
				jQuery('#filter-level').val('<?php echo $_REQUEST['level']; ?>'); 
			<?php endif; ?>
			jQuery('#group-filters select').change(function() {
				jQuery('#group-filters').submit();
			});
		</script>
	</div>
	
	<?php ct_sidebar('info', array('content'=>$meta['ct_sidebar'][0])); ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<?php if (get_post_thumbnail_id()) : ?>
			<div class="thumbnail page">
				<div class="overlay"><h1><?php the_title(); ?></h1></div>
				<?php echo ct_get_thumbnail($post, array(635,350,true)); ?>
			</div>
		<?php else : ?>
		<h1><?php the_title(); ?></h1>
		<?php endif; ?>		
		<?php if ($meta['ct-mod-intro'][0]) : ?>
		<p><?php echo $meta['ct-mod-intro'][0]; ?></p>
		<?php endif; ?>
	</div>
	<div class="page_content">
		<?php the_content(); ?>
		
		<?php 
		$title = __('Programa de Estudios');
		/*
if ($_REQUEST['course-type'] || $_REQUEST['loc']) {
			
			$parts = array();
			
			if (isset($_REQUEST['loc']) && $_REQUEST['loc'] != 'all') {
				$l = $_REQUEST['loc'];
				$parts[] = $locations[$l]['name'];
			}
			
			if ($_REQUEST['course-type'] && $_REQUEST['course-type'] != 'all') {
				$term = get_term_by('slug', $_REQUEST['course-type'], 'course-type');
				$parts[] = $term->name;
			}
			
			if ($parts) $title = implode(' : ', $parts);
			
		}
*/
		?>
		<?php ct_sidebar('archive', array('title'=>$title, 
										  'course-type'=>($_REQUEST['course-type']) ? $_REQUEST['course-type'] : 'all',
										  'loc'=>(isset($_REQUEST['loc'])) ? $_REQUEST['loc'] : 'all',
										  'level'=>(isset($_REQUEST['level'])) ? $_REQUEST['level'] : 'all',
										  'locations'=>$locations,
										  'post_type'=>'study-group', 'nopaging'=>true, 'listing'=>'study-groups')); ?>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>