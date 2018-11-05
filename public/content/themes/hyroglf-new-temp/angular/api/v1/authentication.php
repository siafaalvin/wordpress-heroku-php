<?php
// Get post
$app->post('/get_post', function() use($app) {
	$r = $app->request->getBody();
	$param = json_decode($r);
    $db = new DbHandler();
	
	$filter_arr = array();
	//print_r($param);
	if( isset( $param->infor_filter ) || isset( $param->bias_filter ) ) {
		if( isset($param->infor_filter) && isset($param->bias_filter) ) {
			$filter_arr = array('informative' => $param->infor_filter, 'bias' => $param->bias_filter);
		} else if(isset( $param->infor_filter )) {
			$filter_arr = array('informative' => $param->infor_filter, 'bias' => '');
		} else if(isset( $param->bias_filter )) {
			$filter_arr = array('informative' => '', 'bias' => $param->bias_filter);
		}
	}
	
	$view = '';
	if(isset( $param->view )) {
		//echo $param->view;
		$view = $param->view;
	}
	
	$site_url = '';
	if(isset( $param->site_url )) {
		//echo $param->view;
		$site_url = $param->site_url;
	}
	
	$base_url = '';
	if(isset( $param->base_url )) {
		//echo $param->view;
		$base_url = $param->base_url;
	}
	
	$user_ip = '';
	if(isset( $param->user_ip )) {
		//echo $param->view;
		$user_ip = $param->user_ip;
	}
	
	if( isset($param->tax) && $param->tax == 'post_filter_by_author' ) {
		$user_id = $db->getOneRecord("SELECT ID FROM ".$param->wpdb."users WHERE user_login = '$param->term'");
		$param->term = $user_id['ID'];
	}
	
	$post_data = $db->getDatas( $param->wpdb, $param->load, $param->user_id, $param->user_access, $param->tax, $param->term, $param->page_num, $param->order, $param->user_role, $filter_arr, $view, $user_ip, $param->plugin_url, $base_url, $site_url);
	//print_r($post_data);
	echoResponse(200, $post_data);
});
$app->post('/update_userprofile_data', function() use($app) {
	$r = $app->request->getBody();
	$param = json_decode($r);
    $db = new DbHandler();
	$username = '';
	$useremail = '';
	if(isset($param->useremail)){
		$useremail = $param->useremail;
	}
	if(isset($param->username)){
		$username = $param->username;
	}
	$post_data = $db->userprofile_update_data( $username, $useremail , $param->wpdb, $param->user_id);
	echoResponse(200, $post_data);
});

$app->post('/update_username_data', function() use($app) {
	$r = $app->request->getBody();
	$param = json_decode($r);
    $db = new DbHandler();
	$username = '';
	$useremail = '';
	if(isset($param->useremail)){
		$useremail = $param->useremail;
	}
	if(isset($param->username)){
		$username = $param->username;
	}
	$post_data = $db->myprofile_update_data( $username, $useremail , $param->wpdb, $param->user_id);
	echoResponse(200, $post_data);
});
$app->post('/get_profile_data', function() use($app) {
	$r = $app->request->getBody();
	$param = json_decode($r);
    $db = new DbHandler();
	$post_data = $db->get_userprofile_data($param->wpdb, $param->user_id);
	echoResponse(200, $post_data);
	
});

// Get post by category
$app->post('/get_cat_post', function() use($app) {
	$r = $app->request->getBody();
	$param = json_decode($r);
    $db = new DbHandler();
	
	$filter_arr = array();
	if( isset( $param->infor_filter ) || isset( $param->bias_filter ) ) {
		if( isset($param->infor_filter) && isset($param->bias_filter) ) {
			$filter_arr = array('informative' => $param->infor_filter, 'bias' => $param->bias_filter);
		} else if(isset( $param->infor_filter )) {
			$filter_arr = array('informative' => $param->infor_filter, 'bias' => '');
		} else if(isset( $param->bias_filter )) {
			$filter_arr = array('informative' => '', 'bias' => $param->bias_filter);
		}
	}
	
	$view = '';
	if(isset( $param->view )) {
		//echo $param->view;
		$view = $param->view;
	}
	$user_ip = '';
	if(isset( $param->user_ip )) {
		//echo $param->view;
		$user_ip = $param->user_ip;
	}
	
	$base_url = '';
	if(isset( $param->base_url )) {
		//echo $param->view;
		$base_url = $param->base_url;
	}
	$site_url = '';
	if(isset( $param->site_url )) {
		//echo $param->view;
		$site_url = $param->site_url;
	}
	
	$post_data = $db->getDatas( $param->wpdb, $param->load, $param->user_id, $param->user_access, $param->tax, $param->term, $param->page_num, $param->order ,$param->user_role, $filter_arr, $view, $user_ip, $param->plugin_url, $base_url, $site_url);
	
	$term_img = '';
	if( isset( $param->term_id ) && $param->tax != 'post_tag' ) {
	$term_img = $db->getOneRecord("
						SELECT WP_post.guid AS term_image
						FROM ".$param->wpdb."options AS WP_option
						LEFT JOIN ".$param->wpdb."posts AS WP_post
							ON(WP_post.ID = WP_option.option_value)
						WHERE option_name = 'category_".$param->term_id."_post_category_image'"
					);
		
	}
	
	$post_data = array('post_data' => $post_data, 'term_img'=>$term_img);
	
	echoResponse(200, $post_data);
});
// Get post by category end

// Get post content using post id
$app->post('/get_post_by_id', function() use($app) {
	
	$r = $app->request->getBody();
	$param = json_decode($r);
    $db = new DbHandler();
	$post_meta_arr = $db->getAllRecord("SELECT meta_key, meta_value from ".$param->wpdb."postmeta WHERE post_id=".$param->post_id." AND meta_key in( 'post_multi_images', 'post_video' ) ");
	
	$content = '';
	$slide = '';
	if( is_array($post_meta_arr) ) {
		$isset = false;
		foreach( $post_meta_arr as $post_meta ) {
			if( isset($post_meta['meta_key']) && $post_meta['meta_key'] == 'post_multi_images' ) {
				$image_1 = @unserialize( $post_meta['meta_value'] );
				$image = unserialize($image_1);
				if( !empty( $image ) ) {
					$isset = true;
					foreach( $image as $img ) {
						
						$src = $db->getOneRecord("SELECT guid as src from ".$param->wpdb."posts WHERE ID=".$img['image_id']);
						$src['src'] = str_replace('http://','https://',$src['src']);
						$slide .= '<li><a class="fancybox" rel="gallery'.$param->post_id.'" href="'.$src['src'].'" title=""><img src="'.$src['src'].'" alt="" width="473" /></a></li>';
						//$slide .= '<li><a rel="prettyPhoto[mixed]'.$param->post_id.'" href="'.$src['src'].'" title=""><img src="'.$src['src'].'" alt="" width="473" /></a></li>';
					}
				}
			}
			
			if( isset($post_meta['meta_key']) && $post_meta['meta_key'] == 'post_video' ) {
				if( !empty( $post_meta['meta_value'] ) ) {
					$isset = true;
					
					$post_video_url = $post_meta['meta_value'];		
					$post_video_url = str_replace('http://','https://',$post_video_url);	
					$rx = '~
					  ^(?:https?://)?                           # Optional protocol
					   (?:www[.])?                              # Optional sub-domain
					   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
					   ([^&]{11})                               # Video id of 11 characters as capture group 1
						~x';			
					$has_match = preg_match($rx, $post_video_url, $matches);
					if($has_match == true){
							$url = $post_meta['meta_value'];
							$explode_url  = explode("//www.",$url);
							$explode_share = explode('https://',$url);
							$explode_share_http = explode('http://',$url);
							$explode_m  = explode("https://m.",$url);
							
							if( isset( $explode_url[1] )&& !isset($explode_m[1]) ){
								$explode = explode("//www.",$url);
							}
							else if( isset( $explode_share[1] ) && !isset($explode_m[1] ) ){
								$explode = explode('https://',$url);
							}
							else if( isset( $explode_share_http[1] )&& !isset($explode_m[1] ) ){
								$explode = explode('http://',$url);
							}
							else if( isset( $explode_m[1] )  ){
								$explode = explode('m.',$url);
							}
							
							if(isset($explode[1])) {
								$explode_com = explode('.com',$explode[1]);
								$explode_be = explode('.be',$explode[1]);
								$explode_ly = explode('.ly',$explode[1]);
								
								if( isset($explode_com[1]) ){
									$explode = explode('.com',$explode[1]);
								}else if(isset( $explode_be[1]) ){
									$explode = explode('.be',$explode[1]);
								}else if(isset( $explode_ly[1]) ){
									$explode = explode('.ly',$explode[1]);
								}
								
								if($explode[0] == 'dailymotion'){
									$explode_dailymotion = explode('/video/',$url);
									if(isset($explode_dailymotion[1])){
										$url = str_replace('/video/','/embed/video/',$url);
									}else{
										$url = str_replace('/hub/','/embed/video/',$url);
									}
								}
								
								if($explode[0] == 'youtube'){
									$explode_youtube_url = explode('&',$url);									
									
									if(isset($explode_m[1])){
										$url = str_replace('m.youtube.com/watch?v=','www.youtube.com/embed/',$explode_youtube_url[0]);
									}
									else{
										$url = str_replace('watch?v=','embed/',$explode_youtube_url[0]);
									}
								}
								
								if($explode[0] == 'youtu'){
									$explode_youtube_url = explode('&',$url);	
									if(isset($explode_url[1])){
										$url = str_replace('youtu.be/','youtube.com/embed/',$explode_youtube_url[0]);
									}
									else{
										$url = str_replace('youtu.be/','www.youtube.com/embed/',$explode_youtube_url[0]);
									}
								}
								
								if($explode[0] == 'dai'){
									if(isset($explode_url[1])){
										$url = str_replace('dai.ly/','dailymotion.com/embed/video/',$url);
									}
									else{
										$url = str_replace('dai.ly/','www.dailymotion.com/embed/video/',$url);
									}
								}
								if($explode[0] == 'vimeo'){
									$explode_vimeo = explode('channels/vimeogirls/',$url);
									$explode_staffpicks = explode('channels/staffpicks/',$url);
									$explode_musicpicks = explode('channels/musicvideos/',$url);
									
									if(isset($explode_vimeo[1])){
										$url = str_replace('vimeo.com/channels/vimeogirls/','player.vimeo.com/video/',$url);
									}
									else if(isset($explode_staffpicks[1])){
										$url = str_replace('vimeo.com/channels/staffpicks/','player.vimeo.com/video/',$url);
									}else if(isset($explode_musicpicks[1])){
										$url = str_replace('vimeo.com/channels/musicvideos/','player.vimeo.com/video/',$url);
									} else{		
										$url = str_replace('vimeo.com/','player.vimeo.com/video/',$url);
									}
								}
							}
							$url = str_replace('http://','https://',$url);
							$post_meta['post_video_link'] = $url;
					//$slide .= '<li><a rel="prettyPhoto[mixed]'.$param->post_id.'" href="'.$post_video_url.'" title=""><div class="video-container"><iframe src="'.$post_meta['post_video_link'].'" width="100%" height="222"></iframe></div></a></li>';
					$slide .= '<li><a class="fancybox" rel="gallery'.$param->post_id.'" href="'.$post_meta['post_video_link'].'" title=""><div class="video-container"><iframe src="'.$post_meta['post_video_link'].'" width="100%" height="222" allowfullscreen></iframe></div></a></li>';
				}
				}
			}
			
		}
		
		if($isset) {			
			 if( isset($image) && count($image) > 1 || isset($post_video_url) && count($post_video_url) > 1 || isset($post_video_url) && isset($image) && ($image && $post_video_url)){
				$content = '<ul class="single_post_multi_image_slide_'.$param->post_id.' single_post_multi_image_slide" >'.$slide.'</ul>';
				}else{
				$content = '<ul class="single_post_multi_image" >'.$slide.'</ul>';
			}
		}
		
	}
	
	$post_meta_arr = array($content);
	
	echoResponse(200, $post_meta_arr);
});
