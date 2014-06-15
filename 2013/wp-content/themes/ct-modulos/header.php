<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet' type='text/css'>
<?php wp_head();?>
</head>
<body>
<div id="rm-container">
	<div id="rm-header">
		<?php if ($GLOBALS['header']) : ?>
		<div class="rm-content">
			<?php get_template_part('inc', 'header-'.$GLOBALS['header']); ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="rm-content rm-main">