<?php
global $sbar_args, $post;
?>
<div class="section">
	<div class="title">Apóyanos con tus donaciones</div>
	<p>Apoya los proyectos del Editorial Casa Tibet, fomenta la generosidad como parte integral de tu vida cotidiana y refuerza la cohesión con tu comunidad.</p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_xclick-subscriptions">
	<input type="hidden" name="business" value="editorial@casatibet.org.mx">
	<input type="hidden" name="item_name" value="<?php _e('Apoyo al Editorial Casa Tibet'); ?>">
	<table>
	<tbody><tr>
	<td><b>Opciones de Donativo:</b></td>
	</tr>
	<tr>
	<td>
	<select name="a3">
	<option value="100.00">Opción 1 : $100.00 MXN – mensual</option>
	<option value="300.00">Opción 2 : $300.00 MXN – mensual</option>
	<option value="600.00">Opción 3 : $600.00 MXN – mensual</option>
	</select>
	</td>
	</tr>
	</tbody></table>
	<p><input type="hidden" name="currency_code" value="MXN">
	<input type="hidden" name="t3" value="M"> <!-- billing cycle unit=month -->
	<input type="hidden" name="p3" value="1"> <!-- billing cycle length -->
	<input type="hidden" name="src" value="1"> <!-- recurring=yes -->
	<input type="hidden" name="sra" value="1"> <!-- reattempt=yes -->
	<input type="hidden" name="return" value="<?php bloginfo('siteurl');?>">
	<input type="hidden" name="cancel_return" value="<?php bloginfo('siteurl');?>">
	<input type="image" src="https://www.paypalobjects.com/es_XC/MX/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea."><br>
	<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1"><br>
	</p></form>
</div>
