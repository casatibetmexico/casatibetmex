<?php global $tArgs; 
$atts = $tArgs['atts'];
$title = ($atts['title']) ? $atts['title'] : __('Filtros');
$base = ($atts['base']) ? $atts['base'] : $_SERVER['REQUEST_URI'];
?>
<div class="section">
	<div class="title"><?php echo $title; ?></div>
	<ul class="listing pages">
<?php foreach($tArgs['items'] as $item) : ?>
<?php if ($item['term']) : ?>
		<li data-href="<?php echo add_query_arg($atts['taxonomy'], $item['term']->slug, $base); ?>">
		<div class="icon view"></div>
		<?php printf(__('%s (%s)'), $item['term']->name, $item['term']->count); ?></li>
<?php endif;?>
<?php if ($item['children']) : ?>
<?php foreach($item['children'] as $child) : ?>
		<li data-href="<?php echo add_query_arg($atts['taxonomy'], $child->slug, $base); ?>" class="indent">
		<div class="icon view"></div>
		<?php printf(__('%s (%s)'), $child->name, $child->count) ?></li>
<?php endforeach; ?>
<?php endif;?>
<?php endforeach; ?>
	</ul>
</div>