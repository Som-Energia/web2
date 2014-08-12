<?php 
/*
Plugin Name: WPML XLIFF
Plugin URI: https://wpml.org/
Description: Import and Export XLIFF files for translation
Author: ICanLocalize
Author URI: https://wpml.org
Version: 0.9.4
*/

if(defined('WPML_XLIFF_VERSION')) return;

define('WPML_XLIFF_VERSION', '0.9.4');
define('WPML_XLIFF_PATH', dirname(__FILE__));

require WPML_XLIFF_PATH . '/inc/constants.inc';
require WPML_XLIFF_PATH . '/inc/wpml_xliff.class.php';