<?php
global $sbar_args;

$config = apply_filters('ct_archive-'.$sbar_args['post_type'], $sbar_args);

if ($config) :

if ($config['query']) {
	$q = $config['query'];
} else {
	$q = new WP_Query($config['args']);
}

$items = $q->posts;

?>
<div class="section listing <?php if ($sbar_args['listing']) echo $sbar_args['listing']; ?>">
<?php if (!$sbar_args['no_title']) : ?>
<div class="title" <?php if ($sbar_args['href']) : ?>data-href="<?php echo $sbar_args['href']; ?>"<?php endif; ?>>
<?php ct_pagination($q); ?>
<?php echo $config['title']; ?></div>
<?php endif; ?>
<?php if (count($items) > 0) : ?>
<ul <?php if ($sbar_args['no_title']) echo 'class="no_title"';?>>
<?php ct_listing($sbar_args['listing'], $items); ?>
</ul>
<?php else : ?>
	<p class="intro"><?php echo $config['no_results'];?></p>
<?php endif; ?>
</div>

<?php endif; ?>