<?php
global $tArgs, $tPages;
$title = $tArgs['atts']['title'];

?><div class="section">	
<div class="title"><?php echo $title; ?></div>
	<ul class="listing pages">
<?php foreach((array) $tPages as $key=>$items) : ?>
<?php $t = get_term_by('slug', $key, 'course-type'); ?>		
<?php if (!$tArgs['atts']['no_header']) : ?><li class="list_header"><?php echo $t->name; ?></li><?php endif; ?>
<?php foreach((array) $items as $p) : ?>	
		<li data-href="<?php echo ct_get_permalink($p->ID); ?>">
			<div class="icon view"></div><?php echo $p->post_title; ?>
		</li>
<?php endforeach; ?>	
<?php endforeach; ?>
</ul>	
</div>