<?php
global $sbar_args;
$events = ct_news_get_latest($sbar_args['limit']);
?>
<div class="section listing">
<div class="title" data-href="<?php echo ct_get_permalink('/noticias');?>">
	<div class="icon view"></div>
<?php _e('Noticias'); ?></div>
<ul>
<?php ct_listing($sbar_args['listing'], $events); ?>
</ul>
</div>