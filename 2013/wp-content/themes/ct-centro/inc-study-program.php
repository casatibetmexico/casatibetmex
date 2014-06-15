<?php global $program, $info; 

$days = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$access = array('Abierto al Público', 'Alumnos Regulares');
?>
<div class="location-wrapper">
<h2><?php echo $info['name']; ?></h2>
<p><?php if ($info['address_1']) echo $info['address_1']; ?><br />
	<?php if ($info['address_2']) echo $info['address_2']; ?></p>
</div>

	
	<table class="group-schedule" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td width="100"><?php _e('Día'); ?></td>
				<td width="100"><?php _e('Horario'); ?></td>
				<td><?php _e('Grupo'); ?></td>
				<td width="100"><?php _e('Acceso'); ?></td>
			</tr>
		</thead>
		<tbody>
<?php for($i=0; $i<7; $i++) : ?>
<?php if ($program[$i]) : ?>
<?php foreach((array) $program[$i] as $j=>$g) : ?>
		<tr <?php if ($j==0) : ?>class="day"<?php endif; ?>>
			<?php if ($j==0) : ?>
			<td valign="top" rowspan="<?php echo count($program[$i]); ?>"><?php echo $days[$i]; ?></td>
			<?php endif; ?>
			<td valign="top"><?php printf('%s - %s', 
							 get_post_meta($g->ID, 'ct_group_start', true),
							 get_post_meta($g->ID, 'ct_group_end', true)); ?>
			</td>
			<td valign="top">
			<h4><?php echo $g->post_title; ?></h4> 
			<?php if ($g->post_excerpt) : ?>
			<p><?php echo $g->post_excerpt; ?></p>
			<?php endif; ?>
			</td>
			<td valign="top"><?php $a = get_post_meta($g->ID, 'ct_group_level', true); echo $access[$a]; ?></td>
		</tr>
<?php endforeach; ?>

<?php endif; ?>
<?php endfor; ?>
		</tbody>
	</table>