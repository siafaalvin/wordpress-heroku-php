<?php
add_action('wp_head', 'add_ajaxurl_for_theme');
function add_ajaxurl_for_theme() { ?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
<?php
}

add_action( 'admin_init', 'codex_init' );
function codex_init() {
	add_action( 'delete_post', 'delete_post_records_from_custom_table', 10 );
	add_action( 'delete_user', 'delete_user_voted_posts' );
}

function delete_post_records_from_custom_table( $post_id ) {
	global $wpdb;
	// Delete for "wp_hyroglf_users_voting" using post id
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."hyroglf_users_voting WHERE user_article_id = %d", $post_id ));

	// Delete for "wp_hyroglf_users_voting_for_articles" using post id
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles WHERE article_id = %d", $post_id ));
}

function delete_user_voted_posts( $user_id ) {
	global $wpdb;

	// Delete for "wp_hyroglf_users_voting" using user id
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."hyroglf_users_voting WHERE user_id = %d", $user_id ));

	// Delete for "wp_hyroglf_users_voting_for_articles" using user id
	$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles WHERE user_id = %d", $user_id ));

}

function get_category_fn() {
	$cat_arr = array();
	$terms = get_terms('category');
	if($terms) {
		foreach( $terms as $term ) {
			$cat_arr[] = array('cat_id' => $term->term_id, 'cat_name' => $term->name, 'cat_slug' => $term->slug);
		}
	}
	return $cat_arr;
}

function get_tags_fn() {
	$tag_arr = array();
	$tags = get_tags('post_tag');
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr[] = $tag->name;
		}
	}
	return $tag_arr;
}

function get_user_search_key() {
	$search_key = array();
	if( is_user_logged_in() ) {
		global $wpdb;
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$search_key_obj = $wpdb->get_results("SELECT search_key FROM ".$wpdb->prefix."hyroglf_user_search_keys WHERE user_id = '$user_id' ORDER BY search_key");
		foreach( $search_key_obj as $search ) {
			$search_key[] = $search->search_key;
		}
	}
	return $search_key;
}

function About_Content() {
	$query = new WP_Query( array( 'pagename' => 'about' ) );
        while ( $query->have_posts() ) : $query->the_post(); global $wpdb, $post;
			$post_meta = get_post_meta($post->ID);
			echo apply_filters('the_content',$post_meta['about_popup_content'][0]);
			/*//echo '<h2>'. get_the_title() .'</h2>';
			the_content();*/
		endwhile;
    wp_reset_postdata();

}

function post_share_via_email_content() { ?>
    <div class="share_post_email_wrapper">
        <div class="share_post_email_content">
            <div class="share_mail_status" ng-bind-html="share_status"></div>
            <div class="share_post_email_fields">

                    <label for="txtYourEmail">From (email)</label>
                 <div class="input_bg">
                    <input type="email" name="txtYourEmail" id="txtYourEmail" value="" ng-model="share.txtYourEmail" required/>
                    <input type="hidden" name="hidden_share_title" id="hidden_share_title" ng-model="share.share_title" value="" />
                </div>

                <label for="txtRecipientName">To (name)</label>
                <div class="input_bg">
                	<input type="text" name="txtRecipientName" id="txtRecipientName" value="" ng-model="share.txtRecipientName" onChange="shareval(this)"/>
                </div>

                <label for="txtRecipientEmail">To (email)</label>
                <div class="input_bg">
                	<input type="email" name="txtRecipientEmail" id="txtRecipientEmail" value="" ng-model="share.txtRecipientEmail" />
                </div>

                <!--<label for="txtMessage">Message</label>-->
                <textarea name="txtMessage" id="txtMessage" ng-model="share.txtMessage" style="display:none;"></textarea>
                    <div class="g-recaptcha" data-sitekey="6Lc50RsUAAAAABQ0c6uRBVENM1eNm-_znefk8XK2"></div>
                    <div class="mail_sent_button"><a href="javascript:void(0);" ng-click="share_post_email();">Send</a></div>

            </div>
        </div>
    </div>
	<?php
}

function post_share_via_email_content_single() { ?>
    <div class="share_post_email_wrapper">
        <div class="share_post_email_content">
            <div class="share_mail_status" ng-bind-html="share_status"></div>
            <div class="share_post_email_fields">
                <label for="txtYourEmail">From (name)</label>
                <div class="input_bg">
                    <input type="email" name="txtYourEmail" id="txtYourEmail" value="" ng-model="share.txtYourEmail" />
                    <input type="hidden" name="hidden_share_title" id="hidden_share_title" ng-model="share.share_title" value="" />
                </div>

                 <label for="txtRecipientName">To (name)</label>
                <div class="input_bg">
                	<input type="text" name="txtRecipientName" id="txtRecipientName" value="" ng-model="share.txtRecipientName" required/>
                </div>

                <label for="txtRecipientEmail">To (email)</label>
                <div class="input_bg">
                	<input type="email" name="txtRecipientEmail" id="txtRecipientEmail" value="" ng-model="share.txtRecipientEmail" required/>
                </div>
                <div class="g-recaptcha" data-sitekey="6Lc50RsUAAAAABQ0c6uRBVENM1eNm-_znefk8XK2"></div>
               <!--<<label for="txtMessage">Message</label>-->
                <textarea name="txtMessage" id="txtMessage" ng-model="share.txtMessage" style="display:none;"></textarea>


                <div class="mail_sent_button"><a href="javascript:void(0);" ng-click="share_post_email();">Send</a></div>

            </div>
        </div>
    </div>
	<?php
}

function get_image($name = '', $width = '', $height = '', $alt = '') {
	if( $name ) {
		echo '<img src="'.get_stylesheet_directory_uri().'/assets/images/'.$name.' " width="'.$width.'" height="'.$height.'" alt="'.$alt.'" />';
	}
}

function header_section() { ?>
			<!--<div class="wiki_topbar_right header_logo_bottom">
                        <ul class="top_search">
                            <li class="search_box">
                                <div class="text_box">
                                   <?php /* get_search_form(); ?>
                                </div>
                            </li>
                        </ul>
                        <span class="random_list"><?php
                            if( is_front_page() ) { ?>
                                <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '', '');" class="random_icon_des">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" />
                                </a><?php
                            } else { ?>
                                <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '', '');" class="random_icon_des">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" />
                                </a><?php
                            } */?>

                        </span>
                    </div>-->
	<div class="home_head_content <?php if(get_the_title() == 'home'){ echo "full_width_header";} ?>">
        <div class="welcome_head_content">
            <div class="header_left_content">
                <div class="header_left_content_inner">
                    <!-- <span class="header_title_text">Welcome to Hyroglf</span> -->
                    <!-- <span class="header_content_text">
                        Free summaries (up to 80 words) and source ratings. Anyone can vote or write, just be honest and concise.
                    </span> -->
										<div class="wiki_topbar_right search_push_header_footer" > <!--style="display:none;" -->
                        <ul class="top_search">
                            <li class="search_box">
                                <div class="text_box">
                                   <?php get_search_form(); ?>
                                </div>
                            </li>
                        </ul>
                        <span class="random_list"><?php
                            if( is_front_page() ) { ?>
                                <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '');" class="random_icon_des">
                                    <!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" /> -->
																		<i class="fas fa-dice fa-2x"></i>
                                </a><?php
                            } else { ?>
                                <a href="javascript:void(0);" onclick="inner_page_cat_filter('', '', 'random', '');" class="random_icon_des">
                                    <!-- <img src="<?php echo get_template_directory_uri(); ?>/assets/images/random_icon.png" /> -->
																		<i class="fas fa-dice fa-2x"></i>
                                </a><?php
                            } ?>

                        </span>
                    </div>
                </div>
                    <!--  FORMER SEARCH BAR LOCATION-->

            </div>

            <div class="header_right_content">
                <span class="post_count">
                    <?php $count_posts = wp_count_posts('post'); ?>
                    <span class="number_of_count_value"><?php echo $count_posts->publish; ?></span>
                    <span class="number_of_count_text">Posts</span>
                </span>
            </div>

        </div>
    </div><?php
}

function scroll_load_cat_in_sidebar( ) {
	$cat_arr = array();

	// Category images
	$terms = get_terms('category');
	$cat_left_arr = array();
	if( $terms ) {
		foreach( $terms as $term ) {
			$cat_left_arr[] = $term;
		}
	}

	if( $cat_left_arr ) {

		$get_image = array();
		$get_image[] = 'category_#term_id#_post_category_image';
		//$get_image[] = 'category_#term_id#_cat_additional_image';

		foreach( $cat_left_arr as $term) {

			shuffle($get_image);
			$term_image = str_replace("#term_id#",$term->term_id,$get_image[0]);
			//$term_image_id = get_option('category_'.$term->term_id.'_cat_additional_image');
			$term_image_id = get_option($term_image);
			$send_image = wp_get_attachment_image_src ( $term_image_id, '' );
			$image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' );
			$slug = "'".$term->slug."'";
			if( $term->slug != 'other-info' ) {

				$empty = "''";
				$tax = "'category'";
				$term_slug = "'".$term->slug."'";
				$term_name = "'".$term->name."'";
				$image_src = "'".$send_image[0]."'";
				$page_of = "'index'";
				$cat_arr[] = '<div class="post_cat_img" id="cat_'.$term->slug.'"><a href="'.home_url('?term='.$term->slug).'" onclick="cat_post_filter_click('.$empty.', '.$tax.', '.$term_slug.', '.$term_name.', '.$image_src.', '.$page_of.'); return false;"><img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$term->name.'" /></a><span class="list_cat_name">'.$term->name.'</span></div>';

			}

		}
	}
	// Category images end

	// About us images
	$cat_arr[] = '<a href="javascript:void(0);" id="about_link" onclick="about_popup();"><p>About Hyroglf</p></a>'; //<img src="'.get_template_directory_uri().'/assets/images/about_us_icon.png" width="" height="">
	// About us images end

	// Advertisement images
	$args = array(
		'post_type' 		=> 'advertisement_logo',
		'post_status' 		=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'			=> 'desc',
		'order'				=> 'date'
		);
	$query = new WP_Query( $args );
	if ($query->have_posts()) {
		while ( $query->have_posts() ) : $query->the_post(); global $wpdb, $post;
			$post_meta = get_post_meta($post->ID);
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), '326_240_img'); // 162_85_img
			$link = '';
			if($post_meta['logo_link'][0]) {
				$link = $post_meta['logo_link'][0];
			} else {
				if( of_get_option('logo_url') ) {
					$link = of_get_option('logo_url');
				} else {
					$link = '#';
				}
			}
			//$cat_arr[] = '<a href = "'.$link.'" target = "_blank"><img src="'.$image[0].'" width="" height=""></a>';
		endwhile; wp_reset_query();
	}
	// Advertisement images end

	shuffle($cat_arr);

	return $cat_arr;

}

function scroll_load_post_category_left_sidebar( ) {
	$cat_arr = array();

	// Category images
	$terms = get_terms('category');
	$cat_left_arr = array();
	if( $terms ) {
		foreach( $terms as $term ) {
			$cat_left_arr[] = $term;
		}
	}

	if( $cat_left_arr ) {

		$get_image = array();
		$get_image[] = 'category_#term_id#_post_category_image';
		$get_image[] = 'category_#term_id#_cat_additional_image';

		foreach( $cat_left_arr as $term) {

			shuffle($get_image);
			$term_image = str_replace("#term_id#",$term->term_id,$get_image[0]);
			//$term_image_id = get_option('category_'.$term->term_id.'_cat_additional_image');
			$term_image_id = get_option($term_image);
			$send_image = wp_get_attachment_image_src ( $term_image_id, '' );
			$image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' );
			$slug = "'".$term->slug."'";
			if( $term->slug != 'other-info' ) {

				$tax = "'category'";
				$term_slug = "'".$term->slug."'";
				$term_name = "'".$term->name."'";
				$image_src = "'".$send_image[0]."'";
				$page_of = "'index'";
				$cat_arr[] = '<div class="post_cat_img" id="cat_'.$term->slug.'"><a href="'.home_url('?term='.$term->slug).'" ng-click="$event.preventDefault(); cat_post_filter_click($event, '.$tax.', '.$term_slug.', '.$term_name.', '.$image_src.', '.$page_of.');"><img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$term->name.'" /></a><span class="list_cat_name">'.$term->name.'</span></div>';

			}

		}
	}
	// Category images end

	// About us images
	$cat_arr[] = '<a href="javascript:void(0);" id="about_link" onclick="about_popup();"><img src="'.get_template_directory_uri().'/assets/images/about_us_icon.png" width="" height=""><p>About hyroglf</p></a>';
	// About us images end

	// Advertisement images
	$args = array(
		'post_type' 		=> 'advertisement_logo',
		'post_status' 		=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'			=> 'desc',
		'order'				=> 'date'
		);
	$query = new WP_Query( $args );
	if ($query->have_posts()) {
		while ( $query->have_posts() ) : $query->the_post(); global $wpdb, $post;
			$post_meta = get_post_meta($post->ID);
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), '326_240_img'); // 162_85_img
			$link = '';
			if($post_meta['logo_link'][0]) {
				$link = $post_meta['logo_link'][0];
			} else {
				if( of_get_option('logo_url') ) {
					$link = of_get_option('logo_url');
				} else {
					$link = '#';
				}
			}
			//$cat_arr[] = '<a href = "'.$link.'" target = "_blank"><img src="'.$image[0].'" width="" height=""></a>';
		endwhile; wp_reset_query();
	}
	// Advertisement images end

	shuffle($cat_arr);

	return $cat_arr;

}

function scroll_load_post_category_right_sidebar( ) {
	$cat_arr = array();

	// Advertisement images
	$args = array(
		'post_type' 		=> 'advertisement_logo',
		'post_status' 		=> 'publish',
		'posts_per_page' 	=> -1,
		'orderby'			=> 'desc',
		'order'				=> 'date'
		);
	$query = new WP_Query( $args );
	if ($query->have_posts()) {
		while ( $query->have_posts() ) : $query->the_post(); global $wpdb, $post;
			$post_meta = get_post_meta($post->ID);
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), '326_240_img'); // 162_85_img
			$link = '';
			if($post_meta['logo_link'][0]) {
				$link = $post_meta['logo_link'][0];
			} else {
				if( of_get_option('logo_url') ) {
					$link = of_get_option('logo_url');
				} else {
					$link = '#';
				}
			}
		//	$cat_arr[] = '<a href = "'.$link.'" target = "_blank"><img src="'.$image[0].'" width="" height=""></a>';
		endwhile; wp_reset_query();
	}
	// Advertisement images end

	// About us images
	$cat_arr[] = '<a href="javascript:void(0);" id="about_link" onclick="about_popup();"><img src="'.get_template_directory_uri().'/assets/images/about_us_icon.png" width="" height=""><p>About hyroglf</p></a>';
	// About us images end

	// Category images
	$terms = get_terms('category');
	$cat_left_arr = array();
	if( $terms ) {
		foreach( $terms as $term ) {
			$cat_left_arr[] = $term;
		}
	}

	if( $cat_left_arr ) {

		$get_image = array();
		$get_image[] = 'category_#term_id#_post_category_image';
		$get_image[] = 'category_#term_id#_cat_additional_image';

		foreach( $cat_left_arr as $term) {

			shuffle($get_image);
			$term_image = str_replace("#term_id#",$term->term_id,$get_image[0]);
			//$term_image_id = get_option('category_'.$term->term_id.'_cat_additional_image');
			$term_image_id = get_option($term_image);
			$send_image = wp_get_attachment_image_src ( $term_image_id, '' );
			$image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' );
			$slug = "'".$term->slug."'";
			if( $term->slug != 'other-info' ) {

				$tax = "'category'";
				$term_slug = "'".$term->slug."'";
				$term_name = "'".$term->name."'";
				$image_src = "'".$send_image[0]."'";
				$page_of = "'index'";
				$cat_arr[] = '<div class="post_cat_img" id="cat_'.$term->slug.'"><a href="'.home_url('?term='.$term->slug).'" ng-click="$event.preventDefault(); cat_post_filter_click($event, '.$tax.', '.$term_slug.', '.$term_name.', '.$image_src.', '.$page_of.');"><img src="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" alt="'.$term->name.'" /></a><span class="list_cat_name">'.$term->name.'</span></div>';

			}

		}
	}
	// Category images end

	shuffle($cat_arr);

	return $cat_arr;

}

function category_left_sidebar( $page_type ) {

	$class = '';
	if( $page_type == 'front_page' ) {
		$class = 'wiki_category';
	} else {
		$class = 'wiki_categories';
	}

	$terms = get_terms('category');
	$cat_left_arr = array();
	if( $terms ) {
			foreach( $terms as $term ) {
				if( $term->slug == 'economy' || $term->slug == 'politics' || $term->slug == 'technology' || $term->slug == 'sports' ) {
					$cat_left_arr[] = $term;
				}
			}
	} ?>
    <div class="wiki_left_section">
        <div class="<?php echo $class; ?>">
            <ul><?php
                if( $cat_left_arr ) {
                    foreach( $cat_left_arr as $term) {
                        $term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
						$send_image = wp_get_attachment_image_src ( $term_image_id, '' );
                        $image = wp_get_attachment_image_src ( $term_image_id, '326_240_img' ); ?>
                        <li class="pro_list_image <?php echo $term->slug; ?>" data-value="<?php echo $term->slug; ?>" >
                            <div class="post_cat_img">
                            	<a href="<?php echo home_url('?term='.$term->slug); ?>" id="cat_<?php echo $term->slug; ?>" ng-click="$event.preventDefault(); cat_post_filter_click($event, 'category', '<?php echo $term->slug; ?>', '<?php echo $term->name; ?>', '<?php echo $send_image[0]; ?>', 'index')">
                                	<img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php //echo $term->name; ?>" />
                                </a>
                            </div>
                        <span class="list_cat_name"><?php echo $term->name; ?></span>
                        </li><?php
                    }
                }
				/*if( scroll_load_post_category_left_sidebar( ) ) {
					$count_posts = wp_count_posts('post');
                    $count = round($count_posts->publish/3);
					for( $i = 0; $i <= $count; $i++ ) {
						foreach( scroll_load_post_category_left_sidebar( ) as $scroll_load_cat ) {
							echo '<li class="pro_list_image">'.$scroll_load_cat.'</li>';
						}
					}
				}*/ ?>
            </ul>
        </div>
    </div><?php
}

function category_right_sidebar( $page_type ) {

	$class = '';
	if( $page_type == 'front_page' ) {
		$class = 'wiki_category';
	} else {
		$class = 'wiki_categories';
	}

	$terms = get_terms('category');
	$cat_right_arr = array();
	if( $terms ) {
		foreach( $terms as $term ) {
			if( $term->slug == 'news' || $term->slug == 'pop-culture' || $term->slug == 'biography' ) {
				$cat_right_arr[] = $term;
			}
		}
	} ?>
    <?php
}

function hyroglf_get_post_query( $load = '', $post_type = '', $post_per_page = '', $pagenum = '', $taxonomy = '', $terms = '', $order = '', $order_by = '', $filter = '', $drop_down_filter = array(), $view = '', $rand = '', $current_user = '' ) {

	global $wpdb;

	$s = '';

	if( $load == 'random' ) {
		$order_by = 'rand()';
		$post_per_page = 1;
		$offset = 0;
	} else {
		$order_by = 'WP_post.post_date';
		$post_per_page = post_per_page();

		if( $pagenum > 1 ) {
			$post_per_page = post_per_page();
			$offset = $pagenum * $post_per_page;
			$offset = ($offset - post_per_page());
		} else {
			$offset = 0;
		}
	}

	$querystr='';
	$JOIN = '';
	$AND = '';

	if( $drop_down_filter['informative'] != '' || $drop_down_filter['bias'] != '' ) {
		if( $drop_down_filter['informative'] && $drop_down_filter['bias'] ) {

			if( $drop_down_filter['informative'] ) {
				$JOIN .= " INNER JOIN ".$wpdb->prefix."postmeta AS wp_meta_1 ON wp_meta_1.post_id = WP_post.ID";
				$AND = " AND wp_meta_1.meta_key = 'vote_for_".$drop_down_filter['informative']."'";
			}

			if( $drop_down_filter['bias'] ) {
				$JOIN .= " INNER JOIN ".$wpdb->prefix."postmeta AS wp_meta_2 ON wp_meta_2.post_id = WP_post.ID";
				$AND .= " AND wp_meta_2.meta_key = 'vote_for_".$drop_down_filter['bias']."'";
			}

		} else if( $drop_down_filter['informative'] && empty( $drop_down_filter['bias'] ) ) {

			$JOIN = " INNER JOIN ".$wpdb->prefix."postmeta AS wp_meta ON wp_meta.post_id = WP_post.ID";
			$AND = " AND wp_meta.meta_key = 'vote_for_".$drop_down_filter['informative']."'";

		} else if( $drop_down_filter['bias'] && empty( $drop_down_filter['informative'] ) ) {

			$JOIN = " INNER JOIN ".$wpdb->prefix."postmeta AS wp_meta ON wp_meta.post_id = WP_post.ID";
			$AND = " AND wp_meta.meta_key = 'vote_for_".$drop_down_filter['bias']."'";
		}
	}

	if( $load != 'search_post' && $terms && $terms != 'favorite_posts' && $terms != 'my_posts_and_votes' && $taxonomy != 'filter_by_author' ) {
		$querystr = "
			SELECT DISTINCT WP_post.ID, WP_post.post_title, WP_post.post_name, WP_post.post_content
			FROM $wpdb->posts AS WP_post
			LEFT JOIN $wpdb->term_relationships AS WP_term_relation
				ON(WP_post.ID = WP_term_relation.object_id)
			LEFT JOIN $wpdb->term_taxonomy AS WP_term_tax
				ON(WP_term_relation.term_taxonomy_id = WP_term_tax.term_taxonomy_id)
			LEFT JOIN $wpdb->terms AS WP_term
				ON(WP_term_tax.term_id = WP_term.term_id)
			$JOIN
			WHERE WP_term.slug = '$terms'
			AND WP_term_tax.taxonomy = '$taxonomy'
			AND WP_post.post_status = 'publish'
			AND WP_post.post_type = 'post'
			$AND
		";

	} else if( $terms == 'favorite_posts' && $current_user->ID ) {
		// Favorite posts
		$user_meta = get_user_meta($current_user->ID);
		$post_id_arr = unserialize($user_meta['wpfp_favorites'][0]);
		$post__in = array();
		$post_in= array();
		if( is_array( $post_id_arr ) && !empty( $post_id_arr )) {
			foreach( $post_id_arr as $post_id ) {
				if( $post_id ) {
					$post_in[] = $post_id;
				}
			}
			$post__in = implode(',',$post_in);
		}

		$querystr = "
			SELECT DISTINCT WP_post.ID, WP_post.post_title, WP_post.post_name, WP_post.post_content FROM $wpdb->posts AS WP_post
			$JOIN
			WHERE WP_post.post_type = 'post'
			AND WP_post.post_status = 'publish'
			AND WP_post.ID IN($post__in)
			";

	} else {
		$querystr = "SELECT DISTINCT WP_post.ID, WP_post.post_title, WP_post.post_name, WP_post.post_content FROM $wpdb->posts AS WP_post ";

		if( $load == 'search_post' ) {
			// Search post load by ajax filter
			$s = $terms;
			if( $s ) {
				$AND = " AND WP_post.post_title LIKE '%$s%' OR WP_post.post_content LIKE '%$s%'";
			}
			if($current_user->ID) {
				set_user_search_key( $current_user->ID, $s );
			}
		}

		if( $load == 'most_viewed' && $view == '' || $view == 'today' ) {

			$JOIN = " INNER JOIN ".$wpdb->prefix."hyroglf_analytics AS HA ON HA.post_id = WP_post.ID";
			$AND = "AND date > DATE_SUB(NOW(), INTERVAL 1 DAY)";

		} else if( $load == 'most_viewed' && $view == 'this_week' ) {

			$JOIN = " INNER JOIN ".$wpdb->prefix."hyroglf_analytics AS HA ON HA.post_id = WP_post.ID";
			$AND = "AND date > DATE_SUB(NOW(), INTERVAL 1 WEEK)";

		} else if( $load == 'most_viewed' && $view == 'this_month' ) {

			$JOIN = " INNER JOIN ".$wpdb->prefix."hyroglf_analytics AS HA ON HA.post_id = WP_post.ID";
			$AND = "AND date > DATE_SUB(NOW(), INTERVAL 1 MONTH)";

		}

		if( $terms == 'my_posts_and_votes' && $current_user->ID ) {

			$type = 'OR';
			if( $drop_down_filter['informative'] || $drop_down_filter['bias'] ) {
				$type = 'AND';
			}

			// get current user voted post id form current user id
			$AND .= "
				AND WP_post.post_author =".$current_user->ID."
				$type WP_post.ID in (
							SELECT DISTINCT article_id
							FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles
							WHERE user_id =".$current_user->ID.
							")
				";

		}

		if( $taxonomy == 'filter_by_author' ) {
			//$AND .= " AND WP_post.post_author =".$terms;

			$type = 'OR';
			if( $drop_down_filter['informative'] || $drop_down_filter['bias'] ) {
				$type = 'AND';
			}

			// get current user voted post id form current user id
			$AND .= "
				AND WP_post.post_author =".$terms."
				$type WP_post.ID in (
							SELECT DISTINCT article_id
							FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles
							WHERE user_id =".$terms.
							")
				";

		}

		$querystr .= "
			$JOIN
			WHERE WP_post.post_type = 'post'
			AND WP_post.post_status = 'publish'
			$AND
		";

	}

	$result = $wpdb->get_results($querystr);
	//echo $result;
	//wp_die();
	$post_count = count($result);

	$querystr .="ORDER BY $order_by $order LIMIT $offset, $post_per_page";

	//echo $querystr;

	$result = $wpdb->get_results($querystr);

	return array('query' => $querystr, 'post_count' => $post_count, 'data' => $result);

}

function get_rand_post() {
	global $wpdb;
	$querystr = "SELECT ID FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY rand() DESC ";
	$result = $wpdb->get_var($querystr);
	return $result;
}

if ( is_user_logged_in() && isset( $_GET['DeleteMyAccount'] ) && $_GET['DeleteMyAccount'] == 'delete_account' ) {
	add_action( 'init', 'remove_logged_in_user' );
}

function remove_logged_in_user() {

	require_once(ABSPATH.'wp-admin/includes/user.php' );
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$user_obj = get_userdata( $user_id );
	$email = $user_obj->user_email;

	$result = '';
	$user_account_status = get_user_meta( $user_id, 'user_account_status', true );
	if( $user_account_status ) {


		$result = update_user_meta( $user_id, 'user_account_status', 'inactive' );
		if( $result ) {
			wp_logout();
			//wp_redirect( home_url('/sign-up?delete_account=yes') );
		}
	} else {
		$result = add_user_meta( $user_id, 'user_account_status', 'inactive' );
		if( $result ) {
			wp_logout();
			//wp_redirect( home_url('/sign-up?delete_account=yes') );
		}
	}?>
    	<script type="text/javascript">
				window.location.href="<?php echo home_url('/login'); ?>";
		</script>
	<?php

	//$result = wp_delete_user( $user_id );
	if( $result ) {?>
		<?php
		/*$to = $email;
		$subject = 'Hyroglf deleted confirmation';
		$message = 'Hi '.$user_obj->user_login.',<br/>';
		$message .= '<p>You are being deleted, Your account at ' .get_bloginfo("name") . ' is being deleted right now.</p><br/>';
		$message .= 'Regards,<br/>';
		$message .=  get_option( 'blogname' );
		$headers = array('Content-Type: text/html; charset=UTF-8');
 		add_filter( 'wp_mail_content_type', 'set_html_content_type_contributor' );*/

		//if( wp_mail( $email, $subject, $message, $headers ) ) { ?>
			<!--<script type="text/javascript">
				window.location.href="<?php //echo home_url(); ?>";
			</script>-->
			<?php
		//}
		//remove_filter( 'wp_mail_content_type', 'set_html_content_type_contributor' );
	}
}


add_action( 'user_register', 'update_user_status_as_inactive', 10, 1 );
function update_user_status_as_inactive( $user_id ) {


    if ( isset( $user_id ) ) {

		//print_r($_POST);
		//die();

        update_user_meta( $user_id, 'user_account_status', 'inactive' );
		update_user_meta( $user_id, 'user_temp_pass', $_POST['new_user_password'] );

		$user_info = get_userdata($user_id);
		$username = $user_info->data->user_login;
		$user_email = $user_info->data->user_email;
		$status = 'inactive';

		send_notification( $user_id, $username, $user_email, $status );

	}
}

if(!is_admin()) {
	add_action( 'wp_authenticate', 'wp_authenticate_by_email' );
}
// user name is passed in by reference
function wp_authenticate_by_email( &$username ) {
	$user_id='';
    $user_by_email = get_user_by( 'email', $username );
	if(is_array($user_by_email) && isset($user_by_email))
		$user_id = $user_by_email->ID;

	 $user_by_username = get_userdatabylogin( $username );
	 if(is_array($user_by_username) && isset($user_by_username))
		$user_id = $user_by_username->ID;

	if($user_id) {
		$result = get_user_meta( $user_id, 'user_account_status', true );
		if($result=="inactive") {
			add_filter('login_errors','login_error_message');
		}
	}
}


// user name is passed in by reference
add_action( 'wp_ajax_resend_activation_mail', 'wp_resend_activation_mail' );
add_action( 'wp_ajax_nopriv_resend_activation_mail', 'wp_resend_activation_mail' );

function wp_resend_activation_mail() {

	if($_POST['user_log'] && $_POST['user_eml'] ){
		$user_login = $_POST['user_log'];
		$user_email = $_POST['user_eml'];
	} else{
		echo $user_login = $_POST['user_login'];
		echo $user_email = $_POST['user_email'];
	}

	$user_id='';
    $user_by_email = get_user_by( 'email', $user_email );
	if(isset($user_by_email))
		$user_id = $user_by_email->ID;

	 $user_by_username = get_userdatabylogin( $user_login );
	 if(isset($user_by_username))
		$user_id = $user_by_username->ID;
	if($_POST['user_log'] && $_POST['user_eml'] ){
		$user_login = "'".$user_login."'";
		$user_email = "'".$user_email."'";
        echo 'Registration confirmation resent! Click <a href="javascript:void(0);" onclick="fnResendActivationMail2('.$user_login.','.$user_email.')">here</a> to resend.{@';
	} else{
		echo 'Registration confirmation resent! Click <a href="javascript:void(0);" onclick="fnResendActivationMail2('.$user_login.','.$user_email.')">here</a> to resend.{@';
	}
	update_user_status_as_inactive( $user_id );
}



function login_error_message($error){
	$error = "Check your e-mail to confirm registration";
    return $error;
}

function get_hyroglf_random_post_for_mobile() {
	$args = array(
				'post_type' 		=> 'post',
				'post_status' 		=> 'publish',
				'posts_per_page' 	=> 1,
				'orderby'			=> 'rand',
				'order'				=> 'desc'
			);
	$loop = new WP_Query( $args );
	$total_pages = $loop->max_num_pages;
	$total_posts = $loop->found_posts;

	$return = '';
	if ($loop->have_posts()) {
		while ( $loop->have_posts() ) : $loop->the_post(); global $wpdb, $post;
			$return = get_the_permalink($post->ID);
		endwhile;
	}
	return $return;
}

function _remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

if( is_admin() ) {
	add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
	add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

	add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
	add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

}

function my_show_extra_profile_fields( $user ) {
	//echo '<pre>'; print_r($user);
	$user_account_status = get_user_meta( $user->ID, 'user_account_status', true );
	if( $user->roles[0] == 'contributor' ) { ?>
        <table class="form-table">
            <tr>
                <th><label for="active_deactive">Activate / Deactivate</label></th>
                <td>
                	<select name="user_account_status" id="user_account_status">
                    	<option value="">Select</option>
                        <option value="active" <?php echo ($user_account_status == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($user_account_status == 'inactive') ? 'selected' : ''; ?>>Deactive</option>
                    </select>
                </td>
            </tr>
        </table><?php
	}
}

function my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	if( $_POST['user_account_status'] ) {
		$status = $_POST['user_account_status'];
	} else {
		$status = 'inactive';
	}
	update_usermeta( $user_id, 'user_account_status', $status );
}

function popup_category_image_content() {
	// Category modal popup
    $all_terms = get_terms('category');
	if($all_terms) { ?>
    <div class="post_cat_image_modal_wrapper post_cat_image_modal_desktop_wrapper">
        <div class="post_cat_image_modal">
            <a class="btn-close post_cat_trigger">X</a>
           	<?php
           	wp_nav_menu(
				array(
					'theme_location' => 'popup_category',
					'container' 	=> '',
					'menu_id' 		=> '',
					'menu_class'	=> '',
					'walker'	 	=> new popup_category_nav
					)
			);
			wp_nav_menu(
				array(
					'theme_location' => 'popup_category_2',
					'container' 	=> '',
					'menu_id' 		=> '',
					'menu_class'	=> '',
					'walker'	 	=> new popup_category_nav
					)
			);
			wp_nav_menu(
				array(
					'theme_location' => 'popup_category_3',
					'container' 	=> '',
					'menu_id' 		=> '',
					'menu_class'	=> '',
					'walker'	 	=> new popup_category_nav
					)
			);
		  ?>
        </div>
    </div><?php
	}
}
function popup_category_image_profile_content() {
	?>
    <div class="post_cat_image_modal_wrapper post_cat_image_modal_desktop_wrapper">
        <div class="post_cat_image_modal">
        	<span><a href="javascript:void(0);" id="myprofile_field">My Profile</a></span>
            <span><a href="javascript:void(0);" id="myprofile_posts">My Posts</a></span>
            <span><a href="javascript:void(0);" id="myprofile_votes" ng-click="myprofile_votes()">My Votes</a></span>
        </div>
    </div><?php
}

function get_post_rating_options( $post_id = '' ) {

	global $wpdb;

	$return = array();

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$postid = $post_id;
	//get option count for voting
	 $option_id_count = $wpdb->get_results("SELECT option_id FROM ".$wpdb->prefix."hyroglf_question_option");
	 $option_voting_post = array();
	 $options_arr = array();
	 if($option_id_count){
		 foreach($option_id_count as $option) {

			$option_id_array = $option->option_id;

			$option_voting_post[] = $wpdb->get_results("SELECT count(ques_option_id) as count_id FROM ".$wpdb->prefix."hyroglf_users_voting WHERE ques_option_id=".$option->option_id." AND user_article_id=".$postid);

			$options_arr[] = $wpdb->get_results("SELECT
									count(HUV.ques_option_id) as count_id,
									HUV.question_id,
									HQ.questions,
									HUV.ques_option_id,
									HQO.ques_option
									FROM ".$wpdb->prefix."hyroglf_users_voting AS HUV
									INNER join ".$wpdb->prefix."hyroglf_questions AS HQ ON HUV.question_id = HQ.question_id
									INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
									WHERE HUV.ques_option_id='$option_id_array' AND HUV.user_article_id='$postid'");
		 }

	 }

	$infermative_arr = array();
	$bias_arr = array();
	foreach( $options_arr as $options ) {
		if( !empty( $options ) ) {
			foreach( $options as $option ) {
				if( $option->questions == 'Informative/Understandable?' ) {
					$infermative_arr[] = array(
											"count" 			=> $option->count_id,
											"question_id"		=> $option->question_id,
											"questions" 		=> $option->questions,
											"ques_option_id" 	=> $option->ques_option_id,
											"ques_option" 		=> $option->ques_option,
											);

				} elseif( $option->questions == 'Bias?' ) {
					$bias_arr[] = array(
											"count" 			=> $option->count_id,
											"question_id"		=> $option->question_id,
											"questions" 		=> $option->questions,
											"ques_option_id" 	=> $option->ques_option_id,
											"ques_option" 		=> $option->ques_option,
											);
				}
			}
		}
	}

	if( is_array( $infermative_arr ) ) {
		$sort_infor = array();
		foreach($infermative_arr as $c=>$key) {
			$sort_infor[] = $key['count'];
		}
		if( is_array( $sort_infor ) ) {
			array_multisort($sort_infor, SORT_DESC, SORT_STRING,$infermative_arr);
		}
	}
	if( is_array( $bias_arr ) ) {
		$sort_bias = array();
		foreach($bias_arr as $key=>$value) {
			$sort_bias[] = $value['count'];
		}
		if( is_array( $sort_bias ) ) {
			array_multisort($sort_bias, SORT_DESC, SORT_STRING, $bias_arr);
		}
	}

	if($post->ID && $current_user->ID){

		 $user_id = $current_user->ID;
		 $count_user = $wpdb->get_var("
								SELECT count(user_id) AS user_count
								FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles
								WHERE article_id = ".$postid." AND user_id = ".$user_id."
								");

		$user_option_infermative = $wpdb->get_var("
								SELECT *
								FROM ".$wpdb->prefix."hyroglf_questions AS HQ
								INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HQ.question_id = HQO.question_id_fk
								INNER JOIN ".$wpdb->prefix."hyroglf_users_voting AS HUV ON HQO.option_id = HUV.ques_option_id
								WHERE HQ.questions = 'Informative/Understandable?'
								AND HUV.user_id = ".$user_id."
								AND HUV.user_article_id = ".$postid
								);

		$infermative_count = '';
		if( !empty( $user_option_infermative ) ) {
			$infermative_count = $user_option_infermative;
		}

		$user_option_bias = $wpdb->get_var("
								SELECT *
								FROM ".$wpdb->prefix."hyroglf_questions AS HQ
								INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HQ.question_id = HQO.question_id_fk
								INNER JOIN ".$wpdb->prefix."hyroglf_users_voting AS HUV ON HQO.option_id = HUV.ques_option_id
								WHERE HQ.questions = 'Bias?'
								AND HUV.user_id = ".$user_id."
								AND HUV.user_article_id = ".$postid
								);

		$bias_count = '';
		if( !empty( $user_option_bias ) ) {
			$bias_count = $user_option_bias;
		}

	 }


	 if( $option_voting_post[0][0]->count_id || $option_voting_post[1][0]->count_id || $option_voting_post[2][0]->count_id || $option_voting_post[3][0]->count_id || $option_voting_post[4][0]->count_id || $option_voting_post[5][0]->count_id ) {

		 if(!empty( $infermative_arr ) && is_array( $infermative_arr )) {

			 //$informative_high_vote = array();
			if($infermative_arr[0]['count']){
				if($infermative_arr[0]['ques_option']){
					//$informative_high_vote[] = ucfirst( str_replace('_', ' ', $infermative_arr[0]['ques_option'] ) );
					$informative_high_vote[] = str_replace('_', ' ', $infermative_arr[0]['ques_option'] );
				}
			}
			if($infermative_arr[1]['count'] == $infermative_arr[0]['count']){
				if($infermative_arr[1]['ques_option']){
					//$informative_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $infermative_arr[1]['ques_option'] ) );
					$informative_high_vote[] = '/'.str_replace('_', ' ', $infermative_arr[1]['ques_option'] );
				}
			}
			if($infermative_arr[2]['count'] == $infermative_arr[0]['count'] && $infermative_arr[2]['count'] == $infermative_arr[1]['count']){
				if($infermative_arr[2]['ques_option']){
					//$informative_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $infermative_arr[2]['ques_option'] ) );
					$informative_high_vote[] = '/'.str_replace('_', ' ', $infermative_arr[2]['ques_option'] );
				}
			}

			$total = ( ( $option_voting_post[0][0]->count_id ) + ( $option_voting_post[1][0]->count_id ) + ( $option_voting_post[2][0]->count_id ) );
			$very = '';
			$somewhat = '';
			$not_really = '';

			$count_very = '';
			$count_somewhat = '';
			$count_not_really = '';
			if( $option_voting_post[0][0] ) {
				$count = $option_voting_post[0][0]->count_id/$total * 100;
				$count_very = round( $count ).'%';
				$very = $option_voting_post[0][0]->count_id > 1  ? $option_voting_post[0][0]->count_id.' votes' : $option_voting_post[0][0]->count_id.' vote';
			}

			 if( $option_voting_post[1][0] ) {
					$count = $option_voting_post[1][0]->count_id/$total * 100;
					$count_somewhat = round( $count ).'%';
					$somewhat = $option_voting_post[1][0]->count_id > 1  ? $option_voting_post[1][0]->count_id.' votes' : $option_voting_post[1][0]->count_id.' vote';
			 }

			 if( $option_voting_post[2][0] ) {
					$count = $option_voting_post[2][0]->count_id/$total * 100;
					$count_not_really = round( $count ).'%';
					$not_really = $option_voting_post[2][0]->count_id > 1 ? $option_voting_post[2][0]->count_id.' votes' : $option_voting_post[2][0]->count_id.' vote';
			 }

			$informative_arr = array(
								'very'  		=> array('count'=> $count_very, 'vote' => $very),
								'somewhat'  	=> array('count'=> $count_somewhat, 'vote' => $somewhat),
								'not_really'  	=> array('count'=> $count_not_really, 'vote' => $not_really),
							);
		 }

		//$bias_high_vote = array();
		if($bias_arr[0]['count']){
			if($bias_arr[0]['ques_option']){
				//$bias_high_vote[] = ucfirst( str_replace('_', ' ', $bias_arr[0]['ques_option'] ) );
				$bias_high_vote[] = str_replace('_', ' ', $bias_arr[0]['ques_option'] );
			}
		}
		if($bias_arr[1]['count'] == $bias_arr[0]['count']){
			if($bias_arr[1]['ques_option']){
				//$bias_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $bias_arr[1]['ques_option'] ) );
				$bias_high_vote[] = '/'.str_replace('_', ' ', $bias_arr[1]['ques_option'] );
			}
		}
		if($bias_arr[2]['count'] == $bias_arr[0]['count'] && $bias_arr[2]['count'] == $bias_arr[1]['count']){
			if($bias_arr[2]['ques_option']){
				//$bias_high_vote[] = '/'.ucfirst( str_replace('_', ' ', $bias_arr[2]['ques_option'] ) );
				$bias_high_vote[] = '/'.str_replace('_', ' ', $bias_arr[2]['ques_option'] );
			}
		}

		if(!empty( $bias_arr ) && is_array( $bias_arr )) {

			$liberal = '';
			$neutral = '';
			$conservative = '';

			$count_liberal = '';
			$count_neutral = '';
			$count_conservative = '';

			$total = ( ( $option_voting_post[3][0]->count_id ) + ( $option_voting_post[4][0]->count_id ) + ( $option_voting_post[5][0]->count_id ) );
			if( $option_voting_post[4][0] ) {
				$count = $option_voting_post[4][0]->count_id/$total * 100;
				$count_liberal = round( $count ).'%';
				$liberal = $option_voting_post[4][0]->count_id > 1 ? $option_voting_post[4][0]->count_id.' votes' : $option_voting_post[4][0]->count_id.' vote';
			 }

			 if( $option_voting_post[3][0] ) {
				$count = $option_voting_post[3][0]->count_id/$total * 100;
				$count_neutral = round( $count ).'%';
				$neutral = $option_voting_post[3][0]->count_id > 1 ? $option_voting_post[3][0]->count_id.' votes' : $option_voting_post[3][0]->count_id.' vote';
			}

			if( $option_voting_post[5][0] ) {
				$count = $option_voting_post[5][0]->count_id/$total * 100;
				$count_conservative = round( $count ).'%';
				$conservative = $option_voting_post[5][0]->count_id > 1  ? $option_voting_post[5][0]->count_id.' votes' : $option_voting_post[5][0]->count_id.' vote';
			}

			$bias_arr = array(
				'liberal'  		=> array('count'=> $count_liberal, 'vote' => $liberal),
				'neutral'  		=> array('count'=> $count_neutral, 'vote' => $neutral),
				'conservative'  => array('count'=> $count_conservative, 'vote' => $conservative),
			);

		 }

	 }

	 $user_vote_arr = $wpdb->get_results($wpdb->prepare("
											SELECT HQO.ques_option
											FROM ".$wpdb->prefix."hyroglf_users_voting AS HUV
											INNER JOIN ".$wpdb->prefix."hyroglf_question_option AS HQO ON HUV.ques_option_id = HQO.option_id
											WHERE HUV.user_article_id = %d
											AND HUV.user_id = %d"
											, $postid, $user_id ));

	 $return = array(
	 			'post_rating_options_informative_count' => $informative_arr,
				'post_rating_options_bias_count' 		=> $bias_arr,
				'informative_high_vote'					=> $informative_high_vote,
				'bias_high_vote'						=> $bias_high_vote,
				'user_vote_arr'							=> $user_vote_arr
			);

	 return $return;

}
function fn_get_edit_post_content(){
	global $post,$wp_query ;
	$data = get_post_meta($post->ID);
		$post = $wp_query->get_queried_object();
		if((isset($_GET['task']) && !is_user_logged_in()) || (!is_user_logged_in() && $post->post_name == 'my-posts')){
				$url = home_url();
				if( isset( $_GET['redirect_to'] ) ) {
					$url = urldecode($_GET['redirect_to']);
				} else {
					$url = home_url('/login');
				} ?>
				<script>
					window.location.href= '<?php echo $url; ?>';
				</script><?php
		}?>
        <div class="post_single_filter_text">
            <div class="post_single_view_content_head">
                <div class="post_single_heade_edit_action">
                    <div class="post_single_cat_view_popup">
                        <a class="filter_cat_popup_action" id ="filter_cat_popup" href="javascript:void(0);" onclick="view_all_list_cat('all');">
                            <i class="fa fa-plus fa-2x">
                        </a>
                         <?php popup_category_image_content(); ?>
                    </div>
                </div>
            </div>
        </div><?php
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
                <?php
                    if(  isset($_GET['task']) && $_GET['task'] == 'new' ) {
                        $text = "Editor";
                    } else if(  isset($_GET['task']) && $_GET['task'] == 'edit' ) {
                        $text = "Editor";
                    }
                    if( isset($_GET['task']) && $_GET['task'] == 'edit' || $_GET['task'] == 'new') { ?>
                        <div class="post_new_and_edit_section" >
                            <span class="single_page_pencil_icon_left"><i class="fas fa-edit fa-3x"></i></span>
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
                                        <?php edit_post_link('<i class="fas fa-edit fa-3x"></i>',''); ?>
                                    </li><?php
                                    }
                                    if( isset($_GET['task'] ) ) { ?>
                                    <li class="single_close_icon"><?php
                                    if( isset( $_GET['postid'] ) ) { ?>
                                        <a href="<?php echo get_the_permalink($_GET['postid']); ?>"><i class="fas fa-close fa-3x"></i></a><?php
                                    } else { ?>
                                        <a href="javascript:void(0);"><i class="fas fa-close fa-3x"></i></a><?php
                                    } ?>
                                    </li><?php
                                    } else { ?>
                                        <li class="single_close_icon">
                                        <a href="javascript:void(0);"><i class="fas fa-close fa-3x"></i></a>
                                    </li><?php
                                    } ?>
                                </ul>
                            </div><?php
                        } ?>
                        <div class="entry post_single_page">
                        	<div class="error_msg_post"></div>
                            <?php the_content(); ?>
                        </div>
                    </div><?php
		}
}


/**
 * WordPress function for redirecting users on login based on user role
 */

function user_login_redirect( $url, $request, $user ){
	if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) ) {
		if( $user->has_cap( 'administrator' ) ) {
			$url = admin_url();
		} elseif( $user->has_cap( 'contributor' ) ) {
			$url = home_url('/');
		} else {
			$url = home_url();
		}
}
return $url;
}
add_filter( 'login_redirect', 'user_login_redirect', 10, 3 );


function wmpudev_enqueue_icon_stylesheet() {
	wp_register_style( 'ico-hyroglf', 'assets/fonts/ico-hyroglf.css' );
	wp_enqueue_style( 'ico-hyroglf');
}
add_action( 'wp_enqueue_scripts', 'wmpudev_enqueue_icon_stylesheet' );
