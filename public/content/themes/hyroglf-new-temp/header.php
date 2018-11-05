<!DOCTYPE html>
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html >
<!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php if( isset( $_GET['task'] ) && $_GET['task'] == 'new' ) {
		echo 'Editor - '; bloginfo('name');
	} else if( isset( $_GET['task'] ) && $_GET['task'] == 'edit' ) {
		echo 'Editor - '; bloginfo('name');
	} else {
		if( is_front_page() ) {
			bloginfo('name'); //bloginfo('description');
		} else {
			global $post;
			$page_title = '';
			if(empty($post->post_title) || $post->post_title == 'c' || $post->post_name == 'c'){
				$page_title = 'Post Deleted';
			}
			if($post->post_title == 'Free Forever'){
				$post->post_title = 'Sign Up';
			}else if($post->post_title == 'Wiki page'){
				$post->post_title = 'Editor';
			}else if($post->post_title == 'Change Password'){
				$post->post_title = 'Change Password';
			}
			echo (!empty($page_title) ? $page_title : ucfirst($post->post_title)); echo ' - '; bloginfo('name');
		}
	} ?></title><?php
	if(is_archive()){?>
    	<meta name="robots" content="noindex,follow"/><?php
	}?>
    <meta name="description" content="description of your website/webpage, make sure you use keywords!">

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri();?>/assets/images/favicon/apple-touch-icon.png?v=2">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri();?>/apple-touch-icon.png?v=2">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri();?>/assets/images/favicon/favicon-32x32.png?v=2" sizes="32x32">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri();?>/assets/images/favicon/favicon-16x16.png?v=2" sizes="16x16">
    <link rel="manifest" href="<?php echo get_template_directory_uri();?>/assets/images/favicon/manifest.json">
    <link rel="mask-icon" href="<?php echo get_template_directory_uri();?>/assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri();?>/assets/images/favicon/favicon.ico?v=2">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <meta name="msapplication-config" content="<?php echo get_template_directory_uri();?>/assets/images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />

    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
   <!-- <link rel="shortcut icon" href="<?php //echo of_get_option('favicon'); ?>" sizes="32x32"> -->
<!--    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">-->
<!-- Hiding the Browser's User Interface for iOS & Android -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="mobile-web-app-capable" content="yes">

    <!--<meta property="og:image" content="<?php echo of_get_option('ss'); ?>" />
    <meta name="twitter:card" value="summary" />
	<meta name="twitter:site" value="@" />
    <meta name="twitter:creator" content="@" />
    <meta name="twitter:title" content="<?php echo bloginfo('name');?>" />
    <meta name="twitter:description" content="custom description">
    <meta name="twitter:url" content="<?php echo home_url();?>/" />
    <meta name="twitter:image" content="<?php echo of_get_option('ss'); ?>">-->

<!--<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> onload="current_system_time();"><?php do_action('website_before'); ?><?php
if(is_archive()){
	$url = home_url('/');
	wp_redirect( $url );
	exit;
}?>
<input type="hidden" name="hid_base_url" id="hid_base_url" value="<?php echo get_stylesheet_directory_uri();?>">
<div class="page-wrapper">
  <div class="wiki_wrapper">
    <header><?php
    	if(of_get_option('logo')){?>
						<div class="post_filter_text header_bottom_logo_search">
							<!-- <ul class="carousel">
								<li class="carousel-cell"><a href="#"><span class="icon-cat-news" style="font-size:2em;"></span>News</a></li>
								<li class="carousel-cell"><a href="#"><span class="icon-cat-pol" style="font-size:2em;"></span>Politics</a></li>
								<li class="carousel-cell"><a href="#"><span class="icon-cat-pop" style="font-size:2em;"></span>Popular</a></li>
								<li class="carousel-cell"><a href="#"></span>More...</a></li>
							</ul> -->
						</div>
						<div class="logo">
                <a class="title-text" href="<?php echo home_url('/'); ?>">HYROGLF</a>
								<div class="beta-text">Beta</div>
								<div class="motto">Summarizing Your World</div>
								<!-- Begin search form -->
               <div class="post_filter_text"><?php
									 if( is_front_page() ) { ?>
								 <!-- ADD DYNAMIC CATEGORY TITLE HERE-->
								<button class="ocs-trigger ocs-toggle ocs-toggle-menu"><p class="dyna-label">Explore</p><i class="fas fa-bars fa-4x dyna-label"></i></button>
								<!-- END DYNAMIC CATEGORY TITLE HERE-->
								<?php
								} else { ?>
								<button class="ocs-trigger ocs-toggle ocs-toggle-menu ocs-sub"><p class="dyna-label">Explore</p><i class="fas fa-bars fa-4x dyna-label"></i></button>
								<?php
						} ?>
                    <div class="wiki_topbar_right header_logo_bottom">
                        <ul>
                            <li class="search_box">
                                <div class="text_box">
                                   <?php get_search_form(); ?>
                                </div>
                            </li>
                        </ul>
                        <span class="random_list"><?php
                            if( is_front_page() ) { ?>
                                <a href="javascript:void(0);" ng-click="random_post($event);" class="random_icon_des">Random Post<i class="fas fa-dice fa-1x" title="random article generator"></i>
                                    <!--<img src="<questionmarkphp echo get_template_directory_uri(); ?>/assets/images/random_icon.png" /> CHANGE THIS TO FONTAWESOME -->
                                </a><?php
                            } else { ?>
                                <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '');" class="random_icon_des_none">
                                    <i class="fas fa-dice fa-4x" title="random article generator"></i>
                                </a><?php
                            } ?>

                        </span>
                    </div>
                </div>
								<!--  END search form-->
								<!--  category label-->

							 <!--  END category label-->
                <div class="header_right"><?php
        	if(!is_user_logged_in()){?>
            <ul class="my_account row">
                	<li><a class="login-trigger" href="<?php echo home_url('/login/'); ?>" onclick="page_redirect(<?php echo "'".home_url('/login/')."'"; ?>)">Login</a></li>
                  <li><a class="signup-trigger" href="<?php echo home_url('/sign-up/'); ?>" onclick="page_redirect(<?php echo "'".home_url('/sign-up/')."'"; ?>)">Sign up</a></li>
                  <!-- <li><a class="ocs-trigger ocs-toggle ocs-toggle-about-bar">About</a></li> -->
            </ul><?php

			} else {
				$current_user = wp_get_current_user();?>
                <ul class="my_account row">
                	<li><a class="user-trigger" href = "<?php echo home_url('my-profile'); ?>"><?php echo $current_user->data->display_name;?></a></li>
                	<li><a class="logout-trigger" href="<?php echo wp_logout_url( home_url() ); ?>" >Log out</a></li>
									<!-- <li><a class="ocs-trigger ocs-toggle ocs-toggle-about-bar">About</a></li> -->
                </ul><?php
			} ?>

        </div>
            </div>
            <?php
        } ?>


        <!--<div class="header_advertisement_logo desktop_advertisement_logo show-768"><?php
		$meta_query    = array(
						array(
							'key' => 'featured_logo',
							'value' => serialize(array('yes'))
						)
						);
		$args = array(
			'post_type' 		=> 'advertisement_logo',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> 1,
			'orderby'			=> 'desc',
			'order'				=> 'date',
			'meta_query' 		=> $meta_query,
			);
		$query = new WP_Query( $args );
		if ($query->have_posts()) {
			while ( $query->have_posts() ) : $query->the_post(); global $wpdb, $post;
				$post_meta = get_post_meta($post->ID); ?>
				<a href = "<?php echo ($post_meta['logo_link'][0]) ? $post_meta['logo_link'][0] : of_get_option('logo_url'); ?>" target = "_blank">
					<?php the_post_thumbnail(array(270)); ?>
				</a><?php
			endwhile; wp_reset_query();
		} ?>
       </div>-->
    </header>
