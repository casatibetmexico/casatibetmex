<?php

/**
 * bbPress - Topic Archive
 *
 * @package bbPress
 * @subpackage Theme
 */

get_header(); ?>

	<div class="col side">
		<div class="placeholder login"><div>LOGIN</div></div>
		<div class="placeholder events"><div>PÁGINAS RELACIONADAS</div></div>
		<?php //get_sidebar(); ?>
	</div>

	<div class="col main">

	<?php do_action( 'bbp_before_main_content' ); ?>

	<?php do_action( 'bbp_template_notices' ); ?>

	<div id="topic-front" class="bbp-topics-front">
		<h1 class="entry-title"><?php bbp_topic_archive_title(); ?></h1>
		<div class="entry-content">

			<?php bbp_get_template_part( 'content', 'archive-topic' ); ?>

		</div>
	</div><!-- #topics-front -->

	<?php do_action( 'bbp_after_main_content' ); ?>

	</div>
	
<?php get_footer(); ?>
