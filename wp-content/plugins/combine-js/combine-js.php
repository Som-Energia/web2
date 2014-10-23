<?php
/*
Plugin Name: Combine JS
Plugin URI: http://www.weareconvoy.com
Description: WordPress plugin that attempts to combine, minify, and compress JS.
Author: Convoy
Version: 2.1
Author URI: http://www.weareconvoy.com
Requires at least: 3.0.0
Tested up to: 3.4.2

Copyright 2010-2011 by Tim McDaniels http://www.weareconvoy.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License,or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not,write to the Free Software
Foundation,Inc.,51 Franklin St,Fifth Floor,Boston,MA 02110-1301 USA
*/
?>
<?php

// don't allow direct access of this file

if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) die();

// require base objects and do instantiation

if ( !class_exists( 'CombineJS' ) ) {
    require_once( dirname( __FILE__ ) . '/classes/combine-js-class.php' );
}
$wp_combine_js = new CombineJS();

// define plugin file path

$wp_combine_js->set_plugin_file( __FILE__ );

// define directory name of plugin

$wp_combine_js->set_plugin_dir( basename( dirname( __FILE__ ) ) );

// path to this plugin

$wp_combine_js->set_plugin_path( dirname( __FILE__ ) );

// URL to plugin

$wp_combine_js->set_plugin_url( plugin_dir_url(__FILE__) );

// call init

$wp_combine_js->init();

?>
