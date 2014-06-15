<?php
/*
Plugin Name: PHP Settings
Plugin URI: http://wpsecurity.net
Description: Attempts to set the max upload size and script timeout settings in the server's PHP config
Author: Mark - WPSecurity.net
Version: 1.0
Author URI: http://mpsecurity.net
*/
ini_set('memory_limit', '100M');
ini_set('upload_max_filesize', '192M');
ini_set('post_max_size', '100M');
ini_set('file_uploads', true);

add_filter( 'upload_size_limit', 'b5f_increase_upload' );

function b5f_increase_upload( $bytes )
{
    return 104857600; // 32 megabytes
}

?>