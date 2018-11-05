// javascript functions and Jquery scripts
/*function hyroglf_analytics( post_id ) {
	data = {
			'action': 'set_hyroglf_analytics',
			'post_id' : post_id,
		}
	jQuery.post(ajaxurl, data, function(response){
	});
}*/



/* Feed Most viewed count */
function hyroglf_analytics_home_feed( post_id ) {
	data = {
			'action': 'set_hyroglf_analytics',
			'post_id' : post_id,
		}
	jQuery.post(ajaxurl, data, function(response){
	});
}



jQuery("body").on("click",".wpfp-link",function(){
	system_date_fn();
});
jQuery( window ).scroll(function() {
	
	 if( jQuery(window).scrollTop() + 50 > (jQuery(document).height() - jQuery(window).height()) - 1500) {

		var scroll_post = document.getElementById('scroll_load_post');
		var scope = angular.element(scroll_post).scope();
	 		scope.$apply(function() {
			scope.scroll_load_post();
	 });
	}
});

function fnpost_submit(){
	/*user_update = jQuery("#action").val();
	jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			datatype: "html",
			data: jQuery("#adduser").serialize()+"&action=wp_user_profile_update_customize&post_action="+user_update+"",
			success: function( html ) {
				alert(html)
			}
		});*/
}


function vote_post_rating(post_id, action) {
	jQuery("."+action+"_voting_option_tab_"+post_id).show();
	//jQuery("."+action+"_vote_results_title_"+post_id).hide();
	//jQuery("."+action+"_vote_results_"+post_id).hide();
}

function display_rating_option( post_id, action, type ) {
	if( type == 'show' ) {
		jQuery("."+action+"_action_show_"+post_id).hide(); 		// Plus image hide
		jQuery("."+action+"_action_close_"+post_id).show(); 	// Close image show
		jQuery("."+action+"_option_"+post_id).show(); 			// Options show
	} else if( type == 'close' ) {
		jQuery("."+action+"_action_show_"+post_id).show(); 		// Plus image show
		jQuery("."+action+"_action_close_"+post_id).hide(); 	// Close image hide
		jQuery("."+action+"_option_"+post_id).hide(); 			// Options hide
	}
}

function change_option_vote(dhis, option) {
	jQuery("#set_vote_option").val(option);
}

function set_post_vote_option(dhis, post_id) {
	jQuery("#set_post_id").val(post_id);
	var scope = angular.element(jQuery(dhis)).scope();
	  scope.$apply(function() {
		scope.fnLoadPosts();
	 });
}

function view_content(post_id) {
	
	var close_icon = theme_object.images.close_sign;
	hyroglf_analytics(post_id);
	
	jQuery(".view-post-"+post_id).hide();
	jQuery(".post_edit_btn_"+post_id).show();
	jQuery(".close-post-"+post_id).show();
	jQuery(".content-"+post_id).slideToggle();
	jQuery(".single_post_multi_image_slide_"+post_id+",.single_post_multi_video_slide_"+post_id).show();
	jQuery(".single_post_multi_image_slide_"+post_id+",.single_post_multi_video_slide_"+post_id).bxSlider({
		pager: false,
		video: true,
	});
	
	jQuery('.fancybox').fancybox({
		helpers : {
				media : true
			},
		youtube: {
			autoplay: 1, // enable autoplay
			start: 01 // set start time in seconds (embed)
		},
	});
	
}

function close_the_content(post_id) {
	
	jQuery(".post_edit_btn_"+post_id).hide();
	jQuery(".close-post-"+post_id).hide();
	jQuery(".view-post-"+post_id).show();
	jQuery(".content-"+post_id).slideToggle();
}

function close_flag_inappropriate_popup_content( post_id ) {
	jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
}

function autocomplete_action() {
	var tags = [];
	


	for (i = 0; i < theme_object.tag.length; i++) {
		tags.push(theme_object.tag[i]);
	}

	// autocomplete
    jQuery( "#post_tag_text" ).autocomplete({
        minLength: 1,
		autoFocus: false,
		delay: 5,
		appendTo: ".tag_text_box",
		position: {
			my: "left top",
			at: "left bottom",
			collision: "none"
		},
		
        source: function( request, response ) {
			var matcher = new RegExp( "^" + jQuery.ui.autocomplete.escapeRegex( request.term ), "i" );
			 response( jQuery.grep( tags, function( item ){
				 return matcher.test( item );
			 }) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split_tag( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( " " );
          return false;
        }
	});
	// autocomplete end
}

function split_tag( val ) {
	//return val.split( /,\s*/ );
	return val.split( /[ ,]+/ );
}

function extractLast( term ) {
	return split_tag( term ).pop();
}

function add_cat_to_post(this_val, post_id, action) {
	jQuery("#cat_post_id").val(post_id);
	jQuery(".post_cat_list_"+post_id).each(function() {
		old_cat_id = jQuery(this).attr('data-id');
		jQuery("#post_cat_checkbox-"+old_cat_id).parent().addClass('active');
		jQuery("#post_cat_checkbox-"+old_cat_id).attr('checked', 'checked');
	});

	jQuery('.post_cat_modal_wrapper').toggleClass('post_cat_open');
	jQuery('.page-wrapper').toggleClass('blur-it');
}

function remove_category(this_val, term_id, post_id) {
	//jQuery('.cs-placeholder').text("Select");
	jQuery('.post_cat_list_'+post_id+'_'+term_id).remove();
	jQuery(".post_of_cat_list_"+post_id+" ul input[value="+term_id+"]").remove();
	jQuery(".add_new_select_category ul input[value="+term_id+"]").remove();
	
	var	allow_cat = jQuery("input[name='allow_cat_id[]']").map(function(){return jQuery(this).val();}).get();

	
	var	exists_cat_term_id = jQuery("input[name='fp_tax_category[]']").map(function(){return jQuery(this).val();}).get();
	data_id = jQuery(".post_categories_list li:last").attr("id");
	if(data_id){
		split_id = data_id.split('_cat-');	
		if(split_id[1]){
			jQuery("#fp_tax_category").val(split_id[1]); //split_id = split_id[1];
		} else {
			jQuery("#fp_tax_category").val(''); 
		}
	}else{
		jQuery("#fp_tax_category").val(''); 
	}
	
	exists_cat_term_id =jQuery("#fp_tax_category").val();
	
	if( theme_object.user_roll != 'user' ) {
		jQuery.each( allow_cat, function( i, val ) {
		  if( jQuery.inArray(val, exists_cat_term_id) > 0 ) {
				jQuery("input#reference_link").attr("required", "");
				//jQuery("#fp_title").removeAttr("readonly");
				jQuery("#fp_refernce_link_home_page_title").removeAttr("readonly");
				return false;
		  } else {
				jQuery("input#reference_link").removeAttr("readonly");
				//jQuery("#fp_title").attr("readonly", "readonly");
				jQuery("#fp_title").removeAttr("required");
				jQuery("#fp_refernce_link_home_page_title").attr("readonly", "readonly");
		  }
		});
	}
	
	var	plublish_date_cat = jQuery("input[name='fp_tax_category[]']").map(function(){return jQuery(this).attr("data-name");}).get();
	if( jQuery.inArray('News', plublish_date_cat) < 0 ) {
		jQuery("#date_picker_news").val('');
		//jQuery("#date_picker_news").css("display","none");
		//alert()
	}
	
	var cat = [];
	jQuery(".post_cat_list_"+post_id).each(function() {
		old_cat_id = jQuery(this).attr('data-id');
		cat.push(old_cat_id);
	});
	
	jQuery.ajax({
		url: ajaxurl,
		type: "post",
		data: {
			action : 'add_and_remove_cat_to_post',
			post_id : post_id,
			post_category: cat
			},
		success: function(data, textStatus, jqXHR) {
		},
		error: function(jqXHR, textStatus, errorThrown) {
			//alert(' Sorry your post categories are not saved please try again later');
		}
	});
	var dummy_caty = jQuery("select:hidden").val();		
	if( dummy_caty != null ){
		jQuery(".cat_select_action").removeClass("validation_error");
	}else{
		jQuery(".cat_select_action").addClass("validation_error");
	}
	if(jQuery('.validation_error').length<1){
		jQuery(".error_msg_source").remove();
	}
}

function add_tag( action ) {
	//tags = jQuery("#post_tag_text").val().split(',');
	tags = jQuery("#post_tag_text").val().split(' ');
	for(i = 0; i < tags.length; i++) {
		var tag = jQuery.trim(tags[i]);
		if( action == 'new_post') {
			var	exists_tags = jQuery(".new_tag_section .insert_tags input").map(function(){return jQuery(this).val();}).get();
			if( tag ) {
				if( jQuery.inArray(tag, exists_tags) < 0 || exists_tags.length == 0 ) {
					count = jQuery(".tagchecklist").length;
					html = '<div class="tagchecklist"><span><a class="post_tag-check post_tag-check-'+count+'" id="post_tag-check-num-'+count+'" onclick="remove_tag_in_editor(this);">X</a>'+tag+'</span><input type="hidden" name="user_post_tag'+count+'" id="post_tags_'+count+'" value="'+tag+'"></div>';
					jQuery(".insert_tags").append(html);
				}
			}
		} else {
			if( tag ) {
				var	exists_tags = jQuery("input[name='post_tags[]']").map(function(){return jQuery(this).val();}).get();
				if( jQuery.inArray(tag, exists_tags) < 0 && tag != '') {
					count = jQuery(".tagchecklist").length;
					
					//html = '<div class="tagchecklist"><span><a class="post_tag-check post_tag-check-'+count+'" id="post_tag-check-num-'+count+'" onclick="remove_tag_in_editor(this);">X</a>'+tag+'</span><input type="hidden" name="post_tags[]" id="post_tags_'+count+'" value="'+tag+'"></div>';
					
					html = '<div class="tagchecklist"><span><a class="post_tag-check post_tag-check-'+count+'" id="post_tag-check-num-'+count+'" onclick="remove_tag_in_editor(this);">X</a>'+tag+'</span><input type="hidden" name="user_post_tag'+count+'" id="post_tags_'+count+'" value="'+tag+'"></div>';
					
					jQuery(".insert_tags").append(html);
				}
			}
		}
		jQuery("#post_tag_text").val('');
	}
}

function remove_tag_in_editor(this_val) {
	jQuery(this_val).parent().parent().remove();
	count = 0;
	jQuery(".frontier_no_border .new_tag_section .tagchecklist").each(function() {
		tag = jQuery(this).find("span").text();
		jQuery(this).find("input").attr("name", "user_post_tag"+count);
		jQuery(this).find("input").attr("id", "post_tags_"+count);
		count++;
	});
}

function view_all_list_cat( type ) {
	jQuery('.post_cat_image_modal_wrapper').toggleClass('post_cat_image_open');
	jQuery('.page-wrapper').toggleClass('blur-it');
	
	/*var scope = angular.element(document.getElementById('filter_cat_popup')).scope();
	  scope.$apply(function() {  
		scope.view_all_list_cat();
	 });*/
	return false;
}

function editor_cat_change() {
	jQuery("p.error").remove();
	post_id = jQuery("#cat_post_id").val();
	cat_term_id = jQuery("#fp_tax_category option:selected").val();
	if(cat_term_id != '') {
		cat_name = jQuery("#fp_tax_category option:selected").text();
		var	exists_cat_term_id = jQuery(".add_new_select_category input[name='fp_tax_category[]']").map(function(){return jQuery(this).val();}).get();
		if( jQuery.inArray(cat_term_id, exists_cat_term_id) < 0 && exists_cat_term_id) {
			content = '<li id="post_'+post_id+'_cat-'+cat_term_id+'" class="post_cat_list post_cat_list_'+post_id+'_'+cat_term_id+'"><a class="post_list_cat post_list_cat-'+cat_term_id+'" onclick="remove_category(this, '+cat_term_id+', '+post_id+');" title="Remove category">X</a><span class="post_cat_name post_cat_name-'+cat_term_id+'"> '+cat_name+' </span><input type="hidden" name="fp_tax_category[]" value="'+cat_term_id+'"></li>';
			jQuery(".post_of_cat_list_"+post_id+" ul").append(content);
			jQuery(".add_new_select_category ul").append(content);
		}
	}
}

function post_read_titme_set( post_id, type) {
}

// Post rating
function post_rating( post_id ) {
	//jQuery(".post_voting_section").slideUp();
	//jQuery("#post_voting_"+post_id).slideDown();
}

function rating_option( post_id, action, show ) {
	jQuery("#"+action+'_option_'+post_id).slideToggle();
	jQuery("#"+action+'_option_'+show+'_'+post_id).slideToggle();	
}

function rating_option_informative( post_id, action, show ) {
	jQuery("#"+action+'_option_'+post_id).slideToggle();
	jQuery("#"+action+'_option_'+show+'_'+post_id).slideToggle();	
}

function rating_option_bias( post_id, action1, action2, show ) {
	jQuery("#"+action1+"_"+action2+"_option_"+show+"_"+post_id).slideToggle();	
}

function display_voting( post_id, action ) {
	//jQuery(".post_voting_section").slideUp();
	//jQuery("#post_"+action+"_voting_"+post_id).slideDown();
}

function hide_voting( post_id, action ) {
	//jQuery(".post_voting_section").slideUp();
}

function select_vote_option(dish_val, action) {
	jQuery("#"+action).val(dish_val);
}
	
function fnLoadPosts(form_post, post_id) {
	var d = new Date();
   	var ampm = (d.getHours() >= 12) ? "PM" : "AM";
   	var hours = (d.getHours() >= 12) ? d.getHours()-12 : d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	var glf_time = ((''+hours).length<2 ? '0' :'') + hours+':'+((''+minute).length<2 ? '0':'') + minute+':'+second;
  	//alert( glf_time );

	var month = d.getMonth()+1;
	var day = d.getDate();
	//var year = d.getFullYear().toString().substr(2,2);
	var year = d.getFullYear();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();

				
	var post_client_date = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;	
	var output = ((''+month).length<2 ? '0' : '') + month + '/' + ((''+day).length<2 ? '0' : '') + day + '/' + year + ' ' +glf_time;
	jQuery(".post_rating_action #post_client_date").val(output);

	var informative = jQuery('#user_'+form_post+'_voting_form_'+post_id+' #'+form_post+'_informative').val();
	var bias = jQuery('#user_'+form_post+'_voting_form_'+post_id+' #'+form_post+'_bias').val();
	//alert(informative+' - '+bias);
	/*if(informative != '' && bias == '' )
	{
			
	}*/
	if( informative == '' && bias == '' || informative != '' && bias == '' || informative == '' && bias != '') {
		//alert('Please select informative or bias options');
		jQuery(".error_msg").remove();
		jQuery("#user_"+form_post+"_voting_form_"+post_id).append('<p class="error_msg">Please rate on quality and bias</p>');
	} 
	/*else if( informative == '' ) {
		alert("Select informative Options");
	} else if( bias == '' ){
		alert("Select bias Options");
	}*/ else {
		jQuery("#post_submit").addClass('action_disable');
		jQuery(".error_msg").remove();
		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			datatype: "html",
			data: jQuery("#user_"+form_post+"_voting_form_"+post_id).serialize()+"&action=user_voting_single&post_client_date="+post_client_date,
			success: function( html ) {
				//alert(html);
				if(html != 'Error' || html == '' ){	
					jQuery(".post_content_"+post_id).html(html);
				} else {
					alert('having some technical problem try again later');
				}

				// custom select box
				(function() {
					[].slice.call( document.querySelectorAll('select.cs-select')).forEach( function(el) {	
						new SelectFx(el);
					} );
				})();
				
				jQuery('a.rating_option').click(function(){
					jQuery(this).hide();
					jQuery(this).siblings('a.rating_option').show();
				});
			}
		});
	}
	return false;
}

function fnflagpost( post_id ) {
	
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: jQuery("#flag_post_form_"+post_id).serialize()+"&action=user_flag_report_single",
		success: function( html ) {
			if(html == "Flagged successfully"){
				jQuery('#flag_message_'+post_id).css("display","block");
				jQuery('#flag_post_report_'+post_id).remove();
				jQuery('#flag_message_'+post_id).append('Flagged as inappropriate! Thank you!');
			}
		}
	});
}

function flag_advertisement( post_id ) {
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: "action=user_flag_advertisement_single&post_id="+post_id+"&",
		success: function( html ) {
			if(html == "Flagged successfully"){
				jQuery('.flag_as_adverstiment_message_'+post_id).show();
				jQuery('#flag_advertisement_post_report_'+post_id).remove();
				jQuery('.flag_as_adverstiment_message_'+post_id).append('<p class="success_msg">Flagged as Ad! Thank you!</p>');
			}
		}
	});
}

function flag_as_duplicate( post_id ) {
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: jQuery("#flag_post_form_"+post_id).serialize()+"&action=user_flag_as_duplicate",
		success: function( html ) {
			if(html == "Flag as duplicate successfully"){
				jQuery('#flag_as_duplicate_message_'+post_id).css("display","block");
				jQuery('#flag_as_duplicate_report_'+post_id).remove();
				content = 'Your request to flag this as duplicate has been sent to the moderators. They would check on this and do the needful';
				//jQuery('#flag_as_duplicate_message_'+post_id).append(content);
			}
		}
	});
}

function interval_fn() {
	//setInterval(function(){ system_date_fn(); }, 1000);
	system_date_fn();
}

function system_date_fn() {
	
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
				
	var output = ((''+month).length<2 ? '0' : '') + month + '/' + ((''+day).length<2 ? '0' : '') + day + '/' + year + ' ' +glf_time;
		
	//alert(output);		
	//alert(output);
				
	jQuery("#glf_update").val(output);
	jQuery(".current_system_datetime").val(output);
}

// Search post filter
function search_post_filter( term, tax, load_type, src, term_title ) {
	//alert(term+'-'+tax+'-'+load_type+'-'+src)
	//alert(key+' - '+ajaxurl);
	jQuery.post(
            ajaxurl,
            {
                action: "search_post_filter",
                term: term,
				tax : tax,
				_load : load_type,
				src : src,
				term_title : term_title
            },
            function( response ) {
				//alert(response)
				window.location.href = response;
			}
        );
}

function inner_page_cat_filter( term, tax, load_type, src , term_title) {
	
	
	search_post_filter( term, tax, load_type, src , term_title);	
}

function inner_page_cat_filter_profile(dhis) {
	
	jQuery.post(
            ajaxurl,
            {
                action: "get_profile_cat_image",
                term:  jQuery(dhis).attr("data-term"),
            },
            function( response ) {
				term       = jQuery(dhis).attr("data-term");
				 tax        = jQuery(dhis).attr("data-tax");
				 load_type  = jQuery(dhis).attr("data-load_type");
				 src        = response;
				 term_title = jQuery(dhis).attr("data-term_title");
				  
				search_post_filter( term, tax, load_type, src , term_title);	
				//alert(response)
			}
        );
	
	 	
}

/*function inner_page_tag_filter_profile(dhis) {
	 term       = jQuery(dhis).attr("data-term");
	 tax        = jQuery(dhis).attr("data-tax");
	 load_type  = jQuery(dhis).attr("data-load_type");
	 src        = '';
	 term_title = jQuery(dhis).attr("data-term_title");
	  
	search_post_filter( term, tax, load_type, src , term_title);	
}*/



function remove_old_select_option(dhis) {
	jQuery("body .cs-select-c").removeClass("cs-active");
}

function share_this_post_click(dish, social_type) {
	post_id = jQuery(dish).attr('data-value');
		
	jQuery.post(
		ajaxurl,
		{
			action: "get_title_by_id",
			post_id: post_id,
			type: social_type,
		},
		function( response ) {

			var popup = window.open(response, 'newwindow', 'intent, width=700, height=500,status=yes');
		}
	);
	return false;
}

/*function search_key_press(dish) {
	
	search_val = jQuery(dish).val();
	
	jQuery.ajax({
	  type: "POST",
	  url: ajaxurl,
	  data: {
			action: "get_user_search_key",
			search_val: search_val,
		},
	  dataType: "JSON", //tell jQuery to expect JSON encoded response
	  timeout: 6000,
	  success: function (response) {
		jQuery( "#s" ).autocomplete({
			source: response,
			appendTo: "#searchform_page"
		});
		
	  }
	});
	
	//return false;
}*/

function share_this_post_click_single(dish) {
	url = jQuery(dish).attr('data-value');
	window.open(url, 'newwindow', 'intent,width=700, height=500,status=yes,resizable=1');  
}

jQuery(document).ready(function() {
	
	
jQuery( "#s" ).autocomplete({
     source: function( request, response ) {
         var matcher = new RegExp( "^" + jQuery.ui.autocomplete.escapeRegex( request.term ), "i" );
         response( jQuery.grep( theme_object.search_key, function( item ){
             return matcher.test( item );
         }) );
   },
   appendTo: "#searchform_page,#searchform",
   // minLength: 1,
    
});
	/*jQuery( "#s" ).autocomplete({
			source: theme_object.search_key,
			appendTo: "#searchform_page"
		});*/

	var s = jQuery( '#s' );
    // the search form
    var sForm = s.closest( 'form#searchform_page' );
    sForm.on( 'submit', function( event) {
        event.preventDefault();
        var key = s.val();
		search_post_filter( key, 'search_post', 'search_post', '','' );
    });
	
	jQuery("input#post_tag_text").blur(function(){
		add_tag('');
	});
	
	
	jQuery('body').on('submit', '#searchform', function() {
		
		data_value = jQuery("#s").val();
		if(data_value){
		jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action,.home_head_content").hide();
		jQuery("#filter_taxonomy").val('search_post');
		jQuery("#filter_category").val(data_value);
		var search_in = jQuery("#search_in").val();
		/*hid_base_url = jQuery("#hid_base_url").val();
		jQuery(".page-content.post").hide();
		jQuery("#ng-view").append('<div class="page_loader"><img src='+ hid_base_url.replace("http://","https://") +'/assets/images/ajax_load_content.gif width="150" height="150"/></div>')*/
		if( search_in == 'index' ) {
			var scope = angular.element(document.getElementById('post_filter_by_search')).scope();
			scope.$apply(function() {
				scope.post_filter_by_search('',data_value,'index');
			});
		} else {
			/*var scope = angular.element(document.getElementById('post_filter_by_search')).scope();
			scope.$apply(function() {
				scope.post_filter_by_search();
			});*/
			search_post_filter(data_value,'search_post','search_post','','');
		}
		}	
		return false;
	});
	jQuery('body').on('click', 'ul#ui-id-1 li', function() {
		jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action,.home_head_content").hide();
		data_value = jQuery(this).text();
		jQuery("#filter_taxonomy").val('search_post');
		jQuery("#filter_category").val(data_value);
		/*hid_base_url = jQuery("#hid_base_url").val();
		jQuery(".page-content.post").hide();
		jQuery("#ng-view").append('<div class="page_loader"><img src='+ hid_base_url.replace("http://","https://") +'/assets/images/ajax_load_content.gif width="150" height="150"/></div>')*/
		var search_in = jQuery("#search_in").val();
		if( search_in == 'index' ) {
			var scope = angular.element(document.getElementById('post_filter_by_search')).scope();
			scope.$apply(function() {
				scope.post_filter_by_search('',data_value,'index');
			});
		} else {
			/*var scope = angular.element(document.getElementById('post_filter_by_search')).scope();
			scope.$apply(function() {
				scope.post_filter_by_search();
			});*/
			search_post_filter(data_value,'search_post','search_post','','');
		}
	});
   
				jQuery('.fancybox').fancybox({
					helpers: {
						media: true
					},
					youtube: {
						autoplay: 1, // enable autoplay
						start: 01 // set start time in seconds (embed)
					},
					
					afterLoad: function(current, previous) {
						jQuery('body').addClass('body_fancybox_open');
					},
					afterClose : function(current, previous) {
						jQuery('body').removeClass('body_fancybox_open');
					},
					
					afterShow: function(){
						jQuery(".bx-clone a").swipe( {
							swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
								if(direction == "left"){
									jQuery.fancybox.prev(direction);
									
								}else{
									jQuery.fancybox.prev(direction);
								}
								
							}
							
						});
						
					} // afterShow
				});
				jQuery('.fancybox').fancybox({
					'transitionIn'	:	'elastic',
					'transitionOut'	:	'elastic',
					'speedIn'		:	600, 
					'speedOut'		:	200, 
					'overlayShow'	:	false,
					afterLoad: function(current, previous) {
						jQuery('body').addClass('body_fancybox_open');
					},
					afterClose : function(current, previous) {
						jQuery('body').removeClass('body_fancybox_open');
					},
					afterShow: function(){
						jQuery(".fancybox-wrap").swipe( {
							swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
								if(direction == "left"){
									jQuery.fancybox.prev(direction);
									
								}else{
									jQuery.fancybox.prev(direction);
								}
								
							}
							
						});
						
					} // afterShow
				});
						
	var	allow_cat = jQuery("input[name='allow_cat_id[]']").map(function(){return jQuery(this).val();}).get();
	var	exists_cat_term_id = jQuery("input[name='fp_tax_category[]']").map(function(){return jQuery(this).val();}).get();
	//alert(allow_cat+'\n'+exists_cat_term_id);
	jQuery.each( allow_cat, function( i, val ) {
	  if( jQuery.inArray(val, exists_cat_term_id) != -1 ) {
		  //alert(1);
			jQuery("input#reference_link").removeAttr("required");
			jQuery("#fp_title").removeAttr("readonly");
			jQuery("#fp_title").removeAttr("required");
			jQuery("#fp_refernce_link_home_page_title").removeAttr("readonly");
			return false;
	  } else {
		  //alert(2);
			jQuery("input#reference_link").removeAttr("readonly");
			//jQuery("#fp_title").attr("readonly", "readonly");
			jQuery("#fp_title").removeAttr("required");
			jQuery("#fp_refernce_link_home_page_title").attr("readonly", "readonly");
	  }
	});
	
	reference_link_hidden = jQuery("#reference_link_hidden").val();
	jQuery("#reference_link").val(reference_link_hidden);
	
	total_post = jQuery("#total_post").val();
	
	jQuery("body").on('click', ".time_select_option .cs-options ul li", function() {
		data_val = jQuery(this).attr('data-value');
		jQuery("#hidden_time_selected_val").val(data_val);
	});
	
	jQuery("body").on('click', ".rating_options_select .cs-options ul li", function() {
		data_value = jQuery(this).attr('data-value');
		jQuery(this).parent().parent().parent().siblings('input').val(data_value);
	});
	
	var	allow_cat = jQuery("input[name='allow_cat_id[]']").map(function(){return jQuery(this).val();}).get();
	var	exists_cat_term_id = jQuery("input[name='fp_tax_category[]']").map(function(){return jQuery(this).val();}).get();
	
	jQuery.each( allow_cat, function( i, val ) {
	  if( jQuery.inArray(val, exists_cat_term_id) > 0 ) {
		jQuery("#fp_refernce_link_home_page_title").removeAttr("readonly");
		jQuery("#fp_title").removeAttr("readonly");
	  } else {
		jQuery("#fp_refernce_link_home_page_title").attr("readonly", "readonly");
		//jQuery("#fp_title").attr("readonly", "readonly");
	  }
	});
	find_add_or_edit = jQuery("#find_add_or_edit").val();
	if( find_add_or_edit == 'yes' ) {
		jQuery("#fp_refernce_link_home_page_title").removeAttr("readonly");
		jQuery("#fp_title").removeAttr("readonly");
	}
	
	jQuery("body").on('click', ".frontier_post_dropdown .cs-options ul li", function() {
				
		jQuery("p.error").remove();
		
		cat_term_id = jQuery(this).attr('data-value');
		
		if( cat_term_id != '' && cat_term_id != 0 ) {
			
			cat_name = jQuery(this).find('span').text();
			
			var	exists_cat_term_id = jQuery(".add_new_select_category ul input[name='fp_tax_category[]']").map(function(){return jQuery(this).val();}).get();
				
			if( jQuery.inArray(cat_term_id, exists_cat_term_id) < 0 && exists_cat_term_id) {
				
				content = '<li id="post_'+cat_term_id+'_cat-'+cat_term_id+'" class="post_cat_list post_cat_list_'+cat_term_id+'_'+cat_term_id+'"><a class="post_list_cat post_list_cat-'+cat_term_id+'" onclick="remove_category(this, '+cat_term_id+', '+cat_term_id+');" title="Remove category">X</a><span class="post_cat_name post_cat_name-'+cat_term_id+'"> '+cat_name+' </span><input type="hidden" name="fp_tax_category[]" data-name="'+cat_name+'" value="'+cat_term_id+'"></li>';
			
				jQuery(".add_new_select_category ul").append(content);
			}
			
			var	plublish_date_cat = jQuery("input[name='fp_tax_category[]']").map(function(){return jQuery(this).attr("data-name");}).get();
			
			if( jQuery.inArray('News', plublish_date_cat) != -1 ) {
				jQuery("#date_picker_news").css("display","block");
			} else {
				//jQuery("#date_picker_news").css("display","none");
			}
			
			var	allow_cat = jQuery("input[name='allow_cat_id[]']").map(function(){return jQuery(this).val();}).get();
			var	exists_cat_term_id = jQuery("input[name='fp_tax_category[]']").map(function(){return jQuery(this).val();}).get();
			
			if( theme_object.user_roll != 'user' ) {
				find_add_or_edit = jQuery("#find_add_or_edit").val();
				if( find_add_or_edit == 'yes' ) {
					jQuery("#fp_refernce_link_home_page_title").removeAttr("readonly");
					jQuery("#fp_title").removeAttr("readonly");
				} else {
					jQuery.each( allow_cat, function( i, val ) {
						if( jQuery.inArray(val, exists_cat_term_id) != -1 ) {
							//alert('yes')
							//alert(exists_cat_term_id);
							jQuery("#fp_refernce_link_home_page_title").removeAttr("readonly");
							jQuery("#fp_title").removeAttr("readonly");
							jQuery("#fp_title").attr("required", "");
							jQuery("input#reference_link").attr("readonly", "readonly");
							jQuery("input#reference_link").removeAttr("required");
						}
					});
				}
			}
		}
		
		var dummy_caty = jQuery("select:hidden").val();		
		if( dummy_caty != null ){
			jQuery(".cat_select_action").removeClass("validation_error");
		}else{
			jQuery(".cat_select_action").addClass("validation_error");
		}
		if(jQuery('.validation_error').length<1){
		jQuery(".error_msg_source").remove();
		}
		
	});
	
	var hy_tax_category = "empty_value";
	jQuery('body').change(function(){
		 hy_tax_category = "nochange";
		
	});
	

	/*jQuery(document).ready(function(){
	 
		jQuery("select:hidden").blur(function(){
			//alert();
		var edit_cate = jQuery("#fp_tax_category").val();
		if(edit_cate == null || edit_cate == 0 || hy_tax_category == '' && edit_cate == null){
			jQuery(".cat_select_action").addClass("validation_error");
		}else{
			jQuery(".cat_select_action").removeClass("validation_error");
			}
		
		});
	});
	
	jQuery(document).ready(function(){
		jQuery("input[name=reference_link]").blur(function(){
			var edit_source_url = jQuery("input[name=reference_link]").val();
			if(edit_source_url == ''){
				jQuery("#reference_link").addClass("validation_error");
			}else{
				jQuery("#reference_link").removeClass("validation_error");
			}
		});
	});
	
	jQuery(document).ready(function(){
		jQuery("input[name=user_post_title]").blur(function(){
			var edit_post_name = jQuery("#fp_title").val();
			if(edit_post_name == ''){
				jQuery("#fp_title").addClass("validation_error");
			}else{
				jQuery("#fp_title").removeClass("validation_error");
			}
		});
	});
	*/
	jQuery(document).ready(function(){
		jQuery("input[name=post_read_time]").blur(function(){
		
		var edit_post_read_time = jQuery("#post_read_time").val();
			if(edit_post_read_time == ''){
				jQuery("#valid_post_read_time").addClass("validation_error");
			}else{
				jQuery("#valid_post_read_time").removeClass("validation_error");
			}
			if(jQuery('.validation_error').length<1){
				jQuery(".error_msg_source").remove();
			}
		});
		jQuery("input[name=user_post_title]").blur(function(){
		
			var user_post_title = jQuery(this).val();
			if(user_post_title == ''){
				jQuery(this).addClass("validation_error");
			}else{
				jQuery(this).removeClass("validation_error");
			}
			if(jQuery('.validation_error').length<1){
				jQuery(".error_msg_source").remove();
			}
		});		
		
	});
	
		
	jQuery("body").on('click', '#user_post_publish, #user_post_save', function(e) {
		
		//alert(1);
		//alert(hy_tax_category);
		//alert(dummy);
		cat = jQuery("#fp_tax_category").val();
		cat_error = '';
		if( cat == '' || cat == null|| cat == 0 ) {
			//alert(2);
			jQuery("p.error_msg").remove();
			cat_error = '<div class="empty_cat_error select_box_error"><p class="error_msg">You did not select a category!</p></div>';
			//jQuery(cat_error).insertBefore(".frontier-post-taxonomies");
			
			require_error = '<div class="require_error"><p class="error_msg">All post requirements not met!</p></div>';
			//jQuery(require_error).insertBefore(".button");
			
			e.preventDefault();
		} else {
			//alert(3);
			jQuery("p.error_msg").remove();
		}
		
		post_read_time = jQuery("#post_read_time").val();		
		if( post_read_time == '' || post_read_time == null|| post_read_time == 0 ) {
			//alert(2);
			jQuery(".error_msg_source").remove();
			src_error = '<p class="error_msg_source">You did not enter a Source Length!</p>';
			//jQuery(".source_required_msg,.source_required_msg_btm").html(src_error);
			e.preventDefault();
		} else {
			//alert(3);
			jQuery(".error_msg_source").remove();
		}
		
		
		var	find_cats = jQuery(".add_new_select_category ul li input[name='fp_tax_category[]']").map(function(){return jQuery(this).attr("data-name");}).get();
		/*if( jQuery.inArray('News', find_cats) != -1 ) {
			alert(4);
			var publish_date = jQuery("#publish_date_news_manual").val();
			var date_validation = jQuery("#date_validation").val();
			//alert(date_validation);
			if( publish_date == '' ) {
				jQuery(".publish_date_error").remove();
				jQuery("#date_picker_news").append('<div class="publish_date_error"><p>Missing Date Published!</p></div>');
				
				jQuery(".require_error").remove();
				require_error = '<div class="require_error"><p class="error_msg">All post requirements not met!</p></div>';
				jQuery(require_error).insertBefore(".button");
				
				e.preventDefault();
				
			} else if( date_validation != 'valid' ) {
				alert(6);
				jQuery(".require_error").remove();
				require_error = '<div class="require_error"><p class="error_msg">All post requirements not met!</p></div>';
				jQuery(require_error).insertBefore(".button");
				e.preventDefault();
			} else {
				alert(7);
				jQuery(".publish_date_error").remove();
			}	
		}*/
		
		var word_count_error = jQuery("#word_count_error_hidden").val();
		if( word_count_error && word_count_error != '' ) {
			//alert(8);
			jQuery(".word_count_plulish_error").remove();
			jQuery('<div class="word_count_plulish_error"><p>'+word_count_error+'</p></div>').insertBefore("#user_post_publish");
			jQuery('<div class="word_count_plulish_error"><p>'+word_count_error+'</p></div>').insertBefore("#user_post_save");
			e.preventDefault();
		}
		
		if( cat_error && cat_error != '' ) {
			//alert(9);
			//jQuery(cat_error).insertBefore("#user_post_publish");
			//jQuery(cat_error).insertBefore("#user_post_save");
			e.preventDefault();
		}
		
		//source_url = jQuery("#reference_link").val();
		source_url = jQuery("input[name=reference_link]").val();
		post_name = jQuery("#fp_title").val();
		post_read_time = jQuery("#post_read_time").val();
		
		if( source_url == '' && post_name == '' || post_read_time == '' ) {
			//alert(10);
			jQuery(".require_error").remove();
			require_error = '<div class="require_error"><p class="text-red">All post requirements not met!</p></div>';
			//jQuery(require_error).insertBefore(".button");
			e.preventDefault();
		} else {
			//alert(11);
			jQuery("p.text-red").remove();	
		}
		
		
		
		//test = jQuery('select option').first().prop('selected', true);​
		//var test = jQuery('#fp_tax_category option:selected').text();
		
		//cate = jQuery("#fp_tax_category").val();
		//post_read_time = jQuery("#post_read_time").val();
		//source_url = jQuery("input[name=reference_link]").val();
		//post_name = jQuery("#fp_title").val();
		//alert(cate);
		//alert(test);
		//alert (JSON.stringify(test));
		//alert(source_url);
		
		//alert(hy_tax_category);
		
		if(cat == null || cat == 0 || hy_tax_category == 'empty_value'){
			
			jQuery(".cat_select_action").addClass("validation_error");
			
		}else{
			jQuery(".cat_select_action").removeClass("validation_error");
			}
		if(post_read_time == ''){
			jQuery("#valid_post_read_time").addClass("validation_error");
		}else{
			jQuery("#valid_post_read_time").removeClass("validation_error");
			}
		if(source_url == ''){
			jQuery("#reference_link").addClass("validation_error");
		}else{
			jQuery("#reference_link").removeClass("validation_error");
		}
		if(post_name == ''){
			jQuery("#fp_title").addClass("validation_error");
		}else{
			jQuery("#fp_title").removeClass("validation_error");
		}
		//var dummy_caty = jQuery("select:hidden").val();		
		///if( dummy_caty != null ){
			//jQuery(".cat_select_action").removeClass("validation_error");
		//}else{
			///jQuery(".cat_select_action").addClass("validation_error");
		//}
		if(cat == '' || cat == null || post_read_time == '' || source_url == '' || post_name == ''){
			//alert("condition sucess");
			src_error = '<p class="error_msg_source">All post requirements not met!</p>';
			jQuery(".source_required_msg,.source_required_msg_btm").html(src_error);
			e.preventDefault();
			
			}
		
	});

	jQuery('#frontier_post').keypress(function(e) {
		if(e.which == 13) { // Checks for the enter key
			var tag = jQuery("#post_tag_text").val();
			if( tag != '' ) {
				add_tag('new_post');
				e.preventDefault(); // Stops IE from triggering the button to be clicked
			} else {
				
			}
		}
	});
		
	autocomplete_action(); // trigget auto complete function for add new post
	
	// Category popup close action
	jQuery('.post_cat_trigger').on('click', function() {
		jQuery(".post_cat_modal ul li.popup_list_cat").removeClass('active');
		jQuery(".post_cat_modal ul li").find('input').removeAttr('checked');
		jQuery('.post_cat_modal_wrapper').removeClass('post_cat_open');
		jQuery('.post_cat_image_modal_wrapper').removeClass('post_cat_image_open');
		jQuery('.page-wrapper').toggleClass('blur-it');
	});
	
	jQuery('.pop_editor_trigger').on('click', function() {
		jQuery(".popup_editor_modal_wrapper").removeClass("popup_editor_modal_open");
		jQuery('.page-wrapper').toggleClass('blur-it');
	});
	// Category popup close action end
	  
	// Selecte category to add post
	jQuery(".post_cat_modal ul li input").click(function() {
		cat_id = jQuery(this).val();
		cat_name = jQuery(this).attr('data-name');
		post_id = jQuery("#cat_post_id").val();
		if(jQuery(this).prop('checked') == true) {
			jQuery(this).parent().addClass('active');
			content = '<li id="post_'+post_id+'_cat-'+cat_id+'" class="post_cat_list post_cat_list_'+post_id+'_'+cat_id+'"><a class="post_list_cat post_list_cat-'+cat_id+'" onclick="remove_category(this, '+cat_id+', '+post_id+');" title="Remove category">X</a><span class="post_cat_name post_cat_name-'+cat_id+'"> '+cat_name+' </span></li><input type="hidden" name="fp_tax_category[]" value="'+cat_id+'">';
			jQuery(".post_of_list_cat_section .post_of_cat_list_"+post_id+" ul").append(content);
		} else {
			jQuery(this).parent().removeClass('active');
			jQuery(".post_cat_list_"+post_id+"_"+cat_id+"").remove();
		}

		jQuery.ajax({
			url: ajaxurl,
			type: "post",
			data: {
				action: 'add_and_remove_cat_to_post',
				post_id : post_id,
				post_category: jQuery("input[name='post_cat_checkbox[]']").map(function(){if(jQuery(this).prop('checked') == true) {return jQuery(this).val();}}).get()
				},
			success: function(data, textStatus, jqXHR) {
				//alert(textStatus+' '+jqXHR);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				//alert(' Sorry your post categories are not saved please try again later');
			}
		});
	});
	// Selecte category to add post end
	
	interval_fn();
	
});

/*jQuery(window).scroll(function(){
	alert();
	if( jQuery(window).scrollTop() + 50 > (jQuery(document).height() - jQuery(window).height()) - 1000) {
		var scroll_post = document.getElementById('scroll_load_post');
		var scope = angular.element(scroll_post).scope();
	 		scope.$apply(function() {
			scope.scroll_load_post();
	 });
	}
});
*/


function fnResendActivationMail() {
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: jQuery("#register").serialize()+"&action=resend_activation_mail",
		success: function( html ) {
			jQuery('.reg_error').hide();
			jQuery('.reg_success').hide();
			jQuery('<div class="reg_success">'+html+'</div>').insertBefore("form#register");
			
			//jQuery('.reg_error').innerHTML="Activation mail resend to your registered email. Please check.";
			
			//jQuery('.reg_error').html("Registration confirmation resent! Click <a href='javascript:void(0);' onclick='"+fnResendActivationMail()+"'>here</a> to resend.");
			//jQuery('.reg_success').html("Registration confirmation resent! Click <a href='javascript:void(0);' onclick='"+fnResendActivationMail()+"'>here</a> to resend.");
		}
	});
}
function fnResendActivationMail2(user_name,user_email){
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: jQuery("#register").serialize()+"&action=resend_activation_mail&user_log="+user_name+"&user_eml="+user_email,
		success: function( html ) {
			jQuery('.reg_error').hide();
			jQuery('.reg_success').hide();
			split_html = html.split('{@0');
			jQuery('<div class="reg_success">'+split_html[0]+'</div>').insertBefore("form#register");
			jQuery('<div class="reg_success">'+split_html[0]+'</div>').insertBefore(".wppb-user-forms");
			jQuery(".login_error.error_msg").hide();
			
			//jQuery('<div class="reg_success">'+split_html[0]+'</div>').insertBefore("#wppb-login-wrap");
			
			
			//jQuery('.reg_error').innerHTML="Activation mail resend to your registered email. Please check.";
			//jQuery('.reg_error').html("Registration confirmation resent! Click <a href='javascript:void(0);' onclick='"+fnResendActivationMail2(user_name,user_email)+"'>here</a> to resend.");
			//jQuery('.reg_success').html("Registration confirmation resent! Click <a href='javascript:void(0);' onclick='"+fnResendActivationMail2(user_name,user_email)+"'>here</a> to resend.");
		}
	});
}

function current_system_time(){
	var d = new Date();
   	var ampm = (d.getHours() >= 24) ? "PM" : "AM";
   	var hours = (d.getHours() >= 24) ? d.getHours()-24 : d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	var glf_time = ((''+hours).length<2 ? '0' :'') + hours+':'+((''+minute).length<2 ? '0':'') + minute+':'+second;
  	//alert( glf_time );

	var month = d.getMonth()+1;
	var day = d.getDate();
	//var year = d.getFullYear().toString().substr(2,2);
	var year = d.getFullYear();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	
	var output = year +'-'+((''+month).length<2 ? '0' : '') + month+'-'+ ((''+day).length<2 ? '0' : '') + day + ' ' + glf_time;
	data = {
			'action': 'standard_timing',
			'posted_date' : output,
		}
	jQuery.post(ajaxurl, data, function(response){
	});
				
	
}
/*function standard_timing_for_fav(){
	jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		data: just_data,
		success: function( html ) {
			alert("html");
			 // $('#add').val('data sent sent');
              //$('#msg').html(html);
		}
	});
	
	}
	
	*/