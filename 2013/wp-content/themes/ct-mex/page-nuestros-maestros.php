<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

<div class="col full">
	<div class="teacher-grid">
		<div class="page_header">
			<?php dynamic_sidebar('ct_page_nav'); ?>
			<h1><?php the_title(); ?></h1>
		</div>
		<?php  $types = get_terms( 'teacher-type'); ?> 
		<?php foreach((array) $types as $t) : ?>
		<div class="section sidebar">
			<div class="title"><?php echo $t->name; ?></div>
			
			<?php if ($t->slug == 'maestros-raiz') : ?>
			<div class="root-teachers">
			<?php endif; ?>
			<!-- <div class="teacher-grid" style="margin-bottom:25px;"> -->
				<table class="teachers">
					<?php 
					
					$args = array('nopaging'=>true);
					$teachers = ct_get_teachers($t->slug, $args);
					
					$list = ($t->slug == 'maestros-raiz') ? 'teacher-root' : 'teacher';
					
					ct_listing($list, $teachers); 
					?>
				</table>
			<!-- </div> -->
			<?php if ($t->slug == 'maestros-raiz') : ?>
			</div>
			<?php endif; ?>

		</div>

		<?php endforeach; ?>
	</div>
</div>

<script type="text/javascript">

jQuery('.teacher').each(function() {
	var $this = jQuery(this);
	$this.addClass('ui');
	$this.hover(function() {
		var info = jQuery(this).find('.info');
		info.show();
		info.css('top', 20);
		info.animate({opacity:1.0, top:0}, 400);
		info.find('h2').delay(400).fadeIn();	
		jQuery(this).find('.ct-image-fader').data('imageFader').onOver();
	}, function() {
		jQuery(this).find('.info').fadeOut(function() {
			var info = jQuery(this).find('.info');
			info.css('top', 20);
		});
		jQuery(this).find('.ct-image-fader').data('imageFader').onOut();
	});
	
	$this.click(function() {
		ct.goTo(jQuery(this).data('href'), true);
	});	
	
});
jQuery('.teachers .ct-image-fader').each(function() {
	jQuery(this).imageFader({fade:500});
});
</script>

<?php get_footer();?>
<?php endwhile; endif; ?>