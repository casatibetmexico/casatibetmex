<?php 
$GLOBALS['use_feature'] = true;
get_header();?>
<div class="col full">
	<?php get_banners('features', ct_query_args(array('slug'=>'destacados'))); ?>
</div>
<div class="col side">
	
	<?php //ct_sidebar('donate-bank'); ?>
	<!-- <div class="sidebar"> -->
		<?php $p = get_page_by_path('/altruismo-inicio'); 
		ct_sidebar('info', array('content'=>get_post_meta($p->ID, 'ct_sidebar', true))); ?>
	<!-- </div> -->
</div>
<div class="col main">
	<div class="intro top">
		<?php 
		$p = get_page_by_path('/como-apoyar'); 
		echo get_post_meta($p->ID, 'ct-mod-intro', true); 
		?>
		<div class="button_bar">
			<div data-href="<?php echo ct_get_permalink($p->ID);?>" class="btn red right">
				<div class="label">MÁS INFORMACIÓN</div>
			</div>
		</div>
	</div>
	
	<?php ct_sidebar('archive', array('title'=>__('Noticias'),
									  'href'=>ct_get_permalink('/noticias'),
								      'post_type'=>'news', 
								      'listing'=>'news-main',
								      'limit'=>4)); ?>
</div>

<?php get_footer();?>