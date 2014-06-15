<?php 
$GLOBALS['use_feature'] = true;
get_header();?>
<div class="col full">
	<?php get_banners('features', ct_query_args(array('slug'=>'destacados'))); ?>
</div>
<div class="col side">
	<?php ct_sidebar('donate-recurring'); ?>
	<?php 
	echo do_shortcode('[ct-quote author="Shantideva"]Cualquier alegría que hay en este mundo proviene de desear la felicidad de los demásy todo el sufrimiento que hay en este mundo no viene de otra parte que de mi deseo de ser feliz.

¿Qué necesidad hay de decir algo más? Los necios se esfuerzan por su propio beneficio, los budas trabajan por el beneficio de los demás.¡Mira simplemente la diferencia que hay entre ambos![/ct-quote]');
	?>
	
</div>
<div class="col main">
	<div class="intro top">
		<?php 
		$p = get_page_by_path('/consejo-editorial'); 
		echo get_post_meta($p->ID, 'ct-mod-intro', true); 
		?>
		<div class="button_bar">
			<div data-href="<?php echo ct_get_permalink($p->ID);?>" class="btn red right">
				<div class="label">MÁS INFORMACIÓN</div>
			</div>
		</div>
	</div>
	
	<?php ct_sidebar('archive', array('title'=>__('Publicaciones Recientes'),
									  'href'=>ct_get_permalink('/publicaciones'),
								      'post_type'=>'publication', 
								      'listing'=>'publication',
								      'limit'=>5)); ?>
								      
	
</div>

<?php get_footer();?>