<?php

class register_wid extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'register_wid',
			'Register Widget AFO',
			array( 'description' => __( 'This is a simple register form in the widget.', 'wp-register-profile-with-shortcode' ), )
		);
		add_action( 'init', array($this, 'register_validate' ) );
	 }

	public function widget( $args, $instance ) {
		extract( $args );
		
		$wid_title = apply_filters( 'widget_title', $instance['wid_title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $wid_title ) )
			echo $args['before_title'] . $wid_title . $args['after_title'];
			$this->registerForm_1();
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wid_title'] = sanitize_text_field( $new_instance['wid_title'] );
		return $instance;
	}


	public function form( $instance ) {
		$wid_title = $instance[ 'wid_title' ];
		?>
		<p><label for="<?php echo $this->get_field_id('wid_title'); ?>"><?php _e('Title','wp-register-profile-with-shortcode'); ?> </label>
		<input class="widefat" id="<?php echo $this->get_field_id('wid_title'); ?>" name="<?php echo $this->get_field_name('wid_title'); ?>" type="text" value="<?php echo $wid_title; ?>" />
		</p>
		<?php 
	}
	
	public function is_field_enabled($value){
		$data = get_option( $value );
		if($data == 'Yes'){
			return true;
		} else {
			return false;
		}
	}
	
	public function is_field_required($value){
		$data = get_option( $value );
		if($data == 'Yes'){
			return 'required="required"';
		} else {
			return '';
		}
	}
	
	public function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
	public function registerForm_1(){
		global $post;
		$default_registration_form_hooks = get_option('default_registration_form_hooks'); 
		
		if(!is_user_logged_in()){
			if(get_option('users_can_register')) {  
		?>
       <?php /*?> <?php
		if(isset($_SESSION['delete_account_message'])){
			echo '<span class="deleted_account_message">'.$_SESSION['delete_account_message'].'</span>';
		}
		session_destroy();?><?php */?>
            <script src='https://www.google.com/recaptcha/api.js'></script>
		<form name="register" id="register" method="post" action="" enctype="multipart/form-data">
        
        <input type="hidden" name="contributor_role" id="contributor_role" value="contributor" />
		<input type="hidden" name="option" value="afo_user_register" />
        
        <input type="hidden" name="redirect" value="<?php echo sanitize_text_field( $this->curPageURL() ); ?>" />
		<div id="reg_forms" class="reg_forms">
			<?php $this->error_message();?>
			
            <?php if($this->is_field_enabled('username_in_registration')){ ?>
            <div class="form-group">
				<?php /*?><label for="username"><?php _e('Username (cannot be email)','wp-register-profile-with-shortcode');?> </label><?php */?>
				<input type="text" name="user_login" value="<?php if(isset($_SESSION['wp_register_temp_data']['user_login'])){ echo sanitize_text_field($_SESSION['wp_register_temp_data']['user_login']);}?>" required="required" placeholder="<?php _e('Username (cannot be email)','wp-register-profile-with-shortcode');?>" id="user_login" onblur="allLetter(this);"/>
			</div>
            <span class="error"></span>
			<?php } ?>
            
			<div class="form-group">
				<?php /*?><label for="useremail"><?php _e('Email','wp-register-profile-with-shortcode');?> </label><?php */?>
				<input type="email" name="user_email" value="<?php if(isset($_SESSION['wp_register_temp_data']['user_login'])) { echo sanitize_text_field($_SESSION['wp_register_temp_data']['user_email']); }?>" required="required" placeholder="<?php _e('Email','wp-register-profile-with-shortcode');?>"/>
			</div>
            
			
			<?php if($this->is_field_enabled('password_in_registration')){ ?>
			<div class="form-group password_register_field">
			<?php /*?><label for="password"><?php _e('Password','wp-register-profile-with-shortcode');?> </label><?php */?>
			<input type="password" name="new_user_password" id="new_user_password" required="required" placeholder="<?php _e('Password','wp-register-profile-with-shortcode');?>" />
            <span id="result" class=""></span>
			</div>
			
			<div class="form-group password_register_field">
			<?php /*?><label for="retypepassword"><?php _e('Re-enter Password','wp-register-profile-with-shortcode');?> </label><?php */?>
			<input type="password" name="re_user_password" id="re_user_password" required="required" placeholder="<?php _e('Re-enter Password','wp-register-profile-with-shortcode');?>"  />
            <span id="result2" class=""></span>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('firstname_in_registration')){ ?>
			<div class="form-group">
			<?php /*?><label for="firstname"><?php _e('First Name','wp-register-profile-with-shortcode');?> </label><?php */?>
			<input type="text" name="first_name" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['first_name']);?>" <?php echo $this->is_field_required('is_firstname_required');?> placeholder="<?php _e('First Name','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('lastname_in_registration')){ ?>
			<div class="form-group">
			<?php /*?><label for="lastname"><?php _e('Last Name','wp-register-profile-with-shortcode');?> </label><?php */?>
			<input type="text" name="last_name" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['last_name']);?>" <?php echo $this->is_field_required('is_lastname_required');?> placeholder="<?php _e('Last Name','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('displayname_in_registration')){ ?>
			<div class="form-group">
			<?php /*?><label for="displayname"><?php _e('Display Name','wp-register-profile-with-shortcode');?> </label><?php */?>
			<input type="text" name="display_name" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['display_name']);?>" <?php echo $this->is_field_required('is_displayname_required');?> placeholder="<?php _e('Display Name','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('userdescription_in_registration')){ ?>
			<div class="form-group">
			<?php /*?><label for="aboutuser"><?php _e('About User','wp-register-profile-with-shortcode');?> </label><?php */?>
			<textarea name="description" <?php echo $this->is_field_required('is_userdescription_required');?>><?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['description']);?></textarea>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('userurl_in_registration')){ ?>
			<div class="form-group">
			<?php /*?><label for="website"><?php _e('Website','wp-register-profile-with-shortcode');?> </label><?php */?>
			<input type="url" name="user_url" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['user_url']);?>" <?php echo $this->is_field_required('is_userurl_required');?> placeholder="<?php _e('Website','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php do_action('wp_register_profile_subscription'); ?>
            
            <?php /*if($this->is_field_enabled('captcha_in_registration')){ ?>
			<div class="form-group">
			<label for="website"><?php _e('Captcha','wp-register-profile-with-shortcode');?> </label>
            <?php $this->captchaImage();?>
			<input type="text" name="user_captcha" required="required"/>
			</div>
			<?php } */
			?>
            
           <div class="g-recaptcha" data-sitekey="6Lc50RsUAAAAABQ0c6uRBVENM1eNm-_znefk8XK2"></div>
            
            
            <?php //$default_registration_form_hooks == 'Yes'?do_action('register_form'):'';?>
			
			<div class="form-group"><input name="register" id="addnewuser" type="submit" value="<?php _e('Register','wp-register-profile-with-shortcode');?>" /></div>

		</div>
		</form>
        <p class="error_msg password_match_error" style="display:none;">Passwords must match!</p>

		<?php 
		} else {
			echo '<div id="reg_forms"><p>'.__('Sorry. Registration is not allowed in this site.','wp-register-profile-with-shortcode').'</p></div>';
		}
		}
	}
	
	public function registerForm_2(){
		global $post;
		$default_registration_form_hooks = get_option('default_registration_form_hooks'); 
		
		if(!is_user_logged_in()){
			if(get_option('users_can_register')) {  
		?>
		<form name="register" id="register" method="post" action="" enctype="multipart/form-data">
        
        <input type="hidden" name="user_role" id="user_role" value="user" />
		<input type="hidden" name="option" value="afo_user_register" />
        <input type="hidden" name="redirect" value="<?php echo sanitize_text_field( $this->curPageURL() ); ?>" />
		<div id="reg_forms" class="reg_forms">
			<?php $this->error_message();?>
			
            <?php if($this->is_field_enabled('username_in_registration')){ ?>
            <div class="form-group">
				<label for="username"><?php _e('Username','wp-register-profile-with-shortcode');?> </label>
				<input type="text" name="user_login" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['user_login']);?>" required="required" placeholder="<?php _e('Username','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
            
			<div class="form-group">
				<label for="useremail"><?php _e('User Email','wp-register-profile-with-shortcode');?> </label>
				<input type="email" name="user_email" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['user_email']);?>" required="required" placeholder="<?php _e('User Email','wp-register-profile-with-shortcode');?>"/>
			</div>
			
			<?php if($this->is_field_enabled('password_in_registration')){ ?>
			<div class="form-group">
			<label for="password"><?php _e('Password','wp-register-profile-with-shortcode');?> </label>
			<input type="password" name="new_user_password" required="required" placeholder="<?php _e('Password','wp-register-profile-with-shortcode');?>" />
			</div>
			
			<div class="form-group">
			<label for="retypepassword"><?php _e('Retype Password','wp-register-profile-with-shortcode');?> </label>
			<input type="password" name="re_user_password" required="required" placeholder="<?php _e('Retype Password','wp-register-profile-with-shortcode');?>" id="re_user_password"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('firstname_in_registration')){ ?>
			<div class="form-group">
			<label for="firstname"><?php _e('First Name','wp-register-profile-with-shortcode');?> </label>
			<input type="text" name="first_name" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['first_name']);?>" <?php echo $this->is_field_required('is_firstname_required');?> placeholder="<?php _e('First Name','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('lastname_in_registration')){ ?>
			<div class="form-group">
			<label for="lastname"><?php _e('Last Name','wp-register-profile-with-shortcode');?> </label>
			<input type="text" name="last_name" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['last_name']);?>" <?php echo $this->is_field_required('is_lastname_required');?> placeholder="<?php _e('Last Name','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('displayname_in_registration')){ ?>
			<div class="form-group">
			<label for="displayname"><?php _e('Display Name','wp-register-profile-with-shortcode');?> </label>
			<input type="text" name="display_name" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['display_name']);?>" <?php echo $this->is_field_required('is_displayname_required');?> placeholder="<?php _e('Display Name','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('userdescription_in_registration')){ ?>
			<div class="form-group">
			<label for="aboutuser"><?php _e('About User','wp-register-profile-with-shortcode');?> </label>
			<textarea name="description" <?php echo $this->is_field_required('is_userdescription_required');?>><?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['description']);?></textarea>
			</div>
			<?php } ?>
			
			<?php if($this->is_field_enabled('userurl_in_registration')){ ?>
			<div class="form-group">
			<label for="website"><?php _e('Website','wp-register-profile-with-shortcode');?> </label>
			<input type="url" name="user_url" value="<?php echo sanitize_text_field($_SESSION['wp_register_temp_data']['user_url']);?>" <?php echo $this->is_field_required('is_userurl_required');?> placeholder="<?php _e('Website','wp-register-profile-with-shortcode');?>"/>
			</div>
			<?php } ?>
			
			<?php do_action('wp_register_profile_subscription'); ?>
            
            <?php /* if($this->is_field_enabled('captcha_in_registration')){ ?>
			<div class="form-group">
			<label for="website"><?php _e('Captcha','wp-register-profile-with-shortcode');?> </label>
            <?php $this->captchaImage();?>
			<input type="text" name="user_captcha" required="required"/>
			</div>
			<?php } ?>
            
            <?php $default_registration_form_hooks == 'Yes'?do_action('register_form'):'';*/?>
			
			<div class="form-group"><input name="register" id="addnewuser" type="submit" value="<?php _e('Register','wp-register-profile-with-shortcode');?>" /></div>

		</div>
		</form>

		<?php 
		} else {
			echo '<div id="reg_forms"><p>'.__('Sorry. Registration is not allowed in this site.','wp-register-profile-with-shortcode').'</p></div>';
		}
		}
	}
	
	public function captchaImage(){
	?>
	<div>
    <img src="<?php echo plugin_dir_url( __FILE__ ).'captcha/captcha.php';?>" id="captcha">
	<br /><a href="javascript:refreshCaptcha();"><?php _e('Reload Image','wp-register-profile-with-shortcode');?></a>
	</div>
    <script type="application/javascript">
	function refreshCaptcha(){ document.getElementById('captcha').src = '<?php echo plugin_dir_url( __FILE__ ).'captcha/captcha.php'?>?rand='+Math.random(); }
	</script>
    <?php
	}
	
	public function error_message(){
		if(isset($_SESSION['reg_error_msg']) and $_SESSION['reg_error_msg']){
			echo '<div class="'.$_SESSION['reg_msg_class'].'">'.$_SESSION['reg_error_msg'].'</div>';
			unset($_SESSION['reg_error_msg']);
			unset($_SESSION['reg_msg_class']);
		}
	}
	
	public function set_html_content_type() {
		return 'text/html';
	}
	
	public function create_user($data, $user_role){
		global $wprw_mail_to_user_subject,$wprw_mail_to_admin_subject;
		$wprw_admin_email = get_option( 'wprw_admin_email' );
		$wprw_from_email = get_option( 'wprw_from_email' );
		if($wprw_from_email == ''){
			$wprw_from_email = 'no-reply@wordpress.com';
		}
		
		$userdata = $data['userdata'];
		
		// insert new user in db //
			$user_id = wp_insert_user( $userdata );
			$user = get_userdata( $user_id );
			$user->set_role( $user_role['role'] );
		// insert new user in db //
		
		$headers[] = 'From: <'.$wprw_from_email.'>';
		
		// send mail to user //
		$subject = get_option('new_user_register_mail_subject');
		$body = $this->new_user_data_to_user_mail( $userdata );
		$body = html_entity_decode($body);
		
		$to_array = array($userdata['user_email']);
		add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		//wp_mail( $to_array, $subject, $body, $headers );
		remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		// send mail to user //
		
		// send mail to admin //
		if($wprw_admin_email){
			$subject1 = __($wprw_mail_to_admin_subject,'wp-register-profile-with-shortcode');
			$body1 = $this->new_user_data_to_admin_mail( $userdata );
			$body1 = html_entity_decode($body1);
			add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
			wp_mail( $wprw_admin_email, $subject1, $body1, $headers );
			remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		}
		// send mail to admin //
		$user_info = get_userdata($user_id);
		$user_login = "'".$user_info->user_login."'";
		$user_email = "'".$user_info->user_email."'";
		if( !$this->is_field_enabled('force_login_after_registration') and $user_id ){
			$_SESSION['reg_error_msg'] = __('Thanks for registering! Please check your e-mail to confirm registration. Click <a href="javascript:void(0);" onclick="fnResendActivationMail2('.$user_login.','.$user_email.');" >here</a> to resend confirmation.','wp-register-profile-with-shortcode');
			$_SESSION['reg_msg_class'] = 'reg_success';
		}
		
		unset($_SESSION['wp_register_temp_data']);
		return $user_id;
	}
	
	public function new_user_data_to_user_mail( $userdata = array() ){
		$wprw_mail_to_user_body = get_option('new_user_register_mail_body');
		
		$wprw_mail_to_user_body = str_replace(array("#site_name#","#user_name#","#user_password#","#site_url#"), array( get_bloginfo('name'), $userdata['user_login'], $userdata['user_pass'], site_url() ), $wprw_mail_to_user_body);
		return $wprw_mail_to_user_body;
	}
	
	public function new_user_data_to_admin_mail( $userdata = array() ){
		global $wprw_mail_to_admin_body;
		$data = '';
		
		if(!empty($userdata['user_login'])){
			$data .= '<strong>User Name:</strong> '.$userdata['user_login'];
			$data .= '<br>';
		}
		if(!empty($userdata['user_email'])){
			$data .= '<strong>User Email:</strong> '.$userdata['user_email'];
			$data .= '<br>';
		}
		if(!empty($userdata['first_name'])){
			$data .= '<strong>First Name:</strong> '.$userdata['first_name'];
			$data .= '<br>';
		}
		if(!empty($userdata['last_name'])){
			$data .= '<strong>Last Name:</strong> '.$userdata['last_name'];
			$data .= '<br>';
		}
		if(!empty($userdata['display_name'])){
			$data .= '<strong>Display Name:</strong> '.$userdata['display_name'];
			$data .= '<br>';
		}
		if(!empty($userdata['description'])){
			$data .= '<strong>About User:</strong> '.$userdata['description'];
			$data .= '<br>';
		}
		if(!empty($userdata['user_url'])){
			$data .= '<strong>User Url:</strong> '.$userdata['user_url'];
			$data .= '<br>';
		}
		
		$wprw_mail_to_admin_body = str_replace( array("#site_name#","#new_user_data#"), array(get_bloginfo('name'),$data ), $wprw_mail_to_admin_body);
		
		return $wprw_mail_to_admin_body;
	}
	
	public function register_validate(){
		if(isset($_POST['option']) and sanitize_text_field($_POST['option']) == "afo_user_register"){
			
			global $post;
			$error = false;
			$comp_errors = array();
			$msg = '';
			$_SESSION['wp_register_temp_data'] = $_POST;
			
			// validation compatibility filter //
			$default_registration_form_hooks = get_option('default_registration_form_hooks'); 
			if($default_registration_form_hooks == 'Yes'){
				$comp_validation = apply_filters('registration_errors', $comp_errors);
				if ( is_wp_error( $comp_validation ) ) {
					$msg .= __($comp_validation->get_error_message(),'wp-register-profile-with-shortcode');
					$msg .= '</br>';
					$error = true;
				
				}
			}
			// validation compatibility filter //
			
			if($this->is_field_enabled('captcha_in_registration')){ 
				if ( sanitize_text_field($_POST['user_captcha']) != $_SESSION['captcha_code'] ){
					$msg .= __('Wrong code! Try again!','wp-register-profile-with-shortcode');
					$msg .= '</br>';
					$error = true;
				}
			}
			
			if(!$this->is_field_enabled('username_in_registration') and empty($_POST['user_login'])){
				$_POST['user_login'] = sanitize_text_field($_POST['user_email']);
			}
			
			if ( username_exists( sanitize_text_field($_POST['user_login']) ) ){
				
				$user = get_user_by('login', $_POST['user_login']);
				
				/*$msg .= __('Username already taken. Enter another one.','wp-register-profile-with-shortcode');
				$msg .= '</br>';*/
				$error = true;
			}
			
			if( email_exists( sanitize_text_field($_POST['user_email']) )) {
				$user = get_user_by('email', $_POST['user_email']);
				//$msg .= __('E-mail already registered. Enter another one.','wp-register-profile-with-shortcode');
				//$msg .= '</br>';
				$error = true;
			}
			
			if( $error ) {
				if($user) {
					 $status = get_user_meta( $user->ID, 'user_account_status', true );
					 
					
					
					// Is the use logging in disabled?
					if ( $status == 'inactive' ) {
						$user_id = base64_encode($user->ID);
						$user_id = base64_encode($user_id);
					}
					if( email_exists( sanitize_text_field($_POST['user_email']) )) {
						
						//$action = '<a href="'.home_url('/contributor-registration?status=inactive&access_key='.$user_id).'">here</a>';
						$action = '<a href="javascript:void(0);" onclick="fnResendActivationMail();" >here</a>';
						//$msg .= __('Before logging in, you must confirm registration. Click '.$action.' to resend confirmation e-mail.');
						$msg .= __('Sorry this email address is already registered.');
					}else{
						//$msg .= __('Email already registered.<a href="forgot-password" title="Password Reset?"">Password Reset?</a>');
						$msg .= __('Sorry this username is taken.');
					}
				}
			}
			
			if($this->is_field_enabled('password_in_registration')){ 
				if(sanitize_text_field($_POST['new_user_password']) != sanitize_text_field($_POST['re_user_password'])){
					$msg .= __('Password and Retype password do not match!','wp-register-profile-with-shortcode');
					$msg .= '</br>';
					$error = true;
				}
			}
			
			if(!$error){
				$userdata = array(
					'user_login' => sanitize_text_field($_POST['user_login']),
					'user_email' => sanitize_text_field($_POST['user_email'])
					);
					
				$user_role = array();
				if($_POST['contributor_role']) {
					$user_role = array('role' => 'contributor');
				} else if($_POST['user_role']) {
					$user_role = array('role' => 'user');
				}
				
				if($this->is_field_enabled('password_in_registration') and sanitize_text_field($_POST['new_user_password'])){
					$new_pass = sanitize_text_field($_POST['new_user_password']);
					$userdata['user_pass'] = $new_pass;
				} else {
					$new_pass = wp_generate_password();
					$userdata['user_pass'] = $new_pass;
				}
				
				if($this->is_field_enabled('firstname_in_registration') and sanitize_text_field($_POST['first_name'])){
					$userdata['first_name'] = sanitize_text_field($_POST['first_name']);
				}
				
				if($this->is_field_enabled('lastname_in_registration') and sanitize_text_field($_POST['last_name'])){
					$userdata['last_name'] = sanitize_text_field($_POST['last_name']);
				}
				
				if($this->is_field_enabled('displayname_in_registration') and sanitize_text_field($_POST['display_name'])){
					$userdata['display_name'] = sanitize_text_field($_POST['display_name']);
				}
				
				if($this->is_field_enabled('userdescription_in_registration') and sanitize_text_field($_POST['description'])){
					$userdata['description'] = sanitize_text_field($_POST['description']);
				} 
				
				if($this->is_field_enabled('userurl_in_registration') and sanitize_text_field($_POST['user_url'])){
					$userdata['user_url'] = sanitize_text_field($_POST['user_url']);
				} 
				
				if(get_option('enable_subscription') == 'Yes'){
					$_SESSION['wp_register_subscription']['userdata'] = $userdata;
					$_SESSION['wp_register_subscription']['sub_type'] = sanitize_text_field($_REQUEST['sub_type']);
					$redirect_page = get_permalink(get_option('subscription_page'));
					wp_redirect($redirect_page);
					exit;
				} else {
					$create_user_data['userdata'] = $userdata;
					$user_id = $this->create_user($create_user_data, $user_role);
					
					if( $this->is_field_enabled('force_login_after_registration') and $user_id ){
						$nuser = get_user_by( 'id', $user_id ); 
						if( $nuser ) {
							wp_set_current_user( $user_id, $nuser->user_login );
							wp_set_auth_cookie( $user_id );
							do_action( 'wp_login', $nuser->user_login );
						}
					}
					
					$redirect_page = get_option('thank_you_page_after_registration_url');
					if($redirect_page){
						$redirect =  get_permalink($redirect_page);
					} else {
						$redirect =  sanitize_text_field($_REQUEST['redirect']);
					}
					
					wp_redirect($redirect);
					exit;
				}
			} else {
				$_SESSION['reg_error_msg'] = $msg;
				$_SESSION['reg_msg_class'] = 'reg_error';
			}
		}
	}
	
} 

add_action( 'widgets_init', create_function( '', 'register_widget( "register_wid" );' ) );
?>