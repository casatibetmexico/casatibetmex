<?php
/*
Plugin Name: RM Contact Form
Author: Joe Flumerfelt
Description: This plugin provides a VERY simple implementation of a contact form.
Version: 0.2
*/

add_action( 'wp_enqueue_scripts', 'rm_enqueue_style', 2 );
function rm_enqueue_style() {
	if ($GLOBALS['use_validator']) wp_enqueue_script('jquery-validate', 
		plugins_url('js/jquery.validate.js', __FILE__), 'jquery');
	wp_enqueue_script('rm-contact-form', plugins_url('js/rm-contact-form.js', __FILE__), 'jquery');
	wp_enqueue_style('rm-contact-form-css', plugins_url('rm-contact-form.css', __FILE__));
}

function rm_form($template, $args=array(), $output=true) {

	$url = plugins_url('rm-contact-form').'/rm-process-form.php';
	$return_url = ($args['return_url']) ? $args['return_url'] : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"] ;
	
	$GLOBALS['form_args'] = $args;
	
	$html = '';
	$html .='<form id="rm-form" method="POST" action="'.$url.'">';
	$html .= rm_cf_load_template_part('form', $template); 
	$html .= '<input type="hidden" id="rm-return-url" name="rm-return-url" value="'.$return_url.'" />';
	$html .= '</form>';
	
	if ($output) {
		echo $html;
	} else {
		return $html;
	}
}

function rm_cf_load_template_part($template_name, $part_name=null) {
 	ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}


add_action('admin_menu', 'rm_cf_create_menu');
add_action( 'admin_init', 'rm_cf_register_settings' );
function rm_cf_create_menu() {
	add_options_page('RM Contact Form Settings', 'RM Form Settings', 'administrator', __FILE__, 'rm_cf_settings_page');
	
}
function rm_cf_register_settings() {
	register_setting( 'rm-settings-group', 'rm-contact-email' );
	register_setting( 'rm-settings-group', 'rm-contact-from' );
	register_setting( 'rm-settings-group', 'rm-contact-smtp' );
	register_setting( 'rm-settings-group', 'rm-contact-smtp-port' );
}

function rm_cf_settings_page() {
?>
<div class="wrap">
<h2>RM Contact Form Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'rm-settings-group' ); ?>
    <table class="form-table">
    
    	<tr valign="top">
        <th scope="row">Contact Email</th>
        <td><input type="text" name="rm-contact-email" 
        		   value="<?php echo get_option('rm-contact-email'); ?>" style="width:300px;" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">From Email</th>
        <td><input type="text" name="rm-contact-from" 
        		   value="<?php echo get_option('rm-contact-from'); ?>" style="width:300px;" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">SMTP Server</th>
        <td><input type="text" name="rm-contact-smtp" 
        		   value="<?php echo get_option('rm-contact-smtp'); ?>" style="width:300px;" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">SMTP Port</th>
        <td><input type="text" name="rm-contact-smtp-port" 
        		   value="<?php echo get_option('rm-contact-smtp-port'); ?>" style="width:300px;" /></td>
        </tr>
         
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>
<?php } 



?>