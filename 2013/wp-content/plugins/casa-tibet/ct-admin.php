<?php

/** INIT **********************************************************/

add_action('admin_enqueue_scripts', 'ct_admin_enqueue_scripts', 0);
function ct_admin_enqueue_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_media();
	wp_enqueue_script('ct-admin', plugins_url('casa-tibet').'/js/ct-admin.js', array('jquery'));
	wp_enqueue_style('ct-admin-style', plugins_url('casa-tibet').'/ct-admin.css');
	wp_enqueue_script('ct-functions', plugins_url('casa-tibet').'/js/ct_functions.js', array('jquery'));
}

add_action('admin_head', 'ct_admin_head');
function ct_admin_head() {
?>
<script type="text/javascript">
	ct_siteurl('<?php bloginfo('siteurl');?>');
</script>
<?php
}

/** REMOVE ADMIN BAR FROM SITE ************************************/

remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
function remove_admin_bar_style_frontend() {   
  echo '<style type="text/css" media="screen"> 
  html { margin-top: 0px !important; } 
  * html body { margin-top: 0px !important; } 
  </style>';  
}  
add_filter('wp_head','remove_admin_bar_style_frontend', 99); 

/** DASHBOARD PAGES *************************************************/

add_action('admin_menu', 'ct_admin_menu');
function ct_admin_menu() {
	
	add_menu_page('Welcome to Casa Tibet Mexico Online', 'Casa Tibet Mexico', 'read', 'ct-dashboard', 'ct_dashboard_screen');
	
	add_menu_page('Administración de Eventos', 'Admin Eventos', 'read', 'ct-events-admin', 'ct_events_admin_screen');
	
	if (current_user_can('ct_coordinator')) {
	
		$u = wp_get_current_user();
		$center = get_user_meta($u->ID, 'ct_center', true);
	
		add_menu_page('Administración de Miembros', 'Admin Miembros', 'read', 'ct-center-admin', 'ct_center_admin_screen');
		add_menu_page('Administración de Cursos', 'Admin Cursos', 'read', 'ct-course-admin', 'ct_course_admin_screen');
		add_dashboard_page('Editar Sede', 'Editar Sede', 'ct_coordinator', 'post.php?post='.$center.'&action=edit');
	}
	
	if (current_user_can('ct_instructor')) {
		add_menu_page('Administración de Materiales Académicos', 'Admin Materiales', 'read', 'ct-edu-admin', 'ct_edu_admin_screen');
	}
	
	if (current_user_can('administrator')) {
		add_menu_page('Administración de Usuarios', 'Admin Users', 'read', 'ct-user-admin', 'ct_user_admin_screen');
		add_menu_page('Administración de Centros', 'Admin Centers', 'read', 'ct-centers-admin', 'ct_centers_admin_screen');
	}
	
		
 
    remove_menu_page('ct-dashboard');
    remove_menu_page('ct-center-admin');
    remove_menu_page('ct-course-admin');
    remove_menu_page('ct-edu-admin');
    remove_menu_page('ct-user-admin');
    remove_menu_page('ct-centers-admin');
    remove_menu_page('ct-events-admin');
}

function ct_dashboard_screen() {
	global $wp_rewrite;

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Bienvenido a Casa Tibet Mexico en Linea'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			

			<?php ct_admin_tabs('ct-dashboard'); ?>

		</div>

		<?php
}

function ct_center_admin_screen() {
	global $wp_rewrite;
	
	$user = wp_get_current_user();
	$center = ct_get_center_for_user($user->ID);

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Administración de Miembros'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			

			<?php ct_admin_tabs('ct-center-admin'); ?>
		</div>

		<?php
}

function ct_edit_center_screen() {
	
}

function ct_course_admin_screen() {
	global $wp_rewrite;
	
	$user = wp_get_current_user();
	$center = ct_get_center_for_user($user->ID);

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Administración de Cursos'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			
			<?php ct_admin_tabs('ct-course-admin'); ?>
		</div>

		<?php
}

function ct_edu_admin_screen() {
	global $wp_rewrite;
	
	$user = wp_get_current_user();
	$center = ct_get_center_for_user($user->ID);

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Administración de Materiales Académicos'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			
			<?php ct_admin_tabs('ct-edu-admin'); ?>

		</div>

		<?php
}

function ct_user_admin_screen() {
	global $wp_rewrite;
	
	$user = wp_get_current_user();
	$center = ct_get_center_for_user($user->ID);

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Administración de Usuarios'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			
			<?php ct_admin_tabs('ct-user-admin'); ?>

		</div>

		<?php
}

function ct_centers_admin_screen() {
	global $wp_rewrite;
	
	$user = wp_get_current_user();
	$center = ct_get_center_for_user($user->ID);

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Administración de Centros'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			
			<?php ct_admin_tabs('ct-centers-admin'); ?>

		</div>

		<?php
}

function ct_events_admin_screen() {
	global $wp_rewrite;

?>

		<div class="wrap about-wrap">
			<div style="float:right;">
				<img src="<?php echo plugins_url( 'img/logo-casa-tibet.png' , __FILE__ );?>" />
			</div>
			<h1><?php echo 'Administración de Eventos'; ?></h1>
			<div class="about-text">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac odio vel neque condimentum pretium. Sed aliquet, nisl vel tempus dictum, dui arcu vulputate nunc, non aliquet tortor neque at sem.</p>
			</div>
			
			<?php ct_admin_tabs('ct-events-admin'); ?>

		</div>

		<?php
}

function ct_admin_tabs($active) {
?>
<h2 class="nav-tab-wrapper">
	<a class="nav-tab <?php if ($active == 'ct-dashboard') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-dashboard' ), 'index.php' ) )	; ?>">
		¿Qué hay de nuevo?
	</a>
	
	<?php if (current_user_can('ct_coordinator')) : ?>
	<a class="nav-tab <?php if ($active == 'ct-center-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-center-admin' ), 'index.php' ) )	; ?>">
		Miembros
	</a>
	<a class="nav-tab <?php if ($active == 'ct-course-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-course-admin' ), 'index.php' ) )	; ?>">
		Cursos
	</a>
	<a class="nav-tab <?php if ($active == 'ct-events-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-events-admin' ), 'index.php' ) )	; ?>">
		Eventos
	</a>
	<?php endif; ?>
	
	<?php if (current_user_can('ct_instructor')) : ?>
	<a class="nav-tab <?php if ($active == 'ct-edu-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-edu-admin' ), 'index.php' ) )	; ?>">
		Materiales
	</a>
	<?php endif; ?>
	
	<?php if (current_user_can('administrator')) : ?>
	<a class="nav-tab <?php if ($active == 'ct-user-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-user-admin' ), 'index.php' ) )	; ?>">
		Usuarios
	</a>
	<a class="nav-tab <?php if ($active == 'ct-centers-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-centers-admin' ), 'index.php' ) )	; ?>">
		Centros
	</a>
	<a class="nav-tab <?php if ($active == 'ct-events-admin') echo 'nav-tab-active'; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'ct-events-admin' ), 'index.php' ) )	; ?>">
		Eventos
	</a>
	<?php endif; ?>
</h2>
<?php 
}


function ct_custom_dashboard() {
	global $pagenow;
    if($pagenow == 'index.php' && empty($_REQUEST['page']) && empty($_REQUEST['action'])) {
       	wp_redirect(admin_url('index.php?page=ct-dashboard'));
        exit;
    }
}
add_action('admin_init', 'ct_custom_dashboard');

function my_admin_menu() {

    add_menu_page('Membership Applications', 'Reviews', 'review_members', 'manage-applications', $page_handler);

    global $submenu_file, $pagenow, $menu;

    $menu[2][0] = __('Applications');

    if(current_user_can('plugin_user_role')) {
        if(@$_GET['page'] == 'manage-applications') {
            $submenu_file = 'index.php';
            $pagenow = 'index.php';
        }
    }

    remove_menu_page('manage-applications');
}

?>