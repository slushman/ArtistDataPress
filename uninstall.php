<?php

// If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Delete option from options table
delete_option( 'slushman_adp_general_settings' );
delete_option( 'slushman_adp_layout_settings' );

?>