<?php global $ct_list; 

$total = count($ct_list);

$cols = 6;
$rows = ceil($total / $cols);

for($i=0; $i<$rows; $i++) : ?>
		<tr>
		<?php for($j=0; $j<$cols; $j++) : ?>
			<?php
				$index = ($i*$cols)+$j;
				$t = $ct_list[$index];
			?>	
			<td><?php if ($t) : ?>
			<div class="teacher secondary" data-href="<?php echo ct_get_permalink($t->ID); ?>">
				<div class="info">
					<h2><?php echo $t->post_title;?></h2>
				</div>
				<div class="thumbnail ct-image-fader">
				<?php echo ct_get_thumbnail($t, array(150,190,true)); ?>
				</div>
			</div><?php else : echo '&nbsp;'; endif;?></td>
		<?php endfor; ?>
		</tr>
<?php endfor; ?>