<?php global $form_args; 
$title = ($form_args['title']) ? $form_args['title'] : __('Forma de Contacto');
$subject = ($form_args['subject']) ? $form_args['subject'] : $title;
?>
<div class="header">
	<div class="title"><?php echo $title;?></div>
	<?php if ($_GET['rm-result']) : ?>
	<div class="msg-thankyou">
		<p><?php _e('¡Gracias! Su mensaje ha sido enviado.'); ?></p>
		<a href="javascript:toggleForm();" class="btn red">ENVIAR NUEVO MENSAJE</a>
	</div>
	<div class="msg-help" style="display:none;">
		<p><?php _e('Por favor escriba en el siguiente formulario para contactar con nosotros'); ?></p>
	</div>
	<?php else : ?>
		<p><?php _e('Por favor escriba en el siguiente formulario para contactar con nosotros'); ?></p>
	<?php endif ; ?>
</div>

<div class="form-body">
	<label><?php _e('<span class="error">*</span> Nombre completo'); ?></label>
	<input type="text" id="rm-name" name="rm-name" />
	<label><?php _e('<span class="error">*</span> Correo electr&oacute;nico'); ?></label>
	<input type="text" id="rm-email" name="rm-email" />
	<label><?php _e('<span class="error">*</span> Comentarios'); ?></label>
	<textarea id="rm-message" name="rm-message" ></textarea>
	<input type="hidden" id="rm-subject-prefix" name="rm-subject-prefix" value="<?php echo $subject; ?>" />
	
	<?php if ($form_args['to']) : ?>
	<input type="hidden" id="rm-email-to" name="rm-email-to" value="<?php echo $form_args['to']; ?>" />
	<?php endif; ?>
	
	<div class="button_bar right">
	<a onclick="rm.submit();" class="btn red send"><div class="label">ENVIAR</div></a>
	</div>
</div>


<script type="text/javascript">
jQuery(document).ready(function() {
	var form_ops = {
		rules: {
			'rm-name': "required",
			'rm-email': {
				required: true,
				email: true
			},
			'rm-message': "required"
		},
		messages: {
			'rm-name': "<?php _e('Este campo es requerido.');?>",
			'rm-email': "<?php _e('Usa un correo válido');?>",
			'rm-message': "<?php _e('Este campo es requerido.');?>"
		}
	};
	jQuery('#rm-form').validate(form_ops);
});
<?php if ($_GET['rm-result'] == 1) : ?>
	function toggleForm() {
		jQuery('.msg-thankyou').hide();
		jQuery('.msg-help').show();
		jQuery('.form-body').show();
	}
	jQuery('.form-body').hide();
<?php endif; ?>
</script>

