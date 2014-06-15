<?php global $sbar_args;
$title = ($sbar_args['title']) ? $sbar_args['title'] : __('Nuevo en la Biblioteca');
$latest = ct_res_get_latest();
if ($latest) : ?>
<div class="section">	
<div class="title"><?php echo $title; ?></div>
	<ul class="listing pages">
<?php foreach((array) $latest as $p) : ?>
		<li data-href="<?php echo ct_get_permalink($p->ID); ?>">
			<div class="icon view"></div><?php echo $p->post_title; ?>
		</li>
<?php endforeach; ?>
</ul>	
</div>
<?php endif; ?>