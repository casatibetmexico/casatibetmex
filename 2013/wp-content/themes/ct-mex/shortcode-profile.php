<?php global $tArgs; ?>
<div class="profile">
	<div class="thumbnail"><?php echo ct_get_thumbnail($tArgs['atts']['image'], array(100,100,true));?></div>
	<h2><?php echo $tArgs['atts']['title']; ?></h2>
	<p><?php echo $tArgs['content']; ?></p>
</div>