<?php

function frontier_posting_form_submit($fpost_sc_parms = array())
	{
		
    //extract($fpost_sc_parms);		
    global $current_user, $wpdb;
    
    // which button has been pressed
	$tmp_return = isset($_POST['user_post_submit']) ? $_POST['user_post_submit'] : "savereturn";
	
    //Get Frontier Post capabilities
	$fp_capabilities	= frontier_post_get_capabilities();
	
	if(isset($_POST['action'])&& $_POST['action']=="wpfrtp_save_post")
		{
		if ( !wp_verify_nonce( $_POST['frontier_add_edit_post_'.$_POST['postid']], 'frontier_add_edit_post'  ) )
			{
			wp_die(__("Security violation (Nonce check) - Please contact your Wordpress administrator", "frontier-post"));
			}
		
	
		//Save quick post mode
		/*
		if( isset($_POST['fp_show_quickpost']) && fp_bool($_POST['fp_show_quickpost']) )
			update_user_meta( $current_user->ID, 'frontier_post_show_quickpost', "true" );
		else
			update_user_meta( $current_user->ID, 'frontier_post_show_quickpost', "false" );
		*/
		
	
		if ( isset($_REQUEST['task']) && ($_REQUEST['task'] == "new") )
			$tmp_task_new = true;
		else	
			$tmp_task_new = false;
		
			
		//fp_log("New post ? : ".$tmp_task_new);
		
		if(isset($_POST['post_status']))
			$post_status = $_POST['post_status'];
		else
			$post_status = 'draft';
		
		//Check if Publish has been pressed
		if ($tmp_return === "publish" && current_user_can("frontier_post_can_publish"))
			$post_status = 'publish';
		
		$tmp_post_type = isset($_POST['posttype']) ? $_POST['posttype'] : 'post';
		
		$postid = $_POST['postid'];
		$title = '';
		
		if( isset( $_POST['user_post_title'] ) && !empty( $_POST['user_post_title'] ) ) {
			
			$title = $_POST['user_post_title'];
			
		} else if( isset( $_POST['reference_link'] ) && !empty( $_POST['reference_link'] ) ) {
			$page_url = trim($_POST['reference_link']);
		
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
			}
			
			$title = $post_title_reference_link;
			
		}
		
		$tmp_title 		= trim(strip_tags($title));
		$tmp_content 	= trim( $_POST['frontier_post_content'] );
		
		// check empty title, and set status to draft if status is empty
		/*if ( $tmp_title)
			{
			if ( strlen($tmp_content) > 20 )
				$tmp_title = wp_trim_words( $tmp_content, 10);
			else
				$tmp_title = __("No Title", "frontier-post");
			
			$post_status = 'draft';
			frontier_post_set_msg('<div id="frontier-post-alert">'.__("Warning", "frontier-post").': '.__("Title was empty", "frontier-post").' - '.__("Post status set to draft", "frontier-post").'</div>');
			}
		$tmp_title = trim( strip_tags( $tmp_title ));
	
	
		
		if ( !fp_get_option_bool("fps_allow_empty_content") &&  $tmp_content ) ) 
			{
			$tmp_content = __("No content", "frontier-post");
			$post_status = 'draft';
			frontier_post_set_msg('<div id="frontier-post-alert">'.__("Warning", "frontier-post").': '.__("Content was empty", "frontier-post").' - '.__("Post status set to draft", "frontier-post").'</div>');
			}*/
			
		$tmp_excerpt = isset( $_POST['user_post_excerpt']) ? trim($_POST['user_post_excerpt'] ) : null;
		
		$users_role 	= frontier_get_user_role();
		
		//****************************************************************************************************
		// Manage Categories
		//****************************************************************************************************
		
		// Do not manage categories for page
		if ( $tmp_post_type != 'page' )
			{
			$category_type 		= $fp_capabilities[$users_role]['fps_role_category_layout'] ? $fp_capabilities[$users_role]['fps_role_category_layout'] : "multi"; 
			$default_category	= $fp_capabilities[$users_role]['fps_role_default_category'] ? $fp_capabilities[$users_role]['fps_role_default_category'] : get_option("default_category"); 
		
			$tmp_field_name = frontier_tax_field_name('category');
			if ( ($category_type != "hide") && ($category_type != "readonly") )
				$tmp_categorymulti = ( isset($_POST[$tmp_field_name]) ? $_POST[$tmp_field_name] : array() );
		
		
			
			//frontier_post_set_msg("Default Category: ".$default_category);
			//frontier_post_set_msg("Post Categories: ".( isset($_POST['post_categories']) ? $_POST['post_categories'] : "NONE"));
			
		
			// if no category returned from entry form, check for hidden field, if this is empty set default category 
			if ((!isset($tmp_categorymulti)) || (count($tmp_categorymulti)==0) )
				{
				$tmp_categorymulti = ( isset($_POST['post_categories']) ? explode(',', $_POST['post_categories']) : array());
				// Do not use default category if post type = page 
				if ( $tmp_post_type != 'page' )
					$tmp_categorymulti = ((count($tmp_categorymulti) > 0 && isset($tmp_categorymulti[0]) && $tmp_categorymulti[0] > 0) ? $tmp_categorymulti : array($default_category));
				}
			//frontier_post_set_msg("Category from POST: ".print_r($tmp_categorymulti,true));
			} // do not manage categories for pages
		
		//****************************************************************************************************
		// Update post
		//****************************************************************************************************
		
		
		$tmp_post = array(
			 'ID'				=> $postid,
			 'post_type'		=> $tmp_post_type,
			 'post_title' 		=> $tmp_title,
			 'post_status' 		=> $post_status,
			 'post_content' 	=> $tmp_content,				 
			 'post_excerpt' 	=> $tmp_excerpt
			);
		
		// Do not manage categories for page
		if ( $tmp_post_type != 'page' )
			{
			$tmp_post['post_category'] 	= $tmp_categorymulti;
			}
		
		
		//****************************************************************************************************
		// Apply filter before update of post 
		// filter:			frontier_post_pre_update
		// $tmp_post 		Array that holds the updated fields 
		// $tmp_task_new  	Equals true if the user is adding a post
		// $_POST			Input form			
		//****************************************************************************************************
		
		$tmp_post = apply_filters( 'frontier_post_pre_update', $tmp_post, $tmp_task_new, $_POST );
		
		
		//Set $post_status to tmp_post value, if changed by filter 
		$post_status = $tmp_post['post_status'];
		
		
		//force save with draft status first, if new post and status is set to published to align with wordpress standard
		$exists_reference_link = false;
		if( $_POST['reference_link'] != '' ) {
			$reference_link = $_POST['reference_link'];
			$exists_reference_link = $wpdb->get_results("SELECT post_id FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'reference_link' AND meta_value = '$reference_link'");
			//print_r($exists_reference_link);
		}
		$isexists = false;
		$exists_post_link = '';
		if ( ($tmp_task_new == true) && ($post_status == "publish") ) {
			if(!$exists_reference_link) {
				$tmp_post['post_status'] = "draft";
				wp_update_post( $tmp_post );
				$tmp_post = array('ID'	=> $postid, 'post_status' => $post_status);
				wp_update_post( $tmp_post );
				
			} else {
				$isexists = true;
				$exists_post_link = get_the_permalink($exists_reference_link[0]->post_id);
			}
		} else {
			wp_update_post( $tmp_post );
		}
		
		if( $_POST['post_attach_image'] ) {
			$attachment_id_arr = $_POST['post_attach_image'];
			$attachment = array();
			foreach( $attachment_id_arr as $attachment_id ) {
				$attachment[] = array('image_id' => $attachment_id, 'title' => $_POST['post_attach_image_title_'.$attachment_id]);
			}
			
			
			$post_image = get_post_meta( $postid, 'post_multi_images' );
			if( $post_image ) {
				update_post_meta( $postid, 'post_multi_images', serialize( $attachment ) );
			} else {
				add_post_meta( $postid, 'post_multi_images', serialize( $attachment ) );
			}
			
		} else if(empty($_POST['post_attach_image'])){
			update_post_meta( $postid, 'post_multi_images', '' );
		}
		$explode_video_url = explode('htttp://',$_POST['post_video']);
		
		if( $_POST['post_video'] ){			
			$url = $_POST['post_video'];			
			$rx = '~
			  ^(?:https?://)?                           # Optional protocol
			   (?:www[.])?                              # Optional sub-domain
			   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
			   ([^&]{11})                               # Video id of 11 characters as capture group 1
				~x';			
			$has_match = preg_match($rx, $url, $matches);
			if($has_match == true){
				$post_video = $_POST['post_video'];
				$meta_post_video = get_post_meta( $postid, 'post_video' );
				if( $meta_post_video ) {
					update_post_meta( $postid, 'post_video',  $post_video  );
				} else {
					add_post_meta( $postid, 'post_video',  $post_video  );
				}				
			} else {
				update_post_meta( $postid, 'post_video',  ''  );
			}			
			
		} else {
			update_post_meta( $postid, 'post_video',  ''  );
		}
		
		if( isset( $_POST['reference_link'] ) ) {
			$get_post_meta = get_post_meta($tmp_post->ID, 'reference_link', $_POST['reference_link']);
			update_post_meta($postid, 'reference_link', $_POST['reference_link'] );
		}
		
		if( isset( $_POST['publish_date_news'] ) ) {
			update_post_meta($postid, 'publish_date_news', $_POST['publish_date_news'] );
		}
		
		/*if( $_POST['publish_date_news'] ){
			$publish_date_news = $_POST['publish_date_news'];
			$is_publish_date_news = get_post_meta( $postid, 'publish_date_news', true );
			if( $is_publish_date_news ) {
				update_post_meta( $postid, 'publish_date_news', $publish_date_news );
			} else {
				add_post_meta( $postid, 'publish_date_news', $publish_date_news );
			}
		}*/
		
		if( $_POST['source_name'] ){
			$source_name = $_POST['source_name'];
			$post_source_name = get_post_meta( $postid, 'source_name', true );
			if( $post_source_name ) {
				update_post_meta( $postid, 'source_name', $source_name );
			} else {
				add_post_meta( $postid, 'source_name', $source_name );
			}
		}
		
		if( $_POST['fp_refernce_link_home_page_title'] ){
			
			$refernce_link_home_page_title = $_POST['fp_refernce_link_home_page_title'];
			
			$post_refernce_link_home_page_title = get_post_meta( $postid, 'refernce_link_home_page_title', true );
			if( $refernce_link_home_page_title ) {
				update_post_meta( $postid, 'refernce_link_home_page_title', $refernce_link_home_page_title );
			} else {
				add_post_meta( $postid, 'refernce_link_home_page_title', $refernce_link_home_page_title );
			}
			
		}
		/*$timezone = '';
		$ipaddress = '';
		$country_code = '';
		$cur_lat = '';
		$cur_long = '';
		$ip = get_client_ip();  //$_SERVER['REMOTE_ADDR']
		$ipInfo = file_get_contents("http://freegeoip.net/json/$ip");
		$ipInfo = json_decode($ipInfo);
		$country_code = $ipInfo->country_code;
		$cur_lat = $ipInfo->latitude;
		$cur_long = $ipInfo->longitude;		
		$timezone = $ipInfo->time_zone;
		if($timezone == ''){
			$timezone = get_nearest_timezone($cur_lat, $cur_long, $country_code);
		}*/
		date_default_timezone_set('UTC');
		
		$current_user = wp_get_current_user(); // current user
		if( $_POST['glf_update'] ){
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
			
			
			// add post edit user name
			if( isset( $_REQUEST['task'] ) && $_REQUEST['task'] == 'edit' ) {
				$last_edited_user = get_post_meta( $postid, 'last_edited_user', true );
				if( $last_edited_user ) {
					update_post_meta( $postid, 'last_edited_user', $current_user->user_login );
				} else {
					add_post_meta( $postid, 'last_edited_user', $current_user->user_login );
				}
			}
		}
		
		if( $_POST['post_read_time'] ) {
			$values = array();
			$values[$_POST['hidden_time_selected_val']] = $_POST['post_read_time'];
			
			$post_read_arr = get_post_meta( $postid, 'post_read_time', true );
			$post_read = unserialize($post_read_arr);
			//$post_read = array_filter( $post_read );
			
			if( !empty( $post_read ) && is_array( $post_read ) ) {
				update_post_meta($postid, 'post_read_time', serialize( $values ) );
			} else {
				add_post_meta($postid, 'post_read_time', serialize( $values ) );
			}
		}
		
		/*$informative_vote = get_post_meta($postid, 'informative_vote', true);
		if( $informative_vote ) {
		} else {
			add_post_meta( $postid, 'informative_vote', 0 );
		}
		
		$bias_vote = get_post_meta($postid, 'bias_vote', true);
		if( $bias_vote ) {
		} else {
			add_post_meta( $postid, 'bias_vote', 0 );
		}*/
		
		//****************************************************************************************************
		// Tags
		//****************************************************************************************************
		
		// Do not manage tags for page
		if ( current_user_can( 'frontier_post_tags_edit' ) && $tmp_post_type != 'page' )
			{
			$fp_tag_count	= fp_get_option_int("fps_tag_count",3);
			$taglist = array();
			for ($i=0; $i<$fp_tag_count; $i++)
				{
				if (isset( $_POST['user_post_tag'.$i]))
					{
					array_push($taglist, fp_tag_transform($_POST['user_post_tag'.$i]));
					}
				}
				
				wp_set_post_tags($postid, $taglist);
			}

		

		//****************************************************************************************************
		// Add/Update message
		//****************************************************************************************************
		
		$action_type = "add_modified";
		if($tmp_task_new == true && $isexists == false) {
			frontier_post_set_msg(__("<span class='fp_success_msg'>Post Added – Thank You!</span>", "frontier-post").": ".$tmp_title);
			$link = get_the_permalink($postid);
			$get_post_url = get_post_meta($postid,'post_url');
			if( $get_post_url)  {
				update_post_meta($postid, 'post_url', $link );
			} else {
				add_post_meta($postid, 'post_url', $link );
			}
			?>
            	<script type="text/javascript">
					window.location.href = '<?php echo $link.'?action_type='.base64_encode($action_type); ?>';
                </script>
			<?php
		} else if ( $tmp_task_new == true && $isexists == true) {
			frontier_post_set_msg(__("<span class='fp_error_msg'>Post not added</span>", "frontier-post"));
			/*frontier_post_set_msg(__("Post with the Source URL you entered already exists. <a href='".$exists_post_link."' target='_blank'>Click here</a> to view", "frontier-post").": <a href='".$exists_post_link."' target='_blank'>".$reference_link."</a>");*/
			frontier_post_set_msg(__("Post with the Source URL you entered already exists. <a href='".$exists_post_link."' target='_blank'>Click here</a> to view", "frontier-post"));
		} else {	
			frontier_post_set_msg(__("<span class='fp_success_msg'>Post Edited – Thank You!</span>", "frontier-post")." "/*.$tmp_title*/);
			$link = get_the_permalink($postid);
			$action_type = "edit";?>
            	<script type="text/javascript">
					window.location.href = '<?php echo $link.'?action_type='.base64_encode($action_type); ?>';
                </script>
			<?php
		}
		
		
		//****************************************************************************************************
		// Taxonomies
		//****************************************************************************************************
		
			
		
		// Do not manage taxonomies for page
		if ( $tmp_post_type != 'page' )
			{
			
			foreach ( $fpost_sc_parms['frontier_custom_tax'] as $tmp_tax_name ) 
				{
				if ( !empty($tmp_tax_name) && ($tmp_tax_name != 'category') )
					{
					$tmp_field_name = frontier_tax_field_name($tmp_tax_name);
					$tmp_value = isset($_POST[$tmp_field_name]) ? $_POST[$tmp_field_name] : array();
					if ( is_array($tmp_value) )
						$tmp_tax_selected = $tmp_value;
					else
						$tmp_tax_selected = array($tmp_value);
				
					wp_set_post_terms( $postid, $tmp_tax_selected, $tmp_tax_name );
					//error_log("set terms: ".$tmp_tax_name." : ". print_r($tmp_tax_selected,true));
			
					}
				}	
			} // end do not manage taxonomies for pages
	
		//****************************************************************************************************
		// End updating post
		//****************************************************************************************************
				
		//Get the updated post
		$my_post = get_post($postid);
		
		
		// Delete users cache for My Posts widget
		fp_delete_my_post_w_cache();
		
		//***************************************************************************************
		//* Save post moderation fields
		//***************************************************************************************
		
		if ( fp_get_option_bool("fps_use_moderation") && (current_user_can("edit_others_posts") || $current_user->ID == $my_post->post_author))
			{
			if (isset($_POST['frontier_post_moderation_new_text']))
				{
				$fp_moderation_comments_new = $_POST['frontier_post_moderation_new_text'];
				//$fp_moderation_comments_new = trim(stripslashes(strip_tags($fp_moderation_comments_new)));
				$fp_moderation_comments_new = wp_strip_all_tags($fp_moderation_comments_new);
				$fp_moderation_comments_new = nl2br($fp_moderation_comments_new);
				$fp_moderation_comments_new = stripslashes($fp_moderation_comments_new);
				$fp_moderation_comments_new = trim($fp_moderation_comments_new);
				if (strlen($fp_moderation_comments_new) > 0)
					{
					global $current_user;
					
					$fp_moderation_comments_old = get_post_meta( $my_post->ID, 'FRONTIER_POST_MODERATION_TEXT', true );
					$fp_moderation_comments  = current_time( 'mysql')." - ".$current_user->user_login.":<br>";
					$fp_moderation_comments .= $fp_moderation_comments_new."<br>";
					$fp_moderation_comments .= '<hr>'."<br>";
					$fp_moderation_comments .= $fp_moderation_comments_old."<br>";
					update_post_meta( $my_post->ID, 'FRONTIER_POST_MODERATION_TEXT', $fp_moderation_comments );
					update_post_meta( $my_post->ID, 'FRONTIER_POST_MODERATION_DATE', current_time( 'mysql'));
					update_post_meta( $my_post->ID, 'FRONTIER_POST_MODERATION_FLAG', 'true');
					// Email author on moderation comments
					if (isset($_POST['frontier_post_moderation_send_email']) && $_POST['frontier_post_moderation_send_email'] == "true")
						{
						$to      		= get_the_author_meta( 'email', $my_post->post_author );
						$subject 		= __("Moderator has commented your pending post", "frontier-post")." (".get_bloginfo( "name" ).")";
						$body    		= __("Moderator has commented your pending post", "frontier-post").": ".$my_post->post_title ." (".get_bloginfo( "name" ).")"."\r\n\r\n";
						$body    		.= "Comments: ".$_POST['frontier_post_moderation_new_text']."\r\n\r\n";
		
		
						if( !wp_mail($to, $subject, $body ) ) 
							frontier_post_set_msg(__("Message delivery failed - Recipient: (", "frontier-post").$to.")");
						}
					}
				}
			
			}
		
		
		
		
		//****************************************************************************************************
		// Action fires after add/update of post, and after taxonomies are updated
		// Do action 		frontier_post_post_save
		// $my_post 		Post object for the post just updated 
		// $tmp_task_new  	Equals true if the user is adding a post
		// $_POST			Input form			
		//****************************************************************************************************
		
		do_action('frontier_post_post_save', $my_post, $tmp_task_new, $_POST);
		
		
		
		
		//If save, set task to edit
		if ( $tmp_return == "save" )
			{
			$_REQUEST['task'] = "edit";
			$_REQUEST['postid'] = $postid;
			}
		
		// if shortcode frontier_mode=add, return to add form instead of list
		if ( $fpost_sc_parms['frontier_mode'] == "add" && $tmp_return == "savereturn")
			$tmp_return = "add";
		// if shortcode frontier_mode=quickpost, return to quickpost form instead of list
		if ( $fpost_sc_parms['frontier_mode'] == "quickpost" && ($tmp_return == "savereturn" || $tmp_return === "publish" ) )
			$tmp_return = "quickpost";
		
		
		switch( $tmp_return )
			{
			case 'preview':
				frontier_preview_post($postid);
				break;
			
			case 'add':
				frontier_post_add_edit($fpost_sc_parms);
				break;
			
			case 'quickpost':
				frontier_quickpost($fpost_sc_parms);
				break;
			
			case 'savereturn':
				frontier_user_post_list($fpost_sc_parms);
				break;
				
			case 'save':
				frontier_post_add_edit($fpost_sc_parms);
				break;
				
			case 'delete':
				frontier_prepare_delete_post($fpost_sc_parms);
				break;
			
			default:
				frontier_user_post_list($fpost_sc_parms);
				break;
			} 
			
			//frontier_user_post_list($fpost_sc_parms);
			frontier_post_add_edit($fpost_sc_parms);?>
            <script type="text/javascript">
				window.location.href = '<?php echo home_url('/'); ?>';
            </script>
            <?php
		}
	else
		{
		frontier_post_set_msg(__("Error - Unable to save post", "frontier-post"));
		frontier_user_post_list($fpost_sc_parms);
		} // end isset post
} // end function frontier_posting_form_submit



?>