<?php @ob_start();
if (!isset($_SESSION)){
	session_start();
}?>
<?php
	require_once("custom_funtion.php"); // Get custom functions

	// Admin Reports
	require_once('admin_reports.php');

	// registration-contributor
	require_once('registration-fun.php');

	// Ajax actions
	require_once('theme_ajax.php');
	$user_info = get_userdata(1);
	function mytheme_enqueue_style() {
		global $wpdb;
		$current_user = wp_get_current_user(); // current user


		wp_register_style('css-reset', get_stylesheet_directory_uri() . '/assets/css/cssreset.css');
		wp_register_style('style', get_stylesheet_directory_uri() . '/style.css');
		wp_register_style('stylesheet', get_stylesheet_directory_uri() . '/assets/css/stylesheet.css?v=105', array( 'style' ));
		wp_register_style('responsive', get_stylesheet_directory_uri() . '/assets/css/responsive.css?v=85', array( 'stylesheet' ));
		wp_register_style('slicknav', get_stylesheet_directory_uri() . '/assets/css/slicknav.min.css', array( 'stylesheet' ));
		wp_register_style('font-awesome', get_stylesheet_directory_uri() . '/assets/css/font-awesome.min.css', array( 'stylesheet' ));
		// wp_register_style('ico-hyroglf', get_stylsheet_directory_uri() . '/assets/css/ico-hyroglf.css', array( 'stylesheet' ));
		wp_register_style('cs-select', get_stylesheet_directory_uri() . '/assets/css/cs-select.css?v=1', array( 'stylesheet' ));
		wp_register_style('cs-skin-elastic', get_stylesheet_directory_uri() . '/assets/css/cs-skin-elastic.css?v=1', array( 'stylesheet' ));
		wp_register_style('drag-drop-css', get_stylesheet_directory_uri() . '/assets/css/drag-drop-style.css', array( 'stylesheet' ));
		wp_register_style('bx_slider_css', get_stylesheet_directory_uri() . '/assets/css/jquery.bxslider.css', array( 'stylesheet' ));
		wp_register_style('J_fancybox', get_stylesheet_directory_uri() . '/assets/css/jquery.fancybox.css', array( 'stylesheet' ));
		wp_register_style('prettyPhoto', get_stylesheet_directory_uri() . '/assets/css/prettyPhoto.css', array( 'stylesheet' ));

		wp_register_script('modernizr', get_stylesheet_directory_uri() . '/assets/js/modernizr.custom.js', array('jquery'), '1.0.0', true);
		wp_register_script('easing', get_stylesheet_directory_uri() . '/assets/js/jquery.easing.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('jslicknav_js', get_stylesheet_directory_uri() . '/assets/js/jquery.slicknav.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('respond-min', get_stylesheet_directory_uri() . '/assets/js/respond.min.js', array('jquery'), '1.0.0', true);

		wp_register_script('validate', get_stylesheet_directory_uri() . '/assets/js/validate.js', array('jquery'), '1.0.0', true);
		wp_register_script('theme_ajax', get_stylesheet_directory_uri() . '/assets/js/theme_ajax.js?v=55', array('jquery'), '1.0.0', true);
		wp_localize_script( 'theme_ajax', 'theme_object', array(
			'category'		=> get_category_fn(),
			'tag'        	=> get_tags_fn(),
			'images'		=> array(
								'plus_sign' => get_stylesheet_directory_uri().'<i class="fa fa-plus fa-2x"></i>',
								'pencil_sign' => get_stylesheet_directory_uri().'<i class="fa fa-edit fa-2x"></i>',
								'close_sign' => get_stylesheet_directory_uri().'<i class="fa fa-close fa-2x"></i>'),
			'site_url'     	=> get_bloginfo('url'),
			'theme_url' 	=> get_bloginfo('template_directory'),
			'user_roll' 	=> ( isset( $current_user->roles[0] ) ) ? $current_user->roles[0] : '',
			'search_key'	=> get_user_search_key(),
			'date'			=> date("m/d/Y h:i:s A"),
			'user_role'		=> ( isset( $user_info->roles ) ) ? implode(', ', $user_info->roles) : '',
			'site_base_url' => get_site_url()

		) );

		wp_register_script('classie', get_stylesheet_directory_uri() . '/assets/js/classie.js', array('jquery'), '1.0.0', true);
		wp_register_script('selectFx', get_stylesheet_directory_uri() . '/assets/js/selectFx.js', array('jquery'), '1.0.0', true);
		wp_register_script('jquery-matchHeight', get_stylesheet_directory_uri() . '/assets/js/jquery.matchHeight.js', array('jquery'), '1.0.0', true);

		wp_register_script('fidvid', get_stylesheet_directory_uri() . '/assets/js/jquery.fitvids.js?v=12', array('jquery'), '1.0.0', true);
		wp_register_script('bx_slider_js', get_stylesheet_directory_uri() . '/assets/js/jquery.bxslider.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('jquery-fancybox', get_stylesheet_directory_uri() . '/assets/js/jquery.fancybox.js', array('jquery'), '1.0.0', true);
		wp_register_script('jquery-fancybox-media', get_stylesheet_directory_uri() . '/assets/js/jquery.fancybox-media.js', array('jquery'), '1.0.0', true);
		wp_register_script('jquery-ui-min', get_stylesheet_directory_uri() . '/assets/js/jquery-ui.min.js', array('jquery'), '1.0.0', true);

		wp_register_script('drag_drop_js', get_stylesheet_directory_uri() . '/assets/js/multiupload.js?v=22', array('jquery'), '1.0.0', true);
		wp_localize_script( 'drag_drop_js', 'drag_drop_object', array(
			'images'	=> get_stylesheet_directory_uri().'/assets/images'
		) );
		wp_register_script('touch-swipe', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js', array('jquery'), '1.0.1', true);
		wp_register_script('jquery-prettyPhoto', get_stylesheet_directory_uri() . '/assets/js/jquery.prettyPhoto.js', array('jquery'), '1.0.1', true);
		//wp_register_script('jquery.wipetouch', get_stylesheet_directory_uri() . '/assets/js/jquery.wipetouch.js', array('jquery'), '1.7.2', true);
		wp_register_script('touch-wipe', get_stylesheet_directory_uri() . '/assets/js/jquery.touchwipe.min.js', array('jquery'), '1.0.1', true);
		wp_register_script('custom', get_stylesheet_directory_uri() . '/assets/js/custom.js?v=61', array('jquery'), '1.0.1', true);

		/*wp_localize_script( 'custom', 'theme_obj', array(
			'scroll_cat'	=> scroll_load_cat_in_sidebar()
		) );*/
		//wp_register_script('tiny', 'https://cloud.tinymce.com/stable/tinymce.min.js', array('jquery'), '1.0.1', true);

		wp_enqueue_style('css-reset');
		wp_enqueue_style('style');
		wp_enqueue_style('stylesheet');
		wp_enqueue_style('responsive');
		wp_enqueue_style('slicknav');

		wp_enqueue_style('font-awesome');
		// wp_enqueue_style('ico-hyroglf');
		wp_enqueue_style('cs-select');
		wp_enqueue_style('cs-skin-elastic');
		wp_enqueue_style('drag-drop-css');
		wp_enqueue_style('bx_slider_css');
		wp_enqueue_style('J_fancybox');
		wp_enqueue_style('prettyPhoto');

		wp_enqueue_script('jquery');
		//wp_enqueue_script('tiny');
		wp_enqueue_script('jquery-ui-min');
		wp_enqueue_script('modernizr');
		wp_enqueue_script('easing');
		wp_enqueue_script('jslicknav_js');
		wp_enqueue_script('respond-min');
		wp_enqueue_script('theme_ajax');
		wp_enqueue_script('classie');
		wp_enqueue_script('selectFx');
		wp_enqueue_script('jquery-matchHeight');
		wp_enqueue_script('drag_drop_js');
		wp_enqueue_script('fidvid');
		wp_enqueue_script('bx_slider_js');
		wp_enqueue_script('jquery-fancybox');
		wp_enqueue_script('jquery-fancybox-media');
		wp_enqueue_script('validate');
		wp_enqueue_script('touch-swipe');
		wp_enqueue_script('jquery-prettyPhoto');
		wp_enqueue_script('touch-wipe');
		wp_enqueue_script('custom');

	}
	add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_style');

	add_action( 'wp_enqueue_scripts', 'angular_scripts' );
	function angular_scripts() {
		$tax 	= ( isset( $_SESSION['tax'] ) ) 	? 	$_SESSION['tax'] 	: '';
		$term 	= ( isset( $_SESSION['term'] ) ) 	? 	$_SESSION['term'] 	: '';
		$load 	= ( isset( $_SESSION['load'] ) ) 	? 	$_SESSION['load'] 	: '';
		//echo $load;

		$term_src 	= ( isset( $_SESSION['src'] ) ) 	? 	$_SESSION['src'] 	: '';
		$term_title 	= ( isset( $_SESSION['term_title'] ) ) 	? 	$_SESSION['term_title'] 	: '';

		if( isset( $_SESSION['tax'] ) ) {
			unset($_SESSION['tax']);
		}
		if( isset( $_SESSION['term'] ) ) {
			unset($_SESSION['term']);
		}
		if( isset( $_SESSION['term_title'] ) ) {
			unset($_SESSION['term_title']);
		}
		if( isset( $_SESSION['load'] ) ) {
			unset($_SESSION['load']);
		}
		if( isset( $_SESSION['src'] ) ) {
			unset($_SESSION['src']);
		}

		global $wpdb;

		$current_user = wp_get_current_user(); // current user
		$user_id = '';
		$access = 0;
		if( $current_user->ID || is_user_logged_in() ) {
			$user_id = $current_user->ID;
			$access = 1;
		}

		$post_arr = '';
		$vote_count_arr = '';
		$post_arr = $wpdb->get_results("SELECT ID FROM ".$wpdb->prefix."posts WHERE post_author =".$current_user->ID." AND post_type = 'post' AND post_status = 'publish'");

		$post_id_arr = array();
		$post_count_arr = array();

		if( $post_arr ) {
			foreach( $post_arr as $posts ) {
				$post_count_arr[] = $posts->ID;
			}
		}

		if( isset( $current_user->ID ) ) {
			$post_arr = $wpdb->get_results("SELECT DISTINCT article_id FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles WHERE user_id =".$current_user->ID);
			if( $post_arr ) {
				$vote_count_arr = array();
				foreach( $post_arr as $posts ) {
					$vote_count_arr[] = $posts->article_id;
				}
			}
		}

		wp_register_script('angular_min', get_stylesheet_directory_uri() . '/angular/js/angular.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('angular_route', get_stylesheet_directory_uri() . '/angular/js/angular-route.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('angular_animate', get_stylesheet_directory_uri() . '/angular/js/angular-animate.min.js', array('jquery'), '1.0.0', true);
		wp_register_script('angular_toaster', get_stylesheet_directory_uri() . '/angular/js/toaster.js', array('jquery'), '1.0.0', true);
		wp_register_script('angular_sanitize', get_stylesheet_directory_uri() . '/angular/js/angular-sanitize.min.js', array('jquery'), '1.0.0', true);

		wp_register_script('angular_app', get_stylesheet_directory_uri() . '/angular/app/app.js?v=11', array('jquery'), '1.0.0', true);
		wp_localize_script( 'angular_app', 'theme_obj', array(
			'db_name'		=> DB_NAME,
			'db_user'		=> DB_USER,
			'db_password'	=> DB_PASSWORD,
			'db_host'		=> DB_HOST,
			'ajax_url' 		=> admin_url('admin-ajax.php'),
			'site_url' 		=> get_site_url(),
			'plugin_url' 	=> plugins_url(),
			'user_id'		=> $user_id,
			'user_role' 	=> ( isset( $current_user->roles[0] ) ) ? $current_user->roles[0] : '',
			'user_access'	=> $access,
			'base_url'		=> get_stylesheet_directory_uri(),
			'wpdb'			=> $wpdb->prefix,
			'user_post_vote_count'	=> array('post_count' => count($post_count_arr), 'vote_count'	=> count($vote_count_arr) ),
			'date'			=> date("m/d/Y h:i:s A"),
			'user_ip'       => get_the_user_ip(),
			'tax'       	=> $tax,
			'term'       	=> $term,
			'_load'			=> $load,
			'term_src'		=> $term_src,
			'term_title'	=> $term_title,
			'scroll_cat'	=> scroll_load_cat_in_sidebar()
		) );

		wp_register_script('angular_data', get_stylesheet_directory_uri() . '/angular/app/data.js', array('jquery'), '1.0.0', true);
		wp_register_script('angular_directives', get_stylesheet_directory_uri() . '/angular/app/directives.js', array('jquery'), '1.0.0', true);
		wp_register_script('angular_authctrl', get_stylesheet_directory_uri() . '/angular/app/authCtrl.js?v=101', array('jquery'), '1.0.1', true);

		wp_enqueue_script('angular_min');
		wp_enqueue_script('angular_route');
		wp_enqueue_script('angular_animate');
		wp_enqueue_script('angular_toaster');
		wp_enqueue_script('angular_sanitize');
		wp_enqueue_script('angular_app');
		wp_enqueue_script('angular_data');
		wp_enqueue_script('angular_directives');
		wp_enqueue_script('angular_authctrl');
	}

	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'name' => 'Sidebar Widgets',
			'id'   => 'sidebar-widgets',
			'description'   => 'These are widgets for the sidebar.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'
		));
	}
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'name' => 'Sharing Widgets',
			'id'   => 'sharing-widgets',
			'description'   => 'These are widgets for the sharing.',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'
		));
	}
	// Function to disable plugin updates
	/*function disable_plugin_updates( $value ) {
		print_r($value);
		die;
	   unset( $value->response['frontier-post/frontier-post.php'] );
	   unset( $value->response['wp-register-profile-with-shortcode/register_afo_widget_shortcode.php'] );
	   return $value;
	}
	add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );*/

	// Function to add menu to the theme
	function wp_register_theme_menu() {
		register_nav_menu( 'primary', 'Main Navigation' );
		register_nav_menu( 'popup_category', 'Popup Category Navigation - 1' );
		register_nav_menu( 'popup_category_2', 'Popup Category Navigation - 2' );
		register_nav_menu( 'popup_category_3', 'Popup Category Navigation - 3' );
		register_nav_menu( 'profile', 'Profile Navigation' );
		register_nav_menu( 'footer', 'Footer Navigation' );
		register_nav_menu( 'registration', 'Registration Navigation' );
	}
	add_action( 'init', 'wp_register_theme_menu' );

	// Function to check page/post has featured
	function wp_theme_has_featured_posts() {
		return ! is_paged() && (bool) wp_theme_get_featured_posts();
	}

	//To Add featured image
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'medium', 210, 210, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '162_85_img', 162, 85, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '24_24_img', 24, 24, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '170_115_img', 170, 115, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '136_93_img', 136, 93, false );
	add_image_size( '150_150_img', 150, 150, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '180_180_img', 180, 180, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '200_200_img', 200, 200, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '230_155_img', 230, '', false );
	add_image_size( '220_220_img', 220, 220, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '250_250_img', 250, 250, array( 'left', 'top' ) ); // Hard crop left top
	add_image_size( '560_315_img', 560, 315, false ); // Hard crop left top
	add_image_size( '720_540_img', 720, 540, false ); // Hard crop left top
	add_image_size( '129_162_img', 129, 162, false ); // Hard crop left top
	add_image_size( '280_180_img', 280, 180, false ); // Hard crop left top
	add_image_size( '326_240_img', 556, '', false ); // Hard crop left top

	//add_action( 'init', 'update_utc_glf_update' );
	function update_utc_glf_update(){

		global $wpdb;
		//$query = "SELECT * FROM `wp_postmeta` WHERE `meta_key` LIKE '%glf_update%'";
		$query = "SELECT * FROM `wp_hyroglf_users_voting_for_articles`";
		$datas = $wpdb->get_results($query, OBJECT);

		foreach($datas as $data){
			$glf_date = $data->vote_date;
			$glf_date = date( 'Y-m-d H:i:s' ,strtotime($data->vote_date));
			date_default_timezone_set("UTC");
			$utc_date = date("Y-m-d H:i:s", strtotime($glf_date));
			//echo "UPDATE wp_hyroglf_users_voting_for_articles SET vote_utc_date = '".$utc_date."' WHERE voting_id = ".$data->voting_id."<br/>";
			$wpdb->query("UPDATE wp_hyroglf_users_voting_for_articles SET vote_utc_date = '".$utc_date."' WHERE voting_id = ".$data->voting_id);
		}
		//$query3 = "SELECT * FROM `wp_postmeta` WHERE `meta_key` LIKE '%last_edited_user%'";

		/*$datas = $wpdb->get_results($query, OBJECT);
		foreach($datas as $data){
			$mod_date = $data->meta_value;

			$explode_am = explode("AM",$data->meta_value);

			if(isset($explode_am[1]) && is_array($explode_am)) {
				$mod_date = str_replace(" AM",":00",$mod_date);
			} else {
				$mod_date = str_replace(" PM",":00",$mod_date);
			}
			$mod_date = str_replace(" : ",":",$mod_date);
			$glf_date = date( 'Y-m-d H:i:s' ,strtotime($mod_date));
			$time = strtotime($glf_date);
			date_default_timezone_set("UTC");
			$utc_date = date("Y-m-d H:i:s", strtotime($glf_date));
			update_post_meta( $data->post_id, 'glf_date_update_utc', $utc_date );
			//echo(date("Y-d-m G:i:sz", strtotime($glf_date)) . "<br />");

		}*/


		/*$datas3 = $wpdb->get_results($query3, OBJECT);
		foreach($datas3 as $data){
			$mod_date = $data->meta_value;

			$explode_am = explode("AM",$data->meta_value);

			if(isset($explode_am[1]) && is_array($explode_am)) {
				$mod_date = str_replace(" AM",":00",$mod_date);
			} else {
				$mod_date = str_replace(" PM",":00",$mod_date);
			}
			$mod_date = str_replace(" : ",":",$mod_date);
			$glf_date = date( 'Y-m-d H:i:s' ,strtotime($mod_date));
			$time = strtotime($glf_date);
			date_default_timezone_set("UTC");
			$utc_date = date("Y-m-d H:i:s", strtotime($glf_date));
			update_post_meta( $data->post_id, 'last_utc_edited_user', $utc_date );
		}*/

	}



	/*Permalink by slug*/
	function get_permalink_by_slug( $slug ) {
		$obj = get_page_by_path( $slug );
		return get_permalink( $obj->ID );
	}

	/*Get page id by Slugs*/
	function get_ids_by_slugs($slugs) {
		 $slugs = preg_split("/,s?/", $slugs);
		 $ids = array();
		 foreach($slugs as $page_slug) {
			  $page = get_page_by_path($page_slug);
			  array_push($ids, $page->ID);
		 }
		 return implode(",", $ids);
	}
	function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
		$timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code)
										: DateTimeZone::listIdentifiers();

		if($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {

			$time_zone = '';
			$tz_distance = 0;

			//only one identifier?
			if (count($timezone_ids) == 1) {
				$time_zone = $timezone_ids[0];
			} else {

				foreach($timezone_ids as $timezone_id) {
					$timezone = new DateTimeZone($timezone_id);
					$location = $timezone->getLocation();
					$tz_lat   = $location['latitude'];
					$tz_long  = $location['longitude'];
					$theta    = $cur_long - $tz_long;
					$distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat)))
					+ (cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
					$distance = acos($distance);
					$distance = abs(rad2deg($distance));
					// echo '<br />'.$timezone_id.' '.$distance;

					if (!$time_zone || $tz_distance > $distance) {
						$time_zone   = $timezone_id;
						$tz_distance = $distance;
					}

				}
			}
			return  $time_zone;
		}
		return 'unknown';
	}



	/*function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	$ip = get_client_ip();  //$_SERVER['REMOTE_ADDR']
	$timezone = '';
	$country_code = '';
	$cur_lat = '';
	$cur_long = '';
	if(isset($ip)){
		$ipInfo = file_get_contents("http://freegeoip.net/json/$ip");
		$ipInfo = json_decode($ipInfo);
		$country_code = $ipInfo->country_code;
		$cur_lat = $ipInfo->latitude;
		$cur_long = $ipInfo->longitude;
		$timezone = $ipInfo->time_zone;
		if($timezone == ''){
			$timezone = get_nearest_timezone($cur_lat, $cur_long, $country_code);
		}

		//$timezone = $ipInfo->time_zone;
		date_default_timezone_set($timezone);
		date_default_timezone_get();
	}	*/

	function getCurrentTimeZone(){
		$timezone = '';
		$ipaddress = '';
		$country_code = '';
		$cur_lat = '';
		$cur_long = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
			$ip = $ipaddress;  //$_SERVER['REMOTE_ADDR']
		if(isset($ip)){
//			$ipInfo = file_get_contents("http://freegeoip.net/json/$ip");
			$ipInfo = json_decode($ipInfo);
			$country_code = $ipInfo->country_code;
			$cur_lat = $ipInfo->latitude;
			$cur_long = $ipInfo->longitude;
			$timezone = $ipInfo->time_zone;
			if($timezone == ''){
				$timezone = get_nearest_timezone($cur_lat, $cur_long, $country_code);
			}

		}
		return $timezone;
	}


	function humanTiming($date)
		{
			$timezone = getCurrentTimeZone();
			date_default_timezone_set($timezone);
			if(empty($date)) {
				return "No date provided";
			}

			$periods         = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
			$lengths         = array("60","60","24","7","4.35","12","10");

			$now             = time();
			$unix_date       = strtotime($date);

			   // check validity of date
			if(empty($unix_date)) {
				return "Bad date";
			}

			// is it future date or past date
			if($now > $unix_date) {
				$difference     = $now - $unix_date;
				$tense         = "ago";

			} else {
				$difference     = $unix_date - $now;
				$tense         = "ago";
			}

			for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
				$difference /= $lengths[$j];
			}

			$difference = round($difference);

			if($difference != 1 && $j != 0) {
				$periods[$j].= "s";
			}

			return "$difference $periods[$j] {$tense}";
		}
	/*function humanTiming($time){
		 global $wpdb;
		//$get_time = $wpdb->get_results("SELECT now() as timer");
		$time = strtotime(get_option( 'get_current_date_time' )) - $time;
		//$time = strtotime(get_option( 'get_current_date_time' )) - $time; // to get the time since that moment
		$time = ($time<1)? 1 : $time;
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);
		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}
	}*/

	// custom excerpt ellipses
	function new_excerpt_more( $more ) {
		return '<a href="'.get_permalink($post->ID).'" class="">'.'...'.'</a>';
	}
	add_filter('excerpt_more', 'new_excerpt_more');

	// custom excerpt length
	function custom_excerpt_length( $length ) {
		return 55;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

	// Numbered Pagination
	if ( !function_exists( 'wpex_pagination' ) ) {
		function wpex_pagination() {
			$prev_arrow = '&lt;';
			$next_arrow = '&gt;';
			global $wp_query;
			$total = $wp_query->max_num_pages;
			$big = 999999999; // need an unlikely integer
			if( $total > 1 ) {
				if( !$current_page = get_query_var('paged') )
					$current_page = 1;
				if( get_option('permalink_structure') ) {
					$format = 'page/%#%/';
				} else {
					$format = '&paged=%#%';
				}
				echo paginate_links(array(
					'base'			=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'		=> $format,
					'current'		=> max( 1, get_query_var('paged') ),
					'total' 		=> $total,
					'mid_size'		=> 3,
					'type' 			=> 'list',
					'prev_text'		=> $prev_arrow,
					'next_text'		=> $next_arrow,
				 ) );
			}
		}
	}

	if ( !function_exists( 'of_get_option' ) ) {
		function of_get_option($name, $default = false) {
			$optionsframework_settings = get_option('optionsframework');
			// Gets the unique option id
			$option_name = $optionsframework_settings['id'];

			if ( get_option($option_name) ) {
				$options = get_option($option_name);
			}

			if ( isset($options[$name]) ) {
				return $options[$name];
			} else {
				return $default;
			}
		}
	}

	// Custom functions start here
	// Deffine post per page function
	function post_per_page() {
		return 15;
	}

	function global_time_format() {
		return 'm/d/Y';
	}

	function get_the_user_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {	//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return apply_filters( 'wpb_get_ip', $ip );
	}
	add_shortcode('show_ip', 'get_the_user_ip');

	//walker Menu
	function get_the_slug( $id=null ){
		if( empty($id) ):
			global $post;
			if( empty($post) )
				return ''; // No global $post var available.
			$id = $post->ID;
		endif;

		$slug = basename( get_permalink($id) );
		return $slug;
	}
	// menu
	class popup_category_nav extends Walker_Nav_Menu {
		private $color_idx = 1;
	 // add classes to ul sub-menus

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		// depth dependent classes
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
		$display_depth = ( $depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'sub-menu',
			( $display_depth >=1 ? 'sub_menu' : '' ),
			'menu-depth-' . $display_depth
			);
		$class_names = implode( ' ', $classes );
		echo $increment = $this->color_idx;
		$output .= "\n" . $indent . '<ul class="' . $class_names . '" >' . "\n";
	}

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $id_field = $this->db_fields['id'];
        if ( is_object( $args[0] ) ) {
            $args[0]->has_children = !empty( $children_elements[$element->$id_field] );
        }
        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	// add main/sub classes to li's and links

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

		// depth dependent classes
		$depth_classes = array(
			( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
			( $depth >=2 ? 'sub-sub-menu-item' : '' ),
			( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
			'menu-item-depth-' . $depth
		);
		$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

		// passed classes
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item )				) );

	$has_children='';
	if ( $args->has_children ) {
		$has_children = 'menu-item-has-children';
	}
	// build html
	if($item->type == "custom") {
		$menu_title = $item->post_title;
		//$slug = basename( $item->url);
		//$category = get_category_by_slug($slug);
		$id = $category->term_id;
		/*$term_image_id = get_option('category_'.$id.'_post_category_image');
		$image = wp_get_attachment_image_src ( $term_image_id, '136_93_img' );*/
		$increment = $this->color_idx;
		$break_ul = ceil($increment/3);
	} else {
		$menu_title = $item->title;
		$slug = basename( $item->url);
		$category = get_category_by_slug($slug);
		$id = $category->term_id;
		$term_image_id = get_option('category_'.$id.'_post_category_image');
		//$image = wp_get_attachment_image_src ( $term_image_id, '129_162_img' );
		$image = wp_get_attachment_image_src ( $term_image_id, '' );
		$show_image = wp_get_attachment_image_src ( $term_image_id, '' );
	}

	if( $menu_title == 'Recently Added' || $menu_title == 'Recently Added/Edited' ) {
		$slug = 'recent_post';
	} else if( $menu_title == 'Most Viewed' ) {
		$slug = 'most_viewed';
	} else if( $menu_title == 'My Favorites' ) {
		$slug = 'favorite_posts';
	} else if( $menu_title == 'My Posts + Votes' ) {
		$slug = 'my_posts_and_votes';
	}  else if( $menu_title == 'My Profile' ) {
		$slug = 'my_posts_and_votes';
	}

	//echo $menu_title;
	//$output .= $indent . '<li class="popup_list_cat_image"  data-value="'.$slug.'">';
	$output .= $indent . '<li class="popup_list_cat_image">';
		if(in_array('current-menu-item', $item->classes)) {
        	$active = 'active';
		}
		if(in_array('current_page_item', $item->classes)) {
			$active = 'active';
		}
		if(in_array('current-menu-ancestor', $item->classes)) {
			$active = 'active';
		}

		$cat_slug = "'".$slug."'";
		$is_page = "";
		if ( is_front_page() && is_home() ) {
			$is_page = "'index'";
		} else {
			$is_page = "'inner_page'";
		}

		$name = "'".$menu_title."'";
		$img_url = "'".$show_image[0]."'";

		$tax = '';
		if( $slug == 'recent_post' ) {
			$tax = "'recent'";
		} else if( $slug == 'most_viewed' ) {
			$tax = "'most_viewed'";
		} else if( $slug == 'my_posts_and_votes' ) {
			$tax = "'cat_my_posts_and_votes'";
		} else if( $slug == 'favorite_posts' ) {
			$tax = "'cat_favorite_posts'";
		} else {
			$tax = "'category'";
		}

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		if( $slug != 'my_posts_and_votes' ) {
			$attributes .= ' id="cat_'.$slug.'" class="menu-link ' . $active . '" ng-click="$event.preventDefault(); cat_post_filter_click($event, '.$tax.','.$cat_slug.','.$name.','.$img_url.','.$is_page.')"';
		}

		if($image[0]){
			if( $slug == 'most_viewed' || $slug == 'sports' || $slug == 'biography' ) {
				$img .="<img src='".$image[0]."' width='' height=''/>";
			} else {
				$img .="<img src='".$image[0]."' width='' height=''/>";
			}
		} else {
			$img ='';
		}
		if( $menu_title == 'My Favorites' && is_user_logged_in() || $menu_title == 'My Posts + Votes' && is_user_logged_in() || $menu_title == 'My Profile' && is_user_logged_in()) {
			$item_output = sprintf( '<div class="filter_post_img">%1$s<a%2$s>'.$img.'%3$s%4$s<label>'.$menu_title.'</label>%5$s</a>%6$s</div>',
				$args->before,
				$attributes,
				$args->link_before ,
				apply_filters( 'the_title', '', $item->ID ),
				$args->link_after,
				$args->after
			);
		} else if($menu_title != 'My Favorites' && $menu_title != 'My Profile' ) {
			$item_output = sprintf( '<div class="filter_post_img">%1$s<a%2$s>'.$img.'%3$s%4$s<label>'.$menu_title.'</label>%5$s</a>%6$s</div>',
				$args->before,
				$attributes,
				$args->link_before ,
				apply_filters( 'the_title', '', $item->ID ),
				$args->link_after,
				$args->after
			);
		}
			$output .= ''.apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	add_action( 'init', 'create_post_type' );
	function create_post_type() {
		register_post_type( 'advertisement_logo',
				array(
					'labels' => array(
					'name' => __( 'Advertisement Logo' ),
					'singular_name' => __( 'Advertisemnet Logo' ),
					'add_new'		=> 'Add New',
					'add_new_item'	=> 'Add New Logo',
					'edit_item'		=> 'Edit Logo',
				),
				'public' => true,
				'hierarchical'  => true,
				'show_in_menu' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'advertisement_logo'),
				'supports'      =>  array( 'title',  'order', 'thumbnail','page-attributes')
				)
			);
			flush_rewrite_rules();
	}

	function littlecontent($limit) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		 } else {
		$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
		return $excerpt;
	}

	//Page Slug Body Class
	function add_slug_body_class( $classes ) {
		global $post;
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}
		return $classes;
	}
	add_filter( 'body_class', 'add_slug_body_class' );

	function wiki_login_url($redirect = '') {
	$login_url = home_url('/login');

	if ( !empty($redirect) )
		$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
		return $login_url;
	}

	function RemoveAddMediaButtonsForNonAdmins(){
		remove_action( 'media_buttons', 'media_buttons' );
	}
	add_action('wp_head', 'RemoveAddMediaButtonsForNonAdmins');

	function rudr_filter_by_the_author() {
		$params = array(
			'name' => 'author', // this is the "name" attribute for filter
			'show_option_all' => 'All authors' // label for all authors (display posts without filter)
		);

		if ( isset($_GET['user']) )
			$params['selected'] = $_GET['user']; // choose selected user by $_GET variable

		wp_dropdown_users( $params ); // print the ready author list
	}
	add_action('restrict_manage_posts', 'rudr_filter_by_the_author');

	function remove_update_notifications( $value ) {
		if ( isset( $value ) && is_object( $value ) ) {
			unset( $value->response['wp-favorite-posts/wp-favorite-posts.php'] );
			unset( $value->response['frontier-post/frontier-post.php'] );
			unset( $value->response['wp-register-profile-with-shortcode/register.php'] );
		}
		return $value;
	}
	add_filter( 'site_transient_update_plugins', 'remove_update_notifications' );
	add_filter( 'auto_update_plugin', '__return_false' );

	function voting_count_ratio( $question_id, $user_article_id ){
		global $wpdb;
		$sum = 0;
		$ratio_arr = array();

		$voting_ratio = $wpdb->get_var("SELECT count(ques_option_id) FROM ".$wpdb->prefix."hyroglf_users_voting WHERE question_id = $question_id AND user_article_id = $user_article_id");

		$voting_ratio_score = $wpdb->get_results("SELECT option_score FROM ".$wpdb->prefix."hyroglf_question_option as ques_opt JOIN ".$wpdb->prefix."hyroglf_users_voting as usr_vot ON ques_opt.option_id=usr_vot.ques_option_id WHERE question_id = $question_id AND user_article_id = $user_article_id");
		if( $voting_ratio_score ) {
			foreach( $voting_ratio_score as $ratio ) {
				$ratio_arr[] = $ratio->option_score;
			}
			$sum = array_sum( $ratio_arr ) / $voting_ratio;
		}
		return $sum;
	}

	add_action('admin_footer', 'my_admin_footer_function');
	function my_admin_footer_function() { ?>
		<script>
			jQuery("#hyroglf_vote_by_category").insertAfter("#dashboard-widgets-wrap")
			var text=jQuery("li#menu-posts a.wp-has-submenu").attr('href','edit.php');
		</script><?php
	}

	add_filter( 'wp_mail_from', 'new_mail_from' );
	add_filter( 'wp_mail_from_name', 'new_mail_from_name' );
	function new_mail_from( $old ) {
		return get_option( 'admin_email' );
	}
	function new_mail_from_name( $old ) {
		return get_option( 'blogname' );
	}


	####################### // Single page functions // #######################

	// page left and right sidebars
	function category_left_sidebar_for_page( $page_type = '' ) {

		$class = '';
		if( $page_type == 'front_page' ) {
			$class = 'wiki_category';
		} else {
			$class = 'wiki_categories';
		}

		$terms = get_terms('category');
		$cat_left_arr = array();
		if( $terms ) {
			foreach( $terms as $term ) {
				if( $term->slug == 'economy' || $term->slug == 'politics' || $term->slug == 'technology' || $term->slug == 'sports' ) {
					$cat_left_arr[] = $term;
				}
			}
		} ?>
		<div class="wiki_left_section equal_height">
			<div class="<?php echo $class; ?>">
				<ul><?php
					if( $cat_left_arr ) {
						foreach( $cat_left_arr as $term) {
							$term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
							$image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' ); ?>
							<li class="pro_list_image <?php echo $term->slug; ?>">
								<div class="post_cat_img">
									<a href="javascript:void(0);<?php //echo home_url('?term='.$term->slug); ?>" onclick="inner_page_cat_filter('<?php echo $term->slug; ?>', 'category', 'cat_post', '<?php echo $image[0]; ?>','<?php echo $term->name; ?>');">
										<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php //echo $term->name; ?>" />
									</a>
								</div>
							<span class="list_cat_name"><?php echo $term->name; ?></span>
							</li><?php
						}
					} ?>
				</ul>
			</div>
		</div><?php
	}

	function category_right_sidebar_for_page( $page_type = '' ) {

		$class = '';
		if( $page_type == 'front_page' ) {
			$class = 'wiki_category';
		} else {
			$class = 'wiki_categories';
		}

		$terms = get_terms('category');
		$cat_right_arr = array();
		if( $terms ) {
			foreach( $terms as $term ) {
				if( $term->slug == 'news' || $term->slug == 'pop-culture'|| $term->slug == 'biography' ) {
					$cat_right_arr[] = $term;
				}
			}
		} ?>
		<div class="wiki_right_section equal_height">
			<div class="<?php echo $class; ?>">
				<ul>
					<li class="pro_list_image" data-value="recent_post">
						<div class="post_cat_img">
							<a href="<?php echo home_url(''); ?>"><i class="fas fa-clock fa-3x"></i></a>
						</div>
						<span>Recently Added/Edited</span>
					</li><?php
					if( $cat_right_arr ) {
						foreach( $cat_right_arr as $term) {
							$term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
							$image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' ); ?>
							<li class="pro_list_image <?php echo $term->slug; ?>">
								<div class="post_cat_img">
									<a href="javascript:void(0);<?php //echo home_url('?term='.$term->slug); ?>" onclick="inner_page_cat_filter('<?php echo $term->slug; ?>', 'category', 'cat_post', '<?php echo $image[0]; ?>','<?php echo $term->name; ?>');">
										<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php //echo $term->name; ?>" />
									</a>
								</div>
								<span class="list_cat_name"><?php echo $term->name; ?></span>
							</li><?php
						}
					} ?>
				</ul>
			</div>
		</div>
	<?php
	}

	function load_post_title_with_rating( $post = '', $key = '', $page = '' ) {
		global $wpdb;
		$style = '';
		if( $page == 'tag' ) {
			$style = 'style="display:none;"';
		}

		$current_user = wp_get_current_user(); // current user
		$user_id = $current_user->ID;
		$post_id = $post->ID;
		$content_data = get_post_meta($post->ID); // post meta id

		//get option count for voting
		 $option_id_count= $wpdb->get_results("SELECT option_id FROM ".$wpdb->prefix."hyroglf_question_option");
		 $option_voting_post = array();
		 $options_arr = array();
		 if($option_id_count){
			 foreach($option_id_count as $option) {
				$option_id_array = $option->option_id;
				$option_voting_post[] = $wpdb->get_results("SELECT count(ques_option_id) as count_id FROM ".$wpdb->prefix."hyroglf_users_voting WHERE ques_option_id=".$option->option_id." AND user_article_id=".$post_id);
				$options_arr[] = $wpdb->get_results("SELECT
										count(HUV.ques_option_id) as count_id,
										HUV.question_id,
										HQ.questions,
										HUV.ques_option_id,
										HQO.ques_option
										FROM ".$wpdb->prefix."hyroglf_users_voting AS HUV
										INNER join ".$wpdb->prefix."hyroglf_questions AS HQ ON HUV.question_id = HQ.question_id
										INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
										WHERE HUV.ques_option_id='$option_id_array' AND HUV.user_article_id='$post_id'");
			 }

		 }

		$infermative_arr = array();
		$bias_arr = array();
		foreach( $options_arr as $options ) {
			if( !empty( $options ) ) {
				foreach( $options as $option ) {
					if( $option->questions == 'Informative/Understandable?' ) {
						$infermative_arr[] = array(
												"count" 			=> $option->count_id,
												"question_id"		=> $option->question_id,
												"questions" 		=> $option->questions,
												"ques_option_id" 	=> $option->ques_option_id,
												"ques_option" 		=> $option->ques_option,
												);

					} elseif( $option->questions == 'Bias?' ) {
						$bias_arr[] = array(
												"count" 			=> $option->count_id,
												"question_id"		=> $option->question_id,
												"questions" 		=> $option->questions,
												"ques_option_id" 	=> $option->ques_option_id,
												"ques_option" 		=> $option->ques_option,
												);
												//print_r($bias_arr);
					}
				}
			}
		}

		if( is_array( $infermative_arr ) ) {
			$sort_infor = array();
			foreach($infermative_arr as $c=>$key) {
				$sort_infor[] = $key['count'];
			}
			if( is_array( $sort_infor ) ) {
				array_multisort($sort_infor, SORT_DESC, SORT_STRING,$infermative_arr);
			}
		}
		if( is_array( $bias_arr ) ) {
			$sort_bias = array();
			foreach($bias_arr as $key=>$value) {
				$sort_bias[] = $value['count'];
			}
			if( is_array( $sort_bias ) ) {
				array_multisort($sort_bias, SORT_DESC, SORT_STRING, $bias_arr);
			}
		}
		//rsort($infermative_arr);
		//rsort($bias_arr);
		//echo "<pre>";
		//print_r($infermative_arr);
		$user_voting_hide_infor = '';
		$user_voting_hide_bias = '';
		if($post->ID && $current_user->ID){
			 $user_id = $current_user->ID;
			 $count_user = $wpdb->get_var("
									SELECT count(user_id) AS user_count
									FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles
									WHERE article_id = ".$post_id." AND user_id = ".$user_id."
									");
			$user_option_infermative = $wpdb->get_var("
									SELECT *
									FROM ".$wpdb->prefix."hyroglf_questions AS HQ
									INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HQ.question_id = HQO.question_id_fk
									INNER JOIN ".$wpdb->prefix."hyroglf_users_voting AS HUV ON HQO.option_id = HUV.ques_option_id
									WHERE HQ.questions = 'Informative/Understandable?'
									AND HUV.user_id = ".$user_id."
									AND HUV.user_article_id = ".$post_id
									);
			$infermative_count = '';
			if( !empty( $user_option_infermative ) ) {
				$infermative_count = $user_option_infermative;
			}

			$user_option_bias = $wpdb->get_var("
									SELECT *
									FROM ".$wpdb->prefix."hyroglf_questions AS HQ
									INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HQ.question_id = HQO.question_id_fk
									INNER JOIN ".$wpdb->prefix."hyroglf_users_voting AS HUV ON HQO.option_id = HUV.ques_option_id
									WHERE HQ.questions = 'Bias?'
									AND HUV.user_id = ".$user_id."
									AND HUV.user_article_id = ".$post_id
									);

			$bias_count = '';
			if( !empty( $user_option_bias ) ) {
				$bias_count = $user_option_bias;
			}

			$user_voting_hide_infor = 0;
			$count_user_infor = $wpdb->get_var("SELECT count(user_id) AS user_count FROM ".$wpdb->prefix."hyroglf_users_voting WHERE user_article_id = ".$post_id." AND user_id = ".$user_id." AND question_id = 1");
			if($count_user_infor >= 1){
				$user_voting_hide_infor=1;
			} else {
				$user_voting_hide_infor=0;
			}

			$user_voting_hide_bias = 0;
			$count_user_bias = $wpdb->get_var("SELECT count(user_id) AS user_count FROM ".$wpdb->prefix."hyroglf_users_voting WHERE user_article_id = ".$post_id." AND user_id = ".$user_id." AND question_id = 2");
			if($count_user_bias >= 1){
				$user_voting_hide_bias=1;
			} else {
				$user_voting_hide_bias=0;
			}

		 }
		$terms_obj = get_the_terms( $post_id, 'category' );
		$cat_arr = array();
		foreach( $terms_obj as $term) {
			$cat_arr[] = $term->slug;
		} ?>
		<div class="post_title"><?php
			$fav_icon_link = ( isset( $content_data['reference_link'][0] ) ) ? $content_data['reference_link'][0] : '#';
			if( is_array( $cat_arr ) && in_array('other-info', $cat_arr) ) { ?>
				<span class="post_fav_icons"><a href="<?php echo $fav_icon_link; ?>"><img src="<?php echo get_stylesheet_directory_uri().'/assets/images/GLF-Favicon.png';//of_get_option('favicon'); ?>" alt="favicon" width="50" height="50" /></a></span><?php
			} else if( isset( $content_data['post_ref_link_favicon'][0] ) && file_exists( $content_data['post_ref_link_favicon'][0] ) ) { ?>
				<span class="post_fav_icons"><a <?php echo ($content_data['reference_link'][0]) ? 'href="'.$content_data['reference_link'][0].'"' : '';?> target="_blank" class="post_fav_icons">
					<img src="<?php echo $content_data['post_ref_link_favicon'][0]; ?>">
				</a></span><?php // favicon image from referencce link
			} else {
				if( $terms_obj ) {
					$i = 0;
					foreach( $terms_obj as $term) {
						if( $i == 0 ) {
							/*$term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
							$image = wp_get_attachment_image_src ( $term_image_id, '24_24_img' ); ?>
							<a href="<?php echo home_url('/?term='.$term->slug); ?>">
								<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php echo $term->name; ?>" />
						   </a><?php*/
							$term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
							$image = wp_get_attachment_image_src ( $term_image_id, '' ); ?>
							<span class="post_fav_icons"><a href="<?php echo home_url('/?term='.$term->slug); ?>">
								<img src="<?php echo $image[0]; ?>" width="50" height="50" alt="<?php echo $term->name; ?>" />
						   </a></span><?php
						}
					$i++;
					}
				}
				//get_image('favicon_1.png', '24', '24', 'Fav icon');
			} ?>
		<div class="feed_post_refer_link"><?php
				/*$timezone = '';
				$country_code = '';
				$cur_lat = '';
				$cur_long = '';
				$ip = get_client_ip();  //$_SERVER['REMOTE_ADDR']
				$ipInfo = file_get_contents("http://freegeoip.net/json/$ip");
				$ipInfo = json_decode($ipInfo);
				$country_code = $ipInfo->country_code;
				$cur_lat = $ipInfo->latitude;
				$cur_long = $ipInfo->longitude;
				$timezone = get_nearest_timezone($cur_lat, $cur_long, $country_code);
				if($timezone == ''){
					$timezone = $ipInfo->time_zone;
				}*/
				//$timezone = getCurrentTimeZone();
				//date_default_timezone_set($timezone);
				$last_edited_user = $content_data['last_edited_user'][0];
				if($last_edited_user){
					$user_data = get_user_by( 'login', $last_edited_user );
					$user_login = $user_data->data->user_login;
				} else {
					$user_list_data = get_author_name( $post->post_author );
					$user_data = get_user_by( 'login', $user_list_data );
				}
				$mod_date = get_post_meta( $post->ID, 'glf_date_update_utc', true );
				//echo str_replace(" AM",":00",$mod_date);
				$explode_am = explode("AM",$mod_date.'aa');
				if(isset($explode_am[1]) && is_array($explode_am)) {
					$mod_date = str_replace(" AM",":00",$mod_date);
				} else {
					$mod_date = str_replace(" PM",":00",$mod_date);
				}
				$mod_date = str_replace(" : ",":",$mod_date);
				$glf_date = date( 'Y-m-d H:i:s' ,strtotime($mod_date));

				$date = new DateTime($glf_date, new DateTimeZone('UTC'));
				$timeZone = getCurrentTimeZone();
				$date->setTimezone(new DateTimeZone($timeZone ));
				$glf_date = $date->format('Y-m-d H:i:s');

				$time = strtotime($glf_date);
				/*$date = new DateTime($meta_arr['meta_value'], new DateTimeZone('UTC'));
				$timeZone = $this->getTimeZone();
				$date->setTimezone(new DateTimeZone($timeZone ));
				$meta_arr['meta_value'] = $date->format('Y-m-d H:i:s');*/

				 if($time > strtotime("-1 day")  ) {?>
					<p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago"><?php echo humanTiming($glf_date);?> at <?php echo date('g:i A',strtotime( $glf_date ));?> </p><?php
				} else if($time < strtotime("-1 day")  && $time > strtotime("-7 day")) {?>
                    <p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago"><?php echo date( 'D' ,strtotime($glf_date));?> at <?php echo date('g:i A',strtotime( $glf_date ));?> </p><?php
                } else {?>
                    <p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago"><?php echo date( 'M d Y' ,strtotime($glf_date));?> at <?php echo date('g:i A',strtotime( $glf_date ));?> </p><?php
                }

					/*$last_edited_user = $content_data['last_edited_user'][0];
					if( $last_edited_user ) {
						$user_data = get_user_by( 'login', $last_edited_user );
						$user_login = $user_data->data->user_login;
						$mod_date = get_post_meta( $post->ID, 'glf_update', true );
						$glf_update_system = get_post_meta( $post->ID, 'glf_update_system', true );
						//echo str_replace(" AM",":00",$mod_date);
						$explode_am = explode("AM",$mod_date.'aa');
						if(isset($explode_am[1]) && is_array($explode_am)) {
							$mod_date = str_replace(" AM",":00",$mod_date);
						} else {
							$mod_date = str_replace(" PM",":00",$mod_date);
						}
						$mod_date = str_replace(" : ",":",$mod_date);
						$glf_date = date( 'Y-m-d H:i:s' ,strtotime($mod_date));
						$time = strtotime($glf_date);

						if($time > strtotime("-7 day")  ) {?>
							<p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago"><?php echo humanTiming($glf_date);?> at <?php echo date('g:i A',strtotime( $glf_date ));?> </p><?php
						} else {?>
	                        <p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago"><?php echo date( 'M d Y' ,strtotime($glf_date));?> at <?php echo date('g:i A',strtotime( $glf_date ));?> </p><?php
                        }
					} else {
						$user_list_data = get_author_name( $post->post_author );
						$user_data = get_user_by( 'login', $user_list_data );
						$post_glf_update = get_post_meta( $post->ID, 'glf_update', true );
						$glf_update_system = get_post_meta( $post->ID, 'glf_update_system', true );

						$explode_am = explode("AM",$post_glf_update.'aa');
						if(isset($explode_am[1]) && is_array($explode_am)){
							$glf_update = str_replace(" AM",":00",$post_glf_update);
						} else {
							$glf_update = str_replace(" PM",":00",$post_glf_update);
						}
						$glf_update = str_replace(" : ",":",$glf_update);
						$post_glf_update = date( 'Y-m-d H:i:s' ,strtotime($glf_update));
						$time = strtotime($post_glf_update);
						if($time > strtotime("-7 day")) {?>
							<p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago" <?php echo $style; ?>><?php echo humanTiming($post_glf_update);?> at <?php echo date('g:i A',strtotime(str_replace('00', '12', $glf_date)));?></p><?php
						} else {?>
	                        <p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single dates_ago"><?php echo date( 'M d Y' ,strtotime($post_glf_update));?> at <?php echo date('g:i A',strtotime( $glf_date ));?> </p><?php
						}
					}*/


					?>
			<h4><a href="<?php echo get_the_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h4><?php
			if (is_user_logged_in() && function_exists('wpfp_link')) {
				echo wpfp_link( true, $action = "", $show_span = 1, $args = array(), $post->ID );
			} ?>
			<div class="post_refer_link"><?php
			$post_source_name = get_post_meta( $post_id, 'source_name', true );
			if( isset( $content_data['reference_link'][0] ) ) { ?>
				<a href="<?php echo ( isset( $content_data['reference_link'][0] ) ) ? $content_data['reference_link'][0] : ''; ?>" target="_blank"><?php echo ( isset( $content_data['source_name'][0] ) ) ? htmlspecialchars_decode($content_data['source_name'][0]) : 'Source'; ?></a>  <?php
			} else { ?>
				<a href="#">Source</a>  <?php
			} ?>
			<div class="post_read_time_section" id="post_read_time_section_<?php echo $post->ID; ?>"><?php source_read( $post->ID ); ?></div>
			<div class="post_reting_section vote_result_section"><?php
				$info_arr = array_filter( $infermative_arr );
				$b_arr =  array_filter( $bias_arr );
				if( ( is_user_logged_in() ) || ( $option_voting_post[0][0]->count_id || $option_voting_post[1][0]->count_id || $option_voting_post[2][0]->count_id || $option_voting_post[3][0]->count_id || $option_voting_post[4][0]->count_id || $option_voting_post[5][0]->count_id ) ) {
					if( !$option_voting_post[0][0]->count_id && !$option_voting_post[1][0]->count_id && !$option_voting_post[2][0]->count_id && !$option_voting_post[3][0]->count_id && !$option_voting_post[4][0]->count_id && !$option_voting_post[5][0]->count_id ) { ?>

						<a href="javascript:void(0);" class="post_rating" onmouseover="post_rating(<?php echo $post->ID; ?>);">Rate the source</a><?php
					}
				}
				// When user is logged in
				if( is_user_logged_in() ) {
					if( $infermative_count == 0 || $bias_count == 0 ) { ?>
						<div class="post_voting_section" id="post_voting_<?php echo $post->ID; ?>">
							<div class="voting_option">
								<form name="form_<?php echo $post->ID; ?>" method="post" action="" id="user_infor_bias_voting_form_<?php echo $post->ID; ?>"><?php
									if( $infermative_count == 0 ) { ?>
                                        <div class="infermative_tab">
                                            <span>Informative?</span>
                                            <a href="javascript:void(0);" class="rating_option infermative_action" onclick="rating_option(<?php echo $post->ID; ?>, 'infermative', '');"><i class="fa fa-plus fa-2x"></i></a>
                                            <a href="javascript:void(0);" class="rating_option infermative_action_close" onclick="rating_option(<?php echo $post->ID; ?>, 'infermative', '');"><i class="fa fa-close fa-3x"></i></a>
                                            <div class="infermative_option" id="infermative_option_<?php echo $post->ID; ?>" style="display:none;">
                                                <select name="informative_select" id="informative_select" class="rating_options_select cs-select cs-skin-elastic" onchange="select_vote_option(this.value, 'infor_bias_informative');">
                                                    <option value="" disabled="disabled">Select</option>
                                                    <option value="very" data-id="<?php echo $post->ID; ?>">very</option>
                                                    <option value="somewhat" data-id="<?php echo $post->ID; ?>">somewhat</option>
                                                    <option value="not_really" data-id="<?php echo $post->ID; ?>">not really</option>
                                                </select>
                                                <input type="hidden" name="informative" id="infor_bias_informative" value="" />
                                            </div>
                                        </div><?php
									}

									if( $bias_count == 0 ) { ?>
                                        <div class="bias_tab">
                                            <span>Bias?</span>
                                            <a href="javascript:void(0);" class="rating_option bias_action" onclick="rating_option(<?php echo $post->ID; ?>, 'bias', '');"><i class="fa fa-plus fa-2x"></i></a>
                                            <a href="javascript:void(0);" class="rating_option bias_action_close" onclick="rating_option(<?php echo $post->ID; ?>, 'bias', '');"><i class="fa fa-close fa-3x"></i></a>
                                            <div class="bias_option" id="bias_option_<?php echo $post->ID; ?>" style="display:none;">
                                                <select name="bias_select" id="bias_select" class="rating_options_select cs-select cs-skin-elastic" onchange="select_vote_option(this.value, 'infor_bias_bias');">
                                                    <option value="" disabled="disabled">Select</option>
                                                    <option value="liberal">liberal</option>
                                                    <option value="neutral">neutral</option>
                                                    <option value="conservative">conservative</option>
                                                </select>
                                                <input type="hidden" name="bias" id="infor_bias_bias" value="" />
                                            </div>
                                        </div><?php
									} ?>
									<div class="post_rating_action">
										<input  type="hidden" name="post_id" id="post_id" value="<?php echo $post->ID; ?>" />
                                        <input  type="hidden" name="post_client_date" id="post_client_date" value="" />
										<input  type="hidden" name="posted_date" id="posted_date" value="<?php echo date('Y-m-d'); ?> " />
										<input  type="hidden" name="user_id" id="user_id" value="<?php if($current_user){ echo $current_user->ID;}?>" />
										<a href="javascript:void(0);" id="post_submit" onclick="fnLoadPosts('infor_bias', <?php echo $post->ID; ?>);">Submit</a>
									</div>
								</form>
							</div>
						</div><?php
					}
				} ?>
			</div><?php
			if( $option_voting_post[0][0]->count_id || $option_voting_post[1][0]->count_id || $option_voting_post[2][0]->count_id || $option_voting_post[3][0]->count_id || $option_voting_post[4][0]->count_id || $option_voting_post[5][0]->count_id ) {
				// Infermative vote result
				if( !empty( $infermative_arr ) && is_array( $infermative_arr ) /*|| !empty( $bias_arr ) && is_array( $bias_arr )*/ ) {
					$in = 0;
				   // foreach( $infermative_arr as $infermative ) {
						//echo "<pre>";print_r($infermative_arr);
						if( $in == 0 ) { ?>
							<div class="infermative_vote_result_section vote_result_section">
							<a href="javascript:void(0);" onmouseover="display_voting(<?php echo $post->ID; ?>, 'infermative');" onmouseout="hide_voting(<?php echo $post->ID; ?>, 'infermative');"><?php if($infermative_arr[0]['count']) {
							if($infermative_arr[0]['ques_option']) {
								echo str_replace('_', ' ', $infermative_arr[0]['ques_option'] );
							}
						}
						if(isset($infermative_arr[1]) && $infermative_arr[1]['count'] == $infermative_arr[0]['count']) {
							if($infermative_arr[1]['ques_option']) {
								echo '/'.str_replace('_', ' ', $infermative_arr[1]['ques_option'] );
							}
						}
						if(isset( $infermative_arr[2] ) && $infermative_arr[2]['count'] == $infermative_arr[0]['count'] && $infermative_arr[2]['count'] == $infermative_arr[1]['count']){
							if($infermative_arr[2]['ques_option']){
								echo '/'.str_replace('_', ' ', $infermative_arr[2]['ques_option'] ) ;
							}
						} ?> informative</a><?php
						if( !empty( $infermative_arr ) && is_array( $infermative_arr ) && !empty( $bias_arr ) && is_array( $bias_arr ) ) { ?>
                            <span>and</span><?php
                        } ?>
                        <div class="post_voting_section" id="post_infermative_voting_<?php echo $post->ID; ?>">
                             <div class="voting_option_values">
                                <span class="informative_single_vote_post_result informative_single_vote_post_result_<?php echo $post->ID; ?>"><?php
                                    if( $option_voting_post[0][0]->count_id || $option_voting_post[1][0]->count_id || $option_voting_post[2][0]->count_id ) { ?>
                                        <span>Informative?</span><?php
                                    }
                                    $total = ( ( $option_voting_post[0][0]->count_id ) + ( $option_voting_post[1][0]->count_id ) + ( $option_voting_post[2][0]->count_id ) ); ?>
                                    <ul><?php
                                        if( $option_voting_post[0][0]->count_id || $option_voting_post[1][0]->count_id || $option_voting_post[2][0]->count_id ) { ?>
                                        <li>
                                            <label>very</label><?php
                                            if( $option_voting_post[0][0] ) {
                                                $count = $option_voting_post[0][0]->count_id/$total * 100; ?>
                                                <span> - <?php echo round( $count );?>% ( <?php echo ( $option_voting_post[0][0]->count_id > 1 ) ? $option_voting_post[0][0]->count_id.' votes' : $option_voting_post[0][0]->count_id.' vote'; ?> )</span><?php
                                            } ?>
                                        </li>
                                        <li>
                                            <label>somewhat</label><?php
                                            if( $option_voting_post[1][0] ) {
                                                $count = $option_voting_post[1][0]->count_id/$total * 100; ?>
                                                <span> - <?php echo round( $count );?>% ( <?php echo ( $option_voting_post[1][0]->count_id > 1 ) ? $option_voting_post[1][0]->count_id.' votes' : $option_voting_post[1][0]->count_id.' vote'; ?> )</span><?php
                                            } ?>
                                        </li>
                                        <li>
                                            <label>not really</label><?php
                                            if( $option_voting_post[2][0] ) {
                                                $count = $option_voting_post[2][0]->count_id/$total * 100; ?>
                                                <span> - <?php echo round( $count );?>% ( <?php echo ( $option_voting_post[2][0]->count_id > 1 ) ? $option_voting_post[2][0]->count_id.' votes' : $option_voting_post[2][0]->count_id.' vote'; ?> )</span><?php
                                            } ?>
                                        </li><?php
                                        }
                                        if( $infermative_count == 0 || $bias_count == 0 ) {
                                            if( is_user_logged_in() ) { ?>
                                                <li>
                                                <a href="javascript:void(0);" class="post_rating" onclick="post_rating(<?php echo $post->ID; ?>, 'informative', 'infor');">Rate the source</a>
                                                </li><?php
                                            } else { ?>
                                                <li>
                                                    <span class="sign_up_source"><a href="sign-up">sign up</a> or <a href="login">login</a> to rate the source </span>
                                                </li><?php
                                            }
                                        } ?>
                                     </ul>
                                 </span><?php
                                 if( is_user_logged_in() ) {
                                    //if( $infermative_count == 0 ) { ?>
                                        <span class="informative_single_vote_post_option informative_single_vote_post_option_<?php echo $post->ID; ?>" style="display:none;"><?php
                                        if( $user_voting_hide_infor == 0 || $user_voting_hide_bias == 0) { ?>
                                        <form name="form_<?php echo $post->ID; ?>" method="post" action="" id="user_infor_voting_form_<?php echo $post->ID; ?>"><?php
                                            if( $user_voting_hide_infor == 0 ) { ?>
                                            <div class="single_vote_informative_option">
                                                <span>Informative?</span>
                                                <a href="javascript:void(0);" class="rating_option infermative_action" onclick="rating_option_informative(<?php echo $post->ID; ?>, 'infermative', 'single');"><i class="fa fa-plus fa-2x"></i></a>
                                                <a href="javascript:void(0);" class="rating_option infermative_action_close" onclick="rating_option_informative(<?php echo $post->ID; ?>, 'infermative', 'single');"><i class="fa fa-close fa-3x"></i></a>
                                                <div class="infermative_option" id="infermative_option_single_<?php echo $post->ID; ?>" style="display:none;">
                                                    <div class="infermative_option_single">
                                                        <select name="informative_select" id="infor_informative_select" class="rating_options_select cs-select cs-skin-elastic" onchange="select_vote_option(this.value, 'infor_informative');">
                                                            <option value="">Select</option>
                                                            <option value="very">very</option>
                                                            <option value="somewhat">somewhat</option>
                                                            <option value="not_really">not really</option>
                                                        </select>
                                                        <input type="hidden" name="informative" id="infor_informative" value="" />
                                                    </div>
                                                </div>
                                            </div><?php
                                            }
                                            if( $user_voting_hide_bias == 0 ) { ?>
                                                <div class="single_vote_bias_option">
                                                    <span>Bias?</span>
                                                    <a href="javascript:void(0);" class="rating_option bias_action" onclick="rating_option_informative(<?php echo $post->ID; ?>, 'bias', 'single');"><i class="fa fa-plus fa-2x"></i></a>
                                                    <a href="javascript:void(0);" class="rating_option bias_action_close" onclick="rating_option_informative(<?php echo $post->ID; ?>, 'bias', 'single');"><i class="fa fa-close fa-3x"></i></a>
                                                    <div class="bias_option" id="bias_option_single_<?php echo $post->ID; ?>" style="display:none;">
                                                        <div class="bias_option_single">
                                                            <select name="bias_select" id="infor_bias_select" class="rating_options_select cs-select cs-skin-elastic" onchange="select_vote_option(this.value, 'infor_bias');">
                                                                <option value="">Select</option>
                                                                <option value="liberal">liberal</option>
                                                                <option value="neutral">neutral</option>
                                                                <option value="conservative">conservative</option>
                                                            </select>
                                                            <input type="hidden" name="bias" id="infor_bias" value="" />
                                                        </div>
                                                    </div>
                                                </div><?php
                                            } ?>
                                            <a href="javascript:void(0);" id="post_submit" onclick="fnLoadPosts('infor', <?php echo $post->ID; ?>);">Submit</a>
                                            <input  type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
                                            <input  type="hidden" name="posted_date" value="<?php echo date('Y-m-d'); ?> " />
                                            <input  type="hidden" name="user_id" value="<?php if($current_user){ echo $current_user->ID;}?>" />
                                        </form><?php
                                        } ?>
                                        </span><?php
                                    //}
                                }?>
                            </div>
						</div>
							</div><?php
						}
					$in++;
				   // }
				}
				// Bias vote result
				if( /*!empty( $infermative_arr ) && is_array( $infermative_arr ) ||*/ !empty( $bias_arr ) && is_array( $bias_arr ) ) {
					$bi = 0;
				   // foreach( $bias_arr as $bias ) {
						if( $bi == 0 ) { ?>
							<div class="bias_vote_result_section vote_result_section">
								<a href="javascript:void(0);" onmouseover="display_voting(<?php echo $post->ID; ?>, 'bias');" onmouseout="hide_voting(<?php echo $post->ID; ?>, 'bias');"><?php
							if($bias_arr[0]['count']){
								if($bias_arr[0]['ques_option']){
									echo str_replace('_', ' ', $bias_arr[0]['ques_option'] );
								}
							}
							if(isset( $bias_arr[1] ) && $bias_arr[1]['count'] == $bias_arr[0]['count']){
								if($bias_arr[1]['ques_option']){
									echo '/'.str_replace('_', ' ', $bias_arr[1]['ques_option'] );
								}
							}
							if( isset( $bias_arr[2] ) && $bias_arr[2]['count'] == $bias_arr[0]['count'] && $bias_arr[2]['count'] == $bias_arr[1]['count']) {
								if($bias_arr[2]['ques_option']) {
									echo '/'.str_replace('_', ' ', $bias_arr[2]['ques_option'] );
								}
							}?> bias</a>
								<div class="post_voting_section" id="post_bias_voting_<?php echo $post->ID; ?>">
									 <div class="voting_option_values">
										<span class="bias_single_vote_post_result bias_single_vote_post_result_<?php echo $post->ID; ?>"><?php
										if( $option_voting_post[3][0]->count_id || $count = $option_voting_post[4][0]->count_id || $option_voting_post[5][0]->count_id ) { ?>
											<span>Bias?</span><?php
										}
										$total = ( ( $option_voting_post[3][0]->count_id ) + ( $option_voting_post[4][0]->count_id ) + ( $option_voting_post[5][0]->count_id ) ); ?>
										<ul><?php
										if( $option_voting_post[3][0]->count_id || $count = $option_voting_post[4][0]->count_id || $option_voting_post[5][0]->count_id ) { ?>
											<li>
												<label>liberal</label><?php
												if( $option_voting_post[3][0] ) {
													$count = $option_voting_post[3][0]->count_id/$total * 100; ?>
													<span> - <?php echo round( $count );?>% ( <?php echo ( $option_voting_post[3][0]->count_id > 1 ) ? $option_voting_post[3][0]->count_id.' votes' : $option_voting_post[3][0]->count_id.' vote'; ?> )</span><?php
												} ?>
											</li>
											<li>
												<label>neutral</label><?php
												if( $option_voting_post[4][0] ) {
													$count = $option_voting_post[4][0]->count_id/$total * 100; ?>
													<span> - <?php echo round( $count );?>% ( <?php echo ( $option_voting_post[4][0]->count_id > 1 ) ? $option_voting_post[4][0]->count_id.' votes' : $option_voting_post[4][0]->count_id.' vote'; ?> )</span><?php
												} ?>
											</li>
											<li>
												<label>conservative</label><?php
												if( $option_voting_post[5][0] ) {
													$count = $option_voting_post[5][0]->count_id/$total * 100; ?>
													<span> - <?php echo round( $count );?>% ( <?php echo ( $option_voting_post[5][0]->count_id > 1 ) ? $option_voting_post[5][0]->count_id.' votes' : $option_voting_post[5][0]->count_id.' vote'; ?> )</span><?php
												} ?>
											</li><?php
										}
										if( $infermative_count == 0 || $bias_count == 0 ) {
											if( is_user_logged_in() ) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" class="post_rating" onclick="post_rating(<?php echo $post->ID; ?>, 'bias', 'bias');">Rate the source</a>
                                                </li><?php
                                            } else { ?>
                                                <li>
                                                    <span class="sign_up_source"><a href="sign-up">sign up</a> or <a href="login">login</a> to rate the source </span>
                                                </li><?php
                                            }
                                        } ?>
										</ul>
										</span><?php
										if( is_user_logged_in() ) {
											//if( $bias_count == 0 ) { ?>
												<span class="bias_single_vote_post_option bias_single_vote_post_option_<?php echo $post->ID; ?>" style="display:none;"><?php
													if( $user_voting_hide_infor == 0 || $user_voting_hide_bias == 0) { ?>
													<form name="form_<?php echo $post->ID; ?>" method="post" action="" id="user_bias_voting_form_<?php echo $post->ID; ?>"><?php
													if( $user_voting_hide_infor == 0 ) { ?>
														<div class="single_vote_informative_option">
															<span>Informative?</span>
															<a href="javascript:void(0);" class="rating_option infermative_action" onclick="rating_option_bias(<?php echo $post->ID; ?>, 'bias', 'infermative', 'single');"><i class="fa fa-plus fa-2x"></i></a>
															<a href="javascript:void(0);" class="rating_option infermative_action_close" onclick="rating_option_bias(<?php echo $post->ID; ?>, 'bias', 'infermative', 'single');"><i class="fa fa-close fa-3x"></i></a>
															<div class="infermative_option" id="bias_infermative_option_single_<?php echo $post->ID; ?>" style="display:none;">
																<div class="infermative_option_single">
																	<select name="informative_select" id="bias_informative_select" class="rating_options_select cs-select cs-skin-elastic" onchange="select_vote_option(this.value, 'bias_informative');">
																		<option value="">Select</option>
																		<option value="very">very</option>
																		<option value="somewhat">somewhat</option>
																		<option value="not_really">not really</option>
																	</select>
																	<input type="hidden" name="informative" id="bias_informative" value="" />
																</div>
															</div>
														</div><?php
													}

													if( $user_voting_hide_bias == 0 ) { ?>
														<div class="single_vote_bias_option">
															<span>Bias?</span>
															<a href="javascript:void(0);" class="rating_option bias_action" onclick="rating_option_bias(<?php echo $post->ID; ?>, 'bias', 'bias', 'single');"><i class="fa fa-plus fa-2x"></i></a>
															<a href="javascript:void(0);" class="rating_option bias_action_close" onclick="rating_option_bias(<?php echo $post->ID; ?>, 'bias', 'bias', 'single');"><i class="fa fa-close fa-3x"></i></a>
															<div class="bias_option" id="bias_bias_option_single_<?php echo $post->ID; ?>" style="display:none;">
																<div class="bias_option_single">
																	<select name="bias_select" id="bias_bias_select" class="rating_options_select cs-select cs-skin-elastic" onchange="select_vote_option(this.value, 'bias_bias');">
																		<option value="">Select</option>
																		<option value="liberal">liberal</option>
																		<option value="neutral">neutral</option>
																		<option value="conservative">conservative</option>
																	</select>
																	<input type="hidden" name="bias" id="bias_bias" value="" />
																</div>
															</div>
														</div><?php
													} ?>
														<a href="javascript:void(0);" id="post_submit" onclick="fnLoadPosts('bias', <?php echo $post->ID; ?>);">Submit</a>
														<input  type="hidden" name="post_id" value="<?php echo $post->ID; ?>" />
														<input  type="hidden" name="posted_date" value="<?php echo date('Y-m-d'); ?> " />
														<input  type="hidden" name="user_id" value="<?php if($current_user){ echo $current_user->ID;}?>" />
													</form><?php
													} ?>
												</span><?php
											//}
										}?>
									</div>
								</div>
							</div><?php
						}
					$bi++;
					//}
				}
			}?>
			</div><?php

			if( is_user_logged_in() ) {
				$user_post_edit = get_post_meta( $post->ID, 'user_post_edit_'.$current_user->ID, true );
				$user_post_edit_system = get_post_meta( $post->ID, 'user_post_edit_system_'.$current_user->ID, true );
				$user_post_create = get_post_meta( $post->ID, 'user_post_create_'.$current_user->ID, true );
				$user_post_create_system = get_post_meta( $post->ID, 'user_post_create_system_'.$current_user->ID, true );
				if($user_post_edit ) {
					$explode_am = explode("AM",$user_post_edit.'aa');
					if($explode_am[1]) {
						$user_post_edit = str_replace(" AM",":00",$user_post_edit);
					} else {
						$user_post_edit = str_replace(" PM",":00",$user_post_edit);
					}
					$user_post_edit= str_replace(" : ",":",$user_post_edit);

					$date = new DateTime($user_post_edit, new DateTimeZone('UTC'));
					$timeZone = getCurrentTimeZone();
					$date->setTimezone(new DateTimeZone($timeZone ));
					$user_post_edit = $date->format('Y-m-d H:i:s');?>

						<p class="hide-767">You edited on  <?php echo date('M d Y', strtotime($user_post_edit)); ?> at <?php echo date('g:i A', strtotime($user_post_edit)); ?> </p><?php
				} else if($user_post_create){
					$explode_am = explode("AM",$user_post_create.'aa');
					if($explode_am[1]) {
						$user_post_create = str_replace(" AM",":00",$user_post_create);
					} else {
						$user_post_create = str_replace(" PM",":00",$user_post_create);
					}
					$user_post_create= str_replace(" : ",":",$user_post_create);

					$date = new DateTime($user_post_create, new DateTimeZone('UTC'));
					$timeZone = getCurrentTimeZone();
					$date->setTimezone(new DateTimeZone($timeZone ));
					$user_post_create = $date->format('Y-m-d H:i:s');?>

						<p class="hide-767">You added on <?php echo date('M d Y', strtotime($user_post_create)); ?> at <?php echo date('g:i A', strtotime($user_post_create)); ?> </p><?php
				}
			}
			$terms_arr = array();
			$terms_obj = get_the_terms( $post->ID, 'category' );
			if( $terms_obj ) {
				foreach( $terms_obj as $term) {
					$terms_arr[] = $term->slug;
				}
			}
			//You posted or edited


			$user_vote_arr = '';
			if( is_user_logged_in() ) {
				$user_vote_arr = $wpdb->get_results($wpdb->prepare("
											SELECT HQO.ques_option
											FROM ".$wpdb->prefix."hyroglf_users_voting AS HUV
											INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
											WHERE HUV.user_article_id = %d
											AND HUV.user_id = %d"
											, $post->ID, $user_id ));
			}
			if( $user_vote_arr ) { ?>
			<div class="user_vote_result_section source_publish_date_<?php echo $post->ID; ?> hide-767" <?php echo $style; ?> >
				<!--<span>Your Source Rating(s):</span>--><?php
				$post_arr = $wpdb->get_results("SELECT vote_utc_date FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles WHERE article_id = ".$post->ID." AND user_id =".$current_user->ID." ORDER BY voting_id DESC" ); ?>
				<ul class="single_page_your_rating">
				<li><span>You rated as </span></li><?php
					$option =  '';
					$option_1 =  '';
					$option_2 =  '';
					foreach( $user_vote_arr as $user_vote ) {
						$val = str_replace('_', ' ', $user_vote->ques_option);
						//$val = implode('', array_map(strtolower, explode('_', $str)));
						if( $user_vote->ques_option == 'very' || $user_vote->ques_option == 'somewhat' || $user_vote->ques_option == 'not_really' ) {
							$option_1 = strtolower($val).' informative';
						} else {
							$option_2 = ($val).' bias';
						}
					}
					if( $option_1 ) { ?>
						<li class="user_vote_rating user_info_rated_source_<?php echo $post->ID; ?>"><?php echo $option_1; ?></li><?php
					}
					if( $option_1 && $option_2 ) { ?>
						<li class="user_vote_rating margin_space"> and </li><?php
					}
					if( $option_2 ) { ?>
						<li class="user_vote_rating user_info_rated_source_<?php echo $post->ID; ?>"><?php echo $option_2; ?></li><?php
					} ?>
				</ul> on <?php

				$date = new DateTime($post_arr[0]->vote_utc_date, new DateTimeZone('UTC'));
				$timeZone = getCurrentTimeZone();
				$date->setTimezone(new DateTimeZone($timeZone ));
				$post_arr[0]->vote_utc_date = $date->format('Y-m-d H:i:s');

					echo date('M d Y', strtotime($post_arr[0]->vote_utc_date)); ?> at <?php echo date('g:i A', strtotime($post_arr[0]->vote_utc_date)); ?>
			</div><?php
			}if( $key == 'favorite_posts' ) {
				$user_meta = get_user_meta($current_user->ID);
				//print_r($user_meta);
				$vote_date_arr = unserialize($user_meta['wpfp_favorites_date'][0]);
				if( $vote_date_arr ) {
					if( isset( $vote_date_arr[$post->ID] ) ) {

					$date = new DateTime($vote_date_arr[$post->ID], new DateTimeZone('UTC'));
					$timeZone = getCurrentTimeZone();
					$date->setTimezone(new DateTimeZone($timeZone ));
					$vote_date_arr[$post->ID] = $date->format('Y-m-d H:i:s'); ?>

						<p class="favorites_result">Added to Favorites on <?php echo date('M d Y', strtotime($vote_date_arr[$post->ID])); ?> at <?php echo date('g:i A', strtotime($vote_date_arr[$post->ID])); ?></p><?php
					}
				}
			} ?>
            <div class="post_of_list_cat_section">
                <div class="post_of_cat_list post_of_cat_list_<?php echo $post_id; ?>"><?php custom_get_post_category($post->ID, 'all_post');
					$pulish_date = $content_data['publish_date_news'][0];
					if( $pulish_date ) { ?>
						<ul class="source_published_single_show" >
							<li>
								<p>Source Published <?php echo date( 'M d Y', strtotime( $pulish_date ) ); //date( 'M d Y g:i A', strtotime( $pulish_date ) );?></p>
							</li>
						</ul><?php
					}
					custom_get_post_tags($post->ID, 'all_post_tag'); // post tags ?>
                </div>
             </div>
             <div class="post_share_section post_share_section_<?php echo $post_id; ?>">
               <!-- <a href="javascript:void(0);" class="post_share_action"><i class="fa fa-share-square-o"></i>Share</a>-->
                <div class="post_share_icons" ><?php echo social_sharing_buttons(); ?></div>
            </div>
		</div>
	   </div>
			<div class="user_vote_result_section user_info_and_bias_rated_source_quick_<?php echo $post->ID; ?> source_publish_date_<?php echo $post->ID; ?>"></div><?php
	}

	function source_read( $post_id = '', $source_isset = false, $rating_isset = false ) {
		global $wpdb;
		$current_user = wp_get_current_user(); // current user
		$post_read_arr = get_post_meta( $post_id, 'post_read_time', true );
		$post_read = unserialize($post_read_arr);
		$time = '';
		$type = '';
		if( !empty( $post_read ) && is_array( $post_read ) ) {
			foreach( $post_read as $key => $value ) {
				$time = $value;
				$type = $key;
				if($source_isset) {
					echo ' - ';
				} else {
				} ?>
				<div class="post_read_time_section" id="post_read_time_section_<?php echo $post_id; ?>">
				<!--<span class="hide-500">(<?php echo $value.' '.$key.' '; ?> read/watch)</span>-->
                <span>(<?php echo $value.' '.$key.' '; ?> read)</span>
				</div><?php
			}
		}

		if($rating_isset) {
			echo ' - ';
		} else {
		}
	}

	function get_slide_image_and_video_single( $post_id ) {
		$post_multi_image_arr = unserialize( get_post_meta( $post_id, 'post_multi_images', true ) );
		$post_video = get_post_meta( $post_id, 'post_video', true );
		if( $post_multi_image_arr[0] || $post_video ) { ?>
			<div class="single_post_image_section">
				<ul class="gallery clearfix <?php if(count($post_multi_image_arr)>= 1 && count($post_video)>= 1 || count($post_multi_image_arr)> 1 || count($post_video)> 1 ||($post_multi_image_arr && $post_video) ){ echo 'single_post_multi_image_slide '; } else { echo "single_post_multi_image"; }?>" <?php if(count($post_multi_image_arr)> 1 || count($post_video)> 1 || ($post_multi_image_arr && $post_video)){?> style="display:none;" <?php }?>><?php
				if( $post_multi_image_arr[0] ) {
					if( !empty( $post_multi_image_arr[0] ) ) {
						foreach( $post_multi_image_arr as $post_multi_image ) {
							$full_image = wp_get_attachment_image_src ( $post_multi_image['image_id'], '' );
							$image = wp_get_attachment_image_src ( $post_multi_image['image_id'], '' );
							if( $image[0] ) { ?>
                                <li>
                                    <!--<div class="single_post_slide_image">-->
                                    <a class="fancybox" rel="gallery<?php echo $post_id; ?>" href="<?php echo $full_image[0]; ?>" title="">
                                        <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" /><?php
                                        if( isset( $post_multi_image['title'] ) ) {
                                            $link = $post_multi_image['title'];
                                        } else {
                                            $link = 'javascript:void(0)';
                                        } ?>
                                    </a>
                                        <!--<a href="<?php //echo $link; ?>">Image Source</a>-->
                                    <!--</div>-->
                                </li><?php
							}
						}
					}
				}

				if( $post_video ) {
					$post_video_url = $post_video;
					$rx = '~
					  ^(?:https?://)?                           # Optional protocol
					   (?:www[.])?                              # Optional sub-domain
					   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
					   ([^&]{11})                               # Video id of 11 characters as capture group 1
						~x';
					$has_match = preg_match($rx, $post_video_url, $matches);
					if($has_match == true){
							$url = $post_video;
							$explode_url  = explode("//www.",$url);
							$explode_share = explode('https://',$url);
							$explode_share_http = explode('http://',$url);
							$explode_m  = explode("https://m.",$url);

							if( isset( $explode_url[1] )&& !isset($explode_m[1]) ){
								$explode = explode("//www.",$url);
							} else if( isset( $explode_share[1] ) && !isset($explode_m[1] ) ){
								$explode = explode('https://',$url);
							} else if( isset( $explode_share_http[1] )&& !isset($explode_m[1] ) ){
								$explode = explode('http://',$url);
							} else if( isset( $explode_m[1] )  ){
								$explode = explode('m.',$url);
							}

							if(isset($explode[1])) {
								$explode_com = explode('.com',$explode[1]);
								$explode_be = explode('.be',$explode[1]);
								$explode_ly = explode('.ly',$explode[1]);

								if( isset($explode_com[1]) ) {
									$explode = explode('.com',$explode[1]);
								} else if(isset( $explode_be[1]) ) {
									$explode = explode('.be',$explode[1]);
								} else if(isset( $explode_ly[1]) ) {
									$explode = explode('.ly',$explode[1]);
								}

								if($explode[0] == 'dailymotion'){
									$explode_dailymotion = explode('/video/',$url);
									if(isset($explode_dailymotion[1])) {
										$url = str_replace('/video/','/embed/video/',$url);
									} else {
										$url = str_replace('/hub/','/embed/video/',$url);
									}
								}

								if($explode[0] == 'youtube') {
									$explode_youtube_url = explode('&',$url);
									if(isset($explode_m[1])) {
										$url = str_replace('m.youtube.com/watch?v=','www.youtube.com/embed/',$explode_youtube_url[0]);
									} else {
										$url = str_replace('watch?v=','embed/',$explode_youtube_url[0]);
									}
								}
								if($explode[0] == 'youtu') {
									$explode_youtube_url = explode('&',$url);
									if(isset($explode_url[1])) {
										$url = str_replace('youtu.be/','youtube.com/embed/',$explode_youtube_url[0]);
									} else {
										$url = str_replace('youtu.be/','www.youtube.com/embed/',$explode_youtube_url[0]);
									}
								}
								if($explode[0] == 'dai') {
									if(isset($explode_url[1])){
										$url = str_replace('dai.ly/','dailymotion.com/embed/video/',$url);
									} else {
										$url = str_replace('dai.ly/','www.dailymotion.com/embed/video/',$url);
									}
								}

								if($explode[0] == 'vimeo'){
									$explode_vimeo = explode('channels/vimeogirls/',$url);
									$explode_staffpicks = explode('channels/staffpicks/',$url);
									$explode_musicpicks = explode('channels/musicvideos/',$url);

									if(isset($explode_vimeo[1])){
										$url = str_replace('vimeo.com/channels/vimeogirls/','player.vimeo.com/video/',$url);
									} else if(isset($explode_staffpicks[1])){
										$url = str_replace('vimeo.com/channels/staffpicks/','player.vimeo.com/video/',$url);
									} else if(isset($explode_musicpicks[1])){
										$url = str_replace('vimeo.com/channels/musicvideos/','player.vimeo.com/video/',$url);
									} else{
										$url = str_replace('vimeo.com/','player.vimeo.com/video/',$url);
									}
								}
							}
							$post_video = $url;?>
					<li>
						<a class="fancybox fancybox.iframe" rel="gallery<?php echo $post_id; ?>" href="<?php echo $post_video; ?>">
							<div class="post_list_video"><iframe src="<?php echo $post_video; ?>" width="100%" height="222" allowfullscreen></iframe></div>
						</a>
					</li><?php
				} ?>
				</ul><?php
				}?>
			</div>
			<?php
		}
	}

	function social_sharing_buttons() {
		global $post;
			// Get current page URL

			// Get current page title
			$crunchifyTitle = str_replace( ' ', '%20', get_the_title());
			$crunchifycontent = str_replace( ' ', '%20', get_the_content());
			$crunchifyURL = urlencode(get_the_permalink($post->ID));
			// Construct sharing URL without using any script
			$twitterURL = 'https://twitter.com/intent/tweet?url='.$crunchifyURL.'&text='.$crunchifyTitle.'&via=hyroglf';
			//$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL ;
			//$facebookURL = "http://www.facebook.com/share.php?u=".$crunchifyURL."&title=".$crunchifyTitle."&description=Free summaries (up to 80 words) and source ratings. Anyone can vote or write, just be honest and concise.";
			$facebookURL = "https://www.facebook.com/sharer.php?u=".$crunchifyURL."&t=".$crunchifyTitle."&description=Free summaries (up to 80 words) and source ratings. Anyone can vote or write, just be honest and concise.";
			$whatsappURL = "whatsapp://send?text=".$crunchifyTitle.' '.$crunchifyURL;
			$smsURL = "sms://&body=".$crunchifyURL;
			//$link = 'http://www.facebook.com/sharer.php?s=100&p[title]="dsadsadsa"&p[summary]="dsadsadsadsa"&p[url]="$crunchifyURL"';

			$para = "".$post->ID.", '', '".get_the_title($post->ID)."', '".get_the_permalink($post->ID)."', 'page'";

			$variable .= '<div class="crunchify-social">';
			//$variable .= '<a class="crunchify-link crunchify-facebook" href="javascript:void(0);" data-value="'.$facebookURL.'" onclick="share_this_post_click_single(this);"><i class="fa fa-facebook"></i></a>';
			//$variable .= '<a class="crunchify-link crunchify-twitter" href="javascript:void(0);" data-value="'. $twitterURL .'" onclick="share_this_post_click_single(this);"><i class="fa fa-twitter"></i></a>';
			//$variable .= '<a href="javascript:void(0);" id="share_post_email" onclick="share_this_post_email('.$para.');" ng-click="share_this_post_email('.$para.');"><i class="fa fa-envelope" aria-hidden="true"></i></a>';
			//$variable .= '<a class="crunchify-link crunchify-whatsapp" href="javascript:void(0);" id="share_post_whatsapp" data-value="'.$whatsappURL.'" onclick="share_this_post_click_single(this);"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>';
			//$variable .= '<a class="crunchify-link crunchify-commenting" href="javascript:void(0);" id="share_post_sms" data-value="'.$smsURL.'" onclick="share_this_post_click_single(this);"><i class="fa fa-commenting" aria-hidden="true"></i></a>';

			$variable .= '<a class="crunchify-link crunchify-facebook" href="javascript:void(0);" data-value="'.$facebookURL.'" onclick="share_this_post_click_single(this);"></a>';
			$variable .= '<a class="crunchify-link crunchify-twitter" href="javascript:void(0);" data-value="'. $twitterURL .'" onclick="share_this_post_click_single(this);"></a>';
			$variable .= '<a href="javascript:void(0);" id="share_post_email" onclick="share_this_post_email('.$para.');" ng-click="share_this_post_email('.$para.');"><i class="fa fa-envelope" aria-hidden="true"></i></a>';
			$variable .= '<a class="crunchify-link crunchify-whatsapp" href="javascript:void(0);" id="share_post_whatsapp" data-value="'.$whatsappURL.'" onclick="share_this_post_click_single(this);"></a>';
			$variable .= '<a class="crunchify-link crunchify-commenting" href="javascript:void(0);" id="share_post_sms" data-value="'.$smsURL.'" onclick="share_this_post_click_single(this);"></a>';

			$variable .= '</div>';

			return $variable;
	}

	function custom_get_post_category($post_id, $action = '') {
		$cat_terms = get_the_terms($post_id, 'category');
		if($cat_terms) {?>
		<!--<div class="post_of_list_cat_section">
			<div class="post_of_cat_list post_of_cat_list_<?php echo $post_id; ?>">-->
				<!--<span><h4>Categories: </h4></span>-->
				<ul class="post_categories_list"><?php
					foreach( $cat_terms as $cat ) {
						$term_image_id = get_option('category_'.$cat->term_id.'_post_category_image');
						$image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' ); ?>
						<li class="post_cat_list post_cat_list_<?php echo $post_id; ?> post_cat_list_<?php echo $post_id; ?>_<?php echo $cat->term_id; ?>" id="post_<?php echo $post_id; ?>_cat-<?php echo $cat->term_id; ?>" data-id="<?php echo $cat->term_id; ?>" data-value="<?php echo $cat->slug; ?>">
							<a class="post_list_cat post_list_cat-<?php echo $cat->term_id; ?>" title="Remove category" onclick="remove_category(this, <?php echo $cat->term_id; ?>, <?php echo $post_id; ?>);">X</a><?php
							if( $action == 'single_post_edit' ) { ?>
								<a href="javascript:void(0);" class="post_cat_name post_cat_name-<?php echo $cat->term_id; ?>">
									<?php echo $cat->name; ?>
								</a><?php
							} else { ?>
								<a href="javascript:void(0);<?php //echo home_url('/?term=').$cat->slug;?>" class="post_cat_name post_cat_name-<?php echo $cat->term_id; ?>" onclick="inner_page_cat_filter('<?php echo $cat->slug; ?>', 'category', 'cat_post', '<?php echo $image[0]; ?>','<?php echo $cat->name; ?>');">
									<?php echo $cat->name; ?>
								</a><?php
							} ?>
							<input type="hidden" name="fp_tax_category[]" value="<?php echo $cat->term_id; ?>">
						</li><?php
					} ?>
				</ul>
			<!--</div>
		</div>--><?php
		}
	}

	function custom_get_post_tags($post_id, $action = '') {
		$tags = wp_get_post_tags($post_id, 'post_tag');
		if($tags) { ?>
		<!--<div class="list_of_tag_section list_of_tag_section-<?php echo $post_id; ?>">-->
			<!--<h3>Tag: </h3>-->
			<ul class="tags_single"><?php
				$count = 0;
				foreach($tags as $tag) {?>
                    <li class="list_tag list_tag_<?php echo $count;?>">
                        <a href="javascript:void(0);<?php //echo home_url('/?tag_post='.$tag->slug);?>" onclick="inner_page_cat_filter('<?php echo $tag->slug; ?>', 'post_tag', 'cat_post', '<?php echo $image[0]; ?>','<?php echo $tag->name;?>');"><?php echo $tag->name;?></a>
                    </li>
                    <input type="hidden" name="list_post_tags[]" id="list_post_tags_<?php echo $count; ?>" value="<?php echo $tag->name;?>"><?php
                    $count++;
				} ?>
			</ul>
		<!--</div>--><?php
		}
	}

	//add_action('init', 'set_post_title_in_meta');
	function set_post_title_in_meta__() {
		$arg = array(
			'post_type'	=> 'post',
			'posts_per_page'	=> -1,
		);
		$query = new WP_Query($arg);
		if( $query->have_posts() ) {
			while( $query->have_posts() ) : the_post(); global $post;
			$link = get_the_permalink($post->ID);
			$get_post_url = get_post_meta($post->ID,'post_url', true);
			if( $get_post_url)  {
				update_post_meta($post->ID, 'post_url', $link );
			} else {
				add_post_meta($post->ID, 'post_url', $link );
			}
			endwhile;
		}
	}

	function fp_save_my_custom_fields ($tmp_post, $tmp_task_new, $input_values ) {
		global $wpdb;
		$reference_link = $input_values['reference_link'];
		$post_id = $tmp_post->ID;
		$exists_reference_link = $wpdb->get_results("SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'reference_link' AND meta_value = '$reference_link'");
		if(!$exists_reference_link) {
			if( array_key_exists('reference_link', $input_values) ) {
				$url = '';
				if( $input_values['reference_link'] ) {
					$url = $input_values['reference_link'];
				} else {
					$url = '';
				}
				saveSiteFavicon($tmp_post->ID, $url); // save favicon to db
				$get_post_meta=get_post_meta($tmp_post->ID, 'reference_link', $input_values['reference_link']);
				update_post_meta($tmp_post->ID, 'reference_link', $input_values['reference_link'] );
			}
		}
		if( is_array($input_values) && array_key_exists('publish_date_news', $input_values) ) {
			update_post_meta($tmp_post->ID, 'publish_date_news', $input_values['publish_date_news'] );
		}

		if( is_array($input_values) && array_key_exists('new_category', $input_values) ) {
			update_post_meta($tmp_post->ID, 'new_category', $input_values['new_category'] );
		}

		if(!empty($input_values['reference_link'])){
			$page_url = $input_values['reference_link'];
			$read_page = file_get_contents($page_url);
			preg_match("/<title.*?>[\n\r\s]*(.*)[\n\r\s]*<\/title>/", $read_page, $page_title);
			if (isset($page_title[1])) {
				if ($page_title[1] == '') {
					$page_title = $page_url;
				}
				$page_title = $page_title[1];
				$page_title = trim($page_title);
			} else {
				$page_title = $page_url;
			}
			preg_match( '#<h1[^>]*>(.*?)</h1>#i', $read_page, $h1_title );
			if($h1_title && is_array($h1_title)) {
				if(isset($h1_title[0])) {
					$post_title_reference_link = $h1_title[0];
				}
				if(isset($h1_title[1])) {
					$post_title_reference_link = $h1_title[1];
				}
			}
			$home_page_title = $page_title;
			$exists_reference_link_favicon = $wpdb->get_results("SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'post_ref_link_favicon' AND meta_value = '$post_ref_link_favicon'");
			if(!empty($input_values['reference_link'])) {
				 $wpdb->insert('wp_postmeta', array(
								 'meta_key'         => 'refernce_link_home_page_title',
								 'post_id'          => $tmp_post->ID,
								 'meta_value'       => $home_page_title));
			}
		}
		if(!empty($input_values['new_category'])) {
			$results_category = $wpdb->get_var("SELECT  count(name) from wp_terms where name='".$_POST['new_category']."'");
			if($results_category==0 && !empty($_POST['new_category'])){
				$wpdb->insert('wp_terms', array(
								 'name' => $_POST['new_category'],
								 'slug' => $_POST['new_category']));
				$insert_id=$wpdb->insert_id;
				$wpdb->insert('wp_term_taxonomy', array(
								 'term_id' => $insert_id,
								 'taxonomy' => 'category',
								 'count' => 1));

				$post_id_category=$tmp_post->ID;
				$term_taxonomy_id=$wpdb->insert_id;

				$wpdb->insert('wp_term_relationships', array(
									 'object_id' => $post_id_category,
									 'term_taxonomy_id' => $term_taxonomy_id));
			}
		}
	}
	add_action( 'frontier_post_post_save', 'fp_save_my_custom_fields', 10, 3 );

function saveSiteFavicon___($post_id, $url) {
	if( $url ) {
		$upload_dir = wp_upload_dir();

		// Drop the TLD from the url
		$saveFileName = substr($url,0,-4);
		$saveFileName = time();
		$path = $upload_dir['basedir'].'/fav-icon/'.$saveFileName.'.png';
		$fp = fopen ($path, 'w+');
		$path = $upload_dir['baseurl'].'/fav-icon/'.$saveFileName.'.png';

		add_post_meta($post_id, 'post_ref_link_favicon', $path); // favicon add to postmeta

		$ch = curl_init('http://www.google.com/s2/favicons?domain='.$url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 6);

		// Save the returned data to a file
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
}
/*if (!function_exists('wp_password_change_notification')) {
    function wp_password_change_notification($user) {
    return;
    }
}*/
if ( !function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification() {}
}
add_filter( 'send_password_change_email', '__return_false');
//logout err
function logout_redirect_page() {
wp_logout( home_url() );
		$smart_redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '/';
		wp_safe_redirect( $smart_redirect_to );
		exit();
}
add_action ( 'login_form_logout' , 'logout_redirect_page' );

add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
	$explode_url = explode('wp-admin',$_SERVER['HTTP_REFERER']);
	if($explode_url[1]){
  		wp_redirect( home_url('/index.php')); //changes made here 20 Sept 2018
	} else {
		$redirect_to_url = explode('redirect_to=',$_SERVER['REQUEST_URI']);
		$redirect_url_complete = explode('_wpnonce=',$redirect_to_url[1]);
		$redirect_url_complete = str_replace('&','',$redirect_url_complete[0]);

		if(is_array($redirect_to_url) && $redirect_to_url[1] && $redirect_url_complete[1]){
			wp_redirect( urldecode($redirect_url_complete) );
		} else {
			wp_redirect( home_url('/index.php')); //changes made here 20 Sept 2018
		}

	}
  exit();
}


//function for redirecting users on login based on user role
/*function my_login_redirect( $url, $request, $user ){
    if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
        if( !$user->has_cap( 'administrator' ) ) {
      	  $url = home_url();
        }
    }
    return $url;
}*/
//add_filter('login_redire', 'my_login_redirect', 10, 3 );

/*function remove_dashboard_non_admin(){
	global $current_user;
	if($current_user->roles[0] != "administrator" ){
		wp_redirect(home_url());
	}
}
add_action('admin_menu', 'remove_dashboard_non_admin');*/

//for block emailnotifications
add_filter( 'send_password_change_email', '__return_false');
add_filter( 'send_email_change_email', '__return_false');

//log out the inactive users
if(is_user_logged_in()){

	$user_id = wp_get_current_user()->ID;
	$user_ac_status = get_user_meta( $user_id, 'user_account_status','true');
	if($user_ac_status != "active"){
		wp_clear_auth_cookie();
	}
}


add_action( 'init', 'custom_session_destroy');
function custom_session_destroy() {
	require_once(ABSPATH.'wp-admin/includes/user.php' );
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$user_account_status = get_user_meta( $user_id, 'user_account_status', true );
   if($user_account_status == 'inactive'){
		$sessions = WP_Session_Tokens::get_instance($user_id);
		// we have got the sessions, destroy them all!
		$sessions->destroy_all();
	}
}

add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );

function keep_me_logged_in_for_1_year( $expirein ) {
    return 31556926; // 1 year in seconds
}


/* most viewes Count in admin */
add_filter('manage_post_posts_columns','filter_cpt_columns' );

function filter_cpt_columns( $columns ) {
    // this will add the column to the end of the array
    $columns['most_viewed_post_count'] = 'Views count';
    //add more columns as needed

    // as with all filters, we need to return the passed content/variable
    return $columns;
}
add_action( 'manage_posts_custom_column','action_custom_columns_content' , 10, 2 );
function action_custom_columns_content ( $column_id, $post_id ) {
    //run a switch statement for all of the custom columns created
    switch( $column_id ) {
        case 'most_viewed_post_count':
			global $wpdb;
			$result = $wpdb->get_var("SELECT count(post_id) FROM wp_hyroglf_analytics WHERE post_id = $post_id");
            echo ($result) ? $result : '0';
        break;

   }
}
	function post_published_notification( $postid, $post ) {
		date_default_timezone_set('UTC');
		$current_user = wp_get_current_user(); // current user
			global $wpdb;
			$get_time = $wpdb->get_results("SELECT now() as timer");
			$post_glf_update = get_post_meta( $postid, 'glf_update', true );
			if( $post_glf_update ) {
				update_post_meta( $postid, 'glf_update',  date('Y-m-d H:i:s') );
				update_post_meta( $postid, 'glf_date_update_utc',  date('Y-m-d H:i:s') );
				update_post_meta( $postid, 'glf_update_system', date('Y-m-d H:i:s') );
			} else {
				add_post_meta( $postid, 'glf_update', date('Y-m-d H:i:s') );
				add_post_meta( $postid, 'glf_date_update_utc',  date('Y-m-d H:i:s') );
				add_post_meta( $postid, 'glf_update_system', date('Y-m-d H:i:s') );
			}

			$post_data = "SELECT meta_key FROM ".$wpdb->prefix."postmeta WHERE post_id =".$postid." AND meta_key LIKE '%user_post_create%' LIMIT 0 , 30";
			$data = $wpdb->get_results( $post_data );
			$count = count($data);

			//if
			//$user_post_edit = get_post_meta( $postid, 'user_post_create_'.$current_user->ID, true );
			global $wpdb;
			if( $count > 0 ) {
				update_post_meta( $postid, 'user_post_edit_'.$current_user->ID, date('Y-m-d H:i:s') );
				update_post_meta( $postid, 'user_post_edit_system_'.$current_user->ID, date('Y-m-d H:i:s') );
				//die();
			} else {
				add_post_meta( $postid, 'user_post_create_'.$current_user->ID, date('Y-m-d H:i:s') );
				add_post_meta( $postid, 'user_post_create_system_'.$current_user->ID, date('Y-m-d H:i:s') );
				//
			}

	}
	add_action( 'publish_post', 'post_published_notification', 10, 2 );
/*add_action( 'transition_post_status', 'a_new_post', 10, 3 );

function a_new_post( $new_status, $old_status, $post )
{
   // add post edit user name
   echo $new_status.'---'.$old_status;
   die;
	if( isset( $_REQUEST['task'] ) && $_REQUEST['task'] == 'edit' ) {
		$last_edited_user = get_post_meta( $postid, 'last_edited_user', true );
		if( $last_edited_user ) {
			update_post_meta( $postid, 'last_edited_user', $current_user->user_login );
		} else {
			add_post_meta( $postid, 'last_edited_user', $current_user->user_login );
		}
	}
}
*/

/*add_filter('allowed_http_origins', 'add_allowed_origins');

function add_allowed_origins($origins) {
    $origins[] = 'https://www.hyroglf.com';
    return $origins;
}*/
?>
