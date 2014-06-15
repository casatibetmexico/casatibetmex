<div class="menu">
	<ul>
		<li data-href="<?php echo site_url('/wp-admin'); ?>">ADMIN</li>
	</ul>
</div>
<div class="copyright">
<?php 
$center = ct_get_current_center();
$locations = unserialize(get_post_meta($center, 'ct_center_locations', true));
$primary = array_pop(array_splice($locations, get_post_meta($center, 'ct_center_primary_loc', true), 1));
?>

<?php echo $primary['address_1']; ?> <?php if ($primary['address_2']) echo ', '.$primary['address_2']; ?><br />
<?php if ($primary['tel']) : ?>Tel. <?php echo implode('&nbsp;&bull;&nbsp;', $primary['tel']); ?> <?php endif; ?>
<?php if ($primary['email']) : ?>&nbsp;&mdash;&nbsp;<?php echo $primary['email']; ?><?php endif; ?>
</div>