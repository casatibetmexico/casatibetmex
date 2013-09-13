<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$GLOBALS['use_validator'] = true;
$meta = get_post_custom($post->ID);

$center = ct_get_current_center(true);
$locations = unserialize(get_post_meta($center->ID, 'ct_center_locations', true));
$primary = array_splice($locations, get_post_meta($center->ID, 'ct_center_primary_loc', true), 1);

$subject = sprintf(__('Forma de Contacto : %s'), $center->post_title);
?>

<?php function ct_print_location($loc, $name=false) { ?>


	<?php if ($loc['map']) :?>
	<?php echo do_shortcode('[ct-embed type="gmap" width="315" height="300"]'.$loc['map'].'[/ct-embed]'); ?>
	<?php endif; ?>
	
	<?php if ($name) : ?>
	<h2><?php echo $loc['name'];?></h2>
	<?php endif; ?>
	
	<p><strong><?php _e('Dirección:'); ?></strong><br />
	<?php if ($loc['address_1']) echo $loc['address_1']; ?><br />
	<?php if ($loc['address_2']) echo $loc['address_2']; ?>
	</p>
	
	<?php if ($loc['schedule']) : ?>	
	<p><strong><?php _e('Horarios de Oficina:'); ?></strong><br />
	<?php echo implode('<br />', explode(',', $loc['schedule'])); ?>
	</p>
	<?php endif; ?>
		
	<?php if ($loc['tel']) : ?>
	<p><strong><?php _e('Teléfonos:'); ?></strong><br />
	<?php echo implode('<br />', $loc['tel']); ?>
	</p>
	<?php endif; ?>
	<?php if ($loc['email']) echo '<strong>Mail:</strong> '.$loc['email']; ?>
	
<?php } ?>

<?php get_header();?>

<div class="col side">
	
	<div class="section sidebar">
	<div class="title"><?php echo $primary[0]['name'];?></div>
	<?php ct_print_location($primary[0]); ?>
	</div>
	<div class="section sidebar">
		<div class="title"><?php _e('Otras Ubicaciones'); ?></div>
		<?php foreach($locations as $loc) : ?>
		<?php ct_print_location($loc, true); ?>
		<?php endforeach; ?>
	</div>

</div>
<div class="col main">
	<?php echo do_shortcode('[ct-form to="'.$primary[0]['email'].'" subject="'.$subject.'"]'); ?>
	<div class="spacer"></div>
</div>

<?php get_footer();?>
<?php endwhile; endif; ?>