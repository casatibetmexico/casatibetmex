<?php
global $sbar_args;
$digest = ct_is_theme('ct-mex');
$events = ct_event_get_upcoming($sbar_args['limit'], array(), $digest);
?>
<div class="section listing">
<div class="title" data-href="<?php echo ct_get_permalink('calendario');?>">
	<div class="icon view"></div>
<?php _e('Proximos Eventos'); ?></div>
<ul>
<?php ct_listing($sbar_args['listing'], $events); ?>
</ul>
</div>