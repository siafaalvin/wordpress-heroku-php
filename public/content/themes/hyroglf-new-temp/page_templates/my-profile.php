
<?php
/**
 * Template Name: My profile
 *
 * @package WordPress
 * @subpackage Hyroglf
 */

get_header(); ?>
<div id="check_password_popup" style="display:none;">
     <div class="form_check_pass_hyroglf" id="form_check_pass_hyroglf">
     <h6>Confirm Update</h6>
         <div id="frm_check_password_close"><img src="<?php echo get_template_directory_uri();?>/assets/images/close_arrow.png"></div>
         <form name="check_password_frm" method="POST" id="check_password_frm">
             <label>Re-enter password to save changes.</label>
             <input type="password" name="check_password" id="check_password" value=""/>
           <!--  <a href="<?php //echo home_url('/reset-password');?>" id="frm_forget_password">Change Password</a>-->
             <div class="form_check_pass_hyroglf_button">
                 <!--<a href="javascript:void(0);" id="frm_check_pass_cancel" class="btn">Cancel</a>-->
                 <button type="submit" name="frm_check_pass_submit" id="frm_check_pass_submit" class="btn" value="submit">Save</button>
             </div>
         </form>
         <?php
         if(isset($_POST['frm_check_pass_submit'])){
         $current_user = wp_get_current_user();
         $username  = $current_user->user_login;
         $pass = $_POST['check_password'];
         $user = get_user_by( 'login', $username );
            if ( $user && wp_check_password( $pass, $user->data->user_pass, $user->ID ) ) {
                $output = "success";
            } else {
                $output = "nope";
            }
         }?>
     </div>
 </div>
<div class="content_wrapper hdtopmargin profile-page" ng-app="hyroglf_app" ng-controller="profileCtrl">
<?php category_left_sidebar_for_page('page'); ?>
<?php header_section();
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="content profile_page">
        <h2><?php the_title( ); ?></h2>
        		<div class="post_view_content_head">
                    <div class="post_heade_edit_action">
                        <div class="post_cat_view_popup">
                            <a class="filter_cat_popup_action" id ="filter_cat_popup" href="javascript:void(0);" onclick="view_all_list_cat('all');">
                                <?php get_image('plus_sign.png', 60, 60);?>
                            </a>
                             <?php popup_category_image_profile_content(); ?>
                        </div>
                    </div>
                </div>
                <span class="error_msg_user" style="display:none;"></span>
                <span class="error_msg" style="display:none;"></span>
                <span class="success_msg" style="display:none;"></span>
            <div class="myprofile_form_content"><?php
                if(is_user_logged_in()){
                    the_content();
                }?>
                 <!--<a class = "delete_account" href="<?php //echo home_url('/?DeleteMyAccount=delete_account'); ?>">Delete my account</a>-->
            </div>
        </div>

     <input type="hidden" name="hid_check_password" id="hid_check_password" value="<?php echo $output;?>"/>

     <div id="delete_account_popup_content" style="display:none;">
     <div class="form_check_pass_delete_hyroglf" id="form_check_pass_delete_hyroglf" >
     <h6>Delete Account?</h6>
         <a href="javascript:void(0);"  id="frm_check_password_delete_close"><img src="<?php echo get_template_directory_uri();?>/assets/images/close_arrow.png"></a>
         <form name="check_password_delete_frm" method="POST" id="check_password_delete_frm">
             <label><span>We're sorry you're leaving! Enter your password below to confirm.</span></label>
             <input type="password" name="delete_check_password" id="delete_check_password" value=""/>
            <!--<a href="<?php echo home_url('/forgot-password');?>" id="delete_frm_forget_password">Forget password?</a>-->
             <div class="form_check_pass_hyroglf_button">
                 <!--<a href="javascript:void(0);" id="delete_frm_check_pass_cancel" class="btn">Cancel</a>-->
                 <a class="delete_account" href="javascript:void(0);" ng-click="fndeleteaccount();">Delete account</a>
             </div>
         </form>
       </div>
     </div><?php
	 $current_user = wp_get_current_user();
     $username  = $current_user->user_login;?>
     <input type="hidden" name="filter_taxonomy" id="filter_taxonomy" value="<?php echo 'post_filter_by_author';?>"/>
     <input type="hidden" name="filter_category" id="filter_category" value="<?php echo $username;?>"/>
     <div class="home_content_section my-profile" style="display:none;">
     <div class="page-content post" >

       <div class="list_of_post_content_section" ng-repeat="post in data">
         <div class="post_content post-{{post.ID}}">
            <div class="post_content_{{post.ID}}">
            <span>{{data.text}}</span>
        	<div class="rating_vote_success_msg success_list_rating_vote_{{post.post_id}}" style="display:none;">You rated..</div>
        	<div class="post_title"  ng-if="!data.text">
            <span class="post_fav_icons" ng-bind-html-unsafe="post.post_meta.post_ref_link_favicon"></span>
            <div class="feed_post_refer_link">
            <span class="" ng-bind-html-unsafe="post.favorite_icon"></span>
             <p class="glf_update_date_{{post.post_id}} dates_ago" style="display:inline-block;" ng-if="post.post_meta.last_edited_user"> {{post.post_meta.last_edited_user}}<span ng-if="post.post_meta.last_edited_user_system"> at {{post.post_meta.last_edited_user_local}}</span><span ng-if="!post.post_meta.last_edited_user_system"> at {{post.post_meta.last_edited_user_local}}</span></p>
             <div class="post_feed_content_{{post.post_id}}">
                        <h4><a href="javascript:void(0);" data-value="{{post.post_link}}" onclick="fnpost_link(this);" class="">{{post.post_title}}</a></h4>
                        <span class="" ng-bind-html-unsafe="post.favirote_icon"></span>


                    <div class="post_refer_link">
                        <a href="{{post.post_meta.reference_link}}" target="_blank"><span ng-if="!post.post_meta.source_name">Source Published</span></a>
                        <a href="{{post.post_meta.reference_link}}" target="_blank"><span ng-if="post.post_meta.source_name">{{post.post_meta.source_name}}</span></a>
                        <div id="post_read_time_section_{{post.post_id}}" class="post_read_time_section" ng-if="post.post_meta.post_read_time || post.post_meta.post_read_sec">
                           <!-- <span ng-if="window_size == 0">({{post.post_meta.post_read_time}} {{post.post_meta.post_read_sec}} read/watch) </span>-->
                            <span>({{post.post_meta.post_read_time}} {{post.post_meta.post_read_sec}} read) </span>
                        </div>
                        <div class="list_post_rating_section list_post_rating_section_{{post.post_id}}">
                            <div class="post_reting_section vote_result_section" >
                                <a href="javascript:void(0);" class="post_rating" ng-if="user_access == 1 && post.informative_high_vote == '' && user_access == 1 && post.bias_high_vote == ''">Rate the source </a>

                                <a href="javascript:void(0);" class="post_rating" ng-if="user_access == 0 && post.informative_high_vote == 0 && post.bias_high_vote == 0">Rate the source</a>

                                    <div class="post_voting_section" id="post_voting_{{post.post_id}}">
                                        <div class="voting_option">
                                            <span ng-if="user_access == 0" class="sign_up_source"><b><a href="sign-up">sign up</a> or <a href="login">login</a> to rate the source</b></span>

                                            <form name="form_{{post.post_id}}" method="post" action="" id="user_infer_bias_voting_form_{{post.post_id}}" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0 && post.voting_post_user_count_bias == 0">
                                                <div class="infermative_tab">

                                                    <span>Informative?</span>

                                                    <a class="rating_option infermative_action infermative_action_show infermative_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'show');">
                                                        <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                        <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                    </a>

                                                    <a class="rating_option infermative_action_close infermative_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'close');">
                                                    <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                        <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                    </a>

                                                    <div class="infermative_option infermative_option_{{post.post_id}}" style="display:none;">
                                                        <select name="informative_select" id="informative_select" class="rating_options_select" ng-model="voting.informative">
                                                            <option value="" disabled="disabled">Select</option>
                                                            <option value="very" data-id="{{post.post_id}}">very</option>
                                                            <option value="somewhat" data-id="{{post.post_id}}">somewhat</option>
                                                            <option value="not_really" data-id="{{post.post_id}}">not really</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="bias_tab">

                                                    <span>Bias?</span>

                                                    <a class="rating_option bias_action bias_action_show bias_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'show');">
                                                    <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                        <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                    </a>

                                                    <a class="rating_option bias_action_close bias_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'close');" style="display:none;">
                                                    <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                        <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                    </a>

                                                    <div class="bias_option bias_option_{{post.post_id}}" style="display:none;">
                                                        <select name="bias_select" id="bias_select" class="rating_options_select" ng-model="voting.bias">
                                                            <option value="" disabled="disabled">Select</option>
                                                            <option value="liberal">liberal</option>
                                                            <option value="neutral">neutral</option>
                                                            <option value="conservative">conservative</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="post_rating_action">
                                                <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                    <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                        </div>
                        <div class="infermative_vote_result_section vote_result_section" ng-if="post.informative_high_vote">
                            <a href="javascript:void(0);" ng-mouseover="display_voting(post, 'infermative');" ng-mouseout="hide_voting(post, 'infermative');">
                            <span ng-repeat="post_informative_vote in post.informative_high_vote">
                                {{post_informative_vote}}
                            </span> informative</a>
                            <span ng-if="post.informative_high_vote && post.bias_high_vote">and</span>
                            <div class="post_voting_section" id="post_infermative_voting_{{post.post_id}}">
                                <div class="voting_option_values">

                                    <span class="informative_vote_results_title_{{post.post_id}}">Informative</span>

                                    <a href="javascript:void(0);" class="rating_option infermative_action" ng-click="rating_option(post, 'infermative', 'single');"></a>
                                    <a href="javascript:void(0);" class="rating_option infermative_action_close" ng-click="rating_option(post, 'infermative', 'single');"></a>
                                    <ul class="informative_vote_results informative_vote_results_{{post.post_id}}">
                                        <li ng-if="post.informative_rating_count.very.count">
                                            <label>very</label>
                                            <span> - {{post.informative_rating_count.very.count}} ({{post.informative_rating_count.very.vote}})</span>
                                        </li>
                                        <li ng-if="post.informative_rating_count.somewhat.count">
                                            <label>somewhat</label>
                                            <span> - {{post.informative_rating_count.somewhat.count}} ({{post.informative_rating_count.somewhat.vote}})</span>
                                        </li>
                                        <li ng-if="post.informative_rating_count.not_really.count">
                                            <label>not really</label>
                                            <span> - {{post.informative_rating_count.not_really.count}} ({{post.informative_rating_count.not_really.vote}})</span>
                                        </li>
                                        <li>
                                            <span ng-if="user_access == 0"  class="sign_up_source"><b><a href="sign-up">sign up</a> or <a href="login">login</a> to rate the source</b></span>
                                        </li>
                                        <li>
                                             <span ng-if="user_access == 1 && post.voting_post_user_count_infor == 0 || user_access == 1 && post.voting_post_user_count_bias == 0"> <a href="javascript:void(0);" class="post_rating" ng-click="post_rating(post, 'informative');">Rate the source</a></span>
                                        </li>
                                    </ul>

                                    <div class="informative_voting_option_tab_{{post.post_id}}" style="display:none;">
                                        <div class="" ng-if="user_access == 1 && post.voting_post_user_count_infor != 0 || user_access == 1 && post.voting_post_user_count_bias != 0">
                                            <form name="form_{{post.post_id}}" method="post" action="" id="user_infer_bias_voting_form_{{post.post_id}}">
                                            <div class="infermative_tab informative_option_tab_{{post.post_id}}" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0">
                                                <span>Informative?</span>
                                                <a class="rating_option infermative_action infermative_action_show infermative_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'show');">
                                                <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <a class="rating_option infermative_action_close infermative_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'close');">
                                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                               <!-- <img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="infermative_option infermative_option_{{post.post_id}}" style="display:none;">
                                                    <select name="informative_select" id="informative_select" class="rating_options_select" ng-model="voting.informative">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="very" data-id="{{post.post_id}}">very</option>
                                                        <option value="somewhat" data-id="{{post.post_id}}">somewhat</option>
                                                        <option value="not_really" data-id="{{post.post_id}}">not really</option>
                                                    </select>
                                                </div>

                                                <div class="post_rating_action" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0 && post.voting_post_user_count_bias == 1">
                                                    <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                    <input  type="hidden" name="post_client_date" id="post_client_date" value="{{post.post_client_date}}"/>
                                                    <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                                </div>
                                            </div>

                                            <div class="bias_tab bias_option_tab_{{post.post_id}}" ng-if="user_access == 1 && post.voting_post_user_count_bias == 0">
                                                <span>Bias?</span>
                                                <a class="rating_option bias_action bias_action_show bias_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'show');">
                                                <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>
                                                <a class="rating_option bias_action_close bias_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'close');" style="display:none;">
                                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="bias_option bias_option_{{post.post_id}}" style="display:none;">
                                                    <select name="bias_select" id="bias_select" class="rating_options_select" ng-model="voting.bias">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="liberal">liberal</option>
                                                        <option value="neutral">neutral</option>
                                                        <option value="conservative">conservative</option>
                                                    </select>
                                                </div>

                                                <div class="post_rating_action" ng-if="user_access == 1 && post.voting_post_user_count_infor == 1 && post.voting_post_user_count_bias == 0">
                                                    <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                    <input  type="hidden" name="post_client_date" id="post_client_date" value="{{post.post_client_date}}"/>
                                                    <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                                </div>
                                            </div>
                                            </form>
                                        </div>

                                        <div class="" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0 && user_access == 1 && post.voting_post_user_count_bias == 0">
                                            <form name="form_{{post.post_id}}" method="post" action="" id="user_infer_bias_voting_form_{{post.post_id}}">
                                            <div class="infermative_tab informative_option_tab_{{post.post_id}}">
                                                <span>Informative?</span>
                                                <a class="rating_option infermative_action infermative_action_show infermative_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'show');">
                                                 <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <a class="rating_option infermative_action_close infermative_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'close');">
                                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="infermative_option infermative_option_{{post.post_id}}" style="display:none;">
                                                    <select name="informative_select" id="informative_select" class="rating_options_select" ng-model="voting.informative">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="very" data-id="{{post.post_id}}">very</option>
                                                        <option value="somewhat" data-id="{{post.post_id}}">somewhat</option>
                                                        <option value="not_really" data-id="{{post.post_id}}">not really</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="bias_tab bias_option_tab_{{post.post_id}}">
                                                <span>Bias?</span>
                                                <a class="rating_option bias_action bias_action_show bias_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'show');">
                                                 <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                   <!-- <img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>
                                                <a class="rating_option bias_action_close bias_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'close');" style="display:none;">
                                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                   <!-- <img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="bias_option bias_option_{{post.post_id}}" style="display:none;">
                                                    <select name="bias_select" id="bias_select" class="rating_options_select" ng-model="voting.bias">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="liberal">liberal</option>
                                                        <option value="neutral">neutral</option>
                                                        <option value="conservative">conservative</option>
                                                    </select>
                                                </div>
                                            </div>
                                            </form>

                                             <div class="post_rating_action">
                                                <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                <input  type="hidden" name="post_client_date" id="post_client_date" value="{{post.post_client_date}}"/>
                                                <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="bias_vote_result_section vote_result_section" ng-if="post.bias_high_vote">
                            <a href="javascript:void(0);" ng-mouseover="display_voting(post, 'bias');" ng-mouseout="hide_voting(post, 'bias');">
                                <span ng-repeat="post_bias_vote in post.bias_high_vote">{{post_bias_vote}}</span> bias
                            </a>
                            <div class="post_voting_section" id="post_bias_voting_{{post.post_id}}">
                                <div class="voting_option_values">
                                    <span class="bias_vote_results_title_{{post.post_id}}">Bias?</span>
                                    <a href="javascript:void(0);" class="rating_option bias_action" ng-click="rating_option(post, 'bias', 'single');"></a>
                                    <a href="javascript:void(0);" class="rating_option bias_action_close" onclick="rating_option(post, 'bias', 'single');"></a>

                                    <ul class="bias_vote_results bias_vote_results_{{post.post_id}}">
                                        <li ng-if="post.bias_rating_count.liberal.count">
                                            <label>liberal</label>
                                            <span> - {{post.bias_rating_count.liberal.count}} ({{post.bias_rating_count.liberal.vote}})</span>
                                        </li>
                                        <li ng-if="post.bias_rating_count.neutral.count">
                                            <label>neutral</label>
                                            <span> - {{post.bias_rating_count.neutral.count}} ({{post.bias_rating_count.neutral.vote}})</span>
                                        </li>
                                        <li ng-if="post.bias_rating_count.conservative.count">
                                            <label>conservative</label>
                                            <span> - {{post.bias_rating_count.conservative.count}} ({{post.bias_rating_count.conservative.vote}})</span>
                                        </li>
                                        <li>
                                           <!-- <span ng-if="user_access == 0" class="sign_up_source"><b><a href="sign-up">sign up</a> or <a href="login">login</a> to rate the source</b></span>-->
                                           <span ng-if="user_access == 0" class="sign_up_source"><b><a href="sign-up">sign up</a> or <a href="login">login</a> to rate the source</b></span>
                                        </li>
                                        <li>
                                            <span ng-if="user_access == 1 && post.voting_post_user_count_bias == 0 || user_access == 1 && post.voting_post_user_count_infor == 0"> <a href="javascript:void(0);" class="post_rating" ng-click="post_rating(post, 'bias');">Rate the source</a></span>
                                        </li>
                                    </ul>

                                    <div class="bias_voting_option_tab_{{post.post_id}}" style="display:none;">
                                        <div class="" ng-if="user_access == 1 && post.voting_post_user_count_infor != 0 || user_access == 1 && post.voting_post_user_count_bias != 0">
                                            <form name="form_{{post.post_id}}" method="post" action="" id="user_infer_bias_voting_form_{{post.post_id}}">
                                            <div class="infermative_tab informative_option_tab_{{post.post_id}}" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0">
                                                <span>Informative?</span>
                                                <a class="rating_option infermative_action infermative_action_show infermative_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'show');">
                                                 <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <a class="rating_option infermative_action_close infermative_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'close');">
                                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="infermative_option infermative_option_{{post.post_id}}" style="display:none;">
                                                    <select name="informative_select" id="informative_select" class="rating_options_select cs-select cs-skin-elastic" ng-model="voting.informative">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="very" data-id="{{post.post_id}}">very</option>
                                                        <option value="somewhat" data-id="{{post.post_id}}">somewhat</option>
                                                        <option value="not_really" data-id="{{post.post_id}}">not really</option>
                                                    </select>
                                                </div>

                                                <div class="post_rating_action" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0 && post.voting_post_user_count_bias == 1">
                                                    <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                    <input  type="hidden" name="post_client_date" id="post_client_date" value="{{post.post_client_date}}"/>
                                                    <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                                </div>

                                            </div>

                                            <div class="bias_tab bias_option_tab_{{post.post_id}}" ng-if="user_access == 1 && post.voting_post_user_count_bias == 0">
                                                <span>Bias?</span>
                                                <a class="rating_option bias_action bias_action_show bias_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'show');">
                                                 <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                   <!-- <img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>
                                                <a class="rating_option bias_action_close bias_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'close');" style="display:none;">
                                                 <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="bias_option bias_option_{{post.post_id}}" style="display:none;">
                                                    <select name="bias_select" id="bias_select" class="rating_options_select cs-select cs-skin-elastic" ng-model="voting.bias">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="liberal">liberal</option>
                                                        <option value="neutral">neutral</option>
                                                        <option value="conservative">conservative</option>
                                                    </select>
                                                </div>

                                                <div class="post_rating_action" ng-if="user_access == 1 && post.voting_post_user_count_infor == 1 && post.voting_post_user_count_bias == 0">
                                                    <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                    <input  type="hidden" name="post_client_date" id="post_client_date" value="{{post.post_client_date}}"/>
                                                    <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                                </div>

                                            </div>
                                            </form>
                                        </div>

                                        <div class="" ng-if="user_access == 1 && post.voting_post_user_count_infor == 0 && user_access == 1 && post.voting_post_user_count_bias == 0">
                                            <form name="form_{{post.post_id}}" method="post" action="" id="user_infer_bias_voting_form_{{post.post_id}}">
                                            <div class="infermative_tab informative_option_tab_{{post.post_id}}">
                                                <span>Informative?</span>
                                                <a class="rating_option infermative_action infermative_action_show infermative_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'show');">
                                                <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <a class="rating_option infermative_action_close infermative_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'infermative', 'close');">
                                                 <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="infermative_option infermative_option_{{post.post_id}}" style="display:none;">
                                                    <select name="informative_select" id="informative_select" class="rating_options_select cs-select cs-skin-elastic" ng-model="voting.informative">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="very" data-id="{{post.post_id}}">very</option>
                                                        <option value="somewhat" data-id="{{post.post_id}}">somewhat</option>
                                                        <option value="not_really" data-id="{{post.post_id}}">not really</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="bias_tab bias_option_tab_{{post.post_id}}">
                                                <span>Bias?</span>
                                                <a class="rating_option bias_action bias_action_show bias_action_show_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'show');">
                                                <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="plus_image" ng-src="{{plus_image}}" onError="angular.element(this).scope().plus_image = false" alt="" width="23" height="23"/>-->
                                                </a>
                                                <a class="rating_option bias_action_close bias_action_close_{{post.post_id}}" href="javascript:void(0);" ng-click="rating_option(post, 'bias', 'close');" style="display:none;">
                                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="23" height="23">
                                                    <!--<img ng-show="close_image" ng-src="{{close_image}}" onError="angular.element(this).scope().close_image = false" alt="" width="23" height="23"/>-->
                                                </a>

                                                <div class="bias_option bias_option_{{post.post_id}}" style="display:none;">
                                                    <select name="bias_select" id="bias_select" class="rating_options_select cs-select cs-skin-elastic" ng-model="voting.bias">
                                                        <option value="" disabled="disabled">Select</option>
                                                        <option value="liberal">liberal</option>
                                                        <option value="neutral">neutral</option>
                                                        <option value="conservative">conservative</option>
                                                    </select>
                                                </div>
                                            </div>
                                            </form>

                                            <div class="post_rating_action">
                                                <input  type="hidden" name="post_id" id="post_id" value="{{post.post_id}}"/>
                                                <input  type="hidden" name="post_client_date" id="post_client_date" value="{{post.post_client_date}}"/>
                                                <a href="javascript:void(0);" id="post_submit" class="post_submit_{{post.post_id}}" ng-click="fnLoadPosts( voting,'infer_bias', post, 'both');">Submit</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                   </div>
                     </div>
                    <div class="user_vote_result_section source_publish_date{{post.post_id}}" ng-if="user_access == 1">
                         <div class="user_info_and_bias_rated_source_quick_{{post.post_id}}"></div>
                    </div>
             </div>
                    <div class="post_add_edit_section post_add_edit_section_{{post.post_id}}">
                            <a class="view_list_post" id="view_list_post_{{post.post_id}}" href="javascript:void(0);" ng-click="view_content_click($event,post);" ng-if="post.post_meta.post_multi_images == 1 || post.post_content != ''">
                                <img ng-src="{{base_url}}/assets/images/plus_sign.png " alt="" width="35" height="35">
                            </a>
                            <a class="pop_edit_text" id="list_post_edit_btn_{{post.post_id}}" href="{{post.post_edit_link}}" ng-if="post.post_meta.post_multi_images != 1 && !post.post_content" >
                                <img ng-src="{{base_url}}/assets/images/pencil_sign.png " alt="" width="35" height="35">
                             </a>
                            <a class="close_list_post" id="close_list_post_{{post.post_id}}" href="javascript:void(0);" ng-click="close_the_content_click(post);" style="display:none;">
                                <img ng-src="{{base_url}}/assets/images/close_sign.png " alt="" width="35" height="35">
                            </a>
                            <input id="post_edit_action_{{post.post_id}}" name="post_edit_action[]" value="View" type="hidden">
                    </div>
                    <p class="post_voted_date_{{post.post_id}} source_publish_date_{{post.post_id}}" style="display:none;" ng-show="window_size == 0" ng-if="user_access == 1 && post.user_voted_date">{{post.user_voted_date}}</p>

            <p class="post_modified_{{post.post_id}} source_publish_date_{{post.post_id}}" style="display:none;" ng-if="post.post_meta.your_post_mod" ng-show="window_size == 0">{{post.post_meta.your_post_mod}}<span ng-if="post.post_meta.user_post_edit_system"> at {{post.post_meta.user_post_edit_system}}</span><span ng-if="!post.post_meta.user_post_edit_system"> at {{post.post_meta.your_post_mod_local}}</span></p>
            <p class="post_modified_{{post.post_id}} source_publish_date_{{post.post_id}}" style="display:none;" ng-if="post.post_meta.your_post_created" ng-show="window_size == 0">{{post.post_meta.your_post_created}}<span ng-if="post.post_meta.user_post_create_system"> at {{post.post_meta.user_post_create_system}}</span><span ng-if="!post.post_meta.user_post_create_system"> at {{post.post_meta.your_post_mod_local}}</span></p>
            <p class="user_vote_content_{{post.post_id}}"  ng-if="user_access == 1 && post.user_vote_content" ng-bind-html-unsafe="post.user_vote_content" ng-show="window_size == 0"></p>
            <p class="post_user_posted_fav_{{post.post_id}}" ng-if="user_access == 1 && post.user_posted_fav_date" ng-bind-html-unsafe="post.user_posted_fav_date" ng-show="window_size == 0"></p>
            <div class="post_terms post_terms_{{post.post_id}}">
                	<div class="post_of_list_cat_section">
                    	<div class="post_of_cat_list post_of_cat_list_{{post.post_id}}">
                        	<ul class="post_categories_list">
                            	<li class="post_cat_list post_cat_list_{{post.post_id}}" ng-repeat="cat in post.post_cat">
                                	<a href="javascript:void(0);" class="post_cat_name post_cat_name-{{cat.term_id}}" data-value="{{cat.slug}}" ng-click="cat_tag_post_filter_click($event, 'category', cat, index)">{{cat.name}}</a>
                                </li>
                            </ul>
                            <div class="post_of_cat_list source_publish_date_{{post.post_id}} sourcetopspace" ng-if="post.post_meta.publish_date_news">
                                <ul>
                                    <li>Source Published {{post.post_meta.publish_date_news}}</li>
                                </ul>
                            </div>
                            <ul class="tags" ng-if="window_size == 0">
                                <li class="list_tag" ng-repeat="tags in post.post_tag" ng-if="tags.term_id">
                                	<a href="javascript:void(0);" class="post_cat_name post_cat_name-{{tags.term_id}}" data-value="{{tags.slug}}" ng-click="cat_tag_post_filter_click($event, 'post_tag', tags, index)">{{tags.name}}</a>
                                </li>
                            </ul>
                       </div>
                    </div>
                </div>
                <!-- // Post share section // -->
                <div class="post_share_section post_share_section_{{post.post_id}}" style="display:none;">
                    <!--<a href="javascript:void(0);" class="post_share_action"><i class="fa fa-share-square-o"></i>Share</a>-->
                    <div class="post_share_icons" >
                   <!--  <div class="" ng-bind-html="post.share_list"></div> -->
                       <div class="crunchify-social">
                           <a class="crunchify-link crunchify-facebook" href="javascript:void(0);" data-value="https://www.facebook.com/sharer.php?u={{post.post_link}}&t={{post.post_title}}&description=Free summaries (up to 80 words) and source ratings. Anyone can vote or write, just be honest and concise." onclick="share_this_post_click_single(this);"></a>
                           <a class="crunchify-link crunchify-twitter" href="javascript:void(0);" data-value="https://twitter.com/intent/tweet?url={{post.post_link}}&text={{post.post_title}}&via=hyroglf" onclick="share_this_post_click_single(this);"></i></a>
                           <a href="javascript:void(0);" data-href="{{post.post_link}}" data-title="{{post.post_title}}" id="share_post_email" ng-click="share_this_post_email(post, '', '', 'index');"><i class="fa fa-envelope" aria-hidden="true"></i></a>
                            <a class="crunchify-link crunchify-whatsapp" href="javascript:void(0);" data-value="whatsapp://send?text={{post.post_title}} {{post.post_link}};" onclick="share_this_post_click_single(this)"></a>
                             <a class="crunchify-link crunchify-commenting" href="javascript:void(0);" data-value="sms://?&body={{post.post_link}}" onclick="share_this_post_click(this, 'sms'); return false;"></a>
                       </div>
                    </div>
                </div>
            </div>
            </div>

           <!-- <p class="glf_update_date_{{post.post_id}}" ng-if="post.post_meta.glf_update" style="display:none;" >GLF Updated - {{post.post_meta.glf_update}}</p>-->

            <div class="entry list_post_content content-{{post.post_id}} show-768" id="list_post_content_{{post.post_id}}" style="display:none;" >


                <!-- // Post content section -->
                <div class="post_content_section post_content-{{post.post_id}}" ng-if="post.post_content">
                    <div ng-bind-html-unsafe="post.post_content"></div>
                </div>
                <!-- // Post content section end // -->
                <!-- // Post Gallery section -->
                <div class="single_post_image_section single_post_image_section_{{post.post_id}}"></div>

                <div class="updated_user_section">
                 <div class="updated_user">
                	<p class="glf_update_date_{{post.post_id}} dates_ago" style="display:inline-block;" ng-bind-html-unsafe="post.post_meta.edited_user_update" ng-if="post.post_meta.edited_user_update"></p>
                </div>
                 <span class="single_edit_icon"><a class="pop_edit_tex" id="list_post_edit_btn_{{post.post_id}}" href="{{post.post_edit_link}}" style="display: none;">
                    <img ng-src="{{base_url}}/assets/images/pencil_sign.png " alt="" width="35" height="35">
                 </a></span>
                 </div>

                <!-- // Post flag inappropriate section // -->
                <div id="flag_message_{{post.post_id}}" style="display:none;">Flagged as inappropriate! Thank you!</div>
                <div id="flag_post_report_{{post.post_id}}" ng-if="post.flag_inappropriate_count == 0">
                    <a id="flag_post" href="javascript:void(0);" ng-click="fnflagpost(post);">
                        <img ng-src="{{base_url}}/assets/images/flag-icon.png " alt="" width="16" height="16">Flag Inappropriate
                    </a>
                </div>
                <!-- // Post flag inappropriate section end // -->

                <!-- // Post flag as advertisement section // -->
                <div id="flag_as_adverstiment_message_{{post.post_id}}" style="display:none;">Flagged as Ad! Thank you!</div>
                <div id="flag_advertisement_post_report_{{post.post_id}}" ng-if="post.flag_as_advertisement_count == 0">
                    <a id="flag_post" href="javascript:void(0);" data-value="{{post.post_id}}" onclick="flag_advertisement_set(this);">Flag Ad</a>
                </div>
                <!-- // Post flag as advertisement section end // -->
                <!-- <span class="single_edit_icon"><a class="pop_edit_tex" id="list_post_edit_btn_{{post.post_id}}" href="{{post.post_edit_link}}" style="display: none;">
                    <img src="{{base_url}}//assets/images/pencil_sign.png " alt="" width="35" height="35">
                 </a></span>-->

            </div>

            <div class="entry list_post_content content-{{post.post_id}} show-767" id="list_post_content_{{post.post_id}}" style="display:none;" >

                <!-- // Post content section -->
                <div class="post_content_section post_content-{{post.post_id}}" ng-if="post.post_content">
                    <div ng-bind-html-unsafe="post.post_content"></div>
                </div>
                <!-- // Post content section end // -->
                <!-- // Post Gallery section -->
                <div class="single_post_image_section single_post_image_section_{{post.post_id}}"></div>

                <!-- // Post flag inappropriate section // -->
                <div id="flag_message_{{post.post_id}}" style="display:none;">Flagged as inappropriate! Thank you!</div>
                <div id="flag_post_report_{{post.post_id}}" ng-if="post.flag_inappropriate_count == 0">
                    <a id="flag_post" href="javascript:void(0);" ng-click="fnflagpost(post);">
                        <img ng-src="{{base_url}}/assets/images/flag-icon.png " alt="" width="16" height="16">Flag Inappropriate
                    </a>
                </div>
                <!-- // Post flag inappropriate section end // -->

                <!-- // Post flag as advertisement section // -->
                <div id="flag_as_adverstiment_message_{{post.post_id}}" style="display:none;">Flagged as Ad! Thank you!</div>
                <div id="flag_advertisement_post_report_{{post.post_id}}" ng-if="post.flag_as_advertisement_count == 0">
                    <a id="flag_post" href="javascript:void(0);" data-value="{{post.post_id}}" onclick="flag_advertisement_set(this);">Flag Ad</a>
                </div>
                <!-- // Post flag as advertisement section end // -->
                <!-- <span class="single_edit_icon"><a class="pop_edit_tex" id="list_post_edit_btn_{{post.post_id}}" href="{{post.post_edit_link}}" style="display: none;">
                    <img src="{{base_url}}//assets/images/pencil_sign.png " alt="" width="35" height="35">
                 </a></span>-->
                 <div class="updated_user_section">
                 <div class="updated_user">
                	<p class="glf_update_date_{{post.post_id}} dates_ago" style="display:inline-block;" ng-bind-html-unsafe="post.post_meta.edited_user_update" ng-if="post.post_meta.edited_user_update"></p>
                </div>
                 <span class="single_edit_icon"><a class="pop_edit_tex" id="list_post_edit_btn_{{post.post_id}}" href="{{post.post_edit_link}}" style="display: none;">
                    <img ng-src="{{base_url}}/assets/images/pencil_sign.png " alt="" width="35" height="35">
                 </a></span>
                 </div>
            </div>


            <div class="flag_inappropriate_popup_content flag_inappropriate_popup_content_{{post.post_id}}" style="display:none;" ng-if="post.post_flag_inappropriate_warning == 0">
                                <div class="flag_popup_content">
                                    <p>Warning! This post has been flagged as inappropriate! Do you wish to open?</p>
                                    <a class="flag_popup_content_close_btn" href="javascript:void(0);" ng-click="close_flag_inappropriate_popup_content(post, 'open');">Yes</a>
                                    <a class="flag_popup_content_close_btn" href="javascript:void(0);" ng-click="close_flag_inappropriate_popup_content(post, 'close');">No</a>
                                </div>
                            </div>

                            <div class="flag_inappropriate_popup_content flag_inappropriate_popup_content_random" style="display:none;">
                                <div class="flag_popup_content">
                                    <p>Warning! This post has been flagged as inappropriate! Do you wish to open?</p>
                                    <a class="flag_popup_content_close_btn" href="javascript:void(0);" ng-click="close_flag_inappropriate_popup_content(post, 'open');">Yes</a>
                                    <a class="flag_popup_content_close_btn" href="javascript:void(0);" ng-click="close_flag_inappropriate_popup_content(post, 'close');">No</a>
                                </div>
                            </div>

                            <div class="quick_warning_content quick_warning_content_{{post.post_id}}"></div>
         </div>
         </div>
      </div>
     </div>
    </div>


   <input type="hidden" name="hid_delete_check_password" id="hid_delete_check_password" value="nope"/>
        <?php category_right_sidebar_for_page('page'); ?>
        <a href="" id="post_filter_by_search"></a>
       <a href="" id="dropdown_filter"></a>
       <a href="" id="post_filter_by_author"></a>
       <a href="" id="flag_advertisement_set"></a>
       <a href="" id="cat_post_filter_click"></a>
       <a href="" id="scroll_load_post"></a>
           </div>
     <div style="display:none;">
        <div id="post_share_via_email_content" class="post_share_via_email_content">
        	<?php post_share_via_email_content(); ?>
        </div>
    </div>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
