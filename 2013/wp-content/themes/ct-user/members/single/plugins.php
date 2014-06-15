<?php

/**
 * BuddyPress - Users Plugins
 *
 * This is a fallback file that external plugins can use if the template they
 * need is not installed in the current theme. Use the actions in this template
 * to output everything your plugin needs.
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

global $bp;
get_header(); ?>

	<div class="col side">
		<?php ct_sidebar('login'); ?>
		<div class="section">
			<div class="title">Páginas Relacionadas</div>
			<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
				<ul class="listing pages">
					<?php foreach($bp->bp_nav as $item) :?>
						<li data-href="<?php echo $item['link']; ?>"><div class="icon view"></div>
							<?php echo __($item['name'], 'buddypress'); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>		
		
		
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
			<span class="user-nicename">@<?php bp_displayed_user_username(); ?></span>
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
		<div class="">			
			<?php do_action( 'bp_template_content' ); ?>			
		</div>
	</div>

<?php //get_sidebar( 'buddypress' ); ?>
<?php get_footer( ); ?>

