<?php
/*
Plugin Name: WP jQuery Plus
Plugin URI: http://zslabs.com
Description: Loads jQuery from Google using the exact jQuery version as your current WordPress install while still maintaining backwards comptability for the core WP jQuery library
Author: Zach Schnackel
Author URI: http://zslabs.com
Version: 1.0.1
*/


/**
 * Check the WordPress version on activation
 * Deactivate plugin if running lower than 3.5
 * @return void
 *
 * @since 0.4
 */
function wpjp_activate() {

	global $wp_version;

	if ( version_compare( $wp_version, '3.5', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( printf( __( 'Sorry, but your version of WordPress, <strong>%s</strong>, does not meet WP jQuery Plus\'s required version of <strong>3.5</strong> to run properly. The plugin has been deactivated. <a href="%s">Click here to return to the Dashboard</a>', 'wpjp' ), $wp_version, admin_url() ) );
	}

}
register_activation_hook( __FILE__, 'wpjp_activate' );

/**
 * Swap jQuery source for Google
 * @return void
 *
 * @since 0.3
 */
function wpjp_set_src() {

	global $wp_version;

	if ( !is_admin() ) {

		wp_enqueue_script( 'jquery' );

		// Check to see if we're on 3.6 or newer (changed the jQuery handle)
		if ( version_compare( $wp_version, '3.6-alpha1', '>=' ) ) {

			// Get current version of jQuery from WordPress core
			$wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery-core']->ver;

			// Set jQuery Google URL
			$jquery_google_url = '//ajax.googleapis.com/ajax/libs/jquery/'.$wp_jquery_ver.'/jquery.min.js';

			// De-register jQuery
			wp_deregister_script( 'jquery-core' );

			// Register jQuery with Google URL
			wp_register_script( 'jquery-core', $jquery_google_url, '', null, false );

		}
		// Theeen we're on 3.5 (since the plugin will deactivate if any lower)
		else {

			// Get current version of jQuery from WordPress core
			$wp_jquery_ver = $GLOBALS['wp_scripts']->registered['jquery']->ver;

			// Set jQuery Google URL
			$jquery_google_url = '//ajax.googleapis.com/ajax/libs/jquery/'.$wp_jquery_ver.'/jquery.min.js';

			// De-register jQuery
			wp_deregister_script( 'jquery' );

			// Register jQuery with Google URL
			wp_register_script( 'jquery', $jquery_google_url, '', null, false );

		}

	}
}
add_action( 'wp_enqueue_scripts', 'wpjp_set_src' );

/**
 * Add local fallback for jQuery if CDN is down or not accessible
 * Inspired by http://rootstheme.com
 * Inspired by http://wordpress.stackexchange.com/a/12450
 * @param  string $src
 * @param  string $handle
 * @return string
 */
function wpjp_local_fallback( $src, $handle ) {

	global $wp_version;

	if ( !is_admin() ) {

		static $add_jquery_fallback = false;

		if ( $add_jquery_fallback ) {
			echo '<script>window.jQuery || document.write(\'<script src="' . includes_url( 'js/jquery/jquery.js' ) . '"><\/script>\')</script>' . "\n";
			$add_jquery_fallback = false;
		}

		// Check to see what version we're using (so we can use the appropriate handle)
		if ( version_compare( $wp_version, '3.6-alpha1', '>=' ) ) {

			if ( $handle === 'jquery-core' ) {
				$add_jquery_fallback = true;
			}

		}
		else {

			if ( $handle === 'jquery' ) {
				$add_jquery_fallback = true;
			}
		}


		return $src;

	}

	return $src;

}
add_filter( 'script_loader_src', 'wpjp_local_fallback', 10, 2 );