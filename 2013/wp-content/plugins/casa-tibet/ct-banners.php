<?php


/* METABOXES *******************************************************************/

add_action( 'add_meta_boxes', 'ct_bnr_add_meta_boxes' );
function ct_bnr_add_meta_boxes($postType) {

	global $post;

	switch($postType) {
		case 'post':
		case 'event':
		case 'banner':  
			
			add_meta_box( 
		        'ct_bnr_options', 'Opciones', 'ct_bnr_meta_option',
		        $postType, 'side'
		    ); 
		     		    
			break;
	}
}

function ct_bnr_meta_option($post) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'ct_bnr_noncename' );
	$distribution = get_post_meta($post->ID, 'ct_distribution', true);
	if (!$distribution) $distribution = 'local';
?>	
	
	<p><b><?php _e('Distribución');?></b><br />
	<select id="ct_distribution" name="ct_distribution" style="width:100%;">
		<option value="local"><?php _e('Local'); ?></option>
		<option value="regional"><?php _e('Regional');?></option>
		<option value="national"><?php _e('Nacional'); ?></option>
	</select>
	<script type="text/javascript">	
		jQuery('#ct_distribution').val('<?php echo $distribution; ?>');
	</script>
	</p>

<?php
}

add_action( 'save_post', 'ct_bnr_save_postdata' );
function ct_bnr_save_postdata( $post_id ) {

	global $post_type;
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
    	return;
  	if ( ( isset ( $_POST['ct_bnr_noncename'] ) ) && 
  		 ( ! wp_verify_nonce( $_POST['ct_bnr_noncename'], plugin_basename( __FILE__ ) ) ) )
    	return;
	
	if (isset($_POST['ct_distribution'])) {
		update_post_meta($post_id, 'ct_distribution', $_POST['ct_distribution']);
	} 
	
	
}



?>