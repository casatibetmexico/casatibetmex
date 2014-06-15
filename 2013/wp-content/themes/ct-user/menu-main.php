<?php global $ob; ?>
<ul id="ct_nav_menu"></ul>
<script type="text/javascript">jQuery("#ct_nav_menu").navMenu(<?php echo json_encode($ob); ?>, 
															  {no_caps:true, no_separator:true});</script>
