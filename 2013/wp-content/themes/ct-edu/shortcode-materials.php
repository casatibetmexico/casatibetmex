<?php 
global $tArgs, $tPages;
$title = ($tArgs['atts']['title']) ? $tArgs['atts']['title'] : __('Módulos de Práctica');	


function ct_mod_shortcode_items($items, $level=0) {
	global $tArgs;
foreach($items as $p) : ?>
	<?php $perma = ($tArgs['atts']['ref']) ? add_query_arg('ref', $tArgs['atts']['ref'], ct_get_permalink($p['ob']->ID))
								  : ct_get_permalink($p['ob']->ID);
	?>
		<li data-href="<?php echo $perma; ?>"><div class="icon view"></div>
		<?php echo '<p style="margin:0px;padding-left:'.($level*10).'px;text-align:left;">'.$p['ob']->post_title.'</p>';?>
		</li>
		<?php if ($p['children']) :?>
		<?php ct_mod_shortcode_items($p['children'], $level+1); ?>
		<?php endif; ?>
	<?php endforeach;
}

?>
<?php if ($tPages) : ?>
<div class="section">	
	<div class="title"><?php echo $title; ?></div>
	<ul class="listing pages">
		<?php ct_mod_shortcode_items($tPages); ?>
	</ul>	
</div>
<?php endif; ?>
	