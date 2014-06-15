<div class="section sidebar">
	<div class="title"><?php _e('Búsqueda'); ?></div>
	<form id="search-form" method="GET">
		<input type="text" name="s" value="<?php echo $_GET['s']; ?>" 
			   placeholder="<?php _e('Palabras claves'); ?>" />
		<a onclick="jQuery('#search-form').submit();" 
		   class="btn red right"><div class="label">BUSCAR</div></a>
	</form>
</div>

