<?php global $site_title, $site_subtitle; ?>
<?php get_template_part('inc', 'nav-bar'); ?>
<?php if (is_front_page()) : ?>
<div class="header"><div class="img"></div></div>
<div class="title_bar">
	<h1><?php bloginfo('name');?></h1>
	<p class="serif-italic"><?php bloginfo('description'); ?></p>
</div>
<?php else : ?>

<?php endif; ?>
<div class="main_menu">
	<?php get_template_part('inc', 'menu-main'); ?>
</div>