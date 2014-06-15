<?php global $sbar_args;

	$title = ($sbar_args['title']) ? $sbar_args['title'] 
								   : __('Tipos de Recursos');
								   
	$parent = ($sbar_args['parent']) ? $sbar_args['parent'] : 0;

	$types = get_terms('resource-type', array('orderby'=>'menu_order title', 'order'=>'ASC',
											  'parent'=>$parent, 'hide_empty'=>($parent==0)?false:true));

	if ($types) {
?>	

<div class="section">
		<div class="title"><?php echo $title;?></div>
		<ul class="listing pages">
<?php foreach($types as $type) :?>
			<li data-href="<?php echo ct_get_permalink($type->term_id, 'resource-type');?>">
				<div class="icon view"></div>
				<?php echo $type->name;?>
			</li>
<?php endforeach; ?>	
		</ul>
</div>
<?php	
	}
?>