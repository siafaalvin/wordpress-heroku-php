app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $window, $http, Data) {
	//$scope.data =[];
	$scope.popupHeight = $window.innerWidth;
	$scope.base_url = theme_obj.base_url;
	$scope.user_access = theme_obj.user_access;
	$scope.base_path = 'wp-content/themes/hyroglf/angular/api/v1';
	$scope._load = theme_obj._load;
	$scope.tax = theme_obj.tax;
	$scope.term = theme_obj.term;
	$scope.term_title = theme_obj.term_title;
	$scope.order = 'DESC';
	$scope.order_class = '';
	$scope.informative = '';
	$scope.bias = '';
	$scope.most_view = 0;
	$scope.scroll_page = 0;
	$scope.mobile_view = true;
	$scope.feed_favourites = true;
	$scope.most_viewed_filter_value = '';
	//initially set those objects to null to avoid undefined error
	$scope.data = [];
	if($scope.popupHeight < 500){
		$scope.window_size = 1;
	} else {
		$scope.window_size = 0;
	}
	if(theme_obj.user_role == 'user'){
		$scope.feed_favourites = true;
	}
	if ($scope._load == 'search_post') {

		//jQuery(".wiki_content.wiki_center_section").append('<div class="page_loader"><img src='+ $scope.base_url.replace("http://","https://") +'/assets/images/ajax_load_content.gif width="150" height="150"/></div>');
		jQuery(".filter_post_img.cat_image_header_sec").hide();
		//alert("auth control-line31");
		//$scope.selected_category = 'Search results for "' + $scope.term + '"';
		jQuery(".post_filter_text").addClass("search_res");
		jQuery(".post_heade_edit_action").hide();
		jQuery(".post_filter_text").addClass("search_res");
		//$scope.selected_category_img = '<img src="' + $scope.base_url + '/assets/images/search_symbol.png" width="150" height="150"/>';
		//jQuery(".filter_post_img").hide();
		//setTimeout(function(){
			jQuery(".home_head_content ").hide();
		//},100);
	} else if ($scope._load == 'cat_post' && $scope.tax == 'category') {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".home_head_content ").hide();
		jQuery(".filter_post_img").show();
		$scope.selected_category = $scope.term_title;
		if (theme_obj.term_src) {
			$scope.selected_category_img = '<img src="' + theme_obj.term_src + '" width="150" height="150"/>';
		} else {
			$scope.selected_category_img = '';
		}
	} else if ($scope._load == 'cat_post' && $scope.tax == 'post_tag') {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".filter_post_img").show();
		jQuery(".filter_post_img,.post_heade_edit_action").show();
		$scope.selected_category = $scope.term_title;
		$scope.selected_category_img = '<img src="' + $scope.base_url + '/assets/images/search_symbol.png" width="150" height="150"/>';
	} else if ($scope._load == 'cat_post' && $scope.tax == 'post_filter_by_author') {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".filter_post_img").show();
		$scope.selected_category = $scope.term+"'s " +'POV';
		$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
		jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action").hide();
	}  else {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".home_head_content ").show();
		jQuery(".filter_post_img").show();
		// $scope.selected_category = 'Recently Added/Edit';
		// $scope.selected_category_img = '<i class="fas fa-clock fa-3x"></i>';
	}


	$scope.loadrecords = function (_load, wpdb, user_id, user_access, tax, term, page_num, order, user_role, user_ip, plugin_url, site_url, base_url) {
		jQuery(".sort_section").show();
		jQuery("#num_of_scroll").val(1);
		$http({
			method: 'post',
			data: {
				'load': _load,
				'wpdb': wpdb,
				'user_id': user_id,
				'user_access': user_access,
				'tax': tax,
				'term': term,
				'page_num': page_num,
				'order': order,
				'user_role': user_role,
				'user_ip': user_ip,
				'plugin_url': plugin_url,
				'site_url': site_url,
				'base_url': base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json; charset=utf-8'
			}
		}).
		success(function (data, status, headers, config) {

			if(tax == 'post_tag'){
				$scope.selected_category = "Tag results for '" + term +"'";
				jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action,.home_head_content").hide();

			} else {
				jQuery(".filter_post_img,.post_heade_edit_action").show();
			}

			if(_load =='search_post'){
				$scope.selected_category = 'Search results for "' + $scope.term + '"';
				//jQuery(".wiki_content.wiki_center_section .page_loader").remove();
				jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action,.home_head_content,.search_box,random_list").hide();

			}
			if($scope.popupHeight < 1050){
				jQuery(".ajax_filter_content .filter_post_img.cat_image_header_sec").hide();
			}

			$scope.most_view = 0;
			$scope.data = [];
			$scope.result = data.post_data;
			//console.log($scope.result.text);
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			if ($scope._load == 'cat_post' && $scope.tax == 'post_filter_by_author') {
				jQuery(".filter_post_img,.post_heade_edit_action,.home_head_content").hide();
			}
			post = {}

			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
			if (_load == 'random') {
				jQuery(".post_heade_edit_action").hide();
				if($scope.popupHeight < 993){
					jQuery(".filter_post_img").hide();
				} else{
					jQuery(".filter_post_img").show();
				}
				jQuery(".sort_section").hide();
				$scope.selected_category = 'Random Post';
				//$scope.selected_category = 'Recently Added/Edited';
				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/random_icon.png width="150" height="150"/>';
				jQuery(".home_head_content ").hide();
				rand_id = data.post_data[0].post_id;
				$scope.trigger_rand_actions(rand_id);
			}
			jQuery(".home_content_section").show();
		}).
		error(function (data, status, headers, config) {
			//console.log('Sorry having some error');
		});
	}

	if ($scope._load == '') {
		$scope._load = 'init';
	}

	$scope.loadrecords($scope._load, theme_obj.wpdb, theme_obj.user_id, theme_obj.user_access, $scope.tax, $scope.term, 1, 'DESC', theme_obj.user_role, theme_obj.user_ip, theme_obj.plugin_url, theme_obj.site_url, theme_obj.base_url); // Load datas



	jQuery("#myprofile_votes").click(function(){
		$scope.most_viewed_filter_value = '';
		$scope.myprofile_votes();
	});
	jQuery("#myprofile_posts").click(function(){
		$scope.most_viewed_filter_value = '';
		$scope.post_filter_by_author();
	});

	$scope.random_post = function () {
	$scope.most_viewed_filter_value = '';
	jQuery(".post_heade_edit_action").hide();
	jQuery(".filter_post_img").show();
	if($scope.popupHeight < 993){
		jQuery(".home_head_content").show();
			jQuery(".home_head_content ").css("border-width","0");
			jQuery(".header_left_content_inner").show();
			jQuery(".header_right_content").hide();
			jQuery("body").addClass("full_width_header");
	}
			if($scope.popupHeight > 992){
				//jQuery(".wiki_topbar_right.hide-767 ul.top_search").appendTo("footer .container .row");
				jQuery(".header_left_content").show();
			}
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope._load = 'random';
		$scope.most_view = 0;
		$scope.selected_category = 'Random Post';
		//$scope.selected_category = 'Recently Added/Edited';
		$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/random_icon.png width="150" height="150"/>';
		$scope.loadrecords($scope._load, theme_obj.wpdb, theme_obj.user_id, theme_obj.user_access, '', '', 1, 'DESC', theme_obj.user_role, theme_obj.user_ip, theme_obj.plugin_url, theme_obj.site_url, theme_obj.base_url); // Load datas
		$http({
			method: 'post',
			data: {
				'load': 'random',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': '',
				'term': '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			jQuery(".sort_section").hide();
			$scope.data = data.post_data;
			rand_id = data.post_data[0].post_id;
			$scope.trigger_rand_actions(rand_id);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.trigger_rand_actions = function (rand_id) {
		$scope.most_viewed_filter_value = '';
		setTimeout(function () {
			jQuery(".list_post_content").css("display", "block");
			jQuery(".view_list_post").hide();
			jQuery(".pop_edit_tex").show();
			jQuery(".close_list_post").show();
			$scope.view_content_click('', rand_id);
			setTimeout(function () {
				jQuery(".single_post_multi_image_slide").show();
				jQuery(".single_post_multi_image_slide").bxSlider({
					pager: false,
					preloadImages: 'all',
				});
				jQuery('.fancybox').fancybox({
					helpers: {
						media: true
					},
					youtube: {
						autoplay: 1, // enable autoplay
						start: 01 // set start time in seconds (embed)
					},

				});
			}, 1100); // Image sliding
		}, 50);
	}
	$scope.cat_post_filter_click = function ($event, tax, cat_item, cat_name, cat_image, page_of) {

		if(cat_item == 'recent_post'){
			if($scope.popupHeight > 1050){
				jQuery(".header_right_content").show();
			}
		}
		$scope.most_viewed_filter_value = '';
		jQuery(".post_filter_text").removeClass("search_res");
		if($scope.popupHeight < 1050){

			jQuery(".ajax_filter_content .filter_post_img.cat_image_header_sec").hide();
		}
		jQuery(".post_heade_edit_action").show();
		$scope.scroll_page = 0;
		jQuery(".sort_section").show();
		//jQuery(".filter_post_img").show();
		$scope.catname_on_click = cat_item;
		if($scope.popupHeight < 993)
		jQuery(".home_head_content").hide();

		if(cat_item != 'recent_post'){
			jQuery(".header_left_content_inner").show();
			jQuery(".header_right_content").hide();
			jQuery("body").addClass("full_width_header");
			if($scope.popupHeight > 992){
				//jQuery(".wiki_topbar_right.hide-767 ul.top_search").appendTo("footer .container .row");
				jQuery(".header_left_content").show();
				jQuery(".home_head_content ").css("border-width","0");
			}
		}
		if(cat_item == 'recent_post'){
			jQuery(".home_head_content").show();
			jQuery(".header_left_content_inner").show();
			if($scope.popupHeight > 1050){
				jQuery(".header_right_content").show();
			}
			//jQuery(".header_right_content").show();
			jQuery("body").removeClass("full_width_header");
			if($scope.popupHeight > 992){
				jQuery(".home_head_content ").css("border-width","1px");
				//jQuery("footer .container .row ul.top_search").appendTo(".wiki_topbar_right.hide-767");
				jQuery(".header_left_content").show();
			}
		}
		jQuery(".loaded_cat").remove();
		if (!tax && !cat_item && !cat_name && !cat_image) {
			cat_item = jQuery("#filter_category").val();
			tax = jQuery("#filter_taxonomy").val();
			cat_name = jQuery("#filter_cat_name").val();
			cat_image = jQuery("#filter_cat_img").val();
		}
		jQuery("#num_of_scroll").val(1);
		if (cat_item == 'most_viewed') {
			$scope.most_view = 1;
			jQuery(".sort_tab_sections.most_viewed_action").show();
		} else {
			$scope.most_view = 0;
		}
		if($scope.popupHeight < 993){
			jQuery(".filter_post_img.cat_image_header_sec").hide();
		} else {
			jQuery(".post_heade_edit_action,.filter_post_img.cat_image_header_sec").show();
		}
		order = 'DESC';
		$scope.order = order;
		$scope.order_class = '';
		$scope._load = 'cat_post';
		$scope.tax = tax;
		$scope.term = cat_item;
		$scope.selected_category = cat_name;
		if (cat_item == 'recent_post') {
			$scope.selected_category_img = '<i class="fas fa-clock fa-3x"></i>';
		} else if (cat_item == 'most_viewed') {
			$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/Most-Viewed.jpg width="150" height="150"/>';
		} /*else if (cat_item == 'my_posts_and_votes') {
			//$scope.selected_category = 'MY POSTS (' + theme_obj.user_post_vote_count.post_count + ') + VOTES (' + theme_obj.user_post_vote_count.vote_count + ')';
			//$scope.selected_category = "My POV";
			//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/pencil_sign.png width="150" height="150"/>';
			$scope.selected_category_img = '';
		} */ else if (cat_name == 'My Profile') {
			if($scope.popupHeight > 992){
				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/pencil_sign.png width="150" height="150"/>';
			} else{
				$scope.selected_category_img ='';
			}
		}else if (cat_item == 'other-info') {

				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/GLF-Favicon.png width="150" height="150"/>';

		} else if (cat_item == 'favorite_posts') {
			if($scope.popupHeight > 992){
				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/favorites_star_icon.png width="150" height="150"/>';
			} else{
				$scope.selected_category_img ='';
			}
		} else if (cat_item == 'other-info') {
			$scope.selected_category_img = '';
		} else {
			$scope.selected_category_img = '<img src=' + cat_image + ' width="150" height="150"/>';
		}

		jQuery(".post_cat_image_modal_wrapper").removeClass("post_cat_image_open");

		var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});

		$http({
			method: 'post',
			data: {
				'load': (cat_item == 'most_viewed') ? 'most_viewed' : 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': tax,
				'term': cat_item,
				'page_num': 1,
				'order': order,
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_cat_post',
		}).
		success(function (data, status, headers, config) {
			jQuery(".views_count_for_mv").hide();
			jQuery(".filter_post_img,.post_heade_edit_action").show();
			if($scope.popupHeight < 993){
				jQuery(".filter_post_img.cat_image_header_sec").hide();
			} else {
				jQuery(".post_heade_edit_action,.filter_post_img.cat_image_header_sec").show();
			}
			//$scope.post.post_meta.post_ref_link_favicon=$scope.selected_category_img;
			$scope.data = [];
			//$scope.data = data.post_data.post_data;

			$scope.result = data.post_data.post_data;
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			//var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
				//if(cat_item != 'recent_post'){
					//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
				//}
			}
			jQuery("#num_of_page").val(data.post_data.total_page);


		}).
		error(function (data, status, headers, config) {});
	}

	$scope.cat_tag_post_filter_click = function ($event, tax, cat_item, page_of) {
		$scope.most_viewed_filter_value = '';
		jQuery(".post_filter_text").removeClass("search_res");
		if($scope.popupHeight < 1050){
			jQuery(".ajax_filter_content .filter_post_img.cat_image_header_sec").hide();
		}
		/*if(tax != 'category'){
			jQuery(".filter_post_img,.post_heade_edit_action").hide();
		} else {
			jQuery(".filter_post_img,.post_heade_edit_action").show();
		}*/
			//co

		jQuery(".home_head_content").hide();
		jQuery(".sort_section").show();
		//jQuery(".filter_post_img").show();
		if(cat_item.slug == 'other-info'){
			$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/GLF-Favicon.png width="150" height="150"/>';
		}
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		//alert(tax+' - '+cat_item.slug);

		order = 'DESC';
		$scope.order = order;
		$scope.order_class = '';
		$scope._load = 'cat_post';
		$scope.tax = tax;
		$scope.term = cat_item.slug;
		cat_slug = cat_item.slug;
		//$scope.selected_category = cat_item.name;

		jQuery(".post_cat_image_modal_wrapper").removeClass("post_cat_image_open");

		var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});

		$http({
			method: 'post',
			data: {
				'load': 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': tax,
				'term': cat_slug,
				'term_id': cat_item.term_id,
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_cat_post',
		}).
		success(function (data, status, headers, config) {
			jQuery(".views_count_for_mv").hide();
			if($scope.popupHeight < 993){
				jQuery(".filter_post_img.cat_image_header_sec").hide();
			} else {
				jQuery(".post_heade_edit_action,.filter_post_img").show();
				if(tax != 'category'){
					jQuery(".filter_post_img.cat_image_header_sec").hide();
				}

			}

			//console.log(data);
			if (data.term_img.term_image) {
				$scope.selected_category_img = '<img src=' + data.term_img.term_image.replace('http://',"https://") + ' width="150" height="150"/>';
				//$scope.selected_category_img = '<img src=' + data.term_img.term_image + ' width="150" height="150"/>';
			}
			if (tax == 'post_tag') {
				$scope.selected_category = "Tag results for '"+cat_item.name+"'";
				//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
				$scope.selected_category_img = '';
			} else {
				$scope.selected_category = cat_item.name;
			}
			$scope.data = [];
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data.post_data;
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
				//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
			}
			jQuery("#num_of_page").val(data.post_data.total_page);

		}).
		error(function(data, status, headers, config) {});
	}

	$scope.view_content_click = function (event, post) {
		if (post.post_id) {
			post_id = post.post_id;
		} else {
			post_id = post;
		}
		hyroglf_analytics_home_feed(post.post_id);

		jQuery(".single_post_multi_image_slide_" + post_id + ",.single_post_multi_video_slide_" + post_id).hide();
		jQuery(".user_info_and_bias_rated_source_"+post_id).show();
		jQuery(".post_modified_" + post_id).show();
		jQuery(".post_share_section_" + post_id).show();
		//jQuery(".source_publish_date_" + post_id).show();
		//jQuery(".glf_update_date_" + post_id).show();
		jQuery("#view_list_post_" + post_id).hide();
		jQuery("#close_list_post_" + post_id).show();
		jQuery("#list_post_edit_btn_" + post_id).show();
		jQuery("#list_post_content_" + post_id).show();
		jQuery(".flag_inappropriate_popup_content_" + post_id).show();
		$http({
			method: 'post',
			data: {
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'post_id': post_id,
			},
			url: $scope.base_path + '/get_post_by_id',
		}).
		success(function (data, status, headers, config) {
			if (data) {

				jQuery(".single_post_multi_image_slide_" + post_id + ",.single_post_multi_video_slide_" + post_id).show();
				jQuery(".single_post_image_section_" + post_id).html(data);
				jQuery(".entry.list_post_content .post_content_section a").attr("target","_blank");
				jQuery(".single_post_multi_image_slide_" + post_id + ",.single_post_multi_video_slide_" + post_id).bxSlider({
					pager: false,
					adaptiveHeight: true,
					preloadImages: 'all',
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
			}
		}).
		error(function (data, status, headers, config) {
			$scope.messageFailure(data.message);
		});
	}

	$scope.close_the_content_click = function (post) {
		jQuery(".user_info_and_bias_rated_source_"+post_id).hide();
		post_id = post.post_id;
		jQuery(".post_modified_" + post_id).hide();
		jQuery(".post_share_section_" + post_id).hide();
		//jQuery(".source_publish_date_" + post_id).hide();
		//jQuery(".glf_update_date_" + post_id).hide();
		jQuery("#view_list_post_" + post_id).show();
		jQuery("#close_list_post_" + post_id).hide();
		jQuery("#list_post_edit_btn_" + post_id).hide();
		jQuery("#list_post_content_" + post_id).hide();
	}

	$scope.rating_option = function (post, action, type) {
		$scope.most_viewed_filter_value = '';
		if (type == 'show') {
			jQuery("." + action + "_action_show_" + post.post_id).hide(); // Plus image hide
			jQuery("." + action + "_action_close_" + post.post_id).show(); // Close image show
			jQuery("." + action + "_option_" + post.post_id).show(); // Options show
		} else if (type == 'close') {
			jQuery("." + action + "_action_show_" + post.post_id).show(); // Plus image show
			jQuery("." + action + "_action_close_" + post.post_id).hide(); // Close image hide
			jQuery("." + action + "_option_" + post.post_id).hide(); // Options hide
		}
	}

	$scope.fnLoadPosts = function (post_data, form_post, post, type) {
		var d = new Date();
   	var ampm = (d.getHours() >= 12) ? "PM" : "AM";
   	var hours = (d.getHours() >= 12) ? d.getHours()-12 : d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	var glf_time = ((''+hours).length<2 ? '0' :'') + hours+':'+((''+minute).length<2 ? '0':'') + minute+' '+ampm;
  	//alert( glf_time );

	var month = d.getMonth()+1;
	var day = d.getDate();
	//var year = d.getFullYear().toString().substr(2,2);
	var year = d.getFullYear();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();

	var post_client_date = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
    var months = ["","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    month1 = months[3];
	var output =  ' '+month1 + ' ' + ((''+day).length<2 ? '0' : '') + day + ' ' + year + ' at ' +glf_time;
	jQuery(".post_rating_action #post_client_date").val(output);
		if (!post) {
			//alert(1);
			post_id = jQuery("#set_post_id").val();
		} else {
			//alert(2);
			post_id = post.post_id;
		}
		informative = '';
		bias = '';
		informative_val = '';
		bias_val = '';
		if (!post_data) {
			//alert(3);
			option = jQuery("#set_vote_option").val();
			if (option == 'very' || option == 'somewhat' || option == 'not_really') {
				//alert(4);
				informative = option;
				informative_val = option;
			} else if (option == 'liberal' || option == 'neutral' || option == 'conservative') {
				//alert(5);
				bias = option;
				bias_val = option;
			}
		} else {
			//alert(6);
			informative = (post_data.informative) ? post_data.informative : '';
			bias = (post_data.bias) ? post_data.bias : '';
			informative_val = (post_data.informative) ? post_data.informative : '';
			bias_val = (post_data.bias) ? post_data.bias : '';
		}

		if( informative == '' && bias == '' || informative != '' && bias == '' || informative == '' && bias != '') {
			//alert(7);
			var msg = '<p class="error_msg option_validate">Please rate on quality and bias</p>';
			//alert(msg);
			jQuery(".option_validate").remove();
			jQuery(".error_class_rating_" + post_id).remove();
			jQuery("form#user_infer_bias_voting_form_" + post_id).append(msg);
			jQuery("body .error_class_rating_" + post_id).append(msg);

		} else if( informative == '' &&  !bias) {
			//alert(8);
			var msg = '<p class="error_msg option_validate">Please select informative options</p>';
			//alert(msg);
			jQuery(".option_validate").remove();
			jQuery(".error_class_rating_" + post_id).remove();
			jQuery("form#user_infer_bias_voting_form_" + post_id).append(msg);
			jQuery(".error_class_rating_" + post_id).append(msg);
		} else if( bias == '' && !informative ) {
			//alert(9);
			var msg = '<p class="error_msg option_validate">Please select bias options</p>';
			//alert(msg);
			jQuery(".option_validate").remove();
			jQuery(".error_class_rating_" + post_id).remove();
			jQuery("form#user_infer_bias_voting_form_" + post_id).append(msg);
			jQuery(".error_class_rating_" + post_id).append(msg);
		} else {
			//alert(10);
			//alert(informative+' - '+bias);
			//jQuery(".post_submit_"+post_id).addClass('action_disable');
			jQuery(".post_submit_" + post_id).replaceWith('<a href="javascript:void(0);" id="post_submit" class="post_submit_' + post_id + ' action_disable">Submit</a>');
			jQuery(".option_validate").remove();
			$http({ // Load posts from the WordPress API
				method: 'POST',
				url: theme_obj.ajax_url + '/?action=user_voting',
				params: {
					'post_id': post_id,
					'post_client_date' : post_client_date,
					'informative': informative,
					'bias': bias
				},
			}).
			success(function (data, status, headers, config) {
				if (data.message == 'success') {
					jQuery(".list_post_rating_section_" + post_id).empty();
					var info = "'infermative'";
					var bias = "'bias'";
					var informative_high_vote_1 = '';
					var informative_high_vote_2 = '';
					var informative_high_vote_3 = '';
					if (data.informative_high_vote) {
						var informative_high_vote_1 = (data.informative_high_vote[0]) ? data.informative_high_vote[0].toLowerCase() : '';
						var informative_high_vote_2 = (data.informative_high_vote[1]) ? data.informative_high_vote[1].toLowerCase() : '';
						var informative_high_vote_3 = (data.informative_high_vote[2]) ? data.informative_high_vote[2].toLowerCase() : '';
					}
					var bias_high_vote_content_1 = '';
					var bias_high_vote_content_2 = '';
					var bias_high_vote_content_3 = '';
					if (data.bias_high_vote) {
						var bias_high_vote_content_1 = (data.bias_high_vote[0]) ? data.bias_high_vote[0].toLowerCase() : '';
						var bias_high_vote_content_2 = (data.bias_high_vote[1]) ? data.bias_high_vote[1].toLowerCase() : '';
						var bias_high_vote_content_3 = (data.bias_high_vote[2]) ? data.bias_high_vote[2].toLowerCase() : '';
					}
					jQuery(".success_list_rating_vote_" + post_id).show();
					very_count = '';
					somewhat_count = '';
					not_really_count = '';
					very_vote = '';
					somewhat_vote = '';
					not_really_vote = '';
					if (data.post_rating_options_informative_count) {
						very_count = data.post_rating_options_informative_count.very.count;
						somewhat_count = data.post_rating_options_informative_count.somewhat.count;
						not_really_count = data.post_rating_options_informative_count.not_really.count;
						very_vote = data.post_rating_options_informative_count.very.vote;
						somewhat_vote = data.post_rating_options_informative_count.somewhat.vote;
						not_really_vote = data.post_rating_options_informative_count.not_really.vote;
					}
					liberal_count = '';
					neutral_count = '';
					conservative_count = '';
					liberal_vote = '';
					neutral_vote = '';
					conservative_vote = '';
					if (data.post_rating_options_bias_count) {
						liberal_count = data.post_rating_options_bias_count.liberal.count;
						neutral_count = data.post_rating_options_bias_count.neutral.count;
						conservative_count = data.post_rating_options_bias_count.conservative.count;
						liberal_vote = data.post_rating_options_bias_count.liberal.vote;
						neutral_vote = data.post_rating_options_bias_count.neutral.vote;
						conservative_vote = data.post_rating_options_bias_count.conservative.vote;
					}
					_show = "'show'";
					_close = "'close'";
					infer_bias = "'infer_bias'";
					single = "'single'";
					var infor_option = '';
					var infor_rating_action = '';
					var bias_option = '';
					var bias_rating_action = '';
					if (post_data) {
						if (!post_data.informative && data.user_vote_1 == '') {
							infor_rating_action = '<li><span><a class="post_rating" href="javascript:void(0);" onclick="vote_post_rating(' + post_id + ', ' + info + ');">Rate the source</a></span></li>';
							infor_option = '<div class="infermative_tab infermative_voting_option_tab_' + post_id + '" style="display:none;">' + '<span>Informative?</span>' + '<a class="rating_option infermative_action infermative_action_show infermative_action_show_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + info + ', ' + _show + ');">' + '<i class="fa fa-plus fa-2x"></i>' + '</a>' + '<a class="rating_option infermative_action_close infermative_action_close_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + info + ', ' + _close + ');">' + '<img src="'+theme_obj.base_url+'/assets/images/close_sign.png" alt="" width="23" height="23">' + '</a>' + '<div class="infermative_option infermative_option_' + post_id + '" style="display:none;">' + '<select name="informative_select" id="informative_select" class="rating_options_select cs-select cs-skin-elastic" onchange="change_option_vote(this, this.value);">' + '<option value="">Select</option>' + '<option value="very">very</option>' + '<option value="somewhat">somewhat</option>' + '<option value="not_really">not really</option>' + '</select>' + '</div>' + '<div class="post_rating_action">' + '<span class="error_class_rating_' + post_id + '"></span><a href="javascript:void(0);" id="post_submit" class="post_submit_' + post_id + '" onclick="set_post_vote_option(this, ' + post_id + ');">Submit</a>' + '</div>' + '</div>';
						} else if (!post_data.bias && data.user_vote_2 == '') {
							bias_rating_action = '<li><span><a class="post_rating" href="javascript:void(0);" onclick="vote_post_rating(' + post_id + ', ' + bias + ');">Rate the source</a></span></li>';
							bias_option = '<div class="bias_voting_option_tab_' + post_id + '" style="display:none;">' + '<span>Bias</span>' + '<a class="rating_option bias_action bias_action_show bias_action_show_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + bias + ', ' + _show + ');">' + '<i class="fa fa-plus fa-2x"></i>' + '</a>' + '<a class="rating_option bias_action_close bias_action_close_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + bias + ', ' + _close + ');" style="display:none;">' + '<img src="'+theme_obj.base_url+'/assets/images/close_sign.png" alt="" width="23" height="23">' + '</a>' + '<div class="bias_option bias_option_' + post_id + '" style="display:none;">' + '<select name="bias_select" id="bias_select" class="rating_options_select cs-select cs-skin-elastic" onchange="change_option_vote(this, this.value);">' + '<option value="">Select</option>' + '<option value="liberal">liberal</option>' + '<option value="neutral">neutral</option>' + '<option value="conservative">conservative</option>' + '</select>' + '</div>' + '<div class="post_rating_action">' + '<span class="error_class_rating_' + post_id + '"></span><a href="javascript:void(0);" id="post_submit" class="post_submit_' + post_id + '" onclick="set_post_vote_option(this, ' + post_id + ');">Submit</a>' + '</div>' + '</div>';
						}
					}
					info_result = '';
					if (very_count || somewhat_count || not_really_count) {
						info_result = '<ul class="infermative_vote_results infermative_vote_results_' + post_id + '">' + '<li><label>very</label><span> - ' + very_count + ' (' + very_vote + ')</span></li>' + '<li><label>somewhat</label><span> - ' + somewhat_count + ' (' + somewhat_vote + ')</span></li>' + '<li><label>not really</label><span> - ' + not_really_count + ' (' + not_really_vote + ')</span></li>' + infor_rating_action + bias_rating_action + '</ul>';
					} else {
						info_result = '<ul class="infermative_vote_results infermative_vote_results_' + post_id + '">' + infor_rating_action + bias_rating_action + '</ul>';
					}
					var and = '';
					if (informative_high_vote_1 && bias_high_vote_content_1 || post_data.informative && post_data.bias) {
						var and = 'and&nbsp;';
					}
					content = '';
					if (informative_high_vote_1 || post_data.informative) {
						content = '<div class="informative_vote_result_section vote_result_section">' + '<a href="javascript:void(0);" ng-mouseover="display_voting(' + post + ',' + info + ');">' + '<span class=""> ' + informative_high_vote_1 + informative_high_vote_2 + informative_high_vote_3 + ' </span>' + 'informative </a> ' + and + ' <div id="post_infermative_voting_' + post_id + '" class="post_voting_section">' + '<div class="voting_option_values">' + '<span class="infermative_vote_results_title_' + post_id + '">Informative</span>' + info_result + infor_option + bias_option + '</div>' + '</div>' + '</div>';
					}
					bias_result = '';
					if (liberal_count || neutral_count || conservative_count) {
						bias_result = '<ul class="bias_vote_results bias_vote_results_' + post_id + '">' + '<li><label>liberal</label><span class="ng-binding"> - ' + neutral_count +' (' + neutral_vote + ')</span></li>' + '<li><label>neutral</label><span class="ng-binding"> - ' +  liberal_count+ ' (' + liberal_vote + ')</span></li>' + '<li><label>conservative</label><span class="ng-binding"> - ' + conservative_count + ' (' + conservative_vote + ')</span></li>' + bias_rating_action + infor_rating_action + '</ul>';
					} else {
						bias_result = '<ul class="bias_vote_results bias_vote_results_' + post_id + '">' + bias_rating_action + infor_rating_action + '</ul>';
					}
					if (bias_high_vote_content_1 || post_data.bias) {
						content += '<div class="bias_vote_result_section vote_result_section" >' + '<a href="javascript:void(0);" ng-mouseover="display_voting(' + post + ', ' + bias + ');"><span>' + bias_high_vote_content_1 + bias_high_vote_content_2 + bias_high_vote_content_3 + '</span> bias </a>' + '<div id="post_bias_voting_' + post_id + '" class="post_voting_section">' + '<div class="voting_option_values">' + '<span class="bias_vote_results_title_' + post_id + '">Bias?</span>' + bias_result + bias_option + infor_option + '</div>' + '</div>' + '</div>';
					}
					jQuery(".list_post_rating_section_" + post_id).html(content);
					var rated = '';
					//alert(0);
					if (informative_val && bias_val) {
						//alert(1);
						rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + informative_val + ' informative and &nbsp;</li><li class="user_vote_rating user_bias_rated_source_' + post_id + '">' + bias_val + ' bias</li>';
					} else if (informative_val) {
						//alert(2);
						bias_text = jQuery(".user_bias_rated_source_" + post_id).text();
						if (bias_text) {
							//alert(3);
							rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + informative_val + ' informative and &nbsp; </li><li class="user_vote_rating user_bias_rated_source_' + post_id + '">&nbsp;' + bias_text + '</li>';
						} else {
							//alert(4);
							rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + informative_val + ' informative </li>';
							if(data.user_vote_2){
							  rated+=' &nbsp; and '+ data.user_vote_2 +'  bias';
							}
						}
					} else if (bias_val) {
						//alert(5);
						info_text = jQuery(".user_info_rated_source_" + post_id).text();
						if (info_text) {
							//alert(6);
							rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + info_text + 'and &nbsp;</li><li class="user_vote_rating user_bias_rated_source_' + post_id + '">&nbsp;' + bias_val + ' bias</li>';
						} else {
							//alert(7);
							rated = '<li class="user_vote_rating user_bias_rated_source_' + post_id + '">' + bias_val + ' bias</li>';
						}
					}
					if (rated) {
						//alert(rated);

						rated_content = '<span style="display:none"><ul><li><span>You rated as </span></li>'+ rated +'</ul> ' + '<ul> ' +output + '</ul></span>';
						jQuery(".user_info_and_bias_rated_source_" + post_id).show();
						jQuery(".user_info_and_bias_rated_source_" + post_id).remove();
						jQuery(".user_info_and_bias_rated_source_quick_" + post_id).html(rated_content);
					} else {
						//alert(10);
					}
					jQuery(".post_submit_" + post_id).removeClass('action_disable');
				} else {}
			});
		}
	}

	$scope.post_rating = function (post, action) {
		$scope.most_viewed_filter_value = '';
		post_id = post.post_id
		jQuery("." + action + "_voting_option_tab_" + post_id).show();
	}

	$scope.filter_ass_desc = function () {
		$scope.most_viewed_filter_value = '';
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		order = '';
		if ($scope.order == "DESC") {
			order = 'ASC';
			$scope.order = order;
			$scope.order_class = 'asdsorder';
		} else {
			order = 'DESC';
			$scope.order = order;
			$scope.order_class = '';
		}
		$scope.informative = jQuery("#filter_informative").val();
		$scope.bias = jQuery("#filter_bias").val();
		//alert($scope.informative+' - '+$scope.bias);
		//alert('Load - '+$scope._load+'\nTax - '+$scope.tax+'\nTerm - '+$scope.term);
		$http({
			method: 'post',
			data: {
				'load': ($scope._load) ? $scope._load : '',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': order,
				'user_role': theme_obj.user_role,
				'infor_filter': ($scope.informative) ? $scope.informative : '',
				'bias_filter': ($scope.bias) ? $scope.bias : '',
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.data = [];
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.dropdown_filter = function () {
		$scope.most_viewed_filter_value = '';
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope.informative = jQuery("#filter_informative").val();
		$scope.bias = jQuery("#filter_bias").val();
		//alert($scope.informative+' - '+$scope.bias);
		$http({
			method: 'post',
			data: {
				'load': ($scope._load) ? $scope._load : '',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': $scope.order,
				'user_role': theme_obj.user_role,
				'infor_filter': ($scope.informative) ? $scope.informative : '',
				'bias_filter': ($scope.bias) ? $scope.bias : '',
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.data = [];
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data;
			if (data.post_data.text) {
				$scope.data = data.post_data;
			}
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
				//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
			}
			jQuery("#num_of_page").val(data.total_page);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.most_viewed_filter = function (type, tab) {

		$scope.most_viewed_filter_value = type;

		$scope.scroll_page = 0;
		//alert();
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		jQuery(".tab_action").removeClass('active');
		jQuery("." + tab).addClass('active');
		$http({
			method: 'post',
			data: {
				'load': 'most_viewed',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': '',
				'term': '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'view': $scope.most_viewed_filter_value,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			jQuery(".views_count_for_mv").show();
			$scope.data = [];
			jQuery("#num_of_scroll").val(1);
			$scope.most_view = 1;
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			//alert(data.total_page);
			jQuery("#num_of_page").val(data.total_page);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.share_this_post_email = function (post, title, post_link, type) {
		/*if (type == 'index') {
			str = 'Check this out on Hyroglf!\n\n' + post.post_link;
			jQuery("#hidden_share_title").val(post.post_title);
			jQuery("textarea#txtMessage").val(str);
		} else {
			jQuery("#hidden_share_title").val(title);
			str = 'Check this out on Hyroglf!\n\n' + post_link;
			jQuery("textarea#txtMessage").val(str);
		}
		jQuery.fancybox({
			'type': 'inline',
			'href': '#post_share_via_email_content'
		});*/

		//alert(post.post_id);
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=get_title_by_id_post_email',
			//data : data,
			params: {
				'post_id': post.post_id,
			},
		}).
		success(function (data, status, headers, config) {
			//console.log(post.post_title);
			//alert(data);
			if (type == 'index') {
				$scope.post_shared_title = post.post_title;
				$scope.post_shared_link = data._link;
				//str = "Hey!\n\n  "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+data._link+">"+post.post_title+"</a><p>Best Regards,</p><p>Hyroglf</p>" ;
				jQuery("#hidden_share_title").val(post.post_title);
				jQuery("textarea#txtMessage").val(str);
			} else {
				$scope.post_shared_title = post.post_title;
				$scope.post_shared_link = data._link;
				jQuery("#hidden_share_title").val(post.post_title);
				//str = "Hey!\n\n "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+data._link+">"+post.post_title+"</a><p>Best Regards,</p><p>Hyroglf</p>"
				jQuery("textarea#txtMessage").val(str);
			}
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});

		jQuery.fancybox({
			'type': 'inline',
			'href': '#post_share_via_email_content'
		});

	}

	$scope.fnflagpost = function (post) {
		post_id = post.post_id;
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=user_flag_report',
			params: {
				'post_id': post_id
			},
		}).
		success(function (data, status, headers, config) {
			var _open = "'open'";
			var _close = "'close'";
			warning_content = '<div class="flag_inappropriate_popup_content flag_inappropriate_popup_content_' + post_id + '" style="display:none"><div class="flag_popup_content"><p>Warning! This post has been flagged as inappropriate! Do you wish to open?</p><a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="view_content_click_quick(' + post_id + ', ' + _open + ');">Yes</a><a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="close_the_content_click_quick(' + post_id + ', ' + _close + ');">No</a></div></div>';
			jQuery(".quick_warning_content_" + post_id).html(warning_content);
			post.flag_inappropriate_count = 1;
			jQuery("#flag_message_" + post_id).show();
			$scope.flag_inapproperiate = data;
			//console.log(data);
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}

	$scope.flag_advertisement_set = function () {
		//post_id= post.post_id;
		post_id = jQuery("#hidden_p_id").val();
		//alert(post_id);
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=user_flag_advertisement',
			params: {
				'post_id': post_id
			},
		}).
		success(function (data, status, headers, config) {
			//post.flag_as_advertisement_count = 1;
			jQuery("#flag_as_adverstiment_message_"+post_id).show();
			jQuery("#flag_advertisement_post_report_" + post_id).remove();
			$scope.flag_advertisement = data;
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}

	$scope.close_flag_inappropriate_popup_content = function (post, action) {
		post_id = post.post_id;
		if (action == 'open') {
			jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
			jQuery(".flag_inappropriate_popup_content_random").hide();
		} else {
			jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
			jQuery(".flag_inappropriate_popup_content_random").hide();
			$scope.close_the_content_click(post);
		}
	}

	$scope.post_filter_by_author = function () {
		$scope.scroll_page = 0;
		$scope.most_viewed_filter_value = '';
		jQuery(".post_heade_edit_action").show();
		jQuery(".welcome_head_content").hide();
		jQuery(".sort_section").show();
		jQuery(".filter_post_img").show();
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope.term = jQuery("#filter_category").val();
		$scope.tax = jQuery("#filter_taxonomy").val();
		$scope._load = 'cat_post';
		//$scope.selected_category = 'Post and vote by "' + $scope.term + '"';
		//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
		$scope.selected_category = $scope.term+"'s " +'POV';
		$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/GLF-Favicon.png width="150" height="150"/>';
		$http({
			method: 'post',
			data: {
				'load': 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			jQuery(".filter_post_img,.post_heade_edit_action,.home_head_content").hide();
			$scope.data = [];
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
			var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.post_filter_by_search = function ($event, search_key, page_of) {
	$scope.scroll_page = 0;
	jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action,.home_head_content").hide();
		$scope.most_viewed_filter_value = '';
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".sort_section").show();
		if($scope.popupHeight <= 992){
			jQuery(".header_left_content").show();
			jQuery(".header_right_content").hide();
		}
		jQuery(".home_head_content").show();
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope._load = 'search_post';
		if (search_key) {
			$scope.term = search_key;
			$scope.tax = 'search_post';
		} else {
			$scope.term = jQuery("#filter_category").val();
			$scope.tax = jQuery("#filter_taxonomy").val();
		}
		jQuery(".post_heade_edit_action").hide();
		//$scope.selected_category = 'Search results for "' + $scope.term + '"';
		jQuery(".post_filter_text").addClass("search_res");
		//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
		$scope.selected_category_img = '';
		//jQuery(".filter_post_img").hide();

		$http({
			method: 'post',
			data: {
				'load': 'search_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': 'DESC',
				'infor_filter': ($scope.informative) ? $scope.informative : '',
				'bias_filter': ($scope.bias) ? $scope.bias : '',
				'user_role': theme_obj.user_role,
				'view': '',
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.selected_category = 'Search results for "' + $scope.term + '"';
			jQuery(".filter_post_img.cat_image_header_sec,.post_heade_edit_action,.home_head_content,span.random_list").hide();
			// jQuery(".post_content").addClass(".post-search");
			jQuery(".ng-scope").css("margin-top", "5%");
			jQuery(".home_content_section").css("margin-top", "-3%");
			jQuery("header").css("background", "transparent");
			jQuery(".makefixed").css("background", "transparent");
			jQuery(".slide-animation.ng-scope").css("margin-top", "-8%");
			// jQuery(".sort_content").css("padding-left", "2%");
			jQuery(".sort_content").hide();
			if($scope.popupHeight < 1050){
				jQuery(".ajax_filter_content .filter_post_img.cat_image_header_sec").hide();
			}
			jQuery("#num_of_page").val(data.total_page);



			//	jQuery(".wiki_content.wiki_center_section .page_loader").remove();
			jQuery(".page-content.post").show();
			$scope.data = [];
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			//console.log(data);
			var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.scroll_load_categories = function () {
		//jQuery(".wiki_left_section .wiki_category ul").append('<li class="pro_list_image loaded_cat">' + shuffleArray(theme_obj.scroll_cat) + '</li>');
		jQuery(".wiki_right_section .wiki_category ul").append('<li class="pro_list_image loaded_cat">' + shuffleArray(theme_obj.scroll_cat) + '</li>');
		 if(jQuery('.home_content_section .list_of_post_content_section:nth-last-child(1)').length > 0){
			setTimeout(function () {
				//alert();
				/*var toplastchild = jQuery('.home_content_section .list_of_post_content_section:last-child').offset().top;
				var toplastheight = jQuery('.home_content_section .list_of_post_content_section:last-child').height();
				//alert(toplastheight);
				var toplastchild = toplastchild + toplastheight+50
				jQuery('.wiki_left_section').css({
					"height": toplastchild+'px',
					"overflow": "hidden"
				});
				jQuery('.wiki_right_section').css({
					"height": toplastchild+'px',
					"overflow": "hidden"
				});*/
			}, 100);
		 }
		//alert(shuffleArray(theme_obj.scroll_cat)+' \n '+shuffleArray(theme_obj.scroll_cat));
	}

	$scope.scroll_load_post = function () {
		//alert(jQuery(".list_of_post_content_section").length)

		num_of_scroll = jQuery("#num_of_scroll").val();
		scroll_num = parseInt(num_of_scroll) + 1;
		num_of_page = jQuery("#num_of_page").val();


		//alert( $scope._load+' - '+$scope.tax+' - '+$scope.term+' - '+num_of_scroll+' - '+num_of_page+' - '+scroll_num);
		if( $scope._load != "random" ) {
			//alert(scroll_num+'<='+num_of_page+'-'+num_of_scroll + '!='+ $scope.scroll_page);
			if (scroll_num <= num_of_page && num_of_scroll != $scope.scroll_page) {
				//alert(1);
				$scope.scroll_page = num_of_scroll;
				$http({
					method: 'post',
					data: {
						'load'			: ($scope._load) ? $scope._load : '',
						'wpdb'			: theme_obj.wpdb,
						'user_id'		: theme_obj.user_id,
						'user_access'	: theme_obj.user_access,
						'tax'			: ($scope.tax) ? $scope.tax : '',
						'term'			: ($scope.term) ? $scope.term : '',
						'page_num'		: scroll_num,
						'order'			: $scope.order,
						'user_role'		: theme_obj.user_role,
						'infor_filter'	: ($scope.informative) ? $scope.informative : '',
						'bias_filter'	: ($scope.bias) ? $scope.bias : '',
						'user_ip'		: theme_obj.user_ip,
						'plugin_url'	: theme_obj.plugin_url,
						'site_url'		: theme_obj.site_url,
						'base_url'		: theme_obj.base_url,
						'view' 			: $scope.most_viewed_filter_value,
					},
					url: $scope.base_path + '/get_post',
					// headers: {'Content-Type': 'application/json'}
				}).
				success(function (data, status, headers, config) {
					$scope.result = data.post_data;
					var num_post = $scope.result.length;
					post = {}
					for (i = 0; i < num_post; i++) {
						$scope.data.push($scope.result[i]);
						//if($scope.term != '')
							//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
					}
					jQuery("#num_of_scroll").val(scroll_num);
				}).
				error(function (data, status, headers, config) {});
			}
		}
	}
	function isValidEmailAddress(emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test(emailAddress);
	};


	$scope.share_post_email = function () {


		var frommailval=jQuery("#txtYourEmail").val();
		/*if(frommailval=="")
		{
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter the name</p>');
			return false;
		}*/
		if(isValidEmailAddress(frommailval)== false)
		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		var tomailval=jQuery("#txtRecipientEmail").val();
		if(isValidEmailAddress(tomailval)== false)

		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		var tonameval=jQuery("#txtRecipientName").val();
		if(tonameval=="")
		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter the name</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}



		share_title = jQuery("#hidden_share_title").val();
		YourEmail = jQuery("#txtYourEmail").val();
		$scope.youremail = YourEmail;
		//str = "Hey!\n\n  "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+$scope.post_shared_link+">"+$scope.post_shared_title+"</a><p>Best Regards,</p><p>Hyroglf</p>" ;
		//str = '<div class="mail_div" style="max-width:320px;"><div class="mail_div_top" style="border-bottom:1px solid #ccc; padding:0px 0px 10px"><h6 style="padding:10px 0px; font-size:13px; color:rgb(34, 34, 34); margin:0px; font-weight:400; display:inline-block;">Hey!</h6>'+$scope.youremail+'</div><p>shared a Hyroglf post with you. Click below to view post.</p><a style="background:#f5bd5b;color:#ffffff;border-radius:4px;padding:8px 25px;display:inline-block;margin:5px 0 0 0px" href="'+$scope.post_shared_link+'">View Post</a><div class="yj6qo"></div><div class="adL"><br></div></div>';
		//str = "Hey!\n\n  "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+$scope.post_shared_link+">"+'View Post'+"</a>" ;
		RecipientEmail = jQuery("#txtRecipientEmail").val();
		RecipientName = jQuery("#txtRecipientName").val();
		//Message = str;
		//Message = jQuery("#txtMessage").val()
		//alert(share_title+', '+YourEmail+', '+RecipientEmail+', '+Message);
		jQuery(".share_post_email_fields").addClass('action_disable');
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=share_post_email_send',
			//data : data,
			params: {
				'youremail': YourEmail,
				'recipientemail': RecipientEmail,
				//'message': Message,
				'title': share_title,
				'recipientname' : RecipientName,
				'post_shared_link' : $scope.post_shared_link
			},
		}).
		success(function (data, status, headers, config) {
			if (data.action == true) {
				jQuery("#hidden_share_title").val('');
				jQuery("#txtYourEmail").val('');
				jQuery("#txtRecipientEmail").val('');
				jQuery("#txtRecipientName").val('');
				jQuery("#txtMessage").val('');
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status").append('<p class="success_msg">Post shared!</p>');
				//jQuery('.success_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else if (jQuery("#txtRecipientEmail").val() == '' || jQuery("#txtYourEmail").val() == '' || jQuery("#txtRecipientName").val() == '') {
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Please enter the required field</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);

			} else if (!isValidEmailAddress(jQuery("#txtRecipientEmail").val()) || !isValidEmailAddress(jQuery("#txtYourEmail").val())){
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Please enter the required field</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else {
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Sorry some technical error occured.</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			}
			$scope.share_status = $sce.trustAsHtml(data.message);
			jQuery(".share_post_email_fields").removeClass('action_disable');
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}


});


app.controller('profileCtrl', function ($scope, $rootScope, $routeParams, $location, $window, $http, Data) {
	//$scope.data =[];
	$scope.popupHeight = $window.innerWidth;
	$scope.base_url = theme_obj.base_url;
	$scope.user_access = theme_obj.user_access;
	$scope.base_path = 'wp-content/themes/hyroglf/angular/api/v1';
	$scope._load = theme_obj._load;
	$scope.tax = theme_obj.tax;
	$scope.term = theme_obj.term;
	$scope.term_title = theme_obj.term_title;
	$scope.order = 'DESC';
	$scope.order_class = '';
	$scope.informative = '';
	$scope.bias = '';
	$scope.most_view = 0;
	$scope.scroll_page = 0;
	$scope.mobile_view = true;
	$scope.feed_favourites = true;
	//initially set those objects to null to avoid undefined error
	$scope.data = [];
	$scope.popupHeight = $window.innerWidth;

	if($scope.popupHeight < 500){
		$scope.window_size = 1;
	} else {
		$scope.window_size = 0;
	}
	if(theme_obj.user_role == 'user'){
		$scope.feed_favourites = true;
	}

	if ($scope._load == 'search_post') {
		jQuery(".post_heade_edit_action").hide();
		//$scope.selected_category = 'Search results for "' + $scope.term + '"';
		jQuery(".post_filter_text").addClass("search_res");
		//$scope.selected_category_img = '<img src="' + $scope.base_url + '/assets/images/search_symbol.png" width="150" height="150"/>';
		jQuery(".filter_post_img.cat_image_header_sec").hide();
		//setTimeout(function(){
			jQuery(".home_head_content ").hide();
		//},100);
	} else if ($scope._load == 'cat_post' && $scope.tax == 'category') {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".home_head_content ").hide();
		jQuery(".filter_post_img").show();
		$scope.selected_category = $scope.term_title;
		if (theme_obj.term_src) {
			$scope.selected_category_img = '<img src="' + theme_obj.term_src + '" width="150" height="150"/>';
		} else {
			$scope.selected_category_img = '';
		}
	} else if ($scope._load == 'cat_post' && $scope.tax == 'post_tag') {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".filter_post_img").show();
		$scope.selected_category = $scope.term_title;
		$scope.selected_category_img = '<img src="' + $scope.base_url + '/assets/images/search_symbol.png" width="150" height="150"/>';
	} else if ($scope._load == 'cat_post' && $scope.tax == 'post_filter_by_author') {
		jQuery(".filter_post_img").show();
		$scope.selected_category = $scope.term+"'s " +'POV';
		$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
	}  else {
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".home_head_content ").show();
		jQuery(".filter_post_img").show();
		$scope.selected_category = 'Recently Added/Edited';
		//$scope.selected_category_img = '<img src="' + $scope.base_url + '/assets/images/recently_added_edited.jpg" width="150" height="150"/>';
	}
	jQuery(".home_content_section.my-profile").show();
	/*$scope.loadrecords = function (_load, wpdb, user_id, user_access, tax, term, page_num, order, user_role, user_ip, plugin_url, site_url, base_url) {
		jQuery(".sort_section").show();
		jQuery("#num_of_scroll").val(1);
		$http({
			method: 'post',
			data: {
				'load': _load,
				'wpdb': wpdb,
				'user_id': user_id,
				'user_access': user_access,
				'tax': tax,
				'term': term,
				'page_num': page_num,
				'order': order,
				'user_role': user_role,
				'user_ip': user_ip,
				'plugin_url': plugin_url,
				'site_url': site_url,
				'base_url': base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.most_view = 0;
			$scope.data = [];
			$scope.result = data.post_data;
			console.log($scope.result.text);
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			post = {}

			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
			if (_load == 'random') {
				if($scope.popupHeight < 993){
					jQuery(".filter_post_img").hide();
				} else{
					jQuery(".filter_post_img").show();
				}
				jQuery(".sort_section").hide();
				$scope.selected_category = 'Random Post';
				//$scope.selected_category = 'Recently Added/Edited';
				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/random_icon.png width="150" height="150"/>';
				jQuery(".home_head_content ").hide();
				rand_id = data.post_data[0].post_id;
				$scope.trigger_rand_actions(rand_id);
			}
			jQuery(".home_content_section").show();
		}).
		error(function (data, status, headers, config) {
			console.log('Sorry having some error');
		});
	}*/
	$scope.fndeleteaccount = function(){
		jQuery.ajax({
		url: ajaxurl,
		type:'POST',
		datatype: "html",
		data: jQuery("#check_password_delete_frm").serialize()+"&action=delete_user_account",
		success: function( html ) {
			html = jQuery.parseJSON(html);
			if(html.message == 'nope'){
				jQuery("#delete_account_popup_content").show();
			} else{
				jQuery("#delete_account_popup_content").hide();
				window.location.href=html.output;
			}
		}
	});
	}
	if ($scope._load == '') {
		$scope._load = 'init';
	}

	/*$scope.loadrecords($scope._load, theme_obj.wpdb, theme_obj.user_id, theme_obj.user_access, $scope.tax, $scope.term, 1, 'DESC', theme_obj.user_role, theme_obj.user_ip, theme_obj.plugin_url, theme_obj.site_url, theme_obj.base_url); // Load datas*/


	$scope.myprofile_votes = function(){
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".sort_section").show();
		jQuery(".filter_post_img").show();
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope.term = 'votes';
		$scope.tax = 'user_by_vote';
		$scope._load = 'cat_post';
		//$scope.selected_category = 'Post and vote by "' + $scope.term + '"';
		//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
		//$scope.selected_category = $scope.term+"'s " +'POV';
		if($scope.popupHeight > 1050){
			$scope.selected_category_img = '<i class="fa fa-edit fa-3x"></i>';
		} else {
			$scope.selected_category_img = '';
		}
		$http({
			method: 'post',
			data: {
				'load': 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			data.post_data.text = "You haven't voted yet.";
			$scope.data = [];
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data = $scope.result;
			}
			post = {};
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery(".profile_page h2").text("My Votes")
			$scope.selected_category = 'My Votes';
			jQuery("#num_of_page").val(data.total_page);
			var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});
		}).
		error(function (data, status, headers, config) {});
	}

	jQuery("#myprofile_votes").click(function(){
		jQuery(".post_filter_text").removeClass("search_res");
		$scope.myprofile_votes();
	});
	jQuery("#myprofile_posts").click(function(){
		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".profile_page h2").text("My Posts")
		$scope.post_filter_by_author();
	});

	$scope.random_post = function () {
		jQuery(".post_filter_text").removeClass("search_res");
	jQuery(".post_heade_edit_action").hide();
	jQuery(".filter_post_img").show();
	if($scope.popupHeight < 993){
		jQuery(".home_head_content").hide();
			jQuery(".home_head_content ").css("border-width","0");
			jQuery(".header_left_content_inner").show();
			jQuery(".header_right_content").hide();
			jQuery("body").addClass("full_width_header");
	}
			if($scope.popupHeight > 992){
				//jQuery(".wiki_topbar_right.hide-767 ul.top_search").appendTo("footer .container .row");
				jQuery(".header_left_content").show();
			}
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope._load = 'random';
		$scope.most_view = 0;
		$scope.selected_category = 'Random Post';
		//$scope.selected_category = 'Recently Added/Edited';
		$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/random_icon.png width="150" height="150"/>';
		$scope.loadrecords($scope._load, theme_obj.wpdb, theme_obj.user_id, theme_obj.user_access, '', '', 1, 'DESC', theme_obj.user_role, theme_obj.user_ip, theme_obj.plugin_url, theme_obj.site_url, theme_obj.base_url); // Load datas
		$http({
			method: 'post',
			data: {
				'load': 'random',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': '',
				'term': '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			jQuery(".sort_section").hide();
			$scope.data = data.post_data;
			rand_id = data.post_data[0].post_id;
			$scope.trigger_rand_actions(rand_id);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.trigger_rand_actions = function (rand_id) {
		setTimeout(function () {
			jQuery(".list_post_content").css("display", "block");
			jQuery(".view_list_post").hide();
			jQuery(".pop_edit_tex").show();
			jQuery(".close_list_post").show();
			$scope.view_content_click('', rand_id);
			setTimeout(function () {
				jQuery(".single_post_multi_image_slide").show();
				jQuery(".single_post_multi_image_slide").bxSlider({
					pager: false,
					preloadImages: 'all',
				});
				jQuery('.fancybox').fancybox({
					helpers: {
						media: true
					},
					youtube: {
						autoplay: 1, // enable autoplay
						start: 01 // set start time in seconds (embed)
					},

				});
			}, 1100); // Image sliding
		}, 50);
	}
	$scope.cat_post_filter_click = function ($event, tax, cat_item, cat_name, cat_image, page_of) { //BEGIN POST FILTER
		jQuery(".post_filter_text").removeClass("search_res");
		if($scope.popupHeight < 1050){

			jQuery(".ajax_filter_content .filter_post_img.cat_image_header_sec").hide();
		}
		jQuery(".post_heade_edit_action").show();
		$scope.scroll_page = 0;
		jQuery(".sort_section").show();
		//jQuery(".filter_post_img").show();
		$scope.catname_on_click = cat_item;
		if($scope.popupHeight < 993)
		jQuery(".home_head_content").show();

		if(cat_item != 'recent_post'){
			jQuery(".header_left_content_inner").show();
			jQuery(".header_right_content").hide();
			jQuery("body").addClass("full_width_header");
			if($scope.popupHeight > 992){
				//jQuery(".wiki_topbar_right.hide-767 ul.top_search").appendTo("footer .container .row");
				jQuery(".header_left_content").show();
				jQuery(".home_head_content ").css("border-width","0");
			}
		}
		if(cat_item == 'recent_post'){
			jQuery(".home_head_content").show();
			if($scope.popupHeight > 1050){
			jQuery(".header_left_content_inner").show();
			}
			//jQuery(".header_right_content").show();
			jQuery("body").removeClass("full_width_header");
			if($scope.popupHeight > 992){
				jQuery(".home_head_content ").css("border-width","1px");
				//jQuery("footer .container .row ul.top_search").appendTo(".wiki_topbar_right.hide-767");
				jQuery(".header_left_content").show();
			}
		}
		jQuery(".loaded_cat").remove();
		if (!tax && !cat_item && !cat_name && !cat_image) {
			cat_item = jQuery("#filter_category").val();
			tax = jQuery("#filter_taxonomy").val();
			cat_name = jQuery("#filter_cat_name").val();
			cat_image = jQuery("#filter_cat_img").val();
		}
		jQuery("#num_of_scroll").val(1);
		if (cat_item == 'most_viewed') {
			$scope.most_view = 1;
			jQuery(".sort_tab_sections.most_viewed_action").show();
		} else {
			$scope.most_view = 0;
		}
		order = 'DESC';
		$scope.order = order;
		$scope.order_class = '';
		$scope._load = 'cat_post';
		$scope.tax = tax;
		$scope.term = cat_item;
		$scope.selected_category = cat_name;
		if (cat_item == 'recent_post') {
			//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/recently_added_edited.jpg width="150" height="150"/>';
		} else if (cat_item == 'most_viewed') {
			$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/Most-Viewed.jpg width="150" height="150"/>';
		} /*else if (cat_item == 'my_posts_and_votes') {
			//$scope.selected_category = 'MY POSTS (' + theme_obj.user_post_vote_count.post_count + ') + VOTES (' + theme_obj.user_post_vote_count.vote_count + ')';
			//$scope.selected_category = "My POV";
			//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/pencil_sign.png width="150" height="150"/>';
			$scope.selected_category_img = '';
		} */ else if (cat_name == 'My Profile') {
			if($scope.popupHeight > 992){
				$scope.selected_category_img = '<i class="fa fa-edit fa-2x"></i>';
			} else{
				$scope.selected_category_img ='';
			}
		}else if (cat_item == 'other-info') {

				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/GLF-Favicon.png width="150" height="150"/>';

		} else if (cat_item == 'favorite_posts') {
			if($scope.popupHeight > 992){
				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/favorites_star_icon.png width="150" height="150"/>';
			} else{
				$scope.selected_category_img ='';
			}
		} else if (cat_item == 'other-info') {
			$scope.selected_category_img = '';
		} else {
			$scope.selected_category_img = '<img src=' + cat_image + ' width="150" height="150"/>';
		}

		jQuery(".post_cat_image_modal_wrapper").removeClass("post_cat_image_open");

		var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});

		$http({
			method: 'post',
			data: {
				'load': (cat_item == 'most_viewed') ? 'most_viewed' : 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': tax,
				'term': cat_item,
				'page_num': 1,
				'order': order,
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_cat_post',
		}).
		success(function (data, status, headers, config) {
			//$scope.post.post_meta.post_ref_link_favicon=$scope.selected_category_img;
			$scope.data = [];
			//$scope.data = data.post_data.post_data;

			$scope.result = data.post_data.post_data;
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			//var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
				//if(cat_item != 'recent_post'){
					//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
				//}
			}
			jQuery("#num_of_page").val(data.post_data.total_page);


		}).
		error(function (data, status, headers, config) {});
	}
//END POST FILTER
	$scope.cat_tag_post_filter_click = function ($event, tax, cat_item, page_of) {
		jQuery(".post_filter_text").removeClass("search_res");
		if($scope.popupHeight < 1050){
			jQuery(".ajax_filter_content .filter_post_img.cat_image_header_sec").hide();
		}
		jQuery(".post_heade_edit_action").show();

		jQuery(".home_head_content").hide();
		jQuery(".sort_section").show();
		//jQuery(".filter_post_img").show();
		if(cat_item.slug == 'other-info'){
			$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/GLF-Favicon.png width="150" height="150"/>';
		}
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		//alert(tax+' - '+cat_item.slug);

		order = 'DESC';
		$scope.order = order;
		$scope.order_class = '';
		$scope._load = 'cat_post';
		$scope.tax = tax;
		$scope.term = cat_item.slug;
		cat_slug = cat_item.slug;
		$scope.selected_category = cat_item.name;

		jQuery(".post_cat_image_modal_wrapper").removeClass("post_cat_image_open");

		var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});

		$http({
			method: 'post',
			data: {
				'load': 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': tax,
				'term': cat_slug,
				'term_id': cat_item.term_id,
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: $scope.base_path + '/get_cat_post',
		}).
		success(function (data, status, headers, config) {
			//console.log(data);
			if (data.term_img.term_image) {
				$scope.selected_category_img = '<img src=' + data.term_img.term_image + ' width="150" height="150"/>';
			}
			if (tax == 'post_tag') {
				$scope.selected_category = '"' + cat_item.name + '"';
				$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
			}
			$scope.data = [];
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data.post_data;
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
				//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
			}
			jQuery("#num_of_page").val(data.post_data.total_page);

		}).
		error(function(data, status, headers, config) {});
	}

	$scope.view_content_click = function (event, post) {

		if (post.post_id) {
			post_id = post.post_id;
		} else {
			post_id = post;
		}
		hyroglf_analytics_home_feed(post.post_id);

		jQuery(".single_post_multi_image_slide_" + post_id + ",.single_post_multi_video_slide_" + post_id).hide();
		jQuery(".user_info_and_bias_rated_source_"+post_id).show();
		jQuery(".post_modified_" + post_id).show();
		jQuery(".post_share_section_" + post_id).show();
		//jQuery(".source_publish_date_" + post_id).show();
		//jQuery(".glf_update_date_" + post_id).show();
		jQuery("#view_list_post_" + post_id).hide();
		jQuery("#close_list_post_" + post_id).show();
		jQuery("#list_post_edit_btn_" + post_id).show();
		jQuery("#list_post_content_" + post_id).show();
		jQuery(".flag_inappropriate_popup_content_" + post_id).show();
		$http({
			method: 'post',
			data: {
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'post_id': post_id,
			},
			url: $scope.base_path + '/get_post_by_id',
		}).
		success(function (data, status, headers, config) {
			if (data) {

				jQuery(".single_post_multi_image_slide_" + post_id + ",.single_post_multi_video_slide_" + post_id).show();
				jQuery(".single_post_image_section_" + post_id).html(data);
				jQuery(".entry.list_post_content .post_content_section a").attr("target","_blank");
				jQuery(".single_post_multi_image_slide_" + post_id + ",.single_post_multi_video_slide_" + post_id).bxSlider({
					pager: false,
					adaptiveHeight: true,
					preloadImages: 'all',
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
			}
		}).
		error(function (data, status, headers, config) {
			$scope.messageFailure(data.message);
		});
	}

	$scope.close_the_content_click = function (post) {
		jQuery(".user_info_and_bias_rated_source_"+post_id).hide();
		post_id = post.post_id;
		jQuery(".post_modified_" + post_id).hide();
		jQuery(".post_share_section_" + post_id).hide();
		//jQuery(".source_publish_date_" + post_id).hide();
		//jQuery(".glf_update_date_" + post_id).hide();
		jQuery("#view_list_post_" + post_id).show();
		jQuery("#close_list_post_" + post_id).hide();
		jQuery("#list_post_edit_btn_" + post_id).hide();
		jQuery("#list_post_content_" + post_id).hide();
	}

	$scope.rating_option = function (post, action, type) {
		if (type == 'show') {
			jQuery("." + action + "_action_show_" + post.post_id).hide(); // Plus image hide
			jQuery("." + action + "_action_close_" + post.post_id).show(); // Close image show
			jQuery("." + action + "_option_" + post.post_id).show(); // Options show
		} else if (type == 'close') {
			jQuery("." + action + "_action_show_" + post.post_id).show(); // Plus image show
			jQuery("." + action + "_action_close_" + post.post_id).hide(); // Close image hide
			jQuery("." + action + "_option_" + post.post_id).hide(); // Options hide
		}
	}

	$scope.fnLoadPosts = function (post_data, form_post, post, type) {
		var d = new Date();
   	var ampm = (d.getHours() >= 12) ? "PM" : "AM";
   	var hours = (d.getHours() >= 12) ? d.getHours()-12 : d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();
	var glf_time = ((''+hours).length<2 ? '0' :'') + hours+':'+((''+minute).length<2 ? '0':'') + minute+' '+ampm;
  	//alert( glf_time );

	var month = d.getMonth()+1;
	var day = d.getDate();
	//var year = d.getFullYear().toString().substr(2,2);
	var year = d.getFullYear();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();

	var post_client_date = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
    var months = ["","Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    month1 = months[3];
	var output =  ' '+month1 + ' ' + ((''+day).length<2 ? '0' : '') + day + ' ' + year + ' at ' +glf_time;
	jQuery(".post_rating_action #post_client_date").val(output);
		if (!post) {
			//alert(1);
			post_id = jQuery("#set_post_id").val();
		} else {
			//alert(2);
			post_id = post.post_id;
		}
		informative = '';
		bias = '';
		informative_val = '';
		bias_val = '';
		if (!post_data) {
			//alert(3);
			option = jQuery("#set_vote_option").val();
			if (option == 'very' || option == 'somewhat' || option == 'not_really') {
				//alert(4);
				informative = option;
				informative_val = option;
			} else if (option == 'liberal' || option == 'neutral' || option == 'conservative') {
				//alert(5);
				bias = option;
				bias_val = option;
			}
		} else {
			//alert(6);
			informative = (post_data.informative) ? post_data.informative : '';
			bias = (post_data.bias) ? post_data.bias : '';
			informative_val = (post_data.informative) ? post_data.informative : '';
			bias_val = (post_data.bias) ? post_data.bias : '';
		}

		if (informative == '' && bias == '') {
			//alert(7);
			var msg = '<p class="error_msg option_validate">Please rate on quality and bias</p>';
			//alert(msg);
			jQuery(".option_validate").remove();
			jQuery(".error_class_rating_" + post_id).remove();
			jQuery("form#user_infer_bias_voting_form_" + post_id).append(msg);
			jQuery("body .error_class_rating_" + post_id).append(msg);

		} else if( informative == '' &&  !bias) {
			//alert(8);
			var msg = '<p class="error_msg option_validate">Please select informative options</p>';
			//alert(msg);
			jQuery(".option_validate").remove();
			jQuery(".error_class_rating_" + post_id).remove();
			jQuery("form#user_infer_bias_voting_form_" + post_id).append(msg);
			jQuery(".error_class_rating_" + post_id).append(msg);
		} else if( bias == '' && !informative ) {
			//alert(9);
			var msg = '<p class="error_msg option_validate">Please select bias options</p>';
			//alert(msg);
			jQuery(".option_validate").remove();
			jQuery(".error_class_rating_" + post_id).remove();
			jQuery("form#user_infer_bias_voting_form_" + post_id).append(msg);
			jQuery(".error_class_rating_" + post_id).append(msg);
		} else if( informative == '' && bias == '' || informative != '' && bias == '' || informative == '' && bias != '') {
			jQuery(".error_msg").remove();
		jQuery("#user_"+form_post+"_voting_form_"+post_id).append('<p class="error_msg">Please rate on quality and bias</p>');
		}



		else{
			//alert(10);
			//alert(informative+' - '+bias);
			//jQuery(".post_submit_"+post_id).addClass('action_disable');
			jQuery(".post_submit_" + post_id).replaceWith('<a href="javascript:void(0);" id="post_submit" class="post_submit_' + post_id + ' action_disable">Submit</a>');
			jQuery(".option_validate").remove();
			$http({ // Load posts from the WordPress API
				method: 'POST',
				url: theme_obj.ajax_url + '/?action=user_voting',
				params: {
					'post_id': post_id,
					'post_client_date' : post_client_date,
					'informative': informative,
					'bias': bias
				},
			}).
			success(function (data, status, headers, config) {
				if (data.message == 'success') {
					jQuery(".list_post_rating_section_" + post_id).empty();
					var info = "'infermative'";
					var bias = "'bias'";
					var informative_high_vote_1 = '';
					var informative_high_vote_2 = '';
					var informative_high_vote_3 = '';
					if (data.informative_high_vote) {
						var informative_high_vote_1 = (data.informative_high_vote[0]) ? data.informative_high_vote[0].toLowerCase() : '';
						var informative_high_vote_2 = (data.informative_high_vote[1]) ? data.informative_high_vote[1].toLowerCase() : '';
						var informative_high_vote_3 = (data.informative_high_vote[2]) ? data.informative_high_vote[2].toLowerCase() : '';
					}
					var bias_high_vote_content_1 = '';
					var bias_high_vote_content_2 = '';
					var bias_high_vote_content_3 = '';
					if (data.bias_high_vote) {
						var bias_high_vote_content_1 = (data.bias_high_vote[0]) ? data.bias_high_vote[0].toLowerCase() : '';
						var bias_high_vote_content_2 = (data.bias_high_vote[1]) ? data.bias_high_vote[1].toLowerCase() : '';
						var bias_high_vote_content_3 = (data.bias_high_vote[2]) ? data.bias_high_vote[2].toLowerCase() : '';
					}
					jQuery(".success_list_rating_vote_" + post_id).show();
					very_count = '';
					somewhat_count = '';
					not_really_count = '';
					very_vote = '';
					somewhat_vote = '';
					not_really_vote = '';
					if (data.post_rating_options_informative_count) {
						very_count = data.post_rating_options_informative_count.very.count;
						somewhat_count = data.post_rating_options_informative_count.somewhat.count;
						not_really_count = data.post_rating_options_informative_count.not_really.count;
						very_vote = data.post_rating_options_informative_count.very.vote;
						somewhat_vote = data.post_rating_options_informative_count.somewhat.vote;
						not_really_vote = data.post_rating_options_informative_count.not_really.vote;
					}
					liberal_count = '';
					neutral_count = '';
					conservative_count = '';
					liberal_vote = '';
					neutral_vote = '';
					conservative_vote = '';
					if (data.post_rating_options_bias_count) {
						liberal_count = data.post_rating_options_bias_count.liberal.count;
						neutral_count = data.post_rating_options_bias_count.neutral.count;
						conservative_count = data.post_rating_options_bias_count.conservative.count;
						liberal_vote = data.post_rating_options_bias_count.liberal.vote;
						neutral_vote = data.post_rating_options_bias_count.neutral.vote;
						conservative_vote = data.post_rating_options_bias_count.conservative.vote;
					}
					_show = "'show'";
					_close = "'close'";
					infer_bias = "'infer_bias'";
					single = "'single'";
					var infor_option = '';
					var infor_rating_action = '';
					var bias_option = '';
					var bias_rating_action = '';
					if (post_data) {
						if (!post_data.informative && data.user_vote_1 == '') {
							infor_rating_action = '<li><span><a class="post_rating" href="javascript:void(0);" onclick="vote_post_rating(' + post_id + ', ' + info + ');">Rate the source</a></span></li>';
							infor_option = '<div class="infermative_tab infermative_voting_option_tab_' + post_id + '" style="display:none;">' + '<span>Informative?</span>' + '<a class="rating_option infermative_action infermative_action_show infermative_action_show_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + info + ', ' + _show + ');">' + '<i class="fa fa-plus fa-2x"></i>' + '</a>' + '<a class="rating_option infermative_action_close infermative_action_close_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + info + ', ' + _close + ');">' + '<img src="'+theme_obj.base_url+'/assets/images/close_sign.png" alt="" width="23" height="23">' + '</a>' + '<div class="infermative_option infermative_option_' + post_id + '" style="display:none;">' + '<select name="informative_select" id="informative_select" class="rating_options_select cs-select cs-skin-elastic" onchange="change_option_vote(this, this.value);">' + '<option value="">Select</option>' + '<option value="very">very</option>' + '<option value="somewhat">somewhat</option>' + '<option value="not_really">not really</option>' + '</select>' + '</div>' + '<div class="post_rating_action">' + '<span class="error_class_rating_' + post_id + '"></span><a href="javascript:void(0);" id="post_submit" class="post_submit_' + post_id + '" onclick="set_post_vote_option(this, ' + post_id + ');">Submit</a>' + '</div>' + '</div>';
						} else if (!post_data.bias && data.user_vote_2 == '') {
							bias_rating_action = '<li><span><a class="post_rating" href="javascript:void(0);" onclick="vote_post_rating(' + post_id + ', ' + bias + ');">Rate the source</a></span></li>';
							bias_option = '<div class="bias_voting_option_tab_' + post_id + '" style="display:none;">' + '<span>Bias</span>' + '<a class="rating_option bias_action bias_action_show bias_action_show_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + bias + ', ' + _show + ');">' + '<i class="fa fa-plus fa-2x"></i>' + '</a>' + '<a class="rating_option bias_action_close bias_action_close_' + post_id + '" href="javascript:void(0);" onclick="display_rating_option(' + post_id + ', ' + bias + ', ' + _close + ');" style="display:none;">' + '<img src="'+theme_obj.base_url+'/assets/images/close_sign.png" alt="" width="23" height="23">' + '</a>' + '<div class="bias_option bias_option_' + post_id + '" style="display:none;">' + '<select name="bias_select" id="bias_select" class="rating_options_select cs-select cs-skin-elastic" onchange="change_option_vote(this, this.value);">' + '<option value="">Select</option>' + '<option value="liberal">liberal</option>' + '<option value="neutral">neutral</option>' + '<option value="conservative">conservative</option>' + '</select>' + '</div>' + '<div class="post_rating_action">' + '<span class="error_class_rating_' + post_id + '"></span><a href="javascript:void(0);" id="post_submit" class="post_submit_' + post_id + '" onclick="set_post_vote_option(this, ' + post_id + ');">Submit</a>' + '</div>' + '</div>';
						}
					}
					info_result = '';
					if (very_count || somewhat_count || not_really_count) {
						info_result = '<ul class="infermative_vote_results infermative_vote_results_' + post_id + '">' + '<li><label>very</label><span> - ' + very_count + ' (' + very_vote + ')</span></li>' + '<li><label>somewhat</label><span> - ' + somewhat_count + ' (' + somewhat_vote + ')</span></li>' + '<li><label>not really</label><span> - ' + not_really_count + ' (' + not_really_vote + ')</span></li>' + infor_rating_action + bias_rating_action + '</ul>';
					} else {
						info_result = '<ul class="infermative_vote_results infermative_vote_results_' + post_id + '">' + infor_rating_action + bias_rating_action + '</ul>';
					}
					var and = '';
					if (informative_high_vote_1 && bias_high_vote_content_1 || post_data.informative && post_data.bias) {
						var and = 'and&nbsp;';
					}
					content = '';
					if (informative_high_vote_1 || post_data.informative) {
						content = '<div class="informative_vote_result_section vote_result_section">' + '<a href="javascript:void(0);" ng-mouseover="display_voting(' + post + ',' + info + ');">' + '<span class=""> ' + informative_high_vote_1 + informative_high_vote_2 + informative_high_vote_3 + ' </span>' + 'informative </a> ' + and + ' <div id="post_infermative_voting_' + post_id + '" class="post_voting_section">' + '<div class="voting_option_values">' + '<span class="infermative_vote_results_title_' + post_id + '">Informative</span>' + info_result + infor_option + bias_option + '</div>' + '</div>' + '</div>';
					}
					bias_result = '';
					if (liberal_count || neutral_count || conservative_count) {
						bias_result = '<ul class="bias_vote_results bias_vote_results_' + post_id + '">' + '<li><label>liberal</label><span class="ng-binding"> - ' + neutral_count +' (' + neutral_vote + ')</span></li>' + '<li><label>neutral</label><span class="ng-binding"> - ' +  liberal_count+ ' (' + liberal_vote + ')</span></li>' + '<li><label>conservative</label><span class="ng-binding"> - ' + conservative_count + ' (' + conservative_vote + ')</span></li>' + bias_rating_action + infor_rating_action + '</ul>';
					} else {
						bias_result = '<ul class="bias_vote_results bias_vote_results_' + post_id + '">' + bias_rating_action + infor_rating_action + '</ul>';
					}
					if (bias_high_vote_content_1 || post_data.bias) {
						content += '<div class="bias_vote_result_section vote_result_section">' + '<a href="javascript:void(0);" ng-mouseover="display_voting(' + post + ', ' + bias + ');"><span>' + bias_high_vote_content_1 + bias_high_vote_content_2 + bias_high_vote_content_3 + '</span> bias </a>' + '<div id="post_bias_voting_' + post_id + '" class="post_voting_section">' + '<div class="voting_option_values">' + '<span class="bias_vote_results_title_' + post_id + '">Bias?</span>' + bias_result + bias_option + infor_option + '</div>' + '</div>' + '</div>';
					}
					jQuery(".list_post_rating_section_" + post_id).html(content);
					var rated = '';
					//alert(0);
					if (informative_val && bias_val) {
						//alert(1);
						rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + informative_val + ' informative and &nbsp;</li><li class="user_vote_rating user_bias_rated_source_' + post_id + '">' + bias_val + ' bias</li>';
					} else if (informative_val) {
						//alert(2);
						bias_text = jQuery(".user_bias_rated_source_" + post_id).text();
						if (bias_text) {
							//alert(3);
							rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + informative_val + ' informative and &nbsp; </li><li class="user_vote_rating user_bias_rated_source_' + post_id + '">&nbsp;' + bias_text + '</li>';
						} else {
							//alert(4);
							rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + informative_val + ' informative </li>';
							if(data.user_vote_2){
							  rated+=' &nbsp; and '+ data.user_vote_2 +'  bias';
							}
						}
					} else if (bias_val) {
						//alert(5);
						info_text = jQuery(".user_info_rated_source_" + post_id).text();
						if (info_text) {
							//alert(6);
							rated = '<li class="user_vote_rating user_info_rated_source_' + post_id + '">' + info_text + 'and &nbsp;</li><li class="user_vote_rating user_bias_rated_source_' + post_id + '">&nbsp;' + bias_val + ' bias</li>';
						} else {
							//alert(7);
							rated = '<li class="user_vote_rating user_bias_rated_source_' + post_id + '">' + bias_val + ' bias</li>';
						}
					}
					if (rated) {
						//alert(rated);

						rated_content = '<span><ul><li><span>You rated as </span></li>'+ rated +'</ul> ' + output + '</span>';
						jQuery(".user_info_and_bias_rated_source_" + post_id).show();
						jQuery(".user_info_and_bias_rated_source_" + post_id).remove();
						jQuery(".user_info_and_bias_rated_source_quick_" + post_id).html(rated_content);
					} else {
						//alert(10);
					}
					jQuery(".post_submit_" + post_id).removeClass('action_disable');
				} else {}
			});
		}
	}

	$scope.post_rating = function (post, action) {

		post_id = post.post_id
		jQuery("." + action + "_voting_option_tab_" + post_id).show();
	}

	$scope.filter_ass_desc = function () {
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		order = '';
		if ($scope.order == "DESC") {
			order = 'ASC';
			$scope.order = order;
			$scope.order_class = 'asdsorder';
		} else {
			order = 'DESC';
			$scope.order = order;
			$scope.order_class = '';
		}
		$scope.informative = jQuery("#filter_informative").val();
		$scope.bias = jQuery("#filter_bias").val();
		//alert($scope.informative+' - '+$scope.bias);
		//alert('Load - '+$scope._load+'\nTax - '+$scope.tax+'\nTerm - '+$scope.term);
		$http({
			method: 'post',
			data: {
				'load': ($scope._load) ? $scope._load : '',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': order,
				'user_role': theme_obj.user_role,
				'infor_filter': ($scope.informative) ? $scope.informative : '',
				'bias_filter': ($scope.bias) ? $scope.bias : '',
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.data = [];
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.dropdown_filter = function () {
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope.informative = jQuery("#filter_informative").val();
		$scope.bias = jQuery("#filter_bias").val();
		//alert($scope.informative+' - '+$scope.bias);
		$http({
			method: 'post',
			data: {
				'load': ($scope._load) ? $scope._load : '',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': $scope.order,
				'user_role': theme_obj.user_role,
				'infor_filter': ($scope.informative) ? $scope.informative : '',
				'bias_filter': ($scope.bias) ? $scope.bias : '',
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.data = [];
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data;
			if (data.post_data.text) {
				$scope.data = data.post_data;
			}
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
				//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
			}
			jQuery("#num_of_page").val(data.total_page);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.most_viewed_filter = function (type, tab) {
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		jQuery(".tab_action").removeClass('active');
		jQuery("." + tab).addClass('active');
		$http({
			method: 'post',
			data: {
				'load': 'most_viewed',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': '',
				'term': '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'view': type,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			$scope.data = [];
			$scope.most_view = 1;
			//$scope.data = data.post_data.post_data;
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.share_this_post_email = function (post, title, post_link, type) {
		/*if (type == 'index') {
			str = 'Check this out on Hyroglf!\n\n' + post.post_link;
			jQuery("#hidden_share_title").val(post.post_title);
			jQuery("textarea#txtMessage").val(str);
		} else {
			jQuery("#hidden_share_title").val(title);
			str = 'Check this out on Hyroglf!\n\n' + post_link;
			jQuery("textarea#txtMessage").val(str);
		}
		jQuery.fancybox({
			'type': 'inline',
			'href': '#post_share_via_email_content'
		});*/

		//alert(post.post_id);
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=get_title_by_id_post_email',
			//data : data,
			params: {
				'post_id': post.post_id,
			},
		}).
		success(function (data, status, headers, config) {
			//console.log(post.post_title);
			//alert(data);
			if (type == 'index') {
				$scope.post_shared_title = post.post_title;
				$scope.post_shared_link = data._link;
				//str = "Hey!\n\n  "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+data._link+">"+post.post_title+"</a><p>Best Regards,</p><p>Hyroglf</p>" ;
				jQuery("#hidden_share_title").val(post.post_title);
				jQuery("textarea#txtMessage").val(str);
			} else {
				$scope.post_shared_title = post.post_title;
				$scope.post_shared_link = data._link;
				jQuery("#hidden_share_title").val(post.post_title);
				//str = "Hey!\n\n "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+data._link+">"+post.post_title+"</a><p>Best Regards,</p><p>Hyroglf</p>"
				jQuery("textarea#txtMessage").val(str);
			}
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});

		jQuery.fancybox({
			'type': 'inline',
			'href': '#post_share_via_email_content'
		});

	}

	$scope.fnflagpost = function (post) {
		post_id = post.post_id;
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=user_flag_report',
			params: {
				'post_id': post_id
			},
		}).
		success(function (data, status, headers, config) {
			var _open = "'open'";
			var _close = "'close'";
			warning_content = '<div class="flag_inappropriate_popup_content flag_inappropriate_popup_content_' + post_id + '" style="display:none"><div class="flag_popup_content"><p>Warning! This post has been flagged as inappropriate! Do you wish to open?</p><a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="view_content_click_quick(' + post_id + ', ' + _open + ');">Yes</a><a class="flag_popup_content_close_btn" href="javascript:void(0);" onclick="close_the_content_click_quick(' + post_id + ', ' + _close + ');">No</a></div></div>';
			jQuery(".quick_warning_content_" + post_id).html(warning_content);
			post.flag_inappropriate_count = 1;
			jQuery("#flag_message_" + post_id).show();
			$scope.flag_inapproperiate = data;
			//console.log(data);
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}

	$scope.flag_advertisement_set = function () {
		//post_id= post.post_id;
		post_id = jQuery("#hidden_p_id").val();
		//alert(post_id);
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=user_flag_advertisement',
			params: {
				'post_id': post_id
			},
		}).
		success(function (data, status, headers, config) {
			//post.flag_as_advertisement_count = 1;
			jQuery("#flag_as_adverstiment_message_"+post_id).show();
			jQuery("#flag_advertisement_post_report_" + post_id).remove();
			$scope.flag_advertisement = data;
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}

	$scope.close_flag_inappropriate_popup_content = function (post, action) {
		post_id = post.post_id;
		if (action == 'open') {
			jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
			jQuery(".flag_inappropriate_popup_content_random").hide();
		} else {
			jQuery(".flag_inappropriate_popup_content_" + post_id).hide();
			jQuery(".flag_inappropriate_popup_content_random").hide();
			$scope.close_the_content_click(post);
		}
	}

	$scope.post_filter_by_author = function () {

		jQuery(".post_filter_text").removeClass("search_res");
		jQuery(".post_heade_edit_action").show();
		jQuery(".sort_section").show();
		jQuery(".filter_post_img").show();
		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope.term = jQuery("#filter_category").val();
		$scope.tax = jQuery("#filter_taxonomy").val();
		$scope._load = 'cat_post';
		//$scope.selected_category = 'Post and vote by "' + $scope.term + '"';
		//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
		$scope.selected_category = $scope.term+"'s " +'POV';
		//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/GLF-Favicon.png width="150" height="150"/>';
		if($scope.popupHeight < 1050){
			$scope.selected_category_img = '';
		} else {
			$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/pencil_sign.png width="150" height="150"/>';
		}
		$http({
			method: 'post',
			data: {
				'load': 'cat_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': 'DESC',
				'user_role': theme_obj.user_role,
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			data.post_data.text = "You haven't posted yet.";
			$scope.data = [];
			$scope.result = data.post_data;
			var num_post = $scope.result.length;
			if(!num_post){
				$scope.data =$scope.result;
			}
			post = {}
			for (i = 0; i < num_post; i++) {
				$scope.data.push($scope.result[i]);
			}
			jQuery("#num_of_page").val(data.total_page);
			var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.post_filter_by_search = function ($event, search_key, page_of) {
		jQuery(".post_filter_text").removeClass("search_res");

		jQuery(".loaded_cat").remove();
		jQuery("#num_of_scroll").val(1);
		$scope._load = 'search_post';
		if (search_key) {
			$scope.term = search_key;
			$scope.tax = 'search_post';
		} else {
			$scope.term = jQuery("#filter_category").val();
			$scope.tax = jQuery("#filter_taxonomy").val();
		}
		jQuery(".post_heade_edit_action").hide();
		//$scope.selected_category = 'Search results for "' + $scope.term + '"';
		//$scope.selected_category_img = '<img src=' + $scope.base_url + '/assets/images/search_symbol.png width="150" height="150"/>';
		$scope.selected_category_img = '';
		jQuery(".filter_post_img").hide();

		$http({
			method: 'post',
			data: {
				'load': 'search_post',
				'wpdb': theme_obj.wpdb,
				'user_id': theme_obj.user_id,
				'user_access': theme_obj.user_access,
				'tax': ($scope.tax) ? $scope.tax : '',
				'term': ($scope.term) ? $scope.term : '',
				'page_num': 1,
				'order': 'DESC',
				'infor_filter': ($scope.informative) ? $scope.informative : '',
				'bias_filter': ($scope.bias) ? $scope.bias : '',
				'user_role': theme_obj.user_role,
				'view': '',
				'user_ip': theme_obj.user_ip,
				'plugin_url': theme_obj.plugin_url,
				'site_url': theme_obj.site_url,
				'base_url': theme_obj.base_url,
			},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
			headers: {
				'Content-Type': 'application/json'
			}
		}).
		success(function (data, status, headers, config) {
			jQuery(".sort_section").show();
			if($scope.popupHeight <= 992){
				jQuery(".header_left_content").show();
				jQuery(".header_right_content").hide();
			}
			jQuery(".home_head_content").show();
			$scope.selected_category = 'Search results for "' + $scope.term + '"';
			$scope.data = data.post_data;
			//console.log(data);
			var body = jQuery("html, body");
			body.stop().animate({
				scrollTop: 0
			}, '300', 'swing', function () {
				// your action
			});
		}).
		error(function (data, status, headers, config) {});
	}

	$scope.scroll_load_categories = function () {
		jQuery(".wiki_left_section .wiki_category ul").append('<li class="pro_list_image loaded_cat">' + shuffleArray(theme_obj.scroll_cat) + '</li>');
		jQuery(".wiki_right_section .wiki_category ul").append('<li class="pro_list_image loaded_cat">' + shuffleArray(theme_obj.scroll_cat) + '</li>');
		if(jQuery('.home_content_section .list_of_post_content_section:nth-last-child(1)').length>0){
		  setTimeout(function () {
			  var toplastchild = jQuery('.home_content_section .list_of_post_content_section:nth-last-child(1)').offset().top;
			  //alert(toplastchild);
			  jQuery('.wiki_left_section').css({
				  "height": toplastchild + 'px',
				  "overflow": "hidden"
			  });
			  jQuery('.wiki_right_section').css({
				  "height": toplastchild + 'px',
				  "overflow": "hidden"
			  });
		  }, 30);
		}
		//alert(shuffleArray(theme_obj.scroll_cat)+' \n '+shuffleArray(theme_obj.scroll_cat));
	}

	$scope.scroll_load_post = function () {
		//alert(jQuery(".list_of_post_content_section").length)

		num_of_scroll = jQuery("#num_of_scroll").val();
		scroll_num = parseInt(num_of_scroll) + 1;
		//alert(scroll_num);
		num_of_page = jQuery("#num_of_page").val();
		//alert( $scope._load+' - '+$scope.tax+' - '+$scope.term+' - '+num_of_scroll+' - '+num_of_page+' - '+scroll_num);
		if( $scope._load != "random" ) {
			if (scroll_num <= num_of_page && num_of_scroll != $scope.scroll_page) {
				$scope.scroll_page = num_of_scroll;
				$http({
					method: 'post',
					data: {
						'load': ($scope._load) ? $scope._load : '',
						'wpdb': theme_obj.wpdb,
						'user_id': theme_obj.user_id,
						'user_access': theme_obj.user_access,
						'tax': ($scope.tax) ? $scope.tax : '',
						'term': ($scope.term) ? $scope.term : '',
						'page_num': scroll_num,
						'order': $scope.order,
						'user_role': theme_obj.user_role,
						'infor_filter': ($scope.informative) ? $scope.informative : '',
						'bias_filter': ($scope.bias) ? $scope.bias : '',
						'user_ip': theme_obj.user_ip,
						'plugin_url': theme_obj.plugin_url,
						'site_url': theme_obj.site_url,
						'base_url': theme_obj.base_url,
					},
					url: theme_obj.site_url+'/'+$scope.base_path + '/get_post',
					// headers: {'Content-Type': 'application/json'}
				}).
				success(function (data, status, headers, config) {
					//$scope.data = data.post_data.post_data;
					$scope.result = data.post_data;
					var num_post = $scope.result.length;
					post = {}
					for (i = 0; i < num_post; i++) {
						$scope.data.push($scope.result[i]);
						//if($scope.term != '')
							//$scope.result[i].post_meta.post_ref_link_favicon = $scope.selected_category_img;
					}
					jQuery("#num_of_scroll").val(scroll_num);
				}).
				error(function (data, status, headers, config) {});
			}
		}
	}

	function isValidEmailAddress(emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test(emailAddress);
	};

	$scope.share_post_email = function () {

		var frommailval=jQuery("#txtYourEmail").val();
		if(isValidEmailAddress(frommailval)== false)

		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		var tomailval=jQuery("#txtRecipientEmail").val();
		if(isValidEmailAddress(tomailval)== false)

		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		var tonameval=jQuery("#txtRecipientName").val();
		if(tonameval=="")
		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter the name</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}

		share_title = jQuery("#hidden_share_title").val();
		YourEmail = jQuery("#txtYourEmail").val();
		$scope.youremail = YourEmail;
		//str = "Hey!\n\n  "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+$scope.post_shared_link+">"+$scope.post_shared_title+"</a><p>Best Regards,</p><p>Hyroglf</p>" ;
		/*str = '<div class="mail_div" style="max-width:320px;"><div class="mail_div_top" style="border-bottom:1px solid #ccc; padding:0px 0px 10px"><h6 style="padding:10px 0px; font-size:13px; color:rgb(34, 34, 34); margin:0px; font-weight:400; display:inline-block;">Hey!</h6>'+$scope.youremail+'</div><p>shared a Hyroglf post with you. Click below to view post.</p><a style="background:#f5bd5b;color:#ffffff;border-radius:4px;padding:8px 25px;display:inline-block;margin:5px 0 0 0px" href="'+$scope.post_shared_link+'">View Post</a><div class="yj6qo"></div><div class="adL"><br></div></div>';*/
		//str = "Hey!\n\n  "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+$scope.post_shared_link+">"+'View Post'+"</a>" ;
		RecipientEmail = jQuery("#txtRecipientEmail").val();
		RecipientName = jQuery("#txtRecipientName").val();
		//alert($scope.post_shared_link +'-'+ RecipientName)
		//Message = str;
		//Message = jQuery("#txtMessage").val()
		//alert(share_title+', '+YourEmail+', '+RecipientEmail+', '+Message);
		jQuery(".share_post_email_fields").addClass('action_disable');
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=share_post_email_send',
			//data : data,
			params: {
				'youremail': YourEmail,
				'recipientemail': RecipientEmail,
				//'message': Message,
				'title': share_title,
				'recipientname' : RecipientName,
				'post_shared_link' : $scope.post_shared_link
			},
		}).
		success(function (data, status, headers, config) {
			if (data.action == true) {
				jQuery("#hidden_share_title").val('');
				jQuery("#txtYourEmail").val('');
				jQuery("#txtRecipientEmail").val('');
				jQuery("#txtRecipientName").val('');
				jQuery("#txtMessage").val('');
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status").append('<p class="success_msg">Post shared!</p>');
				//jQuery('.success_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else if (jQuery("#txtRecipientEmail").val() == '' || jQuery("#txtYourEmail").val() == '' || jQuery("#txtRecipientName").val() == '') {
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Please enter the required field</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else if (!isValidEmailAddress(jQuery("#txtRecipientEmail").val()) || !isValidEmailAddress(jQuery("#txtYourEmail").val())){
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Please enter the required field</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else {
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Sorry some technical error occured.</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			}
			$scope.share_status = $sce.trustAsHtml(data.message);
			jQuery(".share_post_email_fields").removeClass('action_disable');
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}

});

app.directive('ngBindHtmlUnsafe', [
	function () {
		return function (scope, element, attr) {
			element.addClass('ng-binding').data('$binding', attr.ngBindHtmlUnsafe);
			scope.$watch(attr.ngBindHtmlUnsafe, function ngBindHtmlUnsafeWatchAction(value) {
				element.html(value || '');
			});
		}
	}
]);

app.controller('authSingleCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {

	function isValidEmailAddress(emailAddress) {
		var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		return pattern.test(emailAddress);
	};

	$scope.share_this_post_email = function (id ,post, title, post_link, type) {
		if (type == 'index') {
			$scope.post_shared_title = title;
			$scope.post_shared_link = post_link;
			//str = "Hey!\n\n "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+post_link+">"+title+"</a><p>Best Regards,</p><p>Hyroglf</p>";
			jQuery("#hidden_share_title").val(title);
			jQuery("textarea#txtMessage").val(str);
		}
		if (type == 'page') {
			jQuery("#hidden_share_title").val(title);
			$scope.post_shared_title = title;
			$scope.post_shared_link = post_link;
			//str = "Hey!\n\n "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+post_link+">"+title+"</a><p>Best Regards,</p><p>Hyroglf</p>";
			jQuery("textarea#txtMessage").val(str);
		}
		jQuery.fancybox({
			'type': 'inline',
			'href': '#post_share_via_email_content'
		});
	}
	$scope.share_post_email = function () {
		var frommailval=jQuery("#txtYourEmail").val();
		if(isValidEmailAddress(frommailval)== false)

		{
		jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		var tomailval=jQuery("#txtRecipientEmail").val();
		if(isValidEmailAddress(tomailval)== false)

		{
		jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
			//jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		var tonameval=jQuery("#txtRecipientName").val();
		if(tonameval=="")
		{
			jQuery(".post_share_via_email_content").addClass("post_sent");
			jQuery(".share_mail_status").html('<p class="error_msg">Post not shared. Please enter the name</p>');
			jQuery('.error_msg').fadeOut(5000);
			setTimeout(function(){
				jQuery('#post_share_via_email_content').removeClass('post_sent');
			},5000);
			return false;
		}
		share_title = jQuery("#hidden_share_title").val();
		YourEmail = jQuery("#txtYourEmail").val();
		$scope.youremail  = YourEmail;
		//str = "Hey!\n\n "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+$scope.post_shared_link+">"+$scope.post_shared_title+"</a><p>Best Regards,</p><p>Hyroglf</p>";
		str = '<div class="mail_div" style="max-width:320px;"><div class="mail_div_top" style="border-bottom:1px solid #ccc; padding:0px 0px 10px"><h6 style="padding:10px 0px; font-size:13px; color:rgb(34, 34, 34); margin:0px; font-weight:400; display:inline-block; text-decoration:none;">Hey!</h6>'+$scope.youremail+'</div><p>shared a Hyroglf post with you. Click below to view post.</p><a style="background:#f5bd5b;color:#ffffff;border-radius:4px;padding:8px 25px;display:inline-block;margin:5px 0 0 0px" href="'+$scope.post_shared_link+'">View Post</a><div class="yj6qo"></div><div class="adL"><br></div></div>';

		//str = "Hey!\n\n "+$scope.youremail+" shared a Hyroglf post with you. Click below to view post.\n\n <a style='background:#f5bd5b; color:#ffffff; border-radius:4px; padding:8px 25px; display:inline-block;' href="+$scope.post_shared_link+">"+'View Post'+"</a>";
		//RecipientEmail = jQuery("#txtRecipientEmail").val();
		//Message = str;
		RecipientEmail = jQuery("#txtRecipientEmail").val();
		RecipientName  = jQuery("#txtRecipientName").val();
		//Message = jQuery("#txtMessage").val()
		//alert(share_title+', '+YourEmail+', '+RecipientEmail+', '+Message);
		jQuery(".share_post_email_fields").addClass('action_disable');
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=share_post_email_send',
			//data : data,
			params: {
				'youremail': YourEmail,
				'recipientemail': RecipientEmail,
				//'message': Message,
				'title': share_title,
				'recipientname' : RecipientName,
				'post_shared_link' : $scope.post_shared_link
			},
		}).
		success(function (data, status, headers, config) {
			if (data.action == true) {
				jQuery("#hidden_share_title").val('');
				jQuery("#txtYourEmail").val('');
				jQuery("#txtRecipientEmail").val('');
				jQuery("#txtRecipientName").val('');
				jQuery("#txtMessage").val('');
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status").append('<p class="success_msg">Post shared!</p>');
				//jQuery('.success_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else if (jQuery("#txtRecipientEmail").val() == '' || jQuery("#txtYourEmail").val() == '' || jQuery("#txtRecipientName").val() == '') {
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Post not shared. Please enter a valid email address.</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else if (!isValidEmailAddress(jQuery("#txtRecipientEmail").val()) || !isValidEmailAddress(jQuery("#txtYourEmail").val())){
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Please enter the required field</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			} else {
				jQuery(".post_share_via_email_content").addClass("post_sent");
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Sorry some technical error occured.</p>');
				//jQuery('.error_msg').fadeOut(5000);
				setTimeout(function(){
					jQuery('#post_share_via_email_content').removeClass('post_sent');
				},5000);
			}
			/*if (data.action == true) {
				jQuery("#hidden_share_title").val('');
				jQuery("#txtYourEmail").val('');
				jQuery("#txtRecipientEmail").val('');
				jQuery("#txtRecipientName").val('');
				jQuery("#txtMessage").val('');
				jQuery(".share_mail_status .success_msg").hide();
				jQuery(".share_mail_status").append('<p class="success_msg">Post shared!</p>');
			} else {
				jQuery(".share_mail_status .error_msg").hide();
				jQuery(".share_mail_status").append('<p class="error_msg">Sorry some technical error occured.</p>');
			}*/
			$scope.share_status = $sce.trustAsHtml(data.message);
			jQuery(".share_post_email_fields").removeClass('action_disable');
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}
});

app.controller('authEditCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
	$scope.set_post_title = function () {
		//alert();
		reference_link = jQuery("#reference_link").val();
		$http({ // Load posts from the WordPress API
			method: 'POST',
			url: theme_obj.ajax_url + '/?action=get_post_and_refer_link_title',
			params: {
				'post_url': reference_link
			},
		}).
		success(function (data, status, headers, config) {
			jQuery("#fp_refernce_link_home_page_title").val(data.reference_link_title);
			jQuery("#fp_title").val(data.post_title);
		}).
		error(function (data, status, headers, config) {
			//console.log('error');
		});
	}
});
app.controller('myProfileFormCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
	$scope.ProfileCtrlSubmit = function (data) {
		if(jQuery("#username").val() || jQuery("#email").val()){
			$http({
				method: 'post',
				data: {
					'username' : jQuery("#username").val(),
					'useremail' : jQuery("#email").val(),
					'wpdb'   :   theme_obj.wpdb,
					'user_id' : theme_obj.user_id
				},
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				url: theme_obj.site_url+'/'+$scope.base_path + '/update_username_data',
			}).success(function (data, status, headers, config) {
				if(data.output.user_message && data.output.user_value == 0){
					jQuery('.content.profile_page .success_msg').fadeOut();
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .error_msg').html(data.output.user_message);
					//jQuery('.content.profile_page .error_msg').delay(2000).fadeOut();
				}
				if(data.output.email_message && data.output.email_value == 0){
					jQuery('.content.profile_page .success_msg').fadeOut();
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .error_msg').html(data.output.email_message)
					//jQuery('.content.profile_page .error_msg').delay(2000).fadeOut();

				}
				if(data.output.email_message && data.output.email_value == 1){
					jQuery('.content.profile_page .error_msg').fadeOut();
					jQuery('.content.profile_page .success_msg').fadeIn();
					jQuery('.content.profile_page .success_msg').html(data.output.email_message);
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
				}
				if(data.output.error_message){
					jQuery('.content.profile_page .success_msg').fadeOut();
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .error_msg').html(data.output.error_message)
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
				}
				if(data.output.message){
					jQuery("#check_password_popup").fadeIn();
				}

			});
			}
	}
	$scope.ChangeProfileCtrl = function (data) {

		if(jQuery("#username_hidden").val() || jQuery("#email_hidden").val()){
			$http({
				method: 'post',
				data: {
					'username' : jQuery("#username_hidden").val(),
					'useremail' : jQuery("#email_hidden").val(),
					'wpdb'   :   theme_obj.wpdb,
					'user_id' : theme_obj.user_id
				},
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				url: theme_obj.site_url+'/'+$scope.base_path + '/update_userprofile_data',
			}).success(function (data, status, headers, config) {
				if(data.output.user_message && data.output.user_value == 1){
					jQuery('.content.profile_page .error_msg').fadeOut();
					//jQuery('.content.profile_page .success_msg').fadeIn();
					//jQuery('.content.profile_page .success_msg').html(data.output.user_message);

					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
					$scope.profile_username = 	jQuery("#username_hidden").val();
					//setTimeout(function(){
						var login_url = theme_obj.site_url;
						login_url += '/login';
						window.location.href=login_url;
						//window.location.href=theme_obj.site_url;

					//},1000);
				}
				if(data.output.user_message && data.output.user_value == 0){
					jQuery('.content.profile_page .success_msg').fadeOut();
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .error_msg').html(data.output.user_message);
					//jQuery('.content.profile_page .error_msg').delay(2000).fadeOut();
				}
				if(data.output.email_message && data.output.email_value == 0){
					jQuery('.content.profile_page .success_msg').fadeOut();
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .error_msg').html(data.output.email_message)
					//jQuery('.content.profile_page .error_msg').delay(2000).fadeOut();

				}
				if(data.output.email_message && data.output.email_value == 0 && data.output.user_message && data.output.user_value == 1){
					setTimeout(function(){
						window.location.href=theme_obj.site_url;
					},1000);
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .success_msg').fadeIn();
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
					//jQuery('.content.profile_page .error_msg').delay(2000).fadeOut();
					$scope.profile_username = 	jQuery("#username_hidden").val();
				}
				if(data.output.email_message && data.output.email_value == 1 && data.output.user_message && data.output.user_value == 0){
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .success_msg').fadeIn();
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
					//jQuery('.content.profile_page .error_msg').delay(2000).fadeOut();
					$scope.profile_email = 	jQuery("#email_hidden").val();
					$scope.profile_username = 	jQuery("#username_hidden").val();
					setTimeout(function(){
						window.location.href=theme_obj.site_url;
					},1000);
				}

				if(data.output.email_message && data.output.email_value == 1){
					jQuery('.content.profile_page .error_msg').fadeOut();
					jQuery('.content.profile_page .success_msg').fadeIn();
					jQuery('.content.profile_page .success_msg').html(data.output.email_message);
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
					$scope.profile_email = 	jQuery("#email_hidden").val();
					jQuery("#hid_check_password").val('failed');

				}
				if(data.output.message){
					jQuery('.content.profile_page .error_msg').fadeOut();
					jQuery('.content.profile_page .success_msg').fadeIn();
					jQuery('.content.profile_page .success_msg').html(data.output.message)
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
					setTimeout(function(){
						window.location.href=theme_obj.site_url;
					},1000);

				}
				if(data.output.error_message){
					jQuery('.content.profile_page .success_msg').fadeOut();
					jQuery('.content.profile_page .error_msg').fadeIn();
					jQuery('.content.profile_page .error_msg').html(data.output.error_message)
					//jQuery('.content.profile_page .success_msg').delay(2000).fadeOut();
				}

			});
		}
	}

	$scope.loadprofiledata = function (data) {
		$http({
			method: 'post',
			data: {
				'wpdb'   :   theme_obj.wpdb,
				'user_id' : theme_obj.user_id
			},
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			url: theme_obj.site_url+'/'+$scope.base_path + '/get_profile_data',
		}).success(function (data, status, headers, config) {
			$scope.profile_username = data[0].user_login;
			$scope.profile_email = data[0].user_email;
			if(jQuery("#hid_check_password").val() == 'success'){
				$scope.ChangeProfileCtrl();
			}
		});
	}
});
