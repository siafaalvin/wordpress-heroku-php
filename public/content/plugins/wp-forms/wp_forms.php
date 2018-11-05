<?php
/*
Plugin Name: WP Edit-Profile and Recover Password
Version: 1.0
Plugin URI:
Description: Easy to edit the user profile and recover the password with confirmation mail
Author:
Author URI:
*/
if( !is_admin() ) {
function set_mail_html_content_type() {
	return 'text/html';
}

add_shortcode( 'wp_user_profile', 'wp_user_profile_edit_customize' );
function wp_user_profile_edit_customize() {
	if ( !is_user_logged_in() ) { ?>
		<p class="error_msg">
			<?php _e('You must be logged in to edit your profile.', 'profile'); ?>
		</p><?php
	} else {
		$result = wp_user_profile_update_customize();
		//print_r($result);
		if( $result['action'] == 'error' ) {
			if ( count($result['message']) > 0 ) {
				foreach( $result['message'] as $message ) {
					echo '<p class="error_msg">' . $message . '</p>';
				}
			}
		} else if( $result['action'] == 'success' ) {
			echo '<p class="success_msg">' .$result['message']. '</p>';
		}
		session_start();
		$current_user = wp_get_current_user(); // current user
		$user_id = $current_user->ID;
		$user = get_user_by( 'id', $user_id );
		//echo '<pre>'; print_r($user);?>
		<form method="post" id="adduser" action="" ng-controller="myProfileFormCtrl" ng-init="loadprofiledata()">
			<p class="form-username">
				<label for="username"><?php _e('Username <span class="field-required">*</span>', 'profile'); ?></label>
				<input class="text-input" name="username" ng-model="profile.username" type="text" id="username" value="{{profile_username}}" />
                <input class="text-input" name="username_hidden" type="hidden" id="username_hidden" value="<?php echo (isset($_SESSION['username']) && !empty($_SESSION['username']) ? $_SESSION['username'] :  $user->data->user_login);?>"/>
                <span id="username_error"></span>
			</p>
			<p class="form-email">
				<label for="email"><?php _e('Email <span class="field-required">*</span>', 'profile'); ?></label>
				<input class="text-input" name="email" ng-model="profile.useremail" type="email" id="email" value="{{profile_email}}" /><span id="email_error"> </span>
                <input class="text-input" name="email_hidden" type="hidden" id="email_hidden" value="<?php echo (isset($_SESSION['user_email']) && !empty($_SESSION['user_email']) ? $_SESSION['user_email'] : $user->data->user_email);?>" />
			</p>
			<!--<p class="form-password">
				<label for="pass1"><?php //_e('Password', 'profile'); ?> </label>
				<input class="text-input" name="pass1" type="password" id="pass1" />
			</p>
			<p class="form-password">
				<label for="pass2"><?php //_e('Repeat Password', 'profile'); ?></label>
				<input class="text-input" name="pass2" type="password" id="pass2" />
			</p>-->
            
			<?php do_action('edit_user_profile',$current_user); ?>
			<p class="form-submit">
				<input name="edit_profile" ng-click="ProfileCtrlSubmit(profile)" id="edit_profile" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
				<?php wp_nonce_field( 'update-user' ) ?>
				<input name="action" type="hidden" id="action" value="update-user" />
			</p>
            <a href="<?php echo home_url('/change-password');?>">Change Password</a>
            <a class="delete_account_popup_btn" href="javascript:void(0);">Delete Account</a>
		</form><?php
	}	
}

function wp_user_profile_update_customize() {
	/* Get user info. */
	global $current_user, $wp_roles;
	//echo '<pre>'; print_r($current_user);
	$error = array();
	$success = array();   
	/* If profile was saved, update profile. */
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {
	
		/* Update user password. */
		if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
			if ( $_POST['pass1'] == $_POST['pass2'] ) {
				//wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
			} else {
				$error[] = __('The repeat password you entered do not match.', 'profile');
			}
		} /*else if( empty($_POST['pass1'] ) ) {
			$error[] = __('The passwords is required.', 'profile');
		}*/ else if( !empty($_POST['pass1']) && empty($_POST['pass2']) ) {
			$error[] = __('The repeat password is required.', 'profile');
		}
	
		/* Update user information. */
		if ( !empty( $_POST['email'] ) || !empty( $_POST['username'] )){
			
			
			$exists_user_id = username_exists( $_POST['username'] );
			$exists_user_id_in_meta = check_user_name_in_user_meta($_POST['username']);
			$exists_email = email_exists( esc_attr( $_POST['email'] ) );
			$exists_email_in_meta = check_email_in_user_meta($_POST['email']);
			
			if ( !is_email( esc_attr( $_POST['email'] ) ) ) {
				$error[] = __('The Email you entered is not valid.', 'profile');
			} else if ( $exists_user_id && $exists_user_id != $current_user->ID || $exists_user_id_in_meta && $exists_user_id_in_meta != $current_user->ID ) {
				$error[] = __('This username is already used by another user.  Enter another one.', 'profile');
			} else if( $exists_email && $exists_email != $current_user->ID || $exists_email_in_meta && $exists_email_in_meta != $current_user->ID ) {
				$error[] = __('This email is already used by another user.  Enter another one.', 'profile');
			} else {
				if( !empty( $_POST['pass1'] ) ) {
					$user_new_datas = array( 'user_login' => $_POST['username'], 'user_email' => $_POST['email'], 'user_pass' => $_POST['pass1'] );
				} else {
					$user_new_datas = array( 'user_login' => $_POST['username'], 'user_email' => $_POST['email'] );	
				}
				update_user_meta($current_user->ID, 'user_edited_user_name', $_POST['username'] );
				update_user_meta($current_user->ID, 'user_edited_user_email', $_POST['email'] );
				
				$user_id = $current_user->ID;
				
				$name = $_POST['username'];
				$email = $_POST['email'];
				
				 if($name){
					$user_data = wp_update_user( array( 'ID' => $user_id, 'user_nicename' => $name ) );
					$user_data = wp_update_user( array( 'ID' => $user_id, 'display_name' => $name ) );
				 }
					
				 if($email)
				 	$user_data = wp_update_user( array( 'ID' => $user_id, 'user_email' => $email ) );
				//wp_update_user( array( 'ID' => $current_user->ID , 'user_login' => $_POST['username']));
				//die();
				update_user_meta($current_user->ID, 'user_edited_datas_status', 'in-approvel' );
				update_user_meta($current_user->ID, 'user_edited_datas', serialize($user_new_datas) );
				$success =  '<p class="success_msg">Your account changes have been saved</p>';
				//$success = $result['message'];
				/*$result = send_edit_profile_notification($current_user->ID, $current_user->data->user_login, $_POST['email'], 'in-approvel' );
				if( $result['action'] == 'success' ) {
					$success = $result['message'];
				} else {
					$error[] = $result['message'];
				}*/
			}
		} else {
			$error[] = __('The email is required.', 'profile');
		}
		
		if( $error ) {
			//return array('action' => 'error', 'message' => $error );
			echo '<p class="error_msg">'.$error[0].'</p>';
		} else {
			//echo $_POST['username'];
			//print_r($success);
			//die();
			//return array('action' => 'success', 'message' => $success );
			echo  $success;
		}
		
	}
}

function check_user_name_in_user_meta($username) {
	global $wpdb;
	return $sql = $wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'user_edited_user_name' AND meta_value = '$username'");
}

function check_email_in_user_meta($email) {
	global $wpdb;
	return $sql = $wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'user_edited_user_email' AND meta_value = '$email'");
}

function send_edit_profile_notification( $user_id = '', $username, $user_email = '', $status = '' ) {
	
	$status_base_1 = base64_encode($status);
	$status = base64_encode($status_base_1);
	
	$activation_key_base_1 = base64_encode($user_id);
	$activation_key = base64_encode($activation_key_base_1);
	
	$activation_url = get_option( 'siteurl' )."/login/?status=".$status."&update_access_key=".$activation_key;
			
	$to = $user_email;
	$subject = 'Hyroglf Confirmation';
	$message = $username.',<br/><hr/>';
	//$message .= "<p>One step more</p>";
	//$message .= "Sent from <a href='mailto:info@hyroglf.com'>info@hyroglf.com</a><p style='line-height:1; font-size:16px;'>Confirm your email address to complete your Hyroglf registration. Just click the button below.</p>";
	$message .= '<p>Sent from <a href="mailto:info@hyroglf.com">info@hyroglf.com</a>.Thank you for signing up with Hyroglf! Your account is now inactive.</p>';
	$message .= '<p>Please click the below url to active your account.</p>';
	$message .= '<a style="background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;" href="'.$activation_url.'">Confirm now</a>';
	$message .= 'Regards,<br/>';
	$message .=  get_option( 'blogname' ); 
	$headers = array('Content-Type: text/html; charset=UTF-8');
	add_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	if( wp_mail( $to, $subject, $message, $headers) ) {
		//$return = array('action' => 'success', 'message'=> '<p class="success_msg">Please check your mail for confirmation to update your account.</p>');
		$return = array('action' => 'success', 'message'=> '<p class="success_msg">Your account changes have been saved</p>');
	} else {
		$return = array('action' => 'error', 'message'=> '<p class="error_msg">Sorry some technical problem occurred please again later!</p>');	
	}
	remove_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	return $return;
}

if( ! empty( $_GET['status'] ) && ! empty( $_GET['update_access_key'] ) ) {
	add_action('init', 'send_confirmation_update_user_profile');
}
//add_action('init', 'confirmation_update_user_profile');
function send_confirmation_update_user_profile() {
	$reuslt = '';
	/*global $current_user, $wp_roles;
	$user_edited_datas = get_user_meta( $current_user->ID, 'user_edited_datas', true );
	$user_edited_datas = unserialize($user_edited_datas);
	echo '<pre>'; print_r($user_edited_datas);
	die();*/
	$status_base_1 = base64_decode($_GET['status']);
	$status = base64_decode($status_base_1);
	
	$activation_key_base_1 = base64_decode($_GET['access_key']);
	$user_id = base64_decode($activation_key_base_1);
	
	$user_account_status = get_user_meta( $user_id, 'user_edited_datas_status', true );
	$user_temp_pass = get_user_meta( $user_id, 'user_temp_pass', true );
				
	if( $status == 'in-approvel' || $_GET['status'] == 'in-approvel' && isset( $_GET['update_access_key'] ) ) {
		$result = update_user_meta( $user_id, 'user_edited_datas_status', 'approvel' );
		
		if( $result ) {
			$user_edited_datas = get_user_meta( $user_id, 'user_edited_datas', true );
			$user_edited_datas = unserialize($user_edited_datas);
			if( $user_edited_datas ) {
				
				$user_id = $user_id;
				$username = ($user_edited_datas['user_login']) ? $user_edited_datas['user_login'] : '';
				$user_pass = ($user_edited_datas['user_pass']) ? $user_edited_datas['user_pass'] : '';
				$user_email = ($user_edited_datas['user_email']) ? $user_edited_datas['user_email'] : '';
				$display_name = ($user_edited_datas['user_login']) ? $user_edited_datas['user_login'] : '';
				$status = 'approvel';
				
				if($user_edited_datas['user_pass']) {
					wp_update_user(
						array(
							'ID' 			=> $user_id,
							'user_login' 	=> $username,
							'user_pass' 	=> $user_pass,
							'user_email' 	=> $user_email,
							'display_name' 	=> $display_name
						));	
				} else {
					wp_update_user(
						array(
							'ID' 			=> $user_id,
							'user_login' 	=> $username,
							'user_email' 	=> $user_email,
							'display_name' 	=> $display_name
						));	
				}
							
				$reuslt = send_notification_profile_update_approvel_user( $user_id, $username, $user_pass, $user_email, $status );

				if($reuslt['action'] == 'success' ) {
					add_filter('hyroglf_login_message', 'edit_profile_login_credential_success_message');
				} else {
					add_filter('hyroglf_login_message', 'edit_profile_login_credential_error_message');
				}
				
				
				
				
			}	
		}
	}
	return $reuslt;
}

function send_notification_profile_update_approvel_user( $user_id = '', $username, $password = '', $user_email = '', $status = '' ) {
	
	$login_url = get_option( 'siteurl' )."/login/";
			
	$to = $user_email;
	$subject = 'Hyroglf Contributor registration';
	$message = '<p>We are pleased to confirm your profile update for Hyroglf. Your account is now updated.</p>';
	$message .= '<p>Below is your login credential.</p>';
	$message .= 'Hi '.$username.',<br/>';
	$message .= '<lable>Username :</label> '.$username.'<br/>';
	
	if( $password ) {
		$message .= '<lable>Password :</lable> '.$password;	
	}
	
	$message .= '<p>'.$login_url.'</p><br/>';
	$message .= 'Regards,<br/>';
	$message .=  get_option( 'blogname' ); 
	$headers = array('Content-Type: text/html; charset=UTF-8');
	add_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	if( wp_mail( $to, $subject, $message, $headers) ) {
		$result = array('action' => 'success');
	} else {
		$result = array('action' => 'error');
	}
	remove_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	return $result;
}

function edit_profile_login_credential_success_message() {
	return '<p class="success_msg">Your profile is now updated. Please check your mail for your login credential</p>';
}

function edit_profile_login_credential_error_message() {
	return '<p class="error_msg">Sorry some technical problem occurred please try again later.</p>';
}

// Forgot password
add_shortcode( 'wp_recover_password', 'forgot_password' );
function forgot_password() {
	$result = wp_recover_password_validation();
	if( $result ) {
		
		//print_r($result);
		if( $result['action'] == 'error' ) {
			//echo '<p class="error_msg">' .$result['message']. '</p>';
			echo $result['message'];
		} /*else if( $result['action'] == 'success' ) {
			echo '<p class="success_msg">' .$result['message']. '</p>';
		}*/
	} ?>
	<form enctype="multipart/form-data" method="post" id="recover-password" class="user-forms" action="">
		<?php /*?><p>Enter your username or email to reset password.<!--Please enter your username or email.--><br><!--You will receive a link to create a new password via email.--></p><?php */?>
        <?php if( $result['action'] == 'success' ) {
			echo '<p class="success_msg">' .$result['message']. '</p>';
		} ?>
		<ul>
			<li class="wppb-form-field wppb-username-email">
				<label for="username_email">Username or Email</label>
				<input class="text-input" name="username_email" type="text" id="username_email" value="<?php echo ($_POST['username_email']) ? $_POST['username_email'] : '';?>">
			</li>
	   </ul>
	   <p class="form-submit">
			<input name="recover_password" type="submit" id="recover-password-button" class="submit button" value="Send Reset Link">
			<input name="action" type="hidden" id="action" value="recover_password">
		</p>
	</form><?php
}

function wp_recover_password_validation() {
    global $current_user, $wp_roles;
	$return = array();
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'recover_password' ) {
		$error = array();
		$success = array();
	
		$user_login = trim($_POST['username_email']);
	
		$exists_user = username_exists( $user_login );
		$exists_email = email_exists( esc_attr( $user_login ) );
		
		$class = '"error_msg wppb-forgot-password-error"';
	
		if ( empty( $user_login ) ) {
			$error = __('The username or e-mail is required.', 'recover_password');
		} else if ( strpos( $user_login, '@' ) && strpos( $user_login, '.' ) ) {
			
			if ( !is_email( esc_attr( $user_login ) ) ) {
				$error = __('The Email you entered is not valid.', 'recover_password');
			} else if(!$exists_email) {
				
				$error = __("<p class=$class>Username or email not registered. Try again.</p>", 'recover_password');
			} else {
				 $user_id = get_user_by( 'email', $user_login )->ID;
				 $user_ac_status = get_user_meta( $user_id, 'user_account_status','true');
				 
				 if(is_email( esc_attr( $user_login ) ) && $user_ac_status != 'inactive'){
				 	 $user_data = get_user_by( 'email', $user_login );
				 } else {
					//echo "hai";
					$error = __("<p class=$class>Your account is inactive</p>");
				 }
			}
		} else {
			if(!$exists_user) {
				$error = __("<p class=$class>Username or email not registered. Try again.</p>", 'recover_password');
			} else {
				 $user_id = get_userdatabylogin($user_login)->ID;
				 $user_ac_status = get_user_meta( $user_id, 'user_account_status','true');
				 if($exists_user && $user_ac_status != 'inactive' ){
				 	$user_data = get_user_by('login', $user_login);
				 } else {
					 $error = __("<p class=$class>Your account is inactive</p>");
				 }
			}
		}
	
		if ( $user_data ) {
			
			$user_id = $user_data->ID;
			$user_login = $user_data->data->user_login;
			$user_email = $user_data->data->user_email;
			
			$result = send_recover_password_link($user_id, $user_login, $user_email);
			
			if( $result['action'] == 'success' ) {
				$success = $result['message'];
				$success = 'Check your email to reset password.';
			} else {
				$error = $result['message'];
			}
		}
		
		if( $error ) {
			$return = array('action' => 'error', 'message' => $error );
		} else {
			$return = array('action' => 'success', 'message' => $success );
		}
	
	}
	return $return;
}

function send_recover_password_link( $user_id = '', $user_name = '', $user_email = '' ) {
	
	$user_id_1 = base64_encode($user_id);
	$user_id = base64_encode($user_id_1);
	
	$token_for_1 = base64_encode('change_pass');
	$token_for = base64_encode($token_for_1);
	
	$url = get_option( 'siteurl' )."/change-password/?token_key=".$user_id."&token_for=".$token_for;
			
	$to = $user_email;
	$subject = 'Hyroglf Reset Password';
	$message = '<div class="mail_div" style="max-width:320px;"><h6 style="border-bottom:1px solid #ccc; padding:10px 0px; font-size:15px; color:rgb(34, 34, 34); margin:0px; font-weight:400;">Hi '.$user_name.',</h6>';
	$message .= '<p style="font-size:15px;margin:15px 0px 15px 0px;">If you requested a password reset for your Hyroglf account, click the button below. If you didn\'t make this request, please ignore this email.</p>';
	$message .= '<p><a style="background:#f5bd5b;color:#ffffff;border-radius:4px;padding:8px 25px;display:inline-block; margin: 5px 17px 5px 0px; text-decoration: none;" href="'.$url.'">Reset Password</a></p><div class="yj6qo"></div><div class="adL"><p></p><p></p></div></div>';
	//$message .= '<p>Regards,</p><br/>';
	//$message .=  get_option( 'blogname' ); 
	$headers = array('Content-Type: text/html; charset=UTF-8');
	add_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	if( wp_mail( $to, $subject, $message, $headers) ) {
		$return = array('action' => 'success', 'message'=> '<p class="success_msg"></p>');
	} else {
		$return = array('action' => 'error', 'message'=> '<p class="error_msg">Sorry some technical problem occurred please again later!</p>');	
	}
	remove_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	return $return;
}

add_shortcode( 'wp_change_password', 'change_password_form' );
function change_password_form() {
	global $current_user;
	$result = change_password_validation();
	if( $result ) {
		if( $result['action'] == 'error' ) {
			echo '<p class="error_msg">' .$result['message']. '</p>';
		} else if( $result['action'] == 'success' ) {
			echo '<p class="success_msg">' .$result['message']. '</p>';
		}
	}
	if( $current_user->ID || isset( $_GET['token_key'] ) && $_GET['token_for'] ) { ?>
    	<div class="frm_error_msg"></div>
    	<form method="post" id="adduser" action="">
            <p class="form-password">
                <label for="pass1"><?php _e('New Password <span class="field-required">*</span>', 'recover_password'); ?> </label>
                <input class="text-input" name="pass1" type="password" id="pass1" />
                <span id="result" class=""></span>
            </p>
            <p class="form-password">
                <label for="pass2"><?php _e('Re-enter New Password <span class="field-required">*</span>', 'recover_password'); ?></label>
                <input class="text-input" name="pass2" type="password" id="pass2" />
                <span id="result2" class=""></span>
            </p>
            <p class="form-submit">
                <input name="edit_profile" type="submit" id="edit_profile" class="submit button" value="<?php _e('Submit', 'recover_password'); ?>" />
                <input name="action" type="hidden" id="action" value="recover_password" />
            </p>   
        </form>
        <p class="error_msg password_match_error" style="display:none;">Passwords must match!</p>
	<?php
    } else {?>
    	<p class="error_msg">Sorry token key is missing!</p>
        <?php
	}
}

function change_password_validation() {
	/* Get user info. */
	global $current_user, $wp_roles;
	//echo '<pre>'; print_r($_SERVER['REQUEST_METHOD']);
	$return  = array();
	$error = '';
	$success = '';   
	/* If profile was saved, update profile. */
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'recover_password' ) {
		if( isset( $_GET['token_key'] ) && $_GET['token_for'] || $current_user->ID ) {
			if(isset( $_GET['token_key'] )){
				$user_id_1 = base64_decode($_GET['token_key']);
				$token_key = base64_decode($user_id_1);
			}
			if(isset( $_GET['token_for'] )){
				$token_for_1 = base64_decode($_GET['token_for']);
				$token_for = base64_decode($token_for_1);
			}
			
			if($current_user->ID) {
				$user_id = $current_user->ID;
			} else if($token_key && $token_for == 'change_pass' ) {
				$user_id = $token_key;
			}
			
			/* Update user password. */
			if( empty( $_POST['pass1'] ) ) {
				$error = __('The passwords is required.', 'profile');
			} else if( empty( $_POST['pass2'] ) ) {
				$error = __('The repeat password is required.', 'profile');
			} else {
				$result = wp_update_user(array('ID' => $user_id,'user_pass' => $_POST['pass1'] ));
				if($result) {
					
					$success = 'Password successfully changed!';?>
                    <script>
					 	window.location.href= '<?php echo wp_logout_url(home_url('login?password_changed=successful'));  ?>';
					</script><?php
				} else {
					$error = 'Sorry your password not changed. try again!';
				}
			}
			
			$user_data = get_user_by('id', $user_id);
	
			if ( $user_data ) {
				
				$user_id = $user_data->ID;
				$user_login = $user_data->data->user_login;
				$user_email = $user_data->data->user_email;
				
				//$result = send_change_password_notification($user_id, $user_login, $user_email, 'password-changed' );
				
				if( $result['action'] == 'success' ) {
					$success = $result['message'];
				} else {
					$error = $result['message'];
				}
				
			}
			
			if( $error ) {
				$return  = array('action' => 'error', 'message' => $error );
			} else {
				$return  = array('action' => 'success', 'message' => $success );
			}
		}
	}
	return $return;
}

function send_change_password_notification($user_id = '', $user_name = '', $user_email = '', $action = '' ) {
	$to = $user_email;
	$subject = 'Change your password?';
	$message = 'Hi '.$user_name.',<br/>';
	$message .= "<p>If you requested a password change for @hyroglf click the button below.If you didn't make this request,ignore this email.</p>";
	
	$message .= '<a style="background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;" href="'.home_url().'">Reset Password</a>';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	add_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	if( wp_mail( $to, $subject, $message, $headers) ) {
		$return = array('action' => 'success', 'message'=> '<p class="success_msg">Password successfully reset!".</p>');
	} else {
		$return = array('action' => 'error', 'message'=> '<p class="error_msg">Sorry some technical problem occurred please again later!</p>');	
	}
	remove_filter( 'wp_mail_content_type', 'set_mail_html_content_type' );
	return $return;
}
}