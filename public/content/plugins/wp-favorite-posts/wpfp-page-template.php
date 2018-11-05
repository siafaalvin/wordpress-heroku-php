<?php
    $wpfp_before = "";
    echo "<div class='wpfp-span'>";
    if (!empty($user)) {
        if (wpfp_is_user_favlist_public($user)) {
            $wpfp_before = "$user's Favorite Posts.";
        } else {
            $wpfp_before = "$user's list is not public.";
        }
    }

    if ($wpfp_before):
        echo '<div class="wpfp-page-before">'.$wpfp_before.'</div>';
    endif;
	
	$isnotempty = true;
    if ($favorite_post_ids) {
		$favorite_post_ids = array_reverse($favorite_post_ids);
        $post_per_page = wpfp_get_option("post_per_page");
        $page = intval(get_query_var('paged'));
        $qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
        // custom post type support can easily be added with a line of code like below.
        // $qry['post_type'] = array('post','page');
        query_posts($qry);
        
        if(have_posts()) {
			$isnotempty = true;
			echo "<ul>";
				while ( have_posts() ) : the_post();
					global $post;
					$category_name=get_the_category();
					$content_data = get_post_meta($post->ID); ?>
					<div class="favorites-post"><?php
						if($content_data['post_ref_link_favicon'][0]){?>
							<img src="<?php echo $content_data['post_ref_link_favicon'][0]; ?>"><?php // favicon image from referencce link
						} ?>
						<div class="post_content_title">
							<h3><?php  echo "<a href='".get_permalink()."' title='". get_the_title() ."'>" . get_the_title() . "</a> "."<br/>"; ?></h3>
						</div><?php
						if($content_data['reference_link'][0]){?>
							<a href="<?php echo $content_data['reference_link'][0]; ?>" class="source_link"><?php // Reference link display
							echo $content_data['reference_link'][0]; ?></a><?php 
						}
								   
						foreach($category_name as $cat){
							if($cat->cat_name != 'Other'){
								echo 'Category:'.'  '."<a href=".$category_link = get_category_link( $cat ).">".$cat->cat_name."</a><br/>";
							}
						}
						
						echo get_the_content()."<br>";
						
						$post_modified = strtr($post->post_modified, '/', '-');
						echo '<span class="last_modified"><b>Last modified on </b>'.date('F d, Y', strtotime($post_modified))."</span><br>";
						
						wpfp_remove_favorite_link(get_the_ID());?>
				   </div><?php
				endwhile;
        	echo "</ul>";
		}

        echo '<div class="navigation">';
            if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
            <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
            <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>
            <?php }
        echo '</div>';

        wp_reset_query();
    } else {
        $wpfp_options = wpfp_get_options();
        echo "<ul><li>";
        echo $wpfp_options['favorites_empty'];
        echo "</li></ul>";
		$isnotempty = false;
    }
	if($isnotempty) {
		echo '<p>'.wpfp_clear_list_link().'</p>';
	}
    
    echo "</div>";
    wpfp_cookie_warning();
