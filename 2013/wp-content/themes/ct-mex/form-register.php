<?php global $form_args; 
$title = __('¿No eres miembro? ¡Unete a la comunidad!');
?>
<div class="header">
	<div class="title"><?php echo $title;?></div>
</div>

<div class="form-body">
	<table width="100%" cellpadding="0" cellspacing="5">
		<tr>
			<td width="50%"><label><?php _e('<span class="error">*</span> Nombre de Usuario'); ?></label>
	<input type="text" id="signup-username" name="signup-username" /></td>
			<td><label><?php _e('<span class="error">*</span> Correo electr&oacute;nico'); ?></label>
	<input type="text" id="signup-email" name="signup-email" /></td>
		</tr>
		<tr>
			<td width="50%"><label><?php _e('<span class="error">*</span> Contraseña'); ?></label>
	<input type="password" id="signup-pass" name="signup-pass" /></td>
			<td><label><?php _e('<span class="error">*</span> Confirmar Contraseña'); ?></label>
	<input type="password" id="signup-pass-confirm" name="signup-pass-confirm" /></td>
		</tr>
		<tr>
			<td colspan="2"><label><?php _e('<span class="error">*</span> Nombre/s'); ?></label>
	<input type="text" id="signup-name-first" name="signup-name-first" />
	<label><?php _e('<span class="error">*</span> Appellidos/s'); ?></label>
	<input type="text" id="signup-name-last" name="signup-name-last" /></td>
		</tr>
	</table>
	<input type="hidden" name="return-url" value="<?php bloginfo('home');?>/register" />

	<div class="button_bar right">
	<a onclick="jQuery('#rm-form').submit();" class="btn red send"><div class="label">REGISTRAR</div></a>
	</div>
</div>


<script type="text/javascript">
jQuery(document).ready(function() {
	var form_ops = {
		rules: {
			'signup-username':'required',
			'signup-email':{required:true, email:true},
			'signup-pass':'required',
			'signup-pass-confirm': {required:true, equalTo:'#signup-pass'},
			'signup-name-first':'required',
			'signup-name-last':'required'
		},
		messages: {
			'signup-username':"<?php _e('Este campo es requerido.');?>",
			'signup-pass':"<?php _e('Este campo es requerido.');?>",
			'signup-pass-confirm': "<?php _e('Las contraseñas no son iguales.');?>",
			'signup-email': "<?php _e('Usa un correo válido');?>",
			'signup-name-first':"<?php _e('Este campo es requerido.');?>",
			'signup-name-last':"<?php _e('Este campo es requerido.');?>"
		}
	};
	jQuery('#rm-form').validate(form_ops);
});
</script>

