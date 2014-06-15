<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

global $bp;
get_header(); ?>

	<div class="col side">
		<?php ct_sidebar('login'); ?>
		<!--
<div class="section">
			<div class="title">Páginas Relacionadas</div>
			<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
				<ul class="listing pages">
					<?php foreach($bp->bp_nav as $item) :?>
						<li data-href="<?php echo $item['link']; ?>"><div class="icon view"></div>
							<?php echo $item['name']; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
-->		
		
		
	</div>
	<div class="col main">
		<div class="page_header member">
			<?php 
			$parts = array('home');
			$parts[] = $post;
			ct_breadcrumbs($parts, $bp->displayed_user);
			?>
			<div class="head_wrapper">
			<div class="thumbnail">
				<?php bp_displayed_user_avatar( 'type=full' ); ?>
			</div>
			<h1><?php bp_displayed_user_fullname();?></h1>
			<p class="user_subtitle">
			<!-- <span class="user-nicename">@<?php bp_displayed_user_username(); ?></span> -->
			<span class="activity"><?php bp_last_activity( bp_displayed_user_id() ); ?></span>
			</p>
				<?php if ( bp_is_my_profile() ) : ?>
				<div id="item-nav">
					<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
						<ul>
							<?php bp_get_options_nav(); ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="page_content">			

			<div id="item-body">
			
				<?php //do_action( 'bp_before_member_body' );

				if ( bp_is_user_activity() || !bp_current_component() ) :
					locate_template( array( 'members/single/activity.php'  ), true );

				 elseif ( bp_is_user_blogs() ) :
					locate_template( array( 'members/single/blogs.php'     ), true );

				elseif ( bp_is_user_friends() ) :
					locate_template( array( 'members/single/friends.php'   ), true );

				elseif ( bp_is_user_groups() ) :
					locate_template( array( 'members/single/groups.php'    ), true );

				elseif ( bp_is_user_messages() ) :
					locate_template( array( 'members/single/messages.php'  ), true );

				elseif ( bp_is_user_profile() ) :
					locate_template( array( 'members/single/profile.php'   ), true );

				elseif ( bp_is_user_forums() ) :
					locate_template( array( 'members/single/forums.php'    ), true );

				elseif ( bp_is_user_settings() ) :
					locate_template( array( 'members/single/settings.php'  ), true );

				// If nothing sticks, load a generic template
				else :
					locate_template( array( 'members/single/plugins.php'   ), true );

				endif;

				//do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php //do_action( 'bp_after_member_home_content' ); ?>
		</div>
	</div>

<?php //get_sidebar( 'buddypress' ); ?>
<?php get_footer( ); ?>
