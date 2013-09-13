<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
$meta = get_post_custom($post->ID);
?>

<?php get_header();?>
	
<div class="col side sidebar">
	<?php ct_sidebar('login'); ?>
	
	<?php
	$g_id = get_post_meta($post->ID, 'ct_group_id', true);
	$group = new BP_Groups_Group($g_id);
	$members = groups_get_group_members( $g_id, false, false, true, false, array(get_current_user_id()));
	?>
	<?php if ($members) : ?>
	<div class="section">
		<div class="title"><?php _e('Miembros'); ?></div>
		<ul class="listing pages">
			<?php foreach((array) $members['members'] as $u) : ?>
				<?php 
				$perma = apply_filters( 'bp_get_member_permalink', 
										bp_core_get_user_domain( $u->user_id, $u->display_name, $u->user_name) );
				?>
				<li data-href="<?php echo $perma; ?>">
					<div class="icon view"></div>
					<div class="left">
					<?php echo bp_core_fetch_avatar( 'item_id='.$u->user_id.'&width=25&height=25' ); ?>
					</div>
					<div style="padding-top:5px;padding-left:35px;"><?php echo $u->display_name; ?></div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>
<div class="col main">
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php the_title(); ?></h1>
	</div>
	<div class="page_content event">
		<div class="details">
			<table>
				<tr>
					<td>Fecha:</td>
					<td><?php echo $period; ?></td>
				</tr>
				<?php if ($location['name'] || $location['address']): ?>
				<tr>
					<td>Lugar:</td>
					<td><?php if ($location['name']) : ?><strong><?php echo $location['name']; ?></strong><br /><?php endif; ?>
					<?php if ($location['address']) : ?><?php echo $location['address']; ?><?php endif; ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($tickets['price']) : ?>
				<tr>
					<td>Precio:</td>
					<td><?php echo $tickets['price']; ?></td>
				</tr>
				<?php endif; ?>
			</table>
		 </div>
	</div>
</div>
<?php get_footer();?>
<?php endwhile; endif; ?>