<?php 
global $sbar_args;
?>

<div class="title">Actividades Recientes</div>
<?php bbp_get_template_part( 'content', 'archive-topic' ); ?>
<script type="text/javascript">
	jQuery('#bbpress-forums .sticky').removeClass('sticky');
</script>