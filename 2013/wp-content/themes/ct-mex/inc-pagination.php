<?php global $pagination; ?>
<div class="pager">
<?php if ($pagination['next']) : ?>
<div class="icon ui next" data-page="<?php echo $pagination['next'];?>"></div>
<?php endif; ?>
<?php if ($pagination['prev']) : ?>
<div class="icon ui prev" data-page="<?php echo $pagination['prev'];?>"></div>
<?php endif; ?>
</div>
<script type="text/javascript">
	jQuery('.pager .icon').each(function() {
		ct.addHover(this);
		jQuery(this).click(function(e) {
			e.stopPropagation();
			ct.goTo(jQuery(this).data('page'), true);
		});
	});
</script>