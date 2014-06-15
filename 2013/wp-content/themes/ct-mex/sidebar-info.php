<?php 
global $sbar_args;
if ($sbar_args['content']) :?>
<div class="sidebar">
	<?php echo do_shortcode($sbar_args['content']); ?>
</div>
<?php endif; ?>