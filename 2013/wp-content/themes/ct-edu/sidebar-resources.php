<?php global $sbar_args;

	$p = $sbar_args['post'];

	$listing = ct_mod_get_resources_as_listing($p);

	if ($listing['types']) {
?>	

<?php	
	for($i=0; $i<count($listing['types']); $i++) :
		$type = $listing['types'][$i];
		if ($type['children']['types'] || $type['items']) :
?>
	<div class="section sidebar">
		<div class="title"><?php echo $type['label'].':';?></div>
	
		<?php if ($type['children']) : ?>
			<?php for($j=0; $j<count($type['children']['types']); $j++) : ?>
				<?php $child = $type['children']['types'][$j]; ?>
				<p><strong><?php echo $child['label'];?></strong><br />
				<ul class="sub_sections">
					<?php for($l=0; $l<count($child['items']); $l++) : ?>
					<li><a href="<?php echo add_query_arg(array('mod'=>$p->ID, 'ref'=>$_GET['ref']), ct_get_permalink($child['items'][$l]['id']));?>"><?php echo $child['items'][$l]['label'];?></a></li>
					<?php endfor; ?>
				</ul></p>
			<?php endfor; ?>
		<?php elseif ($type['items']) : ?>
		<ul class="sub_sections">
			<?php for($j=0; $j<count($type['items']); $j++) : ?>
			<li><a href="<?php echo add_query_arg(array('mod'=>$p->ID, 'ref'=>$_GET['ref']), ct_get_permalink($type['items'][$j]['id']));?>"><?php echo $type['items'][$j]['label'];?></a></li>
			<?php endfor; ?>
		</ul>
		<?php endif; ?>
	</div>
<?php
		endif;
	endfor;
?>	
	
<?php	
	}
?>