<?php
class DbHandler{

    private $conn;
	
    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }
	
	public function post_per_page() {
		return 5;
	}
	
	public function current_user_data($user_id = '') {
		
	}
	
    public function getAllRecord($query) {
    	$r = $this->conn->query($query) or die($this->conn->error.__LINE__);
   	 	while ( $row = $r->fetch_array(MYSQLI_ASSOC) ) {
   	 		$data[] = $row;
    	}
		if(empty($data)){
			$data['text'] ="No records found";
		}
    	return $data;    
	}
	
	public function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
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
	public function getTimeZone(){
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
			$ipInfo = file_get_contents("http://freegeoip.net/json/$ip");
			$ipInfo = json_decode($ipInfo);
			$country_code = $ipInfo->country_code;
			$cur_lat = $ipInfo->latitude;
			$cur_long = $ipInfo->longitude;
			$timezone = $ipInfo->time_zone;
			if($timezone == ''){
				$timezone = $this->get_nearest_timezone($cur_lat, $cur_long, $country_code);
			}
			
		}
		return $timezone;
	}
	
	public function humanTiming ($date){
		/*$timezone = '';
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
			$ip = $ipaddress;*/  //$_SERVER['REMOTE_ADDR']
		/*if(isset($ip)){
			$ipInfo = file_get_contents("http://freegeoip.net/json/$ip");
			$ipInfo = json_decode($ipInfo);
			$country_code = $ipInfo->country_code;
			$cur_lat = $ipInfo->latitude;
			$cur_long = $ipInfo->longitude;
			$timezone = $ipInfo->time_zone;
			if($timezone == ''){
				$timezone = $this->get_nearest_timezone($cur_lat, $cur_long, $country_code);
			}
			if(isset($timezone)){
				date_default_timezone_set($timezone);
				date_default_timezone_get();
			}
		}*/
		date_default_timezone_set($this->getTimeZone());
		
		if(empty($date)) {
			return "No date provided";
		}
		 
			$periods         = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
			$lengths         = array("60","60","24","7","4.35","12","10");
		 
			$now             = time();
			$unix_date         = strtotime($date);
		 
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
		
		
		
		public function check_username_in_user_meta($name,$wpdb) {
			return $sql = $this->getOneRecord("SELECT count(ID) as count FROM ".$wpdb."users WHERE user_nicename= '$name' OR display_name = '$name' OR user_login = '$name'");
		}
		
		public function check_email_in_user_meta($email,$wpdb) {
			return $sql = $this->getOneRecord("SELECT count(ID) as count FROM ".$wpdb."users WHERE user_email = '$email'");
		}
		
		public function check_current_useremail_in_user_meta($email,$wpdb,$user_id) {
			return $sql = $this->getOneRecord("SELECT user_email as user_email FROM ".$wpdb."users WHERE ID = $user_id");
		}
		
		public function check_current_username_in_user_meta($name,$wpdb,$user_id) {
			return $sql = $this->getOneRecord("SELECT user_login as user_login FROM ".$wpdb."users WHERE ID = $user_id");
		}
		public function get_userprofile_data($wpdb,$user_id) {
			return $sql = $this->getAllRecord("SELECT user_login,user_email FROM ".$wpdb."users WHERE ID = $user_id");
		}
		
		public function myprofile_update_data($user_name,$email,$wpdb,$user_id){
			session_start();
			$result = '';
			$user_name_check = $this->check_username_in_user_meta($user_name,$wpdb);
			$user_email_check = $this->check_email_in_user_meta($email,$wpdb);
			$user_current_useremail = $this->check_current_useremail_in_user_meta($email,$wpdb,$user_id);
			$user_current_username = $this->check_current_username_in_user_meta($user_name,$wpdb,$user_id);
			
			if($user_current_username['user_login'] != $user_name){
				if( $user_name_check['count'] < 1){
					$updated_username = 1;
					$result['user_value'] = 1;
					$_SESSION['username'] = $user_name;
					/*$updated_username = 1;
					$result['user_value'] = 1;
					$sql = $this->updateData("UPDATE ".$wpdb."users SET user_nicename = '$user_name',display_name = '$user_name',user_login = '$user_name'  WHERE ID = $user_id");
					$result['user_message'] = 'Username successfully updated';*/
				} else {
					$updated_username = 0;
					$result['user_value'] = 0;
					$result['user_message'] = 'Username already exists';
				}
			}
			
			if($user_current_useremail['user_email'] != $email){
				if($user_email_check['count'] < 1){
					$_SESSION['user_email'] = $email;
					$updated_email = 1;
					$result['email_value'] = 1;
					/*$sql = $this->updateData("UPDATE ".$wpdb."users SET user_email = '$email' WHERE ID = $user_id");
					$updated_email = 1;
					$result['email_value'] = 1;
					$result['email_message'] = 'Email successfully updated';*/
				} else {
					$updated_email = 0;
					$result['email_value'] = 0;
					$result['email_message'] = 'Email already registered.';
				}
			}
				
			
			if(isset($updated_username) && $updated_username == 1 && isset($updated_email) && $updated_email == 1 || isset($updated_email) && $updated_email == 1 || isset($updated_username) && $updated_username == 1){			
				$result['message'] = 'success';				
			} 
			
			if(isset($updated_username) && $updated_username == 0 && isset($updated_email) && $updated_email == 0){	
				$result['email_message'] = '';	
				$result['user_message'] = '';
				$result['email_value'] = '';
				$result['user_value'] = '';		
				$result['error_message'] = 'Username and email already exists';				
			} 
			
			if(empty($result)){
				$result['error_message'] = 'Change Username and/or Email.';
			}
			
			
			return array('output' =>$result);			
			
		}
		
		public function userprofile_update_data($user_name,$email,$wpdb,$user_id){
			
			$result = '';
			$user_name_check = $this->check_username_in_user_meta($user_name,$wpdb);
			$user_email_check = $this->check_email_in_user_meta($email,$wpdb);
			$user_current_useremail = $this->check_current_useremail_in_user_meta($email,$wpdb,$user_id);
			$user_current_username = $this->check_current_username_in_user_meta($user_name,$wpdb,$user_id);
			
			if($user_current_username['user_login'] != $user_name){
				if( $user_name_check['count'] < 1){	
					$updated_username = 1;
					$result['user_value'] = 1;
					$sql = $this->updateData("UPDATE ".$wpdb."users SET user_nicename = '$user_name',display_name = '$user_name',user_login = '$user_name'  WHERE ID = $user_id");
					$result['user_message'] = 'Username successfully updated.';
					session_start();
					$_SESSION["user_message"] = 'Username successfully changed!';
				} else {
					$updated_username = 0;
					$result['user_value'] = 0;
					$result['user_message'] = 'Username already exists';
				}
			}
			
			if($user_current_useremail['user_email'] != $email){
				if($user_email_check['count'] < 1){
					$sql = $this->updateData("UPDATE ".$wpdb."users SET user_email = '$email' WHERE ID = $user_id");
					$updated_email = 1;
					$result['email_value'] = 1;
					$result['email_message'] = 'Email successfully updated.';
				} else {
					$updated_email = 0;
					$result['email_value'] = 0;
					$result['email_message'] = 'Email already registered.';
				}
			}
				
			
			if(isset($updated_username) && $updated_username == 1 && isset($updated_email) && $updated_email == 1){	
				$result['email_message'] = '';	
				$result['user_message'] = '';		
				$result['message'] = 'Username and email updated successfully';				
			} 
			
			if(isset($updated_username) && $updated_username == 0 && isset($updated_email) && $updated_email == 0){	
				$result['email_message'] = '';	
				$result['user_message'] = '';
				$result['email_value'] = '';
				$result['user_value'] = '';		
				$result['error_message'] = 'Username and email already exists';				
			} 
			if(empty($result)){
				$result['error_message'] = 'Change Username and/or Email.';
			}
			
			
			return array('output' =>$result);			
			
		}
		

	
	public function getDatas( $wpdb = '', $load = '', $user_id = '', $user_access = '', $tax = '', $term = '', $page_num = '', $order = '', $user_role ='' , $drop_down_filter= array(), $view = '', $user_ip = '', $plugin_url = '',  $base_url = '' , $site_url= '' ) {

		date_default_timezone_set('UTC');
    	$post_data = array();
		$s = '';
		
		$plugin_url = str_replace('http://','https://',$plugin_url);
		$base_url = str_replace('http://','https://',$base_url);
		$site_url = str_replace('http://','https://',$site_url);
		
		
		$user_voting_hide_infor = 0;
		$user_voting_hide_bias = 0;
		
		$edit_access = false;
		if( $user_access && $user_role != 'user' ) {
			$edit_access = true;
		}
	
		if( $load == 'random' ) {
			$order_by = 'rand()';
			$post_per_page = 1;
			$offset = 0;
		} else {
			$order_by = 'WP_post.post_modified';
			$post_per_page = $this->post_per_page();
			
			if( $page_num > 1 ) {
				$post_per_page = $this->post_per_page();
				$offset = $page_num * $post_per_page;
				$offset = ($offset - $this->post_per_page());
			} else {
				$offset = 0;
			}
		}
		if( $tax != 'user_by_vote'){
			$querystr='';
			$JOIN = '';
			$AND = '';
			$required_value = "DISTINCT WP_post.post_title, WP_post.ID, WP_post.guid, WP_post.post_name, WP_post.post_content, WP_post.post_date, WP_post.post_modified,WP_post.post_author";
			if($load == 'most_viewed' || $term == 'most_viewed' && $load == 'cat_post' || $tax == 'most_viewed' && $load == 'cat_post'){	
				$required_value .=",count(WP_post.post_title) as mv_post_count";
			}
				
			if( isset($drop_down_filter['informative']) && $drop_down_filter['informative'] != '' || isset($drop_down_filter['bias']) && $drop_down_filter['bias'] != '' ) {
				if( $drop_down_filter['informative'] && $drop_down_filter['bias'] ) {
					
					if( $drop_down_filter['informative'] && $drop_down_filter['informative'] != 'all' ) {
						$JOIN .= " INNER JOIN ".$wpdb."postmeta AS wp_meta_1 ON wp_meta_1.post_id = WP_post.ID";
						$AND = " AND wp_meta_1.meta_key = 'vote_for_".$drop_down_filter['informative']."'";
					}
					
					if( $drop_down_filter['bias'] && $drop_down_filter['bias'] != 'all' ) {
						$JOIN .= " INNER JOIN ".$wpdb."postmeta AS wp_meta_2 ON wp_meta_2.post_id = WP_post.ID";
						$AND .= " AND wp_meta_2.meta_key = 'vote_for_".$drop_down_filter['bias']."'";
					}
					
				} else if( $drop_down_filter['informative'] && $drop_down_filter['informative'] != 'all' ) {
					
					$JOIN = " INNER JOIN ".$wpdb."postmeta AS wp_meta ON wp_meta.post_id = WP_post.ID";
					$AND = " AND wp_meta.meta_key = 'vote_for_".$drop_down_filter['informative']."'";
					
				} else if( $drop_down_filter['bias'] && $drop_down_filter['bias'] != 'all' ) {
					
					$JOIN = " INNER JOIN ".$wpdb."postmeta AS wp_meta ON wp_meta.post_id = WP_post.ID";
					$AND = " AND wp_meta.meta_key = 'vote_for_".$drop_down_filter['bias']."'";
				}
			}
			if( $load != 'search_post' && $term && $term != 'favorite_posts' && $term != 'most_viewed' && $term != 'my_posts_and_votes' && $tax != 'post_filter_by_author' && $term != 'recent_post' ) {
				$querystr = "
					SELECT ".$required_value."
					FROM ".$wpdb."posts AS WP_post
					LEFT JOIN ".$wpdb."term_relationships AS WP_term_relation
						ON(WP_post.ID = WP_term_relation.object_id)
					LEFT JOIN ".$wpdb."term_taxonomy AS WP_term_tax
						ON(WP_term_relation.term_taxonomy_id = WP_term_tax.term_taxonomy_id)
					LEFT JOIN ".$wpdb."terms AS WP_term
						ON(WP_term_tax.term_id = WP_term.term_id)
					$JOIN
					WHERE WP_term.slug = '$term'
					AND WP_term_tax.taxonomy = '$tax'
					AND WP_post.post_status = 'publish'
					AND WP_post.post_type = 'post'
					$AND
				";
				
			} else if( $term == 'favorite_posts' && $user_id ) {
				// Favorite posts
				//$user_meta = $this->current_user_data($user_id);
				//$post_id_arr = unserialize($user_meta['wpfp_favorites'][0]);
				$user_fav_arr = $this->getOneRecord("SELECT meta_value FROM ".$wpdb."usermeta WHERE user_id = $user_id AND meta_key = 'wpfp_favorites'");
				$post_id_arr = unserialize($user_fav_arr['meta_value']);
				
				$post__in = array();
				$post_in= array();
				if( is_array( $post_id_arr ) && !empty( $post_id_arr )) {
					foreach( $post_id_arr as $post_id ) {
						if( $post_id ) {
							$post_in[] = $post_id;
						}
					}
					$post__in = implode(',',$post_in);
				}
				
				$querystr = "
					SELECT ".$required_value." FROM ".$wpdb."posts AS WP_post
					$JOIN
					WHERE WP_post.post_type = 'post'
					AND WP_post.post_status = 'publish'
					AND WP_post.ID IN($post__in)
					$AND
					";
				
			} else {
				
				$querystr = "SELECT ".$required_value." FROM ".$wpdb."posts AS WP_post ";
				
				if( $load == 'search_post' ) {
					$JOIN = " 
					JOIN ".$wpdb."postmeta AS wp_meta 
						ON wp_meta.post_id = WP_post.ID
					JOIN ".$wpdb."term_relationships AS WP_term_relation
						ON(WP_post.ID = WP_term_relation.object_id)
					JOIN ".$wpdb."term_taxonomy AS WP_term_tax
						ON(WP_term_relation.term_taxonomy_id = WP_term_tax.term_taxonomy_id)
					JOIN ".$wpdb."terms AS WP_term
						ON(WP_term_tax.term_id = WP_term.term_id)";
					// Search post load by ajax filter
					$s = $term;	
					$explode = explode(' ', $s);
					//foreach($explode  as $search){
						if( $s ) {
							/*$AND.= " AND (WP_post.post_title LIKE '%$s%' OR WP_post.post_content LIKE '%$s%' OR WP_post.post_name LIKE '%$s%' OR WP_term.name LIKE '%$s%') OR (wp_meta.meta_key = 'source_name' AND wp_meta.meta_value LIKE '%$s%')";*/
							//$AND.= " AND (WP_post.post_title  RLIKE '[[:<:]]".$s."[[:>:]]' OR WP_post.post_content RLIKE '[[:<:]]".$s."[[:>:]]' OR WP_post.post_name RLIKE '[[:<:]]".$s."[[:>:]]' OR WP_term.name RLIKE '[[:<:]]".$s."[[:>:]]') OR (wp_meta.meta_key = 'source_name' AND wp_meta.meta_value RLIKE '[[:<:]]".$s."[[:>:]]')";
							/*$AND.= " AND (WP_post.post_title LIKE '% ".$s." %' OR WP_post.post_content LIKE '% ".$s." %' OR WP_post.post_name LIKE '% ".$s." %' OR WP_post.post_title LIKE '$s' OR WP_post.post_content LIKE '$s' OR WP_post.post_name LIKE '$s' OR WP_term.name LIKE '$s') OR (wp_meta.meta_key = 'source_name' AND wp_meta.meta_value LIKE '$s') OR (wp_meta.meta_key = 'source_name' AND wp_meta.meta_value LIKE '% ".$s." %')";*/

$AND.= " AND (WP_post.post_title LIKE '%".$s."%' OR WP_post.post_content LIKE '%".$s."%' OR WP_post.post_name LIKE '%".$s."%' OR WP_post.post_title LIKE '$s' OR WP_post.post_content LIKE '$s' OR WP_post.post_name LIKE '$s' OR WP_term.name LIKE '$s') OR (wp_meta.meta_key = 'source_name' AND wp_meta.meta_value LIKE '$s') OR (wp_meta.meta_key = 'source_name' AND wp_meta.meta_value LIKE '%".$s."%')";


						}
					//}
					/*if( $s ) {
						$AND = " AND (WP_post.post_title LIKE '%$s' OR WP_post.post_content LIKE '%$s%' )";
					}*/
					if($user_access && $user_id && $tax == 'search_post') {
						$date = strtotime(date('Y-m-d H:i:s'));
						
						$user_search_key = $this->getOneRecord("SELECT search_key FROM ".$wpdb."hyroglf_user_search_keys WHERE user_id = '$user_id' AND search_key = '$s'");
						
						if( !$user_search_key ) {
							$obj = array( 
								'user_id' => $user_id, 
								'search_key' => $s,
								'search_date' => $date
							);
							$table_name = $wpdb.'hyroglf_user_search_keys';
							$column_names = array('user_id','search_key','search_date');
							$this->insertIntoTable($obj, $column_names, $table_name);
						}					
					}
				}
				
				if( $load == 'most_viewed' && $view == '' ||  $term == 'most_viewed' && $tax == 'most_viewed' && $view == ''  || $view == 'today' || $load == 'most_viewed' && $view == 'today' || $term == 'most_viewed' && $view == 'today' || $term == 'most_viewed' && $load == 'cat_post' && $view == 'today' || $tax == 'most_viewed' && $load == 'cat_post' && $view == 'today') {
					
					$JOIN = " INNER JOIN ".$wpdb."hyroglf_analytics AS HA ON HA.post_id = WP_post.ID";
					$AND = "AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)";
					
				} else if( $load == 'most_viewed' && $view == 'this_week' || $term == 'most_viewed' && $view == 'this_week' || $load == 'most_viewed' && $view == 'this_week') {
					
					$JOIN = " INNER JOIN ".$wpdb."hyroglf_analytics AS HA ON HA.post_id = WP_post.ID";
					$AND = "AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
					
				} else if( $load == 'most_viewed' && $view == 'this_month' || $term == 'most_viewed' && $view == 'this_month' || $load == 'most_viewed' && $view == 'this_month') {
					
					$JOIN = " INNER JOIN ".$wpdb."hyroglf_analytics AS HA ON HA.post_id = WP_post.ID";
					$AND = "AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
					
				}
				
				if( $term == 'my_posts_and_votes' && $user_id ) {
					$type = 'OR';
					if( isset($drop_down_filter['informative']) && !empty($drop_down_filter['informative']) && $drop_down_filter['informative'] != 'all' || isset($drop_down_filter['bias']) && !empty($drop_down_filter['bias']) && $drop_down_filter['bias'] != 'all') {
						$type = 'AND';
					}
					
					// get current user voted post id form current user id			
					$AND .= " 
						AND WP_post.post_author =".$user_id."
						$type WP_post.ID in (
									SELECT DISTINCT article_id
									FROM ".$wpdb."hyroglf_users_voting_for_articles
									WHERE user_id =".$user_id.
									")
						";
				}
				
				if( $tax == 'post_filter_by_author' ) {
					
					$type = 'OR';
					if( isset($drop_down_filter['informative']) && !empty($drop_down_filter['informative']) && $drop_down_filter['informative'] != 'all' || isset($drop_down_filter['bias']) && !empty($drop_down_filter['bias']) && $drop_down_filter['bias'] != 'all' ) {
						$type = 'AND';
					}
					
					// get current user voted post id form current user id			
					$AND .= " 
						AND WP_post.post_author =".$term;
						}
				$querystr .= "
					$JOIN
					WHERE WP_post.post_type = 'post'
					AND WP_post.post_status = 'publish'
					$AND
				";
				
				
			}
		} else {
			$user_voted_article = $this->getAllRecord("SELECT article_id FROM ".$wpdb."hyroglf_users_voting_for_articles WHERE user_id=".$user_id);
			$post_id = array();
			$JOIN = '';
			$AND = '';
			if( isset($drop_down_filter['informative']) && $drop_down_filter['informative'] != '' || isset($drop_down_filter['bias']) && $drop_down_filter['bias'] != '' ) {
				if( $drop_down_filter['informative'] && $drop_down_filter['bias'] ) {
					
					if( $drop_down_filter['informative'] && $drop_down_filter['informative'] != 'all' ) {
						//$JOIN .= " INNER JOIN ".$wpdb."postmeta AS wp_meta_1 ON wp_meta_1.post_id = WP_post.ID";
						$AND = " AND wp_meta_1.meta_key = 'vote_for_".$drop_down_filter['informative']."'";
					}
					
					if( $drop_down_filter['bias'] && $drop_down_filter['bias'] != 'all' ) {
						//$JOIN .= " INNER JOIN ".$wpdb."postmeta AS wp_meta_2 ON wp_meta_2.post_id = WP_post.ID";
						$AND .= " AND wp_meta_2.meta_key = 'vote_for_".$drop_down_filter['bias']."'";
					}
					
				} else if( $drop_down_filter['informative'] && $drop_down_filter['informative'] != 'all' ) {
					
					//$JOIN = " INNER JOIN ".$wpdb."postmeta AS wp_meta ON wp_meta.post_id = WP_post.ID";
					$AND = " AND wp_meta.meta_key = 'vote_for_".$drop_down_filter['informative']."'";
					
				} else if( $drop_down_filter['bias'] && $drop_down_filter['bias'] != 'all' ) {
					
					//$JOIN = " INNER JOIN ".$wpdb."postmeta AS wp_meta ON wp_meta.post_id = WP_post.ID";
					$AND = " AND wp_meta.meta_key = 'vote_for_".$drop_down_filter['bias']."'";
				}
			}
			
			$querystr = "SELECT DISTINCT WP_post.post_title, WP_post.ID, WP_post.guid, WP_post.post_name,WP_post.post_content, WP_post.post_date, WP_post.post_modified,WP_post.post_author
					FROM ".$wpdb."posts AS WP_post
					LEFT JOIN ".$wpdb."term_relationships AS WP_term_relation
						ON(WP_post.ID = WP_term_relation.object_id)
					LEFT JOIN ".$wpdb."term_taxonomy AS WP_term_tax
						ON(WP_term_relation.term_taxonomy_id = WP_term_tax.term_taxonomy_id)
					LEFT JOIN ".$wpdb."terms AS WP_term
						ON(WP_term_tax.term_id = WP_term.term_id)
					LEFT JOIN ".$wpdb."postmeta AS wp_meta 
						ON wp_meta.post_id = WP_post.ID
					WHERE WP_post.ID IN (";
					$count= count($user_voted_article);
					$i=0;
					foreach($user_voted_article as $article){
					if($i <= $count-2)
						$querystr .= "'".$article['article_id']."',"; 
					if($i == $count-1)
						$querystr .= "'".$article['article_id']."'";
						
						$i++;
					}
					$querystr.=")
					AND WP_post.post_status = 'publish'
					AND WP_post.post_type = 'post'
					$AND";
		}
		
		
		
		/*if($load == 'most_viewed'){	
			$total_post = $this->getAllRecord($querystr);
			if(is_array($total_post)){
				$total_page = ceil($total_post[0]['post_count']/$this->post_per_page());
			}
		}*/
		
		$group_by = '';
		if($load == 'most_viewed' || $term == 'most_viewed'){	
			$group_by  = "group by HA.post_id";
			$order_by = "count(HA.post_id)";
		}
		
		$querystr .="$group_by ORDER BY $order_by $order";
		
		
		$total_post = $this->getAllRecord($querystr);
		$total_page = ceil(count($total_post)/$this->post_per_page());
		
		$querystr .=" LIMIT $offset, $post_per_page";
		
		//echo $querystr;
		
		
		$post_obj =$this->getAllRecord($querystr);
		
		if( !isset($post_obj['text']) ) {
			
			$post_url = $this->getOneRecord("SELECT option_value FROM ".$wpdb."options WHERE `option_name` = 'siteurl'");
			
			if($user_access){
				$user_fav_date = $this->getOneRecord("SELECT meta_value FROM ".$wpdb."usermeta WHERE user_id = $user_id AND meta_key = 'wpfp_favorites_date'");
				
				$user_post_fav_date = unserialize($user_fav_date['meta_value']);
				$user_fav_arr = $this->getOneRecord("SELECT meta_value FROM ".$wpdb."usermeta WHERE user_id = $user_id AND meta_key = 'wpfp_favorites'");
				$user_fav_arr = unserialize($user_fav_arr['meta_value']);
			}
			if(is_array($post_obj)){
				foreach($post_obj as $post){
					$user_posted_fav_date = '';
					$favirote_icon = '';
					if( $user_access ) {
						
						
						if( isset( $user_post_fav_date[$post['ID']] ) ) {
							
							$date = new DateTime($user_post_fav_date[$post['ID']], new DateTimeZone('UTC'));
							$timeZone = $this->getTimeZone();
							$date->setTimezone(new DateTimeZone($timeZone ));
							$user_post_fav_date[$post['ID']] = $date->format('Y-m-d H:i:s');
							
							$user_posted_fav_date = '<p class="post_modified_'.$post['ID'].' source_publish_date_'.$post['ID'].' hide-767" style="display:none;">Added to Favorites on '.date('M d Y', strtotime($user_post_fav_date[$post['ID']])).' at '.date('g:i A', strtotime($user_post_fav_date[$post['ID']])).'</p>';
						}
						
						if(is_array($user_fav_arr)  && !in_array($post['ID'], $user_fav_arr) ) {
							$favirote_icon = '<span class="wpfp-span">
												<img src="'.$plugin_url.'/wp-favorite-posts/img/loading.gif" alt="Loading" title="Loading" class="wpfp-hide wpfp-img">
												<a class="wpfp-link add_to_favorite"  href="?wpfpaction=add&amp;postid='.$post['ID'].'" title="Add to favorites" rel="nofollow">Add to favorites</a>
												</span>';
						} else if(isset($user_fav_arr) ) {
							$favirote_icon = '<span class="wpfp-span">
												<img src="'.$plugin_url.'/wp-favorite-posts/img/loading.gif" alt="Loading" title="Loading" class="wpfp-hide wpfp-img">
												<a class="wpfp-link add_to_favorite" href="?wpfpaction=add&amp;postid='.$post['ID'].'" title="Add to favorites" rel="nofollow">Add to favorites</a>
												</span>';
						}
						if( is_array($user_fav_arr)  && in_array($post['ID'], $user_fav_arr) ) {							
							$favirote_icon = '<span class="wpfp-span">
											<img src="'.$plugin_url.'/wp-favorite-posts/img/loading.gif" alt="Loading" title="Loading" class="wpfp-hide wpfp-img">
											<a class="wpfp-link remove_from_favorite" href="?wpfpaction=remove&amp;postid='.$post['ID'].'" title="Remove from favorites" rel="nofollow">Remove from favorites</a>
											</span>';
						} 
						
					}
					
					$post_cat = $this->getAllRecord("
										SELECT WP_term.term_id, WP_term.name, WP_term.slug FROM ".$wpdb."term_relationships AS WP_term_relation
										LEFT JOIN ".$wpdb."term_taxonomy AS WP_term_tax
											ON(WP_term_relation.term_taxonomy_id = WP_term_tax.term_taxonomy_id)
										LEFT JOIN ".$wpdb."terms AS WP_term
											ON(WP_term_tax.term_id = WP_term.term_id)
										WHERE WP_term_relation.object_id=".$post['ID']."
										AND WP_term_tax.taxonomy = 'category'
										");
										
					$post_tag = $this->getAllRecord("
										SELECT WP_term.term_id, WP_term.name, WP_term.slug FROM ".$wpdb."term_relationships AS WP_term_relation
										LEFT JOIN ".$wpdb."term_taxonomy AS WP_term_tax
											ON(WP_term_relation.term_taxonomy_id = WP_term_tax.term_taxonomy_id)
										LEFT JOIN ".$wpdb."terms AS WP_term
											ON(WP_term_tax.term_id = WP_term.term_id)
										WHERE WP_term_relation.object_id=".$post['ID']."
										AND WP_term_tax.taxonomy = 'post_tag'
										");
										
					// Get user voted rating options
					$user_vote_arr = '';
					if($user_access == 1) {
						$user_vote_arr = $this->getAllRecord("
											SELECT HQO.ques_option											
											FROM ".$wpdb."hyroglf_users_voting AS HUV
											INNER JOIN ".$wpdb."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
											WHERE HUV.user_article_id = ".$post['ID']."
											AND HUV.user_id = ".$user_id
											);
					}
					
					$user_vote_content = '';
					if( $user_vote_arr ) {
						$user_vote_content = '<div class="user_vote_result_section source_publish_date_'.$post['ID'].'">';
							$post_arr = $this->getAllRecord("SELECT vote_utc_date,vote_date FROM ".$wpdb."hyroglf_users_voting_for_articles WHERE article_id = ".$post['ID']." AND user_id =".$user_id." ORDER BY voting_id DESC");
							if( isset($post_arr[0]['vote_utc_date']) ) {
								
								$user_vote_content .= '<div class="user_info_and_bias_rated_source_'.$post['ID'].'" style="display:none;">';
								$user_vote_content .= '<ul class="single_page_your_rating"><li><span>You rated as </span></li> ';
									$option =  '';
									$option_1 =  '';
									$option_2 =  '';
									if(is_array($user_vote_arr)){
										foreach( $user_vote_arr as $user_vote ) {
											$val = str_replace('_', ' ', $user_vote['ques_option']);
											//$val = implode('', array_map(strtolower, explode('_', $val)));
											if( $user_vote['ques_option'] == 'very' || $user_vote['ques_option'] == 'somewhat' || $user_vote['ques_option'] == 'not_really' ) {
												$option_1 = $val.' informative'; 
											} else {
												$option_2 = $val.' bias';
											}
										}
									}
									if( $option_1 ) {
										$user_vote_content .= '<li class="user_vote_rating user_info_rated_source_'.$post['ID'].'">'.$option_1.'</li>';
									}
									if( $option_1 && $option_2 ) {
										 $user_vote_content .= '<li class="user_vote_rating margin_space"> and </li>';
									}
									if( $option_2 ) {
										 $user_vote_content .= '<li class="user_vote_rating user_info_rated_source_'.$post['ID'].'">'.$option_2.'</li>';
									}
									
									$date = new DateTime($post_arr[0]['vote_utc_date'], new DateTimeZone('UTC'));
									$timeZone = $this->getTimeZone();
									$date->setTimezone(new DateTimeZone($timeZone ));
									
									$post_arr[0]['vote_utc_date'] = $date->format('Y-m-d H:i:s');
								//echo $post_arr[0]['vote_utc_date'];

								 $user_vote_content .= ' '.'on '. date('M d Y', strtotime($post_arr[0]['vote_utc_date'])).' at '.date('g:i A', strtotime($post_arr[0]['vote_utc_date'])).'</ul></div>';
							}
						 $user_vote_content .= '</div>';
						}
						
				
						
					// Get user voted rating options end
					
					// user_voted_date
					/*if( $user_id ) {
						$post_arr = $this->getOneRecord("SELECT vote_date FROM ".$wpdb."hyroglf_users_voting_for_articles WHERE article_id = ".$post['ID']." AND user_id =".$user_id);
						$user_voted_date = date( 'm/d/Y g:i A', strtotime( $post_arr['vote_date'] ) );
					}*/
					// user_voted_date ends
						
					$post_meta_arr = $this->getAllRecord("SELECT * from ".$wpdb."postmeta where post_id=".$post['ID']);
					
					if( isset( $post_cat[0]['term_id'] ) ) {
						$post_category_image = $this->getAllRecord("SELECT post.guid as image from ".$wpdb."options  as opt LEFT JOIN ".$wpdb."posts as post ON post.ID = opt.option_value where opt.option_name='category_".$post_cat[0]['term_id']."_post_category_image'");
					}
					
					$post_meta = array();
					if( is_array($post_meta_arr) ){
						foreach($post_meta_arr as $meta_arr){
		
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'refernce_link_home_page_title' ) {
								$post_meta['refernce_link_home_page_title'] = $meta_arr['meta_value'];
							}
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'post_url' ) {
								$post_meta['post_url'] = $meta_arr['meta_value'];
							}
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'publish_date_news' ) {
								//$post_meta['publish_date_news'] = date( 'M d Y g:i A', strtotime($meta_arr['meta_value']));
								
								if($meta_arr['meta_value'] != '')
									//$post_meta['publish_date_news'] = date( 'M d Y g:i A', strtotime($meta_arr['meta_value']));
									$post_meta['publish_date_news'] = date( 'M d Y', strtotime($meta_arr['meta_value']));
							}
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'post_read_time' ) {
								$str_time = unserialize($meta_arr['meta_value']);
								foreach(unserialize($str_time) as $key => $value ) {
									$post_meta['post_read_sec'] = $key;
									$post_meta['post_read_time'] = $value;
								}
							}
							
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'reference_link' ) {
								$post_meta['reference_link'] = $meta_arr['meta_value'];
							}
							
							if(empty($post_meta['reference_link'])){
								$post_meta['reference_link'] = $post_url['option_value'].'/'.strtolower(str_replace(' ', '_', $post['post_title']));
							}
							
							$fav_icon_link = '#';
							$fav_image = '';
							
				
							/*if( isset($meta_arr['meta_key']) && ($meta_arr['meta_key'] == 'post_ref_link_favicon' ) && $post_cat[0]['slug'] != 'other-info') {
								
								
								if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'reference_link' ) {
									$fav_icon_link = $meta_arr['meta_value'];
								}
								$fav_image = $meta_arr['meta_value'];
								$post_meta['post_ref_link_favicon'] = '<a href="'.$post_meta['reference_link'].'" target="_blank" class="post_fav_icons"><img src="'.$meta_arr['meta_value'].'" width="50" height="50"></a>';		
							} */
							
							if(!empty($post_category_image[0]) && is_array($post_category_image[0]) &&  empty($fav_image) && isset( $post_cat[0]['slug'] ) && $post_cat[0]['slug'] != 'other-info' ) {
								
								$event = "''";
								$tax = "'category'";
								$cat_item = "'".$post_cat[0]['slug']."'";
								$cat_name = "'".$post_cat[0]['name']."'";
								$cat_image = "'".$post_category_image[0]['image']."'";
								$page_of = "'index'";
								
								$post_category_image[0]['image'] = str_replace('http://','https://',$post_category_image[0]['image']);
								
								$post_meta['post_ref_link_favicon'] = '<a href="javascript:void(0);" class="post_fav_icons" onclick ="cat_post_filter_click( '.$event.', '.$tax.', '.$cat_item.', '.$cat_name.', '.$cat_image.', '.$page_of.' );"><img src="'.$post_category_image[0]['image'].'" width="50" height="50"></a>';
								
							}
							if(is_array($post_cat)){
								foreach($post_cat as $cat){
									if(isset($cat['slug']) && $cat['slug'] == 'other-info'){
										$event = "''";
										$tax = "'category'";
										$cat_item = "'".$cat['slug']."'";
										$cat_name = "'".$cat['name']."'";
										$base_url = str_replace('http://','https://',$base_url);
										$cat_image = "'".$base_url."/assets/images/GLF-Favicon.png"."'";
										$page_of = "'index'";
										$post_meta['post_ref_link_favicon'] = '<a href="javascript:void(0);" onclick ="cat_post_filter_click( '.$event.', '.$tax.', '.$cat_item.', '.$cat_name.', '.$cat_image.', '.$page_of.' );" class="post_fav_icons"><img src="'.$base_url.'/assets/images/GLF-Favicon.png" width="50" height="50"></a>';
									
									}
								}
							}
							
							/*if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'glf_date_update_utc' ) {
								$post_meta['glf_update'] = $meta_arr['meta_value'];
							}*/
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'post_multi_images' ) {
								$post_meta['post_multi_images'] = 1;
							}
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'post_video' ) {
								$post_meta['post_video'] = 1;
							} 
														
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'source_name' ) {
								$post_meta['source_name'] = ( !empty( $meta_arr['meta_value'] ) ) ? htmlspecialchars_decode($meta_arr['meta_value']) : 'Source';
							} 
							
							//if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'last_edited_user' && $meta_arr['meta_value'] != '' ) {
								//$last_edited_user = $meta_arr['meta_value'];
								//if( $last_edited_user ) {
									$glf_update_system  = '';
									//echo $meta_arr['meta_key'];
									if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'glf_date_update_utc' ) {
										
										$date = new DateTime($meta_arr['meta_value'], new DateTimeZone('UTC'));
										$timeZone = $this->getTimeZone();
										$date->setTimezone(new DateTimeZone($timeZone ));
										$meta_arr['meta_value'] = $date->format('Y-m-d H:i:s');
										
										$post_meta['last_edited_user_system'] = date( 'g:i A', strtotime( $meta_arr['meta_value'] ));
									}
									
									if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'glf_date_update_utc' ) {
										$explode_am = explode("AM",$meta_arr['meta_value'].'aa');
										if(isset( $explode_am[1]) && is_array($explode_am) ){
											$user_post_edit = str_replace(" AM",":00",$meta_arr['meta_value']);
										}else{
											$user_post_edit = str_replace(" PM",":00",$meta_arr['meta_value']);
										}
										$user_post_edit= str_replace(" : ",":",$user_post_edit);
										$glf_date = date( 'Y-m-d H:i:s' ,strtotime($user_post_edit));
										
										$glf_date = new DateTime($glf_date, new DateTimeZone('UTC'));
										$timeZone = $this->getTimeZone();
										$date->setTimezone(new DateTimeZone($timeZone ));
										$glf_date = $date->format('Y-m-d H:i:s');
										$time = strtotime($glf_date);
										if($time > strtotime("-1 day") ){
											//$user_login = "'".$last_edited_user."'";
											/*$post_meta['last_edited_user'] = 'GLF Updated - '.date( 'm/d/Y', strtotime( $mod_date['post_modified'] ) ).' at '. date( 'g:i A', strtotime( $mod_date['post_modified'] ) ).' by <a href="javascript:void(0);" data_value="" onclick="post_filter_by_author('.$user_login.');">'.$last_edited_user.'</a>';*/
											$glf_date = str_replace( '/', '-',$glf_date);
											$post_meta['last_edited_user'] =   $this->humanTiming($glf_date);
											$post_meta['last_edited_user_local'] = date( 'g:i A', strtotime( $glf_date ) );
											
										} else if($time < strtotime("-1 day")  && $time > strtotime("-7 day") ){
											$glf_date = str_replace( '/', '-',$glf_date);
											$post_meta['last_edited_user'] =  date( 'D' ,strtotime($glf_date));
											$post_meta['last_edited_user_local'] =date( 'g:i A', strtotime( $glf_date ) );
										} else{
											$glf_date = str_replace( '/', '-',$glf_date);
											$post_meta['last_edited_user'] =  date( 'M d Y' ,strtotime($glf_date));
											$post_meta['last_edited_user_local'] =date( 'g:i A', strtotime( $glf_date ) );
										}
										}
								//}
							/*}else{
								$author_name = $this->getOneRecord("SELECT user_login FROM ".$wpdb."users WHERE ID = ".$post['post_author']);
								$author = "'".$author_name['user_login']."'";
								if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'glf_update' ) {
									//echo $meta_arr['meta_value'];
								$explode_am = explode("AM",$meta_arr['meta_value'].'aa');
									if(isset( $explode_am[1]) && is_array($explode_am)){
										$user_post_edit = str_replace(" AM",":00",$meta_arr['meta_value']);
									}else{
										$user_post_edit = str_replace(" PM",":00",$meta_arr['meta_value']);
									}
								$user_post_edit= str_replace(" : ",":",$user_post_edit);
								$glf_date = date( 'Y-m-d H:i:s' ,strtotime($user_post_edit));
								$time = strtotime($glf_date);
								$post_meta['last_edited_user'] =  $this->humanTiming($time).' ago'.' at '.date( 'g:i A', strtotime( $user_post_edit ) );
								
							}
							}*/
							
							
							/*if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'glf_date_update_utc' && $meta_arr['meta_value'] != '') {							
								$explode_am = explode("AM",$meta_arr['meta_value'].'aa');
								if(isset( $explode_am[1]) && is_array($explode_am)){
									$user_post_edit = str_replace(" AM",":00",$meta_arr['meta_value']);
								}else{
									$user_post_edit = str_replace(" PM",":00",$meta_arr['meta_value']);
								}
								$user_post_edit= str_replace(" : ",":",$user_post_edit);
								$glf_date = date( 'Y-m-d H:i:s' ,strtotime($user_post_edit));
								
								$date = new DateTime($glf_date, new DateTimeZone('UTC'));
								$timeZone = $this->getTimeZone();	
								$date->setTimezone(new DateTimeZone($timeZone));
								$glf_date = $date->format('Y-m-d H:i:s');
								
								$time = strtotime($glf_date);
								if($time > strtotime("-1 day")  ) {
									$glf_date = str_replace( '/', '-',$glf_date);
									$post_meta['last_edited_user'] =   $this->humanTiming($glf_date);
									$post_meta['last_edited_user_local'] =date( 'g:i A', strtotime( $glf_date ) );
								} else if( $time < strtotime("-1 day")  && $time > strtotime("-7 day") ) {
									$post_meta['last_edited_user'] =  date( 'D' ,strtotime($glf_date));
									$post_meta['last_edited_user_local'] = date( 'g:i A', strtotime( $glf_date ) );
								} else {
									$post_meta['last_edited_user'] =  date('M d Y',strtotime($glf_date));
									$post_meta['last_edited_user_local'] =date( 'g:i A', strtotime( $glf_date ) );
								}
							}*/
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'last_utc_edited_user' && $meta_arr['meta_value'] != '') {
								$last_edited_user = $meta_arr['meta_value'];
								$user_login = "'".$last_edited_user."'";
									$post_meta['edited_user_update'] = 'updated by <a href="javascript:void(0);" data_value="" onclick="post_filter_by_author('.$user_login.');">'.$last_edited_user.'</a>';
							} else{
								$author_name = $this->getOneRecord("SELECT user_login FROM ".$wpdb."users WHERE ID = ".$post['post_author']);
								$author = "'".$author_name['user_login']."'";
								if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'last_utc_edited_user' ) {
									$post_meta['edited_user_update'] = 'updated by <a href="javascript:void(0);" data_value="" onclick="post_filter_by_author('.$author.');">'.$author_name['user_login'].'</a>';
								}
							}
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'last_utc_edited_user' && $meta_arr['meta_value'] != '') {
								
								$last_edited_user = $meta_arr['meta_value'];
								$user_login = "'".$last_edited_user."'";
								$post_filter_by_author = "'post_filter_by_author'";
								$cat_post = "'cat_post'";
									$post_meta['edited_user_profile'] = 'updated by <a href="javascript:void(0);" data_value="" onclick="inner_page_cat_filter('.$user_login.','.$post_filter_by_author.','.$cat_post.');">'.$last_edited_user.'</a>';
							} else{
								$author_name = $this->getOneRecord("SELECT user_login FROM ".$wpdb."users WHERE ID = ".$post['post_author']);
								$author = "'".$author_name['user_login']."'";
								$post_filter_by_author = "'post_filter_by_author'";
								$cat_post = "'cat_post'";
								if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] == 'glf_date_update_utc' ) {
									$post_meta['edited_user_profile'] = 'updated by <a href="javascript:void(0);" data_value="" onclick="inner_page_cat_filter('.$author.','.$post_filter_by_author.','.$cat_post.');">'.$author_name['user_login'].'</a>';
								}
							}
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] ==  'user_post_edit_system_'.$user_id ) {
								
								$date = new DateTime($meta_arr['meta_value'], new DateTimeZone('UTC'));
								$timeZone = $this->getTimeZone();
								$date->setTimezone(new DateTimeZone($timeZone ));
								$meta_arr['meta_value'] = $date->format('Y-m-d H:i:s');
								
								$post_meta['user_post_edit_system'] = date('g:i A',strtotime($meta_arr['meta_value']));
							}else if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] ==  'user_post_create_system_'.$user_id ) {
								$post_meta['user_post_create_system'] = date('g:i A',strtotime($meta_arr['meta_value']));
							}
							
							if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] ==  'user_post_edit_'.$user_id ) {
								$user_post_edit = $meta_arr['meta_value'];
								$timeZone = '';
								$date = new DateTime($user_post_edit, new DateTimeZone('UTC'));
								$timeZone = $this->getTimeZone();
								$date->setTimezone(new DateTimeZone($timeZone ));
								$user_post_edit = $date->format('Y-m-d H:i:s');
								
								if( isset($user_post_edit )) {
									$explode_am = explode("AM",$user_post_edit.'aa');
									if(isset($explode_am[1])){
										$user_post_edit = str_replace(" AM",":00",$user_post_edit);
									}else{
										$user_post_edit = str_replace(" PM",":00",$user_post_edit);
									}
									$user_post_edit= str_replace(" : ",":",$user_post_edit);
									
									$date = new DateTime($user_post_edit, new DateTimeZone('UTC'));
									$timeZone = $this->getTimeZone();
									$date->setTimezone(new DateTimeZone($timeZone ));
									$user_post_edit = $date->format('Y-m-d H:i:s');
									
								$post_meta['your_post_mod'] = 'You edited on '.date('M d Y',strtotime($user_post_edit));
								$post_meta['your_post_mod_local'] = date('g:i A',strtotime($user_post_edit));
								}
							} else if( isset($meta_arr['meta_key']) && $meta_arr['meta_key'] ==  'user_post_create_'.$user_id ){
								$user_post_create = $meta_arr['meta_value'];
								$date = new DateTime($user_post_create, new DateTimeZone('UTC'));
								$timeZone = $this->getTimeZone();
								$date->setTimezone(new DateTimeZone($timeZone ));
								$user_post_create = $date->format('Y-m-d H:i:s');
									
								if( isset($user_post_create )) {
									$explode_am = explode("AM",$user_post_create.'aa');
									if(isset($explode_am[1])){
										$user_post_create = str_replace(" AM",":00",$user_post_create);
									}else{
										$user_post_create = str_replace(" PM",":00",$user_post_create);
									}
									$user_post_create= str_replace(" : ",":",$user_post_create);
									
									$date = new DateTime($user_post_create, new DateTimeZone('UTC'));
									$timeZone = $this->getTimeZone();
									$date->setTimezone(new DateTimeZone($timeZone ));
									$user_post_create = $date->format('Y-m-d H:i:s');
									
									$post_meta['your_post_mod'] = 'You added on '.date('M d Y',strtotime($user_post_create)); 
									$post_meta['your_post_mod_local'] = date('g:i A',strtotime($user_post_create));
								}
								
							}
						}
					}
					
					if($user_id){
										
						$count_user_infor = $this->getOneRecord("SELECT count(user_id) AS user_count FROM ".$wpdb."hyroglf_users_voting WHERE user_article_id = ".$post['ID']." AND user_id = ".$user_id." AND question_id = 1");
						if($count_user_infor['user_count'] >= 1){
							$user_voting_hide_infor=1;
						} else {
							$user_voting_hide_infor=0;
						}
					
						
						$count_user_bias = $this->getOneRecord("SELECT count(user_id) AS user_count FROM ".$wpdb."hyroglf_users_voting WHERE user_article_id = ".$post['ID']." AND user_id = ".$user_id." AND question_id = 2");
						if($count_user_bias['user_count'] >= 1){
							$user_voting_hide_bias=1;
						} else {
							$user_voting_hide_bias=0;
						}
					}
					
					
					$vote_arrays = $this->get_post_rating_options( $wpdb, $post['ID'], $user_id );
					
					$edit_link = $post_url['option_value'].'/my-posts/?task=edit&postid='.$post['ID'];
					$count_flag_report_for_warning ='';	
					// Flag inappropriate
					if( $user_ip && $post['ID'] ) {
						
						// user ip count to remove flag button for particular post
						$count_flag_report = $this->getOneRecord("SELECT count(user_ip_address) as count FROM ".$wpdb."hyroglf_post_revision_restore WHERE article_id=".$post['ID']." and user_ip_address='".$user_ip."'");
						
						$count_flag_report_for_warning = $this->getOneRecord("SELECT count(user_ip_address) as count FROM ".$wpdb."hyroglf_post_revision_restore WHERE article_id=".$post['ID']."");	
									
						
					}
					$post_status = $this->getOneRecord("SELECT post_status FROM ".$wpdb."posts where ID= ".$post['ID']."" );
					$post_flag_inappropriate = '';
					if( isset($count_flag_report['count']) && $count_flag_report['count'] == 0 && $post_status['post_status'] != 'draft' ) {
						$post_flag_inappropriate = 0;
					} else {
						$post_flag_inappropriate = 1;	
					}
				
					$post_flag_inappropriate_warning = '';
					if( isset($count_flag_report_for_warning['count']) && $count_flag_report_for_warning['count'] > 0 ) {
						$post_flag_inappropriate_warning = 0;
					} else {
						$post_flag_inappropriate_warning = 1;
					}
					// Flag inappropriate end
				
					// Flag advertisement
					if( $user_ip && $post['ID'] ) {
						// user ip count to remove flag button for particular post
						$count_flag_report = $this->getOneRecord("SELECT count(user_ip_address) as count FROM ".$wpdb."hyroglf_advertisement_post_revision_restore WHERE article_id= ".$post['ID']." AND user_ip_address='".$user_ip."'");
					}
					
					$post_status = $this->getOneRecord("SELECT post_status FROM ".$wpdb."posts where ID= ".$post['ID']."" );
					$post_flag_as_advertisement = '';
					if( isset($count_flag_report['count']) && $count_flag_report['count'] == 0 && $post_status['post_status'] != 'draft' ) { 
						$post_flag_as_advertisement = 0;
					} else {
						$post_flag_as_advertisement = 1;
					}
					// Flag advertisement end
					$most_viewed_count ="";
					if(isset($post['mv_post_count'])){
						$most_viewed_count = $post['mv_post_count'];
					}					
					
					$encode_post_title = str_replace('%96','-',str_replace('%92',"'",urlencode($post['post_title'])));
					$encode_post_title = urldecode($encode_post_title);	
					
					$post_data[] = array(
						'post_found'		=> 1,
						'mv_post_count'        => $most_viewed_count,
						'post_id' 			=> $post['ID'],
						'post_author' 		=> $post['post_author'],
						'post_title'		=> htmlspecialchars_decode($encode_post_title),
						'favirote_icon'		=> $favirote_icon,
						'user_posted_fav_date'	=> $user_posted_fav_date,
						'post_link'			=> $post['guid'],
						'post_edit_link'	=> ( $edit_access ) ? $edit_link : $site_url.'/login/',
						'post_content'		=> $post['post_content'],
						'post_modified'		=> $post['post_modified'],
						'post_date'			=> date('m/d/Y', strtotime($post['post_date'])),
						'post_cat'			=> $post_cat,
						'post_tag'			=> $post_tag,
						'post_meta'			=> $post_meta,
						'informative_rating_count' 		=> $vote_arrays['post_rating_options_informative_count'],
						'bias_rating_count' 			=> $vote_arrays['post_rating_options_bias_count'],
						'informative_high_vote'			=> $vote_arrays['informative_high_vote'],
						'bias_high_vote'				=> $vote_arrays['bias_high_vote'],
						'voting_post_user_count_infor'  => $user_voting_hide_infor,
						'voting_post_user_count_bias'   => $user_voting_hide_bias,
						'user_vote_content'				=> $user_vote_content,
						'flag_inappropriate_count'		=> $post_flag_inappropriate,
						'post_flag_inappropriate_warning'	=> $post_flag_inappropriate_warning,
						'flag_as_advertisement_count' 		=> $post_flag_as_advertisement,
						//'user_voted_date' 				=> $user_voted_date
						);
				}
			}
		} else {
			//$post_obj['post_found'	] = 0;
			$post_data = $post_obj;
		}
		
		return array('post_data' => $post_data, 'total_page'=> $total_page);
		
	}
	
	/**
     * Fetching post rating values
     */	
	public function get_post_rating_options( $wpdb = '', $post_id = '', $user_id = '' ) {
		$return = array();
		
		$option_voting_post = array();
		$options_arr = array();
		
		$informative_arr = array();
		$bias_arr = array();
		$informative_high_vote = array();
		$bias_high_vote = array();
		$user_vote_arr = array();
		
		//get option count for voting 
		 $option_id_count = $this->getAllRecord("SELECT option_id FROM ".$wpdb."hyroglf_question_option");
		 
		 if(is_array($option_id_count)){
			 foreach($option_id_count as $option) {
				 
				$option_id_array = $option['option_id'];
				
				$option_voting_post[] = $this->getAllRecord("SELECT count(ques_option_id) as count_id FROM ".$wpdb."hyroglf_users_voting WHERE ques_option_id=".$option['option_id']." AND user_article_id=".$post_id);
				
				$options_arr[] = $this->getAllRecord("SELECT
										count(HUV.ques_option_id) as count_id,
										HUV.question_id,
										HQ.questions,
										HUV.ques_option_id,
										HQO.ques_option
										FROM ".$wpdb."hyroglf_users_voting AS HUV
										INNER join ".$wpdb."hyroglf_questions AS HQ ON HUV.question_id = HQ.question_id
										INNER JOIN ".$wpdb."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
										WHERE HUV.ques_option_id='$option_id_array' AND HUV.user_article_id='$post_id'");
			 }
			 
		 }
	 
		$infermative_arr = array();
		$bias_arr = array();
		if(is_array($options_arr)){
		foreach( $options_arr as $options ) {
			if( !empty( $options ) ) {
				foreach( $options as $option ) {
					if( $option['questions'] == 'Informative/Understandable?' ) {
						$infermative_arr[] = array(
												"count" 			=> $option['count_id'],
												"question_id"		=> $option['question_id'],
												"questions" 		=> $option['questions'],
												"ques_option_id" 	=> $option['ques_option_id'],
												"ques_option" 		=> $option['ques_option'],
												);
												
					} elseif( $option['questions'] == 'Bias?' ) {
						$bias_arr[] = array(
												"count" 			=> $option['count_id'],
												"question_id"		=> $option['question_id'],
												"questions" 		=> $option['questions'],
												"ques_option_id" 	=> $option['ques_option_id'],
												"ques_option" 		=> $option['ques_option'],
												);
					}
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
	
		if( $post_id && $user_id ){
			
			 $user_id = $user_id;
			 $count_user = $this->getOneRecord("
									SELECT count(user_id) AS user_count
									FROM ".$wpdb."hyroglf_users_voting_for_articles
									WHERE article_id = ".$post_id." AND user_id = ".$user_id."
									");
									
			$user_option_infermative = $this->getOneRecord("
									SELECT *
									FROM ".$wpdb."hyroglf_questions AS HQ
									INNER JOIN ".$wpdb."hyroglf_question_option AS HQO ON HQ.question_id = HQO.question_id_fk
									INNER JOIN ".$wpdb."hyroglf_users_voting AS HUV ON HQO.option_id = HUV.ques_option_id
									WHERE HQ.questions = 'Informative/Understandable?'
									AND HUV.user_id = ".$user_id."
									AND HUV.user_article_id = ".$post_id
									);
									
			$infermative_count = '';
			if( !empty( $user_option_infermative ) ) {
				$infermative_count = $user_option_infermative;
			}
									
			$user_option_bias = $this->getOneRecord("
									SELECT *
									FROM ".$wpdb."hyroglf_questions AS HQ
									INNER JOIN ".$wpdb."hyroglf_question_option AS HQO ON HQ.question_id = HQO.question_id_fk
									INNER JOIN ".$wpdb."hyroglf_users_voting AS HUV ON HQO.option_id = HUV.ques_option_id
									WHERE HQ.questions = 'Bias?'
									AND HUV.user_id = ".$user_id."
									AND HUV.user_article_id = ".$post_id
									);
			
			$bias_count = '';
			if( !empty( $user_option_bias ) ) {
				$bias_count = $user_option_bias;
			}
			
		 }
		 
		 //print_r($option_voting_post);
	 
	 
		 if( $option_voting_post[0][0]['count_id'] || $option_voting_post[1][0]['count_id'] || $option_voting_post[2][0]['count_id'] || $option_voting_post[3][0]['count_id'] || $option_voting_post[4][0]['count_id'] || $option_voting_post[5][0]['count_id'] ) {
			 
			 if(!empty( $infermative_arr ) && is_array( $infermative_arr )) {
				 
				 //$informative_high_vote = array();
				if($infermative_arr && $infermative_arr[0]['count']){
					if($infermative_arr[0]['ques_option']){
						//$informative_high_vote[] = ucfirst( str_replace('_', ' ', $infermative_arr[0]['ques_option'] ) );
						$informative_high_vote[] = str_replace('_', ' ', $infermative_arr[0]['ques_option'] );
					}
				}
				if(isset( $infermative_arr[1] ) && $infermative_arr[1]['count'] == $infermative_arr[0]['count']){
					if($infermative_arr[1]['ques_option']){
						//$informative_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $infermative_arr[1]['ques_option'] ) );
						$informative_high_vote[] = '/'.str_replace('_', ' ', $infermative_arr[1]['ques_option'] );
					}
				}
				if(isset( $infermative_arr[2] ) && $infermative_arr[2]['count'] == $infermative_arr[0]['count'] && $infermative_arr[2]['count'] == $infermative_arr[1]['count']){
					if($infermative_arr[2]['ques_option']){
						//$informative_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $infermative_arr[2]['ques_option'] ) );
						$informative_high_vote[] = '/'.str_replace('_', ' ', $infermative_arr[2]['ques_option'] );
					}
				}
				 
				$total = ( ( $option_voting_post[0][0]['count_id'] ) + ( $option_voting_post[1][0]['count_id'] ) + ( $option_voting_post[2][0]['count_id'] ) );
				$very = '';
				$somewhat = '';
				$not_really = '';
				
				$count_very = '';
				$count_somewhat = '';
				$count_not_really = '';
				if( $option_voting_post[0][0] ) {
					$count = $option_voting_post[0][0]['count_id']/$total * 100;
					$count_very = round( $count ).'%';
					if(isset($option_voting_post[0][0]['count_id'])  && $option_voting_post[0][0]['count_id'] > 1 ){
						$very = $option_voting_post[0][0]['count_id'].' votes';
					} else {
						$very = $option_voting_post[0][0]['count_id'].' vote';
					}
					//$very = '('. ( isset($option_voting_post[0][0]['count_id'])  && $option_voting_post[0][0]['count_id'] > 1 ) ? $option_voting_post[0][0]['count_id'].' votes' : $option_voting_post[0][0]['count_id'].' vote )';
				}
				
				 if( $option_voting_post[1][0] ) {
						$count = $option_voting_post[1][0]['count_id']/$total * 100;
						$count_somewhat = round( $count ).'%';
						if(isset($option_voting_post[1][0]['count_id'])  && $option_voting_post[1][0]['count_id'] > 1 ){
							$somewhat = $option_voting_post[1][0]['count_id'].' votes';
						} else {
							$somewhat = $option_voting_post[1][0]['count_id'].' vote';
						}
						//$somewhat = '('.( $option_voting_post[1][0]['count_id'] > 1 ) ? $option_voting_post[1][0]['count_id'].' votes' : $option_voting_post[1][0]['count_id'].' vote )';
				 }
				 
				 if( $option_voting_post[2][0] ) { 
						$count = $option_voting_post[2][0]['count_id']/$total * 100;
						$count_not_really = round( $count ).'%';
						if(isset($option_voting_post[2][0]['count_id'])  && $option_voting_post[2][0]['count_id'] > 1 ){
							$not_really = $option_voting_post[2][0]['count_id'].' votes';
						} else {
							$not_really = $option_voting_post[2][0]['count_id'].' vote';
						}
						//$not_really = '('.( $option_voting_post[2][0]['count_id'] > 1 ) ? $option_voting_post[2][0]['count_id'].' votes' : $option_voting_post[2][0]['count_id'].' vote )';
				 }
				
				$informative_arr = array(
									'very'  		=> array('count'=> $count_very, 'vote' => $very),
									'somewhat'  	=> array('count'=> $count_somewhat, 'vote' => $somewhat),
									'not_really'  	=> array('count'=> $count_not_really, 'vote' => $not_really),
								);
			 }
			//print_r($bias_arr);
			//$bias_high_vote = array();
			if($bias_arr && $bias_arr[0]['count'] ){
				if($bias_arr[0]['ques_option']){
					//$bias_high_vote[] = ucfirst( str_replace('_', ' ', $bias_arr[0]['ques_option'] ) );
					$bias_high_vote[] = str_replace('_', ' ', $bias_arr[0]['ques_option'] );
				}
			}
			if( isset( $bias_arr[1] ) && $bias_arr[1]['count'] == $bias_arr[0]['count']){
				if($bias_arr[1]['ques_option']){
					//$bias_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $bias_arr[1]['ques_option'] ) );
					$bias_high_vote[] = '/'.str_replace('_', ' ', $bias_arr[1]['ques_option'] );
				}
			}
			if( isset( $bias_arr[2] ) && $bias_arr[2]['count'] == $bias_arr[0]['count'] && $bias_arr[2]['count'] == $bias_arr[1]['count']){
				if($bias_arr[2]['ques_option']){
					//$bias_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $bias_arr[2]['ques_option'] ) );
					$bias_high_vote[] = '/'.str_replace('_', ' ', $bias_arr[2]['ques_option'] );
				}
			}
			 
			if(!empty( $bias_arr ) && is_array( $bias_arr )) {
				 
				$liberal = '';
				$neutral = '';
				$conservative = '';
				
				$count_liberal = '';
				$count_neutral = '';
				$count_conservative = '';
				$total = ( ( $option_voting_post[3][0]['count_id'] ) + ( $option_voting_post[4][0]['count_id'] ) + ( $option_voting_post[5][0]['count_id'] ) );
				if( $option_voting_post[3][0] ) {
					$count = $option_voting_post[3][0]['count_id']/$total * 100;
					$count_liberal = round( $count ).'%';
					if(isset($option_voting_post[3][0]['count_id'])  && $option_voting_post[3][0]['count_id'] > 1 ){
						$liberal = $option_voting_post[3][0]['count_id'].' votes';
					} else {
						$liberal = $option_voting_post[3][0]['count_id'].' vote';
					}
					//$liberal = '('.( $option_voting_post[3][0]['count_id'] > 1 ) ? $option_voting_post[3][0]['count_id'].' votes' : $option_voting_post[3][0]['count_id'].' vote )';
				 }
				 
				 if( $option_voting_post[4][0] ) {
					$count = $option_voting_post[4][0]['count_id']/$total * 100;
					$count_neutral = round( $count ).'%';
					if(isset($option_voting_post[4][0]['count_id'])  && $option_voting_post[4][0]['count_id'] > 1 ){
						$neutral = $option_voting_post[4][0]['count_id'].' votes';
					} else {
						$neutral = $option_voting_post[4][0]['count_id'].' vote';
					}
					//$neutral = '('.( $option_voting_post[4][0]['count_id'] > 1 ) ? $option_voting_post[4][0]['count_id'].' votes' : $option_voting_post[4][0]['count_id'].' vote )';
				}
				
				if( $option_voting_post[5][0] ) {
					$count = $option_voting_post[5][0]['count_id']/$total * 100;
					$count_conservative = round( $count ).'%';
					if(isset($option_voting_post[5][0]['count_id'])  && $option_voting_post[5][0]['count_id'] > 1 ){
						$conservative = $option_voting_post[5][0]['count_id'].' votes';
					} else {
						$conservative = $option_voting_post[5][0]['count_id'].' vote';
					}
					//$conservative = '('.( $option_voting_post[5][0]['count_id'] > 1 ) ? $option_voting_post[5][0]['count_id'].' votes' : $option_voting_post[5][0]['count_id'].' vote )';
				}
				
				$bias_arr = array(
					'liberal'  		=> array('count'=> $count_liberal, 'vote' => $liberal),
					'neutral'  		=> array('count'=> $count_neutral, 'vote' => $neutral),
					'conservative'  => array('count'=> $count_conservative, 'vote' => $conservative),
				);
				 
			 }
			 
		 }
		 
		 $user_vote_arr = $this->getAllRecord("
											SELECT HQO.ques_option											
											FROM ".$wpdb."hyroglf_users_voting AS HUV
											INNER JOIN ".$wpdb."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
											WHERE HUV.user_article_id = '$post_id'
											AND HUV.user_id = '$user_id'
										");
		 
		 $return = array(
					'post_rating_options_informative_count' => $informative_arr,
					'post_rating_options_bias_count' 		=> $bias_arr,
					'informative_high_vote'					=> $informative_high_vote,
					'bias_high_vote'						=> $bias_high_vote,
					'user_vote_arr'							=> $user_vote_arr
				);
				
		
		 
		return $return;
		 
	}
	
	
    /**
     * Fetching single record
     */	
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }
	
    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
		if(is_array($column_names)){
        	foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
           } else {
                $$desired_key = $c[$desired_key];
           }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
		}
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }
	public function deleteTable($data, $table_name='',$field_name='', $join_table='',$join_field_name1='',$join_field_name2=''){
		
		if(!empty($join_table)){
			$query="DELETE a,b FROM ".$table_name." AS a LEFT JOIN $join_table AS b ON b.$join_field_name1 = a.$join_field_name2 WHERE a.".$field_name." = ".$data." AND b.".$field_name." = ".$data."";
		}
		else{
			$query = "DELETE FROM ".$table_name." WHERE ".$field_name." = ".$data."";
		}
		$r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		return $r;
	}
	
	public function updateData($query){
		$r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		return $r;
	}
	
	public function getSession(){
		if (!isset($_SESSION)) {
			session_start();
		}
		$sess = array();
		if(isset($_SESSION['uid']))
		{
			$sess["uid"] = $_SESSION['uid'];
			$sess["name"] = $_SESSION['name'];
			$sess["email"] = $_SESSION['email'];
			$sess["role"] = $_SESSION['role'];
		} else {
			$sess["uid"] = '';
			$sess["name"] = 'Guest';
			$sess["email"] = '';
			$sess["role"] = '';
		}
		return $sess;
	}

	public function destroySession(){
		if (!isset($_SESSION)) {
		session_start();
		}
		if(isSet($_SESSION['uid'])) {
			unset($_SESSION['uid']);
			unset($_SESSION['name']);
			unset($_SESSION['email']);
			unset($_SESSION['role']);
			$info='info';
			if(isSet($_COOKIE[$info])) {
				setcookie ($info, '', time() - $cookie_time);
			}
			$msg="Logged Out Successfully...";
		} else {
			$msg="Not logged in...";
		}
		return $msg;
	}
 
}
?>