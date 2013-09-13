<?php

function ct_sidebar_contents($posts) {
	if (count($posts) > 0) {
?>
	<h4>Contenidos:</h4>
	<ul class="sub_sections">
		<?php for($i=0; $i<count($posts); $i++) : ?>
		<li><a href="<?php echo get_permalink($posts[$i]->ID);?>"><?php echo $posts[$i]->post_title;?></a></li>
		<?php endfor; ?>
	</ul>
<?php 
	} 
}

function ct_sidebar_bibliography($p) {
	$meta_key = ($p->post_type == 'modulo') ? 'mod_bibliography' : 'res_bibliography';
	$biblio = get_post_meta($p->ID, $meta_key, true);
	if ($biblio) :
?>
	<h4>Bibliografía:</h4>
	<div class="biblio">
	<?php echo apply_filters('the_content', $biblio); ?>
	</div>
<?php 
	endif;
}

function ct_sidebar_resources($p) {

	$listing = ct_mod_get_resources_as_listing($p);

	if ($listing['types']) {
	
	for($i=0; $i<count($listing['types']); $i++) :
		$type = $listing['types'][$i];
		if ($type['children']['types'] || $type['items']) :
?>
	<h4><?php echo $type['label'].':';?></h4>
	<div class="biblio">
		<?php if ($type['children']) : ?>
			<?php for($j=0; $j<count($type['children']['types']); $j++) : ?>
				<?php $child = $type['children']['types'][$j]; ?>
				<p><strong><?php echo $child['label'];?></strong><br />
				<ul class="sub_sections">
					<?php for($l=0; $l<count($child['items']); $l++) : ?>
					<li><a href="<?php echo add_query_arg('mod', $p->ID, get_permalink($child['items'][$l]['id']));?>"><?php echo $child['items'][$l]['label'];?></a></li>
					<?php endfor; ?>
				</ul></p>
			<?php endfor; ?>
		<?php elseif ($type['items']) : ?>
		<ul class="sub_sections">
			<?php for($j=0; $j<count($type['items']); $j++) : ?>
			<li><a href="<?php echo add_query_arg('mod', $p->ID, get_permalink($type['items'][$j]['id']));?>"><?php echo $type['items'][$j]['label'];?></a></li>
			<?php endfor; ?>
		</ul>
		<?php endif; ?>
	</div>
<?php
		endif;
	endfor;
	}
}

?>