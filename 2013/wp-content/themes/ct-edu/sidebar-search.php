<?php global $sbar_args; ?>
<?php
	$title = ($sbar_args['title']) ? $sbar_args['title'] 
								   : __('Búsqueda');
?>
<div class="section">
	<div class="title"><?php echo $title; ?></div>
	<?php bp_directory_members_search_form(); ?>
	<script type="text/javascript">
		var btn = jQuery('#members_search_submit');
		var newBtn = jQuery('<button id="members_search_submit" class="submit btn red right"><div class="label">'+btn.val()+'</div></div>');
		btn.replaceWith(newBtn);
	</script>
</div>