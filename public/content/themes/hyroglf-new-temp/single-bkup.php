<?php get_header(); ?>
<div ng-app="hyroglf_app" ng-controller ="authSingleCtrl">
	<?php category_left_sidebar_for_page('single'); ?>
        
    <?php header_section(); ?>
    <div class="post_single_filter_text">
                        <div class="post_single_view_content_head">                            
                            <div class="post_single_heade_edit_action"><?php
                               /* if( is_user_logged_in() ) { ?>
                                   <!-- <a href="<?php //echo home_url('/my-posts/?task=new');?>" onclick="page_redirect(<?php //echo "'".home_url('/my-posts/?task=new')."'";?>)">-->
                                    <a href="<?php echo home_url('/my-posts/?task=new');?>" onclick="page_redirect(<?php echo "'".home_url('/my-posts/?task=new')."'";?>)">
										<?php get_image('pencil_sign.png', 60, 60);?>
                                    </a><?php
                                } else { 
								//$login_url = home_url('/login/');?>
                                    <!--<a href="<?php //echo home_url('/login/');?>" onclick="page_redirect(<?php //echo "'".$login_url."'"; ?>)">-->
                                      <a href="<?php echo home_url('/login/');?>">
										<?php get_image('pencil_sign.png', 60, 60);?>
                                    </a><?php
                                } */?>
                                <div class="post_single_cat_view_popup">
                                    <a class="filter_cat_popup_action" id ="filter_cat_popup" href="javascript:void(0);" onclick="view_all_list_cat('all');">
										<?php get_image('plus_sign.png', 60, 60);?>
                                    </a>
                                     <?php popup_category_image_content(); ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
    <div class="ajax_filter_content">
                
                    <div class="filter_post_img cat_image_header_sec" ng-bind-html-unsafe="selected_category_img"></div>
                    
                    
                </div>
    
   	<?php
	if (have_posts()) : while (have_posts()) : the_post(); global $post;
	
		hyroglf_analytics( $post->ID );
	
		$url_theme = get_stylesheet_directory_uri();
		
		$cat_terms = get_terms('category');
		if( $cat_terms ) {
			foreach( $cat_terms as $term ) {
				$term_id = $term->term_id;
				$allow = get_option('category_'.$term_id.'_edit_title');
				if( isset( $allow[0] ) ) {
					if( $allow[0] == 'yes' )				
						echo '<input type="hidden" name="allow_cat_id[]" id="allow_cat_id_'.$term_id.'" value="'.$term_id.'">';
				}
			}
		} ?>
    
        <div class="content_wrapper hdtopmargin">
			 <?php
			if( isset( $_GET['action_type'] ) ) {
				$action_type = base64_decode($_GET['action_type']);
				$message = '';
				if( $action_type == 'add_modified' ) {
					$message = 'Post Added – Thank You!';
				} else if( $action_type == 'edit' ) {
					$message = 'Post Edited – Thank You!';
				} ?>
                <div class="post_added_msg post_edit_success">
                    <p class="success_msg"><?php echo $message; ?></p>
                </div><?php
			}
			global $wpdb;	
			$current_user = wp_get_current_user(); // current user
			$postid = get_the_ID();
			$content_data = get_post_meta($post->ID); // post meta id
			$user_ip= get_the_user_ip();
			
			if($postid && $current_user->ID){
			 	$user_id = $current_user->ID;
			 	$count_user = $wpdb->get_var("SELECT count(user_id) AS user_count FROM wp_hyroglf_users_voting_for_articles WHERE article_id=$postid AND user_id = $user_id");
			}
			
            if($user_ip && $postid){
             	// user ip count to remove flag button for particular post
            	$count_flag_report = $wpdb->get_var("SELECT count(user_ip_address) FROM wp_hyroglf_post_revision_restore WHERE article_id=$postid and user_ip_address='".$user_ip."'");	
            }
			
            if($postid){
				
				$count_flag_report_for_warning = $wpdb->get_var("SELECT count(user_ip_address) FROM ".$wpdb->prefix."hyroglf_post_revision_restore WHERE article_id=$postid");
				
            	// user ip count to restore post
            	$count_flag_report_post = $wpdb->get_var("SELECT count(article_id) FROM wp_hyroglf_post_revision_restore where article_id=$postid");
				// Rewrite revision for the post
				$sql_query="SELECT ID,post_title, post_content FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $postid order by ID desc limit 1,1";
				$count_flag_data = $wpdb->get_results($sql_query);
			
				// get last revision id for the post				
				$sql_query_last_id="SELECT ID FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $postid order by ID desc limit 0,1";
				
				$flag_data_id = $wpdb->get_var($sql_query_last_id);
				// get count of post type for revision
				$sql_query_count="SELECT count(post_type) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $postid";
				$count_post_unpublish = $wpdb->get_var($sql_query_count);
				
				// count of post parent for the post revision
				$sql_parent_count="SELECT count(post_parent) FROM $wpdb->posts  WHERE `post_status` = 'inherit' AND `post_type` = 'revision' AND `post_parent` = $postid ";
				$count_parent_ct = $wpdb->get_var($sql_parent_count);
            }
            
			// theme option value       
            if(of_get_option('post-flag-count')){
                $post_flag_count = of_get_option('post-flag-count');
			}
			
            if($post_flag_count>0){
                if($count_flag_report_post>=$post_flag_count && $count_post_unpublish>0 && $count_parent_ct>1){
                    $title= $count_flag_data[0]->post_title;	
                    $content= addslashes($count_flag_data[0]->post_content);
                    $update_flag_post_report=$wpdb->query( "UPDATE $wpdb->posts SET post_title='".$title."',post_content='".$content."' WHERE ID =$postid");									
                    $delete_revision_post_rewrite=$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->posts WHERE ID='".$flag_data_id."'" )); 
                    $delete_user_flag_table=$wpdb->query( "DELETE FROM wp_hyroglf_post_revision_restore WHERE article_id=$postid" ); 
                    wp_redirect(get_permalink( $post->post_parent ));
                }
                if($count_flag_report_post==$post_flag_count && $count_parent_ct==1 && $count_post_unpublish>0 ){
                    $delete_revision_post_rewrite=$wpdb->query( "UPDATE $wpdb->posts SET post_status='draft' WHERE ID = $postid" );
                    $delete_user_flag_table=$wpdb->query( "DELETE FROM wp_hyroglf_post_revision_restore WHERE article_id=$postid" ); 
                } 
            }
			$post_multi_image_arr = get_post_meta( $post->ID, 'post_multi_images', true );
			if($post_multi_image_arr) {
				$post_multi_image_arr = unserialize( $post_multi_image_arr );
				//$post_multi_image_arr = array_filter($post_multi_image_arr);
			}
			
			$post_multi_video_arr = get_post_meta( $post->ID, 'post_video_link', true );
			if($post_multi_video_arr) {
				$post_multi_video_arr = unserialize( get_post_meta( $post->ID, 'post_video_link', true ) );
				//$post_multi_video_arr = array_filter($post_multi_video_arr);
			}
			
			$image_video = false;
			if( ( is_array( $post_multi_image_arr ) && !empty( $post_multi_image_arr ) ) || ( is_array( $post_multi_video_arr ) && $post_multi_video_arr ) ) {
				$image_video = true;
				
			} ?>
                <div class="wiki_right_top">
                    <div class="single_post_title_section">
                        <div class="post_content_<?php echo $post->ID;?>">
                            <?php load_post_title_with_rating( $post, '' ); ?>
                       </div>
                       <div class="single_edit_action">
                            <ul>
                                <li class="single_close_icon">
                                    <a href="javascript:void(0);" onclick="close_single_content('<?php echo home_url('/');?>');"><?php get_image('close_sign.png', 35, 35);?></a>
                                </li>
                                
                                <li class="single_plus_icon" style="display:none;">
                                    <a href="javascript:void(0);" onclick="show_single_content();"><?php get_image('plus_sign.png', 35, 35);?></a>
                                </li>
                            </ul>
                      </div>
                    </div>
					<div class="post_single_content_section"><?php                    
						$slide_img = get_post_meta( $post->ID, 'post_multi_images', true );
						$post_multi_image_arr='';
						if($slide_img) {
							$post_multi_image_arr = unserialize( $slide_img );
						}
						
						//$slide_video = get_post_meta( $post->ID, 'post_video_link', true );
						//$post_multi_video_arr = unserialize( $slide_video );
						?>
                       <?php /*?> <?php
                                if( !$image_video ) { ?>
                                <span class="single_edit_icon"><?php
                                    edit_post_link('<img src="'.$url_theme.'/assets/images/pencil_sign.png" width="35" height="35"/>', '<span class="post-edit" id="'.$postid.'">', '</span>');
                                    ?>
                                </span><?php
                                } ?>
                               <?php
								if( $image_video ) { ?>
								<span class="single_edit_icon"><?php
									edit_post_link('<img src="'.$url_theme.'/assets/images/pencil_sign.png" width="35" height="35"/>', '<span class="post-edit" id="'.$postid.'">', '</span>');
									?>
								</span><?php
								} ?><?php */?>
                        <div class="post_content_section post_content-<?php echo $postid; ?>">                            
                            <div class="post_content">
                                <?php the_content(); ?>
                            </div>
                        </div><?php
						 $post_video = get_post_meta( $post->ID, 'post_video', true );
						
						if( isset( $post_multi_image_arr[0] ) || $post_video /*isset( $post_multi_video_arr[0] )*/ ) {?>
							<div class="post_image_video_section">
								<?php get_slide_image_and_video_single( $post->ID ); ?>
							</div><?php
						} ?>
                        <div class="updated_user_section">
                            <div class="updated_user"><?php
                                $last_edited_user = $content_data['last_edited_user'][0];
                                if( $last_edited_user ) {
                                    $user_data = get_user_by( 'login', $last_edited_user );
                                    $user_login = $user_data->data->user_login; 
                                    $mod_date = get_post_meta( $post->ID, 'glf_date_update_utc', true );?>
                                    <p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single">updated by <a href="javascript:void(0);<?php echo home_url('/?term='.$user_data->data->user_login.'&tax=filter_by_author'); ?>" data_value="<?php echo $user_login; ?>" onclick="inner_page_cat_filter('<?php echo $user_login; ?>', 'post_filter_by_author', 'cat_post', '','');"><?php echo $last_edited_user;?></a></p><?php
                                }
                                else{
                                    $user_list_data = get_author_name( $post->post_author );
                                    $user_data = get_user_by( 'login', $user_list_data );
                                    $post_glf_update = get_post_meta( $post->ID, 'glf_date_update_utc', true );?>
                                <p class="glf_update_date_<?php echo $post->ID; ?> glf_update_single" <?php echo $style; ?>>updated by <a href="javascript:void(0);<?php echo home_url('/?term='.$user_data->data->user_login.'&tax=filter_by_author'); ?>" data_value="<?php echo $user_login; ?>" onclick="inner_page_cat_filter('<?php echo get_author_name( $post->post_author );?>', 'post_filter_by_author', 'cat_post', '', '');"><?php echo get_author_name( $post->post_author );?></a></p>
                                <?php
                                }?>						
                             </div>
                           <div class="flag_edit_sec">
                           <?php
							if( !$image_video ) { ?>
						<span class="single_edit_icon"><?php
							edit_post_link('<img src="'.$url_theme.'/assets/images/pencil_sign.png" width="35" height="35"/>', '<span class="post-edit" id="'.$postid.'">', '</span>');
							?>
						</span><?php
						} ?>
						
					   <?php
						if( $image_video ) { ?>
						<span class="single_edit_icon"><?php
							edit_post_link('<img src="'.$url_theme.'/assets/images/pencil_sign.png" width="35" height="35"/>', '<span class="post-edit" id="'.$postid.'">', '</span>');
							?>
						</span><?php
						} ?><?php
						if(!is_user_logged_in()){?>
                        	<a href="<?php echo home_url('/login');?>"><img src="<?php echo $url_theme.'/assets/images/pencil_sign.png';?>" width="35" height="35"/></a><?php
						}?>
                       </div>						 
                       </div>
                       <div class="flag_as_approperiate_ad_section">
                          <div id="flag_message_<?php echo $post->ID;?>" style="display:none;"></div><?php
							// flag button starts
							$post_status = $wpdb->get_var("SELECT post_status FROM wp_posts where ID= ".$post->ID."" );
							if( $count_flag_report==0 && $post_status != 'draft' ) { ?>
								<div id="flag_post_report_<?php echo $post->ID; ?>">		
									<a id="flag_post" href="javascript:void(0);" onclick="fnflagpost(<?php echo $post->ID; ?>);">
										<img src="<?php echo get_template_directory_uri();?>/assets/images/flag-icon.png" width="16" height="16"/>
										Flag Inappropriate
									</a>
									<form name="form2" method="post" action="" id="flag_post_form_<?php echo $post->ID; ?>">
										<input name="user_ip_address" type="hidden" value="<?php echo ($user_ip) ? $user_ip : ''; ?>" />
										<input name="post_id" type="hidden" value="<?php echo $post->ID; ?>" />
									</form>
								</div><?php
							} 
                            $post_id = $post->ID;
							// Flag advertisement
							if( $user_ip && $post_id ) {
								// user ip count to remove flag button for particular post
								$count_flag_report = $wpdb->get_var("SELECT count(user_ip_address) FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id= ".$post_id." AND user_ip_address='".$user_ip."'");
								
								// user ip count to restore post
								$count_flag_report_post = $wpdb->get_var("SELECT count(article_id) FROM ".$wpdb->prefix."hyroglf_advertisement_post_revision_restore WHERE article_id = ".$post_id);
							}
                                
							$post_status = $wpdb->get_var("SELECT post_status FROM wp_posts where ID= ".$post_id."" );
							$post_flag_as_advertisement = '';
							//echo $count_flag_report.' - '.$post_status;
							if( $count_flag_report == 0 && $post_status != 'draft' ) { ?>
								<div class="flag_as_adverstiment_message_<?php echo $post_id; ?> flg_advertisement" style="display:none;"></div>
								<div class="flag_adver_single" id="flag_advertisement_post_report_<?php echo $post_id; ?>">
									<a id="flag_post" href="javascript:void(0);" onclick="flag_advertisement(<?php echo $post_id; ?>);">Flag Ad</a>
								</div><?php
									/*if( !$image_video ) { ?>
									<span class="single_edit_icon"><?php
										edit_post_link('<img src="'.$url_theme.'/assets/images/pencil_sign.png" width="35" height="35"/>', '<span class="post-edit" id="'.$postid.'">', '</span>');
										?>
									</span><?php
									} ?>
								   <?php
									if( $image_video ) { ?>
									<span class="single_edit_icon"><?php
										edit_post_link('<img src="'.$url_theme.'/assets/images/pencil_sign.png" width="35" height="35"/>', '<span class="post-edit" id="'.$postid.'">', '</span>');
										?>
									</span><?php
									}*/ ?>
							<?php
							}
                            // Flag advertisement end ?>
                            </div>
                        </div>
                       
            </div><?php			
            $post_flag_inappropriate_warning = '';
			if( $count_flag_report_for_warning > 0 && $count_flag_report == 0) { ?>
            	<div class="flag_inappropriate_popup_content flag_inappropriate_popup_content_<?php echo $post->ID; ?>">
                    <div class="flag_popup_content">
                        <!--<a class="flag_popup_content_close_action" href="javascript:void(0);" onclick="close_flag_inappropriate_popup_content(<?php echo $post->ID; ?>);">X</a>-->
                        <p>Warning! This post has been flagged as inappropriate! Do you wish to open?</p>
                        <!--<a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="close_flag_inappropriate_popup_content(<?php echo $post->ID; ?>);">Ok</a>-->
                        <a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="close_flag_inappropriate_popup_content(<?php echo $post->ID; ?>, 'open');">Yes</a>
                        <a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="close_flag_inappropriate_popup_content(<?php echo $post->ID; ?>, 'close');">No</a>
                    </div>
                </div><?php
			} else {
			} ?>
   <div style="display:none;">
        <div id="post_share_via_email_content" class="post_share_via_email_content">
            <?php post_share_via_email_content_single(); ?>            
        </div>
    </div>            
         </div>
         
        <div class="wiki_content wiki_center_section" style="display:none">
            <div class="home_content_section"></div>
        </div>
        
       <?php endwhile; ?>
      <?PHP endif; ?>
      
     <?php category_right_sidebar_for_page('single'); ?>
     
  	</div>
    </div>
    
<?php get_footer(); ?>