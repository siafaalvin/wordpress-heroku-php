<?php

$plugin_schedule 	= wp_get_schedule( 'wp_update_plugins' );
$theme_schedule 	= wp_get_schedule( 'wp_update_themes' );
$core_schedule 		= wp_get_schedule( 'wp_version_check' );
$mail_sc 			= wp_get_schedule( 'cau_set_schedule_mail' );

if( isset( $_POST['submit'] ) ) {

	check_admin_referer( 'cau_save_schedule' );

	// Set variables
	$plugin_sc 		= $_POST['plugin_schedule'];
	$theme_sc 		= $_POST['theme_schedule'];
	$core_sc 		= $_POST['core_schedule'];
	$schedule_mail 	= $_POST['schedule_mail'];


	// First clear schedules
	wp_clear_scheduled_hook('wp_update_plugins');
	wp_clear_scheduled_hook('wp_update_themes');
	wp_clear_scheduled_hook('wp_version_check');
	wp_clear_scheduled_hook('cau_set_schedule_mail');

	// Then set the new times
	if( $plugin_sc == 'daily' ) {

		$date 				= date( 'Y-m-d' );
		$hours 				= $_POST['pluginScheduleTimeH'];
		$minutes 			= $_POST['pluginScheduleTimeM'];
		$seconds 			= date( 's' );
		$fullDate 			= $date.' '.$hours.':'.$minutes.':'.$seconds;
		$pluginSetTime 		= strtotime( $fullDate );

		wp_schedule_event( $pluginSetTime, $plugin_sc, 'wp_update_plugins' );

	} else {

		wp_schedule_event( time(), $plugin_sc, 'wp_update_plugins' );

	}
	if( $theme_sc == 'daily' ) {

		$dateT 				= date( 'Y-m-d' );
		$hoursT 			= $_POST['ThemeScheduleTimeH'];
		$minutesT 			= $_POST['ThemeScheduleTimeM'];
		$secondsT 			= date( 's' );
		$fullDateT 			= $dateT.' '.$hoursT.':'.$minutesT.':'.$secondsT;
		$themeSetTime 		= strtotime( $fullDateT );

		wp_schedule_event( $themeSetTime, $theme_sc, 'wp_update_themes' );

	} else {

		wp_schedule_event( time(), $theme_sc, 'wp_update_themes' );

	}

	wp_schedule_event( time(), $core_sc, 'wp_version_check' );
	wp_schedule_event( time(), $schedule_mail, 'cau_set_schedule_mail' );

	header( "Location: ".cau_menloc()."?page=cau-settings&tab=schedule&showmessage=true" );

}

if( isset( $_GET['showmessage'] ) ) {

	echo '<div id="message" class="updated"><p>'.__('Changes were saved.', 'companion-auto-update').'</p></div>';

} else {

	echo '<div class="warning"><p class="warningText"><strong>'.__( 'Warning', 'companion-auto-update' ).'</strong> &dash; '.__( 'Changing these settings may affect your sites perfomance.', 'companion-auto-update' ).'</p></div>';

}

?>

<form method="POST">

	<h2 class="title"><?php _e('Updating', 'companion-auto-update');?></h2>
	<?php _e('How often should the auto updater kick in? (Default twice daily)', 'companion-auto-update'); ?>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Plugin update interval', 'companion-auto-update');?></th>
			<td>
				<p>
					<select name='plugin_schedule' id='plugin_schedule'>
						<option value='hourly' <?php if( $plugin_schedule == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
						<option value='twicedaily' <?php if( $plugin_schedule == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
						<option value='daily' <?php if( $plugin_schedule == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
					</select>
				</p>
				<div class='timeSchedulePlugins' <?php if( $plugin_schedule != 'daily' ) { echo "style='display: none;'"; } ?> >

					<?php 

					$setTimePlugins 	= wp_next_scheduled( 'wp_update_plugins' );
					$setTimePluginsHour = date( 'H' , $setTimePlugins );
					$setTimePluginsMin 	= date( 'i' , $setTimePlugins ); 

					?>

					<div class='cau_schedule_input'>
						<input type='text' name='pluginScheduleTimeH' value='<?php echo $setTimePluginsHour; ?>' maxlength='2' >
					</div><div class='cau_schedule_input_div'>
						:
					</div><div class='cau_schedule_input'>
						<input type='text' name='pluginScheduleTimeM' value='<?php echo $setTimePluginsMin; ?>' maxlength='2' > 
					</div><div class='cau_shedule_notation'>
						<b><?php _e('Time notation: 24H', 'companion-auto-update'); ?></b>
					</div>
					
					<p class='description'><?php _e('At what time should the updater run? Only works when set to <u>daily</u>.', 'companion-auto-update'); ?> </p>

				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Theme update interval', 'companion-auto-update');?></th>
			<td>
				<p>

					<select name='theme_schedule' id='theme_schedule'>
						<option value='hourly' <?php if( $theme_schedule == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
						<option value='twicedaily' <?php if( $theme_schedule == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
						<option value='daily' <?php if( $theme_schedule == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
					</select>
				</p>
				<div class='timeScheduleThemes' <?php if( $theme_schedule != 'daily' ) { echo "style='display: none;'"; } ?> >

					<?php 

					$setTimeThemes 		= wp_next_scheduled( 'wp_update_themes' );
					$setTimeThemesHour 	= date( 'H' , $setTimeThemes );
					$setTimeThemesMins 	= date( 'i' , $setTimeThemes );

					?>

					<div class='cau_schedule_input'>
						<input type='text' name='ThemeScheduleTimeH' value='<?php echo $setTimeThemesHour; ?>' maxlength='2' >
					</div><div class='cau_schedule_input_div'>
						:
					</div><div class='cau_schedule_input'>
						<input type='text' name='ThemeScheduleTimeM' value='<?php echo $setTimeThemesMins; ?>' maxlength='2' > 
					</div><div class='cau_shedule_notation'>
						<b><?php _e('Time notation: 24H', 'companion-auto-update'); ?></b>
					</div>
					
					<p class='description'><?php _e('At what time should the updater run? Only works when set to <u>daily</u>.', 'companion-auto-update'); ?> </p>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Core update interval', 'companion-auto-update');?></th>
			<td>
				<p>
					<select name='core_schedule'>
						<option value='hourly' <?php if( $core_schedule == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
						<option value='twicedaily' <?php if( $core_schedule == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
						<option value='daily' <?php if( $core_schedule == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
					</select>
				</p>
			</td>
		</tr>		
	</table>

	<h2 class="title"><?php _e('Email Notifications', 'companion-auto-update');?></h2>
	<?php _e('How often should notifications be send? (Default daily)', 'companion-auto-update'); ?>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Email Notifications', 'companion-auto-update');?></th>
			<td>
				<p>
					<select name='schedule_mail'>
						<option value='hourly' <?php if( $mail_sc == 'hourly' ) { echo "SELECTED"; } ?> ><?php _e('Hourly', 'companion-auto-update');?></option>
						<option value='twicedaily' <?php if( $mail_sc == 'twicedaily' ) { echo "SELECTED"; } ?> ><?php _e('Twice Daily', 'companion-auto-update');?></option>
						<option value='daily' <?php if( $mail_sc == 'daily' ) { echo "SELECTED"; } ?> ><?php _e('Daily', 'companion-auto-update');?></option>
					</select>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Disable Notifications', 'companion-auto-update');?></th>
			<td>
				<p>
					<?php _e('To disable email notifications go to the dashboard and uncheck everything under "Email Notifications".', 'companion-auto-update');?>
				</p>
			</td>
		</tr>
	</table>

	<?php wp_nonce_field( 'cau_save_schedule' ); ?>

	<p><!-- SPACING --></p>

	<input type='submit' name='submit' id='submit' class='button button-primary' value='<?php _e( "Save changes", "companion-auto-update" ); ?>'>

</form>

<script type="text/javascript">
	
	jQuery( '#plugin_schedule' ).change( function() {

		var selected = jQuery(this).val();

		if( selected == 'daily' ) {
			jQuery('.timeSchedulePlugins').show();
		} else {
			jQuery('.timeSchedulePlugins').hide();
		}


	});
	
	jQuery( '#theme_schedule' ).change( function() {

		var selected = jQuery(this).val();

		if( selected == 'daily' ) {
			jQuery('.timeScheduleThemes').show();
		} else {
			jQuery('.timeScheduleThemes').hide();
		}


	});

</script>