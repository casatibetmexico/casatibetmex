<?php

/**
 * Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

get_header(); ?>

	<div class="col side">
		<?php ct_sidebar('login'); ?>
		<div class="spacer"></div>
		
		<?php //get_sidebar(); ?>
	</div>

	<div class="col main forum">
	
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php echo str_replace(':', ':<br />', bbp_get_forum_title()); ?></h1>
	</div>

	<?php do_action( 'bbp_before_main_content' ); ?>

	<?php do_action( 'bbp_template_notices' ); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( bbp_user_can_view_forum() ) : ?>

			<div id="forum-<?php bbp_forum_id(); ?>" class="bbp-forum-content">
				<!-- <h1 class="entry-title"><?php bbp_forum_title(); ?></h1> -->
				<div class="entry-content">
					<?php bbp_get_template_part( 'content', 'single-forum' ); ?>

				</div>
			</div><!-- #forum-<?php bbp_forum_id(); ?> -->

		<?php else : // Forum exists, user no access ?>

			<?php bbp_get_template_part( 'feedback', 'no-access' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php do_action( 'bbp_after_main_content' ); ?>

	</div>
<?php get_footer(); ?>
