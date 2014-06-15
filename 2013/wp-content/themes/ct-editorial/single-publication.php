<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php 
	$types = wp_get_post_terms( $post->ID, 'publication-type' );
	$details = get_post_meta($post->ID, 'ct_pub_details', true);
	$subtitle = get_post_meta($post->ID, 'ct_pub_subtitle', true);
	$pub_date = get_post_meta($post->ID, 'ct_pub_publish_date', true);
?>
<?php get_header();?>
<div class="col side publication">
	<?php ct_sidebar('donate-recurring'); ?>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main event publication">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		
		<div class="head_wrapper">
			<?php $thumb = ct_get_thumbnail($post);
			if ($thumb) : ?><div class="left"><?php  echo $thumb; ?></div><?php endif; ?>
			<div class="event_type">
				<?php foreach((array) $types as $t) : ?>
				<?php echo ct_tag($t->name); ?> 
				<?php endforeach; ?>
			</div>
			<h1><?php the_title(); ?></h1>
			<?php if ($subtitle) : ?>
			<h4 class="serif-italic"><?php echo $subtitle; ?></h4>
			<?php endif; ?>
			<?php if ($details['author']) : ?>
				<p class="author"><?php printf('por %s<br />(%s)', $details['author'], ucfirst(date_i18n('F Y', strtotime($pub_date)))); ?></p>
			<?php endif; ?>
			<label>Costo:</label>
			<p><?php if ($details['cost']) : echo $details['cost'].' MXN'; ?>
			<?php else : ?>GRATIS<?php endif; ?></p>
			<?php if ($details['shipping']) : ?>
			<p class="shipping"><?php printf('(+ %s MXN DE ENVIO)', $details['shipping']); ?></p>
			<?php endif; ?>
			<div class="button_bar">
				<?php if ($details['download']) :?> 
				<a class="btn red right" href="<?php echo $details['download']; ?>" target="_blank">
					<div class="label">DESCARGA PDF</div>
				</a>
				<?php endif; ?>
				<?php if ($details['cost']) : ?>
				<div class="right">
				<?php 
					$pp_args = array('email'=>'editorial@casatibet.org.mx',
									 'name'=>$post->post_title,
									 'return'=>ct_get_permalink($post->ID),
									 'price'=>$details['cost'],
									 'cmd'=>'_xclick');
					if ($details['shipping']) $pp_args['shipping'] = $details['shipping'];
					ct_sidebar('paypal', $pp_args); ?>
				</div>
				<?php endif; ?>
				
			</div>
		</div>
	</div>
	<div class="page_content">
		<?php the_content(); ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>