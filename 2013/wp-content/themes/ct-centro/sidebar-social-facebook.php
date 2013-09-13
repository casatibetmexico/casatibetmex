<?php
global $sbar_args;
$url = ($sbar_args['url']) ? $sbar_args['url'] : 'https://www.facebook.com/casatibetmexico';
?>

<div class="section sidebar">
	<div class="title"><?php _e('Síguenos en Facebook'); ?></div>
	<iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo $url; ?>&amp;width=315&amp;height=600&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;show_border=false&amp;header=false&amp;appId=122403712916" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:315px; height:600px;" allowTransparency="true"></iframe>
</div>
