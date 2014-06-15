<?php global $current_user;
auth_redirect(); ?>

<?php get_template_part('inc', 'nav-bar'); ?>

<div class="user-profile">
	<div class="thumbnail">
		<?php bp_loggedin_user_avatar( 'type=full' ); ?>
	</div>
	<?php echo ct_site_tag(bp_loggedin_user_id(), true);?>
	<h1><?php bp_loggedin_user_fullname();?></h1>
	<p><?php echo ucfirst(bp_get_last_activity( bp_loggedin_user_id() )); ?></p>
	<div class="main_menu">
		<?php ct_nav_menu('Usuarios'); ?>
	</div>
</div>

