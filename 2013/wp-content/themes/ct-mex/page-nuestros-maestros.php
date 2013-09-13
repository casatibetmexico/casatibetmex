<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>

<div class="col full">
	<div class="teacher-grid">
		<div class="page_header">
			<?php dynamic_sidebar('ct_page_nav'); ?>
			<h1><?php the_title(); ?></h1>
		</div>
		<?php
		$dalai_lama = ct_get_teacher('su-santidad-el-xiv-dalai-lama');
		$geshe_sopa = ct_get_teacher('geshe-lhundub-sopa');
		$tulku_urgyen = ct_get_teacher('tulku-urgyen-rinpoche');
		?>
		<table class="teachers">
			<tr>
				<td class="right">
				<div class="teacher right" data-href="<?php echo ct_get_permalink($geshe_sopa->ID); ?>">
					<div class="info">
						<h2><?php echo $geshe_sopa->post_title;?></h2>
					</div>
					<div class="thumbnail ct-image-fader">
					<?php echo ct_get_thumbnail($geshe_sopa, array(250,324,true)); ?>
					</div>
				</div>
				</td>
				<td class="center">
				<div class="teacher center" data-href="<?php echo ct_get_permalink($dalai_lama->ID); ?>">
					<div class="info">
						<h2><?php echo $dalai_lama->post_title;?></h2>
					</div>
					<div class="thumbnail ct-image-fader">
					<?php echo ct_get_thumbnail($dalai_lama, array(319,414,true)); ?>
					</div>
				</div>
				</td>
				<td class="left">
				<div class="teacher left" data-href="<?php echo ct_get_permalink($tulku_urgyen->ID); ?>">
					<div class="info">
						<h2><?php echo $tulku_urgyen->post_title;?></h2>
					</div>
					<div class="thumbnail ct-image-fader">
					<?php echo ct_get_thumbnail($tulku_urgyen, array(250,324,true)); ?>
					</div>
				</div>
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="col full">
	<div class="teacher-grid" style="margin-bottom:25px;">
	<table class="teachers">
		<?php 
		
		$args = array('no_paging'=>true);
		$teachers = ct_get_teachers(array('invitado','monastico','rinpoche'), $args);
		
		$args['fields'] = 'ids';
		$args['post__not_in'] = ct_get_teachers(array('invitado','monastico','rinpoche'), $args);
		$args['fields'] = 'all';
		$monks = ct_get_teachers(array('invitado','monastico'), $args);
		
		$teachers = array_merge($teachers, $monks);
		
		$args = array('no_paging'=>true);
		$teachers = array_merge($teachers, ct_get_teachers(array('invitado','leico','rinpoche'),$args));
		
		$args['fields'] = 'ids';
		$args['post__not_in'] = ct_get_teachers(array('invitado','leico','rinpoche'), $args);
		$args['fields'] = 'all';
		$lay = ct_get_teachers(array('invitado','leico'), $args);
		$teachers = array_merge($teachers, $lay);
		
		array_push($teachers, ct_get_teacher('marco-antonio-karam'));
		
		ct_listing('teacher', $teachers); 
		?>
	</table>
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