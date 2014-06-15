<?php 
$domains = ct_site_get_domains(true);
?>
<div class="menu">
	<ul>
		<li data-href="<?php echo $domains['ct-mex'].'/2013/wp-admin'; ?>">ADMIN</li>
		<li data-href="<?php echo $domains['ct-mex'].'/realimentacion'; ?>">REALIMENTACIÓN</li>
			<li data-href="<?php echo $domains['ct-mex'].'/nosotros/preguntas-frecuentes'; ?>">PREGUNTAS FRECUENTES</li>
	</ul>
</div>
<div class="copyright">
<h2><?php bloginfo('name');?></h2>
<p>Orizaba # 93, Roma norte, Del. Cuauhtemoc, México D.F. CP. 06700,<br />
Tels. 55147763 • 55110802 • 55140443 info@casatibet.org.mx</p>
</div>