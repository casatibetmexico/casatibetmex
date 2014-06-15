<div class="section listing">
<?php if (bp_loggedin_user_id()) : ?>
	<div class="title" data-href="<?php echo bp_loggedin_user_link(); ?>">
		<div class="icon view"></div><?php _e('Mi Perfil'); ?></div>
	<div class="user_profile">
		<div class="thumbnail">
			<?php bp_loggedin_user_avatar( 'type=thumb' ); ?>
		</div>
		<h4><?php bp_loggedin_user_fullname();?></h4>
		<p class="user_subtitle">
		<span class="user-nicename">#<?php bp_loggedin_user_username(); ?></span>
		</p>
		<div class="button_bar">
			<div class="btn red right" data-href="<?php echo wp_logout_url( );?>" ><div class="label">CERRAR SESIÓN</div></div>
			<div class="btn red right" data-href="<?php echo bp_loggedin_user_link(); ?>" ><div class="label">VER PERFIL</div></div>
		</div>
	</div>
	
<?php else  : ?>
<?php 
global $post;
$args['redirect'] = ($post) ? ct_get_permalink($post->ID) : site_url('/perfil');
//wp_login_form( $args );
bbp_get_template_part( 'form', 'user-login' );
?>
<?php endif; ?>
</div>