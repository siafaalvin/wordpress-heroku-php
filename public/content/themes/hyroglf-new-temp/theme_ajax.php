<?php
add_action( 'wp_ajax_delete_user_account', 'delete_user_account' );
add_action( 'wp_ajax_nopriv_delete_user_account', 'delete_user_account' );
function delete_user_account(){
	 if(isset($_POST['delete_check_password'])){
		 $current_user = wp_get_current_user();
		 $uname  = $current_user->user_login;
		 $password = $_POST['delete_check_password'];
		 $user_data = get_user_by( 'login', $uname );
			if ( $user_data && wp_check_password( $password, $user_data->data->user_pass, $user_data->ID ) ) {
				$result = update_user_meta( $user_data->ID, 'user_account_status', 'inactive' );
				if( $result ) {
					$delete_output = array('message' => "success", 'output' => '?DeleteMyAccount=delete_account');
					session_start();
					$_SESSION['delete_account_message'] = 'Your account has been deleted! We have kept your username for you so feel free to login again if you want to reactivate it.';
				}
			} else {
				$delete_output = array('message' => "nope", 'output' => '');
			}
		 }
		 echo json_encode($delete_output);
		 wp_die();
}

add_action( 'wp_ajax_get_profile_cat_image', 'get_profile_cat_image' );
add_action( 'wp_ajax_nopriv_get_profile_cat_image', 'get_profile_cat_image' );
function get_profile_cat_image(){
	 if(isset($_POST['term'])){
		 
		 $term = $_POST['term'];
		 $idObj = get_category_by_slug($term); 
 		 $id = $idObj->term_id;
		 
		 $post_category_image = '';
		// print_r($post_category_image_arr);
		 if(get_field( "post_category_image" ,$idObj )) {
			$post_category_image_arr = get_field( "post_category_image" , $idObj );
			$post_category_image = $post_category_image_arr['url']; 
		 	echo $post_category_image;
		 }
		 wp_die();
	 }
}

add_action( 'wp_ajax_get_post_and_refer_link_title_duplicate', 'get_post_and_refer_link_title_duplicate' );
add_action( 'wp_ajax_nopriv_get_post_and_refer_link_title_duplicate', 'get_post_and_refer_link_title_duplicate' );
function get_post_and_refer_link_title_duplicate(){
	global $wpdb;
	$sql="SELECT count(post_title),ID FROM $wpdb->posts  WHERE `post_title` = '".$_REQUEST['post_url']."' AND post_status='publish' AND post_type='post'";
	$count = $wpdb->get_results($sql);
	if(isset($count) && $count[0]->ID){
		echo '{success}-'.get_permalink($count[0]->ID);
	}
	else{
		echo '{failed}';
	}
	wp_die();
}

add_action( 'wp_ajax_get_post_and_refer_link_title', 'get_post_and_refer_link_title' );
add_action( 'wp_ajax_nopriv_get_post_and_refer_link_title', 'get_post_and_refer_link_title' );

function get_post_and_refer_link_title(){
	$return = array();
	$page_url = ( isset( $_REQUEST['post_url'] ) ) ? $_REQUEST['post_url'] : '';
	$read_page = file_get_contents($page_url);
	
	$post_title_reference_link = '';

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
		elseif($page_title){
			$post_title_reference_link = $page_title;
		}
		
		if(isset($h1_title[1])) {
			$post_title_reference_link = $h1_title[1];
		} 
		elseif($page_title){
			$post_title_reference_link = $page_title;
		}
	}
	
	if(empty($post_title_reference_link)){
		$post_title_reference_link = $page_title;
	} else {
		$post_title_reference_link = '';	
	}
	
	$reference_link_title = strip_tags($post_title_reference_link);
	
	//echo $page_title.'{{post_title}}'.$reference_link_title;
	
	$return = array('post_title' => $page_title,'reference_link_title' => $reference_link_title);
	
	echo json_encode($return);
	
	wp_die();
	
}


add_action('wp_ajax_user_flag_report', 'user_flag_report');
add_action('wp_ajax_nopriv_user_flag_report', 'user_flag_report');

function user_flag_report()	{
	global $wpdb;
	$current_user = wp_get_current_user();
	$user_ip = get_the_user_ip();
	$return = array();
	if( $user_ip && !empty( $_REQUEST['post_id'] ) ) {
		$post_id = $_REQUEST['post_id'];
		//$inappropriate_count = get_post_meta($_REQUEST['post_id'], 'flage_as_inappropriate', 'yes');
		update_post_meta( $_REQUEST['post_id'], 'flage_as_inappropriate', 'yes' );
		$wpdb->insert ( $wpdb->prefix."hyroglf_post_revision_restore", 
				array(
					 'user_ip_address'	=> $user_ip,
					 'article_id' 		=> $post_id )
					 )or die("Error on Inserting");
					 
		if($post_id){
				
				$count_flag_report_for_warning = $wpdb->get_var("SELECT count(user_ip_address) FROM ".$wpdb->prefix."hyroglf_post_revision_restore WHERE article_id=$post_id");
				
            	// user ip count to restore post
            	$count_flag_report_post = $wpdb->get_var("SELECT count(article_id) FROM ".$wpdb->prefix."hyroglf_post_revision_restore where article_id=$post_id");
				
				// Rewrite revision for the post
				$sql_query="SELECT ID, post_title, post_content FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id order by ID desc limit 1,1";
				
				$count_flag_data = $wpdb->get_results($sql_query);
			
				// get last revision id for the post				
				$sql_query_last_id="SELECT ID FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id order by ID desc limit 0,1";
				
				$flag_data_id = $wpdb->get_var($sql_query_last_id);
				
				// get count of post type for revision
				$sql_query_count="SELECT count(post_type) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id";
				$count_post_unpublish = $wpdb->get_var($sql_query_count);
				
				// count of post parent for the post revision
				$sql_parent_count="SELECT count(post_parent) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id ";
				
				$count_parent_ct = $wpdb->get_var($sql_parent_count);
            }
            
		// theme option value       
        if( of_get_option('post-flag-count') ){
        	$post_flag_count = of_get_option('post-flag-count');
		}
			
        if( $post_flag_count > 0 ){
				
				
                if( $count_flag_report_post >= $post_flag_count && $count_post_unpublish>0 && $count_parent_ct > 1 ){
					
                    $title= $count_flag_data[0]->post_title;	
                    $content= addslashes($count_flag_data[0]->post_content);
                    $update_flag_post_report=$wpdb->query( "UPDATE $wpdb->posts SET post_title='".$title."',post_content='".$content."' WHERE ID = ".$post_id );									
                    $delete_revision_post_rewrite=$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->posts WHERE ID='".$flag_data_id."'" )); 
					
                    $delete_user_flag_table=$wpdb->query( "DELETE FROM ".$wpdb->prefix."hyroglf_post_revision_restore WHERE article_id = ".$post_id);
					
                }
				
                if( $count_flag_report_post == $post_flag_count && $count_parent_ct == 1 && $count_post_unpublish > 0 ){
					
                    $delete_revision_post_rewrite = $wpdb->query( "UPDATE $wpdb->posts SET post_status='draft' WHERE ID = ".$post_id );
					
                    $delete_user_flag_table = $wpdb->query( "DELETE FROM ".$wpdb->prefix."hyroglf_post_revision_restore WHERE article_id = ".$post_id ); 
                }
				
            }
		
		$return = array('result' => 'success', 'message' => 'Flagged successfully');
	} else {
		$return = array('result' => 'error', 'message' => 'Flag faild');
	}
	
	echo json_encode($return);
	
	wp_die();
}

add_action('wp_ajax_user_flag_advertisement', 'user_flag_advertisement');
add_action('wp_ajax_nopriv_user_flag_advertisement', 'user_flag_advertisement');

function user_flag_advertisement()	{
	global $wpdb;
	$current_user = wp_get_current_user();
	$user_ip = get_the_user_ip();
	$return = array();
	if( $user_ip && !empty( $_REQUEST['post_id'] ) ){
		
		$post_id = $_REQUEST['post_id'];
		
		update_post_meta( $_REQUEST['post_id'], 'flage_as_advertisement', 'yes' );
		
		$wpdb->insert ( $wpdb->prefix."hyroglf_advertisement_post_revision_restore", 
				array(
					 'user_ip_address'	=> $user_ip,
					 'article_id' 		=> $post_id )
					 ) or die("Error on Inserting");
					 
		if($post_id){
			// user ip count to restore post
			$count_flag_report_post = $wpdb->get_var("SELECT count(article_id) FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id);
			
			// Rewrite revision for the post
			$sql_query="SELECT ID,post_title, post_content FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id order by ID desc limit 1,1";
			
			$count_flag_data = $wpdb->get_results($sql_query);
		
			// get last revision id for the post				
			$sql_query_last_id="SELECT ID FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id order by ID desc limit 0,1";
			
			$flag_data_id = $wpdb->get_var($sql_query_last_id);
			// get count of post type for revision
			$sql_query_count="SELECT count(post_type) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id";
			$count_post_unpublish = $wpdb->get_var($sql_query_count);
			
			// count of post parent for the post revision
			$sql_parent_count="SELECT count(post_parent) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id ";
			$count_parent_ct = $wpdb->get_var($sql_parent_count);
		}
			
		// theme option value       
		if(of_get_option('post-flag-count')){
			$post_flag_count = of_get_option('post-flag-count');
		}
			
		if( $post_flag_count > 0 ){
			if( $count_flag_report_post >= $post_flag_count && $count_post_unpublish>0 && $count_parent_ct > 1 ){
				$title= $count_flag_data[0]->post_title;	
				$content= addslashes($count_flag_data[0]->post_content);
				$update_flag_post_report=$wpdb->query( "UPDATE $wpdb->posts SET post_title='".$title."',post_content='".$content."' WHERE ID = ".$post_id );									
				$delete_revision_post_rewrite=$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->posts WHERE ID='".$flag_data_id."'" )); 
				$delete_user_flag_table=$wpdb->query( "DELETE FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id); 
				//wp_redirect(get_permalink( $post->post_parent ));
			}
			if( $count_flag_report_post == $post_flag_count && $count_parent_ct == 1 && $count_post_unpublish > 0 ){
				$delete_revision_post_rewrite = $wpdb->query( "UPDATE $wpdb->posts SET post_status='draft' WHERE ID = ".$post_id );
				$delete_user_flag_table = $wpdb->query( "DELETE FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id ); 
			} 
		}
		
		$return = array('result' => 'success', 'message' => 'Flagged successfully');
	} else {
		$return = array('result' => 'error', 'message' => 'Flag faild');
	}
	
	echo json_encode($return);
	
	wp_die();
}


add_action( 'wp_ajax_set_hyroglf_analytics', 'hyroglf_analytics' );
add_action( 'wp_ajax_nopriv_set_hyroglf_analytics', 'hyroglf_analytics' );

function hyroglf_analytics( $post_id = '' ){
	if( isset( $_POST['post_id'] ) ) {
		$post_id = $_POST['post_id'];
	}
	
	if($post_id) {
		global $wpdb;
		$date = date('Y-m-d');
		
		$wpdb->insert( 
					$wpdb->prefix."hyroglf_analytics", 
					array(
						'post_id' => $post_id,
						'date' => $date
					), 
					array( 
						'%d',
						'%s'
					) 
		);
	}	
}

add_action( 'wp_ajax_custom_file_upload', 'custom_file_upload' );
add_action( 'wp_ajax_nopriv_custom_file_upload', 'custom_file_upload' );

function custom_file_upload(){
	
	$filename = $_FILES['file'];
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	if(isset($_FILES["file"]['name']) && !empty($_FILES["file"]['name'])) {
		//require_once( ABSPATH . '/wp-admin/includes/admin.php' );
		$file_return = wp_handle_upload( $_FILES["file"], array('test_form' => false ) );
		if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
			return false;
		} else {
			$filename = $file_return['file'];
			$attachment = array(
			'post_mime_type' => $file_return['type'],
			'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content' => '',
			'post_status' => 'inherit',
			'guid' => $file_return['url']
			);
			$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
			
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			
			wp_update_attachment_metadata( $attachment_id, $attachment_data );		  
			
			//Insert the attachment for post meta 		
			//add_post_meta($newpost_id, '_thumbnail_id', $attachment_id, true);
			
		}
	}
	
	$image = wp_get_attachment_image_src ( $attachment_id, '150_150_img' );
	
	$content = '<div class="uploaded_image"><a href="javascript:void(0);" class="attach_image_close">X</a><img src="'.$image[0].'" width="150" height="150" alt="" /><input type="hidden" name="post_attach_image[]" id="post_attach_image" value="'.$attachment_id.'"></div>';
	/* // Code for upload image end */
	$arr = array( 'index'=> $_POST['index'], 'image_id' => $attachment_id, 'content' => $content );
	echo $_POST['index'].'&&'.$content;
	exit;
	wp_die();
}

add_action('wp_ajax_user_voting_single', 'user_voting_single');
add_action('wp_ajax_nopriv_user_voting_single', 'user_voting_single');

function user_voting_single(){
	global $wpdb;
	
	//print_r($_POST);
	if( $_POST['user_id'] && $_POST['post_id'] && $_POST['posted_date'] ) {
		
		$user_id = $_POST['user_id'];
		$post_id = $_POST['post_id'];
		
		
		$post_infor = $_POST['informative'];
		$post_bias = $_POST['bias'];
		
		$result = '';
		$informative_obj = '';
		$bias_obj = '';	
		
		//$timezone = $ipInfo->time_zone;
		date_default_timezone_set('UTC');
		date_default_timezone_get();
		
		$posted_date = date("Y-m-d H:i:s");//date('Y-m-d');
			
		
		if( $post_infor /*$_POST['informative']*/) {
			$informative_obj = $wpdb->get_results("SELECT option_id,question_id_fk FROM ".$wpdb->prefix."hyroglf_question_option WHERE ques_option = '$post_infor'");
			
		}
		if( $post_bias /*$_POST['bias']*/) {
			$bias_obj = $wpdb->get_results("SELECT option_id,question_id_fk FROM ".$wpdb->prefix."hyroglf_question_option WHERE ques_option = '$post_bias'");
			
		}
		
		if( $informative_obj || $bias_obj ) {
			
			$result = $wpdb->insert($wpdb->prefix."hyroglf_users_voting_for_articles",
						array(
							'user_id' 		=> $user_id,
						 	'article_id' 	=> $post_id,
						 	'vote_date' 	=> $posted_date,
							'vote_server_date' 	=> date("Y-m-d H:i:s"),
							'vote_utc_date'		=> $posted_date,
						)
					);
			
			if( $informative_obj[0]->option_id ) {
				$result = $wpdb->insert($wpdb->prefix."hyroglf_users_voting",
							array(
								'question_id' 		=> $informative_obj[0]->question_id_fk,
								'ques_option_id' 	=> $informative_obj[0]->option_id,
								'user_id' 			=> $user_id,
								'user_article_id' 	=> $post_id
							)
						);
			}
			
			if( $bias_obj[0]->option_id ) {			 
				$result = $wpdb->insert($wpdb->prefix."hyroglf_users_voting",
							array(
								'question_id'		=> $bias_obj[0]->question_id_fk,
								'ques_option_id'	=> $bias_obj[0]->option_id,
								'user_id' 			=> $user_id,
								'user_article_id'	=> $post_id
							)
						);
			}
			
		}
		
		if( $post_infor ) {
			
			$count = voting_count_ratio(1, $post_id);
			
			$value = '';
			if( $post_infor == 'very' ) {
				$value = 3;
			} else if( $post_infor == 'somewhat' ) {
				$value = 2;
			} else if( $post_infor == 'not_really' ) {
				$value = 1;
			}
			
			$informative_post_meta = get_post_meta($post_id, 'vote_for_'.$post_infor, true);
			if( $informative_post_meta ) {
				update_post_meta($post_id, 'vote_for_'.$post_infor, (1+$informative_post_meta));
			} else {
				add_post_meta($post_id, 'vote_for_'.$post_infor, 1);
			}
			
			/*$informative_vote = get_post_meta($post_id, 'informative_vote', true);
			if( $informative_vote ) {
				update_post_meta( $post_id, 'informative_vote', ( $value + $informative_vote ) );
			} else {
				add_post_meta( $post_id, 'informative_vote', $value );
			}*/
			
			$informative_vote = get_post_meta($post_id, 'informative_vote', true);
			if( $informative_vote ) {
				update_post_meta( $post_id, 'informative_vote', $count );
			} else {
				add_post_meta( $post_id, 'informative_vote', $count );
			}
			
		}
			
			
		if( $post_bias ) {
			
			$count = voting_count_ratio(2, $post_id);
			
			$value = '';
			if( $post_bias == 'liberal' ) {
				$value = 3;
			} else if( $post_bias == 'neutral' ) {
				$value = 2;
			} else if( $post_bias == 'conservative' ) {
				$value = 1;
			}
			
			$bias_post_meta = get_post_meta($post_id, 'vote_for_'.$post_bias, true);
			if( $bias_post_meta ) {
				update_post_meta($post_id, 'vote_for_'.$post_bias, (1+$bias_post_meta));
			} else {
				add_post_meta($post_id, 'vote_for_'.$post_bias, 1);
			}
			
			/*$bias_vote = get_post_meta($post_id, 'bias_vote', true);
			if( $bias_vote ) {
				update_post_meta( $post_id, 'bias_vote', ( $value + $bias_vote ) );
			} else {
				add_post_meta( $post_id, 'bias_vote', $value );
			}*/
			
			$bias_vote = get_post_meta($post_id, 'bias_vote', true);
			if( $bias_vote ) {
				update_post_meta( $post_id, 'bias_vote', $count );
			} else {
				add_post_meta( $post_id, 'bias_vote', $count );
			}
			
		}
	
		if( $result ) {
			$array['ID'] = $_POST['post_id'];
			$post = (object) $array;
			echo '<div class="rating_vote_success_msg">You rated..</div>';
			echo load_post_title_with_rating( $post );
		}
		
	} else {
		echo 'Error';	
	}
	wp_die();
}

add_action('wp_ajax_user_voting', 'user_voting');
add_action('wp_ajax_nopriv_user_voting', 'user_voting');

function user_voting()	{
	
	global $wpdb, $post;
	$current_user = wp_get_current_user(); 
	$user_id = $current_user->ID;
	$post_id = $_REQUEST['post_id'];
	

	//$timezone = $ipInfo->time_zone;
	date_default_timezone_set('UTC');
	date_default_timezone_get();
	
	$posted_date = date("Y-m-d H:i:s");//date('Y-m-d');
	if( $user_id  && $post_id && $posted_date ){	
		
		$post_infor = ($_REQUEST['informative']) ? $_REQUEST['informative'] : '';
		$post_bias = ($_REQUEST['bias']) ? $_REQUEST['bias'] : '';
		
		$informative_obj = '';
		$bias_obj = '';
		
		if( $post_infor ) {
			$informative_obj = $wpdb->get_results("SELECT option_id,question_id_fk FROM ".$wpdb->prefix."hyroglf_question_option WHERE ques_option = '$post_infor'");
			
		}
		if( $post_bias ) {
			$bias_obj = $wpdb->get_results("SELECT option_id,question_id_fk FROM ".$wpdb->prefix."hyroglf_question_option WHERE ques_option = '$post_bias'");
			
		}
		
		if( $informative_obj || $bias_obj ) {
			
			$result = $wpdb->insert($wpdb->prefix."hyroglf_users_voting_for_articles",
						array(
							'user_id' 		=> $user_id,
						 	'article_id' 	=> $post_id,
						 	'vote_date' 	=> $posted_date,
							'vote_server_date' 	=> date("Y-m-d H:i:s"),
							'vote_utc_date'		=> $posted_date,
						)
					);
			
			if( $informative_obj[0]->option_id ) {
				$result = $wpdb->insert($wpdb->prefix."hyroglf_users_voting",
							array(
								'question_id' 		=> $informative_obj[0]->question_id_fk,
								'ques_option_id' 	=> $informative_obj[0]->option_id,
								'user_id' 			=> $user_id,
								'user_article_id' 	=> $post_id
							)
						);
			}
			
			if( $bias_obj[0]->option_id ) {			 
				$result = $wpdb->insert($wpdb->prefix."hyroglf_users_voting",
							array(
								'question_id'		=> $bias_obj[0]->question_id_fk,
								'ques_option_id'	=> $bias_obj[0]->option_id,
								'user_id' 			=> $user_id,
								'user_article_id'	=> $post_id
							)
						);
			}
			
		}
		
		if( $_REQUEST['informative'] ) {
			
			$count = voting_count_ratio(1, $post_id);
			
			$value = '';
			if( $_REQUEST['informative'] == 'very' ) {
				$value = 3;
			} else if( $_REQUEST['informative'] == 'somewhat' ) {
				$value = 2;
			} else if( $_REQUEST['informative'] == 'not_really' ) {
				$value = 1;
			}
			
			$informative_post_meta = get_post_meta($post_id, 'vote_for_'.$_REQUEST['informative'], true);
			if( $informative_post_meta ) {
				update_post_meta($post_id, 'vote_for_'.$_REQUEST['informative'], (1+$informative_post_meta));
			} else {
				add_post_meta($post_id, 'vote_for_'.$_REQUEST['informative'], 1);
			}
			
			$informative_vote = get_post_meta($post_id, 'informative_vote', true);
			if( $informative_vote ) {
				update_post_meta( $post_id, 'informative_vote', $count );
			} else {
				add_post_meta( $post_id, 'informative_vote', $count );
			}
		}
		
		if( $_REQUEST['bias'] ) {
			
			$count = voting_count_ratio(2, $post_id);
			
			$value = '';
			if( $_REQUEST['bias'] == 'liberal' ) {
				$value = 2;
			} else if( $_REQUEST['bias'] == 'neutral' ) {
				$value = 3;
			} else if( $_REQUEST['bias'] == 'conservative' ) {
				$value = 1;
			}
			
			$bias_post_meta = get_post_meta($post_id, 'vote_for_'.$_REQUEST['bias'], true);
			if( $bias_post_meta ) {
				update_post_meta($post_id, 'vote_for_'.$_REQUEST['bias'], (1+$bias_post_meta));
			} else {
				add_post_meta($post_id, 'vote_for_'.$_REQUEST['bias'], 1);
			}
			
			$bias_vote = get_post_meta($post_id, 'bias_vote', true);
			if( $bias_vote ) {
				update_post_meta( $post_id, 'bias_vote', $count );
			} else {
				add_post_meta( $post_id, 'bias_vote', $count );
			}
			
		}
	
		if( $result ) {
			$array['ID'] = $_REQUEST['post_id'];
			$post = (object) $array;
			
			$vote_arrays = get_post_rating_options( $_REQUEST['post_id'] );
			
			$return = array(
						'message'								=> 'success',
						'post_rating_options_informative_count' => ($vote_arrays['post_rating_options_informative_count']) ? $vote_arrays['post_rating_options_informative_count'] : '',
						'post_rating_options_bias_count' 		=> ($vote_arrays['post_rating_options_bias_count']) ? $vote_arrays['post_rating_options_bias_count'] : '',
						'informative_high_vote'					=> ($vote_arrays['informative_high_vote']) ? $vote_arrays['informative_high_vote'] : '',
						'bias_high_vote'						=> ($vote_arrays['bias_high_vote']) ? $vote_arrays['bias_high_vote'] : '',
						'user_vote_arr'							=> ($vote_arrays['user_vote_arr']) ? $vote_arrays['user_vote_arr'] : '',
						'user_vote_1'							=> ( $vote_arrays['user_vote_arr'][0]->ques_option ) ? $vote_arrays['user_vote_arr'][0]->ques_option : '',
						'user_vote_2'							=> ( $vote_arrays['user_vote_arr'][1]->ques_option ) ? $vote_arrays['user_vote_arr'][1]->ques_option : '',
						);
		}
	} else {
		$return = array('message' => 'error');
	}
	//print_r($return);
	echo json_encode( $return );
	wp_die();
}

add_action('wp_ajax_user_flag_report_single', 'user_flag_report_single');
add_action('wp_ajax_nopriv_user_flag_report_single', 'user_flag_report_single');

function user_flag_report_single()	{
	global $wpdb;
	if($_POST['user_ip_address'] && $_POST['post_id']){
		$current_user = wp_get_current_user();
		if(!empty($_POST['user_ip_address']) && !empty($_POST['post_id']))	{	
			$wpdb->insert ( $wpdb->prefix."hyroglf_post_revision_restore", 
					array(
						 'user_ip_address'=> $_POST['user_ip_address'],
						 'article_id' => 	 $_POST['post_id'])
						 )or die("Error on Inserting");
			
			$return = "Flagged successfully";
		} else {
			$return = "Error";
		}
	}
	echo $return;
	wp_die();
}

add_action('wp_ajax_user_flag_advertisement_single', 'user_flag_advertisement_single');
add_action('wp_ajax_nopriv_user_flag_advertisement_single', 'user_flag_advertisement_single');

function user_flag_advertisement_single()	{
	global $wpdb;
	$current_user = wp_get_current_user();
	$user_ip = get_the_user_ip();
	$return = '';
	if( $user_ip && !empty( $_POST['post_id'] ) ){
		
		$post_id = $_POST['post_id'];
		
		update_post_meta( $_POST['post_id'], 'flage_as_advertisement', 'yes' );
		
		$wpdb->insert ( $wpdb->prefix."hyroglf_advertisement_post_revision_restore", 
				array(
					 'user_ip_address'	=> $user_ip,
					 'article_id' 		=> $post_id )
					 ) or die("Error on Inserting");
					 
		if($post_id){
			// user ip count to restore post
			$count_flag_report_post = $wpdb->get_var("SELECT count(article_id) FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id);
			
			// Rewrite revision for the post
			$sql_query="SELECT ID,post_title, post_content FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id order by ID desc limit 1,1";
			
			$count_flag_data = $wpdb->get_results($sql_query);
		
			// get last revision id for the post				
			$sql_query_last_id="SELECT ID FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id order by ID desc limit 0,1";
			
			$flag_data_id = $wpdb->get_var($sql_query_last_id);
			// get count of post type for revision
			$sql_query_count="SELECT count(post_type) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id";
			$count_post_unpublish = $wpdb->get_var($sql_query_count);
			
			// count of post parent for the post revision
			$sql_parent_count="SELECT count(post_parent) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $post_id ";
			$count_parent_ct = $wpdb->get_var($sql_parent_count);
		}
			
		// theme option value       
		if(of_get_option('post-flag-count')){
			$post_flag_count = of_get_option('post-flag-count');
		}
			
		if( $post_flag_count > 0 ){
			if( $count_flag_report_post >= $post_flag_count && $count_post_unpublish>0 && $count_parent_ct > 1 ){
				$title= $count_flag_data[0]->post_title;	
				$content= addslashes($count_flag_data[0]->post_content);
				$update_flag_post_report=$wpdb->query( "UPDATE $wpdb->posts SET post_title='".$title."',post_content='".$content."' WHERE ID = ".$post_id );									
				$delete_revision_post_rewrite=$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->posts WHERE ID='".$flag_data_id."'" )); 
				$delete_user_flag_table=$wpdb->query( "DELETE FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id); 
				//wp_redirect(get_permalink( $post->post_parent ));
			}
			if( $count_flag_report_post == $post_flag_count && $count_parent_ct == 1 && $count_post_unpublish > 0 ){
				$delete_revision_post_rewrite = $wpdb->query( "UPDATE $wpdb->posts SET post_status='draft' WHERE ID = ".$post_id );
				$delete_user_flag_table = $wpdb->query( "DELETE FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id ); 
			} 
		}
		
		$return = "Flagged successfully";
	} else {
		$return = "Error";
	}
	
	echo $return;
	wp_die();
	
}

add_action( 'wp_ajax_share_post_email_send', 'share_post_email_send' );
add_action( 'wp_ajax_nopriv_share_post_email_send', 'share_post_email_send' );

function share_post_email_send() {
	$return = array();
	/*if(isset($_REQUEST['g-recaptcha-response']) && !empty($_REQUEST['g-recaptcha-response'])):
		//your site secret key
		$secret = '9LuDh9kyetYYYYdT0jsVckScsH8Ks3KA';
		//get verify response data
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_REQUEST['g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if($responseData->success):
			
		else:
			$errMsg = 'Robot verification failed, please try again.';
		endif;
	else:
			$errMsg = 'Please click on the reCAPTCHA box.';
	endif;
	if(!$errMsg ){*/
		$YourEmail = $_REQUEST['youremail'];
		$RecipientEmail = $_REQUEST['recipientemail'];
		$txtmessage = $_REQUEST['message'];
		$title = $_REQUEST['title'];
		$post_shared_link = $_REQUEST['post_shared_link'];
		$recipientname = $_REQUEST['recipientname'];
		$to = $RecipientEmail;
		$subject = 'Hyroglf - '.stripslashes($title);
		$uploads = wp_upload_dir();
		$message ='<img src="'.$uploads['url'].'/GLF.jpg"><div class="mail_div" style="max-width:320px;"><div class="mail_div_top" style="padding:0px; font-size:16px;"><h6 style="padding:10px 5px 10px 0px; font-size:16px; color:rgb(34, 34, 34); margin:0px; font-weight:400; display:inline-block; text-decoration:none;">Hey </h6>'.$recipientname.',<p style="font-size:16px; margin-top:7px;">'.$YourEmail.' shared a Hyroglf post with you. Click below to view it.</p><a style="background:#f5bd5b;color:#ffffff;border-radius:4px;padding:8px 25px;display:inline-block;margin:5px 0 0 0px; text-decoration:none; font-size:16px;" href="'.$post_shared_link.'">View Post</a></div><div class="yj6qo"></div><div class="adL"><br></div></div>';
		//$message = '<div class="mail_div" style="max-width:320px;"><div class="mail_div_top" style="border-bottom:1px solid #ccc; padding:0px 0px 10px"><h6 style="padding:10px 0px; font-size:13px; color:rgb(34, 34, 34); margin:0px; font-weight:400; display:inline-block;">Hey</h6>'+$YourEmail+'</div><p>'+$recipientname+'shared a Hyroglf post with you. Click below to view post.</p><a style="background:#f5bd5b;color:#ffffff;border-radius:4px;padding:8px 25px;display:inline-block;margin:5px 0 0 0px" href="'+$post_shared_link+'">View Post</a><div class="yj6qo"></div><div class="adL"><br></div></div>';
		//$message .= 'From,<br/>';
		//$message .=  $YourEmail; 
		$headers = array('Content-Type: text/html; charset=UTF-8');
		
		add_filter( 'wp_mail_content_type', 'set_html_content_type_share' );
		if( wp_mail( $to,$subject, $message, $headers) ) {
			$return = array('action' => true, 'message'=> '<p class="success_msg">Post shared!</p>');
		} else if(empty($recipientname) || empty($RecipientEmail) || empty($YourEmail) ){
			$return = array('action' => false, 'message'=> '<p class="error_msg">Please enter the required field</p>');	
		} else {
			$return = array('action' => false, 'message'=> '<p class="error_msg">Sorry some technical error occured.</p>');	
		}
		remove_filter( 'wp_mail_content_type', 'set_html_content_type_share' );
		
		echo json_encode($return);
		wp_die();
	/*}*/
}

function set_html_content_type_share() {
	return 'text/html';
}

add_action('wp_ajax_search_post_filter', 'search_post_filter');
add_action('wp_ajax_nopriv_search_post_filter', 'search_post_filter');

function search_post_filter()	{
	session_start();
	$_SESSION['term'] = ( isset( $_POST['term'] ) ) ? $_POST['term'] : '';
	$_SESSION['tax'] = ( isset( $_POST['tax'] ) ) ? $_POST['tax'] : '';
	$_SESSION['load'] = ( isset( $_POST['_load'] ) ) ? $_POST['_load'] : '';
	$_SESSION['src'] = ( isset( $_POST['src'] ) ) ? $_POST['src'] : '';
	$_SESSION['term_title'] = ( isset( $_POST['src'] ) ) ? $_POST['term_title'] : '';
	echo home_url();
	wp_die();
}

add_action('wp_ajax_get_title_by_id', 'get_title_by_id');
add_action('wp_ajax_nopriv_get_title_by_id', 'get_title_by_id');
function get_title_by_id() {
	$post_id = $_POST['post_id'];
	if( $post_id ) {
		$crunchifyURL = urlencode(get_the_permalink($post_id));
		$crunchifycontent = get_the_content($post_id);
		$crunchifyTitle = str_replace( ' ', '%20', get_the_title($post_id));
		if( isset( $_POST['type'] )  && !empty($_POST['type'] ) && $_POST['type'] == 'facebook' ) {
			//$link = 'http://www.facebook.com/sharer.php?s=100&p[title]="dsadsadsa"&p[summary]="dsadsadsadsa"&p[url]="$crunchifyURL"';
			//$link = 'https://www.facebook.com/sharer.php?u='.$crunchifyURL;
			//$link = "http://www.facebook.com/share.php?u=".$crunchifyURL."&amp;title=".$crunchifyTitle."&amp;description=Free summaries (up to 80 words) and source ratings. Anyone can vote or write, just be honest and concise.";
			$link = "https://www.facebook.com/sharer.php?u=".$crunchifyURL."&t=".$crunchifyTitle."&description=Free summaries (up to 80 words) and source ratings. Anyone can vote or write, just be honest and concise.";
		} else if( isset( $_POST['type'] )  && !empty($_POST['type'] ) && $_POST['type'] == 'twitter' ) {
			$link = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&via=@hyroglf';
		} else if( isset( $_POST['type'] )  && !empty($_POST['type'] ) && $_POST['type'] == 'whatsapp' ) {
			$link = "whatsapp://send?text=".$crunchifyTitle.' '.$crunchifyURL;;
		} else if( isset( $_POST['type'] )  && !empty($_POST['type'] ) && $_POST['type'] == 'sms' ) {
			$link = "sms://?&body=".$crunchifyURL;
		}
		echo $link;
	}
	wp_die();
}

add_action('wp_ajax_get_title_by_id_post_email', 'get_title_by_id_post_email');
add_action('wp_ajax_nopriv_get_title_by_id_post_email', 'get_title_by_id_post_email');
function get_title_by_id_post_email() {
	$post_id = $_REQUEST['post_id'];
	$link = '';
	if( $post_id ) {
		$link = get_the_permalink($post_id);
	}
	echo json_encode( array( '_link' => $link ) );
	wp_die();
}


add_action( 'wp_ajax_standard_timing', 'standard_timing' );
add_action( 'wp_ajax_nopriv_standard_timing', 'standard_timing' );
function standard_timing(){
	$posted_time = $_REQUEST['posted_date'];
	$time = get_option( 'get_current_date_time' );
	if( $time ) {
		update_option( 'get_current_date_time', $posted_time );
	} else {
		add_option( 'get_current_date_time' , $posted_time , '', 'yes' );
	}
	wp_die();
}

add_action( 'wp_ajax_standard_timing_for_fav', 'standard_timing_for_fav' );
add_action( 'wp_ajax_nopriv_standard_timing_for_fav', 'standard_timing_for_fav' );

function standard_timing_for_fav(){
	
	if($_POST["output_data"]){
		$fav_time = $_POST["output_data"];
			//echo $fav_time;
			echo "bala";
		}
		wp_die();
}