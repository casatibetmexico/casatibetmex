<?php

/**
 * bbPress - Forum Archive
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

	<div id="forum-front" class="bbp-forum-front">
		<h1 class="entry-title"><?php bbp_forum_archive_title(); ?></h1>
		<div class="entry-content">

			<?php bbp_get_template_part( 'content', 'archive-forum' ); ?>

		</div>
	</div><!-- #forum-front -->

	<?php do_action( 'bbp_after_main_content' ); ?>

	</div>
	
<?php get_footer(); ?>
