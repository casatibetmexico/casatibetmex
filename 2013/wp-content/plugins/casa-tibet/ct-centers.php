<?php

/** CUSTOM POST ***************************************************************/

add_action('init', 'ct_center_init');
function ct_center_init() {
	

register_post_type('center', array(	'label' => 'Centros', 'description' => '','public' => true,'show_ui' => true,'show_in_menu' => true,'capability_type' => 'center', 'map_meta_cap' => true, 'hierarchical' => false,'rewrite' =>true,'query_var' => 'center','has_archive' => false, 'supports' => array('title', 'editor'),'labels' => array (
  'name' => 'Sedes',
  'singular_name' => 'Sede',
  'menu_name' => 'Sedes',
  'add_new' => 'Agregar Sede',
  'add_new_item' => 'Agregar Nuevo Sede',
  'edit' => 'Editar',
  'edit_item' => 'Editar Sede',
  'new_item' => 'Nuevo Sede',
  'view' => 'Ver Sede',
  'view_item' => 'Ver Sede',
  'search_items' => 'Buscar Sede',
  'not_found' => 'No Hay Sedes',
  'not_found_in_trash' => 'No hay Sedes en la Papelera',
  'parent' => 'Sede Superior',
),) );

register_taxonomy('country',array('center'),array( 'hierarchical' => true, 'label' => 'País','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'País') );

register_taxonomy('center-type',array('center'),array( 'hierarchical' => true, 'label' => 'Tipo de Sede','show_ui' => true,'query_var' => true,'rewrite' => true, 'singular_label' => 'Tipo de Sede') );

register_taxonomy('region',array('center'),array( 'hierarchical' => true, 'label' => 'Región','show_ui' => true,'query_var' => false,'rewrite' => false, 'singular_label' => 'Región') );

ct_get_current_center();

 
}

/* COLUMNS *********************************************************************/

add_filter("manage_edit-center_columns", "ct_ctr_edit_columns"); 
function ct_ctr_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "Sede",
    "country" => "País",
    "type" => "Tipo",
    "users" => "Miembros"
  );
 
  return $columns;
}

add_action("manage_center_posts_custom_column",  "ct_ctr_custom_columns");
function ct_ctr_custom_columns($column){
  global $post;
 
  switch ($column) {
  	case "country":
  		$terms = array_values(wp_get_post_terms($post->ID, 'country'));
    	if ($terms) {
    		$link = add_query_arg('country', $terms[0]->slug);
			echo '<a href="'.$link.'">'.$terms[0]->name.'</a>';
    	}
  		break;
  	case "type":
  		$terms = array_values(wp_get_post_terms($post->ID, 'center-type'));
    	if ($terms) {
    		$link = add_query_arg('center-type', $terms[0]->slug);
			echo '<a href="'.$link.'">'.$terms[0]->name.'</a>';
    	}
  		break;
    case "users":
      	$users = ct_user_get_users_for_center($post->ID);
      	echo count($users);
      	
      	break;
  }
}

/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_ctr_add_meta_boxes' );
function ct_ctr_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'center':  
						
			// Setup contact info for locations
			add_meta_box( 
		        'ct_ctr_locs', 'Ubicaciones', 'ct_ctr_meta_locations',
		        'center', 'normal'
		    ); 
		     
		    if (get_post_meta($post->ID, 'ct_group_id', true)) {
		    	add_meta_box( 'ct_ctr_add_members', 'Añadir Nuevos Miembros', 'ct_ctr_meta_add_member', 
		    			  'center', 'normal' ); 
			    add_meta_box( 
			        'ct_ctr_members', 'Miembros', 'ct_ctr_meta_members',
			        'center', 'normal' 
			    );
			    add_meta_box( 
			        'ct_ctr_admin', 'Roles y Papeles', 'ct_ctr_meta_admin',
			        'center', 'side' 
			    );
		    }
		    
		    add_meta_box( 
		        'ct_ctr_info', 'Información General', 'ct_ctr_meta_info',
		        'center', 'side', 'core'
		    );
		    
		    // Configure DomainTheme 
			add_meta_box(
				'ct_ctr_site', 'Configuración de Micro-Sitio', 'ct_ctr_meta_site',
		        'center', 'side', 'core'
			);
		    
		    if (current_user_can('administrator')) {
		    	add_meta_box( 
			        'ct_ctr_community', 'Comunidad', 'ct_ctr_meta_community',
			        'center', 'side' 
			    );
		    }
		    
		    
			break;
		case 'page':
			if (current_user_can('administrator')) {
				add_meta_box( 
			        'ct_ctr_content', 'Sede', 'ct_ctr_meta_attach_to_center',
			        $postType, 'side', 'core'
			    );
			}	
			if (current_user_can('ct_coordinator')) {
				add_meta_box( 
			        'ct_ctr_page_options', 'Opciones', 'ct_ctr_meta_page_options',
			        $postType, 'side', 'core'
			    );
			}
			break;
		case 'post':
		case 'resource':
		case 'banner':
			if (current_user_can('administrator')) {
				add_meta_box( 
			        'ct_ctr_content', 'Sede', 'ct_ctr_meta_attach_to_center',
			        $postType, 'side', 'core'
			    );
			}			
			break;
	}
}

function ct_ctr_meta_page_options($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	$show_menu = get_post_meta($post->ID, 'ct_page_show_menu', true);
	$sections = array();
	$sections[] = get_page_by_path('/nosotros');
	$sections[] = get_page_by_path('/programa');
?>
	<div id="ct_page_options">
	<p><strong>Seccion:</strong><br />
	<select name="parent_id">
		<?php foreach($sections as $s) : ?>
		<option value="<?php echo $s->ID; ?>"><?php echo $s->post_title;?></option>
		<?php endforeach; ?>
	</select></p>
	<p><input type="checkbox" name="ct_page_show_menu" value="1" />&nbsp;¿Incluir en menu?</p>
	<p><strong>Orden:</strong><br />
	<input type="text" name="menu_order" value="<?php echo $post->menu_order;?>" style="width:100px;"/></p>
	</div>
	<script type="text/javascript">
		<?php if ($show_menu) : ?>
			jQuery('#ct_page_options input:eq(0)').attr('checked', true);
		<?php endif; ?>
		jQuery('select[name=parent_id]').val(<?php echo $post->post_parent; ?>);
	</script>
<?php
}

function ct_ctr_meta_community($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	
	$use_community = get_post_meta($post->ID, 'ct_center_use_community', true);
	
	$group_id = get_post_meta($post->ID, 'ct_group_id', true);
	if ($group_id) {
		$group = new BP_Groups_Group($group_id);	
		$forum_id = (array) groups_get_groupmeta( $group_id, 'forum_id');
		$forum = get_post($forum_id[0]);
	}
?>
	<p><input type="checkbox" name="ct_center_use_community" value="1" />&nbsp;¿Activa grupos y foros?</p>
	<div class="community_info" style="display:none;">
		<p><b>Grupo de Usuarios:</b><br />
		<a href="<?php echo admin_url('admin.php?page=bp-groups&gid='.$group_id.'&action=edit'); ?>"><?php echo $group->name; ?></a></p>
		<p></p><b>Foro:</b><br />
		<a href="<?php echo admin_url('post.php?post='.$forum_id.'&action=edit'); ?>"><?php echo $forum->post_title; ?></a></p>
	</div>
	<script type="text/javascript">
		var use_community = jQuery('input[name=ct_center_use_community]');
		var community_info = jQuery('.community_info');
		<?php if ($use_community || ($group_id && $forum_id)) : ?>
			use_community.attr('checked', true);
			community_info.show();
		<?php endif; ?>
		use_community.click(function() {
			if(jQuery(this).is(':checked')) {
				community_info.show();
			} else {
				community_info.hide();
			}
		});
	</script>
	<?php
}

function ct_ctr_meta_attach_to_center($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	$centers = ct_ctr_get_all_centers();
	$center = get_post_meta($post->ID, 'ct_center', true);
	
	$u = wp_get_current_user();
	if (!$center) $center = get_user_meta($u->ID, 'ct_center', true);
?>
	
	<select id="ct_center" name="ct_center" style="width:100%;">
		<option value="0">Casa Tibet México (Nacional)</option>
<?php foreach((array) $centers as $c) : ?>
		<option value="<?php echo $c->ID; ?>"><?php echo $c->post_title; ?></option>
<?php endforeach; ?>
	</select>
	<script type="text/javascript">
		jQuery('#ct_center').val('<?php echo (int) $center; ?>');
		<?php if (!current_user_can('administrator')) : ?>
		jQuery('#ct_center').attr('disabled', "true");
		<?php endif; ?>
	</script>
	<?php if (!current_user_can('administrator')) : ?>
		<input type="hidden" name="ct_center" value="<?php echo $center; ?>" />
	<?php endif; ?>

	
	
<?php
	
}

function ct_ctr_meta_info($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	$info = get_post_meta($post->ID, 'ct_center_info', true);
	$active = get_option('ct_active_centers');
?>
	
	<p><input id="is_active" type="checkbox" name="ct_center_active" value="1" />&nbsp; Activar este sede.</p>
	
	<b>Página de Internet:</b><br />
	<input type="text" name="ct_center_info[website]" value="<?php echo $info['website']; ?>" style="width:100%;margin-bottom:5px;"/><br />
	<b>Facebook:</b><br />
	<input type="text" name="ct_center_info[facebook]" value="<?php echo $info['facebook']; ?>" style="width:100%;margin-bottom:5px;"/><br />
	<b>Twitter:</b><br />
	<input type="text" name="ct_center_info[twitter]" value="<?php echo $info['twitter']; ?>" style="width:100%;margin-bottom:5px;"/><br />
	<script type="text/javascript">
		<?php if (in_array($post->ID, $active)) : ?>
		jQuery('#is_active').attr('checked', true);
		<?php endif; ?>
	</script>
<?php
}

function ct_ctr_meta_site($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	$domain = get_post_meta($post->ID, 'ct_center_domain', true);
	$description = get_post_meta($post->ID, 'ct_center_description', true);
?>
	<b>Sub-Dominio:</b><br />
	<input type="text" name="ct_center_domain" value="<?php echo $domain; ?>" style="width:100%;margin-bottom:5px;"/><br />
	<b>Sub-Título:</b><br />
	<input type="text" name="ct_center_description" value="<?php echo $description; ?>" style="width:100%;margin-bottom:5px;"/>
	<?php if (!current_user_can('administrator')) : ?>
	<script type="text/javascript">
		jQuery('input[name=ct_center_domain]').attr('disabled', 'true');
	</script>
	<input type="hidden" name="ct_center_domain" value="<?php echo $domain; ?>" />
	<?php endif;?>
<?php
}

function ct_ctr_meta_locations($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	$locations = unserialize(get_post_meta($post->ID, 'ct_center_locations', true));
	$primary_loc = get_post_meta($post->ID, 'ct_center_primary_loc', true);
?>
	<div id="location_admin" class="ct_ctr_loc_admin">
		<div class="actions">
			<a class="button" data-action="add">Agregar nueva ubicación...</a>
		</div>
		<div class="form">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2">
					<b>Nombre:</b><br />
					<input id="loc_name" type="text" style="width:99.5%;" />
					</td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
					<b>Dirección 1:</b><br />
					<input id="loc_address_1" type="text" style="width:99.5%;" />
					</td>
					<td colspan="2">
					<b>Dirección 2:</b><br />
					<input id="loc_address_2" type="text" style="width:99.5%;" /><br />
					</td>
				</tr>
				<tr>
					<td><b>Teléfono/s:</b><br /><input id="loc_tel_1" type="text" style="width:99%;" /></td>
					<td><input id="loc_tel_2" type="text" style="width:99%;" /></td>
					<td><input id="loc_tel_3" type="text" style="width:99%;" /></td>
					<td><b>Email:</b><br /><input id="loc_email" type="text" style="width:99%;" /></td>
				</tr>
				<tr>
					<td colspan="4">
					<b>Horarios:</b><br />
					<input id="loc_schedule" type="text" style="width:99.5%;" />
					</td>
				</tr>
				<tr>
					<td colspan="4">
					<b>Mapa:</b><br />
					<input id="loc_map" type="text" style="width:99.5%;" />
					</td>
				</tr>
				<tr>
					<td colspan="2" align="left" style="padding-top:10px;vertical-align:middle;">
						<input id="make_primary" type="checkbox" />&nbsp; Esta ubicación es la primaria.
					</td>
					<td colspan="2" align="right" style="padding-top:10px;">
						<input id="loc_id" type="hidden" />
						<a class="button" data-action="cancel">CANCELAR</a>&nbsp;
						<a class="button button-primary" data-action="save">GUARDAR</a>
					</td>
				</tr>
				
			</table>
		</div>
		<ul class="listing"></ul>
		<input id="primary_loc" type="hidden" name="ct_center_primary_loc" value="<?php echo $primary_loc; ?>" />
	</div>
	<script type="text/javascript">
		CTLocationAdmin.init('#location_admin', 'ct_center_locations', <?php echo json_encode($locations); ?>);
	</script>
<?php
}

function ct_ctr_meta_add_member( $item ) {
	?>
	<input name="ct_ctr_new_members" class="suggest_user" placeholder="<?php _e('Introducir una lista de emails, separada por comas.') ?>" />
	<?php
}

function ct_ctr_meta_members($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	
	/*
$members = get_post_meta($post->ID, 'ct_members', true);
	$users  = ct_user_get_users_for_center($post->ID);
*/
	
	$g_id = get_post_meta($post->ID, 'ct_group_id', true);
	$group = new BP_Groups_Group($g_id);
	
	$members = groups_get_group_members( $g_id, false, false, false, false, array($group->creator_id) );
		
?>
	<table class="member_listing" width="100%" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td width="25">&nbsp;</td>
				<td>Nombre</td>
				<td>Email</td>
				<td width="100">Role</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($members['count'] > 0) : ?>
			<?php foreach($members['members'] as $u) : ?>
			<tr>
				<td><div class="status <?php echo ($u->is_banned)?'inactive':'active'; ?>"></div></td>
				<td><?php echo $u->display_name; ?></td>
				<td><?php echo $u->user_email; ?></td>
				
				<?php
					$user = get_user_by('id', $u->user_id);
					if ($u->is_banned) $cap = 'banned';
					else if ($user->has_cap('ct_coordinator')) $cap = 'ct_coordinator';
					else if ($user->has_cap('ct_facilitator')) $cap = 'ct_facilitator';
					else $cap = 'ct_member';
				?>
				<td><select id="ct_center_role_<?php echo $u->user_id; ?>" name="ct-centro-role[<?php echo $u->user_id; ?>]">
					<option value="banned">Inactivo</option>
					<option value="ct_member">Miembro</option>
					<option value="ct_facilitator">Facilitador</option>
					<option value="ct_coordinator">Coordinador</option>
					
				</select>
				<script type="text/javascript">
					jQuery('#ct_center_role_<?php echo $u->user_id; ?>').val('<?php echo $cap; ?>');
				</script>
				</td>
			</tr>
			<?php endforeach; endif;?>
		</tbody>
	</table>
	<input type="hidden" name="ct_group_id" value="<?php echo $g_id; ?>" />
	
<?php
}

function ct_ctr_meta_admin($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_ctr_noncename' );
	
	$g_id = get_post_meta($post->ID, 'ct_group_id', true);
	$coord = ct_get_members_by_role($g_id, 'ct_coordinator');
	$facil = ct_get_members_by_role($g_id, 'ct_facilitator');
	
?>
	<?php if ($coord) : ?>
	<ul class="ctr_user_listing">
		<li class="header">Coordinadores:</li>
		<?php foreach($coord as $c) : ?>
		<li><?php echo $c->display_name; ?></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	
	<?php if ($facil) : ?>
	<ul class="ctr_user_listing">
		<li class="header">Facilitadores:</li>
		<?php foreach($facil as $f) : ?>
		<?php if (!$f->has_cap('ct_coordinator')) : ?>
		<li><?php echo $f->display_name; ?></li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	
<?php
}

add_filter('update_post_metadata', 'ct_ctr_update_postmeta', 10, 5);
function ct_ctr_update_postmeta($meta_id, $post_id, $meta_key, $meta_value, $prev_value) {
	switch($meta_key) {}
}

add_action( 'added_post_meta', 'ct_ctr_updated_postmeta', 10, 4 );
add_action( 'updated_post_meta', 'ct_ctr_updated_postmeta', 10, 4 );
function ct_ctr_updated_postmeta( $meta_id, $post_id, $meta_key, $meta_value ) {
	switch($meta_key) {}
}

add_action( 'save_post', 'ct_ctr_save_postdata' );
function ct_ctr_save_postdata( $post_id ) {

	global $post_type;
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_ctr_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_ctr_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	
	if ($post_type == 'center') {
		
		
		$active_centers = get_option('ct_active_centers');
		if (!$active_centers) $active_centers = array();
			
		if (isset($_POST['ct_center_active'])) {
			$active_centers[] = $post_id;
			array_unique($active_centers);
		} else {
			if(($key = array_search($post_id, $active_centers)) !== false) {
			    unset($active_centers[$key]);
			}
		}
		update_option('ct_active_centers', $active_centers);
		
		
		if (isset($_POST['ct_center_info'])) {
			update_post_meta($post_id, 'ct_center_info', $_POST['ct_center_info']);
		} 
		
	   
	   	if (isset($_POST['ct_center_domain'])) {
	   		global $domainTheme;
	   		
	   		$post = get_post($post_id);
	   		$title = $post->post_title;
	   		$description = ($_POST['ct_center_description']) ? $_POST['ct_center_description'] 
	   														 : get_bloginfo('description');
	   		$prev = get_post_meta($post_id, 'ct_center_domain', true);											 
	   						
	   		
	   		if ($_POST['ct_center_domain']) {
				ct_ctr_update_domainTheme($prev, $_POST['ct_center_domain'], $title, $description);
				update_post_meta($post_id, 'ct_center_domain', $_POST['ct_center_domain']);
				update_post_meta($post_id, 'ct_center_description', $description);
	   		} else {
	   			ct_ctr_remove_domainTheme(get_post_meta($post_id, 'ct_center_domain', true));
	   			update_post_meta($post_id, 'ct_center_domain','');
	   			update_post_meta($post_id, 'ct_center_description', '');
	   		}   		
	   		
	   	}
	   	
	   	if (isset($_POST['ct_center_locations'])) {
	   		update_post_meta($post_id, 'ct_center_locations', serialize($_POST['ct_center_locations']));
	   		update_post_meta($post_id, 'ct_center_primary_loc', $_POST['ct_center_primary_loc']);
	   	}
	    
	    /*
	    if (isset($_POST['ct_center_info'])) {  
		  	update_post_meta($post_id, 'ct_center_info', $_POST['ct_center_info']);
		}
		*/
		
		if ($_POST['ct_center_use_community']) {
			ct_ctr_activate_community($post_id);
		} else {
			ct_ctr_deactivate_community($post_id);
		}
	}
	
	if (isset($_POST['ct_center'])) {
		update_post_meta($post_id, 'ct_center', $_POST['ct_center']);
	}
	
	update_post_meta($post_id, 'ct_page_show_menu', $_POST['ct_page_show_menu']);
	
	
	
	if (isset($_POST['ct_members'])) {  
	  	update_post_meta($post_id, 'ct_members', $_POST['ct_members']);
	}
		
	if (isset($_POST['ct_ctr_new_members'])) {
	
		$group = new BP_Groups_Group(get_post_meta($post_id, 'ct_group_id', true));
		if ($group->id) {
			$mems = explode(',',$_POST['ct_ctr_new_members']);
			
			foreach($mems as $m) {
				$u = get_user_by('email', $m);
				ct_add_member($u->ID, $group->id);
				if ($post_type == 'center') update_usermeta( $u->ID, 'ct_center', $post_id );
			}
		}
				
	}
	
	if (isset($_POST['ct-centro-role'])) {
	
		$group = new BP_Groups_Group(get_post_meta($post_id, 'ct_group_id', true));
	
		foreach($_POST['ct-centro-role'] as $u_id=>$val) {
			$u = get_user_by('id', $u_id);
			if ($u) {
				if ($postType == 'center') update_usermeta( $u_id, 'ct_center', $post_id );
				$u->remove_role('ct_coordinator');
				$u->remove_role('ct_facilitator');
				$u->remove_role('ct_member');
				switch($val) {
					case 'banned':
						groups_demote_member( $u->ID, $group->id);
						groups_ban_member( $u->ID, $group->id );
						break;
					case 'ct_member':
						$u->add_role('ct_member');
						groups_unban_member( $u->ID, $group->id );
						groups_demote_member( $u->ID, $group->id);
						break;
					case 'ct_facilitator':
						$u->add_role('ct_facilitator');
						groups_unban_member( $u->ID, $group->id );
						groups_demote_member( $u->ID, $group->id);
						groups_promote_member( $u->ID, $group->id, 'mod');	
						break;
					case 'ct_coordinator':
						$u->add_role('ct_coordinator');
						groups_unban_member( $u->ID, $group->id );
						groups_promote_member( $u->ID, $group->id, 'admin');
						break;
				}
			}
		}
	}
	
}

add_action( 'before_delete_post', 'ct_ctr_delete_post');
function ct_ctr_delete_post($post_id) {
	global $post_type;
	if ($post_type == 'center') {
		$domain = get_post_meta($post_id, 'ct_center_domain', true);
		ct_ctr_remove_domainTheme($domain);
		
		$group_id = get_post_meta($post_id, 'ct_group_id', true);
		if ($group_id) {
			$forum_id = groups_get_groupmeta( $group_id, 'forum_id' );
			if ($forum_id) wp_delete_post($forum_id);
			groups_delete_group( $group_id );
		}	
	}
}

function ct_add_member($user_id, $group_id) {
	if (!groups_is_user_member( $user_id, $group_id )) {
		$member                = new BP_Groups_Member;
		$member->group_id      = $group_id;
		$member->user_id       = $user_id;
		$member->is_admin      = 0;
		$member->is_confirmed  = 1;
		$member->date_modified = bp_core_current_time();
		$member->save();
	} 
}

function ct_ctr_activate_community($post_id) {
	$group_id = get_post_meta($post_id, 'ct_group_id', true);
	if (!$group_id) {
		remove_action('save_post', 'ct_ctr_save_postdata');
		ct_ctr_create_group($post_id);
		add_action('save_post', 'ct_ctr_save_postdata');
	}
}

function ct_ctr_deactivate_community($post_id) {
	$group_id = get_post_meta($post_id, 'ct_group_id', true);
	if ($group_id) {
		$forum_id = groups_get_groupmeta( $group_id, 'forum_id' );
		groups_delete_group( $group_id );
		update_post_meta($post_id, 'ct_group_id', '');
		wp_delete_post($forum_id, true);
	}
}

function ct_ctr_create_group($post_id) {
	
	$u_id = get_current_user_id();
	
	$p = get_post($post_id);
	if ($p->post_type == 'center') {
	
		$group = array('creator_id'=>$u_id,
				   'name'=>"Miembros : ".$p->post_title,
				   'description'=>'',
				   'slug'=>'center-group-'.$p->post_name,
				   'status'=>'hidden',
				   'enable_forum'=>1,
				   'date_created'=>current_time('mysql'));
		$g_id = groups_create_group($group);
		if ($g_id) {
			update_post_meta($post_id, 'ct_group_id', $g_id);
			groups_update_groupmeta( $g_id, 'total_member_count', 1 );
			groups_edit_group_settings( $g_id, true, 'hidden', 'mods' );		
			
			// Forum
			
			$q = new WP_Query('name=comunidades&post_type='.bbp_get_forum_post_type());
			$parent = $q->post;
			
			$forum = array(
				'post_parent'    => ($parent->ID) ? $parent->ID : 0,
				'post_status'    => bbp_get_private_status_id(),
				'post_type'      => bbp_get_forum_post_type(),
				'post_author'    => $u_id,
				'post_password'  => '',
				'post_content'   => '',
				'post_title'     => $p->post_title,
				'menu_order'     => 0,
				'comment_status' => 'closed'
			);
			
			if (!groups_get_groupmeta($g_id, 'forum_id')) {
				$f_id = wp_insert_post($forum);
				if ($f_id) {
					groups_update_groupmeta( $g_id, 'forum_id', $f_id );
				} 
			}
	
					
		}
	
	}
 
    
}

function ct_ctr_update_domainTheme($prev, $domain, $title = '', $description = '') {
	global $domainTheme;
	
	$ob = array();
	
	foreach($domainTheme->options as $key=>$option) {
		if ($option['url'] == $domain || $option['url'] == $prev) {
			$domainTheme->options[$key]['url'] = $domain;
			if ($title) $domainTheme->options[$key]['blogname'] = stripslashes('Casa Tibet : '.$title);
			if ($description) $domainTheme->options[$key]['blogdescription'] = stripslashes($description);
			$ob = $domainTheme->options[$key];
			$ob['id'] = $key;
			break;
		}
	}
	
	
	
	if (null === $ob['id']) {
		$ob['url'] = $domain;
		$ob['theme'] = 'ct-centro';
		if ($title) $ob['blogname'] = stripslashes('Casa Tibet : '.$title);
		if ($description) $ob['blogdescription'] = stripslashes($description);
		array_push($domainTheme->options, $ob);
	}
	
   	update_option("domainTheme_options", serialize($domainTheme->options));
}

function ct_ctr_remove_domainTheme($domain) {
	global $domainTheme;
	foreach($domainTheme->options as $key=>$option) {
		if ($option['url'] == $domain) array_splice($domainTheme->options,$key,1);
	}
    update_option("domainTheme_options", serialize($domainTheme->options));
}

/** ADMIN CONTROL *************************************/

function ct_ctr_posts_by_center($query) {

	//return $query;
	
	if($query->is_admin && $query->is_main_query() &&
	   !current_user_can('administrator') && 
	   current_user_can('ct_coordinator')) {
		global $user_ID;
		$type = $query->query_vars['post_type'];
		$center = get_user_meta($user_ID, 'ct_center', true); 
		switch($type) {
			default:
				$mq = ($query->query_vars['meta_query']) ? $query->query_vars['meta_query'] : array();
				$mq[] = array('key'=>'ct_center',
							  'value'=>$center,
							  'type'=>'NUMERIC');
				$query->set('meta_query', $mq);
				break;
		}
		
	}
	return $query;
}
add_filter('pre_get_posts', 'ct_ctr_posts_by_center', 10, 1);

/* FILTERS *****************************************************/

function ct_ctr_archive_filter($args) {

	/*
	
	array('post_type'=>'center', 
										  'center-type'=>'sangha-sede',
										  'country'=>array('mexico', 'guatemala', 'usa'), 
										  'listing'=>'center')
										  
									*/
									
					


	$config = array();
	$config['args'] = array('post_type'=>'center',
							'nopaging'=>true,
							 'order'=>'ASC',
							 'orderby'=>'title',
							 'tax_query'=>array(),
							 'meta_query'=>array());
							 
	$config['title'] = ($args['title']) ? $args['title'] : __('Sedes de Casa Tibet');
	$config['no_results'] = __('Actualmente no se encuentra ningun sede en esta categoria.');
							 
	if ($args['country'] && $args['country'][0] != 'all') {
		$config['args']['tax_query'][] = array(
							'taxonomy' => 'country',
							'field' => 'slug',
							'terms' => $args['country']
							);	
	}

	if ($args['center-type'] && $args['center-type'] != 'all') {
	
		$config['args']['tax_query'][] = array(
							'taxonomy' => 'center-type',
							'field' => 'slug',
							'terms' => $args['center-type']
							);	
	}

	return $config;
}
add_filter('ct_archive-center', 'ct_ctr_archive_filter', 10, 1);

function ct_ctr_query_args($args=array(), $digest=null) {
	$center = ct_get_current_center();
	if ($digest) {
		$val = get_option('ct_active_centers');
		$val[] = $center;
	} else {
		$val = array($center);
	}
	array_unique($val);
	
	//if ($center > 0) {
		if (!$args['meta_query']) $args['meta_query'] = array();
		$args['meta_query'][] = array('key'=>'ct_center', 'value'=>$val, 'compare'=>'IN');	
	//}
    return $args;
}
add_filter('ct_query_args', 'ct_ctr_query_args', 10, 1);

/** API FUNCTIONS ***************************************************/

function ct_ctr_is_active($id) {
	$centers = get_option('ct_active_centers');
	return in_array($id, (array) $centers);
}

function ct_get_current_center($ob=false) {
	global $current_center, $domainTheme;
	if (!isset($current_center)) {	
		$args = array('post_type'=>'center',
					  'meta_key'=>'ct_center_domain', 
					  'meta_value' => $domainTheme->currentdomain);
		$q = new WP_Query($args);
		$q->get_posts();
		if ($q->post) {
			$current_center = $q->post->ID;
		} else {
			$current_center = 0;
		}
		wp_reset_query();
	}
	return ($ob) ? get_post($current_center) : $current_center;
}

function ct_get_centers($country='mexico', $type='', $args=array()) {
	$args['post_type'] = 'center';
	$args['nopaging'] = true;
	$args['order'] = 'ASC';
	$args['orderby'] = 'title';
	$args['tax_query'] = array();
	
	if ($country) {
		$args['tax_query'][] = array(
							'taxonomy' => 'country',
							'field' => 'slug',
							'terms' => $country
							);	
	}
	
	if ($type) {
		$args['tax_query'][] = array(
							'taxonomy' => 'center-type',
							'field' => 'slug',
							'terms' => $type
							);	
	}
	
	$q = new WP_Query($args);
	return $q->posts;
}

function ct_get_center_for_user($u_id) {

	$args = array('post_type'=>'center',
				  'meta_key'=>'ct_center_admin',
				  'meta_value'=>$u_id);
	$q = new WP_Query($args);
	return $q->post;
}

function ct_get_user_center() {
	return get_user_meta(get_current_user_id(), 'ct_center', true);
}

function ct_get_members_by_role($g_id, $role) {

	$members = groups_get_group_members( $g_id, false, false, false, false );
	
	if ($members['count'] > 0) {
		$ids = array();
		foreach($members['members'] as $m) {
			$ids[] = $m->user_id;
		}
	
		$args = array('role'=>$role,
					  'include'=>$ids);
		$users = get_users($args);
		return $users;
	}
	
}

function ct_query_args_for_center($center, $args) {
	if (!$args['meta_query']) $args['meta_query'] = array();
	$args['meta_query'][] = array('key'=>'ct_center', 'value'=>$center, 'type'=>'NUMERIC');
    return $args;
}

function ct_ctr_get_locations($user=false) {
	return unserialize(get_post_meta(($user) ? ct_get_user_center() : ct_get_current_center(), 'ct_center_locations', true));
}

function ct_ctr_get_all_centers() {

	$args = array('post_type'=>'center', 'order'=>'ASC', 'orderby'=>'title', 'nopaging'=>true,
				  'tax_query'=>array(array(
								'taxonomy' => 'center-type',
								'field' => 'slug',
								'terms' => 'sangha-sede'
							),
							array('taxonomy' => 'country',
								'field' => 'slug',
								'terms' => 'otra',
								'operator' => 'NOT IN')
							));
	$q = new WP_Query($args);
	return $q->posts;
}


?>