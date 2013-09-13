<?php global $site_title, $site_subtitle; ?>
<div class="nav_bar">

<?php
global $current_user;
get_currentuserinfo();
?>
	<ul class="login">
	<?php if (is_user_logged_in()) : ?>
		<li data-action="logout" data-href="<?php echo wp_logout_url( home_url() );?>">LOGOUT</li>
		<!-- <li data-action="groups">GRUPOS</li> -->
		<li data-action="profile" data-href="<?php echo bp_loggedin_user_link(); ?>">MI PERFIL</li>
		<li>¡Bienvenido <?php echo $current_user->display_name; ?>!</li>
	<?php else : ?>
		<li data-action="register">REGISTRATE</li>
		<!-- <li data-action="login">LOGIN</li> -->
		<li>¡Bienvenido Invitado!</li>
	<?php endif; ?>
	</ul>
	<ul class="menu">
		<?php $domains = ct_site_get_domains(true); ?>
		<li data-site="ct-mex" data-href="<?php echo $domains['ct-mex'];?>">CASA TIBET</li>
		<li data-site="ct-edu" data-href="<?php echo $domains['ct-edu']; ?>">EDUCACIÓN</li>
		<li data-site="ct-editorial" data-href="<?php echo $domains['ct-editorial']; ?>">EDITORIAL</li>
		<li data-site="ct-derechos" data-href="<?php echo $domains['ct-derechos']; ?>">ALTRUISMO</li>
	</ul>
</div>
<div class="header"><div class="img"></div></div>
<div class="title_bar">
	<h1><?php bloginfo('name');?></h1>
	<p class="serif-italic"><?php bloginfo('description'); ?></p>
</div>
<div class="main_menu">
	<?php get_template_part('inc', 'menu-main'); ?>
</div>