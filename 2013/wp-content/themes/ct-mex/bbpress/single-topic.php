<?php

/**
 * Single Topic
 *
 * @package bbPress
 * @subpackage Theme
 */
 


get_header(); ?>

	<div class="col side">
		<?php ct_sidebar('login'); ?>
		<?php //get_sidebar(); ?>
	</div>

	<div class="col main">
	
	<div class="page_header">
		<?php dynamic_sidebar('ct_page_nav'); ?>
		<h1><?php echo str_replace(':', ':<br />', bbp_get_topic_title()); ?></h1>
	</div>
	
	<?php do_action( 'bbp_before_main_content' ); ?>

	<?php //do_action( 'bbp_template_notices' ); ?>

	<?php if ( bbp_user_can_view_forum( array( 'forum_id' => bbp_get_topic_forum_id() ) ) ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<div id="bbp-topic-wrapper-<?php bbp_topic_id(); ?>" class="bbp-topic-wrapper">
				<!-- <h1 class="entry-title"><?php bbp_topic_title(); ?></h1> -->
				<div class="entry-content">

					<?php bbp_get_template_part( 'content', 'single-topic' ); ?>

				</div>
			</div><!-- #bbp-topic-wrapper-<?php bbp_topic_id(); ?> -->

		<?php endwhile; ?>

	<?php elseif ( bbp_is_forum_private( bbp_get_topic_forum_id(), false ) ) : ?>

		<?php bbp_get_template_part( 'feedback', 'no-access' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_after_main_content' ); ?>
	
	</div>

<?php get_footer(); ?>
