<?php
####################### Dashboard actions ##########################################
/**
 * Add a widget to the dashboard.
 */

function user_list_custom_fn() {
	$args = array(
		'blog_id'      => $GLOBALS['blog_id'],
		'role'         => '',
		'role__in'     => array('administrator','contributor'),
		'role__not_in' => array('user'),
		'meta_key'     => '',
		'meta_value'   => '',
		'meta_compare' => '',
		'meta_query'   => array(),
		'date_query'   => array(),        
		'include'      => array(),
		'exclude'      => array(),
		'orderby'      => 'login',
		'order'        => 'ASC',
		'offset'       => '',
		'search'       => '',
		'number'       => '',
		'count_total'  => false,
		'fields'       => 'all',
		'who'          => ''
	 ); 
	$users = get_users( $args );
	return $users;	
}

add_action( 'wp_ajax_load_post_to_dashboard', 'load_post_to_dashboard' );
add_action( 'wp_ajax_nopriv_load_post_to_dashboard', 'load_post_to_dashboard' );
function load_post_to_dashboard( $filter = '', $user_id = '', $term = '', $informative = '', $bias = '' ) {
	global $wpdb;
	if( isset( $_POST['filter'] ) ) {
		$filter = $_POST['filter'];
	}
	if( isset( $_POST['user_id'] ) ) {
		$user_id = $_POST['user_id'];
	}
	if( isset( $_POST['term'] ) ) {
		$term = $_POST['term'];
	}
	if( isset( $_POST['informative'] ) ) {
		$informative = $_POST['informative'];
	}
	if( isset( $_POST['bias'] ) ) {
		$bias = $_POST['bias'];
	}
	
	if( $filter == 'vote_by_catogry' ) {
		$args = array(
				'post_type'			=> 'post',
				'post_status'		=> 'publish',
				'posts_per_page'	=> 5,
				'orderby'			=> 'date',
				'order'				=> 'desc',
				'tax_query' 		=> array(
										array(
											'taxonomy'	=> 'category',
											'field'		=> 'slug',
											'terms'		=> array($term)
										)
									)
			);
	} else if( $filter == 'vote_by_user_name' ) {
		if( $user_id && $informative == '' && $bias == '' ) {
			$post_arr = $wpdb->get_results("SELECT article_id FROM ".$wpdb->prefix."hyroglf_users_voting_for_articles WHERE user_id =".$user_id);
			if( $post_arr ) {
				$post_id_arr = array();
				foreach( $post_arr as $posts ) {
					$post_id_arr[] = $posts->article_id;
				}
			}			
		}
		 
		$post_id_arr = array_unique($post_id_arr);
		
		if( is_array( $post_id_arr ) && !empty( $post_id_arr ) ) {
			
			$out = array();
			foreach ($post_id_arr as $post_id) {
				array_push($out, $post_id);
			}
			
			$args = array(
					'post_type'			=> 'post',
					'post__in'			=> $post_id_arr,
					'post_status'		=> 'publish',
					'posts_per_page'	=> '5',
					'orderby'			=> 'date',
					'order'				=> 'desc',
				);
		}
		 
	} else {
		if( $user_id ) {
			$args = array(
						'post_type'			=> 'post',
						'post_status'		=> 'publish',
						'posts_per_page'	=> 5,
						'orderby'			=> 'date',
						'order'				=> 'desc',
						'author' 			=> $user_id
					);
		} else if( $term ) {
			$args = array(
						'post_type'			=> 'post',
						'post_status'		=> 'publish',
						'posts_per_page'	=> 5,
						'orderby'			=> 'date',
						'order'				=> 'desc',
						'tax_query' 		=> array(
												array(
													'taxonomy'	=> 'category',
													'field'		=> 'slug',
													'terms'		=> array($term)
												)
											)
					);
		}
	}
	
	?>
    <div id="published-posts" class="activity-block"><?php
		$the_query = new WP_Query( $args );
		$total_posts = $the_query->found_posts;
		if( $the_query->have_posts() ) {
			if( $filter == 'vote_by_category' ) {
				$option_voting_post = array();
				while ( $the_query->have_posts() ) : $the_query->the_post(); global $post;
					$post_id = $post->ID;
					if( $filter == 'vote_by_category' ) {
						$option_id_count = $wpdb->get_results("SELECT option_id FROM ".$wpdb->prefix."hyroglf_question_option");
						if($option_id_count){
							foreach($option_id_count as $option) {
								$option_id = $option->option_id;
								$option_key = $wpdb->get_var("SELECT ques_option FROM ".$wpdb->prefix."hyroglf_question_option WHERE option_id = '$option_id'");
								$option_voting_post[$option_key][] = $wpdb->get_var("
																SELECT COUNT(ques_option_id) AS count_id
																FROM ".$wpdb->prefix."hyroglf_users_voting
																WHERE ques_option_id = '$option_id'
																AND user_article_id='$post_id'
																");
							}
						}
					}
				endwhile; wp_reset_query();
				
				$very_sum = 0;
				$somewhat_sum = 0;
				$not_really_sum = 0;
				
				$liberal_sum = 0;
				$neutral_sum = 0;
				$conservative_sum = 0;
				
				foreach( $option_voting_post as $key => $vote_arr ) {
					foreach( $vote_arr as $vote ) {
						if( $key == 'very' ) {
							$very_sum+= $vote;
						} else if( $key == 'somewhat' ) {
							$somewhat_sum+= $vote;
						} else if( $key == 'not_really' ) {
							$not_really_sum+= $vote;
						} else if( $key == 'liberal' ) {
							$liberal_sum+= $vote;
						} else if( $key == 'neutral' ) {
							$neutral_sum+= $vote;
						} else if( $key == 'conservative' ) {
							$conservative_sum+= $vote;
						}
						
					}
				} ?>
                <div class="vote_by_cat_infor_bias_tab_section">
                    <div class="vote_by_cat_infor_tab">
                        <div class="vote_by_cat_infor_content">
                            <span>Informative</span>
                            <ul>
                                <li>
                                    <label>very</label>
                                    <?php
                                    if( $very_sum ){ ?>
                                        <span> - <?php echo ( $very_sum ) ? $very_sum : 0; ?></span>
                                    <?php
                                    } ?>
                                </li>
                                <li>
                                    <label>somewhat</label>
                                    <?php
                                    if( $somewhat_sum ){ ?>
                                        <span> - <?php echo ( $somewhat_sum ) ? $somewhat_sum : 0; ?></span>
                                    <?php
                                    } ?>
                                </li>
                                <li>
                                    <label>not really</label>
                                    <?php
                                    if( $not_really_sum ){ ?>
                                        <span> - <?php echo ( $not_really_sum ) ? $not_really_sum : 0; ?></span>
                                    <?php
                                    } ?>
                                </li>
                             </ul>
                        </div>
                    </div>
                    <div class="vote_by_cat_infor_tab">
                        <div class="vote_by_cat_infor_content">
                            <span>Bias</span>
                            <ul>
                                <li>
                                    <label>liberal</label>
                                    <?php
                                    if( $liberal_sum ){ ?>
                                        <span> - <?php echo ( $liberal_sum ) ? $liberal_sum : 0; ?></span>
                                    <?php
                                    } ?>
                                </li>
                                <li>
                                    <label>netural</label>
                                    <?php
                                    if( $neutral_sum ){ ?>
                                        <span> - <?php echo ( $neutral_sum ) ? $neutral_sum : 0; ?></span>
                                    <?php
                                    } ?>
                                </li>
                                <li>
                                    <label>conservative</label>
                                    <?php
                                    if( $conservative_sum ){ ?>
                                        <span> - <?php echo ( $conservative_sum ) ? $conservative_sum : 0; ?></span>
                                    <?php

                                    } ?>
                                </li>
                             </ul>
                        </div>
                    </div>
                </div>
				<?php
			} ?>
			<ul><?php
				while ( $the_query->have_posts() ) : $the_query->the_post(); global $post;
					post_with_time( $filter, $post->ID, $user_id );
				endwhile; wp_reset_query(); ?>
			</ul><?php
			} else {
				echo '<ul><li><h3>Nothing found!</h3></li></ul>';
			}
		?>
	</div><?php
    if( $total_posts > 5 ) { ?>
        <div class="post_view_more"><?php
		/*if( $_POST['filter'] == 'vote_by_user_name' || $_POST['filter'] == 'vote_by_user_name_infor_bias' ) { ?>
			<a href="<?php echo admin_url('/edit.php?post_type=post&author='.$user_id.'&post_ids='.implode(',',$out)); ?>">See more post</a><?php
		} else*/ if( $user_id ) { ?>
			<a href="<?php echo admin_url('/edit.php?post_type=post&author='.$user_id); ?>">See more post</a><?php
		} else if( $term ) { ?>
        	<a href="<?php echo admin_url('/edit.php?category_name='.$term); ?>">See more post</a><?php			
		} ?>
        </div><?php
	}
	
	if( isset( $_POST['filter'] ) || isset( $_POST['user_id'] ) || isset( $_POST['term'] ) || isset( $_POST['informative'] ) || isset( $_POST['bias'] ) ) {
		wp_die();
	}
}

function get_post_time_custom_fn( $post_id ) {
	global $wpdb;
	$time = get_the_time( 'U' );
	
	if ( isset($today) && date( 'Y-m-d', $time ) == $today ) {
		$relative = __( 'Today' );
	} elseif ( isset($tomorrow) && date( 'Y-m-d', $time ) == $tomorrow ) {
		$relative = __( 'Tomorrow' );
	} elseif ( isset($time) && date( 'Y', $time ) !== date( 'Y', current_time( 'timestamp' ) ) ) {
		/* translators: date and time format for recent posts on the dashboard, from a different calendar year, see http://php.net/date */
		$relative = date_i18n( __( 'M jS Y' ), $time );
	} else {
		/* translators: date and time format for recent posts on the dashboard, see http://php.net/date */
		$relative = date_i18n( __( 'M jS' ), $time );
	}

	// Use the post edit link for those who can edit, the permalink otherwise.
	$recent_post_link = current_user_can( 'edit_post', get_the_ID() ) ? get_edit_post_link() : get_permalink();

	$draft_or_post_title = _draft_or_post_title();
    echo $relative.', '.get_the_time();
   
} 

function post_with_time( $filter_type = '', $post_id = '', $user_id = '' ) {
	global $wpdb;
	$time = get_the_time( 'U' );
	
	if ( isset($today) && date( 'Y-m-d', $time ) == $today ) {
		$relative = __( 'Today' );
	} elseif ( isset($tomorrow) &&  date( 'Y-m-d', $time ) == $tomorrow ) {
		$relative = __( 'Tomorrow' );
	} elseif ( isset($time) &&  date( 'Y', $time ) !== date( 'Y', current_time( 'timestamp' ) ) ) {
		/* translators: date and time format for recent posts on the dashboard, from a different calendar year, see http://php.net/date */
		$relative = date_i18n( __( 'M jS Y' ), $time );
	} else {
		/* translators: date and time format for recent posts on the dashboard, see http://php.net/date */
		$relative = date_i18n( __( 'M jS' ), $time );
	}

	// Use the post edit link for those who can edit, the permalink otherwise.
	$recent_post_link = current_user_can( 'edit_post', get_the_ID() ) ? get_edit_post_link() : get_permalink();

	$draft_or_post_title = _draft_or_post_title(); ?>
    <li>
    <span><?php echo $relative.', '.get_the_time() ?></span>
    <a href="<?php echo $recent_post_link; ?>" aria-label="Edit <?php echo $draft_or_post_title; ?>"><?php echo $draft_or_post_title; ?></a>
    <?php
    if( $filter_type == 'vote_by_user_name' ) {
		
		$option_arr = $wpdb->get_results("SELECT ques_option_id FROM ".$wpdb->prefix."hyroglf_users_voting WHERE user_article_id = '$post_id' AND user_id = '$user_id'");
		//echo '<pre>'; print_r($option_arr);
		$informative_arr = array();
		$bias_arr = array();
		foreach( $option_arr as $option ) {
			
			$option_id = $option->ques_option_id;
			
			$informative_option = $wpdb->get_results("SELECT ques_option FROM ".$wpdb->prefix."hyroglf_question_option WHERE option_id = '$option_id' AND question_id_fk = 1");
			
			if( !empty($informative_option) && $informative_option[0]->ques_option ) {
				echo '&nbsp;&nbsp;&nbsp;<span class="informative_option">'.$informative_option[0]->ques_option.'</span>';
			}
		}
		if(is_array($option_arr)){
			foreach( $option_arr as $option ) {
				
				$option_id = $option->ques_option_id;
				
				$bias_option = $wpdb->get_results("SELECT ques_option FROM ".$wpdb->prefix."hyroglf_question_option WHERE option_id = '$option_id' AND question_id_fk = 2");
				if(!empty($bias_option)){
					if( $bias_option[0]->ques_option ){
						echo '&nbsp;&nbsp;&nbsp;<span class="bias_option">'.$bias_option[0]->ques_option.'</span>';
					}
				}
			}
		}
		
	}
	?>
    </li>
	<?php
	//printf('<li><span>%1$s</span> <a href="%2$s" aria-label="%3$s">%4$s</a></li>',sprintf( _x( '%1$s, %2$s', 'dashboard' ), $relative, get_the_time() ),$recent_post_link,esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $draft_or_post_title ) ),$draft_or_post_title);	
}

function get_post_for_dashboard() { ?>
	<div id="published-posts" class="activity-block">
        <ul><?php
            $args = array(
                        'post_type'			=> 'post',
                        'post_status'		=> 'publish',
                        'posts_per_page'	=> '5'
                    );
            $the_query = new WP_Query( $args );
			
			$today    = date( 'Y-m-d', current_time( 'timestamp' ) );
			$tomorrow = date( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );
			
            while ($the_query->have_posts()) : $the_query->the_post();
                post_with_time(); ?>
            <?php 
            endwhile;
            wp_reset_postdata();
            ?>
        </ul>
	</div><?php
}

function hyroglf_add_dashboard_post_by_user() {

	wp_add_dashboard_widget(
                 'hyroglf_post_by_user',   // Widget slug.
                 'Hyroglf - Post by user name',         					// Title.
                 'hyroglf_add_dashboard_post_by_user_function'	// Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'hyroglf_add_dashboard_post_by_user', 10);

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function hyroglf_add_dashboard_post_by_user_function() {
	$users = user_list_custom_fn();
	$current_user = wp_get_current_user();
	//print_r($current_user);
	//echo '<pre>'; print_r($users);?>
	<select name="post_by_user_name" id="post_by_user_name" onchange="post_by_user_name(this.value);">
    	<!-- <option value="">Please select</option> -->
	   	<?php
       	if( $users ) {
		   foreach( $users as $user ) {
			   $selected = ($user->data->user_login == 'admin') ? 'selected' : '';
			  echo '<option value="'.$user->data->ID.'" '.$selected.'>'.$user->data->display_name.'</option>'; 
		   }
	   	} ?>
    </select>
    <script type="text/javascript">
		function post_by_user_name( dish_val ) {
			data = {
				'action': 'load_post_to_dashboard',
				'user_id': dish_val
			}
			jQuery.post(ajaxurl, data, function(response){
				jQuery(".post_by_user").html(response);
			});
		}
    </script>
    
    <div class="post_by_user">
    	<?php load_post_to_dashboard( $filter = '', $current_user->ID, $term = '', $informative = '', $bias = '' ); ?>
    </div><?php
}

/**
 * Add a widget to the dashboard.
 */ 
function hyroglf_add_dashboard_vote_by_user() {

	wp_add_dashboard_widget(
                 'hyroglf_vote_by_user',   						// Widget slug.
                 'Hyroglf - Votes by user name',         		// Title.
                 'hyroglf_add_dashboard_vote_by_user_function'	// Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'hyroglf_add_dashboard_vote_by_user', 10);

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function hyroglf_add_dashboard_vote_by_user_function() {
	$users = user_list_custom_fn();
	$current_user = wp_get_current_user(); ?>
	<select name="vote_by_user_name" id="vote_by_user_name" onchange="vote_by_user_name(this.value);">
    	<option value="">Please select</option>
	   	<?php
       	if( $users ) {
		   foreach( $users as $user ) {
			   	$selected = ($user->data->user_login == 'admin') ? 'selected' : '';
			  	echo '<option value="'.$user->data->ID.'" '.$selected.'>'.$user->data->display_name.'</option>'; 
		   }
	   	} ?>
    </select>
    
    <script type="text/javascript">
		function vote_by_user_name( dish_val ) {
			data = {
				'action' : 'load_post_to_dashboard',
				'user_id' : dish_val,
				'filter' : 'vote_by_user_name',
				'informative' : jQuery("#vote_by_user_name_informative option:selected").val(),
				'bias' : jQuery("#vote_by_user_name_bias option:selected").val(),
			}
			jQuery.post(ajaxurl, data, function(response){
				jQuery(".vote_by_user_name").html(response);
			});
		}		
    </script>
    <div class="vote_by_user_name">
    	<?php load_post_to_dashboard( $filter = 'vote_by_user_name', $current_user->ID, $term = '', $informative = '', $bias = '' ); ?>
		<?php //get_post_for_dashboard(); ?>
    </div><?php
}

/**
 * Add a widget to the dashboard.
 */
function hyroglf_add_dashboard_post_by_category() {

	wp_add_dashboard_widget(
                 'hyroglf_post_by_category',   						// Widget slug.
                 'Hyroglf - Posts by category',         			// Title.
                 'hyroglf_add_dashboard_post_by_category_function'	// Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'hyroglf_add_dashboard_post_by_category', 10);

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function hyroglf_add_dashboard_post_by_category_function() {
	$terms = get_terms('category');
	if( $terms ) { ?>
		<select name="post_by_catogry" id="post_by_catogry" onchange="post_by_category(this.value);">
			<option value="">Please select</option>
		<?php
		foreach( $terms as $term ) {
			if( $term->count > 0 ) {
				$selected = ($term->slug == 'news') ? 'selected' : '';
				echo '<option value="'.$term->slug.'" '.$selected.'>'.$term->name.'</option>';
			}
		} ?>
		</select>
		<script type="text/javascript">
			function post_by_category( dish_val ) {
				data = {
					'action': 'load_post_to_dashboard',
					'term': dish_val
				}
				jQuery.post(ajaxurl, data, function(response){
					jQuery(".post_by_category").html(response);
				});
			}
        </script>
		<?php
	} ?>
    <div class="post_by_category">
    	<?php load_post_to_dashboard( $filter = '', $user_id = '', $terms[0]->slug, $informative = '', $bias = '' ); ?>
		<?php //get_post_for_dashboard(); ?>
	</div><?php
}

/**
 * Add a widget to the dashboard.
 */
function hyroglf_add_dashboard_vote_by_category() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget(
                 'hyroglf_vote_by_category',   						// Widget slug.
                 'Hyroglf - Votes by category',         			// Title.
                 'hyroglf_add_dashboard_vote_by_category_function'	// Display function.
        );
}
add_action( 'wp_dashboard_setup', 'hyroglf_add_dashboard_vote_by_category', 10);

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function hyroglf_add_dashboard_vote_by_category_function() {
	$terms = get_terms('category');
	if( $terms ) { ?>
		<select name="vote_by_catogry" id="vote_by_catogry" onchange="vote_by_catogry(this.value);">
			<option value="">Please select</option><?php
		foreach( $terms as $term ) {
			if( $term->count > 0 ) {
				$selected = ( $term->slug == 'news' ) ? 'selected' : '';
				echo '<option value="'.$term->slug.'" '.$selected.'>'.$term->name.'</option>';
			}
		} ?>
		</select>
		<script type="text/javascript">
			function vote_by_catogry( dish_val ) {
				data = {
					'action' : 'load_post_vote_by_category',
					'post_per_page' : 5,
					'term' : dish_val,
					'type' : 'ajax_filter_in_dashboard',
					'load' : 'dashboard'
				}
				jQuery.post(ajaxurl, data, function(response){
					jQuery(".vote_by_category_post_list").html(response);
					
					post = jQuery("#vote_by_cat_hidden_posts").val();
					if( post > 2 ){
						term = jQuery("#vote_by_cat_hidden_posts_term").val();
						content = '<a href="<?php echo admin_url('/admin.php?page=votes_category&term='); ?>'+term+'" class="see_more_posts">See more</a>';
						//jQuery(".post_view_more").html(content);
					}
					
				});
			}
        </script>
		<?php
	} ?>
    
    <table class="wp-list-table widefat fixed striped posts">
		<?php
        $type = 'dashboard';
        $load = 'dashboard';
        $post_per_page = 5;
        $taxonomy = 'category';
        $term = $terms[0]->slug;
        ?>
        
        <?php get_vote_by_cat_posts_head( $load ); ?>
        
        <?php get_vote_by_cat_posts_body( $type, $load, $post_per_page, $taxonomy, $term ); ?>                    
        
    </table>
    
    <div class="post_view_more"></div>
    
    <?php
}
add_action('admin_menu', 'my_menu');

function my_menu() {
    add_submenu_page(
        'edit.php', // parent 
        'Votes by category', // Name
        'Votes by category', // Name
        'manage_options', // Edit option
        'votes_category', // slug
        'wpvotes_by_category' // Callback function
    );

}


add_action( 'wp_ajax_load_post_vote_by_category', 'load_post_vote_by_category' );
add_action( 'wp_ajax_nopriv_load_post_vote_by_category', 'load_post_vote_by_category' );

function load_post_vote_by_category() {
	$type = ( isset( $_POST['type'] ) ) ? $_POST['type'] : '';
	$load = ( isset( $_POST['load'] ) ) ? $_POST['load'] : '';
	$post_per_page = ( isset( $_POST['post_per_page'] ) ) ? $_POST['post_per_page'] : '';
	$taxonomy = 'category';
	$term = ( isset( $_POST['term'] ) ) ? $_POST['term'] : '';
	
	get_vote_by_cat_posts( $type, $load, $post_per_page, $taxonomy, $term );
	
}

function get_vote_by_cat_posts(  $type = '', $load = '', $post_per_page = '', $taxonomy = '', $term = '' ) {
	global $wpdb;
	if( $term ) {
		$args = array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'posts_per_page'	=> $post_per_page,
			'orderby'			=> 'date',
			'order'				=> 'desc',
			'tax_query' 		=> array(
									array(
										'taxonomy'	=> $taxonomy,
										'field'		=> 'slug',
										'terms'		=> array($term)
									)
								)
		);
	} else {
		$args = array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'posts_per_page'	=> $post_per_page,
			'orderby'			=> 'date',
			'order'				=> 'desc',
		);
	}
	
	$the_query = new WP_Query( $args );
	$total_posts = $the_query->found_posts;
	if( $the_query->have_posts() ) {
		$option_id_count = $wpdb->get_results("SELECT option_id FROM ".$wpdb->prefix."hyroglf_question_option");
		while ( $the_query->have_posts() ) : $the_query->the_post(); global $post;
			$post_id = $post->ID;
			if($option_id_count) {
				$option_voting_post = array();
				foreach($option_id_count as $option) {
					$option_id = $option->option_id;
					$option_key = $wpdb->get_var("SELECT ques_option FROM ".$wpdb->prefix."hyroglf_question_option WHERE option_id = '$option_id'");
					$option_voting_post[$option_key] = $wpdb->get_var("
													SELECT COUNT(ques_option_id) AS count_id
													FROM ".$wpdb->prefix."hyroglf_users_voting
													WHERE ques_option_id = '$option_id'
													AND user_article_id='$post_id'
													");
				}
			}
			//echo '<pre>'; print_r($option_voting_post); ?>
            <tr>
            	<?php
				//if( $load == 'page' ) { ?>
                <th scope="col" id="date" class="manage-column"><span><?php get_post_time_custom_fn( $post->ID ); ?></span><span class="sorting-indicator"></span></th><?php
				//} ?>
                <th scope="col" id="name" class="manage-column"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></th>
                <th scope="col" id="informative" class="manage-column">
                    <table>
                        <tr>
                            <td class="manage-column column_very"><?php echo $option_voting_post['very']; ?></td>
                            <td class="manage-column column_somewhat"><?php echo $option_voting_post['somewhat']; ?></td>
                            <td class="manage-column column_not_really"><?php echo $option_voting_post['not_really']; ?></td>
                        </tr>
                    </table>
                </th>
                <th scope="col" id="bias" class="manage-column">
                    <table>
                        <tr>
                            <td class="manage-column column_liberal"><?php echo $option_voting_post['liberal']; ?></td>
                            <td class="manage-column column_neutral"><?php echo $option_voting_post['neutral']; ?></td>
                            <td class="manage-column column_conservative"><?php echo $option_voting_post['conservative']; ?></td>
                        </tr>
                    </table>
                </th>
            </tr><?php
		endwhile; wp_reset_query();
		
		if( $total_posts > 5 ) { ?>
        <tr><td><a href="<?php echo admin_url('/admin.php?page=votes_category&term='.$term); ?>" class="see_more_posts">See more</a></td></tr>
        <?php
		}
		
		if( $type == 'ajax_filter_in_dashboard' ) {
			vote_by_cat_hidden_inputs( $total_posts, $term );
		}
		
	} ?>
	
<?php	
}

function vote_by_cat_hidden_inputs(  $total_posts = '', $term = ''  ) { ?>
	<input type="hidden" name="vote_by_cat_hidden_posts" id="vote_by_cat_hidden_posts" value="<?php echo $total_posts; ?>" />
    <input type="hidden" name="vote_by_cat_hidden_posts_term" id="vote_by_cat_hidden_posts_term" value="<?php echo $term; ?>" />
<?php	
}

function get_vote_by_cat_posts_head( $load = '' ) { ?>
	<thead>
        <tr>
        	<?php
			//if( $load == 'page' ) {?>
            <th scope="col" id="date" class="manage-column"><span>Date</span><span class="sorting-indicator"></span></th><?php
			//} ?>
            <th scope="col" id="name" class="manage-column"><span>Name</span><span class="sorting-indicator"></span></th>
            <th scope="col" id="informative" class="manage-column">
                <table>
                    <tr>
                        <td class="manage-column" colspan="3">Informative</td>
                    </tr>
                    <tr>
                        <td class="manage-column">very</td>
                        <td class="manage-column">somewhat</td>
                        <td class="manage-column">not really</td>
                    </tr>
                </table>
            </th>
            <th scope="col" id="bias" class="manage-column">
                <table>
                    <tr>
                        <td class="manage-column" colspan="3">Bias</td>
                    </tr>
                    <tr>
                        <td class="manage-column">liberal</td>
                        <td class="manage-column">neutral</td>
                        <td class="manage-column">conservative</td>
                    </tr>
                </table>
            </th>
        </tr>
    </thead>
<?php	
}

function get_vote_by_cat_posts_body( $type = '', $load = '', $post_per_page = '', $taxonomy = '', $term = '' ) { ?>
	<style>
		#hyroglf_vote_by_category h2.hndle {
			margin: 0;
			padding: 8px 12px;
		}
		select#vote_by_catogry {
			margin-bottom: 20px;
		}
		th > table {
			width: 100%;
		}
	</style>
	<tbody class="vote_by_category_post_list">
		<?php get_vote_by_cat_posts( $type, $load, $post_per_page, $taxonomy, $term ); ?>
    </tbody><?php
}

function wpvotes_by_category() { ?>
   	<div class="wrap">
   		<h1>Posts</h1>
        <form id="posts-filter" method="post">
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <label class="screen-reader-text" for="bulk-action-selector-top">Select category</label><?php
                    $terms = get_terms('category');
                    if( $terms ) { ?>
                        <select name="vote_by_catogry" id="vote_by_catogry" onchange="vote_by_catogry_page(this.value);">
							<option value="">Please select</option><?php
                        foreach( $terms as $term ) {
                            if( $term->count > 0 ) { ?>
                                <option value="<?php echo $term->slug; ?>" <?php echo ( $_GET['term'] == $term->slug ) ? 'selected' : ''; ?> ><?php echo $term->name; ?></option><?php
                            }
                        } ?>
                        </select>
						<script type="text/javascript">
							function vote_by_catogry_page( dish_val ) {
								data = {
									'action' : 'load_post_vote_by_category',
									'post_per_page' : -1,
									'term' : dish_val,
									'type' : 'ajax_filter_in_page',
									'load' : 'page'
								}
								jQuery.post(ajaxurl, data, function(response){
									jQuery(".vote_by_category_post_list").html(response);
								});
							}
						</script>
						<?php
                    } ?>
                    <!--<input id="doaction" class="button action" type="submit" value="Apply">-->
                </div>
            </div>
            <h2 class='screen-reader-text'>Categories list</h2>
            <table class="wp-list-table widefat fixed striped posts">
                <?php
                $type = 'init';
                $load = 'page';
                $post_per_page = -1;
                $taxonomy = 'category';
                $term = ( isset( $_GET['term'] ) ? $_GET['term'] : '' );
                ?>
                
                <?php get_vote_by_cat_posts_head( $load ); ?>
                
                <?php get_vote_by_cat_posts_body( $type, $load, $post_per_page, $taxonomy, $term ); ?>                    
                
            </table>
         </form>
         
  </div><?php	
}

####################### Dashboard actions end ##########################################

####################### Custom columns in custom posts ##########################################

/* Display custom column */
function flag_as_duplicate_column_values( $column, $post_id ) {
    switch ( $column ) {
		case 'flag_as_duplicate':
			$flag_as_duplicate = get_post_meta( $post_id, 'flag_as_duplicate', true );
			if($flag_as_duplicate) {
				echo $flag_as_duplicate;
			}
			break;

		case 'featured_logo':
			echo 'ssss'; 
			break;
		
		case 'is_featured_and_date':
			echo 'Yes'; 
			break;
	}
}
add_action( 'manage_post_posts_custom_column' , 'flag_as_duplicate_column_values', 10, 2 );

/* Display custom column */
function is_featured_and_date_filter( $column, $post_id ) {
    switch ( $column ) {

		case 'featured_logo':
			the_post_thumbnail('162_85_img');
			break;
		
		case 'is_featured_and_date':
			$featured_logo = get_post_meta( $post_id, 'featured_logo', true );
			if($featured_logo) {
				echo 'yes & '.get_the_date( 'd-m-Y', $post_id );
			}
			break;
	}
}
add_action( 'manage_advertisement_logo_posts_custom_column' , 'is_featured_and_date_filter', 10, 2 );

/* Add custom column to post list */
function flag_as_duplicate_column( $columns ) {
	$columns['flag_as_duplicate'] = 'Flag as duplicate';
	return $columns;
}
add_filter( 'manage_post_posts_columns' , 'flag_as_duplicate_column' );

/* Add custom column to advertisement_logo list */
function is_featured_and_date( $columns ) {
	unset($columns['date']);
	$columns['featured_logo'] = 'Logo';
	$columns['is_featured_and_date'] = 'Is featured & Date';
	return $columns;
}
add_filter( 'manage_advertisement_logo_posts_columns' , 'is_featured_and_date' );

####################### Custom columns in custom posts end ##########################################