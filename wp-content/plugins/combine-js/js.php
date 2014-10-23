<?php

// this file servers as the interface to the combined JS file that is written in the WP upload directories

$tmp_dir = 'tmp/';
if ( ! is_writable( dirname( $tmp_dir ) ) ) $tmp_dir = sys_get_temp_dir() . '/';
$settings_path = $tmp_dir . $_SERVER['HTTP_HOST'] . '-settings.dat';
if ( file_exists( $settings_path ) && strlen( $_GET['token'] ) == 32 ) {
    $settings = file_get_contents( $settings_path );
    $settings = unserialize( $settings );
    $js_file = $settings['upload_path'] . $_GET['token'] . '.js';
	if ( isset( $_GET['footer'] ) && ! isset( $_GET['both'] ) ) $js_file = $settings['upload_path'] . $_GET['token'] . '-footer.js';
    if ( file_exists(  $js_file ) ) {
        if ( $settings['compress'] == 'Yes' && extension_loaded( 'zlib' ) ) ob_start( 'ob_gzhandler' );
        header( "Content-type: text/javascript" );
		header( "Cache-Control: max-age=300, must-revalidate" );
		header( "Expires: " . gmdate( "D, d M Y H:i:s", time() + ( 3600 * 24 * 7 ) ) . " GMT" );
        readfile( $js_file );
		if ( isset( $_GET['both'] ) ) {
			$js_file = $settings['upload_path'] . $_GET['token'] . '-footer.js';
			if ( file_exists(  $js_file ) ) readfile( $js_file );
		}
        if ( $settings['compress'] == 'Yes' && extension_loaded( 'zlib' ) ) ob_end_flush();
    }
}

?>
