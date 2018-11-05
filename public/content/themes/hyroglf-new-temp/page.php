<?php
if ( is_page(edit-post) ) :
	get_header( 'header-sub' );
elseif ( is_page(login) ) :
	get_header( 'header-sub' );
else :
	get_header();
endif;
?>

	<?php category_left_sidebar_for_page('page'); ?>

        <?php header_section(); ?>

	<?PHP
	if (have_posts()) : while (have_posts()) : the_post();
		$data = get_post_meta($post->ID);
		$post = $wp_query->get_queried_object();

		if(is_user_logged_in() && $post->post_name == 'login' ){
			$url = home_url();
			if( isset( $_GET['redirect_to'] ) ) {
				$url = urldecode($_GET['redirect_to']);
			} else {
				$url = home_url();
			} ?>
			<script>
				window.location.href= '<?php echo $url; ?>';
			</script><?php
		}
		//Restrict reset password page from loged in users
		if(is_user_logged_in() && $post->post_name == 'reset-password' || is_user_logged_in() && $post->post_name == 'sign-up' ){
			$url = home_url();?>
			<script>
				window.location.href= '<?php echo $url; ?>';
			</script><?php
		}
		/*if(is_user_logged_in() && $post->post_name == 'change-password' && isset($_GET['token_key'])){
			$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
				<script>
					window.location.href= '<?php echo wp_logout_url($actual_link); ?>';
				</script><?php
		}*/
		if(!isset($_GET['token_key']) || !isset($_GET['token_for'])){
			if(!is_user_logged_in() && $post->post_name == 'change-password' && !isset($_GET['token_key'])){
				$url = home_url();
				if( isset( $_GET['redirect_to'] ) ) {
					$url = urldecode($_GET['redirect_to']);
				} else {
					$url = home_url('/login');
				} ?>
				<script>
					window.location.href= '<?php echo $url; ?>';
				</script><?php
			}
		}

		if((isset($_GET['task']) && !is_user_logged_in()) ||  (!is_user_logged_in() && $post->post_name == 'change-password') && !isset($_GET['token_key']) || (!is_user_logged_in() && $post->post_name == 'my-posts')){
				$url = home_url();
				if( isset( $_GET['redirect_to'] ) ) {
					$url = urldecode($_GET['redirect_to']);
				} else {
					$url = home_url('/login');
				} ?>
				<script>
					window.location.href= '<?php echo $url; ?>';
				</script><?php
			}
		?>

        <div class="post_single_filter_text">
                        <div class="post_single_view_content_head">
                            <div class="post_single_heade_edit_action"><?php
                                /*if( is_user_logged_in() ) { ?>
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
										<i class="fa fa-plus fa-5x"></i>
                                    </a>
                                     <?php popup_category_image_content(); ?>
                                </div>
                            </div>

                        </div>
                    </div>

        <?php
		if( isset($_GET['task']) && $_GET['task'] == 'edit' || $_GET['task'] == 'new' || $post->post_name == 'my-posts' ) {
			$cat_terms = get_terms('category');
			//echo '<pre>'; print_r($cat_terms);
			if( $cat_terms ) {
				foreach( $cat_terms as $term ) {
					//echo '<pre>'; print_r($term);
					$term_id = $term->term_id;
					$allow = get_option('category_'.$term_id.'_edit_title');

					if( isset( $allow[0] ) && $allow[0] == 'yes' ) {
						echo '<input type="hidden" name="allow_cat_id[]" id="allow_cat_id_'.$term_id.'" value="'.$term_id.'">';
					}
				}
			}
		}

		$edit = 'no';
		if( isset( $_GET['task'] ) && $_GET['task'] == 'edit' ) {
			$edit = 'yes';
		} else {
			$edit = 'no';
		}

		echo '<input type="hidden" name="find_add_or_edit" id="find_add_or_edit" value="'.$edit.'" />';

		if( isset($_GET['task']) && $_GET['task'] == 'edit' || $post->post_name == 'my-posts' ) { ?>
                <div class="content_wrapper hdtopmargin"><?php
                    if(  isset($_GET['task']) && $_GET['task'] == 'new' ) {
                        $text = "Editor";
                    } else if(  isset($_GET['task']) && $_GET['task'] == 'edit' ) {
                        $text = "Editor";
                    }
                    if( isset($_GET['task']) && $_GET['task'] == 'edit' || $_GET['task'] == 'new') { ?>
                        <div class="post_new_and_edit_section" >
														<span class="single_page_pencil_icon_left"><i class="fa fa-pencil fa-5x"></i></span>
                            <span class="single_page_header_content"><h3><?php echo $text; ?></h3></span>
                            <span class="single_page_add_icon_left"><?php //get_image('plus_sign.png', 45, 45);?></span>
                        </div>
                    <?php
                    } ?>
                    <div class="content"><?php
                        if($post->post_name== 'my-posts') { ?>
                            <div class="single_edit_action">
                                <ul><?php $url_theme = get_stylesheet_directory_uri(); ?>
                                    <?php
                                    if( !isset($_GET['task'] ) ) { ?>
                                    <li class="single_edit_icon">
																				<?php edit_post_link('<i class="fa fa-pencil fa-5x"></i>',''); ?>
                                    </li><?php
                                    }
                                    if( isset($_GET['task'] ) ) { ?>
                                    <li class="single_close_icon"><?php
                                    if( isset( $_GET['postid'] ) ) { ?>
																				<a href="<?php echo get_the_permalink($_GET['postid']); ?>"><i class="fa fa-times fa-5x"></i></a><?php
																		} else { ?>
																				<a href="javascript:void(0);"><i class="fa fa-times fa-5x"></i></a><?php
																		} ?>
                                    </li><?php
                                    } else { ?>
                                        <li class="single_close_icon">
																				<a href="javascript:void(0);"><i class="fa fa-times fa-5x"></i></a>
                                    </li><?php
                                    } ?>
                                </ul>
                            </div><?php
                        } ?>
                        <div class="entry post_single_page">
                        	<div class="error_msg_post"></div>
                            <?php the_content(); ?>
                            <?php //wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
                        </div>
                    </div>
                </div><?php
		} else { ?>

            <div class="content_wrapper hdtopmargin">

                <div class="content"><?php
					if(isset($_GET['token_key']) && is_user_logged_in() && $post->post_name == 'change-password' && $_GET['token_key']){?>
						 <script>
						 	window.location.href= '<?php echo wp_logout_url($_SERVER['REQUEST_URI']) ?>';
						 </script><?php
					}?>
                    <h2><?php
						if(isset($_GET['token_key']) && is_page('change-password') && $_GET['token_key']) {
							echo "New Password";
						} else if(!is_page('about')) {
							the_title();
						}?></h2><?php
						    if(isset($_SESSION['user_message'])){
								echo '<span class="user_success_msg">'.$_SESSION['user_message'].'</span>';
							}
							if(isset($_SESSION['delete_account_message'])){
								echo '<span class="deleted_account_message">'.$_SESSION['delete_account_message'].'</span>';
							}
							session_destroy();?><?php
						if(isset($_GET['password_changed'])){
							echo '<p class="success_msg">Password successfully changed!</p>';
						}
					if(is_page('about') || is_page('contact')){?>
                    <div class="entry show-768">
                    	<?php echo apply_filters( 'hyroglf_login_message', ''); ?>

                        <?php
                        $status_base_1 = base64_decode($_GET['status']);
						$status = base64_decode($status_base_1);

						$activation_key_base_1 = base64_decode($_GET['access_key']);
						$user_id = base64_decode($activation_key_base_1);

						$user_account_status = get_user_meta( $user_id, 'user_account_status', true );
						$user_temp_pass = get_user_meta( $user_id, 'user_temp_pass', true );
						if( $status == 'inactive' || $_GET['status'] == 'inactive' && isset( $_GET['access_key'] ) ) {
							echo apply_filters('user_register_active_notice', '<p class="success_msg">Registration complete! Feel free to login!</p>');
						} ?>

                        <?php the_content();
						if(is_page('contact')){
								echo do_shortcode('[contact-form-7 id="4820" title="Contact form 1"]');
						}?>

                    </div>
                    <div class="entry show-767">
                    	<?php echo apply_filters( 'hyroglf_login_message', ''); ?>

                        <?php
                        $status_base_1 = base64_decode($_GET['status']);
						$status = base64_decode($status_base_1);

						$activation_key_base_1 = base64_decode($_GET['access_key']);
						$user_id = base64_decode($activation_key_base_1);

						$user_account_status = get_user_meta( $user_id, 'user_account_status', true );
						$user_temp_pass = get_user_meta( $user_id, 'user_temp_pass', true );
						if( $status == 'inactive' || $_GET['status'] == 'inactive' && isset( $_GET['access_key'] ) ) {
							echo apply_filters('user_register_active_notice', '<p class="success_msg">Registration complete! Feel free to login!</p>');
						} ?>

                        <?php  if($data['about_page_content_mobile'][0]) {
							echo  apply_filters('the_content',$data['about_page_content_mobile'][0]);
						}?><?php
						if(is_page('contact')){
								echo do_shortcode('[contact-form-7 id="4820" title="Contact form 1"]');
						}?>

                    </div><?php
					}
					else{?>
                        <div class="entry">
                            <?php echo apply_filters( 'hyroglf_login_message', ''); ?>

                            <?php
							if(isset($_GET['status'])){
								$status_base_1 = base64_decode($_GET['status']);
								$status = base64_decode($status_base_1);
							}
                            if(isset($_GET['access_key'])){
								$activation_key_base_1 = base64_decode($_GET['access_key']);
								$user_id = base64_decode($activation_key_base_1);
							}
                            if(isset($user_id)){
								$user_account_status = get_user_meta( $user_id, 'user_account_status', true );
								$user_temp_pass = get_user_meta( $user_id, 'user_temp_pass', true );
								if($status == 'inactive' ||  isset($_GET['status']) &&  $_GET['status'] == 'inactive' && isset( $_GET['access_key'] ) ) {
									echo apply_filters('user_register_active_notice', '<p class="success_msg">Registration complete! Feel free to login!</p>');
								}
							}	?>

                              <?php the_content(); ?>



                        </div><?php
					}?>

                </div>
            </div><?php
		} ?>

        <div class="wiki_content wiki_center_section" style="display:none;">
            <div class="home_content_section"></div>
        </div>

	<?php //comments_template(); ?>

	<?php endwhile;
		endif; ?>

    <?php category_right_sidebar_for_page('page'); ?>

    </div> <!-- this div open in header -->

<?php get_footer(); ?>
