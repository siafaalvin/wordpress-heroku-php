<?php

// Check if emails should be send or not
function cau_check_updates_mail() {

	global $wpdb;
	$table_name 	= $wpdb->prefix . "auto_updates"; 
	$cau_configs 	= $wpdb->get_results( "SELECT * FROM $table_name" );

	if( $cau_configs[5]->onoroff == 'on' ) { 
		cau_list_theme_updates(); // Check for theme updates
		cau_list_plugin_updates(); // Check for plugin updates
	}

	if( $cau_configs[6]->onoroff == 'on' ) if( $cau_configs[0]->onoroff == 'on' ) cau_plugin_updated(); // Check for updated plugins
}

// Ge the emailadresses it should be send to
function cau_set_email() {

	global $wpdb;
	$table_name 	= $wpdb->prefix . "auto_updates"; 
	$cau_configs 	= $wpdb->get_results( "SELECT * FROM $table_name" );
	$emailArray 	= array();

	if( $cau_configs[4]->onoroff == '' ) {
		array_push( $emailArray, get_option('admin_email') );
	} else {
		$emailAdresses 	= $cau_configs[4]->onoroff;
		$list 			= explode( ", ", $emailAdresses );
		foreach ( $list as $key ) {
			array_push( $emailArray, $list );	
		}
	}

	return $emailArray;

}

// Set the content for the emails about pending updates
function cau_pending_message( $single, $plural ) {

	return sprintf( esc_html__( 
		'There are one or more %1$s updates available on your WordPress site at: %2$s, but you have disabled auto-updating for %3$s. Login to your dashboard to manually update your %3$s.', 'companion-auto-update' 
	), $single, get_site_url(),  $plural);

}

// Set the content for the emails about recent updates
function cau_updated_message( $type, $updatedList ) {

	return sprintf( esc_html__( 
		'We have updated one or more %1$s on your WordPress site at %2$s, be sure to check if everything still works properly. The following %1$s have been updated: %3$s', 'companion-auto-update' 
	), $type, get_site_url(), $updatedList );

}

// Checks if theme updates are available
function cau_list_theme_updates() {

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 

	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'themes'");
	foreach ( $configs as $config ) {

		if( $config->onoroff != 'on' ) {

			require_once ABSPATH . '/wp-admin/includes/update.php';
			$themes = get_theme_updates();

			if ( !empty( $themes ) ) {

				$subject 		= '[' . get_bloginfo( 'name' ) . '] ' . __('Theme update available.', 'companion-auto-update');
				$type 			= __('theme', 'companion-auto-update');
				$type_plural	= __('themes', 'companion-auto-update');
				$message 		= cau_pending_message( $type, $type_plural );
				
				foreach ( cau_set_email() as $key => $value) {
					foreach ($value as $k => $v) {
						wp_mail( $v, $subject, $message, $headers );
					}
					break;
				}
			}

		}

	}

}

// Checks if plugin updates are available
function cau_list_plugin_updates() {
	
	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 

	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'plugins'");
	foreach ( $configs as $config ) {

		if( $config->onoroff != 'on' ) {

			require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
			$plugins = get_plugin_updates();

			if ( !empty( $plugins ) ) {

				$subject 		= '[' . get_bloginfo( 'name' ) . '] ' . __('Plugin update available.', 'companion-auto-update');
				$type 			= __('plugin', 'companion-auto-update');
				$type_plural	= __('plugins', 'companion-auto-update');
				$message 		= cau_pending_message( $type, $type_plural );

				foreach ( cau_set_email() as $key => $value) {
					foreach ($value as $k => $v) {
						wp_mail( $v, $subject, $message, $headers );
					}
					break;
				}
			}

		}

	}
}

// Alerts when plugin has been updated
function cau_plugin_updated() {

	// Create arrays
	$pluginNames 	= array();
	$pluginDates 	= array();

	// Where to look for plugins
	$plugdir    	= plugin_dir_path( __DIR__ );
	$allPlugins 	= get_plugins();

	// Loop trough all plugins
	foreach ( $allPlugins as $key => $value) {

		// Get plugin data
		$fullPath 	= $plugdir.'/'.$key;
		$getFile 	= $path_parts = pathinfo( $fullPath );
		$pluginData = get_plugin_data( $fullPath );

		// Get last update date
		$fileDate 	= date ( 'YmdHi', filemtime( $fullPath ) );
		$mailSched 	= wp_get_schedule( 'cau_set_schedule_mail' );

		if( $mailSched == 'hourly' ) {
			$lastday = date( 'YmdHi', strtotime( '-1 hour' ) );
		} elseif( $mailSched == 'twicedaily' ) {
			$lastday = date( 'YmdHi', strtotime( '-12 hours' ) );
		} elseif( $mailSched == 'daily' ) {
			$lastday = date( 'YmdHi', strtotime( '-1 day' ) );
		}

		if( $fileDate >= $lastday ) {

			// Get plugin name
			foreach ( $pluginData as $dataKey => $dataValue ) {
				if( $dataKey == 'Name') {
					array_push( $pluginNames , $dataValue );
				}
			}

			array_push( $pluginDates, $fileDate );
		}

	}
	
	$totalNum 		= 0;
	$updatedList 	= '';

	foreach ( $pluginDates as $key => $value ) {

		$updatedList .= "- ".$pluginNames[$key]."\n";
		$totalNum++;

	}

	// If plugins have been updated in the last day, send email
	if( $totalNum > 0 ) {

		$subject 		= '[' . get_bloginfo( 'name' ) . '] ' . __('One or more plugins have been updated.', 'companion-auto-update');
		$type 			= __('plugins', 'companion-auto-update');
		$message 		= cau_updated_message( $type, "\n".$updatedList );

		foreach ( cau_set_email() as $key => $value) {
			foreach ($value as $k => $v) {
				wp_mail( $v, $subject, $message, $headers );
			}
			break;
		}

	}

}

?>