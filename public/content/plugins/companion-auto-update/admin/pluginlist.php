<p><?php _e('Here you can select plugins that you do not wish to automatically update', 'companion-auto-update'); ?>.</p>

<?php 

global $wpdb;
$table_name = $wpdb->prefix . "auto_updates";

$configs = $wpdb->get_results( "SELECT * FROM $table_name WHERE name = 'plugins'");
foreach ( $configs as $config ) if( $config->onoroff != 'on' ) echo '<div id="message" class="error"><p><b>'.__('Auto updating disabled', 'companion-auto-update').' &ndash;</b> '.__('You have <strong>disabled</strong> auto updating, these settings do not work unless you <strong>enable</strong> it', 'companion-auto-update').'.</p></div>';

if( isset( $_POST['submit'] ) ) {

	check_admin_referer( 'cau_save_pluginlis' );

	$noUpdateList 	= '';
	$noUpdateCount 	= 0;

	foreach ( $_POST['post'] as $key ) {
		$noUpdateList .= $key.', ';
		$noUpdateCount++;
	}

	$wpdb->query( " UPDATE $table_name SET onoroff = '$noUpdateList' WHERE name = 'notUpdateList' " );
	echo '<div id="message" class="updated"><p><b>'.__('Succes', 'companion-auto-update').' &ndash;</b> '.sprintf( esc_html__( '%1$s plugins have been added to the no-update-list', 'companion-auto-update' ), $noUpdateCount ).'.</p></div>';
}

if( isset( $_POST['reset'] ) ) {

	check_admin_referer( 'cau_save_pluginlis' );

	$wpdb->query( " UPDATE $table_name SET onoroff = '' WHERE name = 'notUpdateList' " );
	echo '<div id="message" class="updated"><p><b>'.__('Succes', 'companion-auto-update').' &ndash;</b> '.__( 'The no-update-list has been reset, all plugins will be auto-updated from now on', 'companion-auto-update' ).'.</p></div>';
}

?>

<form method="POST">

	<p>
		<input type='submit' name='submit' id='submit' class='button button-primary' value='<?php _e( "Save changes", "companion-auto-update" ); ?>'>
		<input type='submit' name='reset' id='reset' class='button button-alt' value='<?php _e( "Reset list", "companion-auto-update" ); ?>'>
	</p>

	<table class="wp-list-table widefat autoupdate">
		<thead>
			<tr>
				<td>&nbsp;</td>
				<th class="head-plugin"><strong><?php _e('Plugin', 'companion-auto-update'); ?></strong></th>
				<th class="head-status"><strong><?php _e('Status', 'companion-auto-update'); ?></strong></th>
				<th class="head-description"><strong><?php _e('Description', 'companion-auto-update'); ?></strong></th>
			</tr>
		</thead>

		<tbody id="the-list">

		<?php 

		foreach ( get_plugins() as $key => $value ) {

			$slug 			= $key;
			$explosion 		= explode( '/', $slug );
			$actualSlug 	= array_shift( $explosion );
			$slug_hash 		= md5( $slug[0] );

			foreach ( $value as $k => $v ) {

				if( $k == "Name" ) $name = $v;
				if( $k == "Description" ) $description = $v;

			}

			if( in_array( $actualSlug, donotupdatelist() ) ) {

				$class 		= 'inactive';
				$checked 	= 'CHECKED';
				$status 	= __( 'Auto-updating: disabled' , 'companion-auto-update' );

			} else {
				
				$class 		= 'active';
				$checked 	= '';
				$status 	= __( 'Auto-updating: enabled' , 'companion-auto-update' );

			}

			echo '<tr id="post-'.$slug_hash.'" class="'.$class.'">

				<th class="check-column">			
					<label class="screen-reader-text" for="cb-select-'.$slug_hash.'">Select '. $name .'</label>
					<input id="cb-select-'.$slug_hash.'" type="checkbox" name="post[]" value="'.$actualSlug.'" '.$checked.' ><label></label>
					<div class="locked-indicator"></div>
				</th>

				<td class="column-name">
					<p><strong>'. $name .'</strong></p>
				</td>

				<td class="cau_hide_on_mobile column-status">
					<p>'. $status .'</p>
				</td>

				<td class="cau_hide_on_mobile column-description">
					<p>'.$description.'</p>
				</td>

			</tr>';

		}
		?>

		</tbody>
	</table>

	<?php wp_nonce_field( 'cau_save_pluginlis' ); ?>

	<p>
		<input type='submit' name='submit' id='submit' class='button button-primary' value='<?php _e( "Save changes", "companion-auto-update" ); ?>'>
		<input type='submit' name='reset' id='reset' class='button button-alt' value='<?php _e( "Reset list", "companion-auto-update" ); ?>'>
	</p>

</form>