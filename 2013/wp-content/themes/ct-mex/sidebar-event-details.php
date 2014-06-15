<?php
global $sbar_args, $post;

$period = ct_event_get_period($post);

$tickets = get_post_meta($post->ID, 'ct_event_tickets', true);
$location = get_post_meta($post->ID, 'ct_event_location', true);

?>
<div class="details">
<table>
	<tr>
		<td>Fecha:</td>
		<td><?php echo $period; ?></td>
	</tr>
	<?php if ($location['name'] || $location['address']): ?>
	<tr>
		<td>Lugar:</td>
		<td><?php if ($location['name']) : ?><strong><?php echo $location['name']; ?></strong><br /><?php endif; ?>
		<?php if ($location['address']) : ?><?php echo $location['address']; ?><?php endif; ?></td>
	</tr>
	<?php endif; ?>
	
	<tr>
		<td>Precio:</td>
		<td><?php if ($tickets['price']) : ?><?php echo $tickets['price']; ?><?php else : ?><?php _e('GRATUITA'); ?><?php endif; ?></td>
	</tr>
	
</table>
</div>