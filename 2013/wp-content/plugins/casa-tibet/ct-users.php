<?php

/** USER CONTROL *************************************************************/

add_action( 'admin_init', 'ct_blockusers_init' );
function ct_blockusers_init() {

    if ( is_admin() && ! current_user_can( 'administrator' ) &&
       ! current_user_can('editor') &&
       ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    } else {
   		global $ct_center;
    	$u_id = get_current_user_id();
    	$ct_center = get_user_meta($u_id, 'ct_center', true);
    }
}

add_action( 'wp_login_failed', 'ct_login_failed' );
function ct_login_failed( $user ) {
  	$referrer = $_SERVER['HTTP_REFERER'];
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
		if ( !strstr($referrer, '?res=failed' )) {
	    	wp_redirect( $referrer . '?res=failed');
	    } else {
	      	wp_redirect( $referrer );
	    }
	    exit;
	}
}

add_filter('login_redirect', 'ct_login_redirect', 20, 3);
function ct_login_redirect( $redirect_to, $request, $user ){
    
    if( isset( $user->roles ) && is_array( $user->roles ) ) {
    	return ($redirect_to) ? $redirect_to : home_url();
    	/* DEACTIVATED */
        if( in_array( "administrator", $user->roles ) ||
        	in_array( "ct_instructor", $user->roles ) ||
        	in_array( "ct_coordinator", $user->roles ) ) {
        	$url = get_bloginfo('siteurl').'/wp-admin/index.php';
            return $url;
        } else {
            return ($redirect_to) ? $redirect_to : home_url();
        }
    } else {
      	return $redirect_to;
    }
}

function ct_apply_caps_to_role($role, $args=array()) {
	if ($args['types']) {
		foreach($args['types'] as $type)
		foreach($GLOBALS['wp_post_types'][$type]->cap as $cap) {
			$role->add_cap($cap);
		}
	}
	if ($args['access']) {
		foreach($args['access'] as $cap) {
			$role->add_cap($cap);
		}
	}
	if ($args['restrict']) {
		foreach($args['restrict'] as $cap) {
			$role->add_cap($cap);
		}
	}
}

function ct_add_theme_caps() {

	$u = wp_get_current_user();
	//$u->add_role('super_admin');
	//grant_super_admin($u->ID);
	
	/*
$u->remove_role('administrator');
	$u->add_role('administrator');
*/

	/** MAIN SITE ******************************************************************/

	/** WEBMASTERS **/
    $administrator = get_role('administrator');
    ct_apply_caps_to_role($administrator, 
    					  array('types'=>array('post', 'page', 'event', 'teacher', 
    					  					   'center', 'study-group', 'module', 'resource',
    					  					   'publication', 'course', 'cause', 'org'),
    					  		'access'=>array('manage_terms')));    
    					  		
    /** EDITOR : EDUCACION **/
    add_role('ct_editor_edu', 'CT Editor : Educación');
    $editor = get_role('ct_editor_edu');
    ct_apply_caps_to_role($editor, 
    					  array('types'=>array('post', 'page', 'course', 'study-group', 'module', 'resource'),
    					  		'access'=>array('editor', 'bbp_keymaster', 'bbp_moderator', 'bbp_participant', 'upload_files'),
    					  		'restrict'=>array()));					  				
    
    /** INSTRUCTORS **/
    remove_role('ct_instructor');
    /*
add_role('ct_instructor', 'CT Instructor');
    $instructor = get_role('ct_instructor');
    ct_apply_caps_to_role($instructor, 
    					  array('types'=>array('module', 'resource'),
    					  		'access'=>array('upload_files'),
    					  		'restrict'=>array('edit_centers', 'edit_study-groups', 'edit_courses', 'edit_pages'))); 
*/
    					  		
    
    					  		
    /** SANGHAS ********************************************************************/					  		
    					  		
    /** COORDINADOR DE CENTRO **/
    add_role('ct_coordinator', 'CT Coordinator');
    $coordinator = get_role('ct_coordinator');
    ct_apply_caps_to_role($coordinator, 
    					  array('types'=>array('post', 'page', 'event', 'center', 'course', 'study-group', 'cause', 'org'),
    					  		'access'=>array('editor', 'ct_facilitator', 'ct_member', 
    					  						'bbp_moderator', 'bbp_participant', 
    					  						'upload_files', 'edit_center'),
    					  		'restrict'=>array('edit_centers', 'edit_others_centers'))); 

    
    /** FACILITADOR DE GRUPOS **/
    add_role('ct_facilitator', 'CT Facilitator');
    $facilitator = get_role('ct_facilitator');
    ct_apply_caps_to_role($facilitator, 
    					  array('types'=>array('study-group'),
    					  		'access'=>array('author', 'edit_study_groups',
    					  						'bbp_participant'),
    					  		'restrict'=>array('edit_other_study-groups'))); 
    
    /** STUDENTS **/
    add_role('ct_member', 'CT Miembro');
    $member = get_role('ct_member');
    ct_apply_caps_to_role($member, 
    					  array('access'=>array('subscriber', 'read'))); 
    
}
add_action( 'admin_init', 'ct_add_theme_caps');


/** USER PROFILE *******************************************/

function ct_add_custom_user_profile_fields( $user ) {
	
	$center_id = get_the_author_meta( 'ct_center', $user->ID );
	$status = get_the_author_meta( 'ct_member_status', $user->ID );
	
	$args = array('post_type'=>'centro',
				  'order'=>'ASC',
				  'orderby'=>'title');
	$q = new WP_Query($args);
	
?>
    <h3><?php _e('Casa Tibet'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th>
                <label for="ct-center"><?php _e('Centro'); ?>
            </label></th>
            <td>
                <select name="ct-center" id="ct-center">
                	<option>-- El usuario no pertence a ningun centro --</option>
                <?php foreach($q->posts as $p) : ?>
                	<option <?php if ($p->ID == $center_id) echo 'selected="true"'; ?> value="<?php echo $p->ID;?>"><?php echo $p->post_title; ?></option>
                <?php endforeach; ?>
                </select><br />
                <span class="description"><?php _e('Eliges el centro a que esta usuario pertenece.'); ?></span>
            </td>
        </tr>
    </table>
<?php }
function ct_save_custom_user_profile_fields( $user_id ) {
    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;
    
    update_usermeta( $user_id, 'ct_center', $_POST['ct-center'] );
}
/*
add_action( 'show_user_profile', 'ct_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'ct_add_custom_user_profile_fields' );
add_action( 'personal_options_update', 'ct_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'ct_save_custom_user_profile_fields' );
*/

function grant_super_admin( $user_id ) {
	global $super_admins;

	// If global super_admins override is defined, there is nothing to do here.
	if ( isset($super_admins) )
		return false;

	do_action( 'grant_super_admin', $user_id );

	// Directly fetch site_admins instead of using get_super_admins()
	$super_admins = get_site_option( 'site_admins', array( 'admin' ) );

	$user = new WP_User( $user_id );
	if ( ! in_array( $user->user_login, $super_admins ) ) {
		$super_admins[] = $user->user_login;
		update_site_option( 'site_admins' , $super_admins );
		do_action( 'granted_super_admin', $user_id );
		return true;
	}
	return false;
}

/** FILTERS *******************************************************/

function ct_user_posts_by_center($query) {
	if ($query->is_admin && !current_user_can('administrator') &&
		current_user_can('editor')) {
	
		global $user_ID, $ct_site;
		$type = $query->query_vars['post_type'];
		
		switch($type) {
			case 'banner':
			case 'post':
			case 'page':
				$tax_query = array(
									array(
									'taxonomy' => 'site',
									'field' => 'slug',
									'terms' => ct_site_get_current_admin()
									)
								);
				$query->set('tax_query', $tax_query);
				break;
		}
		
		
	}
	
	return $query;
}
add_filter('pre_get_posts', 'ct_user_posts_by_center');

if (is_admin()) :
	function ct_user_remove_meta_boxes() {
		if (!current_user_can('administrator')) {
			if(current_user_can('editor') ) {
				
				/** BANNERS **/
				remove_meta_box('sitediv', 'banner', 'side');
				
				/** POSTS **/
				remove_meta_box('sitediv', 'post', 'side');
				remove_meta_box('categorydiv', 'post', 'side');
				remove_meta_box('tagsdiv-post_tag', 'post', 'side');
				remove_meta_box('ct_ctr_content', 'post', 'side');
				
				/** PAGES **/
				remove_meta_box('page_categorydiv', 'page', 'side');
				
				/** CENTER **/
				if (current_user_can('ct_coordinator')) {
					remove_meta_box('countrydiv', 'center', 'side');
					remove_meta_box('center-typediv', 'center', 'side');
					remove_meta_box('ct_ctr_community', 'center', 'side');
				}
				
				/** PAGES **/
				if (current_user_can('ct_coordinator')) {
					remove_meta_box('pageparentdiv', 'page', 'side');
				}
				
				/** STUDY GROUPS **/
				remove_meta_box('ct_study_center', 'study-group', 'side');
				
				
				
		 	}
		}		
	}
	add_action( 'admin_menu', 'ct_user_remove_meta_boxes', 20);
	
	
	function ct_hide_add_buttons() {
		  global $pagenow, $post_type;
		  
		  switch($post_type) {
		  	case 'center':
		  		if (current_user_can('ct_coordinator')) {
			  		if($pagenow == 'post.php') {
						echo '<style type="text/css">.add-new-h2{display: none;}</style>';
					} 
			  	}
		  		break;
		  }
	}
	add_action('admin_head','ct_hide_add_buttons');
	
	function ct_user_save_postdata( $post_id ) {
	
		global $post_type;
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	    	return;
	    	
	    if (!current_user_can('administrator')) {
	    	if (current_user_can('editor')) {
	    		
	    		if (current_user_can('ct_editor_edu')) {
	    		
	    			$term = term_exists(ct_site_get_current_admin(), 'site');
		    		wp_set_post_terms($post_id, array( $term['term_id'] ), 'site');	
		    		update_post_meta($post_id, 'ct_center', 0);
	    		
	    			switch($post_type){
	    				case 'post':
	    					$term = term_exists('noticias', 'category');
	    					wp_set_post_terms($post_id, array( $term['term_id'] ), 'category');	
	    					break;
	    				case 'page':
	    					$term = term_exists('cat-educacion', 'page_category');
	    					wp_set_post_terms($post_id, array( $term['term_id'] ), 'page_category');
	    					break;
	    			}	
	    			
	    		} else if (current_user_can('ct_coordinator')) {
	    			
	    			$term = term_exists(ct_site_get_current_admin(), 'site');
		    		wp_set_post_terms($post_id, array( $term['term_id'] ), 'site');	
		    		
		    		
		    		$ctr = ct_get_user_center();
		    		update_post_meta($post_id, 'ct_center', $ctr);	
		    		
		    		switch($post_type){
		    			case 'post':
	    					$term = term_exists('noticias', 'category');
	    					wp_set_post_terms($post_id, array( $term['term_id'] ), 'category');	
	    					break;
	    				case 'page':	    					
	    					/*
remove_action('save_post', 'ct_user_save_postdata');
	    					$page = get_page_by_path('/nosotros');
							if ($page->ID) wp_update_post(array('ID' => $post_id, 'post_parent' => $page->ID));
							add_action('save_post', 'ct_user_save_postdata');
*/
	    					
	    					$term = term_exists('sanghas', 'page_category');
	    					wp_set_post_terms($post_id, array( $term['term_id'] ), 'page_category');	
	    					break;
	    			}    			
	    		}
	    		
	    	}
	    }
		
	}
	add_action( 'save_post', 'ct_user_save_postdata' );
	
endif;

/** API FUNCTIONS *************************************************/

function ct_user_get_users_for_center($center) {
	$args = array('meta_key'=>'ct_center', 'meta_value'=>$center);
	return get_users($args);
}
function ct_user_get_role_in_center($center, $role) {
	$args = array('role'=>$role, 'meta_key'=>'ct_center', 'meta_value'=>$center);
	return get_users($args);
}

?>