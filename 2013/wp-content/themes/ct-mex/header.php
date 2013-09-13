<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta name="viewport" content="width=1021" />
<title><?php bloginfo('name'); ?><?php wp_title( '|', true, 'left' ); ?></title>
<?php wp_head();?>
</head>
<body>
<div id="rm-container">
	<div id="rm-header">
		<div class="rm-content">
			<?php get_template_part('inc', 'header'); ?>
		</div>
	</div>
	<div id="rm-main">
		<div class="rm-content">