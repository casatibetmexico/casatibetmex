<?php global $tArgs; ?>
<div class="quote">
<p><?php echo nl2br($tArgs['content']); ?></p>
<?php if ($tArgs['atts']['author']) : ?>
<p class="author">&mdash; <?php echo $tArgs['atts']['author']; ?> &mdash;</p>
<?php endif; ?>
</div>