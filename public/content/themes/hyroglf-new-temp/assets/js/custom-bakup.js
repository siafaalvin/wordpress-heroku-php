/* 
 * Custom javascript for this theme
 */ 
/*setTimeout(function(){
 jQuery('#mceu_23 iframe .mce-content-body').css({"background-color": "yellow", "font-size": "200%"})
},5000);*/

//var topmgwrapper = jQuery("header").height();
//jQuery('.hdtopmargin').css('margin-top',172+'px');
//alert(topmgwrapper)

jQuery(".delete_account_popup_btn").insertAfter("")
jQuery("#myprofile_field").click(function(){
	jQuery(".profile_page h2").text("My Profile")
	jQuery(".myprofile_form_content").show();
	jQuery(".home_content_section.my-profile").hide();
	jQuery(".content.profile_page").removeClass("grey_bg");
	//$(this).parent().parent().toggleClass("toggle-active");

	//jQuery(this).parent().parent().toggleClass("post_cat_image_modal_wrapper post_cat_image_modal_desktop_wrapper");
});
jQuery("#myprofile_posts").click(function(){
	jQuery(".myprofile_form_content").hide();
	jQuery(".home_content_section.my-profile").show();
	jQuery(".content.profile_page").addClass("grey_bg");
});
jQuery("#myprofile_votes").click(function(){
	jQuery(".myprofile_form_content").hide();
	jQuery(".home_content_section.my-profile").show();
	jQuery(".content.profile_page").addClass("grey_bg");
});
function fndeleteaccount(){
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: jQuery("#check_password_delete_frm").serialize()+"&action=delete_user_account",
		success: function( html ) {
			html = jQuery.parseJSON(html);
			if(html.message == 'nope'){
				jQuery("#delete_account_popup_content").fadeIn();
			} else{
				jQuery("#delete_account_popup_content").fadeOut();
				window.location.href=html.output;
			}
		}
	});
}
jQuery("body").on("click","#user_post_publish.uploading",function(){
	/*alert("Wait! Image still uploading!");*/
});
jQuery(".wppb-error").html('<strong>login failed: try again or <a href="change-password" title="password reset?">reset password</a></strong>');
if(jQuery(".login_error.error_msg").length > 0){
	jQuery(".wppb-error").hide();
	jQuery(".login_error.error_msg").show();
}
function allLetter(inputtxt)	{ 

var str = jQuery(inputtxt).val();
		var re = /\S+@\S+\.\S+/;
		if (re.test(str) == true) {
			jQuery("#reg_forms .error").show();
		  jQuery("#reg_forms .error").html('<span class="field-required red">Invalid username..! Please enter the valid username</span>');
		  //jQuery("input[name='register']").attr("disabled","disabled");
		}
		else
		{
		  jQuery("#reg_forms .error").hide();
		    jQuery("input[name='register']").removeAttr("disabled");
			return true;
		}
		
    
}
jQuery("#user_login").focus(function(){
	jQuery("#reg_forms .error").hide();
	jQuery("input[name='register']").removeAttr("disabled");
});
jQuery( "#s" ).keypress(function() {
	jQuery(".post_cat_image_modal_wrapper.post_cat_image_modal_desktop_wrapper").removeClass("post_cat_image_open");
});

jQuery(".post_single_cat_view_popup").hide();
jQuery(".single .header_left_content").hide();
jQuery(".page .header_left_content").hide();

jQuery(".wiki_topbar_right.search_push_header_footer").appendTo("footer .container .row");
if (jQuery(window).width() < 993){
        jQuery(".wiki_topbar_right.search_push_header_footer").insertBefore(".logo .header_right");
    }
//jQuery("p.glf_update_single").appendTo(".post_reting_section");
function send_home_url(home_url) {
	 window.location.href = home_url;
}
function page_redirect(home_url){
	window.location.href = home_url;
}
function share_this_post_email(post_id, post, title, post_link, type){
jQuery.ajax({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=get_title_by_id_post_email',
			//data : data,
			params: {
				'post_id': post_id,
			},
		}).
		success(function (data, status, headers, config) {
			console.log(data);
			//alert(data._link);
			if (type == 'index') {
				str = "sharer's email thought you'd be interested in this Hyroglf post:\n\n <a href="+post_link+">"+post_title+"</a>";
				jQuery("#hidden_share_title").val(post_title);
				jQuery("textarea#txtMessage").val(str);
			} else {
				jQuery("#hidden_share_title").val(title);
				str = "sharer's email thought you'd be interested in this Hyroglf post:\n\n <a href="+post_link+">"+post_title+"</a>";
				jQuery("textarea#txtMessage").val(str);
			}
		}).
		error(function (data, status, headers, config) {
			console.log('error');
		});
		
		jQuery.fancybox({
			'type': 'inline',
			'href': '#post_share_via_email_content'
		});
}
function spam_report_post(){
	jQuery(".spam-report-post").css("display","block");
}

function spam_report_post_remove(){
	jQuery(".spam-report-post").css("display","none");
}

function set_post_title(dhis_val) {
	
	//jQuery("#reference_link").val(dhis_val);
	/*var scope = angular.element(document.getElementById('set_post_title')).scope();
	  scope.$apply(function() {
		scope.set_post_title();
	 });
	return false;*/
	jQuery('#user_post_publish').attr('disabled','disabled');
	jQuery.ajax({
		url: ajaxurl,
		type: "post",
		data: {
			action : 'get_post_and_refer_link_title',
			post_url: dhis_val
			},
		success: function(data, textStatus, jqXHR) {
			data = jQuery.parseJSON(data);
			jQuery("#fp_refernce_link_home_page_title").val(data.reference_link_title);
			jQuery("#fp_title").val(data.post_title);
				jQuery.ajax({
				url: ajaxurl,
				type: "post",
				data: {
				action : 'get_post_and_refer_link_title_duplicate',
				post_url: dhis_val
				},
				success: function(data, textStatus, jqXHR) {
					split_success_value = data.split('}-')
					if(split_success_value[1]){
						jQuery(".error_msg_post").show();
						jQuery(".content .post_single_page .error_msg_post").html('<div class="frontier_post_msg"><span class="fp_error_msg">Post not added</span><br>Post with the Source URL you entered already exists.<a href="'+split_success_value[1]+'" target="_blank">Click here</a>to view</div>');
						jQuery('#user_post_publish').attr('disabled','disabled');
					}
					else{
						jQuery(".error_msg_post").hide();						
						jQuery('#user_post_publish').removeAttr('disabled');
					}
					
				}
				});
			jQuery('#user_post_publish').removeAttr('disabled');
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log('error');
		}
	});
	
}

function post_rating( post_id, name1, name2 ) {
	//jQuery("."+name1+"_single_vote_post_result_"+post_id).hide();
	jQuery("."+name1+"_single_vote_post_option_"+post_id).show();
}

function close_flag_inappropriate_popup_content(post_id, action) {
	if( action == 'open' ) {
		jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
	} else {
		jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
		jQuery(".post_single_content_section").hide();
		jQuery(".single_close_icon").hide();
		jQuery(".single_plus_icon").show();
	}
}

function show_single_content() {
	jQuery(".flag_inappropriate_popup_content").show();
	jQuery(".post_single_content_section").show();
	jQuery(".single_close_icon").show();
	jQuery(".single_plus_icon").hide();
}
setTimeout(function(){
	jQuery("#frontier_post_content_ifr").contents().find("body").attr("style","font-size:14px;");
},5000);

function close_single_content(home_url) {
	window.location.href = home_url;
	/*jQuery(".post_single_content_section").hide();
	jQuery(".single_close_icon").hide();
	jQuery(".single_plus_icon").show();*/
}

function dropdown_filter(option, option_name, action) {
	//alert(option+' - '+action);
	if( action == 'infor' ) {
		jQuery("#filter_informative").val(option);
	}
	if( action == 'bias' ) {
		jQuery("#filter_bias").val(option);
	}
	
	var scope = angular.element(document.getElementById('dropdown_filter')).scope();
	  scope.$apply(function() {
		scope.dropdown_filter();
	 });
	return false;
	
}

function post_filter_by_author(author) {
	
	jQuery("#filter_category").val(author);
	jQuery("#filter_taxonomy").val('post_filter_by_author');
	
	var scope = angular.element(document.getElementById('post_filter_by_author')).scope();
	  scope.$apply(function() {
		scope.post_filter_by_author();
	 });
}

function flag_advertisement_set( dhis ) {
	post_id = jQuery(dhis).attr("data-value");
	jQuery("#hidden_p_id").val(post_id);
	
	var scope = angular.element(document.getElementById('flag_advertisement_set')).scope();
	  scope.$apply(function() {
		scope.flag_advertisement_set();
	 });
	
}

view_content_click_quick = function( post_id, action ) {
	
	jQuery("#view_list_post_" + post_id).hide();
	jQuery("#close_list_post_" + post_id).show();
	jQuery("#list_post_edit_btn_" + post_id).show();
	jQuery("#list_post_content_" + post_id).show();
	jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
	jQuery(".single_post_multi_image_slide").show();
	setTimeout(function() {
		jQuery(".single_post_multi_image_slide_"+post_id+",.single_post_multi_video_slide_"+post_id).bxSlider({
			pager: false,
			adaptiveHeight: true,
			video: true,
			useCSS: false,
			pagerCustom: '#bx-pager',
			infiniteLoop: true,
			useCSS:false
		});
		
		jQuery('.fancybox').fancybox({
			maxWidth : 400,
			helpers : {
						media : true
					},
			youtube: {
					autoplay: 1, // enable autoplay
					start: 01 // set start time in seconds (embed)
				}
			});
			
	}, 50);	
}

close_the_content_click_quick = function( post_id, action ) {
	
	jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
	jQuery(".source_publish_date_" + post_id).hide();
	jQuery(".glf_update_date_" + post_id).hide();
	
	jQuery("#view_list_post_" + post_id).show();
	jQuery("#close_list_post_" + post_id).hide();
	jQuery("#list_post_edit_btn_" + post_id).hide();
	jQuery("#list_post_content_" + post_id).hide();
}

var shuffleArray = function(array) {
  var m = array.length, t, i;

  // While there remain elements to shuffle
  while (m) {
    // Pick a remaining element…
    i = Math.floor(Math.random() * m--);

    // And swap it with the current element.
    t = array[m];
    array[m] = array[i];
    array[i] = t;
  }

  return array[0];
}

function ValidateDate(dtValue){
var currentTime = new Date()
var month = currentTime.getMonth() + 1
var day = currentTime.getDate()

var year = currentTime.getFullYear()
	if( dtValue!= '' ) {
		var dtRegex = new RegExp(/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/);
		if( !dtRegex.test(dtValue) ) {
			jQuery(".date_error_msg").remove();
			jQuery(".date_div div").append('<p class="error_msg date_error_msg">Invalid date format! (MM/DD/YYYY)</p>');
			jQuery("#date_validation").val('invalid');
		} else {
			jQuery(".date_error_msg").remove();
			jQuery("#date_validation").val('valid');
			var date_regex = /^\d{2}\/\d{2}\/\d{4}$/ ;
			if(!date_regex.test(dtValue)) {
				jQuery(".date_div div").append('<p class="error_msg date_error_msg">Invalid date format! (MM/DD/YYYY)</p>');
				jQuery("#date_validation").val('invalid');
			} else {
				var validate_date = dtValue.split('/');
				//if( validate_date[0] > month || validate_date[1] > day  || validate_date[2] > year) 
				if((validate_date[0] == month) && (validate_date[2] == year)){
				
					if( (validate_date[1] > day ||  validate_date[1] < 1)  ){
						jQuery(".date_error_msg").remove();
						jQuery(".date_div div").append('<p class="error_msg date_error_msg">Invalid date format! (MM/DD/YYYY)</p>');
						}
					
				}
				else if((validate_date[2] == year) && ( validate_date[0] < month  || validate_date[0] < day)){
					if( validate_date[1] > 31 ||  validate_date[1] < 1){
						jQuery(".date_error_msg").remove();
						jQuery(".date_div div").append('<p class="error_msg date_error_msg">Invalid date format! (MM/DD/YYYY)</p>');
						}
					
				}
				 else if( validate_date[0] > 12 || validate_date[0] < 1 || validate_date[1] > 31 ||  validate_date[1] < 1 || validate_date[2] > (year-1) ){
					//alert(year+3);
					jQuery(".date_error_msg").remove();
					jQuery(".date_div div").append('<p class="error_msg date_error_msg">Invalid date format! (MM/DD/YYYY)</p>');
				} else {
					jQuery(".date_error_msg").remove();
					jQuery("#date_validation").val('valid');
					
					var dateEntered = dtValue;
					var month = dateEntered.substring(0, 2);
					var date = dateEntered.substring(3, 5);
					var year = dateEntered.substring(6, 10);
					//alert(year);
					
					//alert(date+' - '+month+' - '+year);
				 
					var dateToCompare = new Date(year, month - 1, date);
					var now_date = new Date();
					var c_year = now_date.getFullYear();
					var c_month = now_date.getMonth()+1;
					var c_date = now_date.getDate();
					
					var currentDate = new Date(c_year, c_month - 1, c_date);
					
					//alert(dateToCompare+' - '+currentDate);
					
					/*if (dateToCompare >= currentDate) {
						jQuery(".date_error_msg").remove();
						jQuery("#date_validation").val('valid');
					} else {
						jQuery("#date_validation").val('invalid');
						jQuery(".date_div div").append('<p class="error_msg date_error_msg">Date Entered is lesser than Current Date</p>');
					}*/
				}
			}
		}
	} else {
		jQuery(".date_error_msg").remove();
	}
}

function find_video_to_add_remove_btn() {
	post_video = jQuery("#post_video").val();
	if( post_video ) {
		jQuery(".remove_post_video_action").show();
	} else {
		jQuery(".remove_post_video_action").hide();
	}
}

function remove_post_video() {
	jQuery("#post_video").val('');
	jQuery(".remove_post_video_action").hide();
}


/* 
 * Custom Jquery for this theme
 */
jQuery(function($){
	//alert();
	jQuery(window).scroll(function() {    
		var scroll = $(window).scrollTop();
		var scrolltop = $(window).scrollTop();
		if (scroll >= 10) {
			jQuery("header").addClass("makefixed");
		} else {
			jQuery("header").removeClass("makefixed");
		}
	});
	
	var winwidth  = jQuery(window).width();
	
	jQuery('.page-privacy-policy .content ol li a,.page-terms .content ol li a').click(function(e){
		e.preventDefault();
		var topmgwrapper = jQuery("header").height();
		var attrv = jQuery(this).attr('href');
		jQuery('html,body').animate({
			scrollTop:jQuery(attrv).offset().top - topmgwrapper-20,
		}, 800);
	});
	
	jQuery("select").find("option").each(function(i,e) {
          var opt_value =jQuery(e).val();
		  if( opt_value == 0 ) {
			  e.disabled=true;
		  }
	});
	
	jQuery('a.rating_option').click(function(){
		jQuery(this).hide();
		jQuery(this).siblings('a.rating_option').show();
	});
	
	jQuery('body').on('click', '#about_link', function() {
		
		jQuery("#AbotContent").parent().parent().attr("class");
		$.fancybox({
			'type': 'inline',
			'href': '#AbotContent',
			'class' : ''
    	});
	});
	
	jQuery('body').on('click', '.page-my-profile #edit_profile', function() {
		$.fancybox({
			'type': 'inline',
			'href': '#check_password_popup'
    	});
	});
	
	
	//var popupEl = document.getElementById('popup'); 

	// As a native plugin
	/*var popup = new Popup(popupEl, {
		width: 400,
		height: 300
	});*/

	// As a jQuery plugin
	// var popup = $('#popup').popup({
	//     width: 400,
	//     height: 300
	// });

	/*var open = document.getElementById('open');
	var close = document.getElementById('close');

	open.onclick = function() {
		popup.open();
	};

	close.onclick = function() {
		popup.close();
	};*/
	
	jQuery('#menu').slicknav({
		appendTo:'.wiki_left_bg',
		label:'',	
	});	
		
	var winwidth  = jQuery(window).width();
	var winheight = jQuery(window).height();
	if(winwidth > 639){
		var leffheadheight = jQuery('.header_left_content').height();
		//jQuery('.header_right_content').height(leffheadheight);
	}
	
	jQuery(".sort_tab_sections a").click(function(){
		jQuery(".sort_tab_sections a").removeClass('active');
		jQuery(this).addClass('active');
	});
	
	/* For iOS touch hover effect */
	
	//document.addEventListener("touchstart", function() {},false);
	jQuery("#fp_tax_category").addClass("cs-select cs-skin-elastic");
	
	/** custom select box **/
	(function() {
		[].slice.call( document.querySelectorAll('select.cs-select')).forEach( function(el) {	
			new SelectFx(el);
		} );
	})();
	
});
jQuery(document).ready(function(){
	
	jQuery('.post_single_filter_text').appendTo('.home_head_content')
	
	jQuery("#publish_date_news_manual").keyup(function(){
		if (jQuery(this).val().length == 2 || jQuery(this).val().length == 5){
			jQuery(this).val(jQuery(this).val() + "/");
		}
		
	});
	
	
	jQuery(".post-revisions").insertAfter( ".spam-report-post" );
	
	jQuery("#frontier_post_fieldset_status").hide();
	
	jQuery(".additional_category_field").insertAfter( ".frontier_post_fieldset_tax_tag");
	
	jQuery("#post_read_time, #publish_date_news_manual").keydown(function (e) {
		//alert(e.keyCode);
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	jQuery(window).scroll(function() {
	  var y = jQuery(this).scrollTop();
	  if (y > 200) {
		jQuery('#back-to-top').fadeIn();
		//jQuery('.page-about-us #back-to-top').fadeIn();
	  }else{
		jQuery('#back-to-top').fadeOut();
		//jQuery('.page-about-us #back-to-top').fadeOut();
	  }
	});
	
	jQuery('#back-to-top').on('click',function () {
       jQuery('html,body').animate({
            scrollTop: 0
        }, 700);
    });
	
	jQuery(document).on("click",'.cs-placeholder-c',function(e) {
		 e.stopPropagation();
		 jQuery(this).parent('.cs-select-c.cs-skin-elastic-c').toggleClass("cs-active-c"); 
	});
	
	jQuery(document).on('click','.cs-options-c ul li span',function(){
		var rptext = jQuery(this).html();
		jQuery(this).parent().parent().parent().siblings('span.cs-placeholder-c').text(rptext);
		jQuery(this).parent().parent().parent().parent().removeClass('cs-active-c');
	});
	
	jQuery(document).click(function(){
		jQuery('.cs-select-c.cs-skin-elastic-c').removeClass("cs-active-c");
	});
	
	
});


jQuery(window).on('load resize', function () {
		
	var winwidth  = jQuery(window).width();
	
	setInterval(function(){
		jQuery('.g-recaptcha-bubble-arrow').parent().addClass('zindex-value')
	},10)
	
	var topmgwrapper = jQuery("header").height();
	jQuery('.hdtopmargin').css('margin-top',topmgwrapper+10+'px');
	jQuery(".single_post_multi_image_slide,.single_post_multi_video_slide").show();
	jQuery(".single_post_multi_image_slide,.single_post_multi_video_slide").bxSlider({
		pager: false,
		adaptiveHeight:true,
		video: true,
		useCSS: false,
	});
	
    var elementHeights = jQuery('.section_equal').map(function() {
		return jQuery(this).height();
	});
	
	var maxHeight = Math.max.apply(null, elementHeights);
	jQuery('.section_equal').height(maxHeight);
	
	jQuery('#loginform .login-remember label input[type="checkbox"]').prependTo('#loginform .login-remember');
	
	jQuery('.post_image_upload_btn').appendTo('#dragAndDropFiles');
	
	if(winwidth < 400){
		var fth = jQuery('footer').innerHeight();
		if(jQuery('body.page-login').length < 0){
			jQuery('body').css('padding-bottom',fth+50+'px');
		} else {
			jQuery('body.page-login').css('padding-bottom',fth+10+'px');
		}
		
		jQuery(window).scroll(function() {    
			var scrolltop = jQuery(window).scrollTop();
			if(jQuery('body.home').length > 0){
				if (scrolltop > 100) {
					jQuery('footer').fadeIn();
				}else{
					jQuery('footer').fadeOut();
				}
			}
		});
	}
	
	
	
	
	jQuery('.post_voting_section').click(function(event){
		event.stopPropagation();
	});
	
	
	jQuery(document).on('mouseover','.vote_result_section > a',function(){
		jQuery('.post_voting_section').css('display','none');
		jQuery(this).siblings('.post_voting_section').css('display','block');
		jQuery(this).siblings('.post_rating_before_signin').css('display','block');
	});
	/*jQuery(document).on('mouseover','.vote_result_section > a').mouseover(function(){
		jQuery('.post_voting_section').css('display','none');
		jQuery(this).siblings('.post_rating_before_signin').css('display','block');
	});*/
	
	jQuery(document).click(function(){
		jQuery('.post_voting_section').hide();
	});
	jQuery(document).on('click','.post_voting_section',function(e){
		 e.stopPropagation();
	});
	
	/*var inputwidth = jQuery('#searchform input#s').width();
	jQuery('#ui-id-1.ui-autocomplete').css('max-width',inputwidth+23+'px');*/
	
	
	jQuery(document).on("click",function(e){ 
		if(jQuery('.post_cat_image_modal_wrapper').hasClass('post_cat_image_open')){
			jQuery('.post_cat_image_modal_wrapper').removeClass('post_cat_image_open')
		}
	});
	

	jQuery('#filter_cat_popup, .post_cat_image_modal_wrapper').click(function(event){
		event.stopPropagation();
	});
	
	
});

  jQuery(".page-login .wppb-error a").attr("href","reset-password");

jQuery(document).ready(function(){
	
	jQuery('label[for="edit_user"]').hide();
	jQuery("body.page-template-my-profile #select_user_to_edit_form #wppb-edit-user").css("display","none");
	
	jQuery('.rating_options_select.cs-select .cs-options ul li').each( function() {
		jQuery(this).attr('onclick', 'get_single_rating_option(this)');
	});
		
});

function get_single_rating_option(dhis) {
	data_value = jQuery(dhis).attr('data-value');
	jQuery(dhis).parent().parent().parent().siblings('input').val(data_value);
}

function cat_post_filter_click( even, tax, cat_item, cat_name, cat_image, page_of ) {
	jQuery("#filter_category").val(cat_item);
	jQuery("#filter_taxonomy").val(tax);
	jQuery("#filter_cat_name").val(cat_name);
	jQuery("#filter_cat_img").val(cat_image);
	
	var scope = angular.element(document.getElementById('cat_post_filter_click')).scope();
	scope.$apply(function() {
		scope.cat_post_filter_click();
	});
	
}
function fnpost_link(dhis){
	window.location.href = jQuery(dhis).attr("data-value");
}
var winwidth  = jQuery(window).width();
		if(winwidth <= 650){			
			jQuery(".home .header_left_content .wiki_topbar_right.hide-767").remove();
		}

// Smooth scroll
/*$(function(){
	
	var $window = $(window);		//Window object
	
	var scrollTime = 1.2;			//Scroll time
	var scrollDistance = 170;		//Distance. Use smaller value for shorter scroll and greater value for longer scroll
		
	$window.on("mousewheel DOMMouseScroll", function(event){
		
		event.preventDefault();	
										
		var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
		var scrollTop = $window.scrollTop();
		var finalScroll = scrollTop - parseInt(delta*scrollDistance);
			
		TweenMax.to($window, scrollTime, {
			scrollTo : { y: finalScroll, autoKill:true },
				ease: Power1.easeOut,	//For more easing functions see http://api.greensock.com/js/com/greensock/easing/package-detail.html
				autoKill: true,
				overwrite: 5							
			});
					
	});
	
});*/
jQuery(document).ready(function() {
	jQuery(".error_msg.password_match_error").hide();
	jQuery('.page-change-password #pass1').keyup(function() {
		jQuery('.page-change-password #result').html(checkStrength(jQuery(this).val()))
	})
	jQuery('.page-change-password #pass2').keyup(function() {
		jQuery('.page-change-password #result2').html(checkStrength2(jQuery(this).val()))
	});
	jQuery('#register #new_user_password').keyup(function() {
		jQuery('#register #result').html(checkStrength3(jQuery(this).val()))
	})
	jQuery('#register #re_user_password').keyup(function() {
		jQuery('#register #result2').html(checkStrength4(jQuery(this).val()))
	});
	
	function checkStrength3(password) {
		jQuery(".error_msg.password_match_error").hide();
		var strength = 0
		jQuery('#register span#result').addClass("red");
		jQuery("#register #new_user_password").removeClass("green");
		jQuery("#register #new_user_password").addClass("red");
		jQuery('#register span#result').removeClass("green");
		jQuery("#register #addnewuser").attr("type","button");
		if (password.length < 6) {
			jQuery('#register span#result').removeClass("red");
			jQuery('#register span#result').removeClass("green");
			jQuery('#register span#result').addClass("red");
			jQuery("#register #addnewuser").attr("type","button");
			jQuery('#result').addClass('short')
			return 'Too short';
		}
		
			
			if (password.length > 7) strength += 1
				// If password contains both lower and uppercase characters, increase strength value.
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
				// If it has numbers and characters, increase strength value.
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
				// If it has one special character, increase strength value.
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// If it has two special characters, increase strength value.
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// Calculated strength value, we can return messages
				// If value is less than 2
				if (strength < 2) {
					jQuery('#register span#result').addClass("red");
					jQuery("#register #new_user_password").removeClass("green");
					jQuery("#register #new_user_password").addClass("red");
					jQuery('#register span#result2').removeClass("green");
					jQuery("#register #addnewuser").attr("type","button");
					return 'Weak';
				} else if (strength == 2) {
					jQuery('#register #result').addClass("green");
					jQuery("#register #new_user_password").addClass("green");
					jQuery("#register #new_user_password").removeClass("red");
					jQuery('#register #result').removeClass("red");
					pass1 = jQuery('#register #new_user_password').val();
					pass2 = jQuery('#register #re_user_password').val();
					
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery("#register #addnewuser").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery("#register #addnewuser").attr("type","button");
					}		
					return 'Good';					
				} else {
					jQuery('#register #result').addClass("green");
					jQuery("#register #new_user_password").addClass("green");
					jQuery("#register #new_user_password").removeClass("red");
					jQuery('#register #result').removeClass("red");
					pass1 = jQuery('#register #new_user_password').val();
					pass2 = jQuery('#register #re_user_password').val();
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery("#register #addnewuser").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery("#register #addnewuser").attr("type","button");
					}				
					return 'Strong';
				}
	}
	function checkStrength4(password) {
		jQuery(".error_msg.password_match_error").hide();
		var strength = 0
		jQuery('#register span#result2').addClass("red");
		jQuery("#register #re_user_password").removeClass("green");
		jQuery("#register #re_user_password").addClass("red");
		jQuery('#register span#result2').removeClass("green");
		jQuery("#register #addnewuser").attr("type","button");
		if (password.length < 6) {
			jQuery('#register span#result2').addClass("red");
			jQuery("#register #re_user_password").removeClass("green");
			jQuery("#register #re_user_password").addClass("red");
			jQuery('#register span#result2').removeClass("green");
			jQuery("#register #addnewuser").attr("type","button");
			jQuery('#result2').addClass('short')
			return 'Too short'
		}
		
			
			if (password.length > 7) strength += 1
				// If password contains both lower and uppercase characters, increase strength value.
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
				// If it has numbers and characters, increase strength value.
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
				// If it has one special character, increase strength value.
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// If it has two special characters, increase strength value.
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// Calculated strength value, we can return messages
				// If value is less than 2
				if (strength < 2) {
					jQuery('#register span#result2').addClass("red");
					jQuery("#register #re_user_password").removeClass("green");
					jQuery("#register #re_user_password").addClass("red");
					jQuery('#register span#result2').removeClass("green");
					jQuery("#register #addnewuser").attr("type","button");
					return 'Weak';
				} else if (strength == 2) {
					jQuery('#register #result2').addClass("green");
					jQuery("#register #re_user_password").addClass("green");
					jQuery("#register #re_user_password").removeClass("red");
					jQuery('#register #result2').removeClass("red");
					pass1 = jQuery('#register #new_user_password').val();
					pass2 = jQuery('#register #re_user_password').val();
					
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery("#register #addnewuser").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery("#register #addnewuser").attr("type","button");
					}		
					return 'Good';					
				} else {
					jQuery('#register #result2').addClass("green");
					jQuery("#register #re_user_password").addClass("green");
					jQuery("#register #re_user_password").removeClass("red");
					jQuery('#register #result2').removeClass("red");
					pass1 = jQuery('#register #new_user_password').val();
					pass2 = jQuery('#register #re_user_password').val();
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery("#register #addnewuser").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery("#register #addnewuser").attr("type","button");
					}				
					return 'Strong';
				}
	}
	
	function checkStrength2(password) {
		jQuery(".error_msg.password_match_error").hide();
		var strength = 0
		jQuery('.page-change-password span#result2').addClass("red");
		jQuery(".page-change-password #pass2").removeClass("green");
		jQuery(".page-change-password #pass2").addClass("red");
		jQuery('.page-change-password span#result2').removeClass("green");
		jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
		if (password.length < 6) {
			jQuery('.page-change-password span#result2').addClass("red");
			jQuery(".page-change-password #pass2").removeClass("green");
			jQuery(".page-change-password #pass2").addClass("red");
			jQuery('.page-change-password span#result2').removeClass("green");
			jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
			jQuery('#result2').addClass('short')
			return 'Too short'
		}
		
			
			if (password.length > 7) strength += 1
				// If password contains both lower and uppercase characters, increase strength value.
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
				// If it has numbers and characters, increase strength value.
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
				// If it has one special character, increase strength value.
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// If it has two special characters, increase strength value.
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// Calculated strength value, we can return messages
				// If value is less than 2
				if (strength < 2) {
					jQuery('.page-change-password span#result2').addClass("red");
					jQuery(".page-change-password #pass2").removeClass("green");
					jQuery(".page-change-password #pass2").addClass("red");
					jQuery('.page-change-password span#result2').removeClass("green");
					jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
					return 'Weak';
				} else if (strength == 2) {
					jQuery('.page-change-password #result2').addClass("green");
					jQuery(".page-change-password #pass2").addClass("green");
					jQuery(".page-change-password #pass2").removeClass("red");
					jQuery('.page-change-password #result2').removeClass("red");
					pass1 = jQuery('.page-change-password #pass1').val();
					pass2 = jQuery('.page-change-password #pass2').val();
					
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
					}		
					return 'Good';					
				} else {
					jQuery('.page-change-password #result2').addClass("green");
					jQuery(".page-change-password #pass2").addClass("green");
					jQuery(".page-change-password #pass2").removeClass("red");
					jQuery('.page-change-password #result2').removeClass("red");
					pass1 = jQuery('.page-change-password #pass1').val();
					pass2 = jQuery('.page-change-password #pass2').val();
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
					}				
					return 'Strong';
				}
	}
	
	
	function checkStrength(password) {
		jQuery(".error_msg.password_match_error").hide();
		var strength = 0
			jQuery('.page-change-password span#result').addClass("red");
			jQuery(".page-change-password #pass1").removeClass("green");
			jQuery(".page-change-password #pass1").addClass("red");
			jQuery('.page-change-password span#result').removeClass("green");
			jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
		if (password.length < 6) {
			jQuery('#result').removeClass()
			jQuery('#result').addClass('short')
			jQuery('.page-change-password span#result').addClass("red");
			jQuery(".page-change-password #pass1").removeClass("green");
			jQuery(".page-change-password #pass1").addClass("red");
			jQuery('.page-change-password span#result').removeClass("green");
			jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
			return 'Too short'
		}
		
			
			if (password.length > 7) strength += 1
				// If password contains both lower and uppercase characters, increase strength value.
			if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
				// If it has numbers and characters, increase strength value.
			if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
				// If it has one special character, increase strength value.
			if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// If it has two special characters, increase strength value.
			if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
				// Calculated strength value, we can return messages
				// If value is less than 2
				if (strength < 2) {
					jQuery('.page-change-password span#result').addClass("red");
					jQuery(".page-change-password #pass1").removeClass("green");
					jQuery(".page-change-password #pass1").addClass("red");
					jQuery('.page-change-password span#result').removeClass("green");
					jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
					return 'Weak';
				} else if (strength == 2) {
					jQuery('.page-change-password span#result').addClass("green");
					jQuery(".page-change-password #pass1").removeClass("red");
					jQuery(".page-change-password #pass1").addClass("green");
					jQuery('.page-change-password span#result').removeClass("red");
					pass1 = jQuery('.page-change-password #pass1').val();
					pass2 = jQuery('.page-change-password #pass2').val();	
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
					}	
					return 'Good';					
				} else {
					jQuery('.page-change-password span#result').addClass("green");
					jQuery(".page-change-password #pass1").removeClass("red");
					jQuery(".page-change-password #pass1").addClass("green");
					jQuery('.page-change-password span#result').removeClass("red");
					pass1 = jQuery('.page-change-password #pass1').val();
					pass2 = jQuery('.page-change-password #pass2').val();	
					if(pass1 == pass2){
						jQuery(".error_msg.password_match_error").hide();
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","submit");
						//jQuery(".page-change-password .form-submit #edit_profile").removeAttr("disabled","disabled");
					}else{
						jQuery(".page-change-password .form-submit #edit_profile").attr("type","button");
					}				
					return 'Strong';
				}
	}
	  /*jQuery('#pass2').on('keyup', function () {
	  if (jQuery(this).val() == jQuery('#pass1').val()) {
		  alert('matched');
		  jQuery('#message').html('matching').css('color', 'green');
	  } else jQuery('#message').html('not matching').css('color', 'red');
	  alert('not');
  });
*/	  

jQuery(".delete_account_popup_btn").click(function(){
	jQuery("#delete_account_popup_content").fadeIn();
	jQuery("body").toggleClass("pop_up_open");
});


/*jQuery('.wiki_wrapper').on('click',function(){
		
		if(jQuery('.pop_up_open').length > 0 . data('clicked')) {
			//alert(jQuery('.pop_up_open').length);
			jQuery("body").not("#delete_account_popup_content").on('click',function(){
				alert(jQuery('.pop_up_open').length);
			//jQuery("body").not("#delete_account_popup_content").hide();
			//jQuery("#delete_account_popup_content").hide();
			jQuery("body").removeClass("pop_up_open");
			//jQuery("#delete_account_popup_content > div:not(#form_check_pass_delete_hyroglf)").hide();
			//jQuery( "p" ).not( "#delete_account_popup_content" ).hide();
			//jQuery('#form_check_pass_delete_hyroglf').not('.form_check_pass_delete_hyroglf').hide();
			//jQuery("#delete_account_popup_content > div:not(.form_check_pass_delete_hyroglf)").
			//jQuery("#delete_account_popup_content").hide();
			});
		}
		*/



/*jQuery('.form_check_pass_delete_hyroglf').click(function(){
		
		//if(jQuery(this).hasClass('pop_up_open')) {
			//alert()
			jQuery("#delete_account_popup_content").show();
		//}
		
});*/

jQuery("#frm_check_password_delete_close,#delete_frm_check_pass_cancel").click(function(){
	jQuery("#delete_account_popup_content").fadeOut();
});
	jQuery(".page-change-password #edit_profile").click(function() {
		var result1 = jQuery('#result').text();
		var result2 = jQuery('#result2').text();
			if (jQuery('.page-change-password #pass1').val() == jQuery('.page-change-password #pass2').val()) {
				jQuery(".error_msg.password_match_error").hide();
				return true;
			}
			else{
				jQuery(".error_msg.password_match_error").show();
				return false;
			}
	  });
	jQuery("#register #addnewuser").click(function() {
		var result1 = jQuery('#result').text();
		var result2 = jQuery('#result2').text();
			if (jQuery('#register #new_user_password').val() == jQuery('#register #re_user_password').val()) {
				jQuery(".error_msg.password_match_error").hide();
				return true;
			}
			else{
				jQuery(".error_msg.password_match_error").show();
				return false;
			}
	});
});


jQuery(".page-my-profile #edit_profile").addClass("failed");
if(jQuery("#hid_check_password").val() == 'success'){
	jQuery("#check_password_popup").hide();
	jQuery(".page-my-profile #edit_profile").removeClass("failed");
	jQuery(".page-my-profile #edit_profile").attr("type","submit");
}else{
	jQuery(".page-my-profile #edit_profile").attr("type","button");
}
jQuery(".page-my-profile #edit_profile.failed").click(function(){
	jQuery("#check_password_popup").fadeIn();
	if(jQuery("#hid_check_password").val() == 'success'){
		jQuery("#check_password_popup").fadeOut();
		jQuery(".page-my-profile #edit_profile").removeClass("failed");
		jQuery(".page-my-profile #edit_profile").attr("type","submit");
	}else{
		jQuery("#check_password_popup").fadeIn();
		jQuery(".page-my-profile #edit_profile").attr("type","button");
	}
});
jQuery("#frm_check_pass_submit").click(function(){
	if(jQuery("#hid_check_password").val() == 'success'){
		jQuery("#check_password_popup").fadeOut();
		jQuery(".page-my-profile #edit_profile").removeClass("failed");
		jQuery(".page-my-profile #edit_profile").attr("type","submit");
	}else{
		jQuery("#check_password_popup").fadeIn();
		jQuery(".page-my-profile #edit_profile").addClass("failed");
		jQuery(".page-my-profile #edit_profile").attr("type","button");
	}
});
jQuery("#frm_check_pass_cancel").click(function(){
	jQuery("#check_password_popup").fadeOut();
});
jQuery("#frm_check_password_close").click(function(){
	jQuery("#check_password_popup").fadeOut();
});
/*jQuery('.pop_up_open').click(function() {
	alert();
	jQuery('.form_check_pass_delete_hyroglf').hide();
});*/

jQuery( document ).ready(function() {
    jQuery(".wiki_topbar_right.search_push_header_footer").show();
	var winwidth = jQuery(window).width();
	/*slider = jQuery('.single_post_multi_image_slide').bxSlider();
	
	if(winwidth < 768) {
		slider.startAuto();
	}
	*/
	jQuery(document).mouseup(function(e) 
	{
		var container = jQuery("#form_check_pass_delete_hyroglf");
	
		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) 
		{
			jQuery('#delete_account_popup_content').fadeOut();
		}
	});
	jQuery(document).mouseup(function(e) 
	{
		var container = jQuery("#form_check_pass_hyroglf");
	
		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) 
		{
			jQuery('#check_password_popup').fadeOut();
		}
	});
	
	
	
});
//var topmgwrapper = jQuery("header").height();
//jQuery('.hdtopmargin').css('margin-top',topmgwrapper+10+'px');
//alert(winwidth);

if(winwidth > 400 & winwidth <= 1050 ){
		var fth = jQuery('footer').innerHeight();
		jQuery('body').css('padding-bottom',fth+50+'px');
		
		jQuery(window).scroll(function() {    
			var scrolltop = jQuery(window).scrollTop();
			if(jQuery('body.home').length > 0){
				if (scrolltop > 50) {
					jQuery('footer').fadeIn();
				}else{
					jQuery('footer').fadeOut();
				}
			}
		});
	}
	jQuery(".page-forgot-password .content_wrapper.hdtopmargin .success_msg").addClass("wppb-forgot-password-success");
	if(jQuery(".deleted_account_message").length == 1){
		jQuery(".sign_up_updates").hide();
	}
	jQuery(".home_content_section.my-profile").hide();
	
	jQuery(".post_cat_image_modal_wrapper span a").click(function(){
	       jQuery(this).parent().parent().parent().toggleClass("post_cat_image_open");
	});
	jQuery('.post_cat_image_modal span').click(function() {
		//alert();
		jQuery('.page-wrapper').addClass("post_open");
	});
	
	
	function standard_timing_fav() {
		//alert();
	
	var d = new Date();
   	var ampm = (d.getHours() >= 24) ? "PM" : "AM";
   	var hours = (d.getHours() >= 24) ? d.getHours()-24 : d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	var glf_time = ((''+hours).length<2 ? '0' :'') + hours+':'+((''+minute).length<2 ? '0':'') + minute+':'+second;

	var month = d.getMonth()+1;
	var day = d.getDate();
	//var year = d.getFullYear().toString().substr(2,2);
	var year = d.getFullYear();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	
	/*var output = ((''+month).length<2 ? '0' : '') + month + '/' +
				((''+day).length<2 ? '0' : '') + day + '/' +
				 year + ' ' +
				((''+hour).length<2 ? '0' :'') + hour + ':' +
				((''+minute).length<2 ? '0' :'') + minute + ':' +
				((''+second).length<2 ? '0' :'') + second;*/
				
	var output_data = ((''+month).length<2 ? '0' : '') + month + '/' + ((''+day).length<2 ? '0' : '') + day + '/' + year + ' ' +glf_time;
		
	//alert(output_data);		
	//alert(output);
				
	//jQuery("#glf_update").val(output);
	//jQuery(".current_system_datetime").val(output);
	/*data = {
			'action': 'standard_timing_for_fav',
			'output_data' : output_data,
		}
	jQuery.post(ajaxurl, data, function(response){
		alert();
	});*/
	
	       jQuery.ajax({
				url: ajaxurl,
				type: "post",
				data: {
					action : 'standard_timing_for_fav',
					output_data : output_data,
					},
				success: function(data) {
					alert(data)
					if(data == 'bala'){
						alert("success");
					}else{
						alert("failed");
						}
			}
	    });
	
	
}

