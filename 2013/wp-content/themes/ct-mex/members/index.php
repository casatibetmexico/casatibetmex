<?php

/**
 * BuddyPress - Members Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
global $bp;
get_header(); ?>

	<div class="col side">
		<?php ct_sidebar('login'); ?>
		
		<div class="section">
			<div class="title"><?php _e('Búsqueda'); ?></div>
			<?php bp_directory_members_search_form(); ?>
			<script type="text/javascript">
				var btn = jQuery('#members_search_submit');
				var newBtn = jQuery('<button id="members_search_submit" name="'+btn.attr('name')+'" class="submit btn red right"><div class="label">'+btn.val()+'</div></div>');
				btn.replaceWith(newBtn);
			</script>
		</div>
	</div>
	<div class="col main">
		<div class="page_header member">
			<?php dynamic_sidebar('ct_page_nav'); ?>
			<div class="head_wrapper">
			<h1><?php _e('Miembros'); ?></h1>
			</div>
		</div>
		<div class="page_content">			

			<form action="" method="post" id="members-directory-form" class="dir-form">
	
				<div id="members-dir-list" class="members dir-list section">
	
					<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
	
				</div><!-- #members-dir-list -->
	
			</form><!-- #members-directory-form -->

			
		</div>
	</div>

<?php get_footer( ); ?>

	