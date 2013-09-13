<?php 
$GLOBALS['use_validator'] = true;
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header();?>
<div class="col side">
	<?php ct_sidebar('login'); ?>
	<div class="spacer"></div>
	<?php ct_sidebar('info', array('content'=>get_post_meta($post->ID, 'ct_sidebar', true))); ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="page_content">
		<?php the_content(); ?>
		<?php if ($_REQUEST['success']) : ?>
		<p><?php _e( '¡Felicidades! Ya eres miembro de la comunidad de Casa Tibet México. Favor de iniciar tu sesión con el usuario y contraseña que has indicado.'); ?></p>
		<?php else : ?>
		
		<?php 
		if ($_REQUEST['error']) : ?>
		
		<div class="msg-error">
			<p><?php 
			switch($_REQUEST['error']) {
				case 'email-exists':
					_e('Esta correo electrónico ya existe. Favor de intentar de nuevo.');
					break;
				case 'username-exists':
					_e('Esta nombre de usuario ya existe. Favor de introducir otro.');
					break;
			}
			?></p>
		</div>
		
		<?php endif; ?>
		
		<form id="rm-form" method="POST" action="<?php echo plugins_url('casa-tibet').'/ct-register.php'?>">
		<?php get_template_part('form', 'register'); ?>
		</form>
		<?php endif; ?>
	</div>
</div>
<?php endwhile; endif; ?>
<?php get_footer();?>