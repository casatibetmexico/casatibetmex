<div class="nav_bar">

<?php
global $current_user;
get_currentuserinfo();
$domains = ct_site_get_domains(true); 
?>
	<ul class="login">
	<?php if ($domains['ct-user']) : ?>
	
	<?php if (is_user_logged_in()) : ?>
		<li data-action="logout" data-href="<?php echo wp_logout_url( $domains['ct-mex'] );?>">LOGOUT</li>
		<li data-action="profile" data-href="<?php echo $domains['ct-user']; ?>">MI PERFIL</li>
		<?php if (current_user_can( 'administrator' ) || current_user_can( 'editor' )) : ?>
		<li data-action="goto" data-href="<?php echo $domains['ct-mex'].'/2013/wp-admin/'; ?>">ADMIN</li>
		<?php endif; ?>
		
		
		<li>¡Bienvenido <?php echo $current_user->display_name; ?>!</li>
	<?php else : ?> 
		<li data-action="register" data-href="<?php echo wp_registration_url(); ?>">REGISTRAR</li>
		<li data-action="login" data-href="<?php echo wp_login_url($domains['ct-user']); ?>">LOGIN</li>
		<li>¡Bienvenido Invitado!</li>
	<?php endif; ?>
	
	<?php else : ?>

	<?php if (is_user_logged_in()) : ?>
		<li data-action="logout" data-href="<?php echo wp_logout_url( home_url() );?>">LOGOUT</li>
		<!-- <li data-action="groups">GRUPOS</li> -->
		<li data-action="profile" data-href="<?php echo bp_loggedin_user_link(); ?>">MI PERFIL</li>
		<li>¡Bienvenido <?php echo $current_user->display_name; ?>!</li>
	<?php else : ?>
		<li data-action="register" data-href="<?php echo wp_registration_url(); ?>">REGISTRAR</li>
		<li data-action="login" 
			data-href="<?php echo wp_login_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>">LOGIN</li>
		<li>¡Bienvenido Invitado!</li>
	<?php endif; ?>
		
	<?php endif; ?>
	</ul>
	<ul class="menu">
		<li data-site="ct-mex" data-href="<?php echo $domains['ct-mex'];?>">CASA TIBET</li>
		<li data-site="ct-edu" data-href="<?php echo $domains['ct-edu']; ?>">EDUCACIÓN</li>
		<li data-site="ct-editorial" data-href="<?php echo $domains['ct-editorial']; ?>">EDITORIAL</li>
		<li data-site="ct-derechos" data-href="<?php echo $domains['ct-derechos']; ?>">ALTRUISMO</li>
	</ul>
</div>