<?php
function send_notification( $user_id = '', $username, $user_email = '', $status = '' ) {
	$status_base_1 = base64_encode($status);
	$status = base64_encode($status_base_1);
	
	$activation_key_base_1 = base64_encode($user_id);
	$activation_key = base64_encode($activation_key_base_1);
	
	$activation_url = get_option( 'siteurl' )."/login/?status=".$status."&access_key=".$activation_key;
	
	//echo json_encode(array('loggedin'=>true, 'message'=>__($user_id.' - '.$activation_url)));
	$to = $user_email;
	$subject = 'Hyroglf Confirmation';
	$message = '<div class="mail_div" style="max-width:320px;"><h6 style="font-size:15px; color:rgb(34, 34, 34); margin:0px; font-weight:400;">Hi '.$username.',</h6>';
	$message .= '<h2 style="font-size:25px;font-weight:500;line-height:1;margin:5px 0px 20px 0px">One more step</h2>';
	//$message .= 'Sent from <a href="mailto:info@hyroglf.com">info@hyroglf.com</a><p style="line-height:1; font-size:16px;">Confirm your email address to complete your Hyroglf registration. Just click the button below.</p>';
	$message .= '<p style="font-size:15px;margin:15px 0px 15px 0px;">Confirm your email address to register your Hyroglf account. Just click on the button below.</p>';
	//$message .= '<p>Please click the below url to active your account.</p>';
	$message .= '<p><a style="background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;margin:0 0 0 0px; text-decoration: none;" href="'.$activation_url.'">Confirm</a><p></table>';
	//$message .= 'Regards,<br/>';
	//$message .=  get_option( 'blogname' ); 
	$headers = array('Content-Type: text/html; charset=UTF-8');
	add_filter( 'wp_mail_content_type', 'set_html_content_type_contributor' );
	if( wp_mail( $to, $subject, $message, $headers) ) {
		//echo json_encode(array('loggedin'=>true, 'message'=>__('<p class="success_msg">Please check your mail for active your account.</p>')));
	} 
	remove_filter( 'wp_mail_content_type', 'set_html_content_type_contributor' );
}
function send_notification_active( $user_id = '', $username, $password = '', $user_email = '', $status = '' ) {
	
	$login_url = get_option( 'siteurl' )."/login/";
			
	$to = $user_email;
	$subject = 'Hyroglf Contributor registration';
	$message = '<p>We are pleased to confirm your registration for Hyroglf. Your account is now active.</p>';
	$message .= '<p>Below is your login credential.</p>';
	$message .= 'Hi '.$username.',<br/>';
	$message .= '<lable>Username :</label> '.$username.'<br/>';
	$message .= '<lable>Password :</lable> '.$password;
	$message .= '<p>'.$login_url.'</p><br/>';
	$message .= 'Regards,<br/>';
	$message .=  get_option( 'blogname' ); 
	$headers = array('Content-Type: text/html; charset=UTF-8');
	add_filter( 'wp_mail_content_type', 'set_html_content_type_contributor' );
	if( wp_mail( $to, $subject, $message, $headers) ) {
	}
	remove_filter( 'wp_mail_content_type', 'set_html_content_type_contributor' );
}

function set_html_content_type_contributor() {
	return 'text/html';
}

if ( !is_user_logged_in() && ! empty( $_GET['status'] ) && ! empty( $_GET['access_key'] ) ) {
	add_action( 'init', 'set_user_active_account' );
}

function set_user_active_account() {
	
	$result = '';
	
	$status_base_1 = base64_decode($_GET['status']);
	$status = base64_decode($status_base_1);
	
	$activation_key_base_1 = base64_decode($_GET['access_key']);
	$user_id = base64_decode($activation_key_base_1);
	
	$user_account_status = get_user_meta( $user_id, 'user_account_status', true );
	$user_temp_pass = get_user_meta( $user_id, 'user_temp_pass', true );
	if( $status == 'inactive' || $_GET['status'] == 'inactive' && isset( $_GET['access_key'] ) ) {
		$result = update_user_meta( $user_id, 'user_account_status', 'active' );
		
		if( $result ) {
			$user = get_user_by( 'id', $user_id ); 
			if( $user ) {
				/*wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
				do_action( 'wp_login', $user->user_login );*/
				
				$username = $user->user_login;
				$user_pass = $user_temp_pass;
				$user_email = $user->user_email;
				$status = 'active';
		
				//send_notification_active( $user_id, $username, $user_temp_pass, $user_email, $status );
				
			}	
		}
	}
	
}

add_action( 'manage_users_custom_column', 'manage_users_column_content', 10, 3 );
function manage_users_column_content( $empty, $column_name, $user_ID ) {
	if ( $column_name == 'hyroglf_user_inactive' ) {
		if ( get_the_author_meta( 'user_account_status', $user_ID ) == 'inactive' ) {
			return __( 'Inactive', 'user_account_status' );
		}
	}
}

add_filter( 'manage_users_columns','manage_users_columns' );
function manage_users_columns( $defaults ) {
	$defaults['hyroglf_user_inactive'] = __( 'Inactive', 'user_account_status' );
	return $defaults;
}

add_filter( 'hyroglf_login_message', 'user_login_message' );
function user_login_message( $message ) {
	// Show the error message if it seems to be a disabled user
	if ( isset( $_GET['status'] ) && $_GET['status'] == 'inactive' && isset( $_GET['access_to'] ) && !isset($_GET['loginerror']) ) {
		
		$login_url = site_url('/login');
		$access_to =  base64_decode($_GET['access_to'] );
		$user_id = base64_decode($access_to );
		$user = get_userdata($user_id);
		$user_email = "'".$user->user_email."'";
		$user_login = "'".$user->user_login."'";
		
		$message =  '<div class="login_error error_msg">' . apply_filters( 'hyroglf_inactive_users_notice', __( 'Before logging in, you must confirm registration. Click <a href="javascript:void(0);" onclick="fnResendActivationMail2('.$user_login.','.$user_email.');">here</a> to resend confirmation email.', 'user_account_status' ) ) . '</div>';
//		'.$login_url.'?status=inactive&access_key='.$_GET['access_to'].'
	} 

	return $message;
}

add_action( 'wp_login', 'user_login', 10, 2 );
function user_login( $user_login, $user = null ) {
	if ( !$user ) {
		$user = get_user_by('login', $user_login);
	}
	if ( !$user ) {
		// not logged in - definitely not disabled
		return;
	}
	// Get user meta
	$status = get_user_meta( $user->ID, 'user_account_status', true );
	
	// Is the use logging in disabled?
	if ( $status == 'inactive' ) {
		// Clear cookies, a.k.a log user out
		wp_clear_auth_cookie();
		
		$user_id = base64_encode($user->ID);
		$user_id = base64_encode($user_id);

		// Build login URL and then redirect
		$login_url = site_url('/login');
		$login_url = add_query_arg( 'status', 'inactive', $login_url );
		$login_url = add_query_arg( 'access_to', $user_id, $login_url );
		wp_redirect( $login_url );
		exit;
	}
}