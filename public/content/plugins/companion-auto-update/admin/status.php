<?php

	$dateFormat = get_option( 'date_format' );
	$dateFormat .= ' '.get_option( 'time_format' );

	global $wpdb;
	$table_name = $wpdb->prefix . "auto_updates"; 

	// Major updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'major'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' ) {
			$majorUpdates 	= true;
			$majorStatus 	= 'enabled';
			$majorIcon		= 'yes';
			$majorInterval 	= wp_get_schedule( 'wp_version_check' );
			$majorNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_version_check' ) );
		} else {
			$majorUpdates 	= false;
			$majorStatus 	= 'disabled';
			$majorIcon		= 'no';
			$majorInterval 	= '&dash;';
			$majorNext 		= '&dash;';
		}

	}

	// Minor updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'minor'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' ) {
			$minorUpdates 	= true;
			$minorStatus 	= 'enabled';
			$minorIcon		= 'yes';
			$minorInterval 	= wp_get_schedule( 'wp_version_check' );
			$minorNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_version_check' ) );
		} else {
			$minorUpdates 	= false;
			$minorStatus 	= 'disabled';
			$minorIcon		= 'no';
			$minorInterval 	= '&dash;';
			$minorNext 		= '&dash;';
		}

	}

	// Plugin updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'plugins'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' ) {
			$pluginsUpdates 	= true;
			$pluginsStatus 		= 'enabled';
			$pluginsIcon		= 'yes';
			$pluginsInterval 	= wp_get_schedule( 'wp_update_plugins' );
			$pluginsNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_update_plugins' ) );
		} else {
			$pluginsUpdates 	= false;
			$pluginsStatus 		= 'disabled';
			$pluginsIcon		= 'no';
			$pluginsInterval 	= '&dash;';
			$pluginsNext 		= '&dash;';
		}

	}

	// Themes updates
	$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'themes'");
	foreach ( $configs as $config ) {

		if( $config->onoroff == 'on' ) {
			$themesUpdates 		= true;
			$themesStatus 		= 'enabled';
			$themesIcon			= 'yes';
			$themesInterval 	= wp_get_schedule( 'wp_update_plugins' );
			$themesNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'wp_update_plugins' ) );
		} else {
			$themesUpdates 		= false;
			$themesStatus 		= 'disabled';
			$themesIcon			= 'no';
			$themesInterval 	= '&dash;';
			$themesNext 		= '&dash;';
		}

	}

	if ( wp_next_scheduled ( 'cau_set_schedule_mail' ) ) {
		$setScheduleStatus  	= 'enabled';
		$setScheduleIcon  		= 'yes';
		$setScheduleInterval 	= wp_get_schedule( 'cau_set_schedule_mail' );
		$setScheduleNext 		= date_i18n( $dateFormat, wp_next_scheduled( 'cau_set_schedule_mail' ) );
	} else {
		$setScheduleStatus  	= 'disabled';
		$setScheduleIcon  		= 'no';
		$setScheduleInterval 	= '&dash;';
		$setScheduleNext 		= '&dash;';
	}

?>

<h2><?php _e('Status', 'companion-auto-update'); ?></h2>

<table class="cau_status_list widefat striped">

	<thead>
		<tr>
			<th><strong><?php _e('Updaters', 'companion-auto-update'); ?></strong></th>
			<th><strong><?php _e('Active?', 'companion-auto-update'); ?></strong></th>
			<th><strong><?php _e('Interval', 'companion-auto-update'); ?></strong></th>
			<th><strong><?php _e('Next', 'companion-auto-update'); ?></strong></th>
		</tr>
	</thead>

	<tbody id="the-list">
		<tr>
			<td><?php _e('Plugins', 'companion-auto-update'); ?></td>
			<td><span class='cau_<?php echo $pluginsStatus; ?>'><span class="dashicons dashicons-<?php echo $pluginsIcon; ?>"></span></span></td>
			<td><?php echo $pluginsInterval; ?></td>
			<td><?php echo $pluginsNext; ?></td>
		</tr>
		<tr>
			<td><?php _e('Themes', 'companion-auto-update'); ?></td>
			<td><span class='cau_<?php echo $themesStatus; ?>'><span class="dashicons dashicons-<?php echo $themesIcon; ?>"></span></span></td>
			<td><?php echo $themesInterval; ?></td>
			<td><?php echo $themesNext; ?></td>
		</tr>
		<tr>
			<td><?php _e('Core (Major)', 'companion-auto-update'); ?></td>
			<td><span class='cau_<?php echo $majorStatus; ?>'><span class="dashicons dashicons-<?php echo $majorIcon; ?>"></span></span></td>
			<td><?php echo $majorInterval; ?></td>
			<td><?php echo $majorNext; ?></td>
		</tr>
		<tr>
			<td><?php _e('Core (Minor)', 'companion-auto-update'); ?></td>
			<td><span class='cau_<?php echo $minorStatus; ?>'><span class="dashicons dashicons-<?php echo $minorIcon; ?>"></span></span></td>
			<td><?php echo $minorInterval; ?></td>
			<td><?php echo $minorNext; ?></td>
		</tr>
	</tbody>

</table>

<table class="cau_status_list widefat striped">

	<thead>
		<tr>
			<th><strong><?php _e('Other', 'companion-auto-update'); ?></strong></th>
			<th><strong><?php _e('Active?', 'companion-auto-update'); ?></strong></th>
			<th><strong><?php _e('Interval', 'companion-auto-update'); ?></strong></th>
			<th><strong><?php _e('Next', 'companion-auto-update'); ?></strong></th>
		</tr>
	</thead>

	<tbody id="the-list">
		<tr>
			<td><?php _e('Notifications', 'companion-auto-update'); ?></td>
			<td><span class='cau_<?php echo $setScheduleStatus; ?>'><span class="dashicons dashicons-<?php echo $setScheduleIcon; ?>"></span></span></td>
			<td><?php echo $setScheduleInterval; ?></td>
			<td><?php echo $setScheduleNext; ?></td>
		</tr>
	</tbody>

</table>

<?php 
function checkAutomaticUpdaterDisabled() {

	if( doing_filter( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
		return true;
	} elseif( automatic_updater_disabled == 'true' ) {
		return true;
	} elseif( automatic_updater_disabled == 'minor' ) {
		return true;
	} else {
		return false;
	}

}

if( checkAutomaticUpdaterDisabled() ) { ?>

	<table class="cau_status_list widefat striped">

		<thead>
			<tr>
				<th colspan="2"><strong><?php _e('Global', 'companion-auto-update'); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">
			<tr>
				<td><span class='cau_disabled'><span class="dashicons dashicons-no"></span> Error</span></td>
				<td>
					<?php _e('Updating is globally disabled. Please contact us for further assistance.', 'companion-auto-update'); ?>
					<?php _e('Tell us about the following error: ', 'companion-auto-update'); ?> <code>AUTOMATIC_UPDATER_DISABLED</code>
				</td>
			</tr>
		</tbody>

	</table>

<?php } ?>