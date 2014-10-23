<?php
/* 
Plugin Name: WPDBSpringClean 
Plugin URI: http://wpsolutions-hq.com 
Description: A WordPress Plugin which deletes unused plugin tables from the database and also allows you to optimize existing DB tables
Author: WPSolutions-HQ 
Version: 1.6 
Author URI: http://wpsolutions-hq.com 
*/

define( 'WPDBSC_PATH', dirname(__FILE__) . '/' );
define('WPDBSC_URL', plugins_url('',__FILE__));
	
// Include some files containing functions we are going to use
include_once( WPDBSC_PATH . 'WPDBSCUnusedTables.php' );
include_once( WPDBSC_PATH . 'grepSearch.php' );
include_once( WPDBSC_PATH . 'tableOptimize.php' );

/*
 * The main plugin class, holds everything our plugin does,
 * initialized right after declaration
 */
class Settings_WPDBSpringClean_Plugin {
	
	/*
	 * For easier overriding we declared the keys
	 * here as well as our tabs array which is populated
	 * when registering settings
	 */
	private $unused_table_settings_key = 'unused_table_settings';
	private $optimize_table_settings_key = 'my_advanced_settings';
	private $wpdbsc_options_key = 'wpdbsc_plugin_options';
	private $wpdbsc_settings_tabs = array();
	/*
	 * Fired during plugins_loaded (very very early),
	 * so don't miss-use this, only actions and filters,
	 * current ones speak for themselves.
	 */
	function __construct() {
		add_action( 'init', array( &$this, 'load_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_general_settings' ) );
		add_action( 'admin_init', array( &$this, 'register_advanced_settings' ) );
		add_action( 'admin_menu', array( &$this, 'add_admin_menus' ) );
	}
	
	/*
	 * Loads both the general and advanced settings from
	 * the database into their respective arrays. Uses
	 * array_merge to merge with default values if they're
	 * missing.
	 */
	function load_settings() {
		$this->general_settings = (array) get_option( $this->unused_table_settings_key );
		$this->advanced_settings = (array) get_option( $this->optimize_table_settings_key );
		
		// Merge with defaults
		$this->general_settings = array_merge( array(
			'general_option' => 'General value'
		), $this->general_settings );
		
		$this->advanced_settings = array_merge( array(
			'advanced_option' => 'Advanced value'
		), $this->advanced_settings );
	}
	
	/*
	 * Registers the general settings via the Settings API,
	 * appends the setting to the tabs array of the object.
	 */
	function register_general_settings() {
		$this->wpdbsc_settings_tabs[$this->unused_table_settings_key] = 'Unused Table Search';
		
		register_setting( $this->unused_table_settings_key, $this->unused_table_settings_key );
		add_settings_section( 'section_general', 'General Plugin Settings', array( &$this, 'section_general_desc' ), $this->unused_table_settings_key );
		add_settings_field( 'general_option', 'A General Option', array( &$this, 'field_general_option' ), $this->unused_table_settings_key, 'section_general' );
	}
	
	/*
	 * Registers the advanced settings and appends the
	 * key to the plugin settings tabs array.
	 */
	function register_advanced_settings() {
		$this->wpdbsc_settings_tabs[$this->optimize_table_settings_key] = 'DB Table Optimize';
		
		register_setting( $this->optimize_table_settings_key, $this->optimize_table_settings_key );
		add_settings_section( 'section_advanced', 'Advanced Plugin Settings', array( &$this, 'section_advanced_desc' ), $this->optimize_table_settings_key );
		add_settings_field( 'advanced_option', 'An Advanced Option', array( &$this, 'field_advanced_option' ), $this->optimize_table_settings_key, 'section_advanced' );
	}
	
	/*
	 * The following methods provide descriptions
	 * for their respective sections, used as callbacks
	 * with add_settings_section
	 */
	function section_general_desc() { echo 'General section description goes here.'; }
	function section_advanced_desc() { echo 'Advanced section description goes here.'; }
	
	/*
	 * General Option field callback, renders a
	 * text input, note the name and value.
	 */
	function field_general_option() {
		?>
		<input type="text" name="<?php echo $this->unused_table_settings_key; ?>[general_option]" value="<?php echo esc_attr( $this->general_settings['general_option'] ); ?>" />
		<?php
	}
	
	/*
	 * Advanced Option field callback, same as above.
	 */
	function field_advanced_option() {
		?>
		<input type="text" name="<?php echo $this->optimize_table_settings_key; ?>[advanced_option]" value="<?php echo esc_attr( $this->advanced_settings['advanced_option'] ); ?>" />
		<?php
	}
	

/******************************************************************************
 * Now we just need to define an admin page.
 ******************************************************************************/

/*
 * Called during admin_menu, adds an options
 * page under Settings called WPDBSpringClean, rendered
 * using the plugin_options_page method.
 */
	
function add_admin_menus() {
    $wpdbsc_admin_menu = add_options_page( 'WPDBSpringClean', 'WPDBSpringClean', 'manage_options', $this->wpdbsc_options_key, array( &$this, 'plugin_options_page' ) );
	
     /* Using registered $wpdbsc_admin_menu handle to hook stylesheet loading */
    add_action( 'admin_print_styles-' .$wpdbsc_admin_menu, array( &$this, 'wpdbsc_load_style' ) );
}

function wpdbsc_load_style() {
	/** Register */
    wp_register_style('wpdbsc-styles', WPDBSC_URL.'/css/wpdbsc.css', array(), '1.0.0', 'all');
	
	 /** Enqueue 
	  * It will be called only on your plugin admin page, enqueue our stylesheet here*/
    wp_enqueue_style('wpdbsc-styles');
}

	/*
	 * Plugin Options page rendering goes here, checks
	 * for active tab and replaces key with the related
	 * settings key. Uses the plugin_options_tabs method
	 * to render the tabs.
	 */
	function plugin_options_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->unused_table_settings_key;
		?>
		<div class="wrap">
			<?php 
			$this->plugin_options_tabs();
			if ($tab == 'unused_table_settings')
				wpdbscListUnusedTables(); //show Unused Table Search page 
			else 
				findFragmentedTables(); //show DB Table Optimize page
			?>
		</div>
		<?php
	}
	
	function current_tab() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->unused_table_settings_key;
		
		return $tab;
	}
	
	/*
	 * Renders our tabs in the plugin options page,
	 * walks through the object's tabs array and prints
	 * them one by one. Provides the heading for the
	 * plugin_options_page method.
	 */
	function plugin_options_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->unused_table_settings_key;

		screen_icon();
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->wpdbsc_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->wpdbsc_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}
};

// Initialize the plugin
add_action( 'plugins_loaded', create_function( '', '$settings_wpdbspringclean_plugin = new Settings_WPDBSpringClean_Plugin;' ) );