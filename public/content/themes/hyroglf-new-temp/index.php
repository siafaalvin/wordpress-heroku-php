<?php get_header(); ?>
<div ng-app="hyroglf_app" ng-controller ="authCtrl">
        <!--<div class="wiki_topbar_right header_logo_bottom">
            <ul>
                <li class="search_box">
                    <div class="text_box">
                       <?php /* get_search_form(); ?>
                    </div>
                </li>
            </ul>
            <span class="random_list"><?php
                if( is_front_page() ) { ?>
                    <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '');" class=" random_icon_des">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" />
                    </a><?php
                } else { ?>
                    <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '');" class=" random_icon_des">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" />
                    </a><?php
                } */?>

            </span>
        </div>-->

	<?php category_left_sidebar('front_page'); ?>
    <div class="wiki_content wiki_center_section hdtopmargin">

    	<?php header_section(); ?>

        <div class="home_content_section" style="display:none;">

        	<div class="ajax_filter_content">

                    <div class="filter_post_img cat_image_header_sec" ng-bind-html-unsafe="selected_category_img"></div>

                    <div class="post_filter_text">
                        <div class="post_view_content_head">

                            <h3 class="ajax_post_filter_action">{{selected_category}}</h3>

                            <div class="post_heade_edit_action"><?php
                                if( is_user_logged_in() ) { ?>
                                   <!-- <a href="<?php //echo home_url('/my-posts/?task=new');?>" onclick="page_redirect(<?php //echo "'".home_url('/my-posts/?task=new')."'";?>)">-->
                                    <a class="new-post" href="<?php echo home_url('/my-posts/?task=new');?>" onclick="page_redirect(<?php echo "'".home_url('/my-posts/?task=new')."'";?>)">Add New Post
										<?php //get_image('pencil_sign.png', 60, 60);?>
										<i class="fa fa-edit fa-1x"></i>
									</a><?php
                                } else { ?>

                                      <a class="register-txt" href="<?php echo home_url('/login');?>" onclick="page_redirect(<?php echo "'".home_url('/my-posts/?task=new')."'";?>)">ADD A POST
										<?php //get_image('pencil_sign.png', 60, 60);?>
										<i class="fas fa-edit fa-1x" title="Click here to login"></i>
                                    </a><?php
                                } ?>

                            </div>

                        </div>
                         <!--<div class="wiki_topbar_right show-767">
                            <ul>
                                <li class="search_box">
                                    <div class="text_box">
                                       <?php /* get_search_form(); ?>
                                    </div>
                                </li>
                            </ul>
                            <span class="random_list"><?php
                                if( is_front_page() ) { ?>
                                    <a href="javascript:void(0);" ng-click="random_post($event);" class=" random_icon_des">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" />
                                    </a><?php
                                } else { ?>
                                    <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '');" class=" random_icon_des">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" />
                                    </a><?php
                                } */?>


                            </span>
                        </div>-->

                        <div class="sort_section" ng-if="most_view == 0">

                        <div class="sort_content">
                        <div class="sorting_left_filter">
                            <div class="sort_date">
                                <a class="active asc_desc_order sort_asc_order {{order_class}}" href="javascript:void(0);" ng-click="filter_ass_desc(posts);">Date</a>
                            </div>
                             <span class="sorting_text">Source Rating Filters:</span>
                        </div>
                             <div class="sort_drop_down_action">
                               	 <div class="infor_drop_down_sort_section">
                                    <div class="cs-select-c cs-skin-elastic-c" tabindex="0">
                                        <span class="cs-placeholder-c" onclick="remove_old_select_option(this);">Informative? (All)</span>
                                        <div class="cs-options-c">
                                            <ul>
                                                 <li onclick="dropdown_filter('all', 'Informative? (All)', 'infor')">
                                                    <span>Informative? (All)</span>
                                                 </li>
                                                 <li onclick="dropdown_filter('very', 'Very', 'infor')">
                                                    <span>very</span>
                                                 </li>
                                                 <li onclick="dropdown_filter('somewhat', 'Somewhat', 'infor')">
                                                    <span>somewhat</span>
                                                 </li>
                                                 <li onclick="dropdown_filter('not_really', 'Not Really', 'infor')">
                                                    <span>not really</span>
                                                 </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="bias_drop_down_sort">
                                    <div class="bias_drop_down_sort_section">
                                        <div class="cs-select-c cs-skin-elastic-c" tabindex="0">
                                            <span class="cs-placeholder-c" onclick="remove_old_select_option(this);">Bias? (All)</span>
                                            <div class="cs-options-c">
                                                <ul>
                                                    <li onclick="dropdown_filter('all', 'Bias? (All)', 'bias');">
                                                        <span>Bias? (All)</span>
                                                    </li>
                                                    <li onclick="dropdown_filter('liberal', 'Liberal', 'bias');">
                                                        <span>liberal</span>
                                                    </li>
                                                    <li onclick="dropdown_filter('neutral', 'Neutral', 'bias');">
                                                        <span>neutral</span>
                                                    </li>
                                                    <li onclick="dropdown_filter('conservative', 'Conservative', 'bias');">
                                                        <span>conservative</span>
                                                    </li>
                                                </ul>
                                             </div>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>



                    <div class="sort_tab_sections most_viewed_action" ng-if="most_view == 1">
                    	<a id="tab_action1" class="tab_action tab1 active" href="javascript:void(0);" ng-click="most_viewed_filter('today', 'tab1');" >Today</a>
                    	<a id="tab_action2" class="tab_action tab2" href="javascript:void(0);" ng-click="most_viewed_filter('this_week', 'tab2');">This Week</a>
                    	<a id="tab_action3" class="tab_action tab3" href="javascript:void(0);" ng-click="most_viewed_filter('this_month', 'tab3');">This Month</a>
                    </div>
                </div>
	    	<div data-ng-view="" id="ng-view" class="slide-animation"></div>
        </div>
    </div>

   <?php category_right_sidebar('front_page'); ?>

   <a href="" id="post_filter_by_search"></a>
   <a href="" id="dropdown_filter"></a>
   <a href="" id="post_filter_by_author"></a>
   <a href="" id="flag_advertisement_set"></a>
   <a href="" id="cat_post_filter_click"></a>
   <a href="" id="scroll_load_post"></a>
   <a href="" id="scroll_load_categories"></a>

   <div style="display:none;">
        <div id="post_share_via_email_content" class="post_share_via_email_content">
        	<?php post_share_via_email_content(); ?>
        </div>
    </div>

</div>
<?php get_footer(); ?>
