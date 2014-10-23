<?php
// If uninstall not called from WordPress exit
if(!defined('WP_UNINSTALL_PLUGIN'))
	exit ();
// Delete option from options table
delete_option( 'wpdbsc_unused_tables' );
delete_option( 'wpdbsc_optimize_search_criteria' );
delete_option( 'wpdbsc_optimize_candidate' );

?>