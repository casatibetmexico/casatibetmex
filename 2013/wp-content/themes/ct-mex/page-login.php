<?php get_header();?>
<?php
$args = array('redirect' => site_url('/perfil'));
wp_login_form( $args );
?>
<?php get_footer();?>