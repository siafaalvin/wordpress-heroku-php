<?php

function cau_menloc() {

	return 'tools.php';

}

function active_tab( $page, $identifier = 'tab' ) {

	if( !isset( $_GET[ $identifier ] ) ) {
		$cur_page = '';
	} else {
		$cur_page = $_GET[ $identifier ];
	}

	if( $page == $cur_page ) {
		echo 'nav-tab-active';
	}

}

function active_subtab( $page, $identifier = 'tab' ) {

	if( !isset( $_GET[ $identifier ] ) ) {
		$cur_page = '';
	} else {
		$cur_page = $_GET[ $identifier ];
	}

	if( $page == $cur_page ) {
		echo 'current';
	}

}


function donotupdatelist() {

	global $wpdb;
	$table_name 	= $wpdb->prefix . "auto_updates"; 
	$config 		= $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'notUpdateList'");

	$list 			= $config[0]->onoroff;
	$list 			= explode( ", ", $list );
	$returnList 	= array();

	foreach ( $list as $key ) array_push( $returnList, $key );
	
	return $returnList;

}

function cau_fetch_log( $limit, $format = 'simple' ) {

	// Create arrays
	$pluginNames 	= array();
	$pluginDates 	= array();
	$pluginDatesF 	= array();
	$type 			= array();

	// Where to look for plugins
	$plugdir    = plugin_dir_path( __DIR__ );
	$allPlugins = get_plugins();

	// Loop trough all plugins
	foreach ( $allPlugins as $key => $value) {

		// Get plugin data
		$fullPath 	= $plugdir.'/'.$key;
		$getFile 	= $path_parts = pathinfo( $fullPath );
		$pluginData = get_plugin_data( $fullPath );

		// Get plugin name
		foreach ( $pluginData as $dataKey => $dataValue ) {
			if( $dataKey == 'Name') {
				array_push( $pluginNames , $dataValue );
			}
		}

		// Get last update date
		$dateFormat = get_option( 'date_format' );
		$fileDate 	= date ( 'YmdHi', filemtime( $fullPath ) );
		if( $format == 'table' ) {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
			$fileDateF .= ' &dash; '.date ( 'H:i', filemtime( $fullPath ) );
		} else {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
		}
		array_push( $pluginDates, $fileDate );
		array_push( $pluginDatesF, $fileDateF );
		array_push( $type, 'Plugin' );

	}

	// Where to look for themes
	$themedir   = get_theme_root();
	$allThemes 	= wp_get_themes();

	// Loop trough all themes
	foreach ( $allThemes as $key => $value) {

		// Get theme data
		$fullPath 	= $themedir.'/'.$key;
		$getFile 	= $path_parts = pathinfo( $fullPath );

		// Get theme name
		array_push( $pluginNames , $path_parts['filename'] );

		// Get last update date
		$dateFormat = get_option( 'date_format' );
		$fileDate 	= date ( 'YmdHi', filemtime( $fullPath ) );

		if( $format == 'table' ) {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
			$fileDateF .= ' &dash; '.date ( 'H:i', filemtime( $fullPath ) );
		} else {
			$fileDateF 	= date_i18n ( $dateFormat, filemtime( $fullPath ) );
		}

		array_push( $pluginDates, $fileDate );
		array_push( $pluginDatesF, $fileDateF );
		array_push( $type, 'Theme' );

	}

	// Sort array by date
	arsort( $pluginDates );

	if( $limit == 'all' ) {
		$limit = 999;
	}

	$listClasses = 'wp-list-table widefat autoupdate autoupdatelog';

	if( $format == 'table' ) {
		$listClasses .= ' autoupdatelog striped';
	} else {
		$listClasses .= ' autoupdatewidget';
	}

	echo '<table class="'.$listClasses.'">';

	// Show the last updated plugins
	if( $format == 'table' ) {

		echo '<thead>
			<tr>
				<th><strong>'.__( 'Name', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'Type', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'Last updated on', 'companion-auto-update' ).'</strong></th>
			</tr>
		</thead>';

	}

	echo '<tbody id="the-list">';

	$loopings = 0;

	foreach ( $pluginDates as $key => $value ) {

		if( $loopings < $limit ) {

			echo '<tr>';

				echo '<td class="column-updatetitle"><p><strong>'. $pluginNames[$key] .'</strong></p></td>';

				if( $format == 'table' ) {

					if( $type[$key] == 'Plugin' ) {
						$thisType = __( 'Plugin', 'companion-auto-update' );
					} else if( $type[$key] == 'Theme' ) {
						$thisType = __( 'Theme', 'companion-auto-update' );
					}

					echo '<td class="cau_hide_on_mobile column-description"><p>'. $thisType .'</p></td>';

				}

				echo '<td class="column-date" style="min-width: 100px;"><p>'. $pluginDatesF[$key] .'</p></td>';

			echo '</tr>';

			$loopings++;

		}

	}

	echo "</tbody></table>";

}

// Only update plugin which are enabled
function cau_dont_update( $update, $item ) {

	$plugins = donotupdatelist();

    if ( in_array( $item->slug, $plugins ) ) {
		// Use the normal API response to decide whether to update or not
    	return $update; 
    } else {
    	// Always update plugins
    	return true; 
    } 

}

?>