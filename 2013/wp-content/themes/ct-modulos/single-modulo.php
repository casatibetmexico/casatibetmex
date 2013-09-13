<?php $GLOBALS['header'] = 'meditation'; ?> 
<?php get_header();?>

<?php if (ct_mod_is_restricted($post->ID) && !ct_mod_can_view($post->ID)) : ?>
	<p class="intro">Este m&oacute;dulo est&aacute; protegido. Favor de teclear la contrase&ntilde;a que te fue proporcionada por tu facilitador de grupo.</p> 
	<p class="login">
	<input type="password" id="mod_passcode" onchange="ct_mod_verifyPasscode('mod_passcode', <?php echo $post->ID; ?>);"/>
	<a class="button" onclick="ct_mod_verifyPasscode('mod_passcode', <?php echo $post->ID; ?>);">ENVIAR</a></p>
	<p class="back"><a href="<?php bloginfo('siteurl');?>/modulos">Regresar a la lista de m&oacute;dulos</a></p>
<?php else : ?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>	
	
	<div class="page_header">
		<h1><?php the_title();?></h1>
		<?php dynamic_sidebar('ct_mod_header'); ?>
	</div>
	<?php $intro = get_post_meta($post->ID, 'ct-mod-intro', true); 
	if ($intro) : ?>
	<div class="page_header">
		<p class="intro"><?php echo do_shortcode(nl2br($intro));?></p>
	</div>
	<?php endif; ?>
	
	<div class="sidebar">
		<?php
		$q = new WP_Query(array('post_type'=>'modulo',
							    'post_parent'=>$post->ID,
							    'order'=>'ASC',
							    'orderby'=>'menu_order',
							    'nopaging'=>true));
		ct_sidebar_contents($q->posts);
		ct_sidebar_resources($post);
		ct_sidebar_bibliography($post); 
		?>
		
	</div>
	<div class="main_col">
		<?php the_content(); ?>
	</div>
	
	<?php endwhile; endif; ?>
	
	
<?php endif; ?>

<?php get_footer();?>